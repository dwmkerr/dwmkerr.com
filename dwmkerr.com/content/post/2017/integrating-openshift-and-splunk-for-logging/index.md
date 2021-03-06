---
author: Dave Kerr
type: posts
categories:
- OpenShift
- Terraform
- AWS
- CodeProject
- Splunk
- Kubernetes
date: "2017-10-29T07:15:04Z"
description: ""
draft: false
image: /images/2017/10/counter-service-splunk-1.png
slug: integrating-openshift-and-splunk-for-logging
tags:
- OpenShift
- Terraform
- AWS
- CodeProject
- Splunk
- Kubernetes
title: Integrating OpenShift and Splunk for Docker Container Logging
---


In this article I'm going to show you how to set up OpenShift to integrate with Splunk for logging in a Docker container orchestration environment.

These techniques could easily be adapted for a standard Kubernetes installation as well!

![Screenshot: Counter service splunk](images/counter-service-splunk.png)

The techniques used in this article are based on the [Kubernetes Logging Cluster Administration Guide](https://kubernetes.io/docs/concepts/cluster-administration/logging). I also found Jason Poon's article [Kubernetes Logging with Splunk](http://jasonpoon.ca/2017/04/03/kubernetes-logging-with-splunk/) very helpful.

First, clone the [Terraform AWS OpenShift](https://github.com/dwmkerr/terraform-aws-openshift) repo:

```
git clone git@github.com:dwmkerr/terraform-aws-openshift
```

This repo can be used to create a vanilla OpenShift cluster. I'm adding 'recipes' to the project, which will allow you to mix in more features (but still keep the main codebase clean). For now, let's merge in the 'splunk' recipe:

```
cd terraform-aws-openshift
git pull origin recipes/splunk
```

Pulling this recipe in adds the extra config and scripts required to set up Splunk[^1].

Now we've got the code, we can get started!

## Create the Infrastructure

To create the cluster, you'll need to install the [AWS CLI](https://aws.amazon.com/cli/) and log in, and install [Terraform](https://www.terraform.io/downloads.html).

Before you continue, <font color="red">**be aware**</font>: the machines on AWS we'll create are going to run to about $250 per month:

![AWS Cost Calculator](images/aws-cost.png)

Once you are logged in with the AWS CLI just run:

```bash
make infrastructure
```

You'll be asked to specify a region:

![Specify Region](images/region.png)

Any [AWS region](http://docs.aws.amazon.com/AWSEC2/latest/UserGuide/using-regions-availability-zones.html#concepts-available-regions) will work fine, use `us-east-1` if you are not sure.

It'll take about 5 minutes for Terraform to build the required infrastructure, which looks like this:

![AWS Infrastructure](images/splunk-architecture.png)

Once it's done you'll see a message like this:

![Apply Complete](images/apply-complete.png)

The infrastructure is ready! A few of the most useful parameters are shown as output variables. If you log into AWS you'll see our new instances, as well as the VPC, network settings etc etc:

![AWS](images/aws.png)

## Installing OpenShift

Installing OpenShift is easy:

```bash
make openshift
```

This command will take quite some time to run (sometimes up to 30 minutes). Once it is complete you'll see a message like this:

![OpenShift Installation Complete](images/openshift-complete.png)

You can now open the OpenShift console. Use the public address of the master node (which you can get with `$(terraform output master-url)`), or just run:

```bash
make browse-openshift
```

The default username and password is `admin` and `123`. You'll see we have a clean installation and are ready to create our first project:

![Welcome to OpenShift](images/welcome-to-openshift.png)

Close the console for now.

## Installing Splunk

You've probably figured out the pattern by now...

```bash
make splunk
```

Once this command is complete, you can open the Splunk console with:

```bash
make browse-splunk
```

Again the username and password is `admin` and `123`. You can change the password on login, or leave it:

![Splunk Login](images/splunk-home.png)

You can close the Splunk console now, we'll come back to it shortly.

## Demoing Splunk and OpenShift

To see Splunk and OpenShift in action, it helps to have some kind of processing going on in the cluster. You can create a very basic sample project which will spin up two nodes which just write a counter every second as a way to get something running:

```bash
make sample
```

This will create a simple 'counter' service:

![Screenshot: The counter service](images/counter-service.png)

We can see the logs in OpenShift:

![Screenshot: The counter service logs](images/counter-service-logs.png)

Almost immediately you'll be able to see the data in Splunk:

![Screenshot: The Splunk data explorer](images/counter-service-splunk-data-summary.png)

And because of the way the log files are named, we can even rip out the namespace, pod, container and id:

![Screenshot: Counter service splunk](images/counter-service-splunk.png)

That's it! You have OpenShift running, Splunk set up and automatically forwarding of all container logs. Enjoy!

## How It Works

I've tried to keep the setup as simple as possible. Here's how it works.

### How Log Files Are Written

The Docker Engine has a [log driver](https://docs.docker.com/engine/admin/logging/overview/) which determines how container logs are handled[^2]. It defaults to the `json-file` driver, which means that logs are written as a json file to:

```
/var/lib/docker/containers/{container-id}/{container-id}-json.log
```

Or visually:

![Diagram: How Docker writes log files](images/logging-docker-1.png)

Normally we wouldn't touch this file, in theory it is supposed to be used internally[^1] and we would use `docker logs <container-id>`.

In theory, all we need to do is use a [Splunk Forwarder](http://docs.splunk.com/Documentation/Forwarder/7.0.0/Forwarder/Abouttheuniversalforwarder) to send this file to our indexer. The only problem is that we only get the container ID from the file name, finding the right container ID for your container can be a pain. However, we are running on Kubernetes, which means the picture is a little different...

### How Log Files Are Written - on Kubernetes

When running on Kubernetes, things are little different. On machines with `systemd`, the log driver for the docker engine is set to `journald` (see [Kubernetes - Logging Architecture](https://kubernetes.io/docs/concepts/cluster-administration/logging/).

It *is* possible to forward `journald` to Splunk, but only by streaming it to a file and then forwarding the file. Given that we need to use a file as an intermediate, it seems easier just to change the driver back to `json-file` and forward that.

So first, we configure the docker engine to use `json-file` (see [this file](https://github.com/dwmkerr/terraform-aws-openshift/blob/recipes/splunk/scripts/postinstall-master.sh)):

```bash
sed -i '/OPTIONS=.*/c\OPTIONS="--selinux-enabled --insecure-registry 172.30.0.0/16 --log-driver=json-file --log-opt max-size=1M --log-opt max-file=3"' /etc/sysconfig/docker
```

Here we just change the options to default to the `json-file` driver, with a max file size of 1MB (and maximum of three files, so we don't chew all the space on the host).

Now the cool thing about Kubernetes is that it creates symlinks to the log files, which have much more descriptive names:

![Symlink diagram](images/logging-k8s.png)

We still have the original container log, in the same location. But we also have a pod container log (which is a symlink to the container log) and another container log, which is a symlink to the pod container log.

This means we can read the container log, and extract some really useful information from the file name. The container log file name has the following format:

```
/var/log/containers/{container-id}/{container-id}-json.log
```

### How Log Files Are Read

Now that we are writing the log files to a well defined location, reading them is straightforward. The diagram below shows how we use a splunk-forwarder to complete the picture:

![Diagram: How logs are read](images/how-logs-are-read.png)

First, we create a DaemonSet, which ensures we run a specific pod on every node.

The DaemonSet runs with a new account which has the 'any id' privilege, allowing it to run as root. We then mount the log folders into the container (which are owned by root, which is why our container needs these extra permissions to read the files). 

The pod contains a splunk-forwarder container, which is configured to monitor the `/var/log/containers` folder. It also monitors the docker socket, allowing us to see docker events. The forwarder is also configured with the IP address of the Splunk Indexer.

## Footnotes

[^1]: As a reference, you can also see the recipe pull request to see what changes from a 'vanilla' installation to add Splunk: [Splunk Recipe Pull Request](https://github.com/dwmkerr/terraform-aws-openshift/pull/16)

[^2]: It is useful to check the documentation on logging drivers for Docker. See [Configure Logging Drivers](https://docs.docker.com/engine/admin/logging/overview/#supported-logging-drivers) and [Docker Log Driver Plugins](https://docs.docker.com/engine/extend/plugins_logging/). It is possible to create custom log drivers. However, at the time of writing only the journald and json-file log drivers will work with the integrated logging view in OpenShift.

