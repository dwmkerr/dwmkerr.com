---
author: Dave Kerr
categories:
- COM
- SharpShell
- Shell
- C#
date: "2013-01-14T03:47:44Z"
description: ""
draft: false
slug: creating-info-tip-handlers-with-net
tags:
- COM
- SharpShell
- Shell
- C#
title: Creating Info Tip Handlers with .NET
---


I have just added an article to the CodeProject that discusses how to create Info Tip shell extensions in .NET. These extensions are used by the shell to customise the tooltips shown over shell items.


<a href="http://www.dwmkerr.com/2013/01/creating-info-tip-handlers-with-net/shellinfotiphandler/" rel="attachment wp-att-210"><img src="http://www.dwmkerr.com/wp-content/uploads/2013/01/ShellInfoTipHandler.png" alt="ShellInfoTipHandler" width="385" height="160" class="alignnone size-full wp-image-210" /></a>

The article shows how you can use <a title="SharpShell on CodePlex" href="http://sharpshell.codeplex.com">SharpShell </a>to very quickly create these extensions, you can find it at: <a title="Shell Info Tip Handlers" href="http://www.codeproject.com/Articles/527058/NET-Shell-Extensions-Shell-Info-Tip-Handlers">http://www.codeproject.com/Articles/527058/NET-Shell-Extensions-Shell-Info-Tip-Handlers</a>.

So just how easy does SharpShell make creating Shell Info Tip Handlers? The answer is pretty easy indeed. The code below shows the <strong>full </strong>implementation of a Shell Info Tip Handler that changes the tooltips for folders to show the name of the folder and the number of items it contains:

[csharp]/// &lt;summary&gt;
/// The FolderInfoTip handler is an example SharpInfoTipHandler that provides an info tip
/// for folders that shows the number of items in the folder.
/// &lt;/summary&gt;
[ComVisible(true)]
[COMServerAssociation(AssociationType.Directory)]
public class FolderInfoTipHandler : SharpInfoTipHandler
{
    /// &lt;summary&gt;
    /// Gets info for the selected item (SelectedItemPath).
    /// &lt;/summary&gt;
    /// &lt;param name=&quot;infoType&quot;&gt;Type of info to return.&lt;/param&gt;
    /// &lt;param name=&quot;singleLine&quot;&gt;if set to &lt;c&gt;true&lt;/c&gt;, put the info in a single line.&lt;/param&gt;
    /// &lt;returns&gt;
    /// Specified info for the selected file.
    /// &lt;/returns&gt;
    protected override string GetInfo(RequestedInfoType infoType, bool singleLine)
    {
        //  Switch on the tip of info we need to provide.
        switch (infoType)
        {
            case RequestedInfoType.InfoTip:
 
                //  Format the formatted info tip.
                return string.Format(singleLine
                                       ? &quot;{0} - {1} Items&quot;
                                       : &quot;{0}&quot; + Environment.NewLine + &quot;Contains {1} Items&quot;,
                                       Path.GetFileName(SelectedItemPath), Directory.GetFiles(SelectedItemPath).Length);
 
            case RequestedInfoType.Name:
                
                //  Return the name of the folder.
                return string.Format(&quot;Folder '{0}'&quot;, Path.GetFileName(SelectedItemPath));
                
            default:
 
                //  We won't be asked for anything else, like shortcut paths, for folders, so we 
                //  can return an empty string in the default case.
                return string.Empty;
        }
    }
} [/csharp]

As you can see, all of the COM interfaces are hidden away and handled for you, there is no ugly pinvoke code and no use of strange structures imported from Win32. SharpShell handles all of the plumbing for you.

