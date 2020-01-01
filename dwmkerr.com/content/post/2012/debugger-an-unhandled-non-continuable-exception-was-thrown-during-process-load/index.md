---
author: Dave Kerr
categories:
- C#
- Debugging
date: "2012-02-08T06:54:00Z"
description: ""
draft: false
slug: debugger-an-unhandled-non-continuable-exception-was-thrown-during-process-load
tags:
- C#
- Debugging
title: 'Debugger:: An unhandled non-continuable exception was thrown during process
  load'
---


<p>The following exception can be a very tricky one to deal with:</p>
<pre>Debugger:: An unhandled non-continuable exception was thrown during process load</pre>
<p>here's some tips if you get it.</p>
<ol>
<li>Are you linking to winmm.lib? If so avoid it - it can cause these problems.</li>
<li>Are you delay-loading the module? If not, try it - this can often resolve this issue if other modules like winmm.lib are interfering with the module that causes this exception.</li>
<li>Are you using C++/CLI for the excepting module? If so, try using #pragma pack around exported class definitions.</li>
</ol>
<div>If you haven't specified packing - do so. This is good practice anyway. I've used libraries that change the packing (which is very bad behaviour) before and this has caused all sorts of problems, so try and do the following:</div>
<div>
<pre class="brush: c-sharp;">// Push packing options, specify the packing.
#pragma pack(push, 1)

//	Exported class
class MY_API MyClass
{
public:

	//	...etc
};</pre>
<pre class="brush: c-sharp;">// Restore packing options.
#pragma pack(pop)</pre>
</div>

