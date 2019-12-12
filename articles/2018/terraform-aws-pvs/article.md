todo:

aws abs (https://docs.openshift.org/latest/install_config/persistent_storage/dynamically_provisioning_pvs.html#aws-elasticblockstore-ebs)

consider aws EFS

# Dynamic Volume Provisioning with OpenShift and AWS

I maintain a project called [`terraform-aws-openshift`](https://github.com/dwmkerr/terraform-aws-openshift), which you an use to quickly spin up the infrastructure required to run [OpenShift](TODO) on AWS. It also contains scripts which allow you to quickly install OpenShift on the infrastructure, maiking it very easy to set up your own environment.

One challenge with the project is that until recently, [Persistent Volumes]() were not supported, meaning that containers which need to persist data to disk were very difficult to set up.

A great pull request came in from (TODO)[TODO] to add support for Dynamically Provisioned Persistent Volumes on AWS. This is a very cool feature which has been available in Kubernetes (which is what OpenShift is built on) for a while. I thought it would be good to show how the feature works with a small example.

## How Docker Volumes Work

Maintaining state in Docker containers can be a little challenging. Containers themselves can write data to disk, but this data will be lost if the container is destroyed. Modern container orchestration platforms expect to be able to shut down containers at will, and then restart them on new worker nodes. It is also common to run many instances of a container across many nodes, for high-availability and high-performance.

Containers can persist data to disk, which lasts beyond the lifetime of the container if a *volume* is mounted into the container. A volume gives a container a location to write to which is on the *host* machine, this allows a container to write persistent data.

Without a volume being mounted into the container, a database container might look like this:

![Diagram 1: Local data only](TODO)

When we mount a volume, we can point the same location on the file system to a directory on the host machine, allowing us to persist the data outside of the container:

![Diagram 2: Mounted Volume](TODO)

We now have a setup where we can have a database process (or any process in the container) write to the local filesystem, which is really just a mounted volume from the host machine. 

## The Challenges of Storage and Orchestration

The process looks simple in a trivial example like thie above, but in more realistic setups, we have many nodes we are running containers on, and many different containers in each node:

![Diagram 3: Real world volume mounts](TODO)

In this example three contains on each node are writing data (they are marked in bold).

If one container dies, and is scheduled on the alternative node, it no longer has access to the data on the file system of the original node.

Even worse, if one of the *nodes* dies, then the persistent storage for the three containers on the node may be lost.

There are other challenges, some subtle, some less so, but this is the one we'll look at in this article.

## Making Storage Available and Durable

The challenges of making file and block storage durable and highly available are not new for engineers and operators - as long as persistent storage has been around there have been various techniques to solve these problems. Some of them we can apply with very little effort.

One good solution is to use something like a NAS (Network Address Storage)[TODO]. The storage itself is on a dedicated system, and much like a host volume being mounted into a container, we can mount a NAS volume into host:

![Diagram 4: NAS](TODO)

Just like in the earlier example, this process is transparent to the host, the directoy works just like any other.

There are various techniques to make the file system on a NAS more durable, such as RAID striping or scheduled backups, and most NAS boxes are easily set up with these options (as durable storage is one of the main reasons a NAS is used).

Another great feature of a NAS is the fact that we can mount it's volumes into multiple hosts:


