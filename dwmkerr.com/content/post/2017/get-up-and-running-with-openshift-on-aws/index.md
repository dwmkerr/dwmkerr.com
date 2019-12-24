---
author: Dave Kerr
categories:
- CodeProject
- Terraform
- Docker
- Microservices
- AWS
- OpenShift
date: "2017-02-02T07:47:00Z"
description: ""
draft: false
image: /images/2017/02/console2-1.png
slug: get-up-and-running-with-openshift-on-aws
tags:
- CodeProject
- Terraform
- Docker
- Microservices
- AWS
- OpenShift
title: Get up and running with OpenShift on AWS
---


[OpenShift](https://www.openshift.com/) is Red Hat's platform-as-a-service offering for hosting and scaling applications. It's built on top of Google's popular [Kubernetes](https://kubernetes.io/) system.

Getting up and running with OpenShift Online is straightforward, as it is a cloud hosted solution. Setting up your own cluster is a little more complex, but in this article I'll show you how to make it fairly painless.

![OpenShift Login](/images/2017/02/welcome.png)

The repo for this project is at: [github.com/dwmkerr/terraform-aws-openshift](https://github.com/dwmkerr/terraform-aws-openshift).

## Creating the Infrastructure

OpenShift has some fairly specific requirements about what hardware it runs on[^1]. There's also DNS to set up, as well as internet access and so on.

All in all, for a bare-bones setup, you'll need something like this:

![Network Diagram](/images/2017/02/network-diagram-2.png)

Which is (deep breath):

1. A network
2. A public subnet, with internet access via a gateway
3. A master host, which will run the OpenShift master
4. A pair of node hosts, which will run additional OpenShift nodes
5. A hosted zone, which allows us to configure DNS
6. A bastion, which allows us to SSH onto hosts, without directly exposing them
7. Some kind of basic log aggregation, which I'm using CloudWatch for

This is not a production grade setup, which requires redundant masters and so on, but it provides the basics.

Rather than setting this infrastructure up by hand, this is all scripted with [Terraform](https://www.terraform.io/). To set up the infrastructure, clone the [github.com/dwmkerr/terraform-aws-openshift](https://github.com/dwmkerr/terraform-aws-openshift) repo:

```
$ git clone git@github.com:dwmkerr/terraform-aws-openshift
...
Resolving deltas: 100% (37/37), done.
```

Then use the terraform CLI[^2] to create the infrastructure:

```
$ cd terraform-aws-openshift/
$ terraform get && terraform apply
```

You'll be asked for a region, to deploy the network into, here I'm using `us-west-1`:

![Enter Region](/images/2017/02/Screenshot-at-Feb-02-21-16-44.png)

After a few minutes the infrastructure will be set up:

![Terraform complete](/images/2017/02/output.png)

A quick glance at the AWS console shows the new hosts we've set up:

![AWS Console](/images/2017/02/aws.png)

The next step is to install OpenShift.

## Installing OpenShift

There are a few different ways to install OpenShift, but the one we'll use is called the 'advanced installation[^3]'. This essentially involves:

1. Creating an 'inventory', which specifies the hosts OpenShift will be installed on and the installation options
2. Downloading the advanced installation code
3. Running the advanced installation Ansible Playbook

To create the inventory, we just run:

```bash
sed "s/\${aws_instance.master.public_ip}/$(terraform output master-public_ip)/" inventory.template.cfg > inventory.cfg
```

This takes our 'inventory template[^4]' and populates it with the public IP of our master node, which is recorded in a Terraform output variable.

We can then copy the inventory to the bastion:

```bash
ssh-add ~/.ssh/id_rsa
scp ./inventory.cfg ec2-user@$(terraform output bastion-public_dns):~
```

We can again use the Terraform output variables, this time to get the bastion IP. Finally, we pipe our install script to the bastion host:

```bash
cat install-from-bastion.sh | ssh -A ec2-user@$(terraform output bastion-public_dns)
```

There's a [bug](https://github.com/dwmkerr/terraform-aws-openshift/issues/1) which means you might see `ansible-playbook: command not found`, if so, just run the script again. The install script clones the installation scripts and runs them, using the inventory we've provided:

![Ansible Output](/images/2017/02/ansible.png)

This'll probably take about 10 minutes to run. And that's it, OpenShift is installed:

```bash
open "https://$(terraform output master-public_dns):8443"
```

Hit 'advanced' and continue, as we're using a self-signed certificate most browsers will complain:

![Invalid Certificate](/images/2017/02/console1.png)

Enter any username and password (the system is configured to allow anyone to access it by default) and you'll be presented with the OpenShift console:

![OpenShift console](/images/2017/02/console2.png)

As the setup requires three t2.large instances, which are not available on the free plan, you might want to clean up when you are done with:

```bash
terraform destroy
```

## Wrapping Up

Hopefully you've found this useful, there are more details and references on the README of the github repo:

https://github.com/dwmkerr/terraform-aws-openshift

Comments and feedback are always welcome!

---

[^1]: See https://docs.openshift.org/latest/install_config/install/prerequisites.html#system-requirements
[^2]: Use 'brew install terraform', full instructions in the [README.md](https://github.com/dwmkerr/terraform-aws-openshift)
[^3]: See https://docs.openshift.org/latest/install_config/install/advanced_install.html
[^4]: See https://github.com/dwmkerr/terraform-aws-openshift/blob/master/inventory.template.cfg

