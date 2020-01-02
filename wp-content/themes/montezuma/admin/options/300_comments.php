<?php 

return array(

'title'			=> __( 'Comments', 'montezuma' ),
'description' => __( 'Configure the comment area', 'montezuma' ),


# Comment area title

array(
'id'		=> 'comments_title_single',
'type' 		=> 'text',
'title'		=> __( 'Comments title', 'montezuma' ),
'before' 	=> __( 'If there is 1 comment. <code>%2$s</code> = Post Title: <span class="arrow-down">&nbsp;</span><br>', 'montezuma' ),
'std'		=> '<span>One</span> thought on %2$s',
),

array(
'id'		=> 'comments_title_plural',
'type' 		=> 'text',
'title'		=> '',
'before' 	=> __( 'If there are 2 or more comments. <code>%1$s</code> = comment number, <code>%2$s</code> 
				= Post Title: <span class="arrow-down">&nbsp;</span><br>', 'montezuma' ),
'std'		=> '<span>%1$s</span> thoughts on %2$s',
'group'		=> true
),



# Avatar sizes

array(
'id'		=> 'avatar_size',
'type' 		=> 'text',
'title'		=> __( 'Avatar image', 'montezuma' ),
'before'	=> __( 'Put a number between 30 and 80 here. (50 means Avatar is 50x50 pixels):<br>', 'montezuma' ),
'after' 	=> __( ' &nbsp;<span class="arrow-left">&nbsp;</span> Default Avatar size', 'montezuma' ),
'std'		=> 50,
'style'		=> 'width:30px',
),

array(
'id'		=> 'avatar_size_small',
'type' 		=> 'text',
'title'		=> '',
'after' 	=> __( ' &nbsp;<span class="arrow-left">&nbsp;</span> Avatar size for 2nd+ level comments', 'montezuma' ),
'std'		=> 35,
'style'		=> 'width:30px',
'group'		=> true
),

array(
'id'		=> 'avatar_url',
'type' 		=> 'upload-image',
'title'		=> '',
'style'		=> 'width:100px;height:100px',
'before'	=> __( 'Default Avatar image URL: <span class="arrow-down">&nbsp;</span><br>', 'montezuma' ),
'after'		=> __( 'Leave empty to use the default avatar image as set in WP Admin -> Settings -> Discussion -> Default Avatar<br>', 'montezuma' ),
'group'		=> true
),	



# comment quicktags

array(
'id'		=> 'comment_quicktags',
'type' 		=> 'text',
'before'	=> '<img style="float:right;margin: 0 0 5px 15px" src="' . get_template_directory_uri() . '/admin/images/quicktagbuttons.png" />' . 
__( 'List "quicktag" buttons to display above the comment form. Separate with comma, without spaces: <span class="arrow-down">&nbsp;</span><br>', 'montezuma' ), 
'after'		=> __( '<br>Leave empty to not display any "quicktag" button. Available buttons: <code>strong</code>, 
				<code>em</code>, <code>link</code>, <code>block</code>, <code>code</code>, <code>close</code>. 
				This does not affect the HTML tags that are actually allowed. For that, see the next option.', 'montezuma' ),
'std'		=> 'strong,em,link,block,code,close',
'title'		=> __( 'Quicktag buttons', 'montezuma' ),
'style'		=> 'width:400px',
),

# allowed html

array(
	'id'	=> 	'comment_allowed_tags',
	'type' 	=> 	'checkbox-list',
	'values'=> 	array( 
					'a' => '<code>&lt;a href="..." title="..."&gt;</code>',
					'abbr' => '<code>&lt;abbr title="..."&gt;</code>',
					'acronym' => '<code>&lt;acronym title="..."&gt;</code>',
					'b' => '<code>&lt;b&gt;</code>',
					'blockquote' => '<code>&lt;blockquote cite="..."&gt;</code>',
					'br' => '<code>&lt;br&gt;</code>',
					'cite' => '<code>&lt;cite&gt;</code>',
					'code' => '<code>&lt;code&gt;</code>',
					'del' => '<code>&lt;del datetime="..."&gt;</code>',
					'dd' => '<code>&lt;dd&gt;</code>',
					'dl' => '<code>&lt;dl&gt;</code>',
					'dt' => '<code>&lt;dt&gt;</code>',
					'em' => '<code>&lt;em&gt;</code>', 
					'i' => '<code>&lt;i&gt;</code>',
					'ins' => '<code>&lt;ins datetime="..." cite="..."&gt;</code>',
					'li' => '<code>&lt;li&gt;</code>',
					'ol' => '<code>&lt;ol&gt;</code>',
					'p' => '<code>&lt;p&gt;</code>',
					'q' => '<code>&lt;q cite="..."&gt;</code>',
					'strike' => '<code>&lt;strike&gt;</code>',
					'strong' => '<code>&lt;strong&gt;</code>',
					'sub' => '<code>&lt;sub&gt;</code>',
					'sup' => '<code>&lt;sup&gt;</code>',
					'u' => '<code>&lt;u&gt;</code>',
					'ul' => '<code>&lt;ul&gt;</code>',
				),
	'title'	=> 	__( 'Allowed HTML', 'montezuma' ),
	'std'	=> array( 'a', 'abbr', 'acronym', 'b', 'blockquote', 'cite', 'code',
					'del', 'em', 'q', 'strike', 'strong' ), // default allowed tags in wp-includes/kses.php
	'columns' => 3,
	'before'	=> __( 'Check the HTML tags you want to allow inside comments. Uncheck all to not allow any HTML tags in comments:<br><br>', 'montezuma' )
),



# form custom code before / after

array(
'id'		=> 'comment_notes_before',
'type' 		=> 'codemirror',
'title'		=> __( 'Code before/after form', 'montezuma' ),
'before' 	=> __( 'Custom text or HTML right before the comment form text area. Will only be displayed to users that are not logged in 
(just like the name, email &amp; url input fields): <span class="arrow-down">&nbsp;</span><br>', 'montezuma' ),
'std'		=> '',
),

array(
'id'		=> 'comment_notes_after',
'type' 		=> 'codemirror',
'title'		=> '',
'before' 	=> __( 'Custom text or HTML right after the comment form text area: Will only be displayed to users that are not logged in 
(just like the name, email &amp; url input fields): <span class="arrow-down">&nbsp;</span><br>', 'montezuma' ),
'std'		=> '',
'group'		=> true
),









	
);
