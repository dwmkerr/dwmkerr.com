+++
author = "Dave Kerr"
categories = ["Kubernetes", "Golang", "Devops", "Docker", "CodeProject"]
date = 2018-10-08T21:34:02Z
description = ""
draft = false
image = "/images/2018/10/code-1.jpg"
slug = "manipulating-istio-and-other-custom-kubernetes-resources-in-golang"
tags = ["Kubernetes", "Golang", "Devops", "Docker", "CodeProject"]
title = "Manipulating Istio and other Custom Kubernetes Resources in Golang"

+++


In this article I'll demonstrate how to use Golang to manipulate Kubernetes Custom Resources, with Istio as an example. No knowledge of Istio is needed, I'll just use it to demonstrate the concepts!

![code](/images/2018/10/code-2.jpg)

[Istio](https://istio.io) is a highly popular Service Mesh platform which allows engineers to quickly add telemetry, advanced traffic management and more to their service-based applications.

One interesting element of how Istio works is that when deployed into a Kubernetes cluster, many key configuration objects are handled as [Custom Resources](https://kubernetes.io/docs/concepts/extend-kubernetes/api-extension/custom-resources/). Custom Resources are a very powerful Kubernetes feature, which allow you to create your own 'first class' resources (just like Pods, ReplicaSets, Deployments or whatever) and then interface with them using `kubectl` or the Kubernetes APIs.

In this article I'll show you how to interface with these Custom Resources using the Golang Kubernetes client.

## CRDs: A Quick Overview

When you set up Istio for your cluster, one common thing you will likely do is specify how you will route traffic. This can be quite sophisticated, as shown below:

![TrafficManagementOverview](/images/2018/10/TrafficManagementOverview.svg)

[Figure 1: Istio Traffic Management Examples, from istio.io](https://istio.io/docs/concepts/traffic-management/)

One way for a system like this to be configured would be to have a ConfigMap which contains the definition of how services are routed.

However, Istio actually registers new types of resources (Custom Resource Definitions) which represent things like Gateways or Services. We can create/update/delete/manipulate them just like any other Kubernetes object.

For example, I could create a virtual service for the example above with something like this:

```bash
cat << EOF | kubectl create -f -
apiVersion: networking.istio.io/v1alpha3
kind: VirtualService
metadata:
  name: service2
spec:
  hosts:
  - "*"
  gateways:
  - demo1-gateway
  http:
  - route:
    - destination:
        host: service2
        subset: v1
      weight: 95
    - destination:
        host: service2
        subset: v2
      weight: 5
EOF
```

Again, the important thing is not the specific content of this resource, more the fact that I can treat my Istio resources just like I would any other Kubernetes object:

```
$ kubectl get virtualservices.networking.istio.io
NAME       AGE
service2   93s
```

Or:

```
$ kubectl delete virtualservices.networking.istio.io/service2
```

I can use `edit`, `describe`, register lifecycle events, watch for changes, and so on.

## Working with CRDs in Golang

The [Golang Kubernetes Client](https://github.com/kubernetes/client-go) allows you to create strongly defined types which you can then use to interface with CRDs. An example is in the Red Hat blog post [Kubernetes Deep Dive: Code Generation for Custom Resources](https://blog.openshift.com/kubernetes-deep-dive-code-generation-customresources/).

This is an excellent approach, but can feel pretty heavy if you want to quickly access some data, and don't want to have to generate a lot of code.

There is an alternative, which is to use the [`DynamicClient`](https://github.com/kubernetes/client-go/blob/master/dynamic/interface.go). The _preferred_ approach seems to be the first, which involves code generation, so little documentation exists for the second approach. However, it is actually very simple.

Here's an example of how you can list all Istio `VirtualService` resources, without having to generate any code:

```go
import (
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	"k8s.io/client-go/dynamic"
)

//  Create a Dynamic Client to interface with CRDs.
dynamicClient, _ := dynamic.NewForConfig(config)

//  Create a GVR which represents an Istio Virtual Service.
virtualServiceGVR := schema.GroupVersionResource{
	Group:    "networking.istio.io",
	Version:  "v1alpha3",
	Resource: "virtualservices",
}

//  List all of the Virtual Services.
virtualServices, _ := dynamicClient.Resource(virtualServiceGVR).Namespace("default").List(metav1.ListOptions{})
for _, virtualService := range virtualServices.Items {
	fmt.Printf("VirtualService: %s\n", virtualService.GetName())
}
```

This snippet omits setup and error-handling for clarity, the full example is in the [k8s-list-virtualservices.go](https://gist.github.com/dwmkerr/09ac0fd98595460456e17d5ef0c77667) gist.

## Patching CRDs in Golang

You may have noticed that the `.Resource().Namespace().List()` code looks very similar to the structure for making API calls when using the Kubernetes `Clientset`. In fact, it is essentially the same. Looking at [the interface](https://github.com/kubernetes/client-go/blob/master/dynamic/interface.go), you can see you have all of the operations you'd expect:

- `Create`
- `Update`
- `Delete`
- `Get`

And so on. This is nice because you can use the same trick in my article '[Patching Kubernetes Resources in Golang](https://www.dwmkerr.com/patching-kubernetes-resources-in-golang/)' to manipulate these entities, without ever having to create a structure to represent it.

Here's another abbreviated example, this time showing how we can adjust the weight of the routing from the services to 50%/50%:

```go
import (
	metav1 "k8s.io/apimachinery/pkg/apis/meta/v1"
	"k8s.io/client-go/dynamic"
)

//  Create a GVR which represents an Istio Virtual Service.
virtualServiceGVR := schema.GroupVersionResource{
	Group:    "networking.istio.io",
	Version:  "v1alpha3",
	Resource: "virtualservices",
}

//  Weight the two routes - 50/50.
patchPayload := make([]PatchUInt32Value, 2)
patchPayload[0].Op = "replace"
patchPayload[0].Path = "/spec/http/0/route/0/weight"
patchPayload[0].Value = 50
patchPayload[1].Op = "replace"
patchPayload[1].Path = "/spec/http/0/route/1/weight"
patchPayload[1].Value = 50
patchBytes, _ := json.Marshal(patchPayload)

//  Apply the patch to the 'service2' service.
_, err := dynamicClient.Resource(virtualServiceGVR).Namespace("default").Patch("service2", types.JSONPatchType, patchBytes)
```

See the full example in the gist [k8s-patch-virtualservice.go](https://gist.github.com/dwmkerr/7332888e092156ce8ce4ea551b0c321f)

After running the sample, you can use the Kubernetes CLI to verify the changes:

```
$ kubectl get virtualservices.networking.istio.io/service2 -o yaml
apiVersion: networking.istio.io/v1alpha3
kind: VirtualService
metadata:
  clusterName: ""
  creationTimestamp: 2018-10-08T09:53:16Z
  generation: 0
  name: service2
  namespace: default
  resourceVersion: "487435"
  selfLink: /apis/networking.istio.io/v1alpha3/namespaces/default/virtualservices/service2
  uid: fac5930c-cadf-11e8-90a2-42010a94005b
spec:
  gateways:
  - demo1-gateway
  hosts:
  - '*'
  http:
  - route:
    - destination:
        host: service2
        subset: v1
      weight: 50
    - destination:
        host: service2
        subset: v2
      weight: 50
```

## Keep It Simple!

That's it! This trick made something I was working on a _lot_ easier, but it took a little bit of experimentation to get right. I hope you find the approach useful. Please share any thoughts/questions in the comments.

## Further Reading

The following articles were using in working out this approach:

- [Red Hat: Deep Dive: Code Generation for Custom Resources](https://blog.openshift.com/kubernetes-deep-dive-code-generation-customresources/)
- [Kubernetes Docs: Custom Resources](https://kubernetes.io/docs/concepts/extend-kubernetes/api-extension/custom-resources/)

