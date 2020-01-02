<?php

function omega_theme_inc() {

	// Set template directory
	define( 'OMEGA_INC', get_template_directory() . '/inc' );
	define( 'OMEGA_INC_ADMIN', OMEGA_INC . '/admin' );
	define( 'OMEGA_INC_FUNCTIONS', OMEGA_INC . '/functions' );
	define( 'OMEGA_INC_EXTENSIONS', OMEGA_INC . '/extensions' );

	/* Custom template tags for this theme. */
	require OMEGA_INC_FUNCTIONS . '/template-tags.php';

	/* Custom functions that act independently of the theme templates. */
	require OMEGA_INC_FUNCTIONS . '/extras.php';

	/* Function Hooks */
	require OMEGA_INC_FUNCTIONS . '/hooks.php';

	
	if ( is_admin() ) {
		// Load  theme settings page	
		require  OMEGA_INC_ADMIN . '/meta-box-theme-options.php';		
		require  OMEGA_INC_ADMIN . '/meta-box-theme-comments.php';
		require  OMEGA_INC_ADMIN . '/meta-box-theme-archives.php';
		require  OMEGA_INC_ADMIN . '/meta-box-theme-general.php';				
	}
	

	/* Load  child themes page if supported. */
	require_if_theme_supports( 'omega-child-themes-page', OMEGA_INC_EXTENSIONS . '/child-themes-page.php' );

	/* Load wraps extension if supported. */
	require_if_theme_supports( 'omega-wraps', OMEGA_INC_EXTENSIONS . '/wraps.php' );

	/* Load custom footer extension if supported. */
	require_if_theme_supports( 'omega-custom-footer', OMEGA_INC_EXTENSIONS . '/custom-footer.php' );

	/* Load custom css extension if supported. */
	require_if_theme_supports( 'omega-custom-css', OMEGA_INC_EXTENSIONS . '/custom-css.php' );

	/* Load custom logo extension if supported. */
	require_if_theme_supports( 'omega-custom-logo', OMEGA_INC_EXTENSIONS . '/custom-logo.php' );

	/* Load reponsive support. */
	require_if_theme_supports( 'omega-responsive', OMEGA_INC_EXTENSIONS . '/responsive.php' );

	/* Load  footer widgets extension if supported. */
	require_if_theme_supports( 'omega-footer-widgets', OMEGA_INC_EXTENSIONS . '/footer-widgets.php' );

}

add_action( 'after_setup_theme', 'omega_theme_inc', 20 );

/*
define( 'OMEGA_OPTIONS', get_template_directory() . '/inc/options/' );
define( 'OMEGA_OPTIONS_URI', get_template_directory_uri() . '/inc/options/' );
// theme option
require OMEGA_OPTIONS . 'theme-options.php';
*/

/* Register custom menus. */
add_action( 'init', 'omega_register_menus' );

/* Register sidebars. */
add_action( 'widgets_init', 'omega_register_sidebars' );

/**
 * Registers nav menu locations.
 *
 * @since  0.9.0
 * @access public
 * @return void
 */
function omega_register_menus() {
	register_nav_menu( 'primary',   _x( 'Primary', 'nav menu location', 'omega' ) );
}

/**
 * Registers sidebars.
 *
 * @since  0.9.0
 * @access public
 * @return void
 */

function omega_register_sidebars() {

	omega_register_sidebar(
		array(
			'id'          => 'primary',
			'name'        => _x( 'Primary', 'sidebar', 'omega' ),
			'description' => __( 'The main sidebar. It is displayed on either the left or right side of the page based on the chosen layout.', 'omega' )
		)
	);

}


