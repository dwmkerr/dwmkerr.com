# mongo-monitor - a simple CLI to monitor your MongoDB cluster

I've used MongoDB in a number of projects over the years. One thing I've often found I need is a quick way to see the topology and status of a cluster. I recently cleaned up a small utlity I've used over the years to
check the status of a cluster.

The animation below shows the tool monitoring a sharded cluster while I make changes in realtime - adding a node to a replicaset, stepping down a primary and shutting down a replicaset node:

![Screenshot: Using the mongo-monitor CLI to monitor a sharded cluster](/content/images/2018/05/overview.gif)

The view updates real time, which makes it very helpful if you are performing server administation such as reconfiguring a replicaset or sharded cluster. As you make changes through the shell you can see how the cluster responds.

## Installing the CLI

The CLI is installed with `npm`:

```bash
npm install -g mongo-monitor-cli
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

![Screenshot: Replicaset Status](/content/images/2018/05/replicaset.jpg)

The name of the replicaset is shown, along with each member. The status of each member is also shown, updating automatically every second.

This is convenient when administering replicasets, stepping down a master, adding or removing nodes and so on.

## Sharded Cluster Status

When connecting to a sharded cluster, you will get output like this:

![Screenshot: Sharded Cluster Status](/content/images/2018/05/sharded-cluster.jpg)

Each shard is shown, along with the details of the replicaset which make it up.

Keeping a view like this open is useful when administering sharded clusters, adding or removing shards, desharding, updating the replicasets which make up shards and so on.

## Get Involved!

If you like the tool, check out the code and feel free to make pull requests with additions! There are a few [issues](https://github.com/dwmkerr/mongo-monitor/issues) on the project already, and there are all sorts of features I'd love to add but haven't had the time, such as:

- Being able to see the lag for replicaset members, to see if secondaries are falling behind
- Being able to perform replicaset operations directly from the tool
- Showing the priorities of nodes if they are not the default

All ideas are welcome, let me know in the comments or repo, and share the tool if you find it useful!
