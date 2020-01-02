<?php
/**
 * Contains all the theme option default values
 * 
 * Set the default values for all the settings. If no user-defined values
 * is available for any setting, these defaults will be used.
 *
 * @package Theme Horse
 * @subpackage Attitude
 * @since Attitude 1.0
 */

global $attitude_theme_options_defaults;
$attitude_theme_options_defaults = array(
	'hide_header_searchform'			=> '0',
 	'disable_slogan' 						=> '0',
 	'home_slogan1'							=> '',
 	'home_slogan2'							=> '',
 	'slogan_position'						=> 'below-slider',
 	'disable_slider'						=> '0',
 	'exclude_slider_post'				=> '0',
 	'default_layout'						=> 'right-sidebar',
 	'reset_layout'							=> '0',
 	'custom_css'							=> '',
 	'disable_favicon'						=> '1',
 	'favicon'								=> '',
 	'disable_webpageicon'				=> '1',
 	'webpageicon'							=> '',
 	'slider_quantity' 					=> '4',
 	'featured_post_slider'				=> array(),
 	'transition_effect'					=> 'fade',
 	'transition_delay'					=> '4',
 	'transition_duration'				=> '1',
 	'social_facebook' 					=> '',
 	'social_twitter' 						=> '',
 	'social_googleplus' 					=> '',
 	'social_pinterest' 					=> '',
 	'social_vimeo' 						=> '',
 	'social_linkedin' 					=> '',
 	'social_flickr' 						=> '',
 	'social_tumblr' 						=> '',
 	'social_myspace' 						=> '',
 	'social_rss' 							=> '',
 	'social_youtube'						=> '',
 	'analytic_header' 					=> '',
 	'analytic_footer' 					=> '',
 	'feed_url'								=> '',
 	'front_page_category'				=> array(),
 	'header_logo'							=> '',
 	'header_show'							=> 'header-text',
 	'button_text'							=> '',
 	'redirect_button_link'				=> '',
 	'site_layout'							=> 'narrow-layout'


 );
global $attitude_theme_options_settings;
$attitude_theme_options_settings = attitude_theme_options_set_defaults( $attitude_theme_options_defaults );

function attitude_theme_options_set_defaults( $attitude_theme_options_defaults) {
	$attitude_theme_options_settings = array_merge( $attitude_theme_options_defaults, (array) get_option( 'attitude_theme_options', array() ) );
	return apply_filters( 'attitude_theme_options_settings', $attitude_theme_options_settings );
}

?>