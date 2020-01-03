---
author: Dave Kerr
type: posts
categories:
- Unit Testing
- C#
- NUnit
date: "2013-05-02T05:22:45Z"
description: ""
draft: false
slug: getting-paths-for-files-in-nunit-tests
tags:
- Unit Testing
- C#
- NUnit
title: Getting Paths for Files in NUnit Tests
---


When using NUnit, sometimes you will want to access files in the test project. These might be xml files with data, assembly references or whatever. Now typically, NUnit will actually copy the files it thinks it needs into a temporary location. This causes the problem that you can then do things like use a relative path to get files in the project. You can use manifest resource streams but sometimes this just isn't suitable.

To get the path of the root of your test project, you can use the snippet below. Make sure you call it in a unit test fixture that's actually in your test project, not from a class referenced in another project!

This class, 'TestHelper' can be included in a Unit Test project to let you quickly get the path to the test project.

[code lang="csharp"]public static class TestHelper
{
    public static string GetTestsPath()
    {
        return Path.GetDirectoryName(Assembly.GetExecutingAssembly().CodeBase).Replace(@&quot;file:\&quot;, string.Empty);
    }
}[/code]

