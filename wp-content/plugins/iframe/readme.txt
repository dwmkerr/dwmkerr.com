=== iframe ===
Contributors: webvitaly
Donate link: http://web-profile.com.ua/donate/
Tags: iframe, embed, youtube, vimeo, google-map, google-maps
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 2.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

[iframe src="http://www.youtube.com/embed/A3PDXmYoF5U" width="100%" height="480"] shortcode

== Description ==

**[iframe](http://web-profile.com.ua/wordpress/plugins/iframe/ "Plugin page")** |
**[All iframe params](http://wordpress.org/plugins/iframe/other_notes/)** |
**[Donate](http://web-profile.com.ua/donate/ "Support the development")**

[iframe src="http://www.youtube.com/embed/A3PDXmYoF5U" width="100%" height="480"] shortcode
should show something like this:

[youtube http://www.youtube.com/watch?v=A3PDXmYoF5U]


Iframe shortcode is the replacement of the iframe html tag and accepts the same [params as iframe](http://wordpress.org/plugins/iframe/other_notes/) html tag does.
You may use iframe shortcode to embed content from YouTube, Vimeo, Google Maps or from any external page.

If you need to embed content from YouTube, Vimeo, SlideShare, SoundCloud, Twitter via direct link, you may use `[embed]http://www.youtube.com/watch?v=A3PDXmYoF5U[/embed]` shortcode.
[embed] shortcode is a core WordPress feature and can [embed content from many resources via direct link](http://codex.wordpress.org/Embeds).

= Useful: =
* ["Anti-spam" - block spam in comments](http://wordpress.org/plugins/anti-spam/ "no spam, no captcha")
* ["Page-list" - show list of pages with shortcodes](http://wordpress.org/plugins/page-list/ "list of pages with shortcodes")
* ["activetab" - responsive light theme](http://wordpress.org/themes/activetab "responsive light theme")

== Other Notes ==

= iframe params: =
* **src** - source of the iframe `[iframe src="http://www.youtube.com/embed/A3PDXmYoF5U"]` (by default src="http://www.youtube.com/embed/A3PDXmYoF5U");
* **width** - width in pixels or in percents `[iframe width="100%" src="http://www.youtube.com/embed/A3PDXmYoF5U"]` or `[iframe width="640" src="http://www.youtube.com/embed/A3PDXmYoF5U"]` (by default width="100%");
* **height** - height in pixels `[iframe height="480" src="http://www.youtube.com/embed/A3PDXmYoF5U"]` (by default height="480");
* **scrolling** - parameter `[iframe scrolling="yes"]` (by default scrolling="no");
* **frameborder** - parameter `[iframe frameborder="0"]` (by default frameborder="0");
* **marginheight** - parameter `[iframe marginheight="0"]` (removed by default);
* **marginwidth** - parameter `[iframe marginwidth="0"]` (removed by default);
* **allowtransparency** - allows to set transparency of the iframe `[iframe allowtransparency="true"]` (removed by default);
* **id** - allows to add the id of the iframe `[iframe id="my-id"]` (removed by default);
* **class** - allows to add the class of the iframe `[iframe class="my-class"]` (by default class="iframe-class");
* **style** - allows to add the css styles of the iframe `[iframe style="margin-left:-30px;"]` (removed by default);
* **same_height_as** - allows to set the height of iframe same as target element `[iframe same_height_as="body"]`, `[iframe same_height_as="div.sidebar"]`, `[iframe same_height_as="div#content"]`, `[iframe same_height_as="window"]` - iframe will have the height of the viewport (visible area), `[iframe same_height_as="document"]` - iframe will have the height of the document, `[iframe same_height_as="content"]` - auto-height feature, so the height of the iframe will be the same as embedded content. [same_height_as="content"] works only with the same domain and subdomain. Will not work if you want to embed page "sub.site.com" on page "site.com". (removed by default);
* **get_params_from_url** - allows to add GET params from url to the src of iframe; Example: page url - `site.com/?prm1=11`, shortcode - `[iframe src="embed.com" get_params_from_url="1"]`, iframe src - `embed.com?prm1=11` (disabled by default);
* **any_other_param** - allows to add new parameter of the iframe `[iframe any_other_param="any_value"]`;
* **any_other_empty_param** - allows to add new empty parameter of the iframe (like "allowfullscreen" on youtube) `[iframe any_other_empty_param=""]`;

== Screenshots ==

1. [iframe] shortcode

== Changelog ==

= 2.7 - 2013-06-09 =
* minor changes

= 2.6 - 2013-03-18 =
* minor changes

= 2.5 - 2012-11-03 =
* added 'get_params_from_url' (thanks to Nathanael Majoros)

= 2.4 - 2012-10-31 =
* minor changes

= 2.3 - 2012.09.09 =
* small fixes
* added (src="http://www.youtube.com/embed/A3PDXmYoF5U") by default

= 2.2 =
* fixed bug (Notice: Undefined index: same_height_as)

= 2.1 =
* added (frameborder="0") by default

= 2.0 =
* plugin core rebuild (thanks to Gregg Tavares)
* remove not setted params except the defaults
* added support for all params, which user will set
* added support for empty params (like "allowfullscreen" on youtube)

= 1.8 =
* Added style parameter

= 1.7 =
* Fixing minor bugs

= 1.6.0 =
* Added auto-height feature (thanks to Willem Veelenturf)

= 1.5.0 =
* Using native jQuery from include directory
* Improved "same_height_as" parameter

= 1.4.0 =
* Added "same_height_as" parameter

= 1.3.0 =
* Added "id" and "class" parameters

= 1.2.0 =
* Added "output=embed" fix to Google Map

= 1.1.0 =
* Parameter allowtransparency added (thanks to Kent)

= 1.0.0 =
* Initial release

== Installation ==

1. install and activate the plugin on the Plugins page
2. add shortcode `[iframe src="http://www.youtube.com/embed/A3PDXmYoF5U" width="100%" height="480"]` to page or post content
