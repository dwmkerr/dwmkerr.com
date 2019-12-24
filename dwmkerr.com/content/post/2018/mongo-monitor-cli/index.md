---
author: Dave Kerr
categories:
- MongoDB
- Devops
- Node.js
- CodeProject
- DBA
date: "2018-05-16T20:09:53Z"
description: ""
draft: false
image: /images/2018/05/sharded-cluster-1.jpg
slug: mongo-monitor-cli
tags:
- MongoDB
- Devops
- Node.js
- CodeProject
- DBA
title: mongo-monitor - a simple CLI to monitor your MongoDB cluster
---


The `mongo-monitor` CLI is a lean and simple tool to check the status of a MongoDB server or cluster. The code is on GitHub:

[github.com/dwmkerr/mongo-monitor](https://github.com/dwmkerr/mongo-monitor)

Here's how it looks in action:

![Screenshot: Using the mongo-monitor CLI to monitor a sharded cluster](/images/2018/05/overview.gif)

In this animation I am monitoring a simple sharded cluster, and running some example maintenance operations, adding a node to a replicaset, stepping down a primary and shutting down a replicaset node.

A simple CLI which shows the status in real-time can be very useful to keep open when performing admin, letting you see how your changes affect the cluster as you work on it.

## Installing the CLI

The CLI is installed with `npm`:

```bash
npm install -g mongo-monitor
```

## Connecting to a Cluster

Connect to a cluster by providing a connection string. The tool uses [`mongo-connection-string`](https://github.com/dwmkerr/mongo-connection-string) to parse the connection string, so you can be flexible with the input:

```bash
# Connect to a local instance
mongo-monitor localhost:27107

# Connect to a remote replicaset, authenticated
mongo-monitor admin:P@sswrd@mdbnode1,mdbnode2,mdbnode3?replicaSet=rs
```

Once a connection is established, the tool will periodically check the status of the cluster. If the cluster is sharded, it will also inspect each individual replicaset.

## Replicaset Status

Here's the kind of output you might get from a replicaset:

![Screenshot: Replicaset Status](/images/2018/05/replicaset.jpg)

The name of the replicaset is shown, along with each member. The status of each member is also shown, updating automatically every second.

This is convenient when administering replicasets, stepping down a master, adding or removing nodes and so on.

## Sharded Cluster Status

When connecting to a sharded cluster, you will get output like this:

![Screenshot: Sharded Cluster Status](/images/2018/05/sharded-cluster.jpg)

Each shard is shown, along with the details of the replicaset which make it up.

Keeping a view like this open is useful when administering sharded clusters, adding or removing shards, desharding, updating the replicasets which make up shards and so on.

## Get Involved!

If you like the tool, check out the code and feel free to make pull requests with additions! There are a few [issues](https://github.com/dwmkerr/mongo-monitor/issues) on the project already, and there are all sorts of features I'd love to add but haven't had the time, such as:

- Being able to see the lag for replicaset members, to see if secondaries are falling behind
- Being able to perform replicaset operations directly from the tool
- Showing the priorities of nodes if they are not the default

All ideas are welcome, let me know in the comments or repo, and share the tool if you find it useful!

