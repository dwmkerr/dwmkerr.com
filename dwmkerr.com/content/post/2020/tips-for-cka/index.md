---
author: Dave Kerr
type: posts
date: "2020-06-04"
description: "Observations, tips and tricks for the CKA certification"
slug: tips-for-cka
title: "Observations, tips and tricks for the CKA certification"
categories:
- Kubernetes
- SRE
- Docker
- Devops
- CodeProject
tags:
- Kubernetes
- SRE
- Docker
- Devops
- CodeProject
---

In this article I'll share some observations, tips and tricks for the [Linux Foundation's](https://www.linuxfoundation.org/) "[Certified Kubernetes Administrator](https://training.linuxfoundation.org/certification/certified-kubernetes-administrator-cka/) certification and exam.

I've been operating Kubernetes in multiple environments for a few years now. I thought this would be an easy certification to get, but I was surprised by how hard it was!

I took this exam without doing any formal training, I mostly focused on the areas of the curriculum which I knew I was a little weak at. The task-based structure for the exam I thought was really excellent. It took me two attempts to pass, and I learnt a few things along the way.

Here I'll share some thoughts on the certification which hopefully will be useful if you are considering taking it!

<!-- vim-markdown-toc GFM -->

* [Tip: Do the right Certification!](#tip-do-the-right-certification)
* [Tip: Understand the Format!](#tip-understand-the-format)
* [Tip: Know your Vim](#tip-know-your-vim)
* [You need to know the architecture of Kubernetes](#you-need-to-know-the-architecture-of-kubernetes)
* [Tip: You Need to know Linux Sysadmin](#tip-you-need-to-know-linux-sysadmin)
* [Tip: "Dry Run" is your friend](#tip-dry-run-is-your-friend)
* [Tip: Know how to troubleshoot networking](#tip-know-how-to-troubleshoot-networking)
* [Tip: Nail the easy questions quickly](#tip-nail-the-easy-questions-quickly)
* [That's it!](#thats-it)

<!-- vim-markdown-toc -->


## Tip: Do the right Certification!

The CKA exam tests _administration_ and _operation_ skills and techniques for Kubernetes. If you have set up and administered clusters before, this will likely not be too challenging. But if you've never set up a cluster by hand, troubleshot weird issues, fixed clusters and so on, then this is likely going to be very hard.

There is a certification which is much more geared towards developers who use Kubernetes, but don't necessarily administer it - that's the [CKAD](https://www.cncf.io/certification/ckad/) exam and might be the one to take if you are not too familiar with system administration.

## Tip: Understand the Format!

This is not a multiple choice question exam. It's a task based exam, meaning you have about 22 or so specific tasks to complete, in a web browser which has a terminal connected to a cluster.

It is open-book - meaning that you can use the [Kubernetes Documentation](https://kubernetes.io/docs/home/) during the exam. It's not a memory test of specific flags for commands or whatever, it will really require you to work with a running cluster. This means you'll have to be pretty familiar with `kubectl`, `kubeadm` and also Linux in general!

## Tip: Know your Vim

In the two exams I took, `nano` was available. But if you are using `nano` to work with files you may struggle for time.

I spent a _lot_ of time in `vim` in the exam. `vim` is my main text editor for day to day work, so I'm fairly familiar with it. Knowing how to quickly copy a file (lets say for example a file which represents a deployment) and quickly manipulate the text in it will be crucial. Make sure you are going to be using a text editor which you can be efficient in!

You won't be using a graphical text editor to work with files, so being competent in a terminal editor like `vim` or `emacs` could make a big difference. Of course you could install your favourite text editor, but you won't be able to use a graphical editor like VS Code.

Also, as in most Linux distributions, `screen` is available out of the box, and `tmux` can also be installed. If you are familiar with either of these terminal mutliplexers it could save you a tonne of time, for example being able to run `watch -n 5 -d kubectl get pods` in one pane while applying resources in another.

## You need to know the architecture of Kubernetes

This exam will require you to deal with trivial tasks such as running a deployment or creating a volume. But the questions which focus on that tend to only count for one or two percent of the overall grade each. Questions which deal with troubleshooting actual Kubernetes issues could count for six or seven percent each.

This means you _need_ to know how Kubernetes is architecture. The `kubelet` which runs on nodes, the API server, the `etcd` store, all of these things you _have_ to understand how they work and how they fit together.

The online documentation covers the architecture in detail, here's the best place to start:

https://kubernetes.io/docs/concepts/overview/components/

[![Kubernetes Architecture](./images/k8s-architecture.png)](https://kubernetes.io/docs/concepts/overview/components/)

You will need to know how the control plane works, how nodes communicate, how transport of messages works and is secured if you are going to have a chance at dealing with the harder questions.

## Tip: You Need to know Linux Sysadmin

If you are not familiar with `systemctl`, `journalctl`, `apt`, `systemd` units and how the core Kubernetes components are configured, you'll really struggle.

Look over the [CNCF curriculum](https://github.com/cncf/curriculum) - expect to not just have to know how to deal with 'happy path' situations, but also broken clusters, incorrect configuration and so on.

## Tip: "Dry Run" is your friend

One thing which helped me a lot in my second attempt at the exam was the `--dry-run` flag. Before you create resources or change anything, run the operation with the `--dry-run` flag and see whether the output is what you would expect.

This is a quick and easy way to see the changes to the cluster which you are going to apply - and troubleshoot them - before making any actual changes.

## Tip: Know how to troubleshoot networking

Networking in Kubernetes is complex. You must be able to troubleshoot networking issues in the cluster to be able to deal with the more complex tasks.

This means that you should know how to be able to run typical networking tools like `dig`, `nslookup`, `telnet` etc, in the cluster itself.

If you are not familiar with these tools you might need to take an online course in Kubernetes or Linux Networking Administration before considering this certification. The [Linux Certified Systems Administrator](https://training.linuxfoundation.org/certification/linux-foundation-certified-sysadmin-lfcs/) training would be a good place to start.

If you have taken the [Docker Certified Associate](https://success.docker.com/certification) exam then some of this should be familiar. If you are not very familiar with how Docker itself works, you'll likely struggle with Kubernetes.

## Tip: Nail the easy questions quickly

There are a lot of tasks which only count for one or two percent each; these ones you should be able to complete in a few minutes. You'll need all the time in the exam to work on the really hard questions which deal with diagnosing and fixing cluster issues.

Know your core Kubernetes concepts; if you have done the CKAD exam you should be good, if not, check the curriculum and make sure you can quickly complete all of the trivial tasks without wasting too much time.

## That's it!

Hopefully this was helpful! Good luck if you are taking the exam and hopefully you'll find it a challenging but rewarding experience. I've taken many exams over the years but this was one of the most challenging, but also one of the most enjoyable, I really felt like it was testing practical techniques rather than your ability to just remember random commands and flags.

As always, if you have any comments or questions, please just add them in the section below!

![CKA Certification](./images/cka-cert.png)
