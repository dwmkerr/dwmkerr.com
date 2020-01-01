---
author: Dave Kerr
categories:
- Deployment
- Visual Studio
date: "2012-12-02T05:40:28Z"
description: ""
draft: false
slug: deployment-projects-in-visual-studio-2012
tags:
- Deployment
- Visual Studio
title: Deployment Projects in Visual Studio 2012
---


[Note: They aren't bringing setup projects back, see <a href="http://www.dwmkerr.com/2013/06/visual-studio-deployment-projects-an-update/">http://www.dwmkerr.com/2013/06/visual-studio-deployment-projects-an-update/</a>]

As part of Microsoft's ongoing campaign to reduce the usability of their tools for anyone who isn't working in exactly the way they want, Visual Studio 2012 no longer comes with the ability to create setup and deployment projects.

This is a pretty serious change. For anyone who is developing client applications, then an installer is pretty critical. Now the feature set in the VS deployment projects was fairly small - they were aimed towards making pretty basic, lean installers. And that was <em>fine</em>. That was what we needed it for. Installers for utility apps, installers for basic client applications, installers for testing out projects on other machines before we went to more advanced systems.

What's truly disappointing is the lack of alternatives. Rather suspiciously there are links to InstallShield projects in Visual Studio now.

If you've never worked with InstallShield before then I envy you. It is truly awful - a maintainance nightmare combined with a user interface that makes creating basic installers baffling.

So Visual Studio now has no deployment projects. You can try using the free edition of InstallShield, but be ready for a world of pain. Also, considering the vast complexity of the UI, the free edition is <em>incredibly </em>limited in functionality - for example you cannot create 'features' (i.e. the chunks of functionality that you offer as an optional feature for an installation).

<strong>Example of Time Wasted</strong>

My Switch addin for Visual Studio adds a button to the UI that lets you switch between related files (cpp/h, aspx/aspx.cs etc etc).

I need to update it to work in Visual Studio 2012. I cannot develop VS 2012 addin projects in Visual Studio 2010. With a sigh I move the solution into 2012. I write the 2012 addin. The deployment project doesn't load (as expected). I build the binaries into specific locations. I open the project in 2010. The 2012 addin doesn't load (as expected). However, the setup project will not build due to an error when 'updating dependencies'. This project has no dependencies - it builds from specific locations.

So now to release a version of Switch that supports VS2012, I need to use InstallShield. InstallShield's free edition doesn't support features - therefore I have to install Switch for 2008, 2010 and 2012 for everyone, always, regardless of whether they have it. A two hour update is not looking possible now. I don't have the time to waste trying to <strong>bring back functionality I already had</strong> and have to move onto other work.

<strong>Conclusion</strong>

Thanks MS for removing this critical feature, and replacing it with an essentially useless and overly complicated alternative.

Please remember, we've paid for Visual Studio - not for a vessel to host adverts to other products. We had the functionality before, now its gone - replaced by links to an expensive (and frankly crap) suite of tools that aren't suitable. Why has this happened? The cynical part of me thinks there's some kind of deal going on between MS and InstallShield (well of course there is), and we're suffering from it.

Hit the uservoice page here: <a href="http://visualstudio.uservoice.com/forums/121579-visual-studio/suggestions/3041773-bring-back-the-basic-setup-and-deployment-project-" target="_blank">http://visualstudio.uservoice.com/forums/121579-visual-studio/suggestions/3041773-bring-back-the-basic-setup-and-deployment-project-</a> to try and vote for it to go back in.

