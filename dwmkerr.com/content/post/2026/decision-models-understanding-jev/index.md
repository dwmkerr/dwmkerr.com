---
author: Dave Kerr
type: posts
date: "2026-09-29"
title: "Understanding Jev and System 1/Decision Models"
description: "What TypeSafe's 'Jev' and the new class of 'system 1' (or 'decision models') actually are, whether you should care as an exec, some potential use-cases"
slug: understanding-jev-decision-models
categories:
- "ai"
- "agentic-ai"
tags:
- "ai"
- "agentic-ai"
---

I've been travelling in the mountains for a couple of weeks, over which time there has been much hype on the release of "Jev", the concept of "System 1 Models" (also described as "Decision Models"). Being disconnected from technology while in the hills has been lovely but this is the one topic I was itching to get my hands into and do a short non-tech and tech overview on.

Typesafe AI[^typesafe] released a new type of model called "Jev" which they described as a "System 1 model" - very fast and cheap at _quick decisions and judgement_ and suitable for tasks that need fast and programmatic output. If their Release Announcement[^jev] doesn't entirely make sense then please read on. I'll try and explain what it means, whether you should care, show a fun example of a decision models playing Space Invaders very quickly (versus a regular LLM playing a more complex Space Invaders more strategically) and then demo a potentially more realistic use-case for financial institutions around rapid potential fraud checks.

Hopefully by the end of this article you can re-read the launch announcement and it might make more sense.

As a delightful hook, enjoy the gif below of Jev vs Opus in gaming, and Jev quickly making judgements on fraud.

TODO space invaders gif

TODO fraud gif

## Refresher - Completion Models

At this point, "System 1 model", "Decision model" and "Jev" are being used pretty much interchangeably[^naming]. Jev is the particular product that TypeSafe have released (whether it is truly novel we'll get to). "System 1 model" is what they call it, and "Decision model" is what others seem to prefer.

First a brief review of what a regular large-language completion model like ChatGPT is. LLMs are trained on vast amounts of data and essentially encode the semantics of language. The training data includes huge amount of human knowledge and opinion (think the beauty of wikipedia and the horror of some social media). They are very good at modeling patterns in language and can give very compelling answers to complex questions.

There's an interactive visual that is super simplified on how an LLM works that I published here:

TODO pls make this a gif that links:

[![What is a Large Language Model? - a screenshot from my interactive guide, LLMs Visualised](images/llms-visualised.png)](https://dwmkerr.github.io/llms-visualised/)

*Words and concepts positioned by meaning - a frame from my interactive guide, [LLMs Visualised](https://github.com/dwmkerr/llms-visualised).*

Given input, an LLM provides the most statistically likely response based on how it is trained (its parameters):

```text
Input: What is the capital of France?
Output: Paris
```

These are completion models - they complete text. Conversational models have been further trained to answer in a conversational format (this is where the 'Chat' in 'ChatGPT' comes from):

```text
Input: What is the capital of France?
Output: Paris - a beautiful and famous city. What would you like to know about Paris - are you visiting, should I build you an itinerary? Or just curious?
```

[ref, brief note that it might also say "Paris might excite you - but not for the reason you think" if a model is overly trained to over-use certain idioms and link to the 'editor' repo]

Training models takes vast amount of data and compute and is hugely expensive. Running them is expensive, so model providers charge based on the size of input and the amount of output. Output is normally more expensive. Better quality models are more expensive, faster output is usually more expensive and there are a raft of options to choose from [ref - frontier labs, smaller labs, open weights, self hosted, whatever].

## Decision Models vs Completion Models

A 'decision model' does not complete text. You hand it some state (context or information) and one or more typed **questions**[^primitives], and for each it gives an answer or judgement in the form of:

- a **noul** - the probability, from 0 to 1, that a statement is true;
- a **choice** - one option from a set you define, with a probability for *every* option and an overall confidence;
- a **score** - a position on a rubric you define, again with probabilities and a confidence.

Here's a trivial example:

**Input**

```json
{
  "state": "The capital of France is Paris.",
  "model": "jev-latest",
  "questions": {
    "is_true": { "type": "noul", "instructions": "Is this statement true?" }
  }
}
```

**Output**

```json
{
  "answers": {
    "is_true": { "type": "noul", "noul": 0.99 }
  }
}
```

Based on how the model was trained, and the input you provided, the judgement is '99% likely to be a true statement'.

Here are a few more examples.

<!-- TODO: render each Input / Output pair side-by-side (left / right) via a shortcode, as on the TypeSafe announce page -->

1. Classify how opinionated this text is (i.e. some text does it suggest opinions or state facts)
2. Given all the attached regulations, are the following actions risky (three things, explain my products, suggest a loan, offer to connect user to fellow investors
3. TODO

The important note is that the output is _free_ and comes back very quickly[^pricing]. Now to look at a couple of fun examples in more detail, discuss why we couldn't just use a regular LLM and then summarise.

## Fun example - System 1 models playing Space Invaders fast vs System 2 models playing strategically

TODO this is where we put the gif

TODO link to the repo

13 years ago I built my own version of the "Space Invaders" game whilst learning JavaScript (my mind boggles at the changes since then). A fun use case can be made of this - give the state of the game to Jev and ask it to decide on the best action (move / fire). As Jev is low latency it should come back very quickly and play real-time[^classicml]. An LLM like Opus will need more munging and be too slow.

However; if we make Space Invaders more complex and strategic (adding more rules), then a large-language model should play better when reasoning is enabled. So this game runs in two modes - real time (where Jev is cheap, fast, scores confidence and does well) or turn-based (where a reasoning model does better). Here's the recording:

TODO another part of the gif

The source is in the original [Space Invaders](link) repo. You can play the OG 13 year old one or clone it and run the System 1/2 version locally with your own keys.

## A more serious example - rapid judgements on potential fraud

[gif]

In this example we give the decision model a large number of recent scam records (company names or text that has been associated with fraud) and then repeatedly ask whether a given transaction from a user could be considered as safe, low-risk or high-risk, with a confidence. Safe cases pass, low-risk cases might give the user a popup asking 'are you sure', and high-risk might be blocked[^injection] (and we can record the potential associated scam).

These sorts of judgements would be more expensive and time-consuming with a regular LLM. A case like this might make a good example to do some real-world experiments on.

## As an executive, should you care

Yes - it is useful to understand at least at a (very) high level what the fuss is about, partly to just separate out the noise (everyone who is releasing something new wants to show it as groundbreaking). The reality is that this might be a new class of very powerful types of models.

Whether this is novel is arguable - without knowing the internals it is possible that simply taking an open weights model and doing the same thing via prompt engineering is also 'good enough' or whether a model post-trained on your own data would in fact be better - but there are a nearly infinite number of use cases we can imagine and many may be served well by this technology[^novelty].

Possibly as an exec you might ask someone in your tech team to look at this, run a couple of experiments in your own domain, and share their learnings to your leadership team. More contextualised will be far more interesting and their real-world experience will be great to see (and probably a fun experiment for them to run).

That's the high level view - my post travel backlog is quite large so this is a short one but I hope you found it at least mildly interesting. No tokens were harmed during the writing of the text, but I have used AI to check references, spellcheck / grab screenshots from my other projects and so on.

## Addendum: Why not just use a large-language model?

It is possible to attempt to force output like this from an existing model, by saying something like:

```text
Answer with a single number between 0 and 1 only, where 0 represents 0% and 1 represents 100%
```

TypeSafe explain that they have used a specific form of reinforcement learning to optimise for this kind of output and will therefore provide better (and faster and cheaper) judgements[^calibration], but I haven't had enough time to really get into those details and try to understand more (if you have, please let me know and I'll link).

## Addendum: On utilitarianism, semantics, and meaning

The absurdity of asking for a numerical likelihood on a question like "Will the capital of Indonesia change"[^weakspots] is emphatically outside of the scope of this write-up.

Suffice to say that TypeSafe themselves don't suggest more than the result is a fast response based on semantics and the model parameters, and you should consider the result with the same skepticism or open-mindedness as if you asked a person (or a person who has read a shit-tonne of the internet's data) the same question.

Attributing numerical values to what is not discrete or measurable is both machine-like and very very human [ref, perhaps the master and his emissary]. As a consultant I am part of an industry that does this professionally. The value is of course highly contextual. So let's move to a more realistic use case.

[^typesafe]: TypeSafe AI, a San Francisco startup founded by former OpenAI researcher Diogo Almeida, came out of stealth on 15 September 2026 with a $40M seed round led by DCVC.

[^jev]: The official announcement is [Introducing System One Models & Jev](https://typesafe.ai/blog/introducing-system-one-models-and-jev).

[^naming]: "System One" is a nod to Kahneman's *Thinking, Fast and Slow* - the fast, automatic mode of thought, as against the slow, deliberate "System Two" (a fair description of a reasoning LLM). Simon Willison prefers "decision model", a name he credits to Maggie Appleton: [Jev introduces a new shape of LLM](https://simonwillison.net/2026/Sep/21/jev/). Worth noting Jev is one product and others are already appearing, so the terms aren't truly synonymous.

[^primitives]: Jev answers three shapes of question, each with a calibrated probability: a *noul* (how true a statement is, from 0 to 1), a *score* (a number on a scale you define) and a *choice* (a pick from a fixed set, returned as a probability spread across the options). The "set of numbers" is that distribution.

[^pricing]: TypeSafe's own figures: input at $0.042 per million tokens, output free ("too cheap to meter"), end-to-end latency of 70-500ms, and 40-200x faster than a frontier LLM on these "System One shaped" queries. It's free and fast because there is no generated text - one pass through the model, no token-by-token output.

[^calibration]: The genuinely new part isn't "a model that emits a number" - you can bully any LLM into doing that. It's *calibration*: TypeSafe train with a method they call Reinforcement Learning for Calibrated Decisions, so that (they claim) a stated confidence matches real accuracy. Take it with a pinch of salt - they concede their own hallucination figure is "not empirical", and what is actually guaranteed is only that the output matches the requested schema (a 0% *type*-error rate), not that it is right.

[^injection]: This is exactly the pattern security researchers broke. A typed output constrains the *format* of the answer, not the *credibility* of the input - so you can slip fabricated evidence into the documents and flip the verdict. Check Point manipulated Jev's decisions roughly 59% of the time, at about $0.50 a go, with no reasoning trace for the analyst to spot: ["Jev Is Not a Language Model, but It Breaks Like One"](https://blog.checkpoint.com/ai-security/jev-is-not-a-language-model-but-it-breaks-like-one-prompt-injection-against-a-typed-decision-model/). If you use it as a gate, screen the inputs *before* Jev sees them; don't trust the tidy number afterwards.

[^weakspots]: TypeSafe's own documentation says Jev is "not great with numbers, dates, or adversarial content" - worth sitting with, given that a capital-city prediction is a date/number question and regulatory documents are adversarial by nature. Simon Willison's test rating Bay Area towns put wealthy Cupertino top and East Palo Alto bottom - a neat reminder the number still comes out of the same semantic soup.

[^novelty]: Analysts expect the big labs to ship their own decision models quickly, and the category is already forming - by late September 2026 OpenRouter was listing several such models from multiple publishers. The interesting question isn't whether Jev specifically wins, but whether "typed, calibrated decisions as a cheap function call" becomes a standard part of the stack.

[^classicml]: Of course it is vastly cheaper and easier to just *train* a model to play Space Invaders - regular machine learning, or even a few lines of hand-written logic, would beat Jev and cost nothing per move. This is only an example for fun; the point is a *general* model reacting to described state, not the best way to play the game.

---

TODO links to openrouter and jev
