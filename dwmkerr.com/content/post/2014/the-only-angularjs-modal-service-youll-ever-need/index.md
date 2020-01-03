---
author: Dave Kerr
type: posts
categories:
- AngularJS
- CodeProject
- Javascript
- Bootstrap
date: "2014-06-16T00:48:12Z"
description: ""
draft: false
slug: the-only-angularjs-modal-service-youll-ever-need
tags:
- AngularJS
- CodeProject
- Javascript
- Bootstrap
title: The Only AngularJS Modal Service You'll Ever Need
---


If you need modals in an AngularJS application, look no further. I'll show you how to use the [Angular Modal Service](https://github.com/dwmkerr/angular-modal-service) to add Bootstrap Modals or your own custom modals to your application.

[See it in a fiddle](http://jsfiddle.net/dwmkerr/8MVLJ/) or check out [a full set of samples online](http://dwmkerr.github.io/angular-modal-service).

#### Contents

1. [Using the Angular Modal Service](#UsingTheAngular ModalService)
2. [A Quick Example](#AQuickExample)
3. [Design Goals](#DesignGoals)
4. [How It Works](#HowItWorks)
5. [Wrapping Up](#WrappingUp)

## Using the Angular Modal Service

Here's how you can use the Angular Modal Service to add a bootstrap modal to your application.

#### Step 1: Install with Bower

Install the service with bower:

```
bower install angular-modal-service --save
```

If you don't use bower, just get the source directly from the [`dst`](https://github.com/dwmkerr/angular-modal-service/tree/master/dst) folder of the repo.

#### Step 2: Include the JavaScript

Include the JavaScript from the `dst` folder or require it with require.js:

```language-html
<script src="bower_components\angular-modal-service\dst\angular-modal-service.min.js"></script>
```

#### Step 3: Add it as a dependency

Make sure the `angularModalService` module is listed as a required module for your application:

```language-javascript
var app = angular.module('myApp', ['angularModalService']);
```

#### Step 4: Show the Modal

Inject `ModalService` into any controller, directive or service and call the `showModal` function to show a modal:

```language js
app.controller('SampleController', function($scope, ModalService) {

  ModalService.showModal({
    templateUrl: "template.html",
    controller: "ModalController"
  }).then(function(modal) {
    
    //it's a bootstrap element, use 'modal' to show it
    modal.element.modal();
    modal.close.then(function(result) {
      console.log(result);
    });
  });

);
```

This code loads the HTML from `template.html`, adds it to the DOM, creates a scope for it and creates an instance of a `ModalController`.

When this is done, the promise returned by the `showModal` function resolves and you get a `modal` object. This object contains the element created. If it's a Bootstrap modal just call `modal` to show it, if it's a custom one you can show it by changing its CSS styles or using whatever APIs are provided. There's an example ofa custom modal in [the samples](http://dwmkerr.github.io/angular-modal-service/).

#### Step 5: Close the Modal

The controller that is created always has one extra parameter injected into it - a function called `close`. Call this function to close the modal, anything you pass to it is passed to the caller as the `result` object.

```language js
app.controller('ModalController', function($scope, close) {
  
  // when you need to close the modal, call close
  close("Success!");
});
```

You can pass a number of milliseconds to wait before destroying the DOM element as an optional second parameter to `close` - this is useful if the closing of the modal is animated and you don't want it to disappear before the animation completes.

## A Quick Example

Here's a fiddle of the modal service in action:

<iframe width="100%" height="300" src="http://jsfiddle.net/dwmkerr/8MVLJ/embedded/result,js,html" allowfullscreen="allowfullscreen" frameborder="0"></iframe>

One thing to note in this examples is that the template is just declared in the DOM - this works fine because the service always checks the template cache before attempting to load it from the server.

There are more examples at [dwmkerr.github.io/angular-modal-service](http://dwmkerr.github.io/angular-modal-service/).

## Design Goals

There are some other services for handling modals out there, notably [Fundoo's Modal Service](https://github.com/Fundoo-Solutions/angularjs-modal-service) and a few others. However, the design goals for my service were slightly different:

1. **No link to bootstrap**. Bootstrap modals are complex with lots of options - if you want to use them then that's great, the service should work with them, but the complexity of the options for Bootstrap Modals should not increase the complexity of the service.
2. **Extremely simple code**. It's rare you'll write something that it will suit everyone's need. Rather than trying to please everyone, I want a service that is simple enough to understand so that it can be easily adapted by others.

So the core goal here is simplicity - if others can understand the code, then they can more effectively decide whether it's what they need, or build upon it.

With these design goals in mind I built the angular modal service.

## How It Works

I'm going to walk through a slightly simplified version of the code because it actually illustrates quite a few important concepts when working with AngularJS.

One of the things that's useful to know is that this service creates a DOM element, builds a scope for it and instantiates a controller for it - what we're doing is *very* similar to what AngularJS does behind the scenes when a directive is created.

So let's dive in. We're going to define a service, so we need a module.

```language-javascript
var module = angular.module('angularModalService', []);
```

Now we have our module, we can define our service. I tend to write services in the form of classes, but this is a personal choice - it's just as valid to return a javascript object that contains functions and data.

```language-javascript
module.factory('ModalService', ['$document', '$compile', '$controller', '$http', '$rootScope', '$q', '$timeout',
    function($document, $compile, $controller, $http, $rootScope, $q, $timeout) {
```

I need a lot of injected components, we'll see why as we continue. I also use the explicit form of the function which takes the parameters as strings - this is the only safe way to write an injected function if you are minifying code.

```language-javascript
    var body = $document.find('body');    
    function ModalService() {
      var self = this;
```

I use the `$document` object to get the body element, which the modal will be appended to. I then create a class function and record `this` as self, so that I can refer to the class instance in callbacks and so on.

The next part of the code creates a function that will return the template, given either a raw template string or a template url. The reason we wrap this function like this is that the operation will either be synchronous or asynchronous, and I don't want the caller to care. So we use promises to wrap the logic.

```language-javascript
var getTemplate = function(template, templateUrl) {
  var deferred = $q.defer();
  if(template) {
    deferred.resolve(template);
  } else if(templateUrl) {
    $http({method: 'GET', url: templateUrl, cache: true})
    .then(function(result) {
      deferred.resolve(result.data);
    })
    .catch(function(error) {
      deferred.reject(error);
    });
  } else {
    deferred.reject("No template or templateUrl has been specified.");
  }
  return deferred.promise;
};
```

If any of this seems confusing, check out my article [AngularJS Promises - The Definitive Guide](http://www.dwmkerr.com/promises-in-angularjs-the-definitive-guide/).

Now to the main function.

```language-javascript
self.showModal = function(options) {        
  var deferred = $q.defer();
```

The `showModal` function is going to have to do all sorts of async work - loading the template from the server and so on. So we are going to create a `deferred` object and build a promise to return to the caller.

```language-javascript
var controller = options.controller;
if(!controller) {
  deferred.reject("No controller has been specified.");
  return deferred.promise;
}
```

Now we validate that a controller has been passed in as part of the options. Notice how just like in `getTemplate` we use the `reject` function to deal with error cases. Again, if error handling with promises seems unfamiliar, check out [AngularJS Promises - The Definitive Guide](http://www.dwmkerr.com/promises-in-angularjs-the-definitive-guide/).

Next we deal with the template.

```language-javascript
getTemplate(options.template, options.templateUrl)
  .then(function(template) {
```

We've used the `getTemplate` function to get the template, sync or async it doesn't matter, our logic is the same.

Now we can build a new scope for our modal.

```language-javascript
var modalScope = $rootScope.$new();
```

We'll refer to this a lot later on. Now for some cleverness.

```language-javascript
var closeDeferred = $q.defer();
var inputs = {
  $scope: modalScope,
  close: function(result, delay) {
    if(delay === undefined || delay === null) delay = 0;
    $timeout(function () {
      closeDeferred.resolve(result);
    }, delay);
  }
};
```

This requires some explanation. First, we create a new `deferred` object. This is going to be used to build a promise that is resolved when the modal closes.

Now we build an `input` object. This contains parameters we want to inject to the controller we're going to create. Any parameters the controller needs, such as `$element`, `$timeout` or whatever will be injected by angular. We're just going to make sure that the `$scope` that is injected is the one we've just created, and that we also inject a function called 'close'. This function simply resolves the promise we've created after a specified timeout.

This means that any controller for a modal can take `close` as a parameter, and we'll inject the function that resolves the promise. This promise is returned to the consumer so that they can take action when the modal closes. We also allow the controller to pass a variable to `close` which is passed to the `resolve` function as well.

```language-javascript
if(options.inputs) {
  for(var inputName in options.inputs) {
    inputs[inputName] = options.inputs[inputName];
  }
}
```

Without the this code, the service is close to useless. What we do here is allow the caller to provide extra inputs to the controller. Imagine we have a list of items, maybe books for a library program, and when the use clicks on one we want to show a modal. The code that shows the modal needs to pass the selected book to the modal controller - by adding it to the `inputs` object, the book can be injected into the controller. This allows to client to pass data **to** the controller, with the parameter of the `close` function used to return data **from** the controller.

Ready for some lower level Angular?

```language-javascript
var modalController = $controller(controller, inputs);
var modalElementTemplate = angular.element(template);
var linkFn = $compile(modalElementTemplate);
var modalElement = linkFn(modalScope);
```

Four innocuous lines that are actually quite complex.

1. First, we create an instance of the controller with name `controller`. Regardless of what AngularJS injects, we provide `inputs` to be injected as well.
2. Now we turn our raw template html into an AngularJS DOM element. AngularJS always works with jQuery or jQuery Lite elements, the `angular.element` function takes raw HTML and turns it into a DOM element we can work with.
3. Now we `$compile` the element. This step goes over the DOM and expands all directives. We're turning raw DOM elements into elements that are expanded into directives, but we haven't yet linked this set of elements into a scope. This is the first step of the compile/link process.
4. Finally, we can link the element. The `$compile` function returns a link function which we call with a scope to link the DOM elements (fully expanded) to the specified scope.

This is very similar to AngularJS actually handles directives itself - creating a scope, loading a template, turning it into an element, compiling it and linking it.

Why are compile and link separate steps? Think of it like this, the work that is done in compile is actually identical for each instance of a directive (or modal in our case). It's not related to an *instance* of a directive or modal, it's just expanding the elements and directives. So this work can be done once only, saving a lot of time - then we just call link to create an *instance* of our element, bound to a specific scope. So link logic is always per instance (you have a scope, you can `$watch` and so on) whereas compile logic is per *type* of directive.

Based on this, we could in fact cache the results of the compile function on a per-template basis, as they can be reused and linked to a scope as necessary. However this is an optimisation that is currently left out.



Now we can add the fully built element to the DOM and build our return object.

```language-javascript
body.append(modalElement);

var modal = {
  controller: modalController,
  scope: modalScope,
  element: modalElement,
  close: closeDeferred.promise
};
```

We return the four things the caller might need - the controller, scope, element and close promise. When the close promise is resolved, we also want to clean up:

```language-javascript
modal.close.then(function(result) {
  modalScope.$destroy();
  modalElement.remove();
});

deferred.resolve(modal);
```

So when `close` is resolved, whatever happens we'll destroy the scope and clean up the DOM. Now we can resolve our promise with the `modal` object we've built...

```language-javascript
  .catch(function(error) {
  deferred.reject(error);
});
return deferred.promise;
```

...and we can pass errors that occured during `getTemplate` to the caller and finally return the promise we've built.

That's it! With this design we handle errors correctly, can pass data to and from the modal, clean up after ourselves and make sure that units of asynchronous work are handled with the standard pattern of promises.

## Wrapping Up

I hope you've found the service and some of the details of the code useful, as always comments are welcome, fork the code and have a play - let me know if you think of improvements or have questions,

