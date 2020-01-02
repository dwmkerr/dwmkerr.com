<?php
/**
 * Handles the display and functionality of the theme settings page. This provides the needed hooks and
 * meta box calls for developers to create any number of theme settings needed. This file is only loaded if 
 * the theme supports the 'omega-theme-settings' feature.
 *
 * Provides the ability for developers to add custom meta boxes to the theme settings page by using the 
 * add_meta_box() function.  Developers should register their meta boxes on the 'add_meta_boxes' hook 
 * and register the meta box for 'appearance_page_theme-settings'.  To validate/sanitize data from 
 * custom settings, devs should use the 'sanitize_option_omega_theme_settings' filter hook.
 */

/* Hook the settings page function to 'admin_menu'. */
add_action( 'admin_menu', 'omega_settings_page_init' );

/**
 * Initializes all the theme settings page functionality. This function is used to create the theme settings 
 * page, then use that as a launchpad for specific actions that need to be tied to the settings page.
 *
 * @since 0.7.0
 * @global string $omega The global theme object.
 * @return void
 */
function omega_settings_page_init() {
	global $omega;

	/* Get theme information. */
	$theme = wp_get_theme( get_template() );

	/* Register theme settings. */
	register_setting(
		'omega_theme_settings',		// Options group.
		'omega_theme_settings',		// Database option.
		'omega_save_theme_settings'	// Validation callback function.
	);

	/* Create the theme settings page. */
	$omega->settings_page = add_theme_page(
		sprintf( esc_html__( 'Theme Settings', 'omega' ), $theme->get( 'Name' ) ),	// Settings page name.
		esc_html__( 'Theme Settings', 'omega' ),				// Menu item name.
		omega_settings_page_capability(),					// Required capability.
		'theme-settings',							// Screen name.
		'omega_settings_page'						// Callback function.
	);

	/* Check if the settings page is being shown before running any functions for it. */
	if ( !empty( $omega->settings_page ) ) {

		/* Filter the settings page capability so that it recognizes the 'edit_theme_options' cap. */
		add_filter( "option_page_capability_omega_theme_settings", 'omega_settings_page_capability' );

		/* Add help tabs to the theme settings page. */
		add_action( "load-{$omega->settings_page}", 'omega_settings_page_help' );

		/* Load the theme settings meta boxes. */
		add_action( "load-{$omega->settings_page}", 'omega_load_settings_page_meta_boxes' );

		/* Create a hook for adding meta boxes. */
		add_action( "load-{$omega->settings_page}", 'omega_settings_page_add_meta_boxes' );

		/* Load the JavaScript and stylesheets needed for the theme settings screen. */
		add_action( 'admin_enqueue_scripts', 'omega_settings_page_enqueue_scripts' );
		add_action( 'admin_enqueue_scripts', 'omega_settings_page_enqueue_styles' );
		add_action( "admin_footer-{$omega->settings_page}", 'omega_settings_page_load_scripts' );
	}
}

/**
 * Returns the required capability for viewing and saving theme settings.
 *
 * @since 1.2.0
 * @return string
 */
function omega_settings_page_capability() {
	return apply_filters( omega_get_prefix() . '_settings_capability', 'edit_theme_options' );
}

/**
 * Returns the theme settings page name/hook as a string.
 *
 * @since 1.2.0
 * @return string
 */
function omega_get_settings_page_name() {
	global $omega;

	return ( isset( $omega->settings_page ) ? $omega->settings_page : 'appearance_page_theme-settings' );
}

/**
 * Provides a hook for adding meta boxes as seen on the post screen in the WordPress admin.  This addition 
 * is needed because normal plugin/theme pages don't have this hook by default.  The other goal of this 
 * function is to provide a way for themes to load and execute meta box code only on the theme settings 
 * page in the admin.  This way, they're not needlessly loading extra files.
 *
 * @since 1.2.0
 * @return void
 */
function omega_settings_page_add_meta_boxes() {

	do_action( 'add_meta_boxes', omega_get_settings_page_name() );
}

/**
 * Loads the meta boxes packaged with the framework on the theme settings page.  These meta boxes are 
 * merely loaded with this function.  Meta boxes are only loaded if the feature is supported by the theme.
 *
 * @since 1.2.0
 * @return void
 */
function omega_load_settings_page_meta_boxes() {

	/* Get theme-supported meta boxes for the settings page. */
	$supports = get_theme_support( 'omega-theme-settings' );
}

/**
 * Validation/Sanitization callback function for theme settings.  This just returns the data passed to it.  Theme
 * developers should validate/sanitize their theme settings on the "sanitize_option_omega_theme_settings" 
 * hook.  This function merely exists for backwards compatibility.
 *
 * @since 0.7.0
 * @param array $settings An array of the theme settings passed by the Settings API for validation.
 * @return array $settings The array of theme settings.
 */
function omega_save_theme_settings( $settings ) {

	/* @deprecated 1.0.0. Developers should filter "sanitize_option_omega_theme_settings" instead. */
	return apply_filters( omega_get_prefix() . '_validate_theme_settings', $settings );
}

/**
 * Displays the theme settings page and calls do_meta_boxes() to allow additional settings
 * meta boxes to be added to the page.
 *
 * @since 0.7.0
 * @return void
 */
function omega_settings_page() {

	/* Get the theme information. */
	$theme = wp_get_theme( get_template() );

	do_action( "omega_before_settings_page" ); ?>

	<div class="wrap">

		<?php screen_icon(); ?>
		<h2>
			<?php printf( __( 'Theme Settings', 'omega' ) ); // hence update ?>
			<a href="<?php echo admin_url( 'customize.php' ); ?>" class="add-new-h2"><?php esc_html_e( 'Customize', 'omega' ); ?></a>
			<?php do_action( "omega_child_theme" ); // hence add ?>
		</h2>
		<?php settings_errors(); ?>

		<?php do_action( "omega_open_settings_page" ); ?>

		<div class="omega-settings-wrap">

			<form method="post" action="options.php">

				<?php settings_fields( "omega_theme_settings" ); ?>
				<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

				<div id="poststuff">

					<div id="post-body" class="metabox-holder columns-2">

						<div id="postbox-container-2" class="postbox-container normal advanced">
							<?php do_meta_boxes( omega_get_settings_page_name(), 'normal', null ); ?>
							<?php do_meta_boxes( omega_get_settings_page_name(), 'advanced', null ); ?>
						</div><!-- #postbox-container-2 -->

					</div><!-- #post-body -->

					<br class="clear">

				</div><!-- #poststuff -->

				<?php do_action('omega_admin_setting');?>
				<?php submit_button( esc_attr__( 'Update Settings', 'omega' ), 'primary', 'submit', false ); // hence updated ?>
				<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore Defaults', 'omega' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Click OK to reset. Any theme settings will be lost!', 'omega' ) ); ?>' );" />

			</form>

		</div><!-- .omega-settings-wrap -->

		<?php do_action( "omega_close_settings_page" ); ?>

	</div><!-- .wrap --><?php

	do_action( "omega_after_settings_page" );
}

/**
 * Creates a settings field id attribute for use on the theme settings page.  This is a helper function for use
 * with the WordPress settings API.
 *
 * @since 1.0.0
 * @return string
 */
function omega_settings_field_id( $setting ) {
	return 'omega_theme_settings-' . sanitize_html_class( $setting );
}

/**
 * Creates a settings field name attribute for use on the theme settings page.  This is a helper function for 
 * use with the WordPress settings API.
 *
 * @since 1.0.0
 * @return string
 */
function omega_settings_field_name( $setting ) {
	return "omega_theme_settings[{$setting}]";
}

/**
 * Adds a help tab to the theme settings screen if the theme has provided a 'Documentation URI' and/or 
 * 'Support URI'.  Theme developers can add custom help tabs using get_current_screen()->add_help_tab().
 *
 * @since 1.3.0
 * @return void
 */
function omega_settings_page_help() {

	/* Get the parent theme data. */
	$theme = wp_get_theme( get_template() );
	$doc_uri = $theme->get( 'Documentation URI' );
	$support_uri = $theme->get( 'Support URI' );

	/* If the theme has provided a documentation or support URI, add them to the help text. */
	if ( !empty( $doc_uri ) || !empty( $support_uri ) ) {

		/* Open an unordered list for the help text. */
		$help = '<ul>';

		/* Add the Documentation URI. */
		if ( !empty( $doc_uri ) )
			$help .= '<li><a href="' . esc_url( $doc_uri ) . '">' . __( 'Documentation', 'omega' ) . '</a></li>';

		/* Add the Support URI. */
		if ( !empty( $support_uri ) )
			$help .= '<li><a href="' . esc_url( $support_uri ) . '">' . __( 'Support', 'omega' ) . '</a></li>';

		/* Close the unordered list for the help text. */
		$help .= '</ul>';

		/* Add a help tab with links for documentation and support. */
		get_current_screen()->add_help_tab(
			array(
				'id' => 'default',
				'title' => esc_attr( $theme->get( 'Name' ) ),
				'content' => $help
			)
		);
	}
}

/**
 * Loads the required stylesheets for displaying the theme settings page in the WordPress admin.
 *
 * @since 1.2.0
 * @return void
 */
function omega_settings_page_enqueue_styles( $hook_suffix ) {

	/* Load admin stylesheet if on the theme settings screen. */
	if ( $hook_suffix == omega_get_settings_page_name() )
		wp_enqueue_style( 'omega-admin' );
}

/**
 * Loads the JavaScript files required for managing the meta boxes on the theme settings
 * page, which allows users to arrange the boxes to their liking.
 *
 * @since 1.2.0
 * @param string $hook_suffix The current page being viewed.
 * @return void
 */
function omega_settings_page_enqueue_scripts( $hook_suffix ) {

	if ( $hook_suffix == omega_get_settings_page_name() ){
		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );
	
	}
}

/**
 * Loads the JavaScript required for toggling the meta boxes on the theme settings page.
 *
 * @since 0.7.0
 * @return void
 */
function omega_settings_page_load_scripts() { ?>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			postboxes.add_postbox_toggles( '<?php echo omega_get_settings_page_name(); ?>' );
		});
		//]]>
	</script><?php
}

?>