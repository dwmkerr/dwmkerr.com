+++
author = "Dave Kerr"
categories = ["WinForms", "WPF", "Visual Studio Extensibility"]
date = 2013-06-12T01:36:18Z
description = ""
draft = false
slug = "wpf-and-visual-studio-addins"
tags = ["WinForms", "WPF", "Visual Studio Extensibility"]
title = "WPF and Visual Studio Addins"

+++


If at all possible nowadays, I write all my Windows UI code in WPF, it's just quicker and easier than WinForms. Recently however, I came across a situation that you should just avoid.

If you're developing addins for multiple versions of Visual Studio - don't use WPF for the Tools &gt; Options windows. It's just noit going to place nice out of the box. This is because there's a lot of property page Win32 stuff going on in the host window that makes it hard to route messages properly - keyboard entry won't work correctly, tab order will be messed up and more, it's just not worth the pain.

If you're developing addins for later versions of Visual Studio, you can actually use the VSPackage functionality to build options pages with WPF with ease, just check <a href="http://msdn.microsoft.com/en-us/library/microsoft.visualstudio.shell.uielementdialogpage.aspx" target="_blank">UIElementDialogPage</a>. In fact, read the article here:

<a title="Creating Option Pages by using MPF" href="http://msdn.microsoft.com/en-us/library/bb165039.aspx" target="_blank">Creating Options Pages by using MPF </a>

Final thoughts on this - if you want the functionality above in VS2010, you can get it (as long as you use MPF) by checking this page:

<a href="http://social.msdn.microsoft.com/Forums/en-US/vsx/thread/6af9718e-8778-4233-875d-b38c03e9f4ba" target="_blank">Unable to access WPF User Control in Options Dialog</a>

You'll see that about halfway down, Ryan Moulden has posted some code from Microsoft for the UIElementDialogPage, you can use that you get the functionality in VS2010.

Any other versions, or for a addin installed by an MSI, it's probably best to stick with WinForms.

&nbsp;

