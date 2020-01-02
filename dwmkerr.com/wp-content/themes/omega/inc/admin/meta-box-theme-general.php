<?php
/**
 * Creates a meta box for the theme settings page, which holds textareas for custom scripts within 
 * the theme. 
 *
 */

add_action( 'admin_menu', 'omega_theme_admin_general' );

function omega_theme_admin_general() {

	global $theme_settings_page;
	
	/* Get the theme settings page name */
	$theme_settings_page = 'appearance_page_theme-settings';

	/* Create a settings meta box only on the theme settings page. */
	add_action( 'load-appearance_page_theme-settings', 'omega_theme_settings_general' );

	/* Sanitize the scripts settings before adding them to the database. */
	add_filter( "sanitize_option_omega_theme_settings", 'omega_theme_validate_general' );

	/* Adds my_help_tab when my_admin_page loads */
    add_action('load-'.$theme_settings_page, 'omega_theme_settings_general_help');
}

/**
 * Adds the core theme scripts meta box to the theme settings page in the admin.
 *
 * @since 0.3.0
 * @return void
 */
function omega_theme_settings_general() {

	add_meta_box(
		'omega-theme-general',			// Name/ID
		__( 'Header and Footer Scripts', 'omega' ),	// Label
		'omega_meta_box_theme_display_general',			// Callback function
		'appearance_page_theme-settings',		// Page to load on, leave as is
		'normal',					// Which meta box holder?
		'high'					// High/low within the meta box holder
	);

}

/**
 * Creates a meta box that allows users to customize their scripts.
 */
function omega_meta_box_theme_display_general() {
?>
	<p>
		<label for="<?php echo omega_settings_field_id( 'header_scripts' ); ?>"><?php printf( __( 'Insert scripts or code before the closing %s tag in the document source', 'omega' ), '<code>&lt;/head&gt;</code>' ); ?>:</label>
	</p>
	
	<textarea name="<?php echo omega_settings_field_name( 'header_scripts' ) ?>" id="<?php echo omega_settings_field_id( 'header_scripts' ); ?>" cols="78" rows="8"><?php echo omega_get_setting( 'header_scripts' ); ?></textarea>


	<p>
		<label for="<?php echo omega_settings_field_id( 'footer_scripts' ); ?>"><?php printf( __( 'Insert scripts or code before the closing %s tag in the document source', 'omega' ), '<code>&lt;/body&gt;</code>' ); ?>:</label>
	</p>

	<textarea name="<?php echo omega_settings_field_name( 'footer_scripts' ); ?>" id="<?php echo omega_settings_field_id( 'footer_scripts' ); ?>" cols="78" rows="8"><?php echo omega_get_setting( 'footer_scripts' ) ; ?></textarea>


<?php }

/**
 * Saves the scripts meta box settings by filtering the "sanitize_option_omega_theme_settings" hook.
 *
 * @since 0.3.0
 * @param array $settings Array of theme settings passed by the Settings API for validation.
 * @return array $settings
 */
function omega_theme_validate_general( $settings ) {

	if ( !isset( $_POST['reset'] ) ) {
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( isset( $settings['footer_scripts'] ) && !current_user_can( 'unfiltered_html' ) )
			$settings['footer_scripts'] = stripslashes( wp_filter_post_kses( addslashes( $settings['footer_scripts'] ) ) );

		if ( isset( $settings['header_scripts'] ) && !current_user_can( 'unfiltered_html' ) )
			$settings['header_scripts'] = stripslashes( wp_filter_post_kses( addslashes( $settings['header_scripts'] ) ) );

	}	

	/* Return the theme settings. */
	return $settings;
}

/**
 * Contextual help content.
 */
function omega_theme_settings_general_help() {

	$screen = get_current_screen();

	$general_help =
		'<h3>' . __( 'Header and Footer Scripts', 'omega' ) . '</h3>' .
		'<p>'  . __( 'This provides you with two fields that will output to the head section of your site and just before the closing body tag. These will appear on every page of the site and are a great way to add analytic code, Google Font and other scripts. You cannot use PHP in these fields.', 'omega' ) . '</p>';
			
	$screen->add_help_tab( array(
		'id'      => 'omega-settings' . '-general',
		'title'   => __( 'Header and Footer Scripts', 'omega' ),
		'content' => $general_help,
	) );

}

?>