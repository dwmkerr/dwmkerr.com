+++
author = "Dave Kerr"
categories = ["Node.js", "Shell", "Unix", "CodeProject"]
date = 2017-05-25T22:15:00Z
description = ""
draft = false
image = "/images/2017/05/wait-port-1.gif"
slug = "a-utility-to-help-you-wait-for-ports-to-open"
tags = ["Node.js", "Shell", "Unix", "CodeProject"]
title = "A utility to help you wait for ports to open"

+++


There are occasions where you might need to have scripts or commands which wait for TCP/IP ports to open before you continue.

I've come across this need again and again when working with [microservices](/tag/microservices/), to make my life easier I've created a little utility called [wait-port](https://github.com/dwmkerr/wait-port) which will wait for a port to open:

[![Wait Port Screenshot](/images/2017/05/wait-port.gif)](https://github.com/dwmkerr/wait-port)

It's built in Node, the project is open source, open for contributions and ready to use:

[github.com/dwmkerr/wait-port](https://github.com/dwmkerr/wait-port)

Installation and usage is pretty straightforward:

```
$ npm install -g wait-port
wait-port@0.1.4

$ wait-port 8080
Waiting for localhost:8080.....
Connected!
```

You can also install locally[^1].

This might be useful if you have a docker-compose workflow where you need to wait for a database to start up, want to run some automated tests against a server which can be slow to start, or have a complex set of interdependent services which need to start up in a specific order.

I'd be interested to know of any cases where people find this useful, so please share in the comments and I can add a 'use cases' section to the project showing others how they might be able to save some time and energy with the utility!

## The Pure Shell Way

It is actually pretty easy to do this purely in bash. Here's how you can wait for a port to open in a shell script:

```bash
until nc -w 127.0.0.1 3000; do sleep 1; done
```

This will be sufficient in many cases, the reason I created the utility is:

1. I want something which is very readable in scripts (`wait-port 3000` to me is more readable).
2. I want to be able to specify an overall timeout (i.e. wait for up to 60 seconds) which requires adding more to the script.
3. I need a different error code if the overall attempt to wait times out or fails for an unknown reason.
4. I want to be able to optionally show some kind of progress (you can use the `--output` flag to control the output from `wait-port`).
5. I know I need a few other features (being able to 'snooze' after the port is opening, i.e. waiting for a little extra time, controllable intervals for trying the port etc, all of which can be easily added).

## Testing Tip!

One really useful tip which will be obvious to *nix pros but I wasn't aware of is that you can create a server listening on a port with `netcat`:

```bash
nc -l 8080
```

This is just the barest basics of what netcat can do, it's a very powerful tool. This tip makes it very easy to test the `wait-port` behaviour.

---

### Footnotes

[^1]: I hate installing things globally, if you are like me you'll prefer local usage with something like: npm install wait-port && ./node_modules/.bin/wait-port :3000</code>

