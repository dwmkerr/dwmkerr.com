#!/usr/bin/env node
// Write data.json with N artificial, labelled transactions - a mix of clear-cut
// and deliberately ambiguous cases so accuracy actually discriminates.
//   node generate.mjs 80
import fs from 'node:fs';
const N = Number(process.argv[2] || 80);
let s = 42; const rnd = () => (s = (s * 1103515245 + 12345) & 0x7fffffff) / 0x7fffffff;
const pick = (a) => a[Math.floor(rnd() * a.length)];
const amt = (lo, hi) => Math.round((lo + rnd() * (hi - lo)) / 10) * 10;
const hr = () => Math.floor(rnd() * 24);

const context = {
  todays_scam_patterns: [
    "'safe account' scam: a caller poses as the bank and urges moving funds to a new 'safe' account",
    "private vehicle sale: urgent same-day payment to a brand-new payee, no prior relationship",
    "crypto/FX 'investment' promising guaranteed returns, funds sent to a new payee",
    "invoice redirection: a known supplier's bank details 'change' at the last minute",
    "advance-fee: pay a small fee to release a larger refund, prize or parcel",
  ],
  flagged_payees_24h: ["QuickCoin Ltd", "J. Marku", "Sterling Yield FX", "AC-2291"],
};

// Each template returns the transaction fields (label + difficulty baked in).
const EASY_SCAM = [
  () => ({ d: 'easy', payee: pick(['Quick Coin Limited', 'Sterling Yield FX', 'Apex Returns']), new_payee: true, hour: pick([1, 2, 23]), reason: 'urgent investment with guaranteed monthly returns, must act now', note: 'new payee, urgency, matches flagged list', amount: amt(2000, 15000) }),
  () => ({ d: 'easy', payee: pick(['Barclays Safe Account', 'Account Protection Team']), new_payee: true, hour: hr(), reason: 'the bank called and told me to move my money to a safe account right now', note: 'classic safe-account script', amount: amt(3000, 9000) }),
  () => ({ d: 'easy', payee: pick(['Prize Claims Ltd', 'Parcel Redelivery']), new_payee: true, hour: hr(), reason: 'small fee to release my prize / parcel', note: 'advance-fee', amount: amt(20, 300) }),
];
const EASY_LEGIT = [
  () => ({ d: 'easy', payee: pick(['Tesco', 'Sainsburys', 'Deliveroo']), new_payee: false, hour: hr(), reason: 'groceries / food', note: '', amount: amt(10, 120) }),
  () => ({ d: 'easy', payee: pick(['Highland Lettings', 'The Landlord']), new_payee: false, hour: pick([8, 9, 10]), reason: 'monthly rent', note: 'regular payee', amount: amt(700, 1800) }),
  () => ({ d: 'easy', payee: pick(['British Gas', 'Octopus Energy', 'EE']), new_payee: false, hour: hr(), reason: 'utility bill', note: 'regular', amount: amt(30, 200) }),
  () => ({ d: 'easy', payee: pick(['Aviva', 'Admiral']), new_payee: false, hour: hr(), reason: 'annual insurance', note: 'expected', amount: amt(200, 1000) }),
  () => ({ d: 'easy', payee: pick(['HMRC', 'Nationwide Mortgage']), new_payee: false, hour: hr(), reason: 'expected tax / mortgage payment', note: 'known payee, expected', amount: amt(500, 5000) }),
];
// Hard SCAMs: genuinely fraudulent but the surface looks calm / plausible.
const HARD_SCAM = [
  () => ({ d: 'hard', payee: pick(['Apex Builders Ltd', 'Verified Supplier Co']), new_payee: false, hour: pick([10, 11, 14]), reason: 'settling this month\'s invoice - they emailed updated bank details this morning', note: 'existing payee, details changed today; calm tone', amount: amt(2000, 9000) }),
  () => ({ d: 'hard', payee: pick(['M. Roberts', 'Handy Trades']), new_payee: true, hour: pick([12, 13, 16]), reason: 'paying the tradesman for the kitchen work, he asked to be paid today so he can buy materials', note: 'plausible cover story, new payee', amount: amt(1500, 6000) }),
  () => ({ d: 'hard', payee: pick(['Sterling Yield FX', 'Meridian Capital']), new_payee: true, hour: pick([9, 15]), reason: 'moving savings into the managed fund my adviser recommended', note: 'measured tone but new payee on the flagged theme', amount: amt(5000, 20000) }),
  () => ({ d: 'hard', payee: 'Barclays Holding', new_payee: true, hour: pick([13, 15, 17]), reason: 'transferring to the new account the bank set up for me after the fraud check', note: 'safe-account, unusually calm', amount: amt(2000, 8000) }),
];
// Hard LEGIT: genuinely fine, but carries scam-shaped red flags (new payee, large, urgent).
const HARD_LEGIT = [
  () => ({ d: 'hard', payee: pick(['Kingsley Solicitors LLP', 'Marwood Conveyancing']), new_payee: true, hour: pick([10, 11, 14]), reason: 'completion funds for our house purchase, going to the solicitor client account', note: 'new payee, very large, but a genuine house completion', amount: amt(25000, 60000) }),
  () => ({ d: 'hard', payee: pick(['Vale Vets Emergency', 'PDSA Clinic']), new_payee: true, hour: pick([20, 22, 2]), reason: 'emergency surgery for our dog tonight, the vet needs payment now', note: 'urgent, new payee, odd hour - but legitimate', amount: amt(800, 3500) }),
  () => ({ d: 'hard', payee: pick(['Coinbase', 'Kraken']), new_payee: true, hour: hr(), reason: 'topping up my own account on the exchange I already use', note: 'crypto + new payee, but a mainstream regulated exchange', amount: amt(200, 2000) }),
  () => ({ d: 'hard', payee: pick(['Pendle Motors Ltd', 'Riverside Car Sales']), new_payee: true, hour: pick([11, 15]), reason: 'balance for a car I bought from a dealership, paying by bank transfer', note: 'large, new payee - but an established dealership, not a private seller', amount: amt(8000, 22000) }),
  () => ({ d: 'hard', payee: 'Handy Builders Co', new_payee: true, hour: pick([9, 16]), reason: 'first invoice for the loft conversion, quote and contract signed last week', note: 'new payee, large - but a vetted contractor with paperwork', amount: amt(3000, 12000) }),
];

const roll = () => {
  const r = rnd();
  if (r < 0.28) return { pool: EASY_SCAM, scam: true };
  if (r < 0.50) return { pool: EASY_LEGIT, scam: false };
  if (r < 0.75) return { pool: HARD_SCAM, scam: true };
  return { pool: HARD_LEGIT, scam: false };
};

const transactions = [];
for (let i = 0; i < N; i++) {
  const { pool, scam } = roll();
  const t = pick(pool)();
  transactions.push({ id: 't' + String(i + 1).padStart(3, '0'), amount_gbp: t.amount, payee: t.payee, new_payee: t.new_payee, hour: t.hour, reason: t.reason, note: t.note, difficulty: t.d, scam });
}
fs.writeFileSync(new URL('./data.json', import.meta.url), JSON.stringify({ context, transactions }, null, 1));
const n = (f) => transactions.filter(f).length;
console.log(`wrote ${transactions.length} transactions - ${n((t) => t.scam)} scam, ${n((t) => t.difficulty === 'hard')} hard (ambiguous)`);
