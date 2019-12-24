---
author: Dave Kerr
categories:
- C#
- OpenGL
- SharpGL
date: "2011-09-24T06:57:00Z"
description: ""
draft: false
slug: importing-opengl-extensions-functions-with-wglgetprocaddress
tags:
- C#
- OpenGL
- SharpGL
title: Importing OpenGL Extensions Functions with wglGetProcAddress
---


<p>There are only a small set of the core OpenGL functions that can be imported via p/invoke - the majority of OpenGL functions are actually extension functions which are supported only on specific video cards. OpenGL offers a function called&nbsp;wglGetProcAddress which can return the address of a named function - but how do we deal with this in the managed world?</p>
<p>Here's a brief description of how it's handled in SharpGL. As of this morning, SharpGL's latest version contains <strong>all </strong>core functions up to OpenGL 4.2 and <strong>all </strong>standard extensions up to OpenGL 4.2. This takes the support for OpenGL to the latest version - August 2011.</p>
<p>First we must import the wglGetProcAddress function:</p>
<pre class="brush: c-sharp;">[DllImport("opengl32.dll")]
public static extern IntPtr wglGetProcAddress(string name);</pre>
<p>This is the correect p/invoke method of importing this function, however it returns an IntPtr, which we cannot call as a function. We could change the return type to a delegate but this function can return essentially any type of delegate - so where do we go from here?</p>
<p>Well the next step is to define the delegates we want to use - they must have exactly the same name as the OpenGL functions and use the correct parameters for marshalling. Here are a couple of delegates for OpenGL 1.4:</p>
<pre class="brush: c-sharp;">private delegate void glBlendFuncSeparate (uint sfactorRGB, uint dfactorRGB, uint sfactorAlpha, uint dfactorAlpha);

private delegate void glMultiDrawArrays (uint mode, int[] first, int[] count, int primcount);</pre>
<p>Now we must create a function which will turn an IntPtr into a delegate and invoke it:</p>
<pre class="brush: c-sharp;">/// &lt;summary&gt;
/// The set of extension functions.
/// &lt;/summary&gt;
private Dictionary&lt;string, Delegate&gt; extensionFunctions = new Dictionary&lt;string, Delegate&gt;();

/// &lt;summary&gt;
/// Invokes an extension function.
/// &lt;/summary&gt;
/// &lt;typeparam name="T"&gt;The extension delegate type.&lt;/typeparam&gt;
/// &lt;param name="args"&gt;The arguments to the pass to the function.&lt;/param&gt;
/// &lt;returns&gt;The return value of the extension function.&lt;/returns&gt;
private object InvokeExtensionFunction&lt;T&gt;(params object[] args)
{
    //  Get the type of the extension function.
    Type delegateType = typeof(T);

    //  Get the name of the extension function.
    string name = delegateType.Name;

    //  Does the dictionary contain our extension function?
    Delegate del = null;
    if (extensionFunctions.ContainsKey(name) == false)
    {
        //  We haven't loaded it yet. Load it now.
        IntPtr proc = Win32.wglGetProcAddress(name);
        if (proc == IntPtr.Zero)
            throw new Exception("Extension function " + name + " not supported");

        //  Get the delegate for the function pointer.
        del = Marshal.GetDelegateForFunctionPointer(proc, delegateType);
        if (del == null)
            throw new Exception("Extension function " + name + " not supported");

        //  Add to the dictionary.
        extensionFunctions.Add(name, del);
    }

    //  Get the delegate.
    del = extensionFunctions[name];

    //  Try and invoke it.
    object result = null;
    try
    {
        result = del.DynamicInvoke(args);
    }
    catch
    {
        throw new Exception("Cannot invoke extension function " + name);
    }

    return result;
}</pre>
<p>We now have a generalised way to invoke an extension function. The loaded functions are stored in a dictionary keyed by name so that the heavy lifting is only done the first time we try to invoke the function. &nbsp;We can finally add the functions to the class as below:</p>
<pre class="brush: c-sharp;">public void BlendFuncSeparate(uint sfactorRGB, uint dfactorRGB, uint sfactorAlpha, uint dfactorAlpha)
{
    InvokeExtensionFunction&lt;glBlendFuncSeparate&gt;(sfactorRGB, dfactorRGB, sfactorAlpha, dfactorAlpha);
}

public void MultiDrawArrays(uint mode, int[] first, int[] count, int primcount)
{
    InvokeExtensionFunction&lt;glMultiDrawArrays&gt;(mode, first, count, primcount);
}</pre>
<p>This is pretty cool - we can invoke any extension function as long as we have defined a delegate for it. What's more, by making the InvokeExtensionFunction function public we can allow other developers to provide their own delegates and invoke other extension functions.</p>
<p>This is the technique used in SharpGL 2.0 to import extension functions - the Core/OpenGLExtensions.cs file contains thousands of lines of functions defined like this, however knowing how to invoke any kind of delegate is a useful skill in the managed world, so this trick could be used in other places.</p>
<p>The version of SharpGL this post relates to is at:</p>
<p><a href="http://sharpgl.codeplex.com/SourceControl/changeset/view/4474">http://sharpgl.codeplex.com/SourceControl/changeset/view/4474</a></p>

