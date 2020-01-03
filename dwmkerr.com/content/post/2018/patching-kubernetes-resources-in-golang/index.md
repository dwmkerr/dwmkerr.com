---
author: Dave Kerr
type: posts
categories:
- Kubernetes
- Golang
- Devops
- Docker
- CodeProject
date: "2018-07-24T06:33:17Z"
description: ""
draft: false
image: /images/2018/07/patch.jpg
slug: patching-kubernetes-resources-in-golang
tags:
- Kubernetes
- Golang
- Devops
- Docker
- CodeProject
title: Patching Kubernetes Resources in Golang
---


Recently I needed to be able to quickly adjust the number of replicas in a Kubernetes Replication Controller. The original solution I'd seen pulled down the spec, modified it, then updated it. There's a better way!

![Kuberentes Patch API](images/patch-1.jpg)

There's a [patch API for Kubernetes resources](https://kubernetes.io/docs/tasks/run-application/update-api-object-kubectl-patch/). Patching resources is faster and easier than pulling them and updating the spec wholesale. However, the documentation is a little limited.

After some trial and error I got it working, here's the solution. I thought it might be helpful to share for others!

### The Solution

I'll start with the solution. If this is all you need, you are good to go. The details of how this works are presented afterwards. In this example I'll update the number of replicas in the `my-rc` controller:


```go
package main

import (
	"encoding/json"
	"fmt"

	types "k8s.io/apimachinery/pkg/types"
	"k8s.io/client-go/kubernetes"
	_ "k8s.io/client-go/plugin/pkg/client/auth"
	"k8s.io/client-go/tools/clientcmd"
)

var (
	//  Leave blank for the default context in your kube config.
	context = ""

	//  Name of the replication controller to scale, and the desired number of replicas.
	replicationControllerName = "my-rc"
	replicas                  = uint32(3)
)

//  patchStringValue specifies a patch operation for a string.
type patchStringValue struct {
	Op    string `json:"op"`
	Path  string `json:"path"`
	Value string `json:"value"`
}

//  patchStringValue specifies a patch operation for a uint32.
type patchUInt32Value struct {
	Op    string `json:"op"`
	Path  string `json:"path"`
	Value uint32 `json:"value"`
}

func scaleReplicationController(clientSet *kubernetes.Clientset, replicasetName string, scale uint32) error {
	payload := []patchUInt32Value{{
		Op:    "replace",
		Path:  "/spec/replicas",
		Value: scale,
	}}
	payloadBytes, _ := json.Marshal(payload)
	_, err := clientSet.
		CoreV1().
		ReplicationControllers("default").
		Patch(replicasetName, types.JSONPatchType, payloadBytes)
	return err
}

func main() {
	//  Get the local kube config.
	fmt.Printf("Connecting to Kubernetes Context %v\n", context)
	config, err := clientcmd.NewNonInteractiveDeferredLoadingClientConfig(
		clientcmd.NewDefaultClientConfigLoadingRules(),
		&clientcmd.ConfigOverrides{CurrentContext: context}).ClientConfig()
	if err != nil {
		panic(err.Error())
	}

	// Creates the clientset
	clientset, err := kubernetes.NewForConfig(config)
	if err != nil {
		panic(err.Error())
	}

	//  Scale our replication controller.
	fmt.Printf("Scaling replication controller %v to %v\n", replicationControllerName, replicas)
	err = scaleReplicationController(clientset, replicationControllerName, replicas)
	if err != nil {
		panic(err.Error())
	}
}
```

This code is also available in the [k8s-patch.go](https://gist.github.com/dwmkerr/447692c8bba28929ef914239781c4e59) gist.

### The Mechanism

The Kubernetes Patch API supports a few different methods for modifying resources. It is important to be aware that there is not a universally accepted 'standard' approach to representing a *change* to a resource in a REST API.

There are three strategies you can use to patch:

1. `merge`: follows the [JSON Merge Patch Spec (RFC 7386)](https://tools.ietf.org/html/rfc7386)
2. `stragetic`: A strategic merge, which addresses some limitations of the merge patch (noted in [this doc]([docs/devel/api-conventions.md#patch-operations](https://github.com/kubernetes/kubernetes/blob/release-1.1/docs/devel/api-conventions.md#patch-operations)).
3. `json`: follows the [JSON Patch Spec (RFC 6902)](https://tools.ietf.org/html/rfc6902)

These are documented in detail at:

[docs/devel/api-conventions.md#patch-operations](https://github.com/kubernetes/kubernetes/blob/release-1.1/docs/devel/api-conventions.md#patch-operations)

The mechanism I've used here is `json`, which I think is the clearest to the reader. To use this strategy we need to build a payload describing what we are changing. This might look like this:

```json
{
    "op": "replace",
    "path": "/spec/replicas",
    "value": 4
}
```

The `op` field can be `remove`, `replace`, `add` etc etc (all the details are in the [RFC 6902)](https://tools.ietf.org/html/rfc6902), or the slightly more readable [jsonpatch.com](jsonpatch.com)). This allows the operation to be very *explicit* to the reader, which is helpful. We create a struct which represents an operation on a string or integer (or whatever data type we need), serialize it and pass to the API.

Under the hood, the Golang client will simply translate this into an HTTP call which will look like something like this:

```
PATCH /api/v1/namespaces/default/replicationcontrollers/app-server-blue HTTP/1.1
Host: 127.0.0.1
Content-Type: application/json-patch+json
Content-Length: 70

[{
	"op": "replace",
  	"path": "/spec/replicas",
  	"value": 4
}]
```

This corresponds to the documentation on the [Patch Operations](https://github.com/kubernetes/kubernetes/blob/release-1.1/docs/devel/api-conventions.md#patch-operations). Note that the patch operation type is specified in the `Content-Type` header.

Hopefully this'll help you if you need to patch resources, are struggling with the docs and are a Go noob like me! Any tips on how to make the code cleaner or more idomatic would be welcome.

Thanks to the following articles and issues which helped me unpick this:

- [Stack Overflow: Kubernetes Go Client Patch Example](https://stackoverflow.com/questions/43415728/kubernetes-go-client-patch-example)
- [Kubernetes Docs: Update API Objects in Place Using kubectl patch](https://kubernetes.io/docs/tasks/run-application/update-api-object-kubectl-patch/)
- [Kubernetes Docs: Patch Operations](https://github.com/kubernetes/kubernetes/blob/release-1.1/docs/devel/api-conventions.md#patch-operations)

