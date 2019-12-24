---
author: Dave Kerr
categories:
- IIS
- Web Deploy
- Visual Studio
date: "2013-06-19T09:22:06Z"
description: ""
draft: false
slug: publish-web-web-deploy
tags:
- IIS
- Web Deploy
- Visual Studio
title: Web Deploy - Could not connect to the remote computer
---


Using Web Deploy is a nice and easy way to publish websites and web applications with Visual Studio. However, I found one thing that can be a bit of a blocker, that didn't seem to be explained anywhere very well.

Let's imagine I administer a webserver that hosts the site www.something.com. I've installed the Remote Management tools for IIS and the Web Deploy stuff, and have also configured the site to allow Web Deploy. I now try and deploy using Visual Studio, with the settings below:

<a href="http://www.dwmkerr.com/wp-content/uploads/2013/06/somesite.jpg"><img class="alignnone size-full wp-image-312" alt="somesite" src="http://www.dwmkerr.com/wp-content/uploads/2013/06/somesite.jpg" width="600" height="471" /></a>

Validating the connection fails with the message:

<em>'Could not connect to the remote computer "somesite.com". On the remote computer, make sure that Web Deploy is installed and that the required process ("Web Management Process") is started. [more stuff] ERROR_DESTINATION_NOT_REACHABLE.</em>

So what do we try first?
<ul>
	<li><span style="line-height: 14px;">Check the Web Deploy feature is installed on the server, it is.</span></li>
	<li>Check the Web Management Process is running, it is.</li>
	<li>Check port 8172 is open, it is.</li>
	<li>Read up on similar issues, they say the same as the above.</li>
</ul>
I spent quite some time pulling my hair out over this - is it because I'm on a different domain? Is there some other port that needs to be open too?

Now the error says 'could not connect to the remote computer "somesite.com"' - so maybe the issue is here. I try the IP address, www.somesite.com and the IP address with the port 8172 specified - no joy.

It turns out, that even though it says 'Server' in the first box (leading us to think it would be the address of a server we need), it's actually the server <strong>with http </strong>specified. Change the Server from <strong>somesite.com </strong>to <b>http://www.somesite.com </b>and it works a charm.

Not the most exciting post ever, but hopefully this'll save someone else wasting the same amount of time that I did.

