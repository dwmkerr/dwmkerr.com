<?php
/**
 * Contains all the shortcodes of the theme.
 *
 * @package Theme Horse
 * @subpackage Attitude
 * @since Attitude 1.0
 */

/* Register shortcodes. */
add_action( 'init', 'attitude_add_shortcodes' );
/**
 * Creates new shortcodes for use in any shortcode-ready area.  This function uses the add_shortcode() 
 * function to register new shortcodes with WordPress.
 *
 * @uses add_shortcode() to create new shortcodes.
 */
function attitude_add_shortcodes() {
	/* Add theme-specific shortcodes. */
	add_shortcode( 'the-year', 'attitude_the_year_shortcode' );
	add_shortcode( 'site-link', 'attitude_site_link_shortcode' );
	add_shortcode( 'wp-link', 'attitude_wp_link_shortcode' );
	add_shortcode( 'th-link', 'attitude_themehorse_link_shortcode' );
}

/**
 * Shortcode to display the current year.
 *
 * @uses date() Gets the current year.
 * @return string
 */
function attitude_the_year_shortcode() {
   return date( 'Y' );
}

/**
 * Shortcode to display a link back to the site.
 *
 * @uses get_bloginfo() Gets the site link
 * @return string
 */
function attitude_site_link_shortcode() {
   return '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" ><span>' . get_bloginfo( 'name', 'display' ) . '</span></a>';
}

/**
 * Shortcode to display a link to WordPress.org.
 *
 * @return string
 */
function attitude_wp_link_shortcode() {
   return '<a href="'.esc_url( 'http://wordpress.org' ).'" target="_blank" title="' . esc_attr__( 'WordPress', 'attitude' ) . '"><span>' . __( 'WordPress', 'attitude' ) . '</span></a>';
}

/**
 * Shortcode to display a link to attitude.com.
 *
 * @return string
 */
function attitude_themehorse_link_shortcode() {
   return '<a href="'.esc_url( 'http://themehorse.com' ).'" target="_blank" title="'.esc_attr__( 'Theme Horse', 'attitude' ).'" ><span>'.__( 'Theme Horse', 'attitude') .'</span></a>';
}

?>