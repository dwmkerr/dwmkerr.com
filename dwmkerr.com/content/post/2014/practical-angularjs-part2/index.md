---
author: Dave Kerr
date: "2014-05-07T14:55:25Z"
description: ""
draft: false
slug: practical-angularjs-part2
title: Practical AngularJS Part 2 – Components of an AngularJS Application
---


Welcome to Part 2 of Practical AngularJS. I’m going to introduce you to some of the core components of an angular app. These are:

* Controllers
* Filters
* Directives
* Services
* Views & Routes

We’re not going to be going into detail – just taking a look at what each of these components are and where they fit into the structure of an app. We’ll be going into detail for each of them in later articles.

## Controllers

Controllers are objects that manage the state of part of a page. The state is stored in the scope - the scope is an object that holds all of the state needed to render the page. We use a controller to add state and functionality to the scope,  it's that simple. 

A controller is normally going to be written for a logical chunk of the user interface of an application, such as a 'Create Something' form, 'Edit Something' popup and so on.

Controllers are dealt with in Practical [AngularJS Part 1 - Introducing AngularJS](http://www.dwmkerr.com/practical-angularjs-part1/) but let's have a quick refresh. Here's a controller for the UI for a list of cartoon characters.

<iframe src="http://jsfiddle.net/dwmkerr/8Ts9u/embedded/js,html,result" width="100%" height="300"></iframe>

Here's the points that you should take from the refresher:

* Controllers create state on the $scope
* Controllers expose functionality by adding functions to the $scope
* Controllers are written for a logical portion of the UI

### A Note on the Controller 'as' Syntax

As of Angular 1.2 it is possible to use a controller with the 'as' syntax, such as:

```language-markup
<div ng-controller="CharacterController as controller">
  <p ng-text="controller.something"></p>
</div>
```

The only problem is with approach is that it's not just a decision you have to make on the view, but also in the controller. Normal controllers add to the $scope, controllers used with the 'as' syntax add to themselves and the controller itself is added to the scope with the given name.

My personal preference is for the 'as' syntax - it makes directives less ambiguous when there are nested scopes. However, until the feature is set up so that the coder can use 'as' or not without having to change the controller code, I suggest that you pick one approach or the other and stick to it.

### Quick Tips for Controllers

Don't put everything on `$scope` - only put functions and data you want to expose to the view on `$scope` and keep all other data private to the controller.
Keep your controllers focused, if they get very large you probably need to break it into smaller controllers.

## Filters

Filters are simple units of logic that format data, let's see some:

<iframe src="http://jsfiddle.net/dwmkerr/XPqL8/embedded/html,result" width="100%" height="150"></iframe>

You can use filters in any expression - you pass a value through a filter by using the vertical pipe symbol. Filters can also take parameters, which come after the colon. AngularJS has a bunch of built in filters that are very useful, such as date, currency, orderBy and sortBy.

Let's create our own filter now. Assume that in our app we are showing statuses in lots of places - either Success, Warning or Error. But in our app our statuses come back as numbers, 0 is error, 1 is warning and 2 is success. Here's how we could write a filter to show the text:

<iframe src="http://jsfiddle.net/dwmkerr/DVb9B/embedded/html,js,result" width="100%" height="150"></iframe>

Filters are simple and they're really useful. They'll become an important part of your angular toolkit. They also lead us nicely onto Directives.

### Quick Tips for Filters

* Learn the out-of-the box filters, particularly date, currency and number.
* Filters can take any number of parameters.
* Although filters can take parameters and are highly adaptable, if you're doing more than what is essentially formatting then you should probably be using a directive.

## Directives

Directives are powerful re-usable units of logic and UI that can be dropped into your application. A directive is written in the HTML of your page, it can be an element, class or attribute. Angular then compiles these directives by adding HTML for them and linking in functionality.

Let's take a look at our earlier example, we have a number which represents a status. We'll make a 'status' directive that shows the status text, in an appropriate colour.

<iframe src="http://jsfiddle.net/dwmkerr/3T2W6/embedded/html,js,css,result" width="100%" height="300"></iframe>

Let's break down what we've got here.

```language-markup
<app-status status-value="0"></app-status>
```

First we have the directive in HTML - Angular knows that it can map hyphens to camel-case, so we can use 'appStatus' as the directive name.

```language-javascript
.directive('appStatus', function() {
 return {
```

Now we actually create the directive. A directive is normally just a function that returns an object with a specific format.

```language-javascript
restrict: 'E',
```

'Restrict' allows us to state that the directive can only be used in certain ways. E is for element, A for attribute and C for class.

```language-javascript
template: '<div ng-class="statusClass">{{statusValue | status}}</div>',
```

The template just contains that HTML to use for the directive. It can contain filters. You can also use 'templateHtml' and specify a path to an HTML file if that's more convenient.

```language-javascript
scope: {
 statusValue: '='
 },
```

Slightly more complex - 'scope' lets us determine what values we want on the scope and where they come from. Using the equals sign means perform a two-way databind to the property with the same name as is on the parent scope. You can use an ampersand for one-way binding. 

In this directive we're using the scope to allow us to pass the status as an attribute. We'll see a lot more of this in later tutorials, so don't worry if it seems a bit cryptic at the moment.

```language-javascript
link: function($scope) {
 switch($scope.statusValue) {
 case 0: $scope.statusClass = "red"; break;
 case 1: $scope.statusClass = "orange"; break;
 case 2: $scope.statusClass = "green"; break;
 }
}
```

Link is the function that is called when the HTML elements have been created - you can use this function to add data to the scope, work with the generated elements or attributes. Again, we'll be seeing more of this later.

The more you work with angular, the more you'll see how directives allow you to create re-usable user interface components. We're going to see them again and again in this series, but for now it's sufficient to know that they are there and what they can be used for.

### Quick Tips for Directives

* If you need to include content in your directive tags, look into transclude.
* If you need the actually DOM tree element generated, you can get it in the link function.
* Learn the syntax for 'scope' on a directive - it's important to use the right binding mechanisms for everything you bring into the scope.
* Learn, learn, learn, we've only glimpsed at directives, writing directives is a large part of angular development.

## Services

Services let you define logic that can be shared between different components. You can inject services into controllers, directives and other components.

Let's say that we want to monitor from the client how long it's taking to get to the server, by sending a request every second. If the round trip time gets too low, we want to show a warning. Now if we implemented this logic in a controller, it would be tied to some UI. But actually, the logic isn't associated with any UI at all - we want an isolated component that is responsible for getting this information, that can expose it to any other component on the client.

Here's how such a service would look in an angular application. Note: jsfiddle takes about 10 seconds to handle the first ajax ping request.

<iframe src="http://jsfiddle.net/dwmkerr/YZF4T/embedded/html,js,result" width="100%" height="300"></iframe>

Although the output is not very impressive, what's important to remember is that all of the state and logic relating to the ping mechanism can be kept with the service - and used from any component. Well designed services will greatly improve the structure of your code and its testability.

### Service Quick Tips

* Services are singletons.
* Services don't have to be classes as I've defined.
* Services shouldn't need to ever think about the DOM - if they do, you should probably be writing a directive.

## Views & Routes

AngularJS has a routing mechanism that allows you to create deep linking in your site. You can configure the $routeProvider service to specify what urls will go to which pages. Here's a brief example:

<iframe src="http://jsfiddle.net/dwmkerr/sQZ6J/embedded/html,js,result" width="100%" height="300"></iframe>

Typically the views are not defined as script elements in the main page, I'm just doing it like this to get past the fact that jsfiddle only has one HTML page. Every time the user clicks on a url, angular checks to see if it can match it in the route provider. If it can, it creates the appropriate view and a new instance of the controller. If it can't, it uses the 'otherwise' route.

Let's take a look at the route provider in a bit more detail:

```language-javascript
// Configure the route provider.
app.config(function($routeProvider) {
 $routeProvider.
 when('/home', {
   template: '<h2>Home</h2>',
   controller: 'HomeController'
 }).
 when('/contact', {
   template: '<h2>Contact</h2>',
   controller: 'ContactController'
 }).
 otherwise({
   redirectTo: '/home'
 });
});
```

You'll typically not use 'template' but 'templateUrl' instead. 'templateUrl' lets you specify an HTML file for your template. Routes can be more complicated - they can contain parameters which are accessible from the controller. This means your routes could include entity ids and other data, and the controller can load the data it needs to based on the route. This makes handling deep linking a bit more straightforward.

### View and Route Quick Tips

The routing capabilities of angular are powerful - you can build complex routes with many parameters.
You can get data about the current route with the $route service.


## That's It

For now. We've seen the essential components of angular apps - much more in one go would be overload. Next we'll be taking a look at the structure of an angular app. We'll be seeing more of all these components in the articles to come.

