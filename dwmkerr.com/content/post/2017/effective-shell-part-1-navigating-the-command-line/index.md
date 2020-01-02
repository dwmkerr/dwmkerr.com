---
author: Dave Kerr
categories:
- Shell
- Bash
- CodeProject
- EffectiveShell
date: "2017-06-11T23:05:40Z"
description: ""
draft: false
image: /images/2017/06/command-line-2.png
slug: effective-shell-part-1-navigating-the-command-line
tags:
- Shell
- Bash
- CodeProject
- EffectiveShell
title: 'Effective Shell Part 1: Navigating the Command Line'
---


This is the [first part of a series](https://github.com/dwmkerr/effective-shell) I am writing which contains practical tips for using the shell more effectively.

- **[Part 1: Navigating the Command Line](https://www.dwmkerr.com/effective-shell-part-1-navigating-the-command-line/)**
- [Part 2: Become a Clipboard Gymnast](https://www.dwmkerr.com/effective-shell-part-2-become-a-clipboard-gymnast/)
- [Part 3: Getting Help](https://www.dwmkerr.com/effective-shell-part-3-getting-hepl/)
- [Part 4: Moving Around](https://dwmkerr.com/effective-shell-4-moving-around/)
- [Part 5: Interlude - Understanding the Shell](https://dwmkerr.com/effective-shell-part-5-understanding-the-shell/)
- [Part 6: Everything You Don't Need to Know About Job Control](https://dwmkerr.com/effective-shell-6-job-control/)
- [Part 7: The Subtleties of Shell Commands](https://dwmkerr.com/effective-shell-7-shell-commands/)

I can't think of a better place to start than *navigating the command line*. As you start to do more and more in the shell, text in the command line can quickly become hard to handle. In this article I'll show some simple tricks for working with the command line more effectively.

Here's a quick reference diagram, the rest of the article goes into the details!

[![command line](images/command-line-3.png)](https://github.com/dwmkerr/effective-shell)

This article, examples and diagrams are available at [github.com/dwmkerr/effective-shell](https://github.com/dwmkerr/effective-shell).

<!-- TOC depthFrom:2 depthTo:3 withLinks:1 updateOnSave:1 orderedList:0 -->

- [Basic Navigation](#basicnavigation)
- [Searching](#searching)
- [Editing In-Place](#editinginplace)
- [Clear the Screen](#clearthescreen)
- [Pro Tip: All The Keys!](#protipallthekeys)
- [Pro Tip: Transposing!](#protiptransposing)
- [Closing Thoughts](#closingthoughts)

<!-- /TOC -->

## Basic Navigation

Let's assume we have a very simple command we are writing, which is going to write a quote to a text file:

```bash
echo "The trouble with writing fiction is that it has to make sense,
whereas real life doesn't. -- Iain M. Banks" >> quote.txt
```

Navigating around long lines of text is a slow process if you are only relying on the arrow keys, so take the time to learn the following shortcuts:

<table>
<thead>
<tr>
<th>Action</th>
<th>Shortcut</th>
<th>Example</th>
</tr>
</thead>
<tbody>
<tr>
<td>Go to beginning / end</td>
<td><p><code>Ctrl + a</code>,  <code>Ctrl + e</code></td>
<td><a href="images/begin-end.gif" target="_blank"><img src="images/begin-end.gif" alt="begin / end" style="max-width:100%;"></a></td>
</tr>
<tr>
<td>Go backwards / forwards one word</td>
<td><code>Alt + b</code> / <code>Alt + f</code></td>
<td><a href="images/forward-backwards.gif" target="_blank"><img src="images/forward-backwards.gif" alt="backward / forward" style="max-width:100%;"></a></td>
</tr>
<tr>
<td>Delete a word / undo</td>
<td><code>Ctrl + w</code> / <code>Ctrl + -</code></td>
<td><a href="images/delete-undo.gif" target="_blank"><img src="images/delete-undo.gif" alt="delete / undo" style="max-width:100%;"></a></td>
</tr>
<tr>
<td>Delete next word</td>
<td><code>Alt + d</code></td>
<td><a href="images/delete-next-word.gif" target="_blank"><img src="images/delete-next-word.gif" alt="delete next word" style="max-width:100%;"></a></td>
</tr>
<tr>
<td>Delete all the way to the beginning[^1]</td>
<td><code>Ctrl + u</code></td>
<td><a href="images/delete-to-beginning.gif" target="_blank"><img src="images/delete-to-beginning.gif" alt="delete to beginning" style="max-width:100%;"></a></td>
</tr>
<tr>
<td>Delete all the way to the end</td>
<td><code>Ctrl + k</code></td>
<td><a href="images/delete-to-end.gif" target="_blank"><img src="images/delete-to-end.gif" alt="delete to end" style="max-width:100%;"></a></td>
</tr></tbody></table>

Note that if you are on a Mac, you might need to tweak your console to allow the 'Alt' key to work.

For iTerm2, go to settings (Command + ,) > Profiles Tab > select the profile you are using > Keys tab. There, you should see Left Option key and Right Option Key with three radio buttons. Select "Esc+" for the Left Option Key.

For Terminal, go to Profiles Tab > Keyboard Tab > check "Use Option as Meta key" at the bottom of the screen.

## Searching

Once you have the basic navigation commands down, the next essential is searching. Let's assume we've run the following three commands:

```
$ command1 param1 param2 param3
$ command2 param4 param5 param6
$ command3 param7 param8 param9
```

You can search backwards or forwards with `Ctrl + r` and `Ctrl + s`. This will search in the current command and then iteratively through previous commands:

![search backwards and forwards](images/search-backwards-and-forwards.gif)

This is useful for searching in the current command, but can be also used to quickly search backwards and forwards through the command history:

![search commands backwards and forwards](images/search-commands-backwards-and-forwards-1.gif)

As you type, your command history is searched, the most recent commands coming first. Use the arrow keys to edit the command, press enter to execute it, or `Ctrl + g` to cancel the search.

Here are the same commands applied to the original example:

<table>
<thead>
<tr>
<th>Action</th>
<th>Shortcut</th>
<th>Example</th>
</tr>
</thead>
<tbody>
<tr>
<td>Search backwards / forwards</td>
<td><code>Ctrl + r</code> / Ctrl + s</code></td>
<td><a href="images/search-history-next.gif" target="_blank"><img src="images/search-history-next.gif" alt="find next occurrence" style="max-width:100%;"></a></td>
</tr>
<tr>
<td>Run the command</td>
<td><code>Enter</code></td>
<td><a href="images/search-history-execute.gif" target="_blank"><img src="images/search-history-execute.gif" alt="execute" style="max-width:100%;"></a></td>
</tr>
<tr>
<td>Edit the command</td>
<td><code>Right Arrow</code> / <code>Right Arrow</code></td>
<td><a href="images/search-history-edit.gif" target="_blank"><img src="images/search-history-edit.gif" alt="edit command" style="max-width:100%;"></a></td>
</tr>
<tr>
<td>Stop searching</td>
<td><code>Ctrl + g</code></td>
<td><a href="images/search-history-cancel.gif" target="_blank"><img src="images/search-history-cancel.gif" alt="cancel search" style="max-width:100%;"></a></td>
</tr></tbody></table>

## Editing In-Place

These tips and tricks are helpful, but if you are working with a really long or complex command, you might find it useful just to jump into your favourite editor.

Use `Ctrl + x , Ctrl + e` to edit-in place:

![edit in place](images/edit-in-place.gif)

In a later article I'll talk a little more about how to configure the default editor.

## Clear the Screen

Probably the shortcut I use the most is `Ctrl + l`, which clears the screen without trashing your current command. Here's how it looks:

![clear screen](images/clear-screen-2.gif)

## Pro Tip: All The Keys!

You can use the `bindkey` command to see a list of all keyboard shortcuts:

```
$ bindkey
"^@" set-mark-command
"^A" beginning-of-line
"^B" backward-char
"^D" delete-char-or-list
"^E" end-of-line
"^F" forward-char
"^G" send-break
"^H" backward-delete-char
"^I" expand-or-complete
"^J" accept-line
"^K" kill-line
"^L" clear-screen
...
```

This is an extremely useful command to use if you forget the specific keyboard shortcuts, or just want to see the shortcuts which are available.

## Pro Tip: Transposing!

If you've mastered all of the commands here and feel like adding something else to your repertoire, try this:

![transpose-word](images/transpose-word.gif)

The `Alt + t` shortcut will transpose the last two words. Use `Ctrl + t` to transpose the last two letters:

![transpose-letters](images/transpose-letters.gif)

These were new to me when I was researching for this article. I can't see myself ever being able to remember the commands more quickly than just deleting the last two words or characters and re-typing them, but there you go!

## Closing Thoughts

If you are ever looking to go deeper, then search the web for *GNU Readline*, which is the library used under the hood to handle the command line in many shells. You can actually configure lower level details of how all shells which use readline work, with the [`.inputrc`](https://www.gnu.org/software/bash/manual/html_node/Readline-Init-File.html) configuration file.

The great thing about learning these shortcuts is that they will work in any prompt which uses GNU Readline. This means everything you've learnt applies to:

1. Bash
2. zsh
3. The Python REPL
4. The Node.js REPL

And probably a whole bunch more[^2].

All of these shortcuts should be familiar to Emacs users. There is in fact a 'Vi Mode' option for readline, which allows you to use vi commands to work with text. You can enter this mode with `set -o vi`, I'll likely come back to this in detail in a later article.

There's a great cheat sheet on emacs readline commands at [readline.kablamo.org/emacs](http://readline.kablamo.org/emacs.html), which is a very useful reference if you want to dig deeper. For this article I've tried to focus on what I think are the most useful commands (and transpose just so you can show off!).

Hope that was useful! GIFs were made with [LICEcap](http://www.cockos.com/licecap/).

---

#### Footnotes

[^1]: If you are using zsh, then this will clear the entire line.
[^2]: If you know of any more, please let me know and I'll update the article!

#### References

- [Wikipedia: GNU Readline](https://en.wikipedia.org/wiki/GNU_Readline)
- [GNU Org: Readline Init File](https://www.gnu.org/software/bash/manual/html_node/Readline-Init-File.html)
- [Kablamo.org: Readline Cheat Sheet](http://readline.kablamo.org/emacs.html)

