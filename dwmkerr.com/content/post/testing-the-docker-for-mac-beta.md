+++
author = "Dave Kerr"
categories = ["Docker", "Unix", "Node.js", "CodeProject"]
date = 2016-06-03T10:45:24Z
description = ""
draft = false
image = "/images/2016/06/Screen-Shot-2016-06-03-at-20-33-20-2.png"
slug = "testing-the-docker-for-mac-beta"
tags = ["Docker", "Unix", "Node.js", "CodeProject"]
title = "Testing the Docker for Mac Beta"

+++


I've finally had a chance to install the new Docker for Mac Beta and give it a whirl. In this article I'm going to talk a bit about how Docker works, the challenges of running Docker on a Mac or Windows and how the new Beta helps.

*Below: The welcome message for the new Docker for Mac app*

![Docker for Mac Icon](/images/2016/06/Screen-Shot-2016-06-03-at-20-33-20.png)

# So What is Docker for Mac?

If you don't know what Docker is, check out my article [Learn Docker by Building a Microservice](http://www.dwmkerr.com/learn-docker-by-building-a-microservice/) or the lovely [What is Docker](https://www.docker.com/what-docker) page from the docs.

You may be aware that Docker creates processes in isolated containers using some key Linux technologies which allow for low-level isolation (such as **namespaces** and **cgroups**[^1]).

This is described in detail on the [Understand the Docker Architecture](https://docs.docker.com/engine/understanding-docker/) page, but essentially means we can do this:

![Docker Running on Ubuntu](/images/2016/06/Docker-on-Ubuntu.png)

Here I have:

1. My machine, called `Dave-Ubuntu`, which is running Ubuntu[^2], with a local IP 192.168.0.1.
2. The `docker` executable, which I use to issue commands to...
3. ...the Docker Host, which runs the docker daemon, which actually does the work of starting/stopping/building containers and so on.
4. Some containers in the Docker Host - one is based on a MySQL image and has a DB, one is based on a Node.js image and is running an app.

The Docker host is actually my machine - I can connect using the loopback IP 127.0.0.1 (i.e. localhost). The containers also have the IP of the host. If I want to create and connect to a MySQL DB from my machine, I just type:

```
docker run -d -e MYSQL_ROOT_PASSWORD=123 -p 3306:3306 mysql
mysql -uroot -p123 -h127.0.0.1
> show databases;
> ...etc...
> exit;
```

The container was created on my machine (in the host) and addressable using my loopback IP.

## So What?

This is all great, but things get a little harder on a Mac or Windows. MacOS and the Windows OS don't have the same kernel level support for process isolation, control groups and so on, so the Docker Host cannot run on these operating systems. Instead, an extra layer and component is introduced:

![Docker on OSX](/images/2016/06/Docker-on-MacOS.png)

What's new?

1. Oracle VirtualBox has been installed to create and manage virtual machines.
2. A virtual machine running Linux (called in this case a 'docker machine') called 'default' has been created (by convention with the IP 192.168.99.100).
3. This virtual machine runs Linux, so can perfectly happily act as the docker host.
4. The docker host is still addressable as 127.0.0.1 - *from the virtual machine* - from the outside world (i.e. my Mac) I have to use the virtual machine IP.

So this is how Docker works on a Mac or on Windows. Things are made seemless where possible, for example, all of the required components are installed when you install the [Docker Toolbox](https://www.docker.com/products/docker-toolbox).

## So What?

Well the problem here is that one of the big benefits of using docker is that it allows us to create development environments which are much closer to production environments (at least from a software point of view).

This kind of breaks down if we are doing development on a Mac or on Windows - because we have introduced an additional component which is simply not going to be present in  our production environment. What are the problems?

### 1. Localhost vs docker-machine IP

Docker helps us be a lot more agnostic to our development box, but if I'm writing about how to interact with docker containers there's a problem:

```
docker run -d -p 8080:8080 my-app-server
curl http://localhost:8080/some-api-call
```

This works on a Linux machine - it does not work on a Mac or Windows. On a Mac I need to run something like:
```
docker run -d -p 8080:8080 my-app-server
curl http://$(docker-machine ip default)/some-api-call
```

This will *not* work on Linux or Windows.

Is this a big deal? Actually, kind of. What if I have an integration test which spins up some containers and runs calls against them - the test has to know about the execution environment. That's a pain. An alternative is to run tests in a container and link them with something like docker-compose, but this is not ideal.

### 2. Terminal Hassle

If I open a terminal and check to see what containers are running:

```
docker ps
```

I'll see nothing. If I try to run a container:

```
docker run -it mongo
```

I'll get an error - because my docker instance cannot communicate with the host. I need to use a specially set up terminal to tell it to connect to the VM.

Again, the Docker Toolkit is set up to try and make things easy. If I install the toolkit I can run an app called Docker Quickstart Terminal:

![Docker Quickstart Terminal](/images/2016/06/Quickstart.jpg)

And this will open a terminal where I *can* use these commands. It will also start the docker machine VM if it has to. It's even smart enough to recognise if I have multiple terminal apps, such as iTerm, and ask which one I want to use.

This problem is - this doesn't always work smoothly. Sometimes it will seem that the machine has started but will still not accept commands. Typically a restart is needed in this scenario.

Also, it's an interruption. If you are running a terminal already and want to issue a quick command, it will fail, unless it was a terminal started with the Docker Quickstart app.

### 3. inotify - In Container Development

If you recognise the term, you probably know the issue. If not, a little explanation is necessary.

As you get more and more familiar with Docker, you will probably find that you are spending more and more time testing, building then running your image in a container. In fact, you might be changing a single code file and using the container as the dev test server on your machine.

This fast gets painful - the container image takes time to build and slows down the development cycle. There's a great technique in this scenario: **In Container Development**.

In container development is pretty much what it sounds like. Instead of editing your code on your machine, building an image and creating a container to debug, you simply create the container with what you need, **mount your code** in to the container and run all of your development tooling from inside the container:

![Docker In Container Development](/images/2016/06/In-Container-Development.png)

In this diagram, I have my code locally on my machine. I have built a container which runs `nodemon`, watching a directory on the container. That directory is actually just a volume containing my code which I have mounted into my container.

This is a really nice technique - I can still code locally, but as I make changes, `nodemon` serves up my new content.

This specific example applies to Node.js, but can be applied to many scenarios.

The problem is that many watcher tools like `nodemon` use a kernel subsystem called `inotify`[^3] to get notifications when files change. But `inotify` doesn't work on virtualbox[^4]. This means that this technique isn't supported for Mac or Windows. There are however some tools which try and work around this with polling.

So now we have another issue. The develop/test process might be nice on Linux, but for devs on other platforms the process is more clunky.

# Docker for Mac and Windows to the rescue

The issues I've mentioned so far are the big ones which cause me problems personally, I'm sure there are others (please comment and let me know!).

This is why there was rather a lot of interest in the new Docker Beta - one of the big features is that the Docker Machine is going away. In theory, we can use Docker on a Mac or Windows and have the same experience as on Linux.

## So how?

Virtualbox is gone. We still need a VM, but this VM is now a very lightweight Alpine Linux based image which runs on xhyve for MacOS and Hyper-V for Windows. All management of this VM is handled *by the docker executable*.

If these are not familiar terms, [Alpine Linux](https://en.wikipedia.org/wiki/Alpine_Linux) is an *extreeeemely* lightweight Linux distro originally design to fit on a floppy disk (I think it clocks at around 5 MB now). [xhyve](https://github.com/mist64/xhyve) is an *extremely* lightweight hypervisor which allows FreeBSD and some other distros on OSX. [Hyper-V](https://en.wikipedia.org/wiki/Hyper-V) is a native hypervisor for Windows Server which can run on Windows 8 onwards[^5].

Using tools specifically designed for each platform (and with the help of both Apple and Microsoft), Docker have been able to make the experience much more seamless and smooth.

# Trying It Out

Removing the three pain points discussed and a clean and simple setup process is what I'm looking at today, and here's the results.

## Installation

Piece of cake. Download the beta, install, run, enter the beta key and pop, there's the new docker:

![Docker Welcome Message](/images/2016/06/Screen-Shot-2016-06-03-at-20-33-20-1.png)

The new status bar icon gives me a way to quickly see the status of the machine. Some of the commands hint at features to come, others offer the instructions needed. Settings are fairly basic, but I'm not sure what else you'd need:

![Status Bar Screenshot 1](/images/2016/06/Screen-Shot-2016-06-04-at-00-09-56.png)

![Status Bar Screenshot 2](/images/2016/06/Screen-Shot-2016-06-04-at-00-10-07.png)

![Status Bar Screenshot 3](/images/2016/06/Screen-Shot-2016-06-04-at-00-10-23.png)

![Status Bar Screenshot 4](/images/2016/06/Screen-Shot-2016-06-04-at-00-44-04.png)

![Status Bar Screenshot 5](/images/2016/06/Screen-Shot-2016-06-04-at-00-44-12.png)

### 1. Localhost vs docker-machine IP

Quickly bashing out the commands below shows that the virtual machine IP address issue is gone:

```
docker run -d -p 3306:3306 -e MYSQL_ROOT_PASSWORD=123 mysql
mysql -uroot -p123 -h127.0.0.1
> show databases;
```

![Localhost Screenshot](/images/2016/06/Screen-Shot-2016-06-03-at-23-37-39.png)

Great news!

How this works under the hood is a mystery to me. If anyone knows, I'd be interested and would like to update this writeup!

### 2. Terminal Hassle

Quick and easy to test - running any terminal any way I like lets me access containers using the `docker` executable - no magic needed:

![Shells](/images/2016/06/Screen-Shot-2016-06-03-at-23-46-57.png)

Here's a screenshot of iTerm3, the Terminal App and the Terminal App running `zsh`, all of which are happily communicating with the docker deamon through the `docker` app.

### 3. In Container Development

I've not thrashed this one too hard, but gone for a quick sanity check. Throwing together probably my best ever node.js app[^6]:

**main.js**
```
setInterval(function() {
    console.log("Goodbye, cruel world!");
}, 1000);
```

and a simple dockerfile:

**Dockerfile**
```
FROM node:6
WORKDIR src/
ADD package.json .
RUN npm install
CMD npm start
```

is enough to test this. I can build then run the container, mounting the working directory into the `src` volume on the container:

```
docker build -t incontainerdev .
docker run -it -v `pwd`:/src incontainerdev]
```

Immediately, I open a new window and change the source code and save (on my local Mac, not in the container). Voila:

![Live Reloading](/images/2016/06/Screen-Shot-2016-06-04-at-00-03-23.png)

Live reloading works without a hitch! `nodemon` picks up my changes, using `inotify` from the VM (all through a lightweight userspace hypervisor).

You know what is cool[^7]? **I don't even need Node.js installed to build this Node app!** The runtime is in the container, all of the execution happens in the container.

# That's a Wrap

That's it for my initial impressions. From this point onwards I'm going to be using Docker for Mac heavily as I'll do all of my work with it installed, so from time to time I may update this article with other observations.

The key takeaway is: at the moment, Docker for Mac just *works*. I'm using it in the same way I would on Ubuntu with no messing around. This is great, it seems like a simple thing but I'm guessing it was a lot of effort from the guys and girls at Docker, Microsoft and Apple.

This is still a Beta, there'll be bugs and they'll be fixed. I can't wait for the Beta to go fully into the wild, and see what exciting things people can do with it.

As usual, any comments or observations are welcome!

**References**

[^1]: Read up on namespaces [here](http://man7.org/linux/man-pages/man7/namespaces.7.html) and cgroups [here](http://man7.org/linux/man-pages/man7/cgroups.7.html). Docker can also use [LXC](https://en.wikipedia.org/wiki/LXC) but no longer *has* to, there's a great write-up [here](https://blog.docker.com/2014/03/docker-0-9-introducing-execution-drivers-and-libcontainer/).
[^2]: Surprise!
[^3]: Read up on inotify [here](http://man7.org/linux/man-pages/man7/inotify.7.html)
[^4]: The issue will not be resolved: https://www.virtualbox.org/ticket/10660
[^5]: I've not used the Docker for Windows Beta yet so have not got first hand experience of it. I've also not looked into compatibility, from memory Hyper-V isn't available on Home versions of Windows, but I might be wrong.
[^6]: Inspired by my first programming book, the excellent [C for Dummies](http://www.amazon.com/C-Dummies-Dan-Gookin/dp/0764570684) by Dan Gookin. 
[^7]: For a given definition of cool.

**Further Reading**

Namespaces: http://man7.org/linux/man-pages/man7/namespaces.7.html
cgroups: http://man7.org/linux/man-pages/man7/cgroups.7.html
Docker Execution Drivers: https://blog.docker.com/2014/03/docker-0-9-introducing-execution-drivers-and-libcontainer/
inotify: http://man7.org/linux/man-pages/man7/inotify.7.html
The challenges of in-container development on OSX: http://hharnisc.github.io/2015/09/16/developing-inside-docker-containers-with-osx.html

