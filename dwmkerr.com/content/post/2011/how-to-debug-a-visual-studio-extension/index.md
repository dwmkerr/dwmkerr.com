---
author: Dave Kerr
categories:
- Visual Studio
- Debugging
date: "2011-11-28T11:11:00Z"
description: ""
draft: false
slug: how-to-debug-a-visual-studio-extension
tags:
- Visual Studio
- Debugging
title: How to Debug a Visual Studio Extension
---


<p>Here are a few tips for debugging Visual Studio Extensions.</p>
<p><strong>Visual Studio 2008/2010</strong></p>
<p>If you need to debug your Visual Studio extension, you may find that Visual Studio itself locks it. This is a real drag - to resolve this issue, add the following as a pre-build step:</p>
<pre>if exist "$(TargetPath).locked" del "$(TargetPath).locked"</pre>
<pre>if not exist "$(TargetPath).locked" if exist "$(TargetPath)" <br />move "$(TargetPath)" "$(TargetPath).locked"</pre>
<p>This will ensure the locked file is moved out of the way first - very useful!</p>
<p><strong>Visual Studio 2010</strong></p>
<p>Every time I do a clean checkout of one of my projects, it seems to lose the ability to be run in the Experimental mode of visual studio. Here's a quick tip - if you lose the ability to debug your visual studio extension, make sure you have the 'Debug' tab of your project set up as below:</p>
<p><img src="images/screenshot.png" /></p>
<p>Specifically with the external program set as visual studio and the command line arguments as <strong>/rootsuffix exp</strong>. This will run your extension in the Experimental Instance of Visual Studio.</p>

