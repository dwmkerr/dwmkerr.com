+++
author = "Dave Kerr"
categories = ["Terraform", "AWS", "CodeProject", "Infrastructure"]
date = 2018-12-11T21:24:34Z
description = ""
draft = false
image = "/images/2018/12/screenshot.jpg"
slug = "dynamic-and-configurable-availability-zones-in-terraform"
tags = ["Terraform", "AWS", "CodeProject", "Infrastructure"]
title = "Dynamic and Configurable Availability Zones in Terraform"

+++


When building Terraform modules, it is a common requirement to want to allow the client to be able to choose which region resources are created in, and which availability zones are used.

I've seen a few ways of doing this, none of which felt entirely satisfactory. After a bit of experimentation I've come up with a solution which I think really works nicely. This solution avoids having to know in advance how many availability zones we'll support.

![screenshot](/images/2018/12/screenshot-1.jpg)

To demonstrate, I've set up a module which deploys a cluster of web servers. My goal is to be able to configure the region, VPC CIDR block, subnets and subnet CIDR blocks as below:

```
module "cluster" {
  source            = "github.com/dwmkerr/terraform-aws-vpc"

  # Note how we can specify any number of availability zones here...
  region            = "ap-northeast-2"
  vpc_cidr          = "10.0.0.0/16"
  subnets           = {
    ap-northeast-2a = "10.0.1.0/24"
    ap-northeast-2b = "10.0.2.0/24"
    ap-northeast-2c = "10.0.3.0/24"
  }

  # This just defines the number of web servers to deploy, and uses
  # adds my public key so I can SSH into the servers...
  web_server_count  = "3"
  public_key_path   = "~/.ssh/id_rsa.pub"

}
```

The example module is at [github.com/dwmkerr/terraform-aws-vpc](https://github.com/dwmkerr/terraform-aws-vpc). Let's take a look at some of the key elements.

## The Variables

We define the required variables very explicitly, with descriptions and a variable type to avoid confusion:

```
variable "region" {
  description = "The region to deploy the VPC in, e.g: us-east-1."
  type = "string"
}

variable "vpc_cidr" {
  description = "The CIDR block for the VPC, e.g: 10.0.0.0/16"
  type = "string"
}

variable "subnets" {
  description = "A map of availability zones to CIDR blocks, which will be set up as subnets."
  type = "map"
}
```

## The VPC

Now that we have defined the variables, we can set up the VPC:

```
//  Define the VPC.
resource "aws_vpc" "cluster" {
  cidr_block           = "${var.vpc_cidr}"
  enable_dns_hostnames = true
}

//  An Internet Gateway for the VPC.
resource "aws_internet_gateway" "cluster_gateway" {
  vpc_id = "${aws_vpc.cluster.id}"
}

//  Create one public subnet per key in the subnet map.
resource "aws_subnet" "public-subnet" {
  count                   = "${length(var.subnets)}"
  
  vpc_id                  = "${aws_vpc.cluster.id}"
  cidr_block              = "${element(values(var.subnets), count.index)}"
  map_public_ip_on_launch = true
  depends_on              = ["aws_internet_gateway.cluster_gateway"]
  availability_zone       = "${element(keys(var.subnets), count.index)}"
}

//  Create a route table allowing all addresses access to the IGW.
resource "aws_route_table" "public" {
  vpc_id       = "${aws_vpc.cluster.id}"

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = "${aws_internet_gateway.cluster_gateway.id}"
  }
}

//  Now associate the route table with the public subnet - giving
//  all public subnet instances access to the internet.
resource "aws_route_table_association" "public-subnet" {
  count          = "${length(var.subnets)}"
  
  subnet_id      = "${element(aws_subnet.public-subnet.*.id, count.index)}"
  route_table_id = "${aws_route_table.public.id}"
}
```

There are a few things of interest here. First, we can easily build a variable number of subnets by using the `count` field on the `aws_subnet` resource:

```
resource "aws_subnet" "public-subnet" {
  count                   = "${length(var.subnets)}"
  
  availability_zone       = "${element(keys(var.subnets), count.index)}"
  cidr_block              = "${element(values(var.subnets), count.index)}"
}
```

By using the [Terraform Interpolation Syntax](https://www.terraform.io/docs/configuration/interpolation.html), and in particular the `count`, `keys`, `values` and `element` functions, we can grab the subnet name and CIDR block from the variables.

## The Web Server Cluster

A cluster of web servers behind a load balancer are created by the module, to demonstrate that it works. There is little of interest in the script except for how the subnets are referenced:

```
resource "aws_autoscaling_group" "cluster_node" {
  name                        = "cluster_node"
  vpc_zone_identifier         = ["${aws_subnet.public-subnet.*.id}"]
  launch_configuration        = "${aws_launch_configuration.cluster_node.name}"
}
```

Note that we can specify the entire list of subnet ids by using the `*` symbol in the resource path - `["${aws_subnet.public-subnet.*.id}"]`.

## That's It!

That's really all there is to it. I quite like this approach. I think it makes it very clear what is going on with the infrastructure, and is fairly manageable.

One question which may be raised is why I am not using the [`cidrsubnet`](https://www.terraform.io/docs/configuration/interpolation.html#cidrsubnet-iprange-newbits-netnum-) function to automatically calculate the CIDR blocks for the subnets. The reason is purely one of preference - I prefer to explicitly specify the CIDR blocks and use various patterns to set conventions. For example, if I see an IP address such as `10.0.3.121` then it is in the third AZ of my public subnet, or `10.2.2.11` is in the second AZ of my locked down data zone.

You can see a sample Terraform module which uses this pattern at: [github.com/dwmkerr/terraform-aws-vpc-example](https://github.com/dwmkerr/terraform-aws-vpc-example). This module also has a basic build pipeline and is published on the [Terraform Registry](https://registry.terraform.io/modules/dwmkerr/vpc-example). I'll also be updating my [AWS Openshift](https://github.com/dwmkerr/terraform-aws-openshift) module to use this pattern.

