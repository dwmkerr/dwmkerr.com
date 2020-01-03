---
author: Dave Kerr
type: posts
categories:
- MongoDB
- AWS
- Elastic Beanstalk
- EC2
date: "2015-03-16T10:34:04Z"
description: ""
draft: false
slug: failures-connecting-from-elastic-beanstalk-servers-to-mongodb-on-ec
tags:
- MongoDB
- AWS
- Elastic Beanstalk
- EC2
title: Failures Connecting from Elastic Beanstalk servers to MongoDB on EC?
---


tl;dr?

> Check your mongodb.conf `bind_ip` settings to make sure that you're not allowing connections only from localhost.

This may just end up being the first part of a wider troubleshooting guide, but this is one I've spent a few hours fixing, after assuming I was making terrible mistakes with my security groups.

If you find you cannot connect to your MongoDB server from an EB app server (or anything for that matter), before you spend ages checking your Elastic IP, VPC and Security Group config, don't forget that you may have simply used `bind_ip` in your config file.

Check for:

```
bind_ip = 127.0.0.1
```

Comment it out or remove it and restart:

```
service mongod restart
```

Don't forget to make sure your firewall is still set up correctly - only allow connections from IPs or even better other security groups you trust.

