<?php
/**
 * The functions file is utilized to initialize every little thing in the theme.  It controls how the theme is loaded and 
 * sets up the supported features, default actions, and default filters.  If making customizations, users 
 * should must make a child theme and make modifications to its functions.php file (not this one).
 *
 * Child themes should do their setup on the 'after_setup_theme' hook with a priority of 11 if they want to
 * override parent theme features.  Use a priority of 9 or lower if wanting to run before the parent theme.
 *
 * @package Omega
 * @author ThemeHall <hello@themehall.com>
 * @copyright Copyright (c) 2013, themehall.com
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link http://themehall.com/omega
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Load the core theme framework. */
require ( trailingslashit( get_template_directory() ) . 'library/framework.php' );
new Omega();

/* Load omega functions */
require get_template_directory() . '/inc/omega.php';

if ( ! function_exists( 'omega_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function omega_theme_setup() {

	/* The best thumbnail/image script ever. */
	add_theme_support( 'get-the-image' );
	
	/* Load scripts. */
	add_theme_support( 
		'omega-scripts', 
		array( 'comment-reply' ) 
	);

	/* Load shortcodes. */
	add_theme_support( 'omega-shortcodes' );
	
	add_theme_support( 'omega-theme-settings', array( 'about' ) );

	/* Enable custom template hierarchy. */
	//add_theme_support( 'omega-template-hierarchy' );
	

	/* Enable theme layouts (need to add stylesheet support). */
	add_theme_support( 
		'theme-layouts', 
		array(
			'1c'        => __( 'Content',           'omega' ),
			'2c-l'      => __( 'Content / Sidebar', 'omega' ),
			'2c-r'      => __( 'Sidebar / Content', 'omega' )
		),
		array( 'default' => is_rtl() ? '2c-r' :'2c-l', 'customizer' => true ) 
	);
	
	/* Add default theme settings */
	add_filter( "omega_default_theme_settings", 'omega_default_theme_settings');
	
	/* implement editor styling, so as to make the editor content match the resulting post output in the theme. */
	add_editor_style();

	/* Enable responsive support */
	add_theme_support( 'omega-deprecated' );

	/* Support pagination instead of prev/next links. */
	add_theme_support( 'loop-pagination' );	

	/* Better captions for themes to style. */
	add_theme_support( 'cleaner-caption' );

	/* Add default posts and comments RSS feed links to <head>.  */
	add_theme_support( 'automatic-feed-links' );

	/* Enable wraps */
	add_theme_support( 'omega-wraps' );

	/* Enable custom css */
	add_theme_support( 'omega-custom-css' );
	
	/* Enable custom logo */
	add_theme_support( 'omega-custom-logo' );

	/* Enable child themes page */
	add_theme_support( 'omega-child-themes-page' );

	/* Enable responsive support */
	add_theme_support( 'omega-responsive' );


	/* Handle content width for embeds and images. */
	omega_set_content_width( 640 );

	add_action( 'wp_enqueue_scripts', 'omega_scripts' );
	add_action( 'wp_head', 'omega_styles' );
	add_action( 'wp_head', 'omega_header_scripts' );
	add_action( 'wp_footer', 'omega_footer_scripts' );

	/* Header actions. */
	add_action( "omega_header", 'omega_header_markup_open', 5 );
	add_action( "omega_header", 'omega_branding' );
	add_action( "omega_header", 'omega_header_markup_close', 15 );

	/* footer insert to the footer. */
	add_action( "omega_footer", 'omega_footer_markup_open', 5 );
	add_action( "omega_footer", 'omega_footer_insert' );
	add_action( "omega_footer", 'omega_footer_markup_close', 15 );

	/* Load the primary menu. */
	add_action( "omega_before_header", 'omega_get_primary_menu' );

	/* Add the title, byline, and entry meta before and after the entry.*/
	add_action( "omega_before_entry", 'omega_entry_header' );
	add_action( "omega_entry", 'omega_entry' );
	add_action( "omega_singular_entry", 'omega_singular_entry' );
	add_action( "omega_after_entry", 'omega_entry_footer' );
	add_action( "omega_singular-page_after_entry", 'omega_page_entry_meta' );

	/* Add the primary sidebars after the main content. */
	add_action( "omega_after_main", 'omega_primary_sidebar' );

	/* Filter the sidebar widgets. */
	add_filter( 'sidebars_widgets', 'omega_disable_sidebars' );
	add_action( 'template_redirect', 'omega_one_column' );

	/* Allow developers to filter the default sidebar arguments. */
	add_filter( "omega_sidebar_defaults", 'omega_sidebar_defaults' );

	add_filter( 'omega_footer_insert', 'omega_default_footer_insert' );

	// add disqus compatibility
	if (function_exists('dsq_comments_template')) {
		remove_filter( 'comments_template', 'dsq_comments_template' );
		add_filter( 'comments_template', 'dsq_comments_template', 12 ); // You can use any priority higher than '10'	
	}

}
endif; // omega_theme_setup

add_action( 'after_setup_theme', 'omega_theme_setup' );


function omega_sidebar_defaults($defaults) {
	/* Set up some default sidebar arguments. */
	$defaults = array(
		'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);

	return $defaults;
}

/**
 * Adds custom default theme settings.
 *
 * @since 0.3.0
 * @access public
 * @param array $settings The default theme settings.
 * @return array $settings
 */

function omega_default_theme_settings( $settings ) {

	$settings = array(
		'comments_pages'            => 0,
		'comments_posts'            => 1,
		'trackbacks_pages'          => 0,
		'trackbacks_posts'          => 1,
		'content_archive'           => 'full',
		'content_archive_limit'		=> 0,
		'content_archive_thumbnail' => 0,
		'content_archive_more'      => '[Read more...]',
		'more_link_scroll'			=> 0,
		'image_size'                => 'thumbnail',
		'posts_nav'                 => 'numeric',
		'single_nav'                 => 0,
		'header_scripts'            => '',
		'footer_scripts'            => '',
	);

	return $settings;

}


function omega_header_markup_open() {
	echo '<header class="site-header" role="banner" itemscope="itemscope" itemtype="http://schema.org/WPHeader">';
}


function omega_header_markup_close() {
	echo '</header><!-- .site-header -->';
}

function omega_footer_markup_open() {
	echo '<footer class="site-footer" role="contentinfo" itemscope="itemscope" itemtype="http://schema.org/WPFooter">';
}


function omega_footer_markup_close() {
	echo '</footer><!-- .site-footer -->';
}

/**
 * Dynamic element to wrap the site title and site description. 
 */
function omega_branding() {

	echo '<div class="' . omega_apply_atomic( 'title_area_class', 'title-area') .'">';

	/* Get the site title.  If it's not empty, wrap it with the appropriate HTML. */	
	if ( $title = get_bloginfo( 'name' ) ) {		
		if ( $logo = get_theme_mod( 'custom_logo' ) )
			$title = sprintf( '<h1 class="site-title"><a href="%1$s" title="%2$s" rel="home"><span><img src="%3$s"/></span></a></h1>', home_url(), esc_attr( $title ), $logo );		
		else
			$title = sprintf( '<h1 class="site-title"><a href="%1$s" title="%2$s" rel="home"><span>%3$s</span></a></h1>', home_url(), esc_attr( $title ), $title );		
	}

	/* Display the site title and apply filters for developers to overwrite. */
	echo omega_apply_atomic( 'site_title', $title );

	/* Get the site description.  If it's not empty, wrap it with the appropriate HTML. */
	if ( $desc = get_bloginfo( 'description' ) )
		$desc = sprintf( '<h2 class="site-description"><span>%1$s</span></h2>', $desc );

	/* Display the site description and apply filters for developers to overwrite. */
	echo omega_apply_atomic( 'site_description', $desc );

	echo '</div>';
}

/**
 * default footer insert filter
 */
function omega_default_footer_insert( $settings ) {

	/* If there is a child theme active, use [child-link] shortcode to the $footer_insert. */
	return '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link].', 'omega' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Theme by [author-uri].', 'omega' ) . '</p>';	

}

/**
 * Loads footer content
 */
function omega_footer_insert() {
	
	echo '<div class="footer-content footer-insert">';
	
	if ( $footer_insert = get_theme_mod( 'custom_footer' ) ) {
		echo omega_apply_atomic_shortcode( 'footer_content', $footer_insert );		
	} else {
		echo omega_apply_atomic_shortcode( 'footer_content', apply_filters( 'omega_footer_insert','') );
	}
	
	echo '</div>';
}

/**
 * Loads the menu-primary.php template.
 */
function omega_get_primary_menu() {
	get_template_part( 'partials/menu', 'primary' );
}


/**
 * Display the default page edit link
 */
function omega_page_entry_meta() {

	echo omega_apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">[post_edit]</div>' );
}

/**
 * Display primary sidebar
 */
function omega_primary_sidebar() {
	get_sidebar();
}


/**
 * Display the default entry header.
 */
function omega_entry_header() {

	echo '<header class="entry-header">';

	if ( is_home() || is_archive() || is_search() ) {
	?>
		<h1 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	<?php		
	} else {
	?>
		<h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
	<?php
	}
	if ( 'post' == get_post_type() ) : 
		get_template_part( 'partials/entry', 'byline' ); 
	endif; 
	echo '</header><!-- .entry-header -->';
	
}

/**
 * Display the default entry metadata.
 */
function omega_entry() {

	if ( is_home() || is_archive() || is_search() ) {
		if(omega_get_setting( 'content_archive_thumbnail' )) {
			get_the_image( array( 'meta_key' => 'Thumbnail', 'default_size' => omega_get_setting( 'image_size' ) ) ); 
		}
	

		if ( 'excerpts' === omega_get_setting( 'content_archive' ) ) {
			if ( omega_get_setting( 'content_archive_limit' ) )
				the_content_limit( (int) omega_get_setting( 'content_archive_limit' ), omega_get_setting( 'content_archive_more' ) );
			else
				the_excerpt();
		}
		else {
			the_content( omega_get_setting( 'content_archive_more' ) );
		}
	} 

}


function omega_excerpt_more( $more ) {
	return ' ... <a class="more-link" href="'. get_permalink( get_the_ID() ) . '">' . omega_get_setting( 'content_archive_more' ) . '</a>';
}
add_filter('excerpt_more', 'omega_excerpt_more');


/**
 * Display the default singular entry metadata.
 */
function omega_singular_entry() {

	the_content();

	wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'omega' ) . '</span>', 'after' => '</p>' ) );

}


/**
 * Display the default entry footer.
 */
function omega_entry_footer() {

	if ( 'post' == get_post_type() ) {
		get_template_part( 'partials/entry', 'footer' ); 
	}
	
}

/**
 * Enqueue scripts and styles
 */
function omega_scripts() {
	wp_enqueue_style( 'omega-style', get_stylesheet_uri() );
}

/**
 * Insert conditional script / style for the theme used sitewide.
 */
function omega_styles() {
?>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->
<?php 
}


/**
 * Echo header scripts in to wp_head().
 */
function omega_header_scripts() {

	echo omega_get_setting( 'header_scripts' );

}

/**
 * Echo the footer scripts, defined in Theme Settings.
 */
function omega_footer_scripts() {

	echo omega_get_setting( 'footer_scripts' );

}

/**
 * Function for deciding which pages should have a one-column layout.
 */
function omega_one_column() {

	if ( !is_active_sidebar( 'primary' ) )
		add_filter( 'theme_mod_theme_layout', 'omega_theme_layout_one_column' );

	elseif ( is_attachment() && wp_attachment_is_image() && 'default' == get_post_layout( get_queried_object_id() ) )
		add_filter( 'theme_mod_theme_layout', 'omega_theme_layout_one_column' );

}


/**
 * Filters 'get_theme_layout' by returning 'layout-1c'.
 */
function omega_theme_layout_one_column( $layout ) {
	return '1c';
}


/**
 * Disables sidebars if viewing a one-column page.
 */

function omega_disable_sidebars( $sidebars_widgets ) {
	global $wp_customize;

	$customize = ( is_object( $wp_customize ) && $wp_customize->is_preview() ) ? true : false;

	if ( !is_admin() && !$customize && '1c' == get_theme_mod( 'theme_layout' ) )
		$sidebars_widgets['primary'] = false;

	return $sidebars_widgets;
}