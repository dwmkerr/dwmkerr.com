---
author: Dave Kerr
type: posts
date: "2022-10-04"
description: ""
slug: template-nodejs-module
title: A simple template for a Node.js module
categories:
- "nodejs"
- "node"
- "javascript"
tags:
- "nodejs"
- "node"
- "javascript"
- "CodeProject"
---


### Blog Post

- Why my own
- Why not 'https://docs.github.com/en/repositories/creating-and-managing-repositories/creating-a-repository-from-a-template'
- Show the work of others
- Design Goals
- Walkthrough
- Artifacts
- Makefile

### Test Steps

- [x] Build: npm badge
- [x] Build: Ensure build does *not* try to publish when `NPM_TOKEN` is not set.
- [x] Build: Ensure build does try publish when `NPM_TOKEN` is set.
- [ ] Build: Ensure we deploy successfully only with the final node version in the matrix (i.e. no errors on multiple publishes OR just publish once)
- [ ] Pull Request: check code coverage is reported FAILED

This template contains a simple 'Hello World' Node.js project, which contains a set of foundational patterns that I find myself adding to almost every project. Just clone, run the `rename.sh` script and follow the [Template Guide](#template-guide) to customise as you see fit.

The design goals are not to suggest the best stack for your Node.js project, only to include the 'must have' code hygiene elements such as linting, coverage reporting, unit tests, contributions list, builds and so on.
