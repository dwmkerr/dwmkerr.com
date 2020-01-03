---
author: Dave Kerr
type: posts
categories:
- Unix
- Bash
- cURL
- CodeProject
date: "2016-05-03T22:12:28Z"
description: ""
draft: false
image: /images/2016/05/Bash-Newlines-1.png
slug: quick-tip-sending-newlines-with-curl
tags:
- Unix
- Bash
- cURL
- CodeProject
title: 'Quick Tip: Sending Newlines with cURL'
---


Yikes, this took far too long to figure out!

I have a service which takes plain text multi-line input and outputs an object for each line, something like this:

**Input**

```
Line 1
Line 2
Line 3
```

**Output**

```
[
  {line: "Line 1"},
  {line: "Line 2"},
  {line: "Line 3"}
]
```

There's a bit more to it than that, but that's the gist.

I want to test my service with cURL, trying:

```
curl --data "Line 1\nLine 2\nLine 3" \
  -H "Content-Type: text/plain" localhost:3000/parse
```

This did not work. Nor did some alternatives. And I really didn't want to have to write the text to a file and load it in.

Turns out there's a nice little shell trick to let you use escape characters C style, use `$'some\ncontent'` to use ANSI C escaping. Now you can cURL with newlines!

```
curl --data $'Line 1\nLine 2\nLine 3' \
  -H "Content-Type: text/plain" localhost:3000/parse
```

Enjoy!

## References

1. [GNU Bash ANSI C Quoting](https://www.gnu.org/software/bash/manual/html_node/ANSI_002dC-Quoting.html)
2. [Stack Overflow - Echo Newline Bash Prints \n](http://stackoverflow.com/questions/8467424/echo-newline-in-bash-prints-literal-n)
3. [Stack Overflow - How to send line break with cURL](http://stackoverflow.com/questions/3872427/how-to-send-line-break-with-curl)

