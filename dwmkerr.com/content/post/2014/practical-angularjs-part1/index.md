---
author: Dave Kerr
type: posts
date: "2014-05-03T14:48:23Z"
description: ""
draft: false
slug: practical-angularjs-part1
title: Practical AngularJS Part 1 – Introducing AngularJS
---


In this series of articles I'm going to be working with AngularJS, a fantastic framework from Google that helps you rapidly build web applications. We'll see how AngularJS can be used to speed up your development and help you write cleaner, more testable code.

![](images/AngularJSLargeLogo.png)

## Introducing AngularJS

First of all an introduction is in order.

AngularJS is a lightweight JavaScript framework, primarily for building single page web applications. The idea behind applications like these is that rather than the 'traditional' way of writing web applications which would involve using server side technologies to render the user interface and send it to the user, we handle all of the presentation logic on the client side.

This means if we're showing a list of blog posts, we don't have the server render the posts as html and send them to the client, but instead send a simple page, very quickly, and then use JavaScript in the client to load the posts as JSON from the server, and add them to the page itself - presenting them in the way we choose.

The server doesn't send back posts as html with formatting, it sends back posts as data (in the form of JSON) and the client code decides how to render them. Selecting a post wouldn't ask the server for a new page with the post contents to be rendered, just ask the server for the post data, and then render it itself.

We don't strictly have to be a single page application entirely to get benefit from this, but we're looking to:

Make our back end servers deal with data not html, so that they can be consumed from a variety of sources.
Keep HTML and DOM logic in the client.
Let the client talk to the server quickly and frequently to keep itself up to date.
Cut down on full round trips to reload entire pages.
Keep presentation logic in client side code, not server side code.
We'll see in this first article how AngularJS can help write applications like this.

As we learn about AngularJS, key statements are marked like this:

>  ![](images/AngularTip.png) All tips like this are in the Angular Cheat Sheet.

These statements are in the Angular Cheat Sheet. The cheat sheet is a quick guide that you can open or print as a fast reference for core info and terminology.

### The Problem

Before I start to advocate the use of a library or framework, I need to demonstrate a need for it. Unless we're just coding for fun or specifically to learn, the reason we choose to use a library or framework is that we have a problem, or problems to solve.

So as a way of introducing angular, I'm going to start with a trivial task, see how it quickly gets harder, identify problems and demonstrate how angular is a good choice as something that can mitigate these problems.

### Our Task

Here's our initially trivial task.

We need to come up with a super-simple proof of concept page that allows a user to write a list of URLs, and have a system check how long it takes to fetch them. That's all. We'll call it Speedmonitor. Imagine that we've been told we're just a proof of concept phase, our graphic designers have some nice visuals and we don't need it to be fully functional, but we to be able to have a play with it. We don't need to have the request send, for the moment a faked time is fine.

The first thing we do is understand the requirements. At this stage, a diagram on a piece of paper suffices. We can make a quick mock up.

### Speedmeter

![](images/Speedmeter.jpg)

[Note: I apologise deeply for my awful handwriting. And drawing. To be fair, these are far from the worst requirements I've had.]

We've now got enough to get started.

### The Task - First Cut

Here's what we come up with first.

<iframe src="http://jsfiddle.net/dwmkerr/57AQV/embedded/html,js,result" width="100%" height="300"></iframe>

Looking at the code, we can see we've got a whole series of issues.

* There's a lot of JavaScript that's only there to maintain the state of the DOM.
* We've got an in-memory representation of the state, but also a DOM representing the state too, and we must keep the two in sync.
* The table is always shown, even if we have no items in it.
* The formatting of the milliseconds is awful.

This is just the beginning. A large part of our code depends heavily on the HTML - the ids must be correct, we need to know about the form and so on.

Imagine that we're now asked to make the URLs in the table links - we now have to change our JavaScript to add an 'a' tag. If the designers ask us to apply a CSS class to the load speed cells, again, we have to change our JavaScript just for a simple HTML change. And this will get worse and worse.

As we add more features, this code will grow and grow and will get harder to maintain. We can do all of this with raw JavaScript, and we can improve the access to the DOM with a library like jQuery, but the fundamental problem remains the same - our logic is heavily tied in to our HTML. Unit testing this is essentially impossible, we need a browser and webserver to run the JS and test the resulting HTML, and this is not easy (it's also not a unit test - it's an integration test, integration tests are great but we want to test smaller units first).

### The Task - Second Cut

After the first cut, we decide to limit ourselves to just improving a few issues:

* After adding an url, we need to clear the selection.
* The urls must be links.
* The round trip times must show in red if they're greater than 100ms.
* There must be no decimal points in the milliseconds.
* The submit button must be disabled if there is no value in the url textbox.

The HTML is almost the same, so I'm highlighting the JavaScript this time. It's starting to look pretty clunky by this point:

<iframe src="http://jsfiddle.net/dwmkerr/v2bSe/embedded/js,html,result" width="100%" height="300"></iframe>

We're building raw html, we're referencing css class names, we're having to use more events from the dom (such as the key up event), it's simple stuff but it's going to get increasingly difficult to maintain, let alone test.

At this stage, we've been told we need to do the following:

* Show a visual indicator to the user that the check is being performed on the page load time for newly added urls.
* Hide the table when there are no urls and show a hint saying to add one to get started.

I'm not going to implement this, we've already seen that vanilla js is not giving us much to work with and we're getting increasingly complex as we add features.

So what are the problems?

* We have too much code managing the DOM.
* We can't unit test the client side logic.
* Changes to the HTML of the page require changes to our JavaScript.

**The Solution - We do the logic, AngularJS does the grunt work**

AngularJS is not the solution, it's a tool. It's a framework that'll let us write the solution and do the repetitive and tedious DOM stuff for us. We'll find out through the series that it can do a lot more as well.

AngularJS is ideal for helping with problems like these. What AngularJS allows us to do is build a controller to control the state of the system (which is the model), and bind the view (the raw HTML) to parts of that model. As the model changes, the view will automatically be updated to represent that state. This will remove our code to manage the DOM.

We can then extend the controller to handle the more complicated cases we need, and not have to worry about synchronisation between the view and the model - it's handled for us. As our controller is a simple JavaScript object, we'll also be able to write unit tests against it. This will allow us to write effective unit tests.

Because AngularJS is declarative in the HTML (we'll see what this means shortly), we can modify the HTML to our hearts content, without worrying about the JavaScript. This means changes to the HTML are done in HTML, not JavaScript.

If we can show in our small example that this is true, we've solved the problem.

### The Task with Angular - First Cut

I'm going to show right away the first cut of our app, using AngularJS. We'll go through the new code line by line afterwards to learn how it works, but what's interesting about this is that we almost don't have to. Look at the html - anything that starts with an 'ng' is Angular. Look at the JavaScript - it's almost entirely vanilla - the only bit of magic we have is the '$scope' parameter, which is the glue between the view and the controller (we'll learn about this shortly).

<iframe src="http://jsfiddle.net/dwmkerr/TXEf6/embedded/html,js,result" width="100%" height="300"></iframe>

Now it's time to go through the code and see what's going on. We'll look at the JavaScript first and then the HTML.

### The Controller

Let's take a look at the controller again.

<iframe src="http://jsfiddle.net/dwmkerr/TXEf6/embedded/js" width="100%" height="300"></iframe>

A controller is where we put the logic for our application. The first thing we do on our controller is define two variables. One is a string, it's the current url that the user will edit. The second is an array of urls that we maintain.

Each of these variables is defined on the $scope object. The scope is our context, it's what we can add model data to and it's what the view can bind to.

We also create a function on the scope that adds a new url (based on the current url) and clears the current url afterwards. The last function on the scope can be used to remove a url. More important stuff here - scopes can contain variables and functions.

Our controller logic is trivial, and already much easier to follow than the previous one.

We've seen the controller in detail, let's look at the view.

<iframe src="http://jsfiddle.net/dwmkerr/TXEf6/embedded/html" width="100%" height="300"></iframe>

The first thing we have a link to the angular library. This should always go at the top of the page, normally in the head (above we have a jsfiddle which makes the head for us so it's right at the top of the body).

```html
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0/angular.min.js"></script>
```

> ![](images/AngularTip.png) Include Angular in the `<head>`, not at the end of the file.

What do we notice first? ng-app. The ng-app directive tells angular that everything that's in this element should be handled by angular. Angular will only pay attention to directives inside an element that's an app.

What's a directive? A directive is a marker in the HTML that tells Angular it needs to do something. It may indicate that something needs to be bound, that an event handler needs to be wired up, or it can even be completely custom - you can write simple directives in the forms of elements or attributes that expand into rich and complex parts of a page, or handle advanced functionality.

The most common place to put an ng-app directive for an application is often directly in the html element - telling angular our whole page is controlled by angular. Like this:

```html
<html ng-app>
```

> ![](images/AngularTip.png) Angular will work for everything within an element with the `ng-app` directive.

Following this we have an ng-controller directive. This tells angular that it needs to create an instance of the controller specified (which is our main controller we've already defined) use the controller to control the scope of all child elements.

```html
<div ng-controller="SpeedmonitorController">
````

> ![](images/AngularTip.png) The `ng-controller` directive specifies the controller to use.

The first thing we do is bind the submit event of the form to the `addUrl()` function. Then we bind the input to the `currentUrl` field. This has shown us two new directives:

```html
<form ng-submit="addUrl()">
  <label for="url">Url</label>
  <input id="url" type="text" ng-model="currentUrl" />
  <input id="submit" type="submit" />
</form>
```

> ![](images/AngularTip.png) The ng-submit directive evaluates the provided expression when a form is submitted.

> ![](images/AngularTip.png) The ng-model is used to bind an input to a model property.

The final part of the html is perhaps the most interesting and where we're really starting to see the power of AngularJS.

```html
<tr ng-repeat="url in urls">
```

Here we use an ng-repeat directive to iterate through a collection. Angular will loop through every item in the urls array and create whatever is contained in the element with the ng-repeat tag for each item. Now we can reference the properties of the item using the name we gave it ('url' in our case). We can also see that the handlebars syntax {{something.else}} can be used to simply write out a value into the html.

```html
<td>{{url.loadSpeed}} ms</td>
```

> ![](images/AngularTip.png) Use `ng-repeat="item in set"` to create html for multiple items in a collection. Use $index, $first, $last (and more) special variables for extra control.

> ![](images/AngularTip.png) Use {{handlebars}} to write out the value of an expression.

The final part of the html is where we have the ng-click directive. When the element is clicked on, angular evaluates the expression - and the expression is the removeUrl function called with the $index special property. $index is provided by angular inside the ng-repeat template and evaluates to the index in the array.

```html
<td><a ng-click="removeUrl($index)" href="javascript:void(0)">Delete</a></td>
```

> ![](images/AngularTip.png) ng-click evaluates the provided expression when the element is clicked on.

### The Task with Angular: Second Cut

In our first attempt, without angular, we then decided to fix the following issues:

* After adding an url, we need to clear the selection.
The urls must be links.
* The round trip times must show in red if they're greater than 100ms.
* There must be no decimal points in the milliseconds.
* The submit button must be disabled if there is no value in the url textbox.

With the arrangement we've got now, the changes become trivial. Let's do them one by one.

#### Item 1: After adding an url, we need to clear the selection.

We can make the following change in the controller, highlighted in bold.

```js
$scope.addUrl = function() {
  $scope.urls.push({url: $scope.currentUrl, loadSpeed: Math.random() * 75 + 50});
  $scope.currentUrl = ""; // Now clear the current URL.
}
```

Notice that we didn't have to get the input element in the JavaScript? We update the model state, via the scope, and the view will update automatically.

#### Item 2: The urls must be links.

Again, a trivial change. It's a change in the view, so we do it in the view. The updated code is in bold below:

```htmlo
<td><a href="{{url.url}}">{{url.url}}</a></td>
```

Easy - we write the url value in the href of an 'a' tag. And we do it in the view - not in the JS logic.

#### Item 3: The round trip times must show in red if they've over 100ms.

We need to apply the 'red' css class if the round trip time is greater than 100ms - where do we do this? In the view!

```html
<td><span ng-class="{red: url.loadSpeed > 100}">{{url.loadSpeed}}</span> ms</td>
```

We use the ng-class directive here. It allows us to set CSS classes on an element conditionally, based on the result of an expression. Again - no CSS or HTML in the JavaScript to handle this, we do it in the view, where it belongs.

> ![](images/AngularTip.png) Use ng-class to apply CSS classes to elements based on expressions.

#### Item 4: There must be no decimal points in the milliseconds.

Oh so easy with Angular. Again - it's presentation logic, so it stays in the view.

```html
{{url.loadSpeed | number:0}} ms
```

The vertical pipe character shows we're using a filter - this is something that can be used to format data. AngularJS comes with a bunch of filters, we use `number:0` to format as a number with no decimal places.

> ![](images/AngularTip.png) Use the | pipe to apply a filter.

#### Item 5: The submit button must be disabled if there is no value in the url textbox.

By now we're starting to see that this logic is very easy to apply in the view.

```html
<input id="submit" type="submit" ng-disabled="currentUrl.length == 0" />
```

We use the ng-disabled directive to apply the disabled attribute to an input based on the value of an expression. AngularJS expressions are powerful ways to quickly apply logic like this.

> ![](images/AngularTip.png) Use ng-disabled to disable an input based on an expression.

Here's the final fiddle for the task, with our new functionality.

<iframe src="http://jsfiddle.net/dwmkerr/5X5CF/embedded" width="100%" height="300"></iframe>

We've added only one line to our JavaScript - we've kept the presentation logic in the view and we've done no extra DOM manipulation. I think we've now shown some progress with our problems:

* SOLVED: We have too much code managing the DOM.
We can't unit test the client side logic.
* SOLVED: Changes to the HTML of the page require changes to our JavaScript.

If we can run some kind of tests now, we've shown that AngularJS can help us greatly in this case.

Unit Testing AngularJS Controllers

Unit testing is a complicated topic, generally because we require extra libraries to write unit tests, run them and so on. We're going to keep things as simple as possible here. Unit testing is a bigger topic that'll be covered in detail in a later article, but for now let's build some trivial tests for our controller.

The recommended approach for writing unit tests for AngularJS applications is to use Jasmine. Jasmine is a topic all on it's own and too much to go into in this introduction article, but I'll show you a quick fiddle that runs unit tests for our controller.

<iframe src="http://jsfiddle.net/dwmkerr/ThfE4/embedded/result,js" width="100%" height="300"></iframe>

We start with the controller - which in a real world test suite would be included or injected, then run a basic set of tests against it. You can check the JavaScript but what's key here is that we can test without a DOM or even a window, these are genuine unit tests.

At this stage, let's look over our problems.

* SOLVED: We have too much code managing the DOM.
* SOLVED: We can't unit test the client side logic.
* SOLVED: Changes to the HTML of the page require changes to our JavaScript.

OK - the sceptics among you may need more persuasion on these points, but if you're interested we'll be seeing them again in later articles.

### Conclusions

We've shown in this article that there are certainly cases where AngularJS can help us write better client-side code. As we go through the series we'll see what else AngularJS can do and how we can even extend it to deal with our own application specific requirements.

I hope you've found this article useful. If you've got any comments please let me know, it's early enough in the series for me to adapt it based on what people want!

