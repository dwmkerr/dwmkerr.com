<?php
/**
 * Functions for handling stylesheets in the framework.  Themes can add support for the 
 * 'omega-styles' feature to allow the framework to handle loading the stylesheets into the 
 * theme header at an appropriate point.
 */

/* Register Omega Core styles. */
add_action( 'wp_enqueue_scripts', 'omega_register_styles', 1 );

/* Load Omega Core styles. */
add_action( 'wp_enqueue_scripts', 'omega_enqueue_styles', 5 );

/* Load the development stylsheet in script debug mode. */
add_filter( 'stylesheet_uri', 'omega_min_stylesheet_uri', 10, 2 );

/* Filters the WP locale stylesheet. */
add_filter( 'locale_stylesheet_uri', 'omega_locale_stylesheet_uri' );

/**
 * Registers stylesheets for the framework.  This function merely registers styles with WordPress using
 * the wp_register_style() function.  It does not load any stylesheets on the site.  If a theme wants to 
 * register its own custom styles, it should do so on the 'wp_enqueue_scripts' hook.
 *
 * @since 0.9.0
 * @access private
 * @return void
 */
function omega_register_styles() {

	/* Get framework styles. */
	$styles = omega_get_styles();

	/* Use the .min stylesheet if SCRIPT_DEBUG is turned off. */
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	/* Loop through each style and register it. */
	foreach ( $styles as $style => $args ) {

		$defaults = array( 
			'handle'  => $style, 
			'src'     => trailingslashit( OMEGA_CSS ) . "{$style}{$suffix}.css",
			'deps'    => null,
			'version' => false,
			'media'   => 'all'
		);

		$args = wp_parse_args( $args, $defaults );

		wp_register_style(
			sanitize_key( $args['handle'] ), 
			esc_url( $args['src'] ), 
			is_array( $args['deps'] ) ? $args['deps'] : null, 
			preg_replace( '/[^a-z0-9_\-.]/', '', strtolower( $args['version'] ) ), 
			esc_attr( $args['media'] )
		);
	}
}

/**
 * Tells WordPress to load the styles needed for the framework using the wp_enqueue_style() function.
 *
 * @since 0.9.0
 * @access private
 * @return void
 */
function omega_enqueue_styles() {

	/* Get the theme-supported stylesheets. */
	$supports = get_theme_support( 'omega-styles' );

	/* If the theme doesn't add support for any styles, return. */
	if ( !is_array( $supports[0] ) )
		return;

	/* Loop through each of the core framework styles and enqueue them if supported. */
	foreach ( $supports[0] as $style )
		wp_enqueue_style( $style );
}

/**
 * Returns an array of the core framework's available styles for use in themes.
 *
 * @since 0.9.0
 * @access private
 * @return array $styles All the available framework styles.
 */
function omega_get_styles() {

	/* Use the .min stylesheet if SCRIPT_DEBUG is turned off. */
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	/* Default styles available. */
	$styles = array(
		'one-five'   => array( 'version' => '20131105' ),
		'gallery'    => array( 'version' => '20130526' ),
	);

	/* If a child theme is active, add the parent theme's style. */
	if ( is_child_theme() ) {
		$parent = wp_get_theme( get_template() );

		/* Get the parent theme stylesheet. */
		$src = trailingslashit( THEME_URI ) . "style.css";

		/* If a '.min' version of the parent theme stylesheet exists, use it. */
		if ( !empty( $suffix ) && file_exists( trailingslashit( THEME_DIR ) . "style{$suffix}.css" ) )
			$src = trailingslashit( THEME_URI ) . "style{$suffix}.css";

		$styles['parent'] = array( 'src' => $src, 'version' => $parent->get( 'Version' ) );
	}

	/* Add the active theme style. */
	$styles['style'] = array( 'src' => get_stylesheet_uri(), 'version' => wp_get_theme()->get( 'Version' ) );

	/* Return the array of styles. */
	return apply_filters( 'omega_styles', $styles );
}

/**
 * Filters the 'stylesheet_uri' to allow theme developers to offer a minimized version of their main 
 * 'style.css' file.  It will detect if a 'style.min.css' file is available and use it if SCRIPT_DEBUG 
 * is disabled.
 *
 * @since 0.9.0
 * @access public
 * @param  string $stylesheet_uri The URI of the active theme's stylesheet.
 * @param  string $stylesheet_dir_uri The directory URI of the active theme's stylesheet.
 * @return string $stylesheet_uri
 */
function omega_min_stylesheet_uri( $stylesheet_uri, $stylesheet_dir_uri ) {

	/* Use the .min stylesheet if SCRIPT_DEBUG is turned off. */
	if ( !defined( 'SCRIPT_DEBUG' ) || false === SCRIPT_DEBUG ) {
		$suffix = '.min';

		/* Remove the stylesheet directory URI from the file name. */
		$stylesheet = str_replace( trailingslashit( $stylesheet_dir_uri ), '', $stylesheet_uri );

		/* Change the stylesheet name to 'style.min.css'. */
		$stylesheet = str_replace( '.css', "{$suffix}.css", $stylesheet );

		/* If the stylesheet exists in the stylesheet directory, set the stylesheet URI to the dev stylesheet. */
		if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $stylesheet ) )
			$stylesheet_uri = trailingslashit( $stylesheet_dir_uri ) . $stylesheet;
	}

	/* Return the theme stylesheet. */
	return $stylesheet_uri;
}

/**
 * Filters `locale_stylesheet_uri` with a more robust version for checking locale/language/region/direction 
 * stylesheets.
 *
 * @since  0.9.0
 * @access public
 * @param  string  $stylesheet_uri
 * @return string
 */
function omega_locale_stylesheet_uri( $stylesheet_uri ) {

	$locale_style = omega_get_locale_style();

	return !empty( $locale_style ) ? $locale_style : $stylesheet_uri;
}

/**
 * Searches for a locale stylesheet.  This function looks for stylesheets in the `css` folder in the following 
 * order:  1) $lang-$region.css, 2) $region.css, 3) $lang.css, and 4) $text_direction.css.  It first checks 
 * the child theme for these files.  If they are not present, it will check the parent theme.  This is much 
 * more robust than the WordPress locale stylesheet, allowing for multiple variations and a more flexible 
 * hierarchy.
 *
 * @since  0.9.0
 * @access public
 * @return string
 */
function omega_get_locale_style() {

	$locale = strtolower( str_replace( '_', '-', get_locale() ) );
	$lang   = strtolower( omega_get_language() );
	$region = strtolower( omega_get_region() );
	$styles = array();

	$styles[] = "css/{$locale}.css";

	if ( $region !== $locale )
		$styles[] = "css/{$region}.css";

	if ( $lang !== $locale )
		$styles[] = "css/{$lang}.css";

	$styles[] = is_rtl() ? 'css/rtl.css' : 'css/ltr.css';

	return omega_locate_theme_file( $styles );
}

