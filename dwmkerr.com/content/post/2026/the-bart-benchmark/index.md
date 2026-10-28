---
author: Dave Kerr
type: posts
date: "2026-10-28"
title: "The Bart Benchmark vs the Ralph Loop"
description: "Traditional benchmarks for new models have little practical value for engineers, the Bart Benchmark is a simple technique to get a more real-world feel for improvements."
slug: the-bart-benchmark
draft: false
thumbnail: TODO
categories:
- "ai"
- "agentic-ai"
tags:
- "ai"
- "agentic-engineering"
- "fable"
- "kimi"
- "astra"
---

As an engineer, and also someone who automates and improves many non-engineering processes using coding harnesses and frontier models, I'm always excited to get my hands dirty when new models are launched. In this post I will briefly share how I do a more real-world rapid benchmark - there's a short video of it in action that takes only a few seconds to watch:

TODO video, with results

This week [Kimi K3.1 Pro was launched](https://kimi.ai/blog/kimi-k3-1-pro). I've been a huge fan of the Kimi models for a while. There's a lot of chatter online about how this model is more powerful and cheaper than other frontier models such as Fable or Astra (as well as what their impending November release of their weights will mean).

The inevitable deluge of articles showing benchmark numbers has followed, but like many engineers I tend to ignore them as largely unrelated to real-world activity.

The best benchmark I have found is a less statistical or quantative approach, and instead a far more rapid and qualitative approach. In my mind this has always been the Bart Benchmark:

TODO image of bart with the homework screen

## How it Works

It's pretty simple. Keep track of realistic and complex challenges that you point agents at and run a set of models at it in parallel. 

As a real-world example, I'm using four harnesses and four frontier models to analyse what I can change in my artchitecture and AWS estate to save costs:



1. Pick a problem, in particular one that requires creativity
2. Run a set of agents in parallel
3. Ask for the best answer
4. Review the tradjectory


