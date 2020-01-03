---
author: Dave Kerr
type: posts
categories:
- JavaScript
- SVG
- HTML
- CSS
- CodeProject
date: "2018-07-29T23:36:46Z"
description: ""
draft: false
image: /images/2018/07/smile-1.png
slug: procedural-smiles-animating-svg-with-pure-javascript
tags:
- JavaScript
- SVG
- HTML
- CSS
- CodeProject
title: Procedural Smiles - Animating SVG with pure JavaScript
---


I recently needed to be able to generate a simple face image, with the face being able to scale from happy to sad.

(*Why* I needed to do this is a long story!)

This gave me the opportunity to have a play with SVG, which is something I've not done in a while and always wished I could spend more time with. You can see the result below, move the slider to see the smile animate:

<p data-height="265" data-theme-id="0" data-slug-hash="ejejeX" data-default-tab="result" data-user="dwmkerr" data-pen-title="SVG Smile" class="codepen">See the Pen <a href="https://codepen.io/dwmkerr/pen/ejejeX/">SVG Smile</a> by Dave Kerr (<a href="https://codepen.io/dwmkerr">@dwmkerr</a>) on <a href="https://codepen.io">CodePen</a>.</p>
<script async src="https://static.codepen.io/assets/embed/ei.js"></script>

Source: [github.com/dwmkerr/svg-smile/](https://github.com/dwmkerr/svg-smile)
CodePen: [codepen.io/dwmkerr/pen/ejejeX](https://codepen.io/dwmkerr/pen/ejejeX)

### How it works - geometry

This is quite a simple effect to achieve, the trick is just to work out how the geometry of the smile will work:

<img src="images/points.jpg" alt="Smile Geometry" />


The black points are the start and end point of the smile, the red points are the control points for the [bezier curve](^1). This means that we can scale from a smile to a frown by just interpolating the position of the anchor and control points from the two extremes shown above.

The face itself (without styling) just looks like this:

```html
<svg viewbox="0 0 120 120">
  <g transform='translate(60 60)'>
    <!-- First the main circle for the face. -->
    <circle
      cx="0"
      cy="0"
      r="50" />
    <!-- Then the left eye... -->
    <circle
      cx="-20"
      cy="-10"
      r="5" />
    <!-- Then the right... -->
    <circle
      cx="20"
      cy="-10"
      r="5" />
    <!-- The smile bezier curve. -->
    <g transform="translate(0, 25)">
      <path
        d="M-20,-10 C-20,10 20,10 20,-10" />
    </g>
  </g>
</svg>
```

The trick here is really just to use whatever coordinate system works for you. I start by defining a viewbox that gives me some space, translate the origin and then put the main circle of the face slap bang in the middle at `(0, 0)`.

The code to interpolate the smile control points is easier again if we shift the origin of the smile as well. This technique works well for SVGs (or any computer graphics), manipulate and transform to get the coordinate system to work for you and make it easier to reason about what is going on.

### How it works - animation

I've not animated SVG before. When looking into doing this, the vast majority of tips, blogs, articles and so on were suggesting to use a libary (common suggestions were [vivus](https://maxwellito.github.io/vivus/), [snap.svg](http://snapsvg.io/) and [svg.js](http://svgjs.com/)).

I've got no doubt that when you know what you are doing with SVG, using a library is a huge accelerator and saves on boilerplate. But if you don't know what a library is doing, what it is wrapping, or the problems it is solving for you, you are likely missing out some fundamentals.

Using a library is great if you know *what the problem is you are solving*. But if you don't, you end up never really learning. I had no idea whether this would be challenging to do with the pure SVG APIs and definitely wanted to work by hand.

After some experimentation, I was able to write the markup which would move the smile to a frown:

```html
<g transform="translate(0, 25)">
  <path id="smilepath" d="M-20,-10 C-20,10 20,10 20,-10">
    <animate
      attributeName="d" attributeType="XML"
      to="M-20,10 C-20,-10 20,-10 20,10" dur="3s"
      repeatCount="indefinite"
      fill="freeze"
    />
  </path>
</g>
```

The geometry we've already seen, all we've done here is swap the position of each anchor and its associated control point. The trick is just making sure that we get the attributes of the `animate` element right.

Once this is done, the final step is just to make it all programmatic. The code to generate the geometry of the path, based on a scale from 0 (sad) to 1 (happy) is online, but the interesting thing is how to run the animation:

```js
// note that 'scale' is 0->1 (sad->happy)
const points = writeSmilePoints(smilePoints(scale));
const svg = document.getElementById('svg');
const smilePath = document.getElementById('smilepath');
const animate = document.createElementNS(svg.namespaceURI, 'animate');

animate.setAttribute('attributeName','d');
animate.setAttribute('attributeType','XML');
animate.setAttribute('to',points);
animate.setAttribute('dur','0.3s');
animate.setAttribute('repeatCount','1');
animate.setAttribute('fill','freeze');
smilePath.appendChild(animate);
animate.beginElement();
```

There's not much to it. The bulk of the code is just setting up the attributes for the [`animate`](https://developer.mozilla.org/en-US/docs/Web/SVG/Element/animate) tag. Then we add it to the path as a child and call [`beginElement`](https://developer.mozilla.org/en-US/docs/Web/API/SVGAnimationElement) to start the animation.

The face is coloured in a similar way. Interpolating between a happy Simpsons yellow and angry red in JavaScript, then setting an `animate` element to target the `fill` of the appropriate circle.

### Wrapping Up

Playing with graphics is fun! This is only the most basic scratching of the surface of what SVG can do. The JavaScript to animate is trivial (although I can appreciate that browser inconsistencies and so on mean a libary is probably useful at some point).

The code is available on GitHub at [github.com/dwmkerr/svg-smile](https://github.com/dwmkerr/svg-smile) or on CodePen:

<p data-height="265" data-theme-id="0" data-slug-hash="ejejeX" data-default-tab="js,result" data-user="dwmkerr" data-pen-title="SVG Smile" class="codepen">See the Pen <a href="https://codepen.io/dwmkerr/pen/ejejeX/">SVG Smile</a> by Dave Kerr (<a href="https://codepen.io/dwmkerr">@dwmkerr</a>) on <a href="https://codepen.io">CodePen</a>.</p>
<script async src="https://static.codepen.io/assets/embed/ei.js"></script>

