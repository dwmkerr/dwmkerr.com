<?php 

$subtemplatefiles = array(
	'title'			=> __( 'Edit Main Templates', 'montezuma' ),
	'description' 	=> __( 'Edit the default WordPress page templates.', 'montezuma' )
);

// Don't use 'maintemplate-...' as ID for 'info' items, should be reserved for actual templates

$info = array(
		'id' => 'info-subtemplates',
		'type' => 'info',
		'title' => __( 'About sub templates', 'montezuma' ),
		'std' => '',
		'before' => '<h3>' . __( 'Use sub templates for redundant layout parts - if you want', 'montezuma' ) . '</h3>
<img src="' . get_template_directory_uri() . '/admin/images/redundantsubtemplates.png" />
<p class="colcount3">' . __( 'The purpose of creating sub templates is to put layout parts that are likely to appear in more than just 1 
main template into a separate "sub" template for easy inclusion in various main templates. Once you want to change something in that 
specific layout part you only need to do <strong>one</strong> edit in that <strong>one</strong> sub template. 
If you had not created a sub template for that redundant layout part 
but had put the corresponding code right into multiple main templates instead, you would have to edit all those main templates 
whenever you want to change something in that particular layout part.', 'montezuma' ) . 
'</p>
<h3>' . __( 'Don\'t create sub templates for every little thing', 'montezuma' ) . '</h3>
<img style="float:right;margin: 0 0 5px 15px" src="' . get_template_directory_uri() . '/admin/images/toomanysubtemplates.png" />
<p  class="colcount2">' . 
__( 'Whether it is better to use a sub template for some layout part, or to put the code right into the 
affected main templates is a tradeoff between more complexity (= more files to deal with, "in which 
sub template did I put that code again...?") and more legwork (= having to do the same changes in 
several main templates). 
Technically you don\'t need to create additional sub templates on top of the already included ones, 
see below. You could as well put everything right into the main templates. Typically, if the required 
piece of HTML/PHP code is short, you might prefer to just put it into various main templates repeatedly, instead 
of creating yet another sub template for it. This really comes down to personal preference. 
VBulletin (a forum software) for instance has a rather "atomic" approach by using sub templates for 
even the smallest bits of code, which leads to a huge amount of sub templates. In WordPress the amount 
of sub templates is typically much smaller. A reasonable amount of redundant code between main templates, 
and the resulting legwork, is commonly accepted for the sake of keeping the amount of templates low.', 'montezuma' ) . 
'<p>
<h3>' . __( 'Some sub templates are already included and should be used: Header, Footer, Comments, Searchform and 1 basic Post Format template', 'montezuma' ) . '</h3>
<p>' . 
__( 'Even if you want to keep the number of sub templates low, i.e. you don\'t want to create any sub templates 
on your own: <strong>Post format</strong> templates and the <strong>comments</strong> template <em>must</em>, 
<strong>header</strong> and <strong>footer</strong> <em>should</em> be sub templates.', 'montezuma' ) . 
'</p>
<img style="float:right;margin: 0 0 5px 15px" src="' . get_template_directory_uri() . '/admin/images/headerfooter.png" />
<h3>' . __( 'Header &amp; Footer sub templates', 'montezuma' ) . '</h3>
<p  class="colcount2">' . 
__( 'It is a convention in WordPress (and in overall web development) to have a "header" and a "footer". After all, most web sites have 
a section at the top and one at the bottom which stay the same on most if not all pages of a web site. 
The "header" and "footer" sub templates 
are per default included in Montezuma and cannot be deleted. However, <strong>you can remove them from any main template</strong> by simply 
removing from all main templates in question the particular piece of code that includes those sub templates: 
To remove the header from a main template remove <code>&lt;?php get_header(); ?&gt;</code>. 
To remove the footer remove <code>&lt;?php get_footer(); ?&gt;</code>. 
<br><br>
By the way, the reason you can simply remove the header and footer from any main template is that they are 
fully self-contained sub templates in Montezuma - contrary to the default WP Themes and most other themes, which usually mimick 
those default themes closely. In Montezuma, the header.php does <strong>not</strong> contain the vital "document head" 
(&lt;head&gt;...&lt;/head&gt;) and the footer.php does <strong>not</strong> close some DIV containers that were opened 
by another template. The header.php and footer.php sub templates are self-contained just like the comments.php and the searchform.php 
templates so you can easily remove them from a main template without breaking anything.', 'montezuma' ) . 
'</p>
<h3>' . __( 'Comments sub template', 'montezuma' ) . '</h3>	
<img style="float:right;margin: -5px 0 5px 15px" src="' . get_template_directory_uri() . '/admin/images/commentstemplate.png" />
<p class="colcount2">' . 
__( 'The comments template is a complex template with a lot of code and easily deserves to be its own sub template. It is also expected 
to have such a comments template in a WP theme - not just as a convention, as it is the case with header and footer, but technically, too. 
In fact the comments template even has its own sub templates in Montezuma, 
"sub-sub-templates" so to say. The comments template exists per default in Montezuma and cannot be deleted, but you can 
remove it from a main template by removing the corresponding code that includes it, which is this:
<code>&lt;?php comments_template( \'\', true ); ?&gt;</code>. For instance, <strong>you may want to remove that code from the 
"page.php" main template if you don\'t want comments on any static page</strong>. Another option is to leave that code there and to turn off comments for 
individual pages, at the WP pages write panel.', 'montezuma' ) . 
'</p>
<h3>' . __( 'Only the "Standard" Post Format sub template is included, you can create the 9 special ones', 'montezuma' ) . '</h3>
<img style="float:right;margin: -5px 0 5px 15px" src="' . get_template_directory_uri() . '/admin/images/postformats.png" />
<p class="colcount2">' . 
__( '"Post format" is a WordPress feature introduced with WP version 3.1. Don\'t confuse it with "Post Types" which is a much 
bigger feature. Post Formats are a small albeit useful feature for "bloggish" sites that aren\'t satisfied with having 
posts that all look the same but want to have "Video Posts" and "Audio Posts" etc., too. The term "format" stands for "the piece of HTML/PHP code that 
is going to be used to display a certain post". So "format" refers to the way each post will look, whether the full post or just an 
excerpt will be displayed, whether the author or the post date will be displayed and so on. This is done in 2 steps. Step 1 is to 
assign one of the 10 available post formats to a post, in the WP post write panel. Step 2 is to 
have actual templates for each one of the 10 post formats. 
There is also a fallback mechanism built in, that is, <strong>WP will fall back to using the standard post format template if the more specific 
one</strong> for the given post - e.g. a "video" template for a post that was set to format "Video" - <strong>does not exist</strong>.', 'montezuma' ) . 
'</p>
<h4>' . __( 'Montezuma uses post formats only on multi post pages', 'montezuma' ) . '</h4>
<p class="colcount2">' . 
__( 'Montezuma uses this feature like WP\'s own "Twenty Eleven" theme, that is, 
post formats are used for controlling the display of posts on <strong>multi post pages</strong> - not on single post pages. 
Single post pages are rather individual anyway because the full post text and all images are displayed instead of just an excerpt 
as it is usually the case on multi post pages. And in case you want to have different templates for single post pages as well you 
can always create a custom main template and assign it to a particular post, in the WP posts write panel. In Montezuma you can 
assign Custom Page Templates to posts, too, not just to static pages.', 'montezuma' ) . 
'</p>
<h4>' . __( 'Per default, only the "Standard" post format exists in Montezuma - create the others as needed', 'montezuma' ) . '</h4>
<p class="colcount2">' . 
__( 'In Montezuma only the "Standard" post format template <code>postformat</code> exists per default. To make full use of the WP 
post formats feature, you can create the 9 "missing" special post format sub templates, which are <code>postformat-aside</code>, 
<code>postformat-audio</code>, <code>postformat-chat</code>, <code>postformat-gallery</code> etc....
Additionally, and this is a special Montezuma feature, you can also create whole new "sets" of post format templates such as: 
<code>myotherformat</code>, <code>myotherformat-aside</code>, 
<code>myotherformat-audio</code>, <code>myotherformat-chat</code>, <code>myotherformat-gallery</code> etc....
Neither the default set (name starting with "postformat") nor your custom post template sets need to be complete, due to the 
built-in fallback mechanism, however the standard templates ( "postformat", "myotherformat" ) of the set should exist.', 'montezuma' ) . 
'</p>
<img style="float:right;margin: -5px 0 5px 15px" src="' . get_template_directory_uri() . '/admin/images/searchform.png" />
<h3>' . __( 'Searchform sub template', 'montezuma' ) . '</h3>
<p class="colcount2">' . 
__( 'The searchform template is not editable but you can add / remove it from main templates by adding /removing this: 
<code>&lt;?php get_searchform(); ?&gt;</code>. You can also create a new custom searchform template. See below on 
how to include sub templates in main templates.', 'montezuma' ) . 
'</p>
<h3>' . __( 'Should a widget area be a sub template?', 'montezuma' ) . '</h3>
<p class="colcount2">' . 
__( 'No, because in Montezuma all it takes to put a widget area anywhere into a main template is this short piece of code: 
<code>&lt;?php dynamic_sidebar( \'Name of the widget area here\' ); ?&gt;</code>. So even though the resulting HTML code  
of a browser-rendered widget area may be huge (if you\'ve put many widgets into that widget area) the actual (in this case: PHP-) code to 
display that widget area is very short and hardly justifies messing with yet another sub template. 
Of course if you really want to, you can. 
<br><br>By the way, that code also creates that widget area in the WordPress backend, 
so it is really <strong>all</strong> you need to display <strong>and</strong> create a widget area in one go.', 'montezuma' ) . 
'</p>
<h3>' . __( 'So, which custom sub templates should I create?', 'montezuma' ) . '</h3>
<p class="colcount2">' . 
__( 'None, if you have nothing in mind. The existing sub templates should be enough for many use cases. Create a 
custom sub template for redundant layout parts (parts that appear in more than just 1 main template) that 
consist of more than just 1 line of HTML and/or PHP. Even with more code sub templates are optional - you can 
as well put everything into main templates instead of creating custom sub templates.', 'montezuma' ) . 
'</p>' . 
__( '<h3>How to include sub templates in main templates. It\'s different for default and custom sub templates.</h3> 
<h4>Including "header.php" and "footer.php"</h4>
<p><code>&lt?php get_header(); ?&gt;</code> and <code>&lt?php get_footer(); ?&gt;</code></p>
<h4>Including "comments.php"</h4>
<p><code>&lt?php comments_template( \'\', true ); ?&gt;</code></p>
<h4>Including "searchform.php"</h4>
<p><code>&lt?php get_searchform(); ?&gt;</code></p>
<h4>Including Post Format templates</h4>
<p>
<code>&lt?php bfa_loop( \'postformat\' ); ?&gt;</code> and for other sets of post format templates you may have created, 
e.g. "my-other-format": <code>&lt?php bfa_loop( \'my-other-format\' ); ?&gt;</code>. Note: This will include 
whatever special post format templates exist, so you <strong>don\'t do this</strong>: 
<code>&lt?php bfa_loop( \'postformat-video\' ); ?&gt;</code> (except if you want WP to use <code>postformat-video.php</code> 
even for posts that are <strong>not</strong> set to format "Video"). You put only the "basename" of your post format set into the 
brackets, e.g. "postformat" for Montezuma\'s default post format template set. WordPress figures out the rest <strong>on its own</strong> 
- it\'s software after all. For instance, WordPress knows very well that it should add "-video" to the template name and look for 
a template named <code>postformat-video.php</code> for a post that you\'ve set to "Format: Video" in the WP post write panel.
</p>
<h4>Including Custom sub templates</h4>
<p>
Including <strong>custom</strong> sub templates (those that don\'t fall into any of the 
groups listed above) in a main template is done with <code>&lt?php bfa_get_template_part( \'name-of-sub-template\' ); ?&gt;</code>.
</p>', 'montezuma' )
);

$info2 = array(
		'id' => 'add-subtemplate',
		'type' => 'info',
		'title' => __( '+ Add sub template', 'montezuma' ),
		'std' => '',
		'before' => '<div class="templatenameinfo"><h3>' . __( 'Template names must be unique', 'montezuma' ) . '</h3>
	<p>' . __( 'You cannot have a main template <code>whatever</code> and a sub template <code>whatever</code> at the same time.', 'montezuma' ) . '</p>
	<h3>' . __( 'Template names already in use', 'montezuma' ) . '</h3>
	<ul class="red">
	<li>comments</li>' 
	. bfa_get_used_templates( 'all_templates' ) . 
	'</ul>
</div>' . 
__ ( 'Template name, without ".php". No spaces. Only letters, numbers, <strong>-</strong> and <strong>_</strong>:', 'montezuma' ) . 
'<br><input class="item_name" type="text" style="color:blue;width:350px;text-align:right;font-size:20px;" value="" />
<code>.php</code><p>' . 
__( 'Make new template a copy of:', 'montezuma' ) . 
'<select id="make_copy_of"><option value="startblank">' . __( 'none (empty template)', 'montezuma' ) . '</option>
' . bfa_get_used_templates( 'used_subtemplates_dropdown' ) . '
</select></p>
<button class="ata-add-item" type="button" rel="subtemplate"><i></i>' . __( 'ADD Sub Template', 'montezuma' ) . '</button>' . 
__( '<h3>If you want to create a "post format" template, name the template like this:</h3>
<h3><code style="font-size:20px">[basename]</code> or <code style="font-size:20px">[basename]-[format]</code></h3>
<p><code>[basename]</code> can be anything. Montezuma\'s standard post format template basename is 
<code>postformat</code>.</p>
<p><code>[format]</code> must be one of the 9 special post format names: 
<code>aside</code>, 
<code>audio</code>, 
<code>chat</code>, 
<code>gallery</code>, 
<code>image</code>, 
<code>link</code>, 
<code>quote</code>, 
<code>status</code>, 
<code>video</code>.</p>
<h3>The "basename" becomes important when you start using post format templates:</h3>
<p>Inside main templates, you call the post format templates with <code>&lt;php bfa_loop( \'postformat\' ); ?&gt;</code> 
or <code>&lt;php bfa_loop( \'smallexcerpt\' ); ?&gt;</code>. The word inside the brackets is the "basename". 
For a post that you\'ve set to post format "Video" in the WP post write panel, the code 
<code>&lt;php bfa_loop( \'smallexcerpt\' ); ?&gt;</code> would look for a template 
<code>smallexcerpt-video</code> first, and if that does not exist, for a template 
<code>smallexcerpt</code>.</p>
<h3>Creating the "missing" post format templates for the default set, or creating a new set</h3>
<h4>1. Creating missing post format templates for the default set with the basename "postformat":</h4>
<p>Add all or a few of the 9 special post format templates to an existing "set". 
The complete set of post format templates for the default set with the basename "postformat" would be:</p>
<ul>
<li><code>postformat</code> - default in Montezuma, used for posts set to "Standard" and also other posts if the corresponding 
post format template does not exist.</li>
<li><strong>postformat-aside</strong> - would be used for posts set to "Aside" (at WP -> Posts -> Add New -> "Format").</li>
<li><strong>postformat-audio</strong> - would be used for posts set to "Audio".</li>
<li><strong>postformat-chat</strong> - would be used for posts set to "Chat".</li>
<li><strong>postformat-gallery</strong> - would be used for posts set to "Gallery".</li>
<li><strong>postformat-image</strong> - would be used for posts set to "Image".</li>
<li><strong>postformat-link</strong> - would be used for posts set to "Link".</li>
<li><strong>postformat-quote</strong> - would be used for posts set to "Quote".</li>
<li><strong>postformat-status</strong> - would be used for posts set to "Status".</li>
<li><strong>postformat-video</strong> - would be used for posts set to "Video".</li>
</ul>
<p>You would use this set of post format templates with <code>&lt;php bfa_loop( \'postformat\' ); ?&gt;</code></p>
<h4>2. Creating a whole new "set" of post formats altogether:</h4>
<p><strong>Examples:</strong></p>
<ul>
<li><code>myotherformat</code> - default, used for posts set to "Standard" and also other posts if the corresponding 
post format template does not exist.</li>
<li><code>myotherformat-aside</code> - would be used for posts set to "Aside" (at WP -> Posts -> Add New -> "Format").</li>
<li><code>myotherformat-audio</code> - would be used for posts set to "Audio".</li>
<li><code>myotherformat-chat</code> - would be used for posts set to "Chat".</li>
<li><code>myotherformat-gallery</code> - would be used for posts set to "Gallery".</li>
<li><code>myotherformat-image</code> - would be used for posts set to "Image".</li>
<li><code>myotherformat-link</code> - would be used for posts set to "Link".</li>
<li><code>myotherformat-quote</code> - would be used for posts set to "Quote".</li>
<li><code>myotherformat-status</code> - would be used for posts set to "Status".</li>
<li><code>myotherformat-video</code> - would be used for posts set to "Video".</li>
</ul>
<p>You would use this set of post format templates with <code>&lt;php bfa_loop( \'myotherformat\' ); ?&gt;</code></p>', 'montezuma' ) 
);

$subtemplatefiles[] = $info;
$subtemplatefiles[] = $info2;

$subtemplates_templates = array(
	array( 'id' => 'header', 'title' => 'header', 
	'before' => __( 'Edit the header sub template.', 'montezuma' ),
	'after' => __( 'This "header" sub template can be included in main templates with <code>&lt;?php get_header(); ?&gt;</code> <br>
	 To have no "header" in a main template, simply remove <code>&lt;?php get_header(); ?&gt;</code> from that main template\'s code.', 'montezuma' ) ),
	 
	array( 'id' => 'footer', 'title' => 'footer', 
	'before' => __( 'Edit the footer sub template.', 'montezuma' ),
	'after' => __( 'This "footer" sub template can be included in main templates with &lt;?php get_footer(); ?&gt;<br>
	 To have no "header" in a main template, simply remove <code>&lt;?php get_header(); ?&gt;</code> from that main template\'s code.', 'montezuma' ) ),

	array( 'id' => 'comments-password', 'title' => 'comments', 
	'before' => __( '<strong>You cannot edit comments.php itself.</strong> Instead, you can edit the following parts of comments.php:<h4>Password Required</h4> Edit the comment template section "Password Required". It will be displayed if 
	a password is required to view comments.<br>', 'montezuma' ) ),

	array( 'id' => 'comments-closed', 'title' => '', 
	'before' => __( '<h4>Comments Closed</h4> Edit the comment template section "Comments Closed". It will be displayed if there are comments, but comments are now closed. 
	( If there are no comments, nothing gets displayed. )<br>', 'montezuma' ) ),

	array( 'id' => 'comments-list', 'title' => '', 
	'before' => __( '<h4>Comment List</h4> Edit the comment list in the comment template.<br>', 'montezuma' ) ),

	array( 'id' => 'comments-comment', 'title' => '', 
	'before' => __( '<h4>One Comment in Comment List</h4> Edit single "Comment" item.<br>
	Wrap everything into a <code>&lt;li&gt;</code> element that is <strong>NOT</strong> closed: <code>&lt;li&gt; ....</code> (<- no closing &lt/li&gt;)<br>', 'montezuma' ) ),

	array( 'id' => 'comments-pingback', 'title' => '', 
	'before' => __( '<h4>One Pingback in Comment List</h4> Edit single "Pingback" item. <br>
	Wrap everything into a <code>&lt;li&gt;</code> element that is <strong>NOT</strong> closed: <code>&lt;li&gt; ....</code> (<- no closing &lt/li&gt;)<br>', 'montezuma' ) ),

	array( 'id' => 'comments-form', 'title' => '', 
	'before' => __( '<h4>Comment Form</h4> Edit the comment template section "Comment Form".', 'montezuma' ) ),

	array( 'id' => 'postformat', 'title' => 'postformat', 
	'before' => __( '<h4>Default postformat template</h4> - Edit the "Standard" post format template. 
	This one cannot be deleted. It will be used 
		when there is no better matching post format template. It will be used for all excerpts on all multi post pages, 
		if there is no better matching post format template, such as <code>postformat-video</code> for a 
		post that you\'ve set to Format: "Video" in the WP Post Write panel.', 'montezuma' ) ),
);

$existing_ids = array();

foreach( $subtemplates_templates as $tpl ) {
	$thisfile = array(
		'id' => 'subtemplate-' . $tpl['id'],
		'type' => 'codemirror',
		'title' => $tpl['title'] != '' ? $tpl['title'] .  '<span style="color:#666">.php</span>' : '',
		'std' => implode( "", file( get_template_directory() . "/admin/default-templates/sub-templates/{$tpl['id']}.php" ) ),
		'before' => isset( $tpl['before'] ) ? $tpl['before'] : '',		
		'after' => isset( $tpl['after'] ) ? $tpl['after'] : '',		
		'codemirrormode' => 'php',
		'istemplate' => 'subtemplate'
	);
	$subtemplatefiles[] = $thisfile;
	$existing_ids[] = 'subtemplate-' . $tpl['id'];
}

// Get any additional custom templates = code string in option $montezuma 
if( $montezuma ) {
	foreach( $montezuma as $key => $value ) {
		if( strpos( $key, 'subtemplate-' ) === 0 && ! in_array( $key, $existing_ids ) ) {
			$title = str_replace( substr( $key, 0, 12 ), '', $key );
			$thisfile = array(
				'id' => $key,
				'type' => 'codemirror',
				'title' => $title . '<span style="color:#666">.php</span>',
				'codemirrormode' => 'php',
				'before' => '<p><button onclick="return confirmDeleteItem(\'' . $title . '\')" class="ata-delete-item" type="button" id="deleteitem-'. $key .'"><i></i>' . 
				sprintf( __( 'Delete "%1$s"', 'montezuma' ), $title ) .'</button></p>',
				'istemplate' => 'subtemplate'
			);
			$subtemplatefiles[] = $thisfile;	
			#unset( $montezuma['maintemplate-' . $key] );
		}
	}
}

return $subtemplatefiles;
