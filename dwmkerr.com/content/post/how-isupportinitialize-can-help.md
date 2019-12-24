+++
author = "Dave Kerr"
categories = ["C#"]
date = 2011-09-18T14:49:00Z
description = ""
draft = false
slug = "how-isupportinitialize-can-help"
tags = ["C#"]
title = "How ISupportInitialize Can Help"

+++


I have recently come to discover the [ISupportInitialize](http://msdn.microsoft.com/en-us/library/system.componentmodel.isupportinitialize.aspx) interface and found that it is extremely useful when developing more complicated WinForms controls.

Here's the link to the interface on MSDN: [ISupportInitialize](http://msdn.microsoft.com/en-us/library/system.componentmodel.isupportinitialize.aspx) -  here I'll describe how it can be useful.

### The Problem

<p>I have a fairly complicated WinForms usercontrol called 'OpenGLControl', which allows OpenGL commands to be used to render 3D scenes in a C# WinForms application. The control has properties which are interdependent to each other. If these properties are set in the designer, code like this is generated:

```language-csharp
// openGLControl1
// 
this.openGLControl1.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                | System.Windows.Forms.AnchorStyles.Left)
                | System.Windows.Forms.AnchorStyles.Right)));
this.openGLControl1.BitDepth = 32;
this.openGLControl1.DrawRenderTime = true;
this.openGLControl1.FrameRate = 29.41176F;
this.openGLControl1.Location = new System.Drawing.Point(12, 12);
this.openGLControl1.Name = "openGLControl1";
this.openGLControl1.RenderContextType = SharpGL.RenderContextType.NativeWindow;
this.openGLControl1.Size = new System.Drawing.Size(768, 379);
this.openGLControl1.TabIndex = 0;
this.openGLControl1.OpenGLDraw += new System.Windows.Forms.PaintEventHandler(this.openGLControl1_OpenGLDraw);
```
Now this leads to a problem - BitDepth, OpenGLDraw, FrameRate etc must all be declared BEFORE the Size property is set - but how can we control this? Or how can we deal with this situation in general?

This is where the ISupportInitialize interface comes in. If a control is added to the design surface with this interface, we'll get the following code wrapped around the designer code:

```language-csharp
private void InitializeComponent()
{
    System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(FormExample1));
    this.label1 = new System.Windows.Forms.Label();
    this.linkLabel1 = new System.Windows.Forms.LinkLabel();
    this.openGLControl1 = new SharpGL.OpenGLControl();
    ((System.ComponentModel.ISupportInitialize)(this.openGLControl1)).BeginInit();
    this.SuspendLayout();
    //
    //  ...ordianry designer code...
    //
    ((System.ComponentModel.ISupportInitialize)(this.openGLControl1)).EndInit();
    this.ResumeLayout(false);
    this.PerformLayout();
}
```

Now just implement the ISupportInitialize interface in your control - in the 'EndInit' function do any processing that depends on the interdependent properties. This is the earliest point that we can do processing like this. In certain circumstances, knowing about this interface can save you a lot of trouble.

