---
author: Dave Kerr
categories:
- React
- AngularJS
- CodeProject
- Javascript
- Redux
- Angular 2
- JSX
date: "2016-04-25T09:45:00Z"
description: ""
draft: false
image: /images/2016/04/Journey-1.jpg
slug: moving-from-react-redux-to-angular-2
tags:
- React
- AngularJS
- CodeProject
- Javascript
- Redux
- Angular 2
- JSX
title: Moving from React + Redux to Angular 2
---


I've just finished working on a very large project written in React and Redux. The whole team were new to both and we loved them.

I'm going to share my experiences of experimenting in Angular 2 with you, from the point of view of someone who needs a pretty compelling reason to move away from my JSX and reducers. 

# The Journey So Far

Let me highlight a few key moments in my UI development experiences, to give a bit of context to my ramblings.

![The Journey So Far](/images/2016/04/Journey.jpg)

Reading about redux was a lightbulb moment for me - rather than a complex framework it's a simply library to help apply a few common sense functional programming principles - state is immutable, functions apply predictable transformations to data to produce new data.

Learning React took a little bit of getting used to, but not too much, it was quite a bit more simple than Angular anyway.

Long story short, simple React components and rigorous state management has so far resulted in the most manageable and well written very large scale UIs I've worked on so far - can Angular 2 compete with this?

# First Step with Angular 2 - Folder Structure, Typescript, Sublime Text

I checked out [the pretty neat 'Getting Started' guide from Angular](https://angular.io/docs/ts/latest/quickstart.html) which promised to get me started in five minutes.

It didn't take five minutes, there's a few gotchas, so I'm going to give a condensed guide here.

## Step 1: The Folder Structure

The first few steps of the angular guide creates the following folder structure:

```
|-- angular2-starter
    |-- tsconfig.json
    |-- typings.json
    |-- package.json
```

This is the standard `package.json` with some scripts ready to go. We also have `tsconfig.json` to configure the typescript compiler and `typings.json` to provide info to the  compiler on where to get type information.

You can check the code at this stage here:

https://github.com/dwmkerr/angular2-starter/tree/step1

![Step 1 GitHub Screenshot](/images/2016/04/Step1.png)

## Node & NPM Issues

At this stage the quickstart says you can run `npm install` and all will be well:

![npm install screenshot](/images/2016/04/npm-install.png)

```
npm ERR! cb() never called!
```

Not so good! For the record I'm using NPM 3.7.3 installed via homebrew. This looks like a bug in Beta 15 (see [Issue #8053](https://github.com/angular/angular/issues/8053)).

I fixed this by using *n* to upgrade my node version:


```
$ node -v 
v5.9.0

$ npm install -g n     # install 'n' node version manager

$ sudo n latest
installed : v5.11.0

$ node -v
v5.11.0
```

Now it `npm install` runs OK.

## Step 2: Adding Components and Configuring Sublime

The next steps of the walkthrough take us through adding an app component, a `main.ts` file to bootstrap the application and an index file. You can check the updates here:

https://github.com/dwmkerr/angular2-starter/tree/step2

Essentially we now have:

```
|-- angular2-starter
    |-- tsconfig.json
    |-- typings.json
    |-- package.json
    |-- index.html
    |-- styles.css
    |-- app
        |-- main.ts
        |-- app.component.ts
```

At this stage, running `npm start` gives us a browerserified app to play with:

![Step 2 Screenshot](/images/2016/04/Step2.png)

Clear enough so far, although the code in Sublime is not looking so pretty:

![Step 2 Sublime Text Screenshot](/images/2016/04/Step2Sublime.png)

Quickly installing the [TypeScript plugin](https://github.com/Microsoft/TypeScript-Sublime-Plugin) from Microsoft[^n] seems to do the trick:

![Step 2 Sublime Text with TypeScript plugin](/images/2016/04/Step2SublimeFormatted.png)

If you need more details, here's a gist with the full setup for Sublime 3, assuming you've got nothing installed.

https://gist.github.com/dwmkerr/04fa8b8c15d049d0381e7798a79bcc45

At this stage the app will run, we can see the basics of the Angular 2 syntax and start experimenting.

## Step 3: Adding some components

At this stage the quick started guide starts going into more detail, guiding you through the process of creating multiple components. I decided to go off on my own here, with the rough plan of being able to write a set of goals for the day and turn it into a check-list[^n].

Within not much time I had the some basic components, input and output, bindings and so on. Some screenshots:

![Goals Screenshot 1](/images/2016/04/Goals-Screenshot-1.png)

![Goals Screenshot 2](/images/2016/04/Goals-Screenshot-2-1.png)

You can take a look at the code at this stage by checking out the 'step3' branch:

[github.com/dwmkerr/angular2-starter/tree/step3](https://github.com/dwmkerr/angular2-starter/tree/step3)

# Thoughts so far

For now, that's all I've got time for. I've had a chance to get a feel for Angular 2, I'm going to come back to this in a few weeks and integrate Redux, maybe swap out System.JS for Webpack and do some experimenting.

Opinions[^n] so far?

### Not Sold on TypeScript

I've used TypeScript in my mess around, rather than plain 'ol JavaScript, to keep the experience authentic to the angular team's goals of using TypeScript to help.

So far, I'm not seeing an enormous benefit. Some of the extra information available to auto-completion in nice, but this is a tooling thing.

JavaScript is not a static language, the TypeScript annotations I find slowing me down a little.

> There's so much extra domain specific *stuff* in Angular 2 that people might be lost without it. But if your stuff is so complex you need to adapt the base language, is it **too** complex?

### Explicit Component Surface Areas are a Nice Idea

When defining a component, you specify explicitly what comes *in* (data) and what goes *out* (events).

This means that the surface area of a component (i.e. the part you touch if you interact with it programmatically) is well defined. This is a good thing.

However, this is all handled with some pretty framework-specific stuff[^n]:

```language-javascript
// e.g.
export class GoalsBoxComponent {
   //  Event we fire when the goals change.
   @Output() goalsChanged: EventEmitter<Goal[]> = new EventEmitter();
}

// e.g.
export class GoalListComponent {
  //  Input is a set of goals to render.
  @Input() goals: Goal[] = [];
}
```

In a nutshell...

> Explicit component surface area is a cool idea.

React does this too with the optional `propTypes`, but it is not enforced. *However*, how this is done in Angular has already gone through a few radical changes with some [lively debate](https://github.com/angular/angular/pull/4435#issuecomment-144789359).

### Not ready for production... yet

There's no standardised, documented way to test a component - nuff said. But things are evolving quickly.

### Framework Fatigue

Comparing React to Angular is unfair, one is a view library, one is a framework. But it's worth pointing out this is a pretty complex framework. There's a **lot** of very domain specific stuff. See this documentation for an example:

```language-javascript
<li *ngFor="#hero of heroes">
```

From [the documentation](https://angular.io/docs/ts/latest/tutorial/toh-pt2.html):

> The (*) prefix to ngFor indicates that the `<li>` element and its children constitute a master template.
>
> ...
>
> The # prefix before "hero" identifies the hero as a local template variable. We can reference this variable within the template to access a hero’s properties.

You'll get used to it (if you have to), but I think it's harder to *reason* about than:

```language-javascript
render () {
  return (
    <div>
    {this.props.goals.map((goal) => {
      return <li>{goal.title}</li>;
    }</div>);
}
```

OK fair enough, JSX is very specific, but the **logic** (mapping an iterable) is JavaScript.

# Wrapping Up

That's it, for now. Next steps are to experiment more, see if it will play nice with Redux and share the next set of opinions.

I'd love to hear what you think, so drop your comments below!

**Footnotes** 

[^1]: Yeah I know - Microsoft making open source plugins for Sublime, brave new world eh?
[^2]: Which I then realised was pathetically similar to the standard 'Todo List' app used to demo MVC frameworks.
[^3]: My opinion only, but get involved in the discussion if you agree/disagree/have thoughts.
[^4]: Which, fairly, is what one would expect.

