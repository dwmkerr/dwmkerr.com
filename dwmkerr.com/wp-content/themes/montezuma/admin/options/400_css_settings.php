<?php 

$montezuma = get_option( 'Montezuma' );

$css_settings = array(
	'title'			=> __( 'CSS', 'montezuma' ),
	'description' 	=> __( 'For referencing background or other images use the following placeholders:
	<ul>
	<li><code>%tpldir%</code> = Template Directory = http://www.yourdomain.com/wp-content/themes/Montezuma</li>
	<li><code>%tplupldir%</code> = Template\'s own folder inside WP Uploads = http://www.yourdomain.com/wp-content/uploads/Montezuma</li>
	<li><code>%upldir%</code> = Default WordPress Uploads directory = http://www.yourdomain.com/wp-content/uploads</li>
	<li><span class="closemirror">Close Mirror</span></li>
	', 'montezuma' )
);


$choose_css_grid = array(
	'id'	=> 	'choose_css_grid',
	'type' 	=> 	'radio',
	'values'=> 	array( 
				'resp12-px-m0px'	=> '<strong>Responsive</strong> 960px. Margin 0',
				'resp12-px-m12px'	=> '<strong>Responsive</strong> 960px. Margin 12px',
				'resp12-px-m24px'	=> '<strong>Responsive</strong> 960px. Margin 24px',
				'resp12-px-m36px'	=> '<strong>Responsive</strong> 960px. Margin 36px',
				'resp12-px-m48px'	=> '<strong>Responsive</strong> 960px. Margin 48px',
				'resp12-percent-m08pc'	=> '<strong>Responsive</strong> 100%. Margin 0.8%',
				'resp12-percent-m20pc'	=> '<strong>Responsive</strong> 100%. Margin 2.0%',
				'resp12-percent-m32pc'	=> '<strong>Responsive</strong> 100%. Margin 3.2%',
				'resp12-percent-m44pc'	=> '<strong>Responsive</strong> 100%. Margin 4.4%',
				'resp12-percent-m56pc'	=> '<strong>Responsive</strong> 100%. Margin 5.6%',
				'12-960px-0'	=> '960px. Margin 0',
				'12-960px-12px'	=> '960px. Margin 12px',
				'12-960px-24px'	=> '960px. Margin 24px',
				'12-960px-36px'	=> '960px. Margin 36px',
				'12-960px-48px'	=> '960px. Margin 48px',	
				'12-percent-08'	=> '100%. Margin 0.8%',		
				'12-percent-20'	=> '100%. Margin 2.0%',	
				'12-percent-32'	=> '100%. Margin 3.2%',	
				'12-percent-44'	=> '100%. Margin 4.4%',	
				'12-percent-56'	=> '100%. Margin 5.6%',					
				),
	'std'	=> 	'resp12-px-m0px',
	'title'	=> 	__( 'Choose CSS Grid', 'montezuma' ),
	'columns' => 2,
	'before' => __( '<h3>Choose one of these 20 CSS Grids</h3>
<p>Choose one of these 20 grids to be used throughout the site. </p>
<p>All grids have 12 columns. "Margin" means the margin between columns, sometimes also called "Gutter Width". The margins 
have these seemingly awkward values like "36px" or "4.4%" on purpose. It needs to be that way to have the columns widths and margins 
of each row add up to exactly the row width. </p>', 'montezuma' ),
	'after' => __( '<p>10 Grids are responsive, 10 are not. 
Out of the 10 that are not responsive 
5 have a percent width so they DO adjust to the screen width however contrary to the responsive Grids 
they don\'t make bigger re-arrangements of the layout when the screen gets very small, e.g. on mobile devices.</p>

<h3>Custom width columns</h3> 
<p>You can "<strong>break out</strong>" of these predefined column widths by providing custom width and margin values for 
all columns of a certain row. You would keep using the class <code>row</code> for the container because that centers 
the container and makes the children DIV\'s align "side by side" (with <code>float:left</code>). Example:</p>
<pre style="color:blue">
&lt;div <span style="color:red">class="row"</span>&gt;
   &lt;div <span style="color:red">style="width:200px"</span>&gt;
      <span style="color:#666">... column on the left that is 200 pixels wide</span>
   &lt;/div&gt;
   &lt;div <span style="color:red">style="width:500px; margin-left:20px; margin-right:20px;"</span>&gt;
      <span style="color:#666">... column in the middle, 500 pixels wide, 20 pixels margin left and right.
      ... Simply leave out the margin code if you don\'t need margins between columns. 
      ... Alternatively you can always put padding inside a column.</span>
   &lt;/div&gt;
   &lt;div <span style="color:red">style="width:220px"</span>&gt;
      <span style="color:#666">... See, I gave this a width of 220px because:
      960 (row width, if a pixel based Grid was chosen) 
      - 200 (left col width) 
      - 500 (center col width) 
      - 20 (center col margin left)
      - 20 (center col margin left)
      =============================
      = 220 remaining width</span>
   &lt;/div&gt;
&lt;/div&gt;
</pre>', 'montezuma' ),

);

$google_fonts = array(
	'id'	=> 	'google_fonts',
	'type' 	=> 	'codemirror',
	'std'	=> 	'Yanone+Kaffeesatz:400,200',
	'title'	=> 	__( 'Add Google Fonts', 'montezuma' ),
	'before' => '<img style="float:right;margin: 10px 0 5px 15px" src="' . get_template_directory_uri() . '/admin/images/googlewebfonts-2.png" />' . 
__( '<p>Before you can use Google Fonts in your CSS you need to add the fonts here. 
You should not add more than a few fonts or else your site may slow down. </p>
<h3>Adding Google Fonts to Montezuma</h3>
<p>Start by visting the <a target="_blank" href="http://www.google.com/webfonts/">Google Web Fonts Site</a>, 
browse through the fonts there and for each font you want click its "<strong>Quick-use</strong>" link 
which will lead to a page with more info about that font. </p>
<p>On that second page do this:</p>
<p><strong>1.</strong> In the section "<strong>Choose the styles you want</strong>" check (the checkboxes of) all the styles you want.</p>
<p><strong>2.</strong> In the section "<strong>Choose the character sets you want</strong>" check the character sets you want.</p>
<p><strong>3.</strong> Look at the "<strong>Page Load</strong>" graphic at the top right. Consider removing some 
character sets and/or styles you 
may not need, if that "Page Load" indicates that you are in the "red" area.</p>
<p><strong>4.</strong> In the blue section "<strong>Add this code to your web site</strong>" click the little "Javascript" tab.</p>
<p><strong>5.</strong> Copy the text <strong>inside the single quotes</strong>:<br>
<pre>
google: { families: [ \'<span style="color:red;background:#fff;border:dotted 1px #000;padding:5px 10px">Anonymous+Pro:400,400italic,700:cyrillic-ext,latin,latin-ext</span>\' ] }
</pre> 
and paste it into the textarea below. All fonts you want, one font code per line, and finally click the 
"<strong>Save Changes</strong>" button.</p>', 'montezuma' ),
	'after' => __( '<h3>Using the Fonts in your CSS</h3>
</p>Now you can use those fonts in your CSS like you\'d use any other font: <br>
<pre>h2 { font-family: \'Anonymous Pro\', sans-serif; font-weight: normal }
h1 { font-family: \'Yanone Kaffeesatz\', sans-serif; font-weight: normal; }</pre>
You can also use numbers for font-weight such as <code>font-weight: 700;</code> if you added that "weight" number for that font from Google fonts. 
Note: Not all fonts have all weights. You see the available weights listed under "<strong>1. Choose the styles you want</strong>".
</p>', 'montezuma' ),

);


	
$cssinfo = array(
	'id'	=> 	'editing-css',
	'type' 	=> 	'info',
	'title' => __( 'Editing CSS', 'montezuma' ),
	'std'	=> 	'',
	'before' => __( '<h3>About CSS Grids</h3>
<p  class="colcount3">
A CSS Grid system is of a bunch of predefined CSS classes (<code>row</code>, <code>col1</code>, 
<code>col2</code> ... <code>col12</code>). You apply these CSS classes to your 
layout containers (usually <code>&lt;div&gt;...&lt;/div&gt;</code>) to build CSS layouts in a streamlined, fast and easy fashion. 
The CSS Grid takes care of aligning the columns of a layout "side by side" and making sure the columns have the right width 
and fill up the row. 
Some CSS Grids provide additonal classes (<code>push</code>, <code>pull</code>) for "source ordering" 
which means re-arranging the columns in the source code (what the search engines see) without changing 
their visual order (what humans see), for possible search engine optimization benefits. All the Montezuma CSS Grids 
offer these additonal classes but you don\'t need to use them. Here are some basic examples, without source ordering:
</p>
<h3>Example: Basic 3 columns, followed by 4 columns</h3>', 'montezuma' ) . 
'<pre>
&lt;div <span style="color:green">class="row"</span>&gt;
  &lt;div <span style="color:blue">class="<strong>col3</strong>"</span>&gt;...column on the left, 3 "units" wide...&lt;/div&gt;
  &lt;div <span style="color:blue">class="<strong>col6</strong>"</span>&gt;...column in the middle, 6 "units" wide...&lt;/div&gt;
  &lt;div <span style="color:blue">class="<strong>col3</strong>"</span>&gt;...column on the right, 3 "units" wide...&lt;/div&gt;
&lt;/div&gt;
&lt;div <span style="color:green">class="row"</span>&gt;
  &lt;div <span style="color:blue">class="<strong>col3</strong>"</span>&gt;...column on the left, 3 "units" wide...&lt;/div&gt;
  &lt;div <span style="color:blue">class="<strong>col3</strong>"</span>&gt;...column 2nd from left, 3 "units" wide...&lt;/div&gt;
  &lt;div <span style="color:blue">class="<strong>col3</strong>"</span>&gt;...column 3rd from left, 3 "units" wide...&lt;/div&gt;
  &lt;div <span style="color:blue">class="<strong>col3</strong>"</span>&gt;...column on the right, 3 "units" wide...&lt;/div&gt;
&lt;/div&gt;
</pre>' . 
__( '<h3>Class <code>row</code> for rows and <code>col1</code> ... <code>col12</code> for columns</h3>
<p>
Note how each "row" container has a class <code>row</code>', 'montezuma' ) . 
'<pre>
&lt;div <span style="color:green">class="<strong style="font-size:18px">row</strong>"</span>&gt;
...
&lt;/div&gt;
</pre>' . 
__( 'and all column containers have a class "col<strong>X</strong>" with <strong>X</strong> being a number between 1-12, such as <code>col3</code> or <code>col6</code>', 'montezuma' ) . 
'<pre>
&lt;div <span style="color:blue;">class="<strong style="font-size:18px">col3</strong>"</span>&gt; ... &lt;/div&gt;
</pre>' . 
__( '<h3> Column class numbers always add up to 12</h3>
Also note how 
the sum of those numbers at the end of column class names ( col<span style="color:red">X</span> ) always is exactly 12 in each row', 'montezuma' ) . 
'<pre>
&lt;div class="row"&gt;
  &lt;div class="col<strong style="font-size:18px;color:blue">3</strong>"</span>&gt; ... &lt;/div&gt;
  &lt;div class="col<strong style="font-size:18px;color:blue">6</strong>"</span>&gt; ... &lt;/div&gt;
  &lt;div class="col<strong style="font-size:18px;color:blue">3</strong>"</span>&gt; ... &lt;/div&gt;
&lt;/div&gt;
<br>' . 
__( 'In this first row above it\'s 3+6+3 = 12.', 'montezuma' ) . 
'<br>
&lt;div class="row"&gt;
  &lt;div class="col<strong style="font-size:18px;color:blue">3</strong>"</span>&gt; ... &lt;/div&gt;
  &lt;div class="col<strong style="font-size:18px;color:blue">3</strong>"</span>&gt; ... &lt;/div&gt;
  &lt;div class="col<strong style="font-size:18px;color:blue">3</strong>"</span>&gt; ... &lt;/div&gt;
  &lt;div class="col<strong style="font-size:18px;color:blue">3</strong>"</span>&gt; ... &lt;/div&gt;
&lt;/div&gt;
<br>' . 
__( 'In the second row above it\'s 3+3+3+3 = 12.', 'montezuma' ) . 
'</pre>' . 
__( 'The sum has to be 12 because we\'re using 
"12 column grids" here. If we used a 16 column grid, the class numbers in each row would have to add up to 
exactly 16.</p>
<h3>About col1, col2, col3 ... col12</h3>
<p  class="colcount3">
In a CSS Grid you don\'t provide real width values for columns such as <code>210px</code> or <code>25%</code>. Instead you 
just add one of these classes to each column container DIV: ', 'montezuma' ) . 
'<code>col1</code>, <code>col2</code>, <code>col3</code>, 
<code>col4</code>, <code>col5</code>, <code>col6</code>, 
<code>col7</code>, <code>col8</code>, <code>col9</code>, 
<code>col10</code>, <code>col11</code>, <code>col12</code>.' . 
__( 'Each one of these 12 classes represents a certain predefined width.
<br><br>
The actual, exact width value and the width unit (pixels or percent) depend  
on the CSS Grid being used. <code>col3</code> is always approximately "one fourth" because "3" is "one fourth of 12".  
Or, in other words, and for a grid that is 960 pixels wide, <code>col3</code> is 960 pixels / 12 * <strong>3</strong> = 240 pixels.  
However, the exact width will usually be slightly smaller because a small fraction of the available row width 
is needed for the margin between the columns. 
<br><br>
Except if you use a CSS Grid with no margin between columns at all, such as 
<code>Responsive 960px. Margin 0</code> or <code>960px. Margin 0</code> (See "Choose CSS Grid"). In a CSS Grid with 
more margin between columns -  e.g. the <code>Responsive 960px. Margin 48px</code> Grid - 
the actual width of the columns will be smaller 
than in a Grid with less margin between columns such as the <code>Responsive 960px. Margin 0</code> Grid. Bigger margins 
consume more of the available total width of a row (960 pixels or 100%), leaving less width to the columns.
</p>
<h3>Why not just use actual values for the column widths?</h3> 
<p  class="colcount3">
For one, you don\'t need to do complicated 
maths such as adding and substracting numbers like "214", "139", "712". The only math you do is to make sure 
that those numbers in the col<strong>X</strong> column classes always add up to 12 within the same row. Then, CSS Grids 
make creating "Responsive" layouts much easier. Advantage 3: Easy "source ordering" for possible SEO benefits. Finally,
these CSS Grids provide more than just the width of columns, they also deal with everything else needed to 
create a layout.    
</p>
<h3>So, the CSS Grid columns have predefined widths. What if I need columns with exact, custom widths?</h3>
<p  class="colcount3">
A disadvantage of CSS Grids is that custom widths aren\'t built in. However you CAN still have 
custom widths, by doing whatever you would have done to create custom column width if you were not using a Grid, 
such as providing actual pixel or percent width values for the columns, either inline (putting the CSS 
right on the element, in a template) or in one of the CSS files (such as <code>layout.css</code> or <code>various.css</code>). 
The CSS Grid isn\'t taking away that possibility. You can even put a width value on a 
column that is part of a CSS Grid row. In that case you should put width values on <strong>all</strong> columns in that row. 
You will also have to make sure that all column widths plus the margins between the columns add up to the row width, 
which usually is 960 pixels in pixel based Grids and 100% in percent based Grids. (10 pixel and 10 percent Grids are 
included in Montezuma).   
Paddings inside the columns and borders on the columns do not add to the width of a column and thus do not affect the total 
row width, because the global CSS rule <code>box-sizing: border-box;</code> applies to all HTML elements in Montezuma, 
which of course includes columns, whether they have custom widths or not.', 'montezuma' ) . 
'</p>
<pre>
&lt;div <span style="color:green">class="row"</span>&gt;
  &lt;div <span style="color:blue">style="width:217px"</span>&gt;... ' . __('column on the left, 217 pixels wide', 'montezuma' ) . ' ...&lt;/div&gt;
  &lt;div <span style="color:blue">style="width:594px"</span>&gt;... ' . __('column in the center, 594 pixels wide', 'montezuma' ) . ' ...&lt;/div&gt;
  &lt;div <span style="color:blue">style="width:149px"</span>&gt;... ' . __('column on the right, 149 pixels wide', 'montezuma' ) . ' ...&lt;/div&gt;
&lt;/div&gt;
</pre>' . 
__( '<p>You should also abandon the idea of source ordering and responsiveness when using custom column widths. 
It is possible to combine custom column widths, responsiveness and source ordering but that is a very manual process and 
requires good CSS knowledge.
</p>
<h3>box-sizing: border-box</h3>
<p  class="colcount3">
All Grids use <code>box-sizing: border-box</code> as do <strong>all</strong> HTML elements in Montezuma. 
In other words * everything * is set to <code>box-sizing: border-box</code>.  
This means the width of HTML elements is calculated in a more reasonable, human way by counting the padding 
("inner space") and the border of an element to its total width. 
<br><br>
So if you add padding or borders to 
an element it doesn\'t change its width, instead the content inside the element gets narrowed a bit to make 
room for the padding or border.  
This means you can apply borders and padding right on the column DIV\'s without affecting the column widths
(Margin would still affect the column width).  
<br><br>
In traditional CSS Grids doing this * would * affect the column widths, the column would get too wide and the 
right-most column would "drop down" all the way to the bottom of the layout. In traditional CSS Grids this 
is fixed by putting another extra DIV (important: without specified width) inside each column DIV. 
But this increases the amount of DIV\'s by 1 extra DIV for each column, and thus 
pollutes your source code with unnecessary code leading to a less optimal "noise to signal ratio". 
<br><br>
The only downside is that <code>box-sizing: border-box</code> is only supported natively by IE8 and up, however 
as of July 2012 IE7 is at 2-3% market share and dropping at a fast rate so it should be at 1% and less by the end of 2012 / early 2013 . 
For the sake of building for the future and now, and for keeping the code clean instead of carrying around numerous 
browser hacks, Montezuma starts dropping IE7 support now. <a href="http://paulirish.com/2012/box-sizing-border-box-ftw/">
More about box-sizing: border-box</a>
</p>', 'montezuma' ),

);


$aboutmenus = array(
	'id'	=> 	'about-menus',
	'type' 	=> 	'info',
	'title' => __( 'About DropDown Menus', 'montezuma' ),
	'std'	=> 	'',
	'before' => '<div style="width:500px;padding:15px;background:#eee;border:solid 1px #666;float:right;margin: 0 0 15px 15px;text-align:center">
<img src="' . get_template_directory_uri() . '/admin/images/assignmenus.png" />' . 
__( 'Creating custom menus and assigning them to Montezuma\'s "Theme Locations" at WP > Appearance > Menus', 'montezuma' ) . 
'</div>' . 
sprintf( __( '<h3>Montezuma provides 2 menus: "Menu 1" (Fallback: Page Menu) and "Menu 2" (Fallback: Category Menu)</h3>
<p>Montezuma has 2 built in menus which you can configure at 
WP > Appearance > Menus (see screenshot on the right). The Montezuma menu "locations" are 
<code>Menu 1</code> and <code>Menu 2</code>. If you don\'t assign a custom menu to any of these 
built in Theme "locations" then they will fall back to displaying automatic menus:
<ul><li><code>Menu 1</code>. Fallback: Displays Page Menu.</li>
<li><code>Menu 2</code>. Fallback: Displays Category Menu.</li></ul>
</p><h3>Display a menu by coyping &amp; pasting some code into a template</h3>
<div style="width:384px;padding:15px;background:#eee;border:solid 1px #666;float:right;margin: 0 0 15px 15px;text-align:center">
<img src="%1$s/admin/images/editmenucode.png" />
<br>Displaying a menu in Sub Templates > <code>header.php</code>. You could place this code in a Main Template as well, 
e.g. <code>index.php</code>
</div>
<p>To display a menu copy some code (see screenshot on the right) into a sub template or main template. 
The code on the right shows 2 menus being displayed in the "header" of the theme, thus the code 
needs to be put into <code>header.php</code>. The code could be placed in main templates as well, e.g. 
<code>index.php</code>. In that code the menus are referenced with that single id that is highlighted in the 
screenshot on the right:
<ul><li><code>Menu 1</code>: Displayed with <code>menu1</code>.</li>
<li><code>Menu 2</code>: Displayed with <code>menu2</code>.</li></ul>
</p><h3>Montezuma streamlines the HTML output of the various WordPress menus</h3>
<p>WordPress prints different HTML, including different CSS classes for (1) custom menus, 
(2) page menus and (3) category menus. Montezuma removes some code and adjust the rest 
so that the HTML and CSS is always the same, no matter if a custom menu, a fallback page menu or 
a fallback category menu is used. 
<h3>Montezuma provides lean and meaningful CSS across all menu types</h3>
<p>Montezuma replaces the ID based CSS classes that WordPress 
provides as a means for styling individual menu items, with more intuitive "slug" based CSS classes. 
So if you look at your CSS or HTML and see <code>.cat-uncategorized</code> you actually know that 
this is about the category "Uncategorized", whereas <code>.cat-item-48</code> doesn\'t tell you much. 
Also, when you move your site with Wp\'s export/import the slugs stay the same and the CSS stays valid whereas 
item ID\'s change after a WP export/import. Montezuma also  
removes quite a bit of unnecessary CSS classes for lighter code and adds these short, meaningful and useful CSS classes across 
all menu types (custom, fallback page, fallback category):
<ul>
<li><code>.active</code>: The current item gets <code>&lt;li class="active"&gt;</code></li>
<li><code>.ancestor</code>: All parents and grand parents of the current item get <code>&lt;li class="ancestor"&gt;</code> 
(Per default WordPress does not add "ancestor" classes to category menus).</li>
<li><code>.parent</code>: The direct parent of the current item gets "parent" in addition to "ancestor" <code>&lt;li class="parent ancestor"&gt;</code></li>
<li><code>.sub-menu</code>: All sub menu UL\'s get <code>&lt;ul class="sub-menu"&gt;</code> (Per default WordPress is inconsistent here 
and uses "children" for fallback menus and "sub-menu" for custom menus which means you\'d have to account for both in 
your CSS or else the styles would break when a custom menu gets assigned or un-assigned.).</li>
<li><code>.has-sub-menu</code>: All items that have children get <code>&lt;li class="has-sub-menu"&gt;</code> 
(Useful for adding those arrow or whatever icons that indicate that sub menus exist below an item. WordPress does not add this at all).</li>
<li><code>.cat-item-48</code> becomes <code>.cat-uncategorized</code> in category menus and custom menus</li>
<li><code>.page-item-174</code> becomes <code>.page-our-products</code> in page menus and custom menus</li>
<li>custom links get <code>.item-link-text-here</code></li>
</ul>
</p>', 'montezuma'), get_template_directory_uri() ),

);


$menuicon_string = '';
for( $i = 0; $i >= -2376; $i -= 24 ) {
 $menuicon_string .= '<div><i style="background-position:0 ' . $i . 'px"></i>' . $i . 'px</div>';
}


$menus_array = array( 'menu1' => 'Menu 1', 'menu2' => 'Menu 2' );
$menu_css_packs = array();
foreach( $menus_array as $menu_id => $menu_title ) {

	// pagemenu for all except menu2
	if( $menu_id == 'menu2' ) 
		$fallback = array( 'id' => 'bfa_cat_menu', 'type' => 'Category Menu' );
	else 
		$fallback = array( 'id' => 'bfa_page_menu', 'type' => 'Page Menu' );
	
	if( has_nav_menu( $menu_id ) ) {
	
		$menu_text = sprintf( __( 'A custom menu was assigned to "%1$s" at WP > Appearance > Menus. 
		So the example CSS below will apply to that custom menu. Should you ever remove the custom menu 
		from "%2$s" then the CSS for the default WP %3$s will 
		be displayed here because the default WP %4$s is the "Fallback" Menu for "%5$s".', 'montezuma' ), 
		$menu_title, $menu_title, $fallback['type'], $fallback['type'], $menu_title );
		
		$nav_menu_output = wp_nav_menu( array( 
			'depth' => 1,
			'container' => 'nav', 
			'container_class' => 'menu-wrapper lw', 
			'container_id' => 'menu1-wrapper', 
			'menu_id' => $menu_id, 
			'menu_class' => 'cf menu', 
			'theme_location' => $menu_id, 
			'fallback_cb' => $fallback['id'],
			'echo' => 0,
		) );

		// Get classes <li class="page-using-custom-template">
		preg_match_all (
			'/\<li class="(.*?)"\>/',
			$nav_menu_output,
			$matches
		);
		$menu_item_slugs = $matches[1];
		
	} else {

		$menu_text = sprintf( __( 'No custom menu was assigned to "%1$s" at WP > Appearance > Menus. 
		So the example CSS below will apply to the default WP %2$s because 
		that is the "Fallback" Menu for "%3$s".', 'montezuma' ), $menu_title, $fallback['type'], $menu_title );	
	
		$menu_item_slugs = array();
		
		if( $menu_id == 'menu2' ) { 
		
			$categories = &get_categories( array( 'parent' => 0, 'child_of' => 0 ) );
			if( $categories ) {
				foreach( $categories as $category ) 
					$menu_item_slugs[] = $category->slug ;							
			}
		} else {
		
			$pages = &get_pages( array( 'parent' => 0, 'child_of' => 0 ) );
			if( $pages ) {
				foreach( $pages as $page ) 
					$menu_item_slugs[] = $page->post_name ;
			}
		}
		
	}	

	$menu_css = "/* Part 1- for default state: */";
	$i = 0;
	foreach( $menu_item_slugs as $class ) {
		$menu_css .= "\n#{$menu_id} .{$class} > a > i { background-position: 0px {$i}px; }";
		$i -= 24;
	}
	$menu_css .= "\n/* Part 2 - for hover state: */";
	$i = 0;
	foreach( $menu_item_slugs as $class ) {
		$menu_css .= "\n#{$menu_id} .{$class}:hover > a > i, #{$menu_id} .{$class}.active > a > i { background-position: -24px {$i}px; }";
		$i -= 24;
	}

	$menu_css_packs[] = array( 'menu_id' => $menu_id, 'menu_title' => $menu_title, 'menu_css' => $menu_css, 'menu_text' => $menu_text );

}

$menu_css_examples = '';
foreach( $menu_css_packs as $menu_pack ) { 
	$menu_css_examples .= sprintf( __( '<h4>CSS for icons in "%1$s"</h4>Replace the second numbers and copy/paste the whole code into %2$s (see menu on the left). 
	If you don\'t want the icons to change on hover you don\'t need the second Part 2 of the code:', 'montezuma' ), 
	$menu_pack['menu_title'], '<code>menus_' . $menu_pack['menu_id'] . '.css</code>' ) . 
	'<textarea spellcheck="false" class="codemirrorarea code" id="' . $menu_pack['menu_id'] . '-icons-codemirror">' 
	. $menu_pack['menu_css'] . '</textarea>';
}



$menuicons = array(
	'id'	=> 	'menu-icons',
	'type' 	=> 	'info',
	'title' => __( 'Using Menu Icons', 'montezuma' ),
	'std'	=> 	'',
	'before' => '<style type="text/css">
.menuicon-container i { 
background: url(' . get_template_directory_uri() . '/images/menu-icons-0090d3.png) 0 0 no-repeat;
display: inline-block;
width: 24px;
height: 24px; 
margin-right: 3px;
vertical-align: -5px;
}
.menuicon-container div { 
display:inline-block;
width: 80px;
border: solid 1px #eee;
background: #fcfcfc;
padding:2px;
}
</style>' . sprintf( __( '<h3>100 menu icons included to get you started</h3>
<p>The icons on the right are all combined into one image <code>images/menu-icons-0090d3.png</code>. The "0090d3" stands for 
the color of the 2nd icon versions. The images contains 100 icons in gray (#666666) and blue (#0090d3). 
The reason both color versions are in the same file is to avoid any delay while "replacing" the 
background image on mouse hover. Scroll down for info about creating your own icon "Sprite Image" (Container image 
that contains many smaller images) in Photoshop.
</p>
<h4>Using the included icons</h4>
<p>Below you see a list of the 100 included icons. Copy &amp; paste the number of the icon into the CSS code below, (1) as the 
<strong>second number</strong> (2) for the menu item you want to use it for (3) into the textarea of the appropriate menu, 
like this:<br>
<code>#menu1 .page-<span style="color:red">sample-page</span> > a > i { background-position: 0px <span style="color:red">-1464px</span>; }</code>
<div style="margin: 20px 0;" class="menuicon-container">%1$s</div>
%2$s
<h4>About "Sprite Images"</h4>
<p>The reason all different icons are in one file is to save HTTP requests. Of course the selection of 100 images is arbitrary 
and will not fit your needs. The file is 45 kByte which is not terribly big but also not ideal especially if you use only 2 or 3 
of the icons. But these included icons are mainly for demonstration purposes and to get you started. And if you find some of the icons 
useful you could in fact use them for production.</p>
<h3>Creating your own "CSS Sprite" image for icons in Photoshop</h3>
<p>The included selection will probably not cover all your icon needs so you will probably end up creating your 
own icon image at some point. The included set was created with Photoshop and Photoshop "Custom Shapes" that you can get for free 
or buy for about $10-$50 on the web. </p>
<ul>
<img style="float:right;margin: 0 0 5px 15px" src="%3$s/admin/images/ps-customshapetool.png" />
<li>Search for "Photoshop Custom Shapes" in "csh" format and buy/download them. 
Facebook or other social icons are rare because legally you aren\'t allowed to alter the original versions 
although "everyone is doing it". But 
beyond that there is a huge range of nice icons at reasonable prices. 
</li><li>
After you downloaded you probably have to extract a ZIP file. After that you should have 1 or several .csh files.
Place them in the Photoshop Custom Shapes folder on your computer, e.g. "Programs > Adobe > Adobe Photoshop CS 5 (64bit) 
> Presets > Custom Shapes". After you placed the .csh file(s) in Photoshop\'s Custom Shapes folder, restart Photoshop.
</li><li>
In Photoshop turn on "View > Show > Grid", "View > Snap" and "View > Snap To > Grid". 
Change the grid size at "Edit > Preferences > Guides, Grids & Slices" to e.g. 12 pixels. That will let you snap 
icons to a size of 12x12px and 24x24px. Or set the grid size to 6 pixels for possible icon sizes of 
12x12, 18x18, 24x24 etc...
</li><li>Open a new file with File > New, with transparent background and these sizes: Width: 2 x the width of the planned icon size, 
e.g. 48pixels (or 24px if you don\'t plan to make 2 color versions of each icon). Height: A multiple of 
planned icon size, e.g. 480 pixels tall to have room for 20 icons at 24x24 pixels each. You can always crop the image later to 
something less tall.  
</li><li>
<img style="float:right;margin: 0 0 5px 15px" src="%4$s/admin/images/ps-customshapetool-2.png" />
Click the "Custom Shape" button on the left, it should 
be the 18th or so from top of the little tools icons on the left. It is probably hidden under the "Rectangle" or "Line" tool. 
Then click the little arrow as shown in the screenshot on the right, to open the drop dwon with all available custom shapes. 
Click on one of the shapes, then click into approximately the top left corner of one of your 12x12px grid boxes on your canvas, 
hold and drag the mouse pointer to approximately the bottom right corner of the 12x12px grid box. Release the mouse button. 
</li><li>
Place all icons on the canves, arrange them below each other and leave room on the right for 
a second color version if you plan to have two colors. Placing them below each other, with the 2nd color version 
to the right of each icon, also lets you do some 
programmatical things with PHP or JS later because you know 
the position of the next image is "+24 pixels down", and the X-position (first value in 
<code>background-position: Xpx Ypx</code>) is -24px.
</li><li>
You can do all this manually, e.g. create one, then the same again with another color etc... Or, more advanced, 
place one version of all icons first, then select all layers with "Shift+click" or "Ctrl+click", right click and "group" them,
"duplicate" the group, press the "v" key (or click the "Move Tool" icon, on the top left), then click on one of the icons and by 
pressing the cursor keys the whole copied group should move. "merge" the group and now you can change the color of all copied icons at once.
Another way to do this would be with an "Adjustment Layer" but I found the "Merge" technique to be easier for changing just the fill 
color. It might be different if you want to have effects like inner shadow on the 2nd group of icons. 
</li></ul>', 'montezuma' ), $menuicon_string, $menu_css_examples, get_template_directory_uri(), get_template_directory_uri() ),
);

$insert_for_sprites = array(
	'id'	=> 	'insert_for_css_sprites',
	'type' 	=> 	'codemirror',
	'title' => __( 'Insert &lt;i&gt; for Icons', 'montezuma' ),
	'std'	=> 	'
.widget ul li
.widget h3
.breadcrumbs ol li
.hentry ul li
.comment-text ul li
li.has-sub-menu a
.menu > li > a
.post-tags
.post-categories
',
	'before' => __( '<p>Add all the CSS selectors, one by line, into which you want to "prepend" (insert at beginning) a 
&lt;i&gt; tag. Example:</p>
<pre>
&lt;li&gt;
	<span style="color:red">&lt;i&gt;&lt;/i&gt;</span>   &lt;-- This is the prepended tag for the icon
	&lt;a href"..."&gt;Link Text&lt;/a&gt;
&lt;/li&gt;
</pre>
<p>This tag is not inserted per default because it is only needed for adding icons 
to HTML elements such as links or titles. A &lt;i&gt; is used for brevity, this could also have been 
a &lt;span&gt; tag. The need for an extra tag is caused by the usage of "CSS Sprite Images" for icons. 
These images have many icons side by side and by adding an extra tag just for the icon it will be 
avoided that the other neighbor icons "lurk through" the background. 
</p>', 'montezuma' ),
);


$dualtitlecolors = array(
	'id'	=> 	'dual-title-colors',
	'type' 	=> 	'codemirror',
	'title' => __( 'Dual Title Colors', 'montezuma' ),
	'std'	=> '
#sitetitle a
.hentry h2 a[rel=bookmark]
.hentry h1 a[rel=bookmark]
.image-attachment h1
.widget h3 span
',
	'before' => __( '<p>Add all text based CSS selectors where you want to have the first half of the text wrapped with a <code>&lt;span class="firstpart"&gt;</code> 
tag so that you can address and thus style that part differently, e.g. give it a different color. This is 
the effect you see on site, post &amp; widget titles in Montezuma\'s default style. Example:', 'montezuma' ) . 
'<pre>
&lt;h2&gt;
   &lt;a rel="bookmark" href="..."&gt;
      <span style="color:red">&lt;span class="firstpart"&gt;</span>Hello <span style="color:red">&lt;/span&gt;</span>
      world!
   &lt;/a&gt;
&lt;/h2&gt;
</pre>',
	'after' => __( '<p>With odd word counts the first part will wrap the bigger part, e.g. 
if the text has 3 words, the <code>&lt;span class="firstpart"&gt;</code> will wrap the first 2 words.', 'montezuma' ),
);


$color_hsla = isset( $montezuma['colorpicker']['hsla'] ) ? esc_attr( $montezuma['colorpicker']['hsla'] ) : "hsla(124, 100%, 65%, 0.6)";
$color_hex = isset( $montezuma['colorpicker']['hex'] ) ? esc_attr( $montezuma['colorpicker']['hex'] ) : "#0090d3";


$color_picker_solid = array(
	'id'	=> 	'color_picker_solid',
	'type' 	=> 	'info',
	'title'	=> 	__( 'Color Picker - Solid', 'montezuma' ),
	'before' => sprintf( __( 'Use this color picker to create CSS color codes which you can <strong>copy and paste</strong> into 
the appropriate CSS files.
<h3 style="margin-bottom:10px">Solid Color for Text, Borders & Shadows</h3>
Solid Color Picker with HEX values. Useful for cross-browser (text-) color, border-color and shadow-color. 
<div class="picker-container-wrap">
	<div class="picker-container">
		<input type="text" class="code hslapicker" id="picker-hex" name="Montezuma[colorpicker][hex]" value="%1$s" />
	</div>
</div>
<h4>Copy & Paste:</h4>
<input class="regular-text code" type="text" id="picker-color-text" value="">
<h4>Currently used colors:</h4>
<div id="bfa_used_colors"></div>
<h4>Usage Example</h4>
For solid colors there is no IE8 issue. Use them like this:', 'montezuma' ), $color_hex ) . 
'<br>
<pre>
.element {
   color: <span style="color:red">#123456;</span>
   border: solid 1px <span style="color:red">#123456;</span>
   box-shadow: 0 0 10px <span style="color:red">#123456;</span>
}
</pre>'
);


$color_picker_transparent = array(
	'id'	=> 	'color_picker_transparent',
	'type' 	=> 	'info',
	'title'	=> 	__( 'Color Picker - Transparent', 'montezuma' ),
	'before' => __( 'Use this color picker to create CSS color codes which you can <strong>copy and paste</strong> into the appropriate CSS files.', 'montezuma' ) . 
'<h3 style="margin-bottom:10px">Transparent Color for Backgrounds</h3>' . 
__( 'Transparent Color Picker with HSLA values, incl. Microsoft IE8+ fix with -ms-filter. Useful for cross-browser transparent background-color.', 'montezuma' ) . 
'<span class="showmore">' . __( 'More/Less', 'montezuma' ) . '</span>
<div class="showmore-content">' . 
__( 'HSLA works in all modern browsers incl. IE9 and is more intuitive than RGBA. RGBA has the advantage that 
its colors can be exactly transferred from Photoshop\'s RGB values but in Montezuma HSLA is favored due to the 
the very understandable color format which makes manual editing (darker/lighter & more/less saturation) of colors easy. 
You don\'t have to come back to the color picker here just to make small adjustments. It is also good practise and "looks 
good" if your let the "Hue", the "H" = first value in HSLA, which is basically the "color", stay the same throughout a site 
and only change the other 3 values to create pleasing variations of one color.', 'montezuma' ) . 
'</div>
<div class="picker-container-wrap">
	<div class="picker-container">
		<input type="text" class="code hslapicker" id="picker-hsla" name="Montezuma[colorpicker][hsla]" value="' . $color_hsla . '" />
	</div>
</div>
<br>' . __( 'For IE8 use:', 'montezuma' ) . '<br>
<input class="regular-text code" style="width:100%" type="text" id="picker-ie8-code" value=""><br>
<h4>' . __( 'Transparent Color Examples', 'montezuma' ) . '</h4>' . 
__( 'IE8 needs to be targeted separately because it does not know HSLA colors.', 'montezuma' ) . 
'<span class="showmore">' . __( 'More/Less', 'montezuma' ) . '</span>
<div class="showmore-content">' . __( 'The <code>-ms-filter: ...</code> fixes that well. HSLA works in IE9 but unfortunately -ms-filter "works", too, 
but with slightly off colors, and takes precedence in IE9 if present in the same CSS selector. To fix this you\'d have to set a separate background style for IE8 
as shown in the example below:', 'montezuma' ) . 
'</div>
<pre>
.element {
   background-color: <span style="color:red">hsla(124, 100%, 65%, 0.6);</span>
   ... other styles for ".element" ...
}
.ie8 .element {
   <span style="color:red">-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#994cfe58, endColorstr=#994cfe58)";</span>
}
</pre>
<br>' . 
__( 'You could use HSLA colors for text, border and shadows as well but it won\'t work in IE8.', 'montezuma' ) . 
'<span class="showmore">' . __( 'More/Less', 'montezuma' ) . '</span>
<div class="showmore-content">' . __( 'Fixing that with various -ms-filter\'s is messy and thus not included in Montezuma. 
One possible workaround is to specify solid colors first, followed by HSLA colors. 
IE8 should use the solid colors while other browsers will overwrite them with the HSLA colors:', 'montezuma' ) . 
'<br>
<pre>
.element {
   color: #123456;
   border: solid 1px #123456;
   box-shadow: 0 0 10px #123456;
   color: hsla(124, 100%, 65%, 0.6);
   border: solid 1px hsla(124, 100%, 65%, 0.6);
   box-shadow: 0 0 10px hsla(124, 100%, 65%, 0.6);
}
</pre>
</div>
'
);


$css_settings[] = $cssinfo;
$css_settings[] = $choose_css_grid;
$css_settings[] = $color_picker_solid;
$css_settings[] = $color_picker_transparent;
$css_settings[] = $google_fonts;
$css_settings[] = $aboutmenus;
$css_settings[] = $menuicons;
$css_settings[] = $insert_for_sprites;
$css_settings[] = $dualtitlecolors;


return $css_settings;

