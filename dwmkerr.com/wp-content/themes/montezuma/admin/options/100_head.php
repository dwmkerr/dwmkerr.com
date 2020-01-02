<?php return array(

'title'			=> __( '&lt;HEAD&gt;', 'montezuma' ),
'description' 	=> __( 'Configure the Document Head - the area between <code>&lt;HEAD&gt;</code> and <code>&lt;/HEAD&gt;</code>', 'montezuma' ),


array(
'id'		=> 'favicon_url',
'type' 		=> 'upload-image',
'title'		=> __( 'Favicon image', 'montezuma' ),
'std'			=> '',
'style'		=> 'width:16px;height:16px',
'after'		=> __( 'A favicon is that little 16x16 pixel image in the Microsoft ".ico" format that you 
				see in various places in the browser, e.g. in Firefox, in front of the URL in the URL field.
				<span class="clicktip">?</span>
				<div class="hidden">
				You should set a favicon file even if you don\'t really want one, and here\'s why:<br><br>
				All browsers will check your web site for a <code>favicon.ico</code> file in the root directory of your domain 
				and if neither that nor another one is set, you get a 404 error printed in your web server\'s error log 
				on each pageview.<br><br>
				"ico" is an actual file format, so instead of simply renaming a .png or .gif file you should create an 
				actual .ico file. Transform an 
				existing .png (can be transparent, too) online <a target="_new" href="http://tools.dynamicdrive.com/favicon/">here</a> 
				or even create one from scratch <a target="_new" href="http://www.favicon.cc/">here</a>. Various desktop 
				graphic programs can also be used for this.
				</div>', 'montezuma' ),
),	


array(
	'id'	=> 'meta_xua_compatible',
	'type' 	=> 'text',
	'title'	=> __( 'Meta tags', 'montezuma' ),
	'before' => '<h4>' . __( '<span>X-UA-Compatible</span> Meta Tag', 'montezuma' ) . '</h4>',
	'std' 	=> '<meta http-equiv="X-UA-Compatible" content="ie=edge;chrome=1">',
	'after' => __( '<br>Adjustment for Internet Explorer, will only be printed if browser 
				is IE. Leave empty to remove.<br>', 'montezuma' )
),

array(
	'id'	=> 'meta_viewport',
	'type' 	=> 'text',
	'title'	=> '',
	'before' => '<h4>' . __( '<span>Viewport</span> Meta Tag', 'montezuma' ) . '</h4>',
	'std' 	=> '<meta name="viewport" content="width=device-width">',
	'after' => __( '<br>Adjustment for mobile users. Leave empty to remove.', 'montezuma' )
),

array(
	'id'	=> 'wp_generator',
	'type' 	=> 'checkbox',
	'title'	=> '',
	'before' => '<h4>' . __( '<span>Generator</span> Meta Tag', 'montezuma' ) . '</h4>',
	'std' 	=> 1,
	'after' => __( '<code>&lt;meta name="generator" content="WordPress 3.7" /&gt;</code>
	<br>Prints the WordPress version being used for the current site. Uncheck to remove.', 'montezuma' )
),

array(
	'id'	=> 'xfn_link',
	'type' 	=> 'checkbox',
	'title'	=> '',
	'before' => '<h4>' . __( '<span>XFN</span> Link', 'montezuma' ) . '</h4>',
	'std' 	=> 1,
	'after' => __( '<code>&lt;link rel="profile" href="http://gmpg.org/xfn/11" /&gt;</code>
		<br>The <a target="_blank" href="http://gmpg.org/xfn/">XHTML Friends Network</a> "represents human relationships using hyperlinks". Uncheck to remove.', 'montezuma' )
),

array(
	'id'	=> 'wlwmanifest_link',
	'type' 	=> 'checkbox',
	'title'	=> '',
	'before' => '<h4>' . __( '<span>WLW</span> Link', 'montezuma' ) . '</h4>',
	'std' 	=> 1,
	'after' => sprintf( __( '<code>&lt;link rel="wlwmanifest" type="application/wlwmanifest+xml" href="%1$s/wp-includes/wlwmanifest.xml" /&gt;</code><br>
	The WLW Manifest link is for the <a target="_blank" href="http://en.wikipedia.org/wiki/Windows_Live_Writer">Windows Live Writer</a>, a Windows desktop software for publishing posts and pages from your desktop 
	computer to your blog. Uncheck to remove.', 'montezuma' ), site_url() )
),

array(
	'id'	=> 'rsd_link',
	'type' 	=> 'checkbox',
	'title'	=> '',
	'before' => '<h4>' . __( '<span>RSD</span> Link', 'montezuma' ) . '</h4>',
	'std' 	=> 1,
	'after' => sprintf( __( '<code>&lt;link rel="EditURI" type="application/rsd+xml" title="RSD" href="%1$s/xmlrpc.php?rsd" /&gt;</code>
	<br><a target="_blank" href="http://en.wikipedia.org/wiki/Really_Simple_Discovery">Really Simple Discovery</a> is an XML format and a publishing 
	convention for making services exposed by a blog, or other web software, discoverable by client software. Uncheck to remove.', 'montezuma' ), home_url() )
),

array(
	'id'	=> 'feed_links',
	'type' 	=> 'checkbox',
	'title'	=> '',
	'before' => '<h4>' . __( '<span>Feed</span> Links', 'montezuma' ) . '</h4>',
	'std' 	=> 1,
	'after' => sprintf( __( '<code>&lt;link rel="alternate" type="application/rss+xml" title="%1$s &raquo; Feed" href="%2$s/feed/" /&gt;
<br>&lt;link rel="alternate" type="application/rss+xml" title="%3$s &raquo; Comments Feed" href="%4$s/comments/feed/" /&gt;</code>
	<br>Prints the the links to the general feeds: Post and Comment Feed. Uncheck to remove.', 'montezuma' ), get_bloginfo('name'), home_url(), get_bloginfo('name'), home_url() )
),

array(
	'id'	=> 'feed_links_extra',
	'type' 	=> 'checkbox',
	'title'	=> '',
	'before' => '<h4>' . __( '<span>Extra Feed</span> Links', 'montezuma' ) . '</h4>',
	'std' 	=> 1,
	'after' => sprintf( __( '<code>&lt;link rel="alternate" type="application/rss+xml" title="%1$s &raquo; Title of post here Comments Feed" href="http://test.bytesforall.com/2011/11/title-of-post-here/feed/" /&gt;</code>
	<br>Prints the links to the extra feeds, such as category feeds on category pages, and the comments feed for a given post on its dedicated single post page. Uncheck to remove.', 'montezuma'), get_bloginfo('name') )
),

array(
	'id'	=> 'adjacent_posts_rel_link_wp_head',
	'type' 	=> 'checkbox',
	'title'	=> '',
	'before' => '<h4>' . __( '<span>Adjacent Posts</span> Link', 'montezuma' ) . '</h4>',
	'std' 	=> 1,
	'after' => __( '<code>&lt;link rel=\'prev\' title=\'Title of previous post here\' href=\'URL of previous post here/\' /&gt;
<br>&lt;link rel=\'next\' title=\'Title of next post here\' href=\'URL of next post here\' /&gt;</code>
	<br>Prints next and previous post links. Uncheck to remove.', 'montezuma' )
),


array(
	'id'	=> 'insert_head_top',
	'type' 	=> 'codemirror',
	'title'	=> __( 'Insert code', 'montezuma' ),
	'before' => __( '<h3><code>&lt;HEAD&gt;</code> Top</h3>
				<p>Insert HTML/Javascript code right after the opening <code>&lt;HEAD&gt;</code> tag. 
				<span class="clicktip">?</span></p>
				<div class="hidden">
				Don\'t put visible HTML here such as <code>&lt;div class="someclass"&gt;Some text here&lt;/div&gt;</code><br>
				Only <code>&lt;base&gt;</code>, <code>&lt;link&gt;</code>, <code>&lt;meta&gt;</code>, <code>&lt;script&gt;</code>, 
				and <code>&lt;style&gt;</code> tags. <a target="_blank" href="http://www.w3schools.com/html/html_head.asp">More info at w3schools</a>.
				</div>', 'montezuma' )
),

array(
	'id'	=> 'insert_head_bottom',
	'type' 	=> 'codemirror',
	'title'	=> '',
	'before' => __( '<h3><code>&lt;HEAD&gt;</code> Bottom</h3>
				<p>Insert HTML/Javascript code right before the closing <code>&lt;/HEAD&gt;</code> tag. 
				<span class="clicktip">?</span></p>
				<div class="hidden">
				Don\'t put visible HTML here such as <code>&lt;div class="someclass"&gt;Some text here&lt;/div&gt;</code><br>
				Only <code>&lt;base&gt;</code>, <code>&lt;link&gt;</code>, <code>&lt;meta&gt;</code>, <code>&lt;script&gt;</code>, 
				and <code>&lt;style&gt;</code> tags. <a target="_blank" href="http://www.w3schools.com/html/html_head.asp">More info at w3schools</a>.
				</div>', 'montezuma' )
),


	
);