=== Floating Social Bar ===
Contributors: smub, griffinjt
Donate link: http://www.wpbeginner.com/wpbeginner-needs-your-help/
Tags: social, social media, floating share bar, share bar, facebook, twitter, google+, pinterest, linkedin, social sharing, tweet, google, google+1, like, share, plus one, socialite, tweet button, twitter button, facebook like, pin it, pinit button, linkedin button, linkedin share, sharing, social media buttons, social media widgets, social widget, wpbeginner, sharethis, sharebar, addthis, social bar
Requires at least: 3.4.1
Tested up to: 3.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Floating Social Bar is the best social media plugin for WordPress that adds a floating share bar to your content.

== Description ==

Social media share buttons are a must have for every site, but they can significantly impact your site's speed. At [WPBeginner](http://www.wpbeginner.com "WPBeginner"), we created the floating social bar to maximize our social media visibility without impacting our site speed.

Floating social bar is a light-weight WordPress plugin that adds a horizontal floating share bar to your blog posts, pages, and other post types. The floating ability allows this eye-catching social media bar to get you maximum shares.

= Slim and Fast =

Unlike other social media plugins, we only support major social media networks to keep our plugin slim. You can add share buttons for Twitter, Facebook, Google+, LinkedIn, and Pinterest.

[Floating Social Bar](http://www.wpbeginner.com/floating-social-bar/ "Floating Social Bar") only loads scripts when necessary. None of the social media scripts are loaded when the page is loaded. Instead we show a replica image with all the counts. We use the socialite script to only load social buttons when the user brings their mouse over the button. Doing this allows us to keep your site as fast as it would load without any social plugins.

= Easy to Use =

Floating Social Share Bar is extremely easy to use. There is a simple options interface that allows you to drag-and-drop the social buttons that you want to display. You can also use the drag-drop interface to control the order of how social share buttons appear on your site.

For developers, we have a template tag available for custom use cases.

We also have a metabox that allows you to disable the social media bar on specific posts or pages.

= Best Social Media Plugin for WordPress =

What is the best social media plugin for WordPress is one of the most common questions that we get asked at [WPBeginner](http://www.wpbeginner.com "WPBeginner"). We found it hard to recommend social plugins for WordPress because none of them met our standards. We only recommend what we use or would use.

All social plugins are very heavy and significantly slow down your site. This plugin is by far the fastest social media plugin for WordPress that exists.

We created this for our site over a year ago. After numerous requests from our users, we decided to release our internal social plugin for our audience and the greater WordPress community.

This plugin is being used on WPBeginner, [List25](http://list25.com "List25"), [SteadyStrength](http://www.steadystrength.com "SteadyStrength"), and numerous other properties of ours.

= Support =

We will do our best to provide support through the WordPress forums. However, please understand that this is a free plugin, so support will be limited. Please read this article on [how to properly ask for WordPress support and get it](http://www.wpbeginner.com/beginners-guide/how-to-properly-ask-for-wordpress-support-and-get-it/ "how to properly ask for WordPress support and get it").

= Credits =

This plugin is created by [Syed Balkhi](http://www.balkhis.com "Syed Balkhi") and [Thomas Griffin](http://thomasgriffinmedia.com/ "Thomas Griffin").

= What's Next =

If you like this plugin, then please leave us a good rating and review.

Consider following us on [Twitter](http://twitter.com/wpbeginner "Twitter"), [Facebook](http://facebook.com/wpbeginner "Facebook"), and [Google+](https://plus.google.com/101634180904808003404/ "Google+")

Check out [Soliloquy - The Best WordPress Slider](http://soliloquywp.com/ "Soliloquy")

Visit WPBeginner to learn from our [WordPress Tutorials](http://www.wpbeginner.com/category/wp-tutorials/ "WordPress Tutorials") and find out about other [best WordPress plugins](http://www.wpbeginner.com/category/plugins/ "Best WordPress Plugins")

== Installation ==

Extract the zip file and just drop the contents in the `wp-content/plugins/` directory of your WordPress installation and then activate the Plugin from Plugins page.

Go to Settings > Floating Social Bar for options.

More Details can be found on the [Floating Social Bar](http://www.wpbeginner.com/floating-social-bar/) page. You can also watch the video below for further instruction.

[youtube http://www.youtube.com/watch?v=-K7aTPT56-Q]

== Screenshots ==

We have a [Live Demo Setup here](http://www.balkhis.com/power-convenience-3-lessons-learn-retail-stores/).

1. Main Floating Social Bar plugin settings page.
2. Drag-and-drop enabling of social services.
3. Option to hide bar on individual pages.
4. Final output of the bar on your site.

== Frequently Asked Questions ==

= Is there a template tag for the plugin? =

Yes, in fact there is both a template tag and a shortcode available for use.

**Template Tag:** `floating_social_bar( $args = array(), $return = false )`

In the template tag, you can pass in an array of arguments to denote which social services you want displayed. The order in which the services are entered will be the order in which they are displayed. The following keys are available for use:

* facebook
* twitter
* google
* linkedin
* pinterest

For example, if you want to use the template tag to add in facebook and twitter services, you would do the following:

`if ( function_exists( 'floating_social_bar' ) ) floating_social_bar( array( 'facebook' => true, 'twitter' => true ) );`

**Shortcode:** `[fsb-social-bar]`

The shortcode takes the same parameters as the template with the following syntax:

`[fsb-social-bar facebook="true" twitter="true"]`

= Why is the button count 0? =

To maximize the performance, we get the social media count from each API and store it in a cache for 30 minutes. It only gets the count for the post when its loaded to reduce server load. For example, if no one visits your two year old post, then this plugin will not waste your precious server resources for that post. It only gets the count right when a user visits the post or page.

We have tested and noticed that this small delay have no impact in the number of shares you get. However, there is an option in the plugin setting’s page that allows you to change the minimum interval if you want to get faster updates. But remember, it will increase server load.

= How can I make the social bar static when using the shortcode or template tag? =

Easy. Just add `static="true"` to the shortcode or `'static' => true` to the template tag inside your array of arguments to make the social bar static (non-floating).

= How can I disable Socialite when using the shortcode or template tag? =

Again, easy. Just add `socialite="false"` to the shortcode or `'socialite' => false` to the template tag inside your array of arguments to prevent Socialite from running.

= Can I hide the social bar on specific posts or pages? =

Yes you can hide the floating social bar on specific posts or pages. All you have to do is go on your post’s edit screen where you will find a metabox to hide the social share bar.

= Why is the share bar not floating? =

If you can see the share bar on your site, but it is not floating then 99% of the time it is a conflict with another plugin or your theme.


== Changelog ==

= 1.1.5 =
* Fixed clearfix bug in Firefox.

= 1.1.4 =
* Fixed bug with subtle shift when viewing on mobile device.
* Fixed clearfix bug in Firefox.

= 1.1.3 =
* Fixed more bugs with URL encoding.

= 1.1.2 =
* Fixed bug with special characters not being encoded for sharing.

= 1.1.1 =
* Fixed bug where extra span tag existed in Google share count.
* Improved escaping of titles (by stripping HTML tags) for social media services.

= 1.1.0 =
* Fixed bug where options would be deleted if plugin was deactivated.
* Fixed bug with outputting closing div tag if no services were enabled.

= 1.0.9 =
* Fixed bug where Twitter share would open duplicate window if Tweets widget was present on page.

= 1.0.8 =
* Fixed bug where social bar would not display when using WordPress SEO social features.

= 1.0.7 =
* Fixed bug where content was not returned correctly.

= 1.0.6 =
* Fixed bug with Pinterest image and added a fallback image option.
* Improved loading of share counts so that there is zero impact of page time.
* Improved display of share bar by hiding 0 counts by default.
* Added ability to disable Socialite (see FAQ for disabling if using shortcode or template tag).
* Added better checks to ensure the bar will always float if checked to do so.

= 1.0.5 =
* Added ability to determine floating or static bar if done manually via shortcode or template tag (see FAQ for more info).

= 1.0.4 =
* Fixed bug in MultiSite that caused options to be same across all sites in network.
* Fixed bug with Pinterest if no images were found (can even set default image!).
* Improved opening of social service windows to match official buttons.
* Added ability to make the bar static and choose to display above or below the content (or both).
* General bug fixes and improvements.

= 1.0.3 =
* Fixed bug that sometimes prevented social bar from appearing.
* Fixed bug where $post wasn't set correctly for updating stats.
* Improved checks by making sure bar is only output in main loop.
* Improved scrolling transition from fixed to hidden when approaching comment respond areas.
* Removed unnecessary checks for multiple bar outputs (some people may want more than one with future updates).

= 1.0.2 =
* Fixed bug where items wouldn't work in MultiSite.
* Fixed bug that caused items to output on both blog and single pages.
* Improved mobile support by ensuring the bar doesn't float on mobile devices.
* Removed the default labeling for the bar.

= 1.0.1 =
* Fixed bug where scrolling wouldn't work if stopper div was not found.
* Fixed bug where stats updater wouldn't fire if template tag was used outside of a singular view.
* Fixed bug in Firefox where settings display was messed up.

= 1.0.0 =
* Initial release of the plugin.

== Press ==

* [Thomas Griffin Media](http://thomasgriffinmedia.com/blog/2013/07/on-building-the-floating-social-bar-plugin/)
* [HQTips](http://hqtips.com/web/wordpress/easy-way-to-add-floating-social-share-bar-in-wordpress/)
* [Softstribe](http://softstribe.com/wordpress/how-to-add-floating-social-sharing-buttons-in-wordpress)
* [WPBeginner](http://www.wpbeginner.com/plugins/how-to-add-a-floating-social-share-bar-in-wordpress/)
* [Binkd](https://www.binkd.com/social-media/4-ways-to-maximize-your-contents-social-media-visibility/)
* [Post Status](http://poststat.us/floating-social-bar/)
* [Techating](http://techating.com/2013/07/how-to-add-a-floating-social-share-bar-in-wordpress/)
* [Revthatup](http://www.revthatup.com/floating-social-bar-wordpress-plugin-by-wpbeginner-is-fast/)
* [Balkhis](http://www.balkhis.com/what-is-the-best-social-media-plugin-for-wordpress/)
* [TalkofWeb](http://www.talkofweb.com/how-to-add-floating-social-share-bar-in-wordpress-plugin/)
* [Sabza](http://sabza.org/best-social-media-plugin-wordpress-floating-social-bar/)
* [WPTavern](http://www.wptavern.com/plugin-review-floating-social-bar)
* [BobWP](http://www.bobwp.com/wordpress-floating-social-bar-plugin/)
* [WP Kube](http://www.wpkube.com/horizontal-floating-social-bar-plugin-wordpress/)
* [Freakify](http://freakify.com/floating-social-share-bar-best-wordpress-plugin/)