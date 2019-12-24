+++
author = "Dave Kerr"
categories = ["AngularJS", "CodeProject", "JavaScript", "ECMAScript 6"]
date = 2014-05-07T12:06:55Z
description = ""
draft = false
slug = "promises-in-angularjs-the-definitive-guide"
tags = ["AngularJS", "CodeProject", "JavaScript", "ECMAScript 6"]
title = "AngularJS Promises - The Definitive Guide"

+++


Promises are a core feature of AngularJS - whether you understand them or not, if you use AngularJS you've almost certainly been using them for a while.

In this post I'm going to explain what promises are, how they work, where they're used and finally how to use them effectively.

Once we've got the core understanding of promises, we'll look at some more advanced functionality - chaining and resolving promises when routing.

#### Contents
1. [What are Promises?](#whatarepromises)
2. [How do Promises Work?](#howdopromiseswork)
3. [A Real World Example](#arealworldexample)
4. [Promises - Success, Error, Then](#promisessuccesserrorthen)
5. [Advanced Promises - Chaining](#advancedpromiseschaining)
6. [Advanced Promises - Routing](#advancedpromisesrouting)
7. [Advanced Promises - Tips & Tricks](#advancedpromisestipstricks)
8. [The Future of Promises](#thefutureofpromises)
9. [Wrapping Up](#wrappingup)

## What are Promises?

I'm going to try and be as succinct as possible - if anyone has a shorter, clearer description, let me know!

> A promise represents the eventual result of an operation. You can use a promise to specify what to do when an operation eventually succeeds or fails.

So let's see this in action. Look at the code below:

```language-javascript
$http.get("/api/my/name");
```

This code uses the `$http` service to perform an HTTP GET on the url '/api/my/name'. Let's say that this is an api we've implemented on our server that returns the name of the logged in user.

Now a common mistake for JavaScript newcomers might be to assume that the function returns the name:

```language-javascript
// The WRONG way!
var name = $http.get("/api/my/name");
```

It doesn't - and in fact it can't. An HTTP request has to be executed, it'll take a while before it returns - it might not return at all if there are errors. Remember, when we make requests in JavaScript we're using **ajax** which is ***asynchronous** javascript and xml*. The key word here is asynchronous - we return control to the browser, let it make a request and give it a function to call when the request completes.

So let's see how you actually make the request.

```language-javascript
var promise = $http.get("/api/my/name");
promise.success(function(name) {
   console.log("Your name is: " + name);
});
promise.error(function(response, status) {
   console.log("The request failed with response " + response + " and status code " + status);
});
```

Now we use the promise object to specify what to do when the request succeeds, or when it fails. Remember, the functions we pass to `success` or `error` will be called later - when this block is finished executing we don't have the name, we've just specified what to do when we *do* eventually get it - or what to do if we fail to get it.

As a convenience, the `success` and `error` functions actually just return the promise, so we can simplify the code:

```language-javascript
$http.get("/api/my/name")
  .success(function(name) {
    console.log("Your name is: " + name);
  })
  .error(function(response, status) {
    console.log("The request failed with response " + response + " and status code " + status);
  });
```

In fact, `success` and `error` are special functions added to a promise by `$http` - normally with promises we just use `then`, which takes the success function as the first parameter and the error function as the second:

```language-javascript
$http.get("/api/my/name")
  .then(
    /* success */
    function(response) {
      console.log("Your name is: " + response.data);
    },
    /* failure */
    function(error) {
      console.log("The request failed: " + error);
  });
```

We'll see more about the difference between `success`, `error` and `then` later.

That's all there is to it - a promise lets us specify what to do as the result of an operation.

## How do Promises Work?

Promises are not actually complicated, they're objects that contain a reference to functions to call when something fails or succeeds.

Under the hood, AngularJS actually wires up a promise for an HTTP request in a way a bit like this:

```language-javascript
var request = new XMLHttpRequest();
request.addEventListener("load", function() {
  // complete the promise
}, false);
request.addEventListener("error", function() {
  // fail the promise
}, false);
request.open("GET", "/api/my/name", true);
request.send();
```

this is pseudo-code, but the idea is that its the browser that calls us back, via the event listeners, then AngularJS can just call the appropriate method on the promise.

Now in AngularJS, the promises are created with the `$q` service (we'll see exactly how to do this shortly), but why `$q`?

The reason the service is named `$q` is that AngularJS' promise implementation is based on Kris Kowal's promise mechanism, which is called 'Q'. You can see the library at [github.com/kristkowal/q](https://github.com/kriskowal/q).

This was a deliberate decision, as the Q library is widely used and well understood by the community. We're going to see a little bit later what the future of promises is in AngularJS and actually in ECMAScript 6.

### A Real World Example

In this example we'll create a service that gets the user's name, just like in our examples. However, to make it interesting, we'll set our service up so that the first time we get the name from the server, and then afterwards we'll return a cached copy.

This means we'll have to build our code to deal with the asynchronous case (the first one) and the more trivial synchronous case (getting the name from the cache).

Let's look at a pure asynchronous implementation.

```language-javascript
app.factory('NameService', function($http, $q) {
  
  //  Create a class that represents our name service.
  function NameService() {
    
    var self = this;
                      
    //  getName returns a promise which when 
    //  fulfilled returns the name.
    self.getName = function() {
      return $http.get('/api/my/name');
    };
  }
    
  return new NameService();
});
```

Here's how it looks in a fiddle - just click 'Result' to see it working. You can click on 'Update' name to get the name, but each time it sends a request. This is what we'll change next.

<iframe width="100%" height="300" src="http://jsfiddle.net/dwmkerr/4GjtR/embedded/js,html,result" allowfullscreen="allowfullscreen" frameborder="0"></iframe>

Now let's update our service so that we hit the server only if we haven't already cached the name. I'll build the service blow by blow, then we can see a fiddle of it working.

```language-javascript
app.factory('NameService', function($http, $q) {

  //  Create a class that represents our name service.
  function NameService() {
    
    var self = this;
        
    //  Initially the name is unknown....
    self.name = null;
```

so first we create a service which is in the form of a class. It has a name field which is initially null.

```language-javascript
   self.getName = function() {
     //  Create a deferred operation.
     var deferred = $q.defer();
```

Now in the `getName` function we start by creating a `deferred` object, using the `$q` service. This object contains the promise we'll return, and has some helper functions to let us build the promise.

We create a deferred object because whether we use ajax or not, we want the consumer to use the promise - even if we *can* return straightaway in some circumstances (when we have the name) we can't in all - so the caller must always expect a promise.

```language-javascript
    if(self.name !== null) {
      deferred.resolve(self.name + " (from Cache!)");
    }
```

If we already have the name, we can just `resolve` the deferred object immediately - this is the easy case. I've added 'from cache' to the name so we can see when it comes from the cache compared to the server.

> **Tip:** You can resolve a promise even before you return it. It still works fine for the consumer.

Finally, we can handle the case if we don't already have the name:

```language-javascript
    else {
      //  Get the name from the server.
      $http.get('/api/my/name/')
         .success(function(name) {
           self.name = name;
           deferred.resolve(name + " (from Server!)");
         })
         .error(function(response) {
           deferred.reject(response);
         });
     }
```

So if we get success from the server, we can `resolve` the promise. Otherwise, we `reject` it, which means failure. 

> Call `resolve` on a deferred object to complete it successfully, call `reject` to fail it with an error.

Finally, we just return the promise we've built with `deferred`:

```language-javascript
   return deferred.promise;
}
```

And that's it! You can see it in action below, press 'Update Name' a few times and you'll see it uses the cache.

<iframe width="100%" height="300" src="http://jsfiddle.net/dwmkerr/LeZU4/embedded/result,html,js" allowfullscreen="allowfullscreen" frameborder="0"></iframe>

How do we use this? We'll it's simple, here's a controller that uses the service we've built:

```language-javascript 
app.controller('MainController', function ($scope, NameService) {

  //  We have a name on the code, but it's initially empty...
  $scope.name = "";
  
  //  We have a function on the scope that can update the name.
  $scope.updateName = function() {
    NameService.getName()
      .then(
      /* success function */
      function(name) {
        $scope.name = name;
      },
      /* error function */
      function(result) {
        console.log("Failed to get the name, result is " + result); 
      });
  };
});
```

Now there's something different here. Before, we might have used the `error` or `success` function of the promise. But here we use `then`. Why is that?

> `success` and `error` are functions on a promise that AngularJS adds for us when using `$http` or `$resource`. They're not standard, you won't find them on other promises.

So we've seen how promises work, what they are and so on, now  we'll look into this success/error/then stuff.

## Promises - Success, Error, Then

Now we know that `$http` returns a promise, and we know that we can call `success` or `error` on that promise. It would be sensible to think that these functions are a standard part of promise - but they're not!

When you are using a promise, the function you should call is `then`. `then` takes two parameters - a callback function for success and a callback function for failure. Taking a look at our original `$http` example, we can rewrite it to use this function. 
So this code:

```language-javascript
$http.get("/api/my/name")
  .success(function(name) {
    console.log("Your name is: " + name);
  })
  .error(function(response, status) {
    console.log("The request failed with response " + response + " and status code " + status);
  };
```

becomes:

```language-javascript 
$http.get("/api/my/name")
  .then(function(response) {
    console.log("Your name is: " + response.data);
  }, function(result) {
    console.log("The request failed: " + result);
  };
```

We **can** use `success` or `error` when using `$http` - it's convenient. For one thing, the `error` function gives us a response and status (and more) and the `success` function gives us the response data (rather than the full response object).

But remember that it's not a standard part of a promise. You can can add your own versions of these functions to promises you build yourself if you want:

```language-javascript
promise.success = function(fn) {
    promise.then(function(response) {
      fn(response.data, response.status, response.headers, config);
    });
    return promise;
  };

promise.error = function(fn) {
    promise.then(null, function(response) {
      fn(response.data, response.status, response.headers, config);
    });
    return promise;
  };
```

this is exactly how angular does it.

So what's the advice?

> Use `success` or `error` with `$http` promises if you want to - just remember they're not standard, and the parameters are different to those for `that` callbacks.

So if you change your code so that your promise is not returned from `$http`, as we did in the earlier example when we load data from a cache, your code will break if you expect `success` or `error` to be there.

A safe approach is to use `then` wherever possible.

## Advanced Promises - Chaining

If you've had your fill of promises for now, you can skip to [The Future of Promises](#thefutureofpromises) or [Wrapping Up](#wrappingup).

One useful aspect of promises is that the `then` function returns the promise itself. This means that you can actually *chain* promises, to create conscise blocks of logic that are executed at the appropriate times, without lots of nesting.

Let's consider an example where we need to fetch the user's name from the backend, but we have to use separate requests to get their profile information and then their application permissions.

Here's an example:

```language-javascript
var details {
   username: null,
   profile: null,
   permissions: null
};

$http.get('/api/user/name')
  .then(function(response) {
     // Store the username, get the profile.
     details.username = response.data;
     return $http.get('/api/profile/' + details.username);
  })
  .then(function(response) {
  	//	Store the profile, now get the permissions.
    details.profile = response.data;
    return $http.get('/api/security/' + details.username);
  })
  .then(function(response) {
  	//	Store the permissions
    details.permissions = response.data;
    console.log("The full user details are: " + JSON.stringify(details);
  });
```

Now we have a series of asynchronous calls that we can coordinate without having lots of nested callbacks.

We can also greatly simplify error handling - let's see the example again, with an exception thrown in:

```language-javascript
$http.get('/api/user/name')
  .then(function(response) {
     // Store the username, get the profile.
     details.username = response.data;
     return $http.get('/api/profile/' + details.username);
  })
  .then(function(response) {
  	//	Store the profile, now get the permissions.
    details.profile = response.data;
    throw "Oh no! Something failed!";
  })
  .then(function(response) {
  	//	Store the permissions
    details.permissions = response.data;
    console.log("The full user details are: " + JSON.stringify(details);
  })
  .catch(function(error) {
    console.log("An error occured: " + error);
  });
```

We can use `catch(callback)` - which is actually just shorthand for `then(null, callback)`. There's even a `finally` - which is executed whether or not the operations fail or succeed.

> Use `catch` and for error handling with promises - and use `finally` for logic that's executed after success OR failure.

The composition of promises can simplify complicated code - particularly when you add in error handling!

One final point to make which is not quite related to chaining but does relate to multiple promises is `$q.all`. `all` can be used to build a single promise from a set of promises.

You can pass an array of promises to `all` and you get back a single promise - which is resolved when all of the promises it contains resolve. This can be useful if you are building complex methods that may have to perform multiple asynchronous tasks - such as multiple ajax calls.

## Advanced Promises - Routing

There's a particular area of AngularJS that uses promises to great effect, and that's the router. 

Let's imagine we have a router like the following:

```language-javascript
$routeProvider
   .when('/home', {
       templateUrl: 'home.html',
       controller: 'MainController'
   })
   .when('/profile', {
       templateUrl: 'profile.html',
       controller: 'ProfileController'
   })
```
Here we have two routes. The home route takes us to the home page, with the `MainController`, and the profile route takes us to the user's profile page. 

Our ProfileController uses our funky name service:

```language-javascript
app.controller('ProfileController', function($scope, NameService) {
	$scope.name = null;
    
    NameService.getName().then(function(name) {
    	$scope.name = name;
    });
});
```

The problem is, **until the name service gets the name from the backend, the name is null**. This means if our view binds to the name, it'll flicker - first it's empty then its set.

What we'd like to do is actully say to the router - "I'm going to go to this view, but only when you can tell me my name".

We can do this with the *resolves* in the router, here's how it works:

```language-javascript
// Create a function that uses the NameService 
// to return the getName promise.
var getName = function(NameService) {
        return NameService.getName();
    };
    
$routeProvider
   .when('/home', {
       templateUrl: '/home.html',
       controller: 'MainController'
   })
   .when('/profile', {
       templateUrl: '/profile.html',
       controller: 'ProfileController',
       /* only navigate when we've resolved these promises */
       resolve: {
           name: getName
       }
   })
```

so now we have a *resolve* on the route - when we go to the profile page the router will wait until the promise returned by `getName` resolves, then it will pass the result into the controller, as the parameter called `name`. Now our controller looks like this:

```language-javascript
app.controller('ProfileController', function($scope, name) {

	$scope.name = name;

});
```

Much better! And also **much** more testable.

One thing you may wonder - why do I use `getName` as the resolve function instead of just using `NameService.getName` directly? 

That's because the route is set up in a `config` function - and that function cannot have services injected. However, a resolve function **can**, so we just use a function and let AngularJS inject the `NameService` for us.

Now for an important statement:

> If the first thing your controller does is fetch data from the server, it's probably wrong.

Why? Because if your controller needs data, inject it - let the router ensure the data is ready. Then you don't have controllers in an invalid state as they're loading - and your controllers become easier to test.

Be aware of `resolve` for routes - it's a great way to handle loading of required data, authentication and other things that you might be putting into the wrong place.

You can see the example above in action here:

<iframe width="100%" height="300" src="http://jsfiddle.net/dwmkerr/m29pe/embedded/result,js,html" allowfullscreen="allowfullscreen" frameborder="0"></iframe>

What's cool is we can also see our caching logic by going to and from the Home and Profile pages. The promises are keeping our code clean and testable.

As a final note on promises when routing, you can specify multiple resolves if you need to:

```language-javascript
$routeProvider
   .when('/profile', {
       templateUrl: '/profile.html',
       controller: 'ProfileController',
       /* only navigate when we've resolved these promises */
       resolve: {
           name: getName,
           profile: getProfile,
           anythingElse: getAnythingElse
       }
   })
```

in this case each resolve is injected into the controller.

## Advanced Promises - Tips & Tricks

This section just contains some tips and tricks you might find useful when working with promises.

1. Promises in directives are not resolved automatically since AngularJS 1.2. Previously, if you passed a promise to a directive with an '=' binding, AngularJS would resolve the promise for you, this is no longer the case.

## The Future of Promises

So promises are a core part of AngularJS and to use the framework effectively, you must understand how to use them and how they work. But what is the future of promises?

It's almost certain that promises are going to become a **native** feature of JavaScript, they are part of the proposed ECMAScript 6 specification. 

The functionality of the `q` library and AngularJS' implementation of promises are very similar indeed to the proposed specification, but be aware that when promises become standard, AngularJS is most likely to  adapt their own promises to work like native promises.

You can read more at [html5rocks.com/en/tutorials/es6/promises/](http://www.html5rocks.com/en/tutorials/es6/promises/).

Just be aware that you'll see promises more and more, in other frameworks and in vanilla JavaScript.

## Wrapping Up

I hope this post has been useful to understanding promises. Any feedback is always good, so let me know if anything is unclear or could be improved. To finish this article, here are some useful links:

* **The Q library** [github.com/kriskowal/q](https://github.com/kriskowal/q)
* **The AngularJS `$q` Service** [docs.angularjs.org/api/ng/service/$q](https://docs.angularjs.org/api/ng/service/$q)
* **Promises in ECMAScript 6** [html5rocks.com/en/tutorials/es6/promises/](http://www.html5rocks.com/en/tutorials/es6/promises/)
* **XmlHttpRequest, which we used in an example** [developer.mozilla.org/en/docs/Web/API/XMLHttpRequest](https://developer.mozilla.org/en/docs/Web/API/XMLHttpRequest)

And also some interesting discussions:

[Why I am switching to promises](http://spion.github.io/posts/why-i-am-switching-to-promises.html) - Written by Gorgi Kosev, great article describing why a switch from callbacks to promises can be a very good thing in NodeJS applications.

[Callbacks, sychronous and asynchronous](http://blog.ometer.com/2011/07/24/callbacks-synchronous-and-asynchronous/) - From Havoc, this post contains many useful points for API writers who are using callbacks or promises. One key takeaway is to **never** do what a sample in this article does which is resolve a promise either synchronously or asynchronously, as it leads to code which can be difficult to reason about. I'll be mentioning this more in a later update which will explain the problem and solution.

[Designing for Asynchrony](http://blog.izs.me/post/59142742143/designing-apis-for-asynchrony) - Written by  Isaac Z. Schlueter, this post is another great one for API designers that takes a look into asynchrony.

