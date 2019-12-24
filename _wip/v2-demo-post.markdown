---
layout: post
title: Koenig Demo Post
---

Hey there! Welcome to the new Ghost editor - affectionately known as **Koenig**.

Koenig is a brand new writing experience within Ghost, and follows more of a rich writing experience which you've come to expect from the best publishing platforms. Don't worry though! You can still use Markdown too, if that's what you prefer.

Because there are some changes to how Ghost outputs content using its new editor, we dropped this draft post into your latest update to tell you a bit about it – and simultaneously give you a chance to preview how well your theme handles these changes. So after reading this post you should both understand how everything works, and also be able to see if there are any changes you need to make to your theme in order to upgrade to Ghost 2.0.

* * *

# What's new

The new editor is designed to allow you have a more rich editing experience, so it's no longer limited to just text and formatting options – but it can also handle rich media objects, called cards. You can insert a card either by clicking on the `+` button on a new line, or typing `/` on a new line to search for a particular card.

Here's one now:

<figure class="kg-card kg-embed-card"><blockquote class="twitter-tweet">
<p lang="en" dir="ltr">Fun announcement coming this afternoon 🙈 what could it be?</p>— Ghost (@TryGhost) <a href="https://twitter.com/TryGhost/status/761119175192420352?ref_src=twsrc%5Etfw">August 4, 2016</a>
</blockquote>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</figure>

Cards are rich objects which contain content which is more than just text. To start with there are cards for things like images, markdown, html and embeds — but over time we'll introduce more cards and integrations, as well as allowing you to create your own!

## Some examples of possible future cards

- A chart card to display dynamic data visualisations
- A recipe card to show a pre-formatted list of ingredients and instructions
- A Mailchimp card to capture new subscribers with a web form
- A recommended reading card to display a dynamic suggested story based on the current user's reading history

For now, though, we're just getting started with the basics.

# New ways to work with images

Perhaps the most notable change to how you're used to interacting with Ghost is in the images. In Koenig, they're both more powerful and easier to work with in the editor itself - and in the theme, they're output slightly differently with different size options.

For instance, here's your plain ol' regular image:

<figure class="kg-card kg-image-card kg-card-hascaption"><img src="https://casper.ghost.org/v1.25.0/images/koenig-demo-1.jpg" class="kg-image"><figcaption>A regular size image</figcaption></figure>

But perhaps you've got a striking panorama that you really want to stand out as your readers scroll down the page. In that case, you could use the new full-bleed image size which stretches right out to the edges of the screen:

<figure class="kg-card kg-image-card kg-card-hascaption"><img src="https://casper.ghost.org/v1.25.0/images/koenig-demo-2.jpg" class="kg-image"><figcaption>It's wide</figcaption></figure>

Or maybe you're looking for something in between, which will give you just a little more size to break up the vertical rhythm of the post without dominating the entire screen. If that's the case, you might like the breakout size:

<figure class="kg-card kg-image-card kg-card-hascaption"><img src="https://casper.ghost.org/v1.25.0/images/koenig-demo-3.jpg" class="kg-image"><figcaption>It's wider, but not widest</figcaption></figure>

Each of these sizes can be selected from within the editor, and each will output a number of HTML classes for the theme to do styling with.

Chances are your theme will need a few small updates to take advantage of the new editor functionality. Some people might also find they need to tweak their theme layout, as the editor canvas previously output a wrapper div around its content – but no longer does. If you rely on that div for styling, you can always add it back again in your theme.

Oh, we have some nice new image captions, too :)

# What else?

Well, you can still write Markdown, as mentioned. In fact you'll find the entire previous Ghost editor _inside_ this editor. If you want to use it then just go ahead and add a Markdown card and start writing like nothing changed at all:

<!--kg-card-begin: markdown-->

Markdown content works just the way it always did, **simply** and _beautifully_.

<!--kg-card-end: markdown-->

of course you can embed code blocks

    .new-editor {
    	display: bock;
    }

or embed things from external services like YouTube...

<figure class="kg-card kg-embed-card"><iframe width="480" height="270" src="https://www.youtube.com/embed/CfeQTuGyiqU?feature=oembed" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></figure>

and yeah you can do full HTML if you need to, as well!

<!--kg-card-begin: html-->
<blink>hello world</blink>
<!--kg-card-end: html-->

So everything works, hopefully, just about how you would expect. It's like the old editor, but faster, cleaner, prettier, and a whole lot more powerful.

# What do I do with this information?

Preview this post on your site to see if it causes any issues with your theme. Click on the settings cog in the top right 👉🏼 corner of the editor, then click on ' **Preview**' next to the 'Post URL' input.

If everything looks good to you then there's nothing you need to do, you're all set! If you spot any issues with your design, or there are some funky display issues, then you might need to make some updates to your theme based on the new editor classes being output.

Head over to the [Ghost 2.0 Theme Compatibility](https://forum.ghost.org/t/ghost-2-0-theme-compatibility-help-support/2103) forum topic to discuss any changes and get help if needed.

That's it!

We're looking forward to sharing more about the new editor soon

