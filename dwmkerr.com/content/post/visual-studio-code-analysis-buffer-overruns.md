+++
author = "Dave Kerr"
categories = ["Debugging", "Code Analysis"]
date = 2011-09-20T13:55:00Z
description = ""
draft = false
slug = "visual-studio-code-analysis-buffer-overruns"
tags = ["Debugging", "Code Analysis"]
title = "Visual Studio Code Analysis - Buffer Overruns"

+++


<p>Today I was looking through some fairly old source code in a large solution, large in this case is ~300 projects and about 1 million lines of code. Parts of the code base are very old - at some stage a decision was made to disable warning C4996. The problem I came across is reduced to its most simple form below:</p>
<pre class="brush: c-sharp;">// AnalysisExample.cpp : An example of how static analysis can help.
//

#include "stdafx.h"

int _tmain(int argc, _TCHAR* argv[])
{
	//	Create two buffers, one small, one large.
	TCHAR storageSmall[13];
	TCHAR storageLarge[128];

	//	Get a pointer to a string literal.
	TCHAR* str = _T("Here is a string that is too long.");
	
	//	Now do something very dangerous.
	::_tcscpy(storageLarge, str);
	::_tcscpy(storageSmall, storageLarge);

	return 0;
}</pre>
<p>Now in a sensible world with this warning enabled, we would get the following when compiling:</p>
<pre>analysisexample.cpp(14): warning C4996: 'wcscpy': 
This function or variable may be unsafe. Consider using 
wcscpy_s instead. To disable deprecation, use 
_CRT_SECURE_NO_WARNINGS. See online help for details.</pre>
<pre>analysisexample.cpp(15): warning C4996: 'wcscpy': 
This function or variable may be unsafe. Consider using 
wcscpy_s instead. To disable deprecation, use 
_CRT_SECURE_NO_WARNINGS. See online help for details.</pre>
<p>The warning is telling us that wcscpy (which is what _tcscpy translates to in a Unicode build) is unsafe, which indeed it is as it does no buffer checking. However, when you migrate a Visual Studio 2005 solution to 2008 or straight to 2010 then suddenly you'll get lots of warnings like this. If there are thousands of warnings and they're masking other more important ones then you can see why maybe you'd consider disabling them.</p>
<p>Why is this a bug?</p>
<p>In case you didn't see it, a string literal that is 34 characters long (68 bytes) is copied to a buffer 128 characters long. OK so far. Then we copy the 34 characters into a smaller 13 character buffer - this causes a buffer overrun on the stack. In reality what happens is variables used subsequently in the function get overwritten unexpectedly. Or don't. Generally the worst case is that nothing odd happens during testing, but then the code blows up on-site with the customer, typically on something business critical like a database server - something it's hard to debug on.</p>
<p>Visual Studio's Code Analysis tool is a life-saver. If you haven't used it before, get used to running it on <em>all</em>&nbsp;of your projects. Here's what happens when we run it (Analyze &gt; Run Code Analysis On Solution):</p>
<pre>1&gt;analysisexample.cpp(18): warning C6202: 
Buffer overrun for 'storageSmall', which is possibly 
stack allocated, in call to 'wcscpy': length '256' 
exceeds buffer size '26'</pre>
<p>Code analysis has shown us <em>exactly</em>&nbsp;the problem, even with the warning disabled.</p>
<p>So why is this important? Imagine we have the following four lines spread across four files:</p>
<pre class="brush: c-sharp;">//	Defined in Header1.h
static const int LENGTH1 = 13;

//	Defined in Header2.h
static const int LENGTH2 = 128;

//	Defined in Header3.h
typedef TCHAR LineOne[LENGTH1];

//	Defined in Header4.h
typedef TCHAR LineTwo[LENGTH2];</pre>
<p>Our code could now look like this:</p>
<pre class="brush: c-sharp;">//	Create two buffers, one small, one large.
LineOne storageSmall;
LineTwo storageLarge;

//	Get a pointer to a string literal.
TCHAR* str = _T("Here is a string that is too long.");
	
//	Now do something very dangerous.
::_tcscpy(storageLarge, str);
::_tcscpy(storageSmall, storageLarge);</pre>
<p>Suddenly things aren't looking quite so obviously wrong - now imagine the different lines that make up this bug are spread across more files - or even more projects. Static analysis takes only a few seconds to run, unfortunately it's only available in the more expensive versions of visual studio.</p>
<p>An even better solution - don't run the risk, use <strong>_tcscpy_s</strong>&nbsp;rather than <strong>_tcscpy </strong>- it checks the buffer length without even requiring a single extra parameter in the example above.</p>

