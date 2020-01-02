<?php
/**
 * Creates a meta box for the theme settings page, which holds textareas for custom scripts within 
 * the theme. 
 *
 */

add_action( 'admin_menu', 'omega_theme_admin_comments' );

function omega_theme_admin_comments() {

	global $theme_settings_page;

	/* Create a settings meta box only on the theme settings page. */
	add_action( 'load-appearance_page_theme-settings', 'omega_theme_settings_comments' );

	/* Sanitize the scripts settings before adding them to the database. */
	add_filter( "sanitize_option_omega_theme_settings", 'omega_theme_validate_comments' );

	/* Adds my_help_tab when my_admin_page loads */
    add_action('load-'.$theme_settings_page, 'omega_theme_settings_comments_help');
}

/**
 * Adds the core theme scripts meta box to the theme settings page in the admin.
 *
 * @since 0.3.0
 * @return void
 */
function omega_theme_settings_comments() {

	/* Add a custom meta box. */
	add_meta_box( 
		'omega-theme-comments', 
		__( 'Comments and Trackbacks', 'omega' ), 
		'omega_meta_box_theme_display_comments', 
		'appearance_page_theme-settings', 'normal', 'high' );

}

/**
 * Callback for Theme Settings Comments meta box.
 */
function omega_meta_box_theme_display_comments() {
?>
	<p>
		<?php _e( 'Enable Comments', 'omega' ); ?>
		<label for="<?php echo omega_settings_field_id( 'comments_posts' ); ?>" title="Enable comments on posts"><input type="checkbox" name="<?php echo omega_settings_field_name( 'comments_posts' ); ?>" id="<?php echo omega_settings_field_id( 'comments_posts' ); ?>" value="1"<?php checked( omega_get_setting( 'comments_posts' ) ); ?> />
		<?php _e( 'on posts?', 'omega' ); ?></label>

		<label for="<?php echo omega_settings_field_id( 'comments_pages' ); ?>" title="Enable comments on pages"><input type="checkbox" name="<?php echo omega_settings_field_name( 'comments_pages' ); ?>" id="<?php echo omega_settings_field_id( 'comments_pages' ); ?>" value="1"<?php checked( omega_get_setting( 'comments_pages' ) ); ?> />
		<?php _e( 'on pages?', 'omega' ); ?></label>
	</p>

	<p>
		<?php _e( 'Enable Trackbacks', 'omega' ); ?>
		<label for="<?php echo omega_settings_field_id( 'trackbacks_posts' ); ?>" title="Enable trackbacks on posts"><input type="checkbox" name="<?php echo omega_settings_field_name( 'trackbacks_posts' ); ?>" id="<?php echo omega_settings_field_id( 'trackbacks_posts' ); ?>" value="1"<?php checked( omega_get_setting( 'trackbacks_posts' ) ); ?> />
		<?php _e( 'on posts?', 'omega' ); ?></label>

		<label for="<?php echo omega_settings_field_id( 'trackbacks_pages' ); ?>" title="Enable trackbacks on pages"><input type="checkbox" name="<?php echo omega_settings_field_name( 'trackbacks_pages' ); ?>" id="<?php echo omega_settings_field_id( 'trackbacks_pages' ); ?>" value="1"<?php checked( omega_get_setting( 'trackbacks_pages' ) ); ?> />
		<?php _e( 'on pages?', 'omega' ); ?></label>
	</p>

	<p><span class="description"><?php _e( 'Comments and Trackbacks can also be disabled on a per post/page basis when creating/editing posts/pages.', 'omega' ); ?></span></p>

<?php
}

/**
 * Saves the scripts meta box settings by filtering the "sanitize_option_omega_theme_settings" hook.
 *
 * @since 0.3.0
 * @param array $settings Array of theme settings passed by the Settings API for validation.
 * @return array $settings
 */
function omega_theme_validate_comments( $settings ) {

	if ( !isset( $_POST['reset'] ) ) {
		$settings['comments_posts'] =  absint( $settings['comments_posts'] );
		$settings['trackbacks_posts'] =  absint( $settings['trackbacks_posts'] );
		$settings['comments_pages'] =  absint( $settings['comments_pages'] );
		$settings['trackbacks_pages'] =  absint( $settings['trackbacks_pages'] );		
	}

	/* Return the theme settings. */
	return $settings;
}

/**
 * Contextual help content.
 */
function omega_theme_settings_comments_help() {

	$screen = get_current_screen();

	$comments_help =
		'<h3>' . __( 'Comments and Trackbacks', 'omega' ) . '</h3>' .
		'<p>'  . __( 'This allows a site wide decision on whether comments and trackbacks (notifications when someone links to your page) are enabled for posts and pages.', 'omega' ) . '</p>' .
		'<p>'  . __( 'If you enable comments or trackbacks here, it can be disabled on an individual post or page. If you disable here, they cannot be enabled on an individual post or page.', 'omega' ) . '</p>';

	
	$screen->add_help_tab( array(
		'id'      => 'omega-settings' . '-comments',
		'title'   => __( 'Comments and Trackbacks', 'omega' ),
		'content' => $comments_help,
	) );

}

?>