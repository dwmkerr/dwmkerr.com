---
author: Dave Kerr
type: posts
categories:
- Oracle
- SQL
date: "2012-02-21T07:21:00Z"
description: ""
draft: false
slug: using-imp-or-exp-as-a-sysdba
tags:
- Oracle
- SQL
title: Using imp or exp as a SYSDBA
---


<p>One of the things that I regularly forget is the syntax for running imp or exp for Oracle and specifying a SYSDBA user. As a quick hint, here's the syntax:</p>
<pre class="brush: c-sharp;">imp '"sys/pass@TNS as sysdba"' FILE=file.exp</pre>
<p>An easy thing to forget!</p>

