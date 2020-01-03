---
author: Dave Kerr
type: posts
categories:
- Node.js
date: "2014-05-05T04:35:09Z"
description: ""
draft: false
slug: blogging-with-ghost
tags:
- Node.js
title: Blogging with Ghost
---


> **tl;dr** [Ghost](https://ghost.org/) is a blogging platform well worth considering if your blog is all about development.

I've been having some  gripes with WordPress as a platform for blogging lately. For development focused blogs like this one, there are some things about it that make writing posts just a little bit clunky. For example:

1. Syntax Higlightling is always going to use plugins with   various shortcode formats. This works, but your raw blog text becomes quite specific to a certain implementation.
2. It's kind of slow, unless you use a theme that's loading as much content as possible with Ajax.
3. There aren't a great number of simple, content focused themes that appeal to dev type blogs.

The final point - I want to blog in [Markdown](https://daringfireball.net/projects/markdown/) its a simple syntax that doesn't require a fancy editor and I use it every day for work.

## Enter Ghost

A little bit of research led me to [Ghost](https://ghost.org/). It's a fairly new, simple blogging platform with few features, but the features it has it does well. You write your posts in Markdown. If you need fancy syntax highlighting, you can have it, over and above that there's not much to it.

Ghost runs on Node.js, so it's simple to install. The data is stored in SqlLite and the blog is ajaxy - without many full page loads.

There are no comments, but you can easily integrate [Disqus](http://disqus.com/) into your blog if you feel you need them.

I'm hosting my old WordPress blog at [oldblog.dwmkerr.com](http://oldblog.dwmkerr/com) and currently migrating things over.

## Installing on IIS

This server is actually running IIS, unlike my droplets on Digital Ocean. If you're installing Ghost on IIS, I strongly recommend [How to set up Node.js, iisnode and Ghost on IIS](http://www.saotn.org/how-to-set-up-nodejs-iisnode-module-ghost-on-windows-server-2012-iis-80/) - it was this post that had the clearest and most up to date instructions.

## Final Thoughts

Before you consider the leap, be aware that Ghost is quite new - features are changing or being added, and if you need lots of complicated stuff you might not find it up to the job. However there's already an active community and it looks like its only getting better.

If it's markdown you want - you *can* use it in WordPress, just find the right plugin and you're done.

