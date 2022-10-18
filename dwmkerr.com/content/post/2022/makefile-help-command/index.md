---
author: Dave Kerr
type: posts
date: "2022-09-25"
description: "A simple Makefile 'help' Command"
slug: makefile-help-command
title: A simple Makefile 'help' command
categories:
- "shell"
- "bash"
- "devsecops"
tags:
- "shell"
- "bash"
- "devsecops"
- "CodeProject"
---

In this article I'm going to show you how to add a `make help` command to your makefiles that quickly and easily shows simple documentation for your commands:

![Screen recording of the 'makefile help' command in action](./images/demo.svg)

To add the `help` command to your makefile, add a recipe like so:

```make
.PHONY: help
help: # Show help for each of the Makefile recipes.
	@grep -E '^[a-zA-Z0-9 -]+:.*#'  Makefile | sort | while read -r l; do printf "\033[1;32m$$(echo $$l | cut -f 1 -d':')\033[00m:$$(echo $$l | cut -f 2- -d'#')\n"; done
```

Now just make sure that each of your recipes has a comment that follows the recipe name, which will be used as its documentation. For example, my [website repository](https://github.com/dwmkerr/dwmkerr.com) has the following recipes in the makefile:

```make
default: help

.PHONY: help
help: # Show help for each of the Makefile recipes.
	@grep -E '^[a-zA-Z0-9 -]+:.*#'  Makefile | sort | while read -r l; do printf "\033[1;32m$$(echo $$l | cut -f 1 -d':')\033[00m:$$(echo $$l | cut -f 2- -d'#')\n"; done

.PHONY: setup
setup: # Setup tools required for local development.
	brew install hugo
	hugo version
	git submodule update --init --recursive --remote

.PHONY: newpost
newpost: # Create a new post.
	cd dwmkerr.com; hugo new posts/my-first-post.md

.PHONY: serve
serve: # Serve the site locally for testing.
	cd dwmkerr.com; hugo server --baseURL "http://localhost/" --buildDrafts -v --debug

.PHONY: build # Build the site.
build:
	cd dwmkerr.com; hugo --minify
```

With this setup, you can just enter `make`, or `make help`, to see the output below:

```
$ make help
help: Show help for each of the Makefile recipes.
newpost: Create a new post.
serve: Serve the site locally for testing.
setup: Setup tools required for local development.
```

Simple! You can find the code at:

https://github.com/dwmkerr/makefile-help

## How it Works

This project was inspired by the project [`golang-cli-template`](https://github.com/FalcoSuessgott/golang-cli-template), which I noticed had this cool feature of showing help for the makefile commands.

I built my own version of the command, which is a little bit more verbose, but I think a little easier to read and parse. I've also included the original version, with a link to the source in the [`makefile-help`](https://github.com/dwmkerr/makefile-help) repo.

Essentially, the code simply:

1. Searches for recipes - these are lines that start with text, have a colon and a hash symbol
2. Goes through each recipe found, extracts the recipe name and documentation comment
3. Write each of the recipe names and its documentation to the console

## Testing Scripts

I wanted to make sure that if I improve on the script over time, or add different versions, the code won't break. There's a test script, which is a simple shell script that runs the two help commands and assets the output is as expected.

At the time of writing, the shell script looks like this:

```bash
#!/usr/bin/env bash

set -e

recipes=("help" "help-compact")

# Some colour codes for formatting.
green="\033[1;32m"
red="\033[1;31m"
reset="\033[00m"

# Default to success for the result of tests.
result=0

for recipe in "${recipes[@]}"; do
    # Create the path to the expected output file.
    expected_output="./test-cases/${recipe}-expected-output.txt"
    if [ ! -f "${expected_output}" ]; then
        printf "[${red}FAIL${reset}] '${recipe}' failed, test file '${expected_output}' not found\n"
        result=1
    elif [ "$(make ${recipe})" == "$(cat ${expected_output})" ]; then
        printf "[${green}PASS${reset}] '${recipe}' passed\n"
    else
        printf "[${red}FAIL${reset}] '${recipe}' failed\n"
        result=1
    fi
done

# Return the exit code.
exit ${result}
```

One thing that is nice about the tests is that they are incorporated into a GitHub Action, which runs the tests using Ubuntu, MacOS and Windows, and tests on both Bash and the generic `sh` shell.

This uses the following features of GitHub actions:

- [Runner Images](https://docs.github.com/en/actions/using-github-hosted-runners/about-github-hosted-runners#supported-runners-and-hardware-resources) - predefined images are made available by GitHub for various operating systems
- [Shell Specificity](https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions#jobsjob_idstepsshell) - GitHub actions allow you to specify the shell used for a step
- [GitHub Action Matrix Strategies](https://docs.github.com/en/actions/using-jobs/using-a-matrix-for-your-jobs) - A matrix of operating systems is specified, to avoid duplicating the pipeline steps for each supported operating system

This project provides a nice template or starting point if you want to build a simple shell script with some basic testing features.

## Further Reading

If you found this interesting, you might enjoy [Effective Shell](https://effective-shell.com) - My free online book of shell techniques.
