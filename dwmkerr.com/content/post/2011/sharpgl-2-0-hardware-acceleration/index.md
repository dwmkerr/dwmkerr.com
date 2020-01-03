---
author: Dave Kerr
type: posts
categories:
- SharpGL
- OpenGL
date: "2011-09-13T07:33:00Z"
description: ""
draft: false
slug: sharpgl-2-0-hardware-acceleration
tags:
- SharpGL
- OpenGL
title: 'SharpGL 2.0: Hardware Acceleration'
---


<p>It took a bit of working out, but finally SharpGL can support hardware acceleration. Previously, all rendering in SharpGL was done to a DIB Section, the result of this would be blitted to the screen. Much playing around has shown that in fact this is problematic - rendering to DIB sections can <em>never</em> be hardware accelerated.</p>
<p>To hardware accelerate rendering, the rendering must be to a window or a pixel buffer. This has introduced an architectural change to SharpGL - the handling of a render context and any supporting objects (DIB sections, windows etc) is handled by a class that implements the IRenderContextProvider interface. This interface specifies that render context providers must be able to Create, Destroy, Resize and Blit.</p>
<p>SharpGL 2.0 now has two render context providers, DIBSectionRenderContext provider which uses a DIB Section as previously and HiddenWindowRenderContextProvider which renders to a hidden window. The hidden window render context provider allows full hardware acceleration.</p>
<p>I will be adding a new example application to the solution which shows rendering with the two providers side by side.</p>
<p>So don't forget: DIB Sections can't be accelerated.</p>

