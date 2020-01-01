---
author: Dave Kerr
categories:
- Addin
- Visual Studio Extensisbility
- Visual Studio 2012
date: "2013-05-13T02:38:34Z"
description: ""
draft: false
slug: creating-addins-an-error-occurred-and-the-wizard-could-not-generate-the-project
tags:
- Addin
- Visual Studio Extensisbility
- Visual Studio 2012
title: Creating Addins - 'An error occurred, and the wizard could not generate the
  project.'
---


When doing a little bit of work on a solution that contains a Visual Studio Addin the other day, I noticed that there's a little bit of an issue with Visual Studio. If you create an addin project and you get the message:

<em>An error occurred, and the wizard could not generate the project. Verify that the programming language is properly installed.</em>

Then double check <em>where </em>you are creating your addin. If it is in a child folder of the solution, then this error can occur. The solution - add the addin project to the solution root. Then if you need to, you can move it afterwards.

This issue occurs in Visual Studio 2012, but a bit of googling suggests that it may also be an issue in 2010.

