+++
author = "Dave Kerr"
date = 2012-12-15T00:21:48Z
description = ""
draft = false
slug = "visual-studio-extensions-and-menu-subitems"
title = "Visual Studio Extensions and Menu Subitems"

+++


<p>Recently I was working on a Visual Studio Extension for VS2010, instead of a single item in the the tools menu, what I wanted was a single item with a set of child items.</p>
<p>Strangely enough, documentation on this is quite lacking. So if you need to know how to do it, here's the gist. First, create a standard Visual Studio 2010 extension with the wizard, we'll have some code like the below to start off with, in the OnConnection function:</p>
<p>[csharp]object []contextGUIDS = new object[] { };
Commands2 commands = (Commands2)_applicationObject.Commands;
string toolsMenuName = &quot;Tools&quot;;

//Place the command on the tools menu.
//Find the MenuBar command bar, which is the top-level command bar holding all the main menu items:
Microsoft.VisualStudio.CommandBars.CommandBar menuBarCommandBar = ((Microsoft.VisualStudio.CommandBars.CommandBars)_applicationObject.CommandBars)[&quot;MenuBar&quot;];

//Find the Tools command bar on the MenuBar command bar:
CommandBarControl toolsControl = menuBarCommandBar.Controls[toolsMenuName];
CommandBarPopup toolsPopup = (CommandBarPopup)toolsControl;

//This try/catch block can be duplicated if you wish to add multiple commands to be handled by your Add-in,
//  just make sure you also update the QueryStatus/Exec method to include the new command names.
try
{
	//Add a command to the Commands collection:
    Command command = commands.AddNamedCommand2(_addInInstance, &quot;MyCommand&quot;, &quot;MyCommand&quot;,
        &quot;Executes the command for MyCommand&quot;, true, 59, ref contextGUIDS,
        (int)vsCommandStatus.vsCommandStatusSupported + (int)vsCommandStatus.vsCommandStatusEnabled,
        (int)vsCommandStyle.vsCommandStylePictAndText, vsCommandControlType.vsCommandControlTypeButton);

   	//Add a control for the command to the tools menu:
	if((command != null) &amp;&amp; (toolsPopup != null))
	{
		command.AddControl(toolsPopup.CommandBar, 1);
	}
}
catch(System.ArgumentException)
{
	//If we are here, then the exception is probably because a command with that name
	//  already exists. If so there is no need to recreate the command and we can
    //  safely ignore the exception.
}[/csharp]
<p>Now what we're going to do first, is change the code so that we don't actually add a Command named MyCommand, but instead a popup:</p>
<p>[csharp]//This try/catch block can be duplicated if you wish to add multiple commands to be handled by your Add-in,
//  just make sure you also update the QueryStatus/Exec method to include the new command names.
try
{
	//  Have we got the tools popup?
	if(toolsPopup != null)
	{
        //  Create 'MyCommand' as a popup.
        var popup = (CommandBarPopup)toolsPopup.Controls.Add(MsoControlType.msoControlPopup);
        popup.Caption = &quot;MyCommand&quot;;
	}
}
catch(System.ArgumentException)
{
	//If we are here, then the exception is probably because a command with that name&lt;br /&gt;
	//  already exists. If so there is no need to recreate the command and we can&lt;br /&gt;
    //  safely ignore the exception.
}[/csharp]
<p>Now that we have the popup object, we can create commands and add them to the popup instead:</p>
<p>[csharp]//  Have we got the tools popup?
if(toolsPopup != null)
{
    //  Create 'MyCommand' as a popup.
    var popup = (CommandBarPopup)toolsPopup.Controls.Add(MsoControlType.msoControlPopup);
    popup.Caption = &quot;MyCommand&quot;;
    //  Create sub item 1.
    var subItem1Command = commands.AddNamedCommand2(_addInInstance, &quot;MyCommand1&quot;, &quot;My Command Subitem 1&quot;, &quot;My Command Subitem 1&quot;,
                                                       true, 59, ref contextGUIDS);
    //  Add it.
    subItem1Command.AddControl(popup.CommandBar, 1);
    //  Create sub item 2.
    var subItem2Command = commands.AddNamedCommand2(_addInInstance, &quot;MyCommand2&quot;, &quot;My Command Subitem 2&quot;, &quot;My Command Subitem 2&quot;,
                                                       true, 59, ref contextGUIDS);
    //  Add it.
    subItem2Command.AddControl(popup.CommandBar, 2);
}[/csharp]</p>
<p>Now that we have made these changes, if we run the addin, we get a menu structure like this:</p>
<a href="http://www.dwmkerr.com/wp-content/uploads/2012/12/CommandSubitems.jpg"><img src="http://www.dwmkerr.com/wp-content/uploads/2012/12/CommandSubitems.jpg" alt="" title="CommandSubitems" width="511" height="47" class="alignnone size-full wp-image-177" /></a>

