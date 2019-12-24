+++
author = "Dave Kerr"
categories = ["C#", "Debugging"]
date = 2012-04-16T07:55:00Z
description = ""
draft = false
slug = "could-not-load-file-or-assembly-system-windows-version2-0-5-0-cultureneutral-publickeytoken7cec85d7bea7798e-or-one-of-its-dependencies"
tags = ["C#", "Debugging"]
title = "Could not load file or assembly 'System.Windows, Version=2.0.5.0, Culture=neutral, PublicKeyToken=7cec85d7bea7798e' or one of its dependencies."

+++


<p>Are you getting the error below when working with Silverlight projects?</p>
<pre>Could not load file or assembly 'System.Windows, Version=2.0.5.0, <br />Culture=neutral, PublicKeyToken=7cec85d7bea7798e' or<br /> one of its dependencies.</pre>
<p>It's a bit of an odd one. The solution that works for me is to re-register System.Core and System.Windows in the GAC. Use the commands below.</p>
<p><strong>32 Bit System</strong></p>
<p>"C:\Program Files\Microsoft SDKs\Windows\v7.0A\bin\NETFX 4.0 Tools\gacutil" /i "C:\Program Files\Microsoft Silverlight\4.1.10111.0\System.Core.dll"<br />"C:\Program Files\Microsoft SDKs\Windows\v7.0A\bin\NETFX 4.0 Tools\gacutil" /i "C:\Program Files\Microsoft Silverlight\4.1.10111.0\System.Windows.dll"&nbsp;&nbsp;</p>
<p><strong>64 Bit System</strong></p>
<p>"C:\Program Files (x86)\Microsoft SDKs\Windows\v7.0A\bin\NETFX 4.0 Tools\gacutil" /i "C:\Program Files\Microsoft Silverlight\4.1.10111.0\System.Core.dll"<br />"C:\Program Files&nbsp;(x86)\Microsoft SDKs\Windows\v7.0A\bin\NETFX 4.0 Tools\gacutil" /i "C:\Program Files\Microsoft Silverlight\4.1.10111.0\System.Windows.dll"&nbsp;&nbsp;</p>
<p>So far I am yet to understand why this happens - if anyone can shed any light please comment!</p>

