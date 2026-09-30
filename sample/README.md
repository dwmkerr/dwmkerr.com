# Jev vs LLMs — fraud scan

Artificial, labelled transactions run through Jev (and LLMs); prints accuracy /
tokens / cost / time. Labels only score results — the models never see them.

```sh
node generate.mjs 120                 # write data.json (120 labelled txns)
node run.mjs --mock                   # no keys, shows the table
TYPESAFE_API_KEY=... node run.mjs     # real Jev; add OPENROUTER_API_KEY for LLMs
```

Jev runs as one fan-out call; LLMs run one call per transaction.
Raw per-transaction output → `results.json`.
