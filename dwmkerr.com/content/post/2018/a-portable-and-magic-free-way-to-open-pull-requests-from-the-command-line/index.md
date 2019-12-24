---
author: Dave Kerr
categories:
- Bash
- Git
- Linux
- CodeProject
date: "2018-10-10T09:17:26Z"
description: ""
draft: false
image: /images/2018/10/gpr-1.png
slug: a-portable-and-magic-free-way-to-open-pull-requests-from-the-command-line
tags:
- Bash
- Git
- Linux
- CodeProject
title: A portable and magic-free way to open Pull Requests from the Command Line
---


This little bash snippet will let you open a GitHub or GitLab pull request from the command line on most Unix-like systems (OSX, Ubuntu, etc), without using any magic libraries, ZSH tricks or other dependencies.

![gpr](/images/2018/10/gpr.png)

Here's how it looks in action OSX:

![gpr](/images/2018/10/gpr.gif)

And Ubuntu:

![gpr-ubuntu](/images/2018/10/gpr-ubuntu.gif)

The script is available as the [`gpr.sh`](https://gist.github.com/dwmkerr/bae3fdca2d7208ec5d0008911d79b47d) gist. You can also find it in my [dotfiles](https://github.com/dwmkerr/dotfiles), in the [git.sh](https://github.com/dwmkerr/dotfiles/blob/master/profile/git.sh) file.

## The Script

Here's the script in its entirety:

```bash
# Colour constants for nicer output.
GREEN='\033[0;32m'
RESET='\033[0m'

# Push the current branch to origin, set upstream, open the PR page if possible.
gpr() {
    # Get the current branch name, or use 'HEAD' if we cannot get it.
    branch=$(git symbolic-ref -q HEAD)
    branch=${branch##refs/heads/}
    branch=${branch:-HEAD}

    # Pushing take a little while, so let the user know we're working.
    echo "Opening pull request for ${GREEN}${branch}${RESET}..."

    # Push to origin, grabbing the output but then echoing it back.
    push_output=`git push origin -u ${branch} 2>&1`
    echo ""
    echo ${push_output}

    # If there's anything which starts with http, it's a good guess it'll be a
    # link to GitHub/GitLab/Whatever. So open it.
    link=$(echo ${push_output} | grep -o 'http.*' | sed -e 's/[[:space:]]*$//')
    if [ ${link} ]; then
        echo ""
        echo "Opening: ${GREEN}${link}${RESET}..."
        python -mwebbrowser ${link}
    fi
}
```

## How It Works

Blow-by-blow, let's take a look.

```bash
# Colour constants for nicer output.
GREEN='\033[0;32m'
RESET='\033[0m'
```

To make colouring console output easier, we create strings with the escape code required to set the 'green' colour, and reset the text colour.

```bash
gpr() {
    # Get the current branch name, or use 'HEAD' if we cannot get it.
    branch=$(git symbolic-ref -q HEAD)
    branch=${branch##refs/heads/}
    branch=${branch:-HEAD}
```

Now we define the `gpr` (Git Pull Request) function. We'll need to push the current branch, so we need to get the current branch name. There's plenty of discussion on how this works on [Stack Overflow: How to get the current branch name in Git](https://stackoverflow.com/questions/6245570/how-to-get-the-current-branch-name-in-git). Essentially we just get the symbolic name for the head of our current branch, which will be something like this:

```
refs/heads/my-new-branch
```

We then use [Bash substring removal](https://www.tldp.org/LDP/abs/html/string-manipulation.html) to rip out the `ref/heads/` part. If we have no branch (for example, we are detached) we just use `HEAD` a the branch name.

Next we have this:

```bash
    # Pushing take a little while, so let the user know we're working.
    echo "Opening pull request for ${GREEN}${branch}${RESET}..."

    # Push to origin, grabbing the output but then echoing it back.
    push_output=`git push origin -u ${branch} 2>&1`
    echo ""
    echo ${push_output}
```

We've previously defined some strings which include the escape codes to colour terminal output. Now we just show the user the branch we're going to push, push it and then store all of the output in the `push_output` variable.

The `2>&1` idiom is a common one. This simply makes sure we put all `stderr` output (which is always file descriptor 2) into `stdout` (which is always file descriptor 1). This means whether the program writes output to `stdout` or `stderr`, we capture it. There's a nice write-up on this in the blog post '[Understanding Shell Script's idiom: 2>&1
](https://www.brianstorti.com/understanding-shell-script-idiom-redirect/)'.

The output from Git push will be dependent on the Git server being used. For GitHub it'll look like this:

```
remote:
remote: Create a pull request for 'feat/doc-cleanup' on GitHub by visiting:
remote:      https://github.com/dwmkerr/dotfiles/pull/new/feat/doc-cleanup
remote:
To github.com:dwmkerr/dotfiles
 * [new branch]      feat/doc-cleanup -> feat/doc-cleanup
Branch feat/doc-cleanup set up to track remote branch feat/doc-cleanup from origin.
```

Now all we want to do is see if there is any text which starts with `http` and if there is, then open it. Here's how we do that:

```bash
    # If there's anything which starts with http, it's a good guess it'll be a
    # link to GitHub/GitLab/Whatever. So open it.
    link=$(echo ${push_output} | grep -o 'http.*' | sed -e 's/[[:space:]]*$//')
    if [ ${link} ]; then
        echo ""
        echo "Opening: ${GREEN}${link}${RESET}..."
        python -mwebbrowser ${link}
    fi
```

This uses `grep` to rip out everything from `http` onwards, and the `sed` to remove any trailing whitespace. If we have found a link, we use `python` to open it (which is a fairly safe cross-platform solution).

That's it! When you have a branch ready which you want to push and create a pull request from, just run:

```bash
gpr
```

And the branch will be pushed to `origin`, and if there is a Pull Request webpage, it'll be opened.

## Prior Art

My colleague Tobias recently shared a nice trick we worked out to open a GitLab merge request - which also now works for GitHub:

<blockquote class="twitter-tweet" data-lang="en"><p lang="en" dir="ltr">git push and directly open PR in Chrome - works for <a href="https://twitter.com/github?ref_src=twsrc%5Etfw">@github</a> &amp; <a href="https://twitter.com/gitlab?ref_src=twsrc%5Etfw">@gitlab</a> 🚀<br><br>Here is how to set it up 👉 <a href="https://t.co/YfNTmdwTFt">https://t.co/YfNTmdwTFt</a> <a href="https://twitter.com/hashtag/github?src=hash&amp;ref_src=twsrc%5Etfw">#github</a> <a href="https://twitter.com/hashtag/gitlab?src=hash&amp;ref_src=twsrc%5Etfw">#gitlab</a> <a href="https://t.co/ISE9kVZmw1">pic.twitter.com/ISE9kVZmw1</a></p>&mdash; Tobias Büschel (@TobiasBueschel) <a href="https://twitter.com/TobiasBueschel/status/1042452158430502915?ref_src=twsrc%5Etfw">September 19, 2018</a></blockquote>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

I wanted to be able to use the same trick in Ubuntu and other Linux distros, but realised it relied on [oh-my-zsh](https://github.com/robbyrussell/oh-my-zsh) and assumed OSX with Chrome as the browser, so tweaked it to the above. Thanks Tobi!

