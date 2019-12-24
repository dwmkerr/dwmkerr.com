---
author: Dave Kerr
categories:
- Extensions
- Visual Studio
date: "2013-02-16T14:41:27Z"
description: ""
draft: false
slug: the-visual-studio-experimental-instance
tags:
- Extensions
- Visual Studio
title: The Visual Studio Experimental Instance
---


Working on some addins lately has taught me a few really useful tricks about debugging in Visual Studio. I'll update this post over time.

<strong>The Experimental Instance</strong>

Very useful to know - the experimental instance loads its extensions from a special folder, and debugging extensions drops them there. The location is:

%UserProfile%\AppData\Local\Microsoft\VisualStudio\10.0Exp\Extensions\

