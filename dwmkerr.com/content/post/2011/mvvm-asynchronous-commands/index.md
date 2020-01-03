---
author: Dave Kerr
type: posts
categories:
- C#
- WPF
- MVVM
date: "2011-10-24T03:51:00Z"
description: ""
draft: false
slug: mvvm-asynchronous-commands
tags:
- C#
- WPF
- MVVM
title: 'MVVM: Asynchronous Commands'
---


<p>The latest cut of the Apex Code (<a href="http://apex.codeplex.com/SourceControl/changeset/changes/6701">http://apex.codeplex.com/SourceControl/changeset/changes/6701</a>) contains a very cool new feature - Asynchronous Command Objects.</p>
<p>An Asynchronous Command is a ViewModelCommand - the standard object used in Apex for commanding. However, what is different about this function is that it runs Asynchronously.</p>
<p>One of the problems with running a view model command asynchronously is that generally the view model properties cannot be accessed - as they're created on a different dispatcher. This problem is resolved by using the 'ReportProgress' function. Here's an example:</p>
<pre class="brush: c-sharp;">public class SomeViewModel : ViewModel
{
  public SomeViewModel()
  {
     // Create the command.
     asyncCommand = new AsynchronousCommand(DoAsyncCommand, true);
  }

  private void DoAsyncCommand()
  {
     for(int i = 0; i &lt; 100; i++)
     {
        // Perform some long operation.
        string message = DoSomeLongOperation();

        // Add the message to the View Model - safely!
        asyncCommand.ReportProgress(
          () =&gt;
          {
             messages.Add(message);
          }
        );
     }
  }
  
  private ObservableCollection&lt;string&gt; messages =
    new ObservableCollection&lt;string&gt;();

  public ObservableCollection&lt;string&gt; Messages
  {
     get { return messages; }
  }

  private AsynchronousCommand asyncCommand;

  public AsynchronousCommand AsyncCommand
  {
     get { return asyncCommand; }
  }
}</pre>
<p class="brush: c-sharp;">In this basic mock-up we have a command called 'AsyncCommand' (which we could bind a button to for example) which invokes DoAsyncCommand. However, it invokes it Asynchronously. We can also update the ViewModel properties by using ReportProgress - meaning AsyncCommands can seamlessly provide live feedback while they're working - and we're keeping well locked in with the MVVM commanding model!</p>
<p class="brush: c-sharp;">Expect a full article soon on the CodeProject, until then the source is at:</p>
<p class="brush: c-sharp;"><a href="http://apex.codeplex.com/SourceControl/changeset/changes/6701">http://apex.codeplex.com/SourceControl/changeset/changes/6701</a></p>

