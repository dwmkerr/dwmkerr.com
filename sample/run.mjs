#!/usr/bin/env node
// Compare Jev vs LLMs on artificial fraud data. No dependencies (Node 18+).
//   node run.mjs --mock                 # no keys, fake responses
//   TYPESAFE_API_KEY=... node run.mjs   # real Jev; add OPENROUTER_API_KEY for the LLMs
//   N=50 node run.mjs                   # first N transactions
//   JEV_CHUNK=50 node run.mjs           # split Jev fan-out into chunks of 50 questions
// Raw per-transaction results are written to results.json.
import fs from 'node:fs';

const MOCK = process.argv.includes('--mock');
const LIMIT = Number(process.env.N || 0);
const CHUNK = Number(process.env.JEV_CHUNK || 0);

const MODELS = [
  { name: 'jev',    kind: 'jev',        model: 'jev-latest' },
  { name: 'sonnet', kind: 'openrouter', model: 'anthropic/claude-sonnet-5.5' },
  { name: 'luna',   kind: 'openrouter', model: 'openai/gpt-6-luna' },
  { name: 'astra',  kind: 'openrouter', model: 'openai/gpt-6-astra' },
  { name: 'opus',   kind: 'openrouter', model: 'anthropic/claude-opus-5.5' },
];

// Fallback prices $/MTok [in, out]; OpenRouter's returned cost wins when present.
const PRICE = {
  'jev-latest': [0.042, 0],
  'anthropic/claude-sonnet-5.5': [3, 15],
  'anthropic/claude-opus-5.5': [15, 75],
  'openai/gpt-6-astra': [5, 20],
  'openai/gpt-6-luna': [1, 4],
};

const { context: ctx, transactions } = JSON.parse(
  fs.readFileSync(new URL('./data.json', import.meta.url)));
const txns = LIMIT ? transactions.slice(0, LIMIT) : transactions;

const describe = (t) =>
  `£${t.amount_gbp} to '${t.payee}'${t.new_payee ? ' (new payee)' : ''} at ${String(t.hour).padStart(2, '0')}:00. `
  + `Reason: ${t.reason}.${t.note ? ' ' + t.note + '.' : ''}`;
const predScam = (choice) => choice === 'high';
const now = () => Number(process.hrtime.bigint() / 1000000n);
const env = (k) => { const v = process.env[k]; if (!v && !MOCK) throw new Error(`missing $${k}`); return v; };
const costOf = (model, i, o) => { const [pi, po] = PRICE[model] || [0, 0]; return (i * pi + o * po) / 1e6; };
const rng = (seed) => { let s = seed || 1; return () => (s = (s * 1103515245 + 12345) & 0x7fffffff) / 0x7fffffff; };
const qFor = (t) => ({ type: 'choice', instructions: describe(t), criteria: { safe: 'clearly fine', low: 'slightly suspicious', high: 'likely a scam' } });

// Jev: fan-out - one question per transaction, shared context read once (optionally chunked).
async function runJev(model) {
  const chunk = CHUNK || txns.length;
  let iTok = 0, oTok = 0, ms = 0, calls = 0; const raw = [];
  for (let off = 0; off < txns.length; off += chunk) {
    const batch = txns.slice(off, off + chunk);
    const questions = {}; for (const t of batch) questions[t.id] = qFor(t);
    const body = { state: ctx, model, questions };
    const t0 = now(); let answers, usage;
    if (MOCK) ({ answers, usage } = mockJev(body, batch));
    else {
      const r = await fetch('https://api.typesafe.ai/v1/systemone', {
        method: 'POST',
        headers: { 'content-type': 'application/json', authorization: `Bearer ${env('TYPESAFE_API_KEY')}` },
        body: JSON.stringify(body),
      });
      const j = await r.json();
      if (!r.ok) throw new Error(`${r.status} ${JSON.stringify(j).slice(0, 140)}`);
      ({ answers, usage } = j);
    }
    ms += now() - t0; calls++;
    iTok += usage?.input_tokens || 0; oTok += usage?.output_tokens || 0;
    for (const t of batch) { const a = answers[t.id]; raw.push({ id: t.id, choice: a.choice, probabilities: a.probabilities, confidence: a.confidence }); }
  }
  const preds = raw.map((r) => predScam(r.choice));
  return { preds, ms, iTok, oTok, cost: costOf(model, iTok, oTok), calls, raw };
}

// LLMs: one call per transaction (no fan-out) - the honest comparison.
async function runLLM(model) {
  let iTok = 0, oTok = 0, cost = 0, ms = 0, err = null; const raw = [];
  for (const t of txns) {
    try {
      const prompt = `Today's fraud intel:\n${JSON.stringify(ctx)}\n\nTransaction:\n${describe(t)}\n\nClassify scam risk. Reply with exactly one word: safe, low, or high.`;
      const t0 = now(); let choice, u = {}, c = null;
      if (MOCK) ({ choice, u, c } = mockLLM(model, t, prompt));
      else {
        const r = await fetch('https://openrouter.ai/api/v1/chat/completions', {
          method: 'POST',
          headers: { 'content-type': 'application/json', authorization: `Bearer ${env('OPENROUTER_API_KEY')}` },
          body: JSON.stringify({ model, messages: [{ role: 'user', content: prompt }], max_tokens: 300, reasoning: { effort: 'low' }, usage: { include: true } }),
        });
        const j = await r.json();
        if (!r.ok) throw new Error(`${r.status} ${JSON.stringify(j).slice(0, 140)}`);
        const text = (j.choices?.[0]?.message?.content || '').toLowerCase();
        choice = ['high', 'low', 'safe'].find((w) => text.includes(w)) || 'unknown';
        u = j.usage || {}; c = u.cost ?? null;
      }
      ms += now() - t0;
      iTok += u.prompt_tokens || 0; oTok += u.completion_tokens || 0;
      cost += c != null ? c : costOf(model, u.prompt_tokens || 0, u.completion_tokens || 0);
      raw.push({ id: t.id, choice });
    } catch (e) { err = String(e.message || e); break; }
  }
  return { preds: raw.map((r) => predScam(r.choice)), ms, iTok, oTok, cost, calls: raw.length, raw, err };
}

// ---- mock responses (biased toward the truth so the table looks plausible) ----
function mockJev(body, batch) {
  const rand = rng(11); const answers = {};
  for (const t of batch) {
    const right = rand() < 0.82; const choice = right ? (t.scam ? 'high' : 'safe') : (t.scam ? 'low' : 'high');
    answers[t.id] = { type: 'choice', choice, probabilities: { safe: 0.2, low: 0.2, high: 0.6 }, confidence: 0.7 };
  }
  return { answers, usage: { input_tokens: Math.round(JSON.stringify(body).length / 4), output_tokens: 0 } };
}
function mockLLM(model, t, prompt) {
  const rand = rng(model.length * 7 + t.id.charCodeAt(1) + t.id.charCodeAt(3));
  const acc = { 'anthropic/claude-opus-5': 0.9, 'anthropic/claude-sonnet-5': 0.83 }[model] || 0.8;
  const right = rand() < acc;
  return { choice: right ? (t.scam ? 'high' : 'safe') : (t.scam ? 'safe' : 'high'), u: { prompt_tokens: Math.round(prompt.length / 4), completion_tokens: 1 }, c: null };
}

// ---- run + table + raw dump ----
const usd = (n) => '$' + (n < 0.01 ? n.toFixed(4) : n.toFixed(2));
const cell = (c) => c.map((x, i) => (i === 0 ? String(x).padEnd(7) : String(x).padStart(9))).join(' ');

(async () => {
  const truth = txns.map((t) => t.scam);
  const out = { generated_at: new Date().toISOString(), count: txns.length, mock: MOCK, transactions: txns.map((t) => ({ id: t.id, scam: t.scam })), models: {} };
  console.log(`\nFraud scan - ${txns.length} transactions${MOCK ? ' (MOCK - no live calls)' : ''}\n`);
  console.log(cell(['model', 'scanned', 'correct', 'acc%', 'in_tok', 'out_tok', 'cost', 'total_s', 'avg_ms', 'calls']));
  for (const m of MODELS) {
    try {
      const r = m.kind === 'jev' ? await runJev(m.model) : await runLLM(m.model);
      const scanned = r.preds.length;
      const valid = (c) => c === 'safe' || c === 'low' || c === 'high';
      const isCorrect = (c, t) => valid(c) && ((c === 'high') === t);
      const correct = r.raw.reduce((a, x, i) => a + (isCorrect(x.choice, truth[i]) ? 1 : 0), 0);
      out.models[m.name] = { model: m.model, scanned, calls: r.calls, ms: r.ms, in_tokens: r.iTok, out_tokens: r.oTok, cost_usd: r.cost, correct, accuracy: scanned ? correct / scanned : 0, error: r.err || undefined,
        per_txn: r.raw.map((x, i) => ({ ...x, pred_scam: x.choice === 'high', correct: isCorrect(x.choice, truth[i]) })) };
      console.log(cell([m.name, scanned, correct, scanned ? ((correct / scanned) * 100).toFixed(0) : '-',
        r.iTok.toLocaleString('en-GB'), r.oTok.toLocaleString('en-GB'), usd(r.cost), (r.ms / 1000).toFixed(2), (r.ms / (r.calls || 1)).toFixed(0), r.calls]));
      if (r.err) console.log('        partial (stopped): ' + r.err.slice(0, 60));
    } catch (e) {
      out.models[m.name] = { model: m.model, error: String(e.message || e) };
      console.log(cell([m.name, '-', '-', '-', '-', '-', '-', '-', '-', 'ERR']) + '  ' + String(e.message || e).slice(0, 60));
    }
  }
  fs.writeFileSync(new URL('./results.json', import.meta.url), JSON.stringify(out, null, 1));
  console.log(`\nraw results -> results.json\n`);
})();
