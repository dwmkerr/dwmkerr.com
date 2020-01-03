---
author: Dave Kerr
type: posts
categories:
- C#
- SharpShell
- Shell
date: "2013-01-08T16:28:05Z"
description: ""
draft: false
slug: sharpshell
tags:
- C#
- SharpShell
- Shell
title: SharpShell
---


SharpShell is a project that I have recently uploaded to CodePlex. This class library, and set of tools and samples, is designed to be a framework to enable rapid development of Shell Extensions using the .NET Framework. In time it may grow to contain some functionality for using Shell entities within managed applications (for example, allowing an Explorer context menu to be built dynamically for a given path).

Anyway, the code is all at <a title="SharpShell on CodePlex" href="http://sharpshell.codeplex.com" target="_blank">sharpshell.codeplex.com</a>. You can also see a nice article on the CodeProject that show's how to create a Shell Context Menu Extension using C#, the article is at: <a title=".NET Shell Extensions - Shell Context Menus" href="http://www.codeproject.com/Articles/512956/NET-Shell-Extensions-Shell-Context-Menus" target="_blank">.NET Shell Extensions - Shell Context Menus</a>.

<a href="http://www.dwmkerr.com/2013/01/sharpshell/screenshot1_exampleiconhandler/" rel="attachment wp-att-200"><img src="images/Screenshot1_ExampleIconHandler.png" alt="Screenshot1_ExampleIconHandler" width="515" /></a>

<em>Above: An example of a Managed Shell Extension. This sample colours the icons for dlls differently, depending on whether they are native dlls or assemblies.</em>

So far, in the repo on CodePlex there are also samples for Shell Icon Handlers (which customise icons in Explorer) and Shell Info Tip Handlers (which customise tooltips). Both of these extension types are fully supported in the current dev version and will be released in the next few days. There's also a partially functioning Shell Property Sheet implementation which will be delivered in the subsequent version. The Shell Property Sheet introduces some particularly strange code - 32 and 64 bit C++ dlls are embedded as manifest resource streams and extracted as needed to provide access to C++ function pointers - ouch.

More to follow - check out the project and the article.

