<?php
/**
 * Suits functions and definitions.
 *
 * @package Suits
 * @since Suits 1.0
 */

/**
 * Sets up the content width value based on the theme's design.
 * @see suits_content_width() for template-specific adjustments.
 */
if ( ! isset( $content_width ) )
	$content_width = 620;

/**
 * Adds support for a custom header image.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Suits supports.
 *
 * @uses add_editor_style() To add Visual Editor stylesheets.
 * @uses add_theme_support() To add support for automatic feed links
 * and post thumbnails.
 * @uses register_nav_menu() To add support for a navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Suits 1.0
 *
 * @return void
 */
function suits_setup() {
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( suits_fonts_url() ) );

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Switches default core markup for search form, comment form, and comments
	// to output valid HTML5.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Navigation Menu', 'suits' ) );

	/*
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff',
	) );

	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 620, 9999 );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
add_action( 'after_setup_theme', 'suits_setup' );

/**
 * Returns the Google font stylesheet URL, if available.
 *
 * @since Suits 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function suits_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Lato, translate this to 'off'. Do not translate into your
	 * own language.
	 */
	$lato = _x( 'on', 'Lato font: on or off', 'suits' );

	if ( 'off' !== $lato ) {
		$font_families = array();

		if ( 'off' !== $lato )
			$font_families[] = 'Lato:300,400';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Enqueues scripts and styles for front end.
 *
 * @since Suits 1.0
 *
 * @return void
 */
function suits_scripts_styles() {
	// Adds JavaScript to pages with the comment form to support sites with
	// threaded comments (when in use).
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Loads JavaScript file with functionality specific to Suits.
	wp_enqueue_script( 'suits-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '2013-10-20', true );

	// Add Lato font, used in the main stylesheet.
	wp_enqueue_style( 'suits-fonts', suits_fonts_url(), array(), null );

	// Loads our main stylesheet.
	wp_enqueue_style( 'suits-style', get_stylesheet_uri(), array(), '2013-10-20' );
}
add_action( 'wp_enqueue_scripts', 'suits_scripts_styles' );

/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Suits 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function suits_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'suits' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'suits_wp_title', 10, 2 );

/**
 * Registers widget areas.
 *
 * @since Suits 1.0
 *
 * @return void
 */
function suits_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'suits' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Appears on posts and pages in the sidebar.', 'suits' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer One', 'suits' ),
		'id'            => 'footer-1',
		'description'   => __( 'Appears in the footer section of the site.', 'suits' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Two', 'suits' ),
		'id'            => 'footer-2',
		'description'   => __( 'Appears in the footer section of the site.', 'suits' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Three', 'suits' ),
		'id'            => 'footer-3',
		'description'   => __( 'Appears in the footer section of the site.', 'suits' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'suits_widgets_init' );

if ( ! function_exists( 'suits_paging_nav' ) ) :
/**
 * Displays navigation to next/previous set of posts when applicable.
 *
 * @since Suits 1.0
 *
 * @return void
 */
function suits_paging_nav() {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older', 'suits' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer <span class="meta-nav">&rarr;</span>', 'suits' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'suits_post_nav' ) ) :
/**
 * Displays navigation to next/previous post when applicable.
*
* @since Suits 1.0
*
* @return void
*/
function suits_post_nav() {
	global $post;

	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous )
		return;
	?>
	<nav class="navigation post-navigation" role="navigation">
		<div class="nav-links">

			<?php previous_post_link( '%link', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'suits' ) ); ?>
			<?php next_post_link( '%link', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link', 'suits' ) ); ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'suits_entry_meta' ) ) :
/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own suits_entry_meta() to override in a child theme.
 *
 * @since Suits 1.0
 */
function suits_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'suits' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'suits' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'suits' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'Posted in %1$s and tagged %2$s<span class="on-date"> on %3$s</span><span class="by-author"> by %4$s</span>.', 'suits' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'Posted in %1$s<span class="on-date"> on %3$s</span><span class="by-author"> by %4$s</span>.', 'suits' );
	} else {
		$utility_text = __( '<span class="on-date">Posted on %3$s</span><span class="by-author"> by %4$s</span>.', 'suits' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

if ( ! function_exists( 'suits_entry_date' ) ) :
/**
 * Prints HTML with date information for current post.
 *
 * Create your own suits_entry_date() to override in a child theme.
 *
 * @since Suits 1.0
 *
 * @param boolean $echo Whether to echo the date. Default true.
 * @return string The HTML-formatted post date.
 */
function suits_entry_date( $echo = true ) {
	$date = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( sprintf( __( 'Permalink to %s', 'suits' ), the_title_attribute( 'echo=0' ) ) ),
		esc_attr( get_the_date( 'c' ) ),
		esc_attr( get_the_date() )
	);

	if ( $echo )
		echo $date;

	return $date;
}
endif;

if ( ! function_exists( 'suits_the_attached_image' ) ) :
/**
 * Prints the attached image with a link to the next attached image.
 *
 * @since Suits 1.0
 *
 * @return void
 */
function suits_the_attached_image() {
	$post                = get_post();
	$attachment_size     = apply_filters( 'suits_attachment_size', array( 940, 940 ) );
	$next_attachment_url = wp_get_attachment_url();

	/**
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

/**
 * Extends the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. When avatars are disabled in discussion settings.
 * 3. Using a full-width layout.
 * 4. Active widgets in the sidebar to change the layout and spacing.
 *
 * @since Suits 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function suits_body_class( $classes ) {
	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	if ( ! get_option( 'show_avatars' ) )
		$classes[] = 'no-avatars';

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'template-full-width.php' ) )
		$classes[] = 'full-width';

	$num_sidebars = 0;

	if ( is_active_sidebar( 'footer-1' ) )
		$num_sidebars++;

	if ( is_active_sidebar( 'footer-2' ) )
		$num_sidebars++;

	if ( is_active_sidebar( 'footer-3' ) )
		$num_sidebars++;

	switch( $num_sidebars ) :

		case 1:
			$classes[] = 'one-footer-sidebar';
			break;

		case 2:
			$classes[] = 'two-footer-sidebars';
			break;

		case 3:
			$classes[] = 'three-footer-sidebars';
			break;

		default:
			$classes[] = 'no-footer-sidebar';

	endswitch;

	return $classes;
}
add_filter( 'body_class', 'suits_body_class' );

/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Suits 1.0
 *
 * @return void
 */
function suits_content_width() {
	if ( is_page_template( 'template-full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 940;
	}
}
add_action( 'template_redirect', 'suits_content_width' );

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 *
 * @since Suits 1.0
 *
 * @return void
 */
function suits_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'content',
		'footer_widgets' => array( 'footer-1', 'footer-2', 'footer-3' ),
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'suits_jetpack_setup' );

/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Suits 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 * @return void
 */
function suits_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'suits_customize_register' );

/**
 * Binds JavaScript handlers to make Customizer preview reload changes
 * asynchronously.
 *
 * @since Suits 1.0
 */
function suits_customize_preview_js() {
	wp_enqueue_script( 'suits-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20131020', true );
}
add_action( 'customize_preview_init', 'suits_customize_preview_js' );
