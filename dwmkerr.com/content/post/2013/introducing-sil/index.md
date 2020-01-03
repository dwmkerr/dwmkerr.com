---
author: Dave Kerr
type: posts
date: "2013-06-05T15:35:39Z"
description: ""
draft: false
slug: introducing-sil
title: Introducing Sil
---


For the last few weeks I've been trying to tie up a project I've been working on for a while called Sil. With lots of other things on my plate at the moment I haven't had much of a chance to work on it, but finally tonight I'm able to release the first version.

Sil is short for 'See IL', or 'See Intermediate Language'. It's primarily an addin for Visual Studio (2010 and 2012) that lets you right click on some code and disassemble it.

I think it can be very useful sometimes to see what's going on in the code your writing, and searching for ildasm (which Sil actually uses itself) slows me down - I want to disassembly right from Visual Studio, and I want the results side-by-side with my original code.

Here's a screenshot of how the code editor looks after I've just right clicked on a method and chosen 'Disassemble':

<a href="http://www.dwmkerr.com/wp-content/uploads/2013/06/ResultSized.png"><img src="images/ResultSized.png" alt="ResultSized" width="640" /></a>

It's not too shabby - syntax highlighting and a few options to see more detail. As I've disassembled a method, from the bottom of the window I can also expand the scope to the parent class or the whole assembly.

Under the hood, Sil uses ildasm to disassemble the entire assembly, then parses it into a set of 'DisassembledEntity' objects (which can be DisassembedClass, DisassembedEnumeration and so on). A little bit of WPF for the UI and the great AvalonEdit control and that's all there is too it. As you might expect, the bulk of the complexity is in the code to parse the disassembly into logical entities.

You can get the Sil installer from the <a title="Sil" href="http://www.dwmkerr.com/sil/">Sil page</a> on this site. You can also head to the CodeProject and take a look at the article I've just written '<a title="See the Intermediate Language for C# Code" href="http://www.codeproject.com/Articles/602648/See-the-Intermediate-Language-for-Csharp-Code">See the Intermediate Language for C# Code'</a>.

I think with this project, rather than using CodePlex (as I've done for Apex, SharpGL and some others) I'm going to go for GitHub to mix things up a bit. Watch this space for news on the source code going online - if you're keen for a look, it's also in the CodeProject article.

