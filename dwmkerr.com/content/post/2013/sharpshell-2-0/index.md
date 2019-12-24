---
author: Dave Kerr
categories:
- C# SharpShell
date: "2013-09-15T06:31:34Z"
description: ""
draft: false
slug: sharpshell-2-0
tags:
- C# SharpShell
title: SharpShell 2.0
---


I have just released SharpShell 2.0  - you can get the release from <a title="SharpShell on CodePlex" href="http://sharpshell.codeplex.com" target="_blank">sharpshell.codeplex.com</a> or the new GitHub page at <a title="SharpShell on GitHub" href="https://github.com/dwmkerr/sharpshell" target="_blank">github.com/dwmkerr/sharpshell</a>.

This release has been primarily a bugfixing release, but there is one very useful new feature, the Server Registration Manager tool (srm.exe). This is a standalone application that can be used to install and uninstall SharpShell servers.
<pre>srm install server.dll -codebase

srm uninstall server.dll</pre>
This tool makes it much easier to deploy SharpShell servers. You can call the tool as a Custom Action in a MSI project, either by using Visual Studio 2010's installer project type, or a WiX project. I'll be writing up an article on the CodeProject on how to use the tool soon, until then you can download the tool and try it out now!

