<?php
/**
 * Shortcodes bundled for use with themes.  These shortcodes are not meant to be used with the post content 
 * editor.  Their purpose is to make it easier for users to filter hooks without having to know too much PHP code
 * and to provide access to specific functionality in other (non-post content) shortcode-aware areas.  Note that 
 * some shortcodes are specific to posts and comments and would be useless outside of the post and comment 
 * loops.  To use the shortcodes, a theme must register support for 'omega-shortcodes'.
 */

/* Register shortcodes. */
add_action( 'init', 'omega_add_shortcodes' );

/**
 * Creates new shortcodes for use in any shortcode-ready area.  This function uses the add_shortcode() 
 * function to register new shortcodes with WordPress.
 *
 * @since 0.8.0
 * @access public
 * @uses add_shortcode() to create new shortcodes.
 * @link http://codex.wordpress.org/Shortcode_API
 * @return void
 */
function omega_add_shortcodes() {

	/* Add theme-specific shortcodes. */
	add_shortcode( 'the-year',      'omega_the_year_shortcode' );
	add_shortcode( 'site-link',     'omega_site_link_shortcode' );
	add_shortcode( 'wp-link',       'omega_wp_link_shortcode' );
	add_shortcode( 'theme-link',    'omega_theme_link_shortcode' );
	add_shortcode( 'child-link',    'omega_child_link_shortcode' );
	add_shortcode( 'author-uri',    'omega_author_uri_shortcode' );	

}

/**
 * Shortcode to display the current year.
 *
 * @since 0.6.0
 * @access public
 * @uses date() Gets the current year.
 * @return string
 */
function omega_the_year_shortcode() {
	return date_i18n( 'Y' );
}

/**
 * Shortcode to display a link back to the site.
 *
 * @since 0.6.0
 * @access public
 * @uses get_bloginfo() Gets information about the install.
 * @return string
 */
function omega_site_link_shortcode() {
	return omega_get_site_link();
}

/**
 * Shortcode to display a link to WordPress.org.
 *
 * @since 0.6.0
 * @access public
 * @return string
 */
function omega_wp_link_shortcode() {
	return omega_get_wp_link();
}

/**
 * Shortcode to display a link to the parent theme page.
 *
 * @since 0.6.0
 * @access public
 * @uses get_theme_data() Gets theme (parent theme) information.
 * @return string
 */
function omega_theme_link_shortcode() {
	return omega_get_theme_link();
}

/**
 * Shortcode to display a link to the child theme's page.
 *
 * @since 0.6.0
 * @access public
 * @uses get_theme_data() Gets theme (child theme) information.
 * @return string
 */
function omega_child_link_shortcode() {
	return omega_get_child_theme_link();
}

/**
 * Shortcode to display a link to author url.
 *
 * @since 0.9.0
 * @access public
 * @uses get_theme_data() Gets theme (parent theme) information.
 * @return string
 */
function omega_author_uri_shortcode() {
	return omega_get_author_uri();
}