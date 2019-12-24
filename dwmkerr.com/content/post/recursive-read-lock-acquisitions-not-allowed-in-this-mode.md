+++
author = "Dave Kerr"
categories = ["Git", "Nuget", "Visual Studio"]
date = 2013-07-10T02:17:09Z
description = ""
draft = false
slug = "recursive-read-lock-acquisitions-not-allowed-in-this-mode"
tags = ["Git", "Nuget", "Visual Studio"]
title = "Recursive read lock acquisitions not allowed in this mode"

+++


If you are using the following combination of tools:
<ul>
	<li><span style="line-height: 14px;">Visual Studio 2012</span></li>
	<li>Visual Studio Tools for Git</li>
	<li>Nuget</li>
</ul>
Then you may encounter some weird problems when trying to update Nuget packages. For me, updates regularly fail with:

<strong>Recursive read lock acquisitions not allowed in this mode.</strong>

I'm lost on the root cause of this, but it does seem that the project I'm working on has files set to read-only by something regularly, perhaps Visual Studio is trying to make Git more TFS-y by locking things all over the place. Whatever the cause, I've found that the following usually helps:
<ol>
	<li><span style="line-height: 14px;">Don't use Update-Package - use Install-Package instead.</span></li>
	<li>Make sure the solution has all of its files read+write, not read only.</li>
	<li>Open the team explorer and go to 'Commits' - making sure that the Git tools have loaded various components.</li>
</ol>
<span style="line-height: 20px;">This combination of tricks seems to solve the problem. If anyone has any other ideas or suggestions, just comment.</span>

