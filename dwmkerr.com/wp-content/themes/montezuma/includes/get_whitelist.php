<?php 
function bfa_get_whitelist() {

	/* 'function_name' => array( 'type' => 'parameter_type' ),
	   parameter_type = single | array | queryarray | function
	   
	   'single' - 0 or 1 parameter: function_name() - function_name( parameter )
	   'array'  - parameter is array: function_name( array( 'key' => 'value', 'key' => 'value' ) )
	   'queryarray' - parameter is URL-query style: function_name('this=that&this=that&this=that')
	   'function' - parameters are function style: function_name( param, 'param2', param3 );
	*/
	
	$wl_global = array(

		'printf' => array( 
			'type' => 'function',
			'examples' => array(
				__( "<?php printf( __( 'Published in %1\$s on %2\$s', 'montezuma' ), the_date(), the_category( ' &middot; ' ) ); ?>", 'montezuma' )
				=> __( 'Prints and replaces the variables.', 'montezuma' ),
			),
			'info' => '	'
		),
		
		'get_header' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php get_header(); ?>' => __( 'Includes <code>header.php</code> sub template.', 'montezuma' ),
			),
			'info' => __( "In case you created a second 'header' sub template in addition to the standard <code>header.php</code>, 
			such as <code>header-2.php</code>, you could include it with <?php get_header( '2' ); ?>. Whatever you put into the 
			brackets will be appended to <code>header</code> with a dash <code>-</code> in between, and that file will be included: 
			E.g. to include <code>header-whatever.php</code> in a main template you would use <code><?php get_header( 'whatever' ); ?></code>", 'montezuma' )
		),
		
		'get_footer' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php get_footer(); ?>' => __( 'Includes <code>footer.php</code> sub template.', 'montezuma' ),
			),
			'info' => __( "Same as <code>get_header</code> but for the 'footer' sub template", 'montezuma' ),
		),

		'get_search_form' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php get_searchform(); ?>' => __( 'Includes <code>searchform.php</code> sub template.', 'montezuma' ),
			),
			'info' => __( 'Similar to <code>get_header</code> and <code>get_header</code> but without the option to 
			include alternative sub template versions by providing a parameter inside the brackets. Use as is.', 'montezuma' ),
		),		
		
		
		'__' => array( 
			'type' => 'function',
			'examples' => array(
				"<?php __( 'Some text', 'montezuma' ); ?>" => __( 'Replaces text with translated version', 'montezuma' ),
				"<?php __( 'Peter\'s <span class=\"peterpage\">Page</span>', 'montezuma' ); ?>" => 
				__( 'Note how that single quote is "escaped" with a backslash, 
				because single quotes are also used to wrap the string in  this example.', 'montezuma' ),
				"<?php __( \"Peter's <span class=\\\"peterpage\\\">Page</span>\", 'montezuma' ); ?>" => 
				__( 'Note how the double quotes are "escaped" with a backslash, 
				because double quotes are also used to wrap the string in this example. <strong>(Note: This exmaple 
				might not show a success window when copied with icon button but should copy regardless)</strong>', 'montezuma' ),
			),
			'info' => __( 'These are 2 underscore characters followed by 2 paramaters inside brackets. 
			The first parameter is always the text string, the second is <code>montezuma</code>. If you wrap the string into 
			single quotes then single quotes inside the string need to be escaped with a backslash but double quotes don\'t need to 
			be escaped. And the other way around with double quotes: If you wrap the string with double quotes you escape any double quotes 
			inside the string with a backslash while single quotes inside the string don\'t need to be escaped. Escaping means telling 
			that you want to print this character literally and that it is not supposed to be a string delimiter. 
			This function <code>__(...)</code> only makes sense as a parameter inside another function because it does not print anything 
			on itself. Example: <code><?php edit_post_link( __(\'Edit\', \'montezuma\'), \'<div class="post-edit">\', \'</div>\' ); ?></code>', 'montezuma' )
		),
		
		'_e' => array( 
			'type' => 'function',
			'examples' => array(
				'<?php _e( "Text", "montezuma" ); ?>' => __( 'Displays "Text" or the translated version of it', 'montezuma' ),
			),
			'info' => __( 'This prints the translation whereas __( ... ) just returns it.', 'montezuma' )
		),	
		
		'dynamic_sidebar' => array( 
			'type' => 'single',
			'examples' => array(
				"<?php dynamic_sidebar( 'Widget Area ONE' ); ?>" => 
					__( 'Displays the contents of a widget area named <code>Widget Area ONE</code>', 'montezuma' ), 
				"<?php dynamic_sidebar( 'My other widget area' ); ?>" => 
					__( 'Displays the contents of a widget area named <code>My other widget area</code>', 'montezuma' ), 
				"<?php dynamic_sidebar( 'Footer stuff' ); ?>" => 
					__( 'Displays the contents of a widget area named <code>Widget Area ONE</code>', 'montezuma' ), 				
			),
			'info' => __( 'Creates and displays widget areas in one go. 
			Simply put this short one-liner into a template to display the 
			contents of that widget area (= the widgets placed therein) at exactly the place in the template where you put the code. 
			This also creates that widget area in the WP backend at WP -> Appearance -> Widgets', 'montezuma' )
		),
		
		'home_url' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php echo home_url(); ?>' => __( 'Displays the content of the field labeled "Site Address (URL)" 
					in WP -> General -> Settings, usually without trailing slash. <code>http://www.mydomain.com</code>', 'montezuma' ),
			),
			'info' => __( '<code>home_url</code> does not print anything on its own, so use it with <code>echo</code> as shown in the example.', 'montezuma' )
		),
		
		'site_url' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php echo site_url(); ?>' => __( 'Displays the content of the field labeled "WordPress Address (URL)" in WP -> Settings -> General, 
				usually without trailing slash. This can but doesn\'t have to be the same as <code>site_url</code>. <code>http://www.mydomain.com</code> 
				or <code>http://www.mydomain.com/wordpress</code>', 'montezuma' ),
			),
			'info' => __( '<code>site_url</code> does not print anything on its own, so use it with <code>echo</code> as shown in the example.', 'montezuma' )
		),
		
		'bloginfo' => array( 
			'type' => 'single',
			'examples' => array(
				"<?php bloginfo('name'); ?>" => "Testpilot",
				"<?php bloginfo('description'); ?>" => __( "Just another WordPress blog", 'montezuma' ),
				"<?php bloginfo('admin_email'); ?>" => "admin@example",
				"<?php bloginfo('atom_url'); ?>" => "http://example/home/feed/atom",
				"<?php bloginfo('rss2_url'); ?>" => "http://example/home/feed",
				"<?php bloginfo('rss_url'); ?>" => "http://example/home/feed/rss",
				"<?php bloginfo('pingback_url'); ?>" => "http://example/home/wp/xmlrpc.php",
				"<?php bloginfo('rdf_url'); ?>" => "http://example/home/feed/rdf",
				"<?php bloginfo('comments_atom_url'); ?>" => "http://example/home/comments/feed/atom",
				"<?php bloginfo('comments_rss2_url'); ?>" => "http://example/home/comments/feed",
				"<?php bloginfo('charset'); ?>" => "UTF-8",
				"<?php bloginfo('html_type'); ?>" => "text/html",
				"<?php bloginfo('language'); ?>" => "en-US",
				"<?php bloginfo('version'); ?>" => "3.1",			
			),
			'info' => __( '<code>bloginfo</code> displays many different bits of global information about the site based on the 
			parameter inside the brackets.', 'montezuma' ),
		),
		
		'wp_nav_menu' => array( 
			'type' => 'array',
			'examples' => array(
				"<?php wp_nav_menu( array( 'theme_location' => 'menu1', 'fallback_cb' => 'bfa_page_menu', 'container' => false ) ); ?>" 
					=> __( 'Prints menu bar with the links as set in WP -> Appearance -> Menus -> Theme Locations -> menu1. 
					If no menu has been configured for "Theme Location: menu1", the menu "bfa_page_menu" will be used as default menu. 
					Available default menus: <code>bfa_page_menu</code> (Page Menu, uses all static pages that exist 
					in this WP installation), <code>bfa_cat_menu</code> 
					(Category menu, uses all categories that exist in this WP installation)', 'montezuma' ),
				"<?php wp_nav_menu( array( 'theme_location' => 'menu2', 'fallback_cb' => 'bfa_cat_menu', 'container' => false ) ); ?>" => 
					__( 'Like above but with "Theme Location: menu2" and "bfa_cat_menu" as the default fallback if no menu was configured for 
					Theme Location menu2', 'montezuma' ),
			),
			'info' => __( 'Displays a navigation menu created in WP -> Appearance -> Menus.', 'montezuma' ),
		),		
			

			
		'wp_dropdown_users' => array( 
			'type' => 'array',
			'examples' => array(
				'<?php wp_dropdown_users(); ?>' => __( 'Displays dropdown HTML content of users', 'montezuma' ),
			),
			'info' => __( 'Use as is or with one/some of the many parameters available, see WP Docs.', 'montezuma' ),
		),		
			
		'wp_list_authors' => array( 
			'type' => 'array',
			'examples' => array(
				'<?php wp_list_authors(); ?>' => __( "Displays a list of the sites's authors (users), and if the user has authored any posts, 
				the author name is displayed as a link to their posts. Optionally this tag displays each author's post count and RSS feed link.", 'montezuma' ),
			),
			'info' => __( 'Use as is or with one/some of the many parameters available, see WP Docs.', 'montezuma' ),
		),		
			
		'wp_list_bookmarks' => array( 
			'type' => 'array',
			'examples' => array(
				'<?php wp_list_bookmarks(); ?> ' => __( 'Displays bookmarks found in WP -> Links', 'montezuma' ),
			),
			'info' => __( 'Use as is or with one/some of the many parameters available, see WP Docs.', 'montezuma' ),
		),		
			
		'wp_dropdown_categories' => array( 
			'type' => 'array',
			'examples' => array(
				'<?php wp_dropdown_categories(); ?> ' => __( 'Displays HTML dropdown list of categories.', 'montezuma' ),
			),
			'info' => __( 'Use as is or with one/some of the many parameters available, see WP Docs.', 'montezuma' ),
		),		
			
		'wp_list_categories' => array( 
			'type' => 'array',
			'examples' => array(
				'<?php wp_list_categories(); ?>' => __( 'Displays a list of Categories as links.', 'montezuma' ),
			),
			'info' => __( 'Use as is or with one/some of the many parameters available, see WP Docs.', 'montezuma' ),
		),		
			
		'wp_tag_cloud' => array( 
			'type' => 'array',
			'examples' => array(
				'<?php wp_tag_cloud(); ?>' => 
				__( "Displays a list of tags in what is called a 'tag cloud', where the size of each tag is 
				determined by how many times that particular tag has been assigned to posts.", 'montezuma' ),
			),
			'info' => __( 'In Montezuma the different sizes of tags in the cloud is removed for a stramlined display. CSS classes are available 
			(see Widgets/Tag Cloud in the CSS section) for applying not just different sizes but any kind of style based on popularity of a tag. 
			Use as is or with one/some of the many parameters available, see WP Docs.', 'montezuma' ),
		),		
			
		'single_term_title' => array( 
			'type' => 'function',
			'examples' => array(
				'<?php single_term_title(); ?>' => __( 'Displays the "term" title for the current page.', 'montezuma' ),
				"<?php single_term_title( 'Currently browsing ' ); ?>" => __( 'Displays "Currently browsing " 
				followed by the "term" title for the current page.', 'montezuma' ),
			),
			'info' => __( 'Displays the title for a taxonomy on taxonomy pages. 
			Can be used instead of single_cat_title() and single_tag_title().', 'montezuma' ),
		),		
		
		// Needs post ID. Get post ID inside bfa_parse_php() ?
		/*
		'the_terms' => array( 
			'type' => 'function',
			'examples' => array(
				'' => '',
			),
			'info' => '',
		),		
		*/	
			
		'wp_list_comments' => array( 
			'type' => 'array',
			'examples' => array(
				'<?php wp_list_comments(); ?>' => __( 'Displays all comments for a post or Page based on a variety 
					of parameters including ones set in the administration area.', 'montezuma' ),
			),
			'info' => __( 'Use as is or with one/some of the many parameters available, see WP Docs.', 'montezuma' ),
		),		
		
		/*
		'esc_url' => array( 
			'type' => 'function',
			'examples' => array(
				'' => '',
			),
			'info' => '',
		),		
			
		'esc_attr_e' => array( 
			'type' => 'function',
			'examples' => array(
				'' => '',
			),
			'info' => '',
		),		
		*/
		
		'bfa_loop' => array( 
			'type' => 'single',
			'examples' => array(
				"<?php bfa_loop(); ?>" => __( 'Displays the list of posts (the WordPress "Loop") on multi post pages. 
					For each post it uses the default post format sub template <code>postformat.php</code> as the base 
					template if no other base post format template is provided inside the brackets.', 'montezuma' ),
				"<?php bfa_loop( 'otherformat' ); ?>" => __( 'Displays the list of posts (the WordPress "Loop") on multi post pages. 
					For each post it uses the sub template <code>otherformat.php</code>. You would have to create 
					that sub template <code>otherformat.php</code> in Montezuma (See "Add sub template").', 'montezuma' ),
			),
			'info' => __( "This function will first look for the specific version of a post format template (e.g. 
					<code>postformat-video.php</code> for a post that was set to Format: 'Video' in the WP posts panel) 
					and then, if that does not exist (because you did not create a sub template named <code>postformat-video.php</code> 
					in Montezuma) 
					fall back to using the base version <code>postformat.php</code>. And if you\'re working 
					with a new set of post format templates (which you created as sub templates in Montezuma, e.g 
					<code>otherformat.php</code>, <code>otherformat-video.php</code>, <code>otherformat-link.php</code> ...) 
					and you use <code><?php bfa_loop( 'otherformat' ); ?></code> then it will look for (again, if a post 
					was set to Format: 'Video' for instance) for <code>otherformat-video.php</code>, and if that does not exist, 
					it will fall back to using <code>otherformat.php</code>.", 'montezuma' )

		),		
		
		/*
		'bfa_loop_single' => array( 
			'type' => 'single',
			'examples' => array(
				'' => '',
			),
			'info' => '',
		),		
			
		'bfa_loop_page' => array( 
			'type' => 'single',
			'examples' => array(
				'' => '',
			),
			'info' => '',
		),		
		*/
		
		'bfa_breadcrumbs' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php bfa_breadcrumbs(); ?>' => __( 'Displays the breadcrumbs navigation.', 'montezuma' ),
				"<?php bfa_breadcrumbs( 'breadcrumbs1' ); ?>" => __( 'Displays the breadcrumbs navigation and adds the CSS ID <code>breadcrumbs1</code>
					to the container element. Useful if you want to display the breadcrumbs more than once on a page, and style them differently.', 'montezuma' ),
			),
			'info' => '',
		),		

		'bfa_paginate_comments' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php bfa_paginate_comments(); ?>' => __( 'Displays a numbered comment navigation.', 'montezuma' ),
				"<?php bfa_paginate_comments( 'comment-pagination-1' ); ?>" => __( 'Displays a numbered comment navigation and 
						adds the CSS ID <code>comment-pagination-1</code> to the container element. 
						Useful if you want to display the comment navigation more than once on a page (for instance: above and below the 
						comment list), and style them differently.', 'montezuma' ),
			),
			'info' => '',
		),		
			
		'bfa_comment_form' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php bfa_comment_form(); ?>' => __( 'Displays the comment form.', 'montezuma' ),
			),
			'info' => '',
		),		
		
		'comment_class' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php comment_class(); ?>' => __( 'Displays various CSS classes for each single comment based on position of comment, whether 
					the comment is from the post author etc... These CSS classes can then be used to style single comments.', 'montezuma' ),
			),
			'info' => __( 'This should be used in <code>comments-comment.php</code> - the sub template for a single comment.', 'montezuma' ),
		),		
			
		'comment_ID' => array( 
			'type' => 'single',	
			'examples' => array(
				'<?php comment_ID(); ?>' => __( 'Displays the numeric ID of the current comment.', 'montezuma' ),
			),
			'info' => __( 'This should be used in <code>comments-comment.php</code> - the sub template for a single comment.', 'montezuma' ),
		),		
			
		'bfa_avatar' => array( 
			'type' => 'single',	
			'examples' => array(
				'<?php bfa_avatar(); ?>' => __( 'Displays the avatar of a comment author.', 'montezuma' ),
			),
			'info' => __( 'This should be used in <code>comments-comment.php</code> - the sub template for a single comment.', 'montezuma' ),
		),		
			
		'comment_author_link' => array( 
			'type' => 'single',	
			'examples' => array(
				'<?php comment_author_link(); ?>' => __( "Displays the comment author's name linked to his/her URL, if one was provided.", 'montezuma' ),
			),
			'info' => __( 'This should be used in <code>comments-comment.php</code> and <code>comments-pingback.php</code> 
				- the sub templates for a single comment and a single pingback/trackback.', 'montezuma' ),
		),		
		
		'bfa_content_nav' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php bfa_content_nav(); ?>' => __( 'Displays a numbered navigation on multi post pages.', 'montezuma' ),
			),
			'info' => __( 'Should be used on multi post pages (index, tag, category, search...) but not on single post pages 
				(single, page, 404, custom templates that are meant to be single post pages...)', 'montezuma' ),
		),

		'bfa_comments_title' => array( 
			'type' => 'single',
			'examples' => array(
				'' => '',
			),
			'info' => '',
		),
					
		'comment_link' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php comment_link(); ?>' => __( 'Display "permalink" URL of a single comment. This is the URL that you or someone else can use to 
					link exactly to a specific comment, instead of linking to just the page the comment is displayed on', 'montezuma' ),
			),
			'info' => __( 'This should be used in <code>comments-comment.php</code> - the sub template for a single comment.
					This is commonly used with the comment date (see <code><?php comment_date(); ?></code>) as the link text. 
					This way the comment date serves a second purpose instead of just displaying the comment date.', 'montezuma' ),
		),
		
		'comment_date' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php comment_date(); ?>' => __( 'Displays the date a comment was posted, using the default date format set in WordPress.', 'montezuma' ),
				"<?php comment_date('n-j-Y'); ?>" => __( 'Displays the date a comment was posted, in the format <code>6-30-2014</code>.', 'montezuma' ),
			),
			'info' => __( 'This should be used in the sub templates <code>comments-comment.php</code> and 
				<code>comments-pingback.php</code>. For more date formatting options see 
				<a target="_blank" href="http://codex.wordpress.org/Formatting_Date_and_Time">WP Date & Time Formats</a>', 'montezuma' ),
		),
		
		'comment_time' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php comment_time(); ?>' => __( 'Displays the time a comment was posted, using the default date format set in WordPress.', 'montezuma' ),
				"<?php comment_time('H:i:s'); ?>" => __( 'Displays the time a comment was posted, in the format <code>22:04:11</code>.', 'montezuma' ),
			),
			'info' => __( 'This should be used in the sub templates <code>comments-comment.php</code> and 
				<code>comments-pingback.php</code>. For more time formatting options see 
				<a target="_blank" href="http://codex.wordpress.org/Formatting_Date_and_Time">WP Date & Time Formats</a>', 'montezuma' ),
		),
		
		'edit_comment_link' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php edit_comment_link(); ?>' => __( 'Displays a link to edit the current comment, if the user is logged in and allowed to edit the comment.', 'montezuma' ),
				"<?php edit_comment_link( __( 'Edit', 'montezuma' ) ); ?>" => __( 'Displays a link to edit the current comment, with "Edit" as the text link.', 'montezuma' ),
			),
			'info' => __( 'This should be used in <code>comments-comment.php</code> - the sub template for a single comment.', 'montezuma' ),
		),
		
		'bfa_comment_delete_link' => array( 
			'type' => 'single',
			'examples' => array(
				"<?php bfa_comment_delete_link( __( 'Delete', 'montezuma' ) ); ?>" => __( 'Displays a link for deleting a comment.', 'montezuma' ),
			),
			'info' => __( 'Will only be displayed if current user is logged in and allowed to delete comments. 
				This should be used in <code>comments-comment.php</code> - the sub template for a single comment.', 'montezuma' ),
		),
		
		'bfa_comment_spam_link' => array( 
			'type' => 'single',
			'examples' => array(
				"<?php bfa_comment_spam_link(); ?>" => __( 'Displays a link for deleting and tagging a comment as spam, without link text. 
					Could be used to style this as a graphical link, with a background image.', 'montezuma' ),
				"<?php bfa_comment_spam_link( __( 'Spam', 'montezuma' ) ); ?>" => __( 'Displays a link to delete and tag a comment as spam, with "Spam" as the link title.', 'montezuma' ),
			),
			'info' => __( 'Will only be displayed if current user is logged in and allowed to delete comments. 
				This should be used in <code>comments-comment.php</code> - the sub template for a single comment.', 'montezuma' ),
		),
		
		'comment_text' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php comment_text(); ?>' => __( 'Displays the text of a comment.', 'montezuma' ),
			),
			'info' => __( 'This should be used in <code>comments-comment.php</code> - the sub template for a single comment.', 'montezuma' ),
		),
		
		'bfa_comment_awaiting' => array( 
			'type' => 'single',
			'examples' => array(
				"<?php bfa_comment_awaiting( __( 'Your comment is awaiting moderation.', 'montezuma' ) ); ?>" 
					=> __( 'Displays "Your comment is awaiting moderation." is comments are being "moderated" (= checked first 
					by site owner before being published, instead of being published immediately. According to setting at 
					WP -> Settings -> Discussion -> Before a comment appears.', 'montezuma' ),
			),
			'info' => __( 'This will only be displayed to the person that submitted a comment, not to everyone.', 'montezuma' ),
		),
	
		'date' => array( 
			'type' => 'single',
			'examples' => array(
				"<?php echo date( 'Y'); ?>" 
					=> __( 'The PHP date() function. Prints the current date and/or time in the specified format. This example prints the year as 4-digit number e.g. 2014. For 
					possible parameters see <a target="_blank" href="http://www.php.net/manual/en/function.date.php">PHP Date (External)</a>.', 'montezuma' ),
			),
			'info' => __( 'This functions does not print by itself. Always use it in combination with <echo>echo</code> as shown in the example.', 'montezuma' ),
		),


		'get_num_queries' => array( 
			'type' => 'single',
			'examples' => array(
				"<?php echo get_num_queries(); ?>" 
					=> __( 'Displays the Database queries consumed to render the page.', 'montezuma' ),
			),
			'info' => __( 'This functions does not print by itself. Always use it in combination with <echo>echo</code> as shown in the example.', 'montezuma' ),
		),
		'timer_stop' => array( 
			'type' => 'single',
			'examples' => array(
				"<?php timer_stop(1); ?>" => __( 'Displays the time needed to render the page.', 'montezuma' ),
			),
			'info' => __( 'Useful to check the impact of settings or plugins etc...', 'montezuma' ),
		),


		'bfa_if_front_else' => array( 
			'type' => 'function',
			'examples' => array(
				'<?php bfa_if_front_else( "h1", "h3" ); ?>' => __( "Print 'h1' if current page is the front page, else print 'h3'", 'montezuma' ),
			),
			'info' => __( 'This could be used to switch the HTML tag used e.g. for the site title from h1 on the front page to h2 or h3 or even a div on all other pages, 
			to put the SEO focus on the title of a post, a page or a category title or whatever the essence of a given page is. You can (and usually will) make the site title 
			look the same, through CSS. It is 
			commony accepted good SEO practise to give the site title an H1 only on the front page and nowhere else. You mileage may vary if your 
			site title is very important and/or full of relevant keywords. You usually use this function twice, once to open a tag, then again to close the tag, like this: 
			<code><<?php bfa_if_front_else( "h1", "h3" ); ?>>Some Title</<?php bfa_if_front_else( "h1", "h3" ); ?>></code>. The first parameter is 
			what will be printed on the front page, the second what will be printed on all other pages.', 'montezuma' ),
		),
		
		
	);
		
	$wl_loop = array(

		'bfa_excerpt' => array( 
			'type' => 'function',
			'examples' => array(
				'<?php bfa_excerpt( num_words, more ); ?>' => "",
				'<?php bfa_excerpt(); ?>' => __( "Default. Same as <?php bfa_excerpt( 55, ' ...' ); ?>", 'montezuma' ),
				"<?php bfa_excerpt( 40, ' ... read more' ); ?>" => __( "Print first 40 words, followed by ' ... read more'", 'montezuma' ),
				"<?php bfa_excerpt( 100, ' ... continue reading <a href=\"%url%\">%title%</a>' ); ?>" => __( "Print first 100 words, followed by by link to full post with post title as link text", 'montezuma' ),
			),
			'info' => __( 'This should be used in post format templates, e.g. <code>postformat.php</code>. 
			<code>%url%</code> will be replaced with the post permalink URL, 
			<code>%title%</code> with the post title.', 'montezuma' ),
		),
		
		'previous_post_link' => array( 
			'type' => 'function',
			'examples' => array(
				'<?php previous_post_link(); ?>' => __( "Default. Same as <?php previous_post_link( '&laquo; %link', '%title', FALSE ); ?>", 'montezuma' ),
				"<?php previous_post_link( '&laquo; %link', '%title', TRUE ); ?>" => __( 'Linked post must be in same category', 'montezuma' ),
				"<?php previous_post_link( '&laquo; %link', '%title', TRUE, '1 and 5 and 15' ); ?>" => __( 'Exclude categories with the IDs 1, 5 and 15. Must have the word " and " between the cat IDs', 'montezuma' ),
				"<?php previous_post_link( '&laquo; %link', __('Previous Post', 'montezuma') ); ?>" => __( 'Link text is "Previous Post" instead of actual post title', 'montezuma' ),		
				"<?php previous_post_link( __('Previous post is here: %link <- previous post', 'montezuma'), '%title', TRUE ); ?>" => __( 'Some text before and after the link', 'montezuma' ),				
			),
			'info' => __( 'The default has reasonable settings. Simply use <?php previous_post_link(); ?> if you\'re unsure.', 'montezuma' ),
		),
		
		'next_post_link' => array( 
			'type' => 'function',
			'examples' => array(
				'<?php next_post_link(); ?>' => __( "Default. Same as <?php next_post_link( '&laquo; %link', '%title', FALSE ); ?>", 'montezuma' ),
			),
			'info' => __( 'For more examples see <code>previous_post_link</code>.', 'montezuma' ),
		),
		
		'post_class' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php post_class(); ?>' => __( 'Displays various CSS classes related to the current post.', 'montezuma' ),
			),
			'info' => __( 'Should be used in templates where single posts or static pages are displayed: 
					In post format templates such as <code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... 
					Also in <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),		
			
		'the_ID' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php the_ID(); ?>' => __( 'Displays the ID of the current post. Commonly used to print a CSS ID of a post, so that 
					each post can be styled individually.', 'montezuma' ),
			),
			'info' => __( 'Should be used in templates where single posts or static pages are displayed: 
					In post format templates such as <code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... 
					Also in <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),		
			
		'the_title' => array( 
			'type' => 'function',
			'examples' => array(
				'<?php the_title(); ?>' => __( 'Displays the title of the current post. 
					If the post is protected or private, this will be noted by the words "Protected: " or "Private: " prepended to the title.', 'montezuma' ),
			),
			'info' => __( 'Should be used in templates where single posts or static pages are displayed: 
					In post format templates such as <code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... 
					Also in <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),		
			
		'the_title_attribute' => array( 
			'type' => 'queryarray',
			'examples' => array(
				'<?php the_title_attribute(); ?>' => __( "Displays the title of the current post. It somewhat duplicates the 
					functionality of <code>the_title()</code>, but provides a 'clean' version of the title for use in HTML attributes 
					by stripping HTML tags and converting certain characters (including quotes) to their character entity equivalent", 'montezuma' ),
			),
			'info' => __( 'Should be used in templates where single posts or static pages are displayed: 
					In post format templates such as <code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... 
					Also in <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),		
			
		'the_permalink' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php the_permalink(); ?>' => __( 'Displays the permalink URL for the current post.', 'montezuma' ),
			),
			'info' => __( 'Should be used in templates where single posts or static pages are displayed: 
					In post format templates such as <code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... 
					Also in <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),		
			
		'the_time' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php the_time(); ?>' => __( 'Displays the time and/or date the current post was published at, using the default date/time format set in WordPress.', 'montezuma' ),
				"<?php the_time('g:i a'); ?>" => __( 'Displays post time as 10:36 pm', 'montezuma' ),
				"<?php the_time('G:i'); ?>" => __( 'Displays post time as 17:24', 'montezuma' ),				
			),
			'info' => '',
		),		

		'the_date' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php the_date(); ?>' => __( 'Displays the post date in the default WP format.', 'montezuma' ),		
			),
			'info' => '',
		),		
		
		'the_author' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php the_author(); ?>' => __( "Displays the value in the post author's 'Display name publicly as' field.", 'montezuma' ),
			),
			'info' => __( 'Should be used in templates where single posts or static pages are displayed: 
					In post format templates such as <code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... 
					Also in <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),		
			
		'the_author_link' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php the_author_link(); ?>' => __( "Displays a link to the Website for the author of a post. The Website field is set in 
				the user's profile (WP -> Users -> Your Profile). The text for the link is the author's Profile 'Display name publicly as' field.", 'montezuma' ),
			),
			'info' => __( 'Should be used in templates where single posts or static pages are displayed: 
					In post format templates such as <code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... 
					Also in <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),		
			
		'the_author_meta' => array( 
			'type' => 'function',
			'examples' => array(
				"<?php the_author_meta( 'user_login' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'user_pass' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'user_nicename' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'user_email' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'user_url' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'user_registered' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'user_activation_key' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'user_status' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'display_name' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'nickname' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'first_name' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'last_name' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'description' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'jabber' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'aim' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'yim' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'user_level' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'user_firstname' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'user_lastname' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'user_description' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'rich_editing' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'comment_shortcuts' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'admin_color' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'plugins_per_page' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'plugins_last_view' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
				"<?php the_author_meta( 'ID' ); ?>" => __( 'See parameter name inside brackets', 'montezuma' ),
			),
			'info' => __( 'Displays meta info about the post author, based on the parameter inside the brackets. 
					Should be used in templates where single posts or static pages are displayed: 
					In post format templates such as <code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... 
					Also in <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),		
			
		'the_author_posts' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php the_author_posts(); ?>' => __( "Displays the total number of posts an author has published. Drafts and private posts aren't counted.", 'montezuma' ),
			),
			'info' => __( 'Should be used in templates where single posts or static pages are displayed: 
					In post format templates such as <code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... 
					Also in <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),		
			
		'the_author_posts_link' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php the_author_posts_link(); ?>' => __( "Displays a link to all posts by an author. The link text is the user's 'Display name publicly as' field.", 'montezuma' ),
			),
			'info' => __( 'Should be used in templates where single posts or static pages are displayed: 
					In post format templates such as <code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... 
					Also in <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),		
			
			
		'the_excerpt' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php the_excerpt(); ?>' => __( 'Displays an excerpt of the current post.', 'montezuma' ),
			),
			'info' => __( 'Use in post format templates such as <code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... ', 'montezuma' ),
		),		
			
		'the_content' => array( 
			'type' => 'function',
			'examples' => array(
				"<?php the_content(); ?>" => __( 'Displays the contents of the current post. This default version is sufficient on single.php and page.php because there no 
					"Read more" link is displayed anyway.', 'montezuma' ),
				"<?php the_content('Read more...'); ?>" => __( 'Displays the contents of the current post.', 'montezuma' ),
			),
			'info' => __( 'Should be used in <code>single.php</code> and <code>page.php</code>. Can also be used 
					instead of <code>the_excerpt()</code> in post format templates: <code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc...', 'montezuma' ),
		),		
			
		'wp_link_pages' => array( 
			'type' => 'array',
			//'type' => 'queryarray',
			'examples' => array(
				"<?php wp_link_pages( array( 'before' => __('<p class=\"post-pagination\">Pages:', 'montezuma'), 'after' => '</div>' ) ); ?>" => __( 'Displays page-links for paginated posts.', 'montezuma' ),
			),
			'info' => __( 'This will only display something in posts that include the <code>&lt;!--nextpage--&gt;</code> Quicktag one or more times.', 'montezuma' ),
		),		

		'bfa_link_pages' => array( 
			'type' => 'array',
			//'type' => 'queryarray',
			'examples' => array(
				"<?php bfa_link_pages( array( 'before' => __('<p class=\"post-pagination\">Pages:', 'montezuma'), 'after' => '</div>' ) ); ?>" => __( 'Displays page-links for paginated posts.', 'montezuma' ),
			),
			'info' => __( 'This will only display something in posts that include the <code>&lt;!--nextpage--&gt;</code> Quicktag one or more times. 
			This is a advanced version of <code>wp_link_pages()</code>. Unlike the WP version, this function wraps the current page number into a span to be able to style that, too.', 'montezuma' ),
		),	
		
		'edit_post_link' => array( 
			'type' => 'function',
			'examples' => array(
				"<?php edit_post_link( __('Edit', 'montezuma'), '<div class=\"post-edit\">', '</div>' ); ?>" => __( 'Displays a link to edit the current post.', 'montezuma' ),
			),
			'info' => __( 'This will only display something if a user is logged in and allowed to edit the post.', 'montezuma' ),
		),		
			
		'the_category' => array( 
			'type' => 'function',
			'examples' => array(
				"<?php the_category(' &middot; '); ?>" => __( 'Displays a link to the category or categories a post belongs to, with " &middot; " as the separator between multiple category names.', 'montezuma' ),
			),
			'info' => __( 'Could be used in post format templates 
					(<code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... ) 
					and in <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),		
			
		'the_tags' => array( 
			'type' => 'function',
			'examples' => array(
				"<?php the_tags( '<p class=\"post-tags\">', ' &middot; ', '</p>' ); ?>" 
					=> __( 'This template tag displays a link to the tag or tags a post belongs to, with " &middot; " as the separator between multiple tag names.', 'montezuma' ),
			),
			'info' => __( 'If no tags are associated with the current entry, nothing is displayed. Could be used in post format templates 
					(<code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... ) 
					and in <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),		
			
		'the_taxonomies' => array( 
			'type' => 'function',
			'examples' => array(
				'<?php the_taxonomies(); ?>' => __( 'Displays the taxonomies for a post.', 'montezuma' ),
			),
			'info' => '',
		),		
		
		'comments_template' => array( 
			'type' => 'function',
			'examples' => array(
				'<?php comments_template(); ?>' => __( 'Displays the comment template.', 'montezuma' ),
			),
			'info' => __( 'For use in <code>single.php</code>, <code>page.php</code> and other "singular" templates.', 'montezuma' ),
		),		
					
		'bfa_thumb' => array( 
			'type' => 'function',
			'examples' => array(
				'<?php bfa_thumb( $width, $height, $crop = false, $before = \'\', $after = \'\', $link = \'permalink\' ); ?>' => 
				__( 'This shows the available parameters and their default values if any.', 'montezuma' ),
				'<?php bfa_thumb( 620, 180, true); ?>' => 
				__( 'Displays a post thumbnail with width 620px, height 180px, cropped, nothing before, 
				nothing after, linked to post.', 'montezuma' ),
			),
			'info' => __( 'Should be used in post format templates (<code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc...).
				If a "Featured Image" was set for the post, that one will 
				be used. Else, the first attached/inserted local image in the post will be used. 
				Else the first local image URL will be used. External images will not be used. Will create thumbnail on the fly if it does 
				not exist. You can use different values for width, height and crop (true/false) in each post format template.	
				Possible values for <code>$link</code>: \'permalink\' (links to post) or empty (not linked). TODO: Add \'fullsize\' to link to full size version of image.', 'montezuma' ),
		),		
			
		'bfa_comments_popup_link' => array( 
			'type' => 'function',
			'examples' => array(
				'<?php bfa_comments_popup_link(); ?>' => __( 'Displays link to comments of a post IF there are comments. Can show the comment number.', 'montezuma' ),
			),
			'info' => __( 'Can be used in post format templates (<code>postformat.php</code>, <code>postformat-video.php</code>, 
					<code>postformat-link.php</code>, <code>my-other-format.php</code>,  <code>my-other-format-video.php</code> etc... ). 
					Should not be used in singular templates like <code>single.php</code> and <code>page.php</code>.', 'montezuma' ),
		),	
		
		'bfa_comments_number' => array( 
			'type' => 'function',
			'examples' => array(
				'<?php bfa_comments_number(); ?>' => __( 'Displays number of comments IF there are any.', 'montezuma' ),
			),
			'info' => '',
		),	


		'comment_reply_link' => array( 
			'type' => 'array',
			'examples' => array(
				"<?php comment_reply_link( array( 
				'reply_text' => __( 'Reply', 'montezuma' ), 
				'login_text' => __( 'Log in to Reply', 'montezuma' ),
				'depth' => 1,
				'max_depth' => 3) ); ?>" => __( 'Displays direct reply link for individual comment.', 'montezuma' )
			),
			'info' => __( 'Should be used in sub templates <code>comments-comment.php</code>', 'montezuma' ),
		),
		

		'bfa_attachment_url' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php bfa_attachment_url(); ?>' => __( 'Prints the URL of an attachment.', 'montezuma' ),
			),
			'info' => __( 'Use on attachment templates such as image.php', 'montezuma' ),
		),	

		'bfa_attachment_image' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php bfa_attachment_image( $size ); ?>' => __( 'Displays number of comments IF there are any.', 'montezuma' ),
			),
			'info' => __( 'Use on attachment templates such as image.php. Replace $size with <code>thumbnail</code>, <code>medium</code>, <code>large</code> or <code>full</code>.', 'montezuma' ),
		),	

		'bfa_parent_permalink' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php bfa_parent_permalink(); ?>' => __( 'Prints the permalink of the post\'s PARENT.', 'montezuma' ),
			),
			'info' => __( 'Use on attachment templates such as image.php', 'montezuma' ),
		),	

		'bfa_parent_title' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php bfa_parent_title(); ?>' => __( 'Prints the title of the post\'s PARENT.', 'montezuma' ),
			),
			'info' => __( 'Use on attachment templates such as image.php', 'montezuma' ),
		),	
		
		'bfa_attachment_caption' => array( 
			'type' => 'single',
			'examples' => array(
				'<?php bfa_attachment_caption( $before, $after ); ?>' => __( 'Prints caption of an attachment, if it exists, with some HTML before and after.', 'montezuma' ),
			),
			'info' => __( 'Use on attachment templates such as image.php', 'montezuma' ),
		),	
		
		'bfa_image_meta' => array( 
			'type' => 'array',
			'examples' => array(
				"<?php bfa_image_meta( array( 
					'keys' => '',
					'before' => '<ul>', 
					'after' => '</ul>',
					'item_before' => '<li>', 
					'item_after' => '</li>',
					'item_sep' => '',
					'key_before' => '',
					'key_after' => ': ',
					'value_before' => '',
					'value_after' => '',
					'display_empty' => false
				) ); ?>"
					=> __( 'Displays all image meta data, alphabetically sorted, with the specified HTML tags.', 'montezuma' ),
				"<?php bfa_image_meta(); ?>" 
					=> __( 'Same as above. Uses the default settings.', 'montezuma' ),
				"<?php bfa_image_meta( array( 
					'keys' => 'camera, aperture, focal_length, shutter_speed',
					'before' => '<p class=\"my-image-meta\">', 
					'after' => '</p>',
					'item_before' => '', 
					'item_after' => '',
					'item_sep' => ', ',
					'key_after' => '= ',
				) ); ?>"
					=> __( 'Displays only the image meta data specified in parameter "keys" (and in that order), wrapped in a paragraph tag with class 
					"my-image-meta", with a comma between the data items, and a "= " after each key.', 'montezuma' ),
			),
			'info' => __( 'Useful on image.php template for displaying details of the given image. The default full set of "keys" is: 
			width, height, aperture, credit, camera, caption, created_timestamp, copyright, focal_length, iso, shutter_speed, title.', 'montezuma' ),
		),	
		
		
	);
		


	$whitelist = array_merge( $wl_global, $wl_loop );
	
	ksort( $whitelist );
	
	return $whitelist;
}

