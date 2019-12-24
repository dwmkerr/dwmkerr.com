+++
author = "Dave Kerr"
categories = ["Docker", "AWS", "CodeProject", "DynamoDB"]
date = 2016-10-27T08:06:00Z
description = ""
draft = false
image = "/images/2016/10/banner-1.jpg"
slug = "run-amazon-dynamodb-locally-with-docker"
tags = ["Docker", "AWS", "CodeProject", "DynamoDB"]
title = "Run Amazon DynamoDB locally with Docker"

+++


**tl;dr:** Run DynamoDB locally using Docker:

```
docker run -d -p 8000:8000 dwmkerr/dynamodb
```

Try it out by opening the shell, [localhost:8000/shell](http://localhost:8000/shell):

![DynamoDB Shell](/images/2016/10/banner.jpg)

That's all there is to it!

## DynamoDB

[Amazon DynamoDB](http://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Introduction.html) is a NoSQL database-as-a-service, which provides a flexible and convenient repository for your services.

Building applications which use DynamoDB is straightforward, there are APIs and clients for many languages and platforms.

One common requirement is to be able to run a local version of DynamoDB, for testing and development purposes. To do this, you need to:

1. Hit the [DynamoDB Local](http://docs.aws.amazon.com/amazondynamodb/latest/developerguide/DynamoDBLocal.html) documentation page
2. Download an archive
3. Extract it to a sensible location
4. Run the extracted JAR, perhaps passing in some options

This can be a little cumbersome if you regularly use DynamoDB, so here's a easier way:


```bash
docker run -p 8000:8000 dwmkerr/dynamodb
```

The `dwmkerr/dynamodb` image runs the JAR in a container, exposing the database on port 8000 by default.

You can see the [image on the Docker Hub](dockeri.co/image/dwmkerr/dynamodb) and the source code at [github.com/dwmkerr/docker-dynamodb](https://github.com/dwmkerr/docker-dynamodb).

## Customising DynamoDB

You can pass any of [the documented commandline flags to DynamoDB](http://docs.aws.amazon.com/amazondynamodb/latest/developerguide/DynamoDBLocal.html). There are instructions on the GitHub page. Here's an example of how you can pass in a data directory, which allows DynamoDB data to be persisted after restarting a container (the image is ephemeral by default, as per [Dockerfile best practices](https://docs.docker.com/engine/userguide/eng-image/dockerfile_best-practices/)).


```bash
docker run -d -p 8000:8000 -v /tmp/data:/data/ dwmkerr/dynamodb -dbPath /data/
```

Running DynamoDB in a container gives an extra degree of flexibility and can speed up your workflow too!

