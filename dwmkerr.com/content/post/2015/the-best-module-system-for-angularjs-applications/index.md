---
author: Dave Kerr
type: posts
categories:
- AngularJS
- CodeProject
- Javascript
- ECMAScript 6
date: "2015-03-18T14:47:10Z"
description: ""
draft: false
slug: the-best-module-system-for-angularjs-applications
tags:
- AngularJS
- CodeProject
- Javascript
- ECMAScript 6
title: The Best Module System for AngularJS Applications
---


I was working on a small and simple application built with AngularJS the other day. As with most applications like this, I start with a single JavaScript file caled `app.js` and no module system.

In the past I've used RequireJS with AngularJS. It's an awful mistake. It leads to a big jump in complexity with no benefts. Angular apps don't work well with AMDs, so really your are using RequireJS to combine files into one big file.

I'm sure there's a good analogy with hammers and nails. Something like:

> It's like banging nails into your face with a hammer.

Maybe a bit extreme. But those who've used the two together may well be nodding sagely.

I've also used Browserify. I prefer this approach, the syntax is cleaner. But it's still a pain.

Ideally, I'd like to use ECMA6 modules. So another approach is to just use ECMA6 module syntax and then compile your code with something like Traceur. But that requires quite a bit of tooling, slows down your pipeline and you're still not *really* using modules.

I think the best approach is this one from [Jeff Dicky](https://medium.com/@dickeyxxx) on his post [Best Practices for Building Angular.js Apps](https://medium.com/@dickeyxxx/best-practices-for-building-angular-js-apps-266c1a4a6917). Just forget all of the module stuff and concatenate only.

Start with this:

```
myproject
 - app/
 - css/
 - vendor/
 - index.html
```

Or whatever your preferred structure is. Then stick your main file in `app/`:

```
myproject
 - app/
   - app.js
 - css/
 - vendor/
 - index.html
```

Your `app.js` file should define your main Angular module:

```js
angular.module('app', []);
```

Now just go ahead and concatenate everything in your `app/` folder. Structure it however you want:

```
myproject
 - app/
   - components/
   - home/
   - profile/
   - app.js
 - css/
 - vendor/
 - index.html
```

Concat will put everything in the top level folder (i.e. `app.js`) first. As long as you don't put anything else in your top level folder (that comes before 'a' alphabetically) then it doesn't matter where you put your other files, as long as you define them without referencing any globals. So define your components like this:

```js
angular.module('app').controller('SomeController', function() {
  // something
});
```

No fuss no muss. No requires, no exports.

If you need a new service, write it and save it. Same for directives or controllers or filters. Add the source file and it's included, no messing around.

Keep it simple, don't force another module system on top of angular's, you don't get much benenfit. And wait patiently until ECMA6 moves more into the mainstream and we can start using native modules. There's less and less point in investing in some super-sophisticated complex fancy module system for a framework which in vNext will throw it all away and for a language which will finally get native modules.

### Words for Gulpers

If you are a gulp user, here's how a pipeline might look to concat your JavaScript:

```js
var gulp = require('gulp');
var jshint = require('gulp-jshint');
var stylish = require('jshint-stylish');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');
var ngAnnotate = require('gulp-ng-annotate');

//  Hints and builds all JavaScript.
gulp.task('js', function() {

  return gulp.src(['./client/app/**/*.js'])
    .pipe(jshint())
    .pipe(jshint.reporter(stylish))
    .pipe(jshint.reporter('fail'))
    .pipe(sourcemaps.init({loadMaps: true}))
      .pipe(concat('app.js'))
      .pipe(gulp.dest('./client/dist'))
      .pipe(ngAnnotate())
      .pipe(uglify())
      .pipe(rename({suffix: '.min'}))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./client/dist/'));

});
```

Watch your app javascript folder and when it changes, you'll hint everything, concat into a single distribution folder, annotate and uglify, as well as building full sourcemaps.

### What about other stuff?

For vendor code (jQuery, Bootstrap, whatever), don't bother trying to be smart and require or import it. Just include it in your app with script tags. I wouldn't go to the effort at trying to force some kind of smart module system on a language that doesn't really support it - uf you can get away with avoiding it, do so.

This is not an encouragement to be sloppy, this is just the easiest way to deal with the issue. The number of hours I've wasted tracking down 'bugs' which were subtle issues to do with require.js or type-os has definitely made the approach above my preferred approach.

