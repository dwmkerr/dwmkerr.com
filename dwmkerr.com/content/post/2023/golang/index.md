---
author: Dave Kerr
type: posts
date: "2023-03-01"
description: ""
slug: TODO
title: TODO
categories:
- "golang"
- "node"
- "javascript"
- "java"
- "dotnet"
tags:
- "CodeProject"
- "dotnet"
- "java"
- "javascript"
- "node"
- "node"
- "nodejs"
---


## installation and setup

GOROOT
src
bin

## Source Code Structure

## Modules

TODO definition of a module

## Packages

TODO definition of a packge

## The go.mod File


```gomod
module github.com/dwmker/jac

go 1.20
```

## Protobufs

Note that if using:

```bash
protoc --go_out=. --go-grpc_out=. --go-grpc_opt=paths=source_relative proto/*.proto
```

You will spit out a folder tree like `github.com/dwmkerr/whatever`, to omit this tree and use relative paths, include the options below:

```bash
protoc --go_out=. --go_opt=paths=source_relative --go-grpc_out=. --go-grpc_opt=paths=source_relative proto/*.proto
```
" Press ? for help

.. (up a dir)
</dwmkerr/repos/golang/jac/
▾ github.com/dwmkerr/jac/pb/
    jac.pb.go
    jac_grpc.pb.go
▾ proto/
    jac.proto
▾ server/
    main.go

For developing applications:

- Code directory in home
- Can do it in any folder
- To make it public, use the github url, e.g. github.com/dwmkerr/jac
- one top level module, e.g. 'jac', then the subfolders are packages
- (For reference, multi-module workspaces might be worth looking at)

If I do for example:

```bash
mkdir ~/repos/golang/jac
cd ~/repos/golang/jac
go mod init github.com/dwmkerr/jac
```

Now when I import:

```go
import (
    "github.com/dwmkerr/jac" // is would download from github
)
```

If I did `go mod get` then I can download the package locally, or `go mod install` to 

go mod

```
go mod
go mod tidy
```

- vendor directory
- code organisation
- where to run go mod
- what is go.mod
- most simple folder structure

## Packages

Anything with lowercase is private, anything starting with Uppercase is public and exported

## Client / Server

Simple echo server
