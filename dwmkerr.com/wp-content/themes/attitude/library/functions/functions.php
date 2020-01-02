<?php
/**
 * Attitude functions and definitions
 *
 * This file contains all the functions and it's defination that particularly can't be
 * in other files.
 * 
 * @package Theme Horse
 * @subpackage Attitude
 * @since Attitude 1.0
 */

/****************************************************************************************/

add_action( 'wp_enqueue_scripts', 'attitude_scripts_styles_method' );
/**
 * Register jquery scripts
 */
function attitude_scripts_styles_method() {

	global $attitude_theme_options_settings;
   $options = $attitude_theme_options_settings;

   /**
	 * Loads our main stylesheet.
	 */
	wp_enqueue_style( 'attitude_style', get_stylesheet_uri() );

	/**
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/**
	 * Register JQuery cycle js file for slider.
	 * Register Jquery fancybox js and css file for fancybox effect.
	 */
	wp_register_script( 'jquery_cycle', ATTITUDE_JS_URL . '/jquery.cycle.all.min.js', array( 'jquery' ), '2.9999.5', true );

   wp_register_style( 'google_fonts', 'http://fonts.googleapis.com/css?family=PT+Sans|Philosopher' );    
	
	/**
	 * Enqueue Slider setup js file.
	 * Enqueue Fancy Box setup js and css file.	 
	 */	
	if( ( is_home() || is_front_page() ) && "0" == $options[ 'disable_slider' ] ) {
		wp_enqueue_script( 'attitude_slider', ATTITUDE_JS_URL . '/attitude-slider-setting.js', array( 'jquery_cycle' ), false, true );
	}
   wp_enqueue_script( 'tinynav', ATTITUDE_JS_URL . '/tinynav.js', array( 'jquery' ) );
   wp_enqueue_script( 'backtotop', ATTITUDE_JS_URL. '/backtotop.js', array( 'jquery' ) );

   wp_enqueue_style( 'google_fonts' );

   /**
    * Browser specific queuing i.e
    */
	$attitude_user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(preg_match('/(?i)msie [1-8]/',$attitude_user_agent)) {
		wp_enqueue_script( 'html5', ATTITUDE_JS_URL . '/html5.js', true ); 
	}

} 

/****************************************************************************************/

add_filter( 'wp_page_menu', 'attitude_wp_page_menu' );
/**
 * Remove div from wp_page_menu() and replace with ul.
 * @uses wp_page_menu filter
 */
function attitude_wp_page_menu ( $page_markup ) {
	preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
	$divclass = $matches[1];
	$replace = array('<div class="'.$divclass.'">', '</div>');
	$new_markup = str_replace($replace, '', $page_markup);
	$new_markup = preg_replace('/^<ul>/i', '<ul class="'.$divclass.'">', $new_markup);
	return $new_markup; 
}

/****************************************************************************************/

if ( ! function_exists( 'attitude_pass_cycle_parameters' ) ) :
/**
 * Function to pass the slider effectr parameters from php file to js file.
 */
function attitude_pass_cycle_parameters() {
    
    global $attitude_theme_options_settings;
    $options = $attitude_theme_options_settings;

    $transition_effect = $options[ 'transition_effect' ];
    $transition_delay = $options[ 'transition_delay' ] * 1000;
    $transition_duration = $options[ 'transition_duration' ] * 1000;
    wp_localize_script( 
        'attitude_slider',
        'attitude_slider_value',
        array(
            'transition_effect' => $transition_effect,
            'transition_delay' => $transition_delay,
            'transition_duration' => $transition_duration
        )
    );
    
}
endif;

/****************************************************************************************/

add_filter( 'excerpt_length', 'attitude_excerpt_length' );
/**
 * Sets the post excerpt length to 30 words.
 *
 * function tied to the excerpt_length filter hook.
 *
 * @uses filter excerpt_length
 */
function attitude_excerpt_length( $length ) {
	return 40;
}

add_filter( 'excerpt_more', 'attitude_continue_reading' );
/**
 * Returns a "Continue Reading" link for excerpts
 */
function attitude_continue_reading() {
	return '&hellip; ';
}

/****************************************************************************************/

add_filter( 'body_class', 'attitude_body_class' );
/**
 * Filter the body_class
 *
 * Throwing different body class for the different layouts in the body tag
 */
function attitude_body_class( $classes ) {
	global $post;	
	global $attitude_theme_options_settings;
	$options = $attitude_theme_options_settings;

	if( $post ) {
		$layout = get_post_meta( $post->ID,'attitude_sidebarlayout', true ); 
	}
	if( empty( $layout ) || is_archive() || is_search() || is_home() ) {
		$layout = 'default';
	}
	if( 'default' == $layout ) {

		$themeoption_layout = $options[ 'default_layout' ];

		if( 'left-sidebar' == $themeoption_layout ) {
			$classes[] = 'left-sidebar-template';
		}
		elseif( 'right-sidebar' == $themeoption_layout  ) {
			$classes[] = '';
		}
		elseif( 'no-sidebar-full-width' == $themeoption_layout ) {
			$classes[] = '';
		}
		elseif( 'no-sidebar-one-column' == $themeoption_layout ) {
			$classes[] = 'one-column-template';
		}		
		elseif( 'no-sidebar' == $themeoption_layout ) {
			$classes[] = 'no-sidebar-template';
		}
	}
	elseif( 'left-sidebar' == $layout ) {
      $classes[] = 'left-sidebar-template';
   }
   elseif( 'right-sidebar' == $layout ) {
		$classes[] = '';
	}
	elseif( 'no-sidebar-full-width' == $layout ) {
		$classes[] = '';
	}
	elseif( 'no-sidebar-one-column' == $layout ) {
		$classes[] = 'one-column-template';
	}
	elseif( 'no-sidebar' == $layout ) {
		$classes[] = 'no-sidebar-template';
	}

	if( is_page_template( 'page-template-blog-image-medium.php' ) ) {
		$classes[] = 'blog-medium';
	}
	if( 'wide-layout' == $options[ 'site_layout' ] ) {
		$classes[] = 'wide-layout';
	}

	return $classes;
}

/****************************************************************************************/

add_action('wp_head', 'attitude_internal_css');
/**
 * Hooks the Custom Internal CSS to head section
 */
function attitude_internal_css() { 

	if ( ( !$attitude_internal_css = get_transient( 'attitude_internal_css' ) ) ) {

		global $attitude_theme_options_settings;
		$options = $attitude_theme_options_settings;

		if( !empty( $options[ 'custom_css' ] ) ) {
			$attitude_internal_css = '<!-- '.get_bloginfo('name').' Custom CSS Styles -->' . "\n";
			$attitude_internal_css .= '<style type="text/css" media="screen">' . "\n";
			$attitude_internal_css .=  $options['custom_css'] . "\n";
			$attitude_internal_css .= '</style>' . "\n";
		}

		set_transient( 'attitude_internal_css', $attitude_internal_css, 86940 );
	}
	echo $attitude_internal_css;
}

/****************************************************************************************/

add_action('wp_head', 'attitude_verification');
/**
 * Header Script Option
 *
 */ 
function attitude_verification() {;    
    
	if ( ( !$attitude_verification = get_transient( 'attitude_verification' ) ) )  {

		global $attitude_theme_options_settings;
		$options = $attitude_theme_options_settings;

		$attitude_verification = '';

		// site stats, analytics header code
		if ( !empty( $options['analytic_header'] ) ) {
		$attitude_verification .=  $options[ 'analytic_header' ] ;
		}

		set_transient( 'attitude_verification', $attitude_verification, 86940 );    
	}
	echo $attitude_verification;
}

/****************************************************************************************/

add_action('wp_footer', 'attitude_footercode');
/**
 * Footer Analytics Code
 */
function attitude_footercode() { 
    
   $attitude_footercode = '';
	if ( ( !$attitude_footercode = get_transient( 'attitude_footercode' ) )  ) {

		global $attitude_theme_options_settings;
		$options = $attitude_theme_options_settings;

		// site stats, analytics footer code
		if ( !empty( $options['analytic_footer'] ) ) {  
		$attitude_footercode .=  $options[ 'analytic_footer' ] ;
		}

		set_transient( 'attitude_footercode', $attitude_footercode, 86940 );
	}
	echo $attitude_footercode;
}

/****************************************************************************************/

add_action('template_redirect', 'attitude_feed_redirect');
/**
 * Redirect WordPress Feeds To FeedBurner
 */
function attitude_feed_redirect() {
	global $attitude_theme_options_settings;
	$options = $attitude_theme_options_settings;

	if ( !empty( $options['feed_url'] ) ) {
		$url = 'Location: '.$options['feed_url'];
		if ( is_feed() && !preg_match('/feedburner|feedvalidator/i', $_SERVER['HTTP_USER_AGENT'])) {
			header($url);
			header('HTTP/1.1 302 Temporary Redirect');
		}
	}
}

/****************************************************************************************/

add_action( 'pre_get_posts','attitude_alter_home' );
/**
 * Alter the query for the main loop in home page
 *
 * @uses pre_get_posts hook
 */
function attitude_alter_home( $query ){
	global $attitude_theme_options_settings;
	$options = $attitude_theme_options_settings;
	$cats = $options[ 'front_page_category' ];

	if ( $options[ 'exclude_slider_post'] != "0" && !empty( $options[ 'featured_post_slider' ] ) ) {
		if( $query->is_main_query() && $query->is_home() ) {
			$query->query_vars['post__not_in'] = $options[ 'featured_post_slider' ];
		}
	}

	if ( !in_array( '0', $cats ) ) {
		if( $query->is_main_query() && $query->is_home() ) {
			$query->query_vars['category__in'] = $options[ 'front_page_category' ];
		}
	}
}

/*************************************************************************************/

add_action('wp_head', 'attitude_check_background_color');
/**
 * Checking if background color is empty
 * If the background color is not empty background-image should be set to none 
 * else background color will be not displayed in the site.
 */
function attitude_check_background_color() {

	$background_color = esc_attr(get_background_color());
			if ( $background_color != "" ) {
				$attitude_css  = '<!-- '.get_bloginfo('name').' Custom CSS Styles -->' . "\n";
		      $attitude_css .= '<style type="text/css" media="screen">' . "\n";
				$attitude_css .= 'body { background-image: none; }' . "\n";
				$attitude_css .= '</style>' . "\n";
			}
	if( isset( $attitude_css ) ) {
		echo $attitude_css;
	}
}

/**************************************************************************************/

add_filter( 'wp_nav_menu_items', 'attitude_nav_menu_alter', 10, 2 );
/**
* Add default navigation menu to nav menu
* Used while viewing on smaller screen
*/
if ( !function_exists('attitude_nav_menu_alter') ) {
	function attitude_nav_menu_alter( $items, $args ) {
		$items .= '<li class="default-menu"><a href="'.get_bloginfo('url').'" title="Navigation">'.__( 'Navigation','attitude' ).'</a></li>';
		return $items;
	}
}

/****************************************************************************************/

add_filter( 'wp_list_pages', 'attitude_page_menu_alter' );
/**
 * Add default navigation menu to page menu
 * Used while viewing on smaller screen
 */
if ( !function_exists('attitude_page_menu_alter') ) {
	function attitude_page_menu_alter( $output ) {
		$output .= '<li class="default-menu"><a href="'.get_bloginfo('url').'" title="Navigation">'.__( 'Navigation','attitude' ).'</a></li>';
		return $output;
	}
}

/****************************************************************************************/

add_filter('wp_page_menu', 'attitude_wp_page_menu_filter');
/**
 * @uses wp_page_menu filter hook
 */
if ( !function_exists('attitude_wp_page_menu_filter') ) {
	function attitude_wp_page_menu_filter( $text ) {
		$replace = array(
			'current_page_item'     => 'current-menu-item'
	 	);

	  $text = str_replace(array_keys($replace), $replace, $text);
	  return $text;
	}
}

/**************************************************************************************/

?>