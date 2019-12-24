+++
author = "Dave Kerr"
categories = ["C#"]
date = 2011-09-12T13:09:00Z
description = ""
draft = false
slug = "pinvoke-performance"
tags = ["C#"]
title = "P/Invoke Performance"

+++


<p>SharpGL 2.0 has no P/Invoke - all native functions are called by a C++/CLI class library (OpenGLWrapper if you're getting the code from CodePlex) which calls functions directly. This means there's no more importing of PIXELFORMAT structures and so on.</p>
<p>The thinking behind this was that a C++/CLI wrapper is faster than P/Invoke for a talkative API like OpenGL - but is this actually the case? In my new article on the CodeProject I investigate the performance differences between these two methods.</p>
<p><a href="http://www.codeproject.com/KB/dotnet/pinvokeperformance.aspx">http://www.codeproject.com/KB/dotnet/pinvokeperformance.aspx</a></p>

