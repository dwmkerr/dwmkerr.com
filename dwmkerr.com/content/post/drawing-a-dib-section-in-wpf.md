---
author: Dave Kerr
categories:
- C#
- WPF
date: "2011-09-30T05:09:00Z"
description: ""
draft: false
slug: drawing-a-dib-section-in-wpf
tags:
- C#
- WPF
title: Drawing a DIB Section in WPF
---


<p>One of the most exciting new features in the forthcoming SharpGL 2.0 (which was actually planned for 2.1 but has been moved to 2.0) is the facility to do OpenGL drawing in a WPF control. This isn't done via a WinFormsHost (which has unpleasant side-effects due to Airspace, see&nbsp;<a href="http://msdn.microsoft.com/en-us/library/aa970688(v=VS.100).aspx">http://msdn.microsoft.com/en-us/library/aa970688(v=VS.100).aspx</a>) but actually via an Image in a WPF UserControl.</p>
<p>What does this mean? Well it means that when you use the SharpGL.WPF libraries OpenGLControl you get what is essentially a genuine WPF control - you can overlay other controls on top of it, with transparency and bitmap effects and do everything you'd normally be able to do with a WPF control.</p>
<p>How this works is an interesting bit of code so here are the details.</p>
<p>When using a WPF OpenGL control we render either using a DIBSectionRenderContextProvider, or a FBORenderContextProvider. Here's the difference:</p>
<p><strong>DIBSectionRenderContextProvider</strong>&nbsp;- Renders directly to a DIB Section. Supported with any version of OpenGL but never hardware accelerated.</p>
<p><strong>FBORenderContextProvider</strong>&nbsp;- Renders to a Framebuffer object, via the GL_EXT_framebuffer_object extension. This is fully hardware accelerated but only supported in OpenGL 1.3 and upwards. The resultant framebuffer is copied into a DIB section also.</p>
<p>With either render context provider we end up with a DIB section that contains the frame - here's how we can render it:</p>
<pre class="brush: c-sharp;">/// &lt;summary&gt;
/// Converts a &lt;see cref="System.Drawing.Bitmap"/&gt; into a WPF &lt;see cref="BitmapSource"/&gt;.
/// &lt;/summary&gt;
/// &lt;remarks&gt;Uses GDI to do the conversion. Hence the call to the marshalled DeleteObject.
/// &lt;/remarks&gt;
/// &lt;param name="source"&gt;The source bitmap.&lt;/param&gt;
/// &lt;returns&gt;A BitmapSource&lt;/returns&gt;
public static BitmapSource HBitmapToBitmapSource(IntPtr hBitmap)
{
    BitmapSource bitSrc = null;
    
    try
    {
        bitSrc = System.Windows.Interop.Imaging.CreateBitmapSourceFromHBitmap(
            hBitmap,
            IntPtr.Zero,
            Int32Rect.Empty,
            BitmapSizeOptions.FromEmptyOptions());
    }
    catch (Win32Exception)
    {
        bitSrc = null;
    }
    finally
    {
        Win32.DeleteObject(hBitmap);
    }

    return bitSrc;
}</pre>
<p>This function allows us to turn a handle to a DIB section into a BitmapSource. The OpenGLControl is essentially just an image, and with each frame we simply set the BitmapSource to the newly rendered DIBSection.</p>
<p>The version of the code this post relates to is:&nbsp;<a href="http://sharpgl.codeplex.com/SourceControl/changeset/view/4805">http://sharpgl.codeplex.com/SourceControl/changeset/view/4805</a></p>
<p>The WPF example renders the Utah Teapot (<a href="http://en.wikipedia.org/wiki/Utah_teapot">http://en.wikipedia.org/wiki/Utah_teapot</a>) directly in a WPF application. We're still pre-beta but grab the code if you want to try OpenGL in WPF.</p>

