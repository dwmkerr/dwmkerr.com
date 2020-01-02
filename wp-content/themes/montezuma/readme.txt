=== Montezuma ===


Usage Note:

For the drop down menus limit the amount of top level items or else 
the menu items will wrap to the next line as seen here:
http://themes.trac.wordpress.org/attachment/ticket/9353/top-menu.jpg

The Montezuma menus are "bold" and require some space. 


== Description ==
Montezuma is a customizable, responsive HTML5 WordPress with extensive admin area.

=========================================================================
	Montezuma Features
=========================================================================

Chooose between Responsive, Flexible and Static CSS grids:
----------------------------------------------------------
Inside the Montezuma you can choose between 10 Repsonsive, 5 flexible and 
5static CSS grids as the base for the theme layout. Additionally you can 
create custom layout rows with custom column widths.


Virtual template system: 
------------------------
Page templates and CSS can be edited and new templates can be created, 
online in the theme's backend, without the need to upload files with FTP. 
HTML, CSS & a limited set of 60+ WordPress & Montezuma PHP functions can 
be used. For full PHP access you can still customize the traditional way, 
by creating/editing templates in an editor on a desktop computer and 
uploading those files with FTP. Physical template files take precedence 
over Montezuma's virtual templates. You can even mix & match virtual and 
physical templates. The ability to create "child themes" is preserved. 

Note: If you copy a virtual template's content from a textarea in Montezuma's 
admin area into a pyhsical file on your computer, in order to create a physical 
template file, keep in mind that the virtual templates are usually missing 
the "loop" (for virtual templates the loop context is provided by /includes/parse_php.php). 
In your physical template wrap the part that prints a post or page into 
<?php while( have_posts() ): the_post(); ?> ... <?php endwhile; ?> or
 

Auto post thumbnails:
---------------------
bfa_thumb( $width, $height, $crop = false, $before = '', $after = '', $link = 'permalink' )
Cached, can be used on a per-page basis, for differently sized/cropped/linked post thumbnails. 
Will look for a featured image first, then for the first attached image, then for the first 
local image URL in a post. External image URLs are not used.


Advanced post excerpts: 
-----------------------
bfa_excerpt( $num_words = 55, $more = ' ...' )
%title% and %url% inside the $more parameter will be replaced with post title and
post URL. This lets you have different excerpt lengths and different "more" texts on a 
per-post or per-page basis. 


Advanced Meta Widget:
---------------------
Turn on/off the various links in the Meta Widget.


Numbered page navigation with core WP functions:
------------------------------------------------
No need for a plugin such as WP-PageNavi. The page pagination is done in a lightweight 
way with existing WP functions.


Streamlined HTML for WP navigation menus: 
-----------------------------------------
WordPress prints different HTML, including different CSS classes for 
(1) custom menus, (2) page menus and (3) category menus. Montezuma removes 
typically unused CSS classes, replaces WP's ID based CSS for individual menu 
items (".page-item-174") with more intuitive & semantic "slug" based CSS classes 
(".page-our-products"), and adds these short & meaningful CSS classes across 
all menu types: ".active", ".ancestor", ".parent", ".has-sub-menu", ".sub-menu"



See style.css for more info.

== Forum & Blog ==

Forum: http://forum.bytesforall.com
Blog: http://wordpress.bytesforall.com





== Bundled Resources: Javascript & Flash ==

* javascript/smooth-menu.js: http://www.opensource.org/licenses/mit-license.php
* javascript/masonry.js: http://www.opensource.org/licenses/mit-license.php
* javascript/html5.js: http://www.opensource.org/licenses/mit-license.php
* javascript/css3-mediaqueries.js: http://www.opensource.org/licenses/mit-license.php
* admin/ZeroClipboard.swf: http://www.opensource.org/licenses/mit-license.php
* admin/jquery.ui.colorPicker.js: http://opensource.org/licenses/BSD-3-Clause


== Bundled Resources: Images ==

* images/*.gif: http://www.gnu.org/licenses/gpl-2.0.html
* images/*.jpeg: http://www.gnu.org/licenses/gpl-2.0.html
* images/*.png: http://www.gnu.org/licenses/gpl-2.0.html
* images/*.ico: http://www.gnu.org/licenses/gpl-2.0.html
* images/*.psd: http://www.gnu.org/licenses/gpl-2.0.html
* admin/images/*.gif: http://www.gnu.org/licenses/gpl-2.0.html
* admin/images/*.jpeg: http://www.gnu.org/licenses/gpl-2.0.html
* admin/images/*.png: http://www.gnu.org/licenses/gpl-2.0.html
* admin/images/*.ico: http://www.gnu.org/licenses/gpl-2.0.html
* admin/images/*.psd: http://www.gnu.org/licenses/gpl-2.0.html

