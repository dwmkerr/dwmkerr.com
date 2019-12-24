---
author: Dave Kerr
categories:
- SVN
- TFS
date: "2012-06-21T07:56:00Z"
description: ""
draft: false
slug: changing-mergecompare-tools-in-tfs
tags:
- SVN
- TFS
title: Changing Merge/Compare Tools in TFS
---


<p>Moving from SVN to TFS has been an interesting experience. The integration of source control directly into Visual Studio seems like a good thing, but even on a well set up network it can occasionally bring Visual Studio to its knees. And I still don't trust its automerge.</p>
<p>Anyway, if you find that the TFS diff and merge tools are just too ugly and odd to work with, there's a great page on the MSDN blogs that describes how to set Visual Studio to use your preferred tool:</p>
<p><a href="http://blogs.msdn.com/b/jmanning/archive/2006/02/20/diff-merge-configuration-in-team-foundation-common-command-and-argument-values.aspx">http://blogs.msdn.com/b/jmanning/archive/2006/02/20/diff-merge-configuration-in-team-foundation-common-command-and-argument-values.aspx</a></p>
<p>Welcome back TortoiseMerge, I've missed you.</p>

