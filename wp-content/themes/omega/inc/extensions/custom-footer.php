<?php
/**
 * Functions for registering and setting theme settings that tie into the WordPress theme customizer.  
 * This file loads additional classes and adds settings to the customizer for the built-in Omega Core 
 * settings.
 */

/* Load custom control classes. */
add_action( 'customize_register', 'omega_load_footer_customize_controls', 1 );

/* Register custom sections, settings, and controls. */
add_action( 'customize_register', 'omega_customize_footer_register' );

/* Add the footer content Ajax to the correct hooks. */
add_action( 'wp_ajax_omega_customize_footer_content', 'omega_customize_footer_content_ajax' );
add_action( 'wp_ajax_nopriv_omega_customize_footer_content', 'omega_customize_footer_content_ajax' );

/**
 * Loads framework-specific customize control classes.  Customize control classes extend the WordPress 
 * WP_Customize_Control class to create unique classes that can be used within the framework.
 *
 * @since 1.4.0
 * @access private
 */
function omega_load_footer_customize_controls() {

	/* Loads the textarea customize control class. */
	require_once( trailingslashit( OMEGA_CLASSES ) . 'customize-control-textarea.php' );
}

/**
 * Registers custom sections, settings, and controls for the $wp_customize instance.
 *
 * @since 1.4.0
 * @access private
 * @param object $wp_customize
 */
function omega_customize_footer_register( $wp_customize ) {

	/* Add the section. */
	$wp_customize->add_section(
		'footer',
		array(
			'title'      => esc_html__( 'Footer', 'omega' ),
			'priority'   => 149,
			'capability' => 'edit_theme_options'
		)
	);

	/* Add the 'footer_insert' setting. */
	$wp_customize->add_setting(
		"custom_footer",
		array(
			'default'              => apply_filters( 'omega_footer_insert', '' ),
			'type'                 => 'theme_mod',
			'capability'           => 'edit_theme_options',
			'sanitize_callback'    => 'omega_customize_sanitize',
			'sanitize_js_callback' => 'omega_customize_sanitize',
			'transport'            => 'postMessage',
		)
	);

	/* Add the textarea control for the 'footer_insert' setting. */
	$wp_customize->add_control(
		new Omega_Customize_Control_Textarea(
			$wp_customize,
			'omega-footer',
			array(
				'label'    => esc_html__( 'Footer Text', 'omega' ),
				'section'  => 'footer',
				'settings' => "custom_footer",
			)
		)
	);

	/* If viewing the customize preview screen, add a script to show a live preview. */
	if ( $wp_customize->is_preview() && !is_admin() )
		add_action( 'wp_footer', 'omega_customize_preview_footer_script', 21 );
	
}

/**
 * Sanitizes the footer content on the customize screen.  Users with the 'unfiltered_html' cap can post 
 * anything.  For other users, wp_filter_post_kses() is ran over the setting.
 *
 * @since 1.4.0
 * @access public
 * @param mixed $setting The current setting passed to sanitize.
 * @param object $object The setting object passed via WP_Customize_Setting.
 * @return mixed $setting
 */
function omega_customize_sanitize( $setting, $object ) {

	/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( "omega_theme_settings[footer_insert]" == $object->id && !current_user_can( 'unfiltered_html' )  )
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );

	/* Return the sanitized setting and apply filters. */
	return apply_filters( "omega_customize_sanitize", $setting, $object );
}

/**
 * Runs the footer content posted via Ajax through the do_shortcode() function.  This makes sure the 
 * shortcodes are output correctly in the live preview.
 *
 * @since 1.4.0
 * @access private
 */
function omega_customize_footer_content_ajax() {

	/* Check the AJAX nonce to make sure this is a valid request. */
	check_ajax_referer( 'omega_customize_footer_content_nonce' );

	/* If footer content has been posted, run it through the do_shortcode() function. */
	if ( isset( $_POST['footer_content'] ) )
		echo do_shortcode( wp_kses_stripslashes( $_POST['footer_content'] ) );

	/* Always die() when handling Ajax. */
	die();
}

/**
 * Handles changing settings for the live preview of the theme.
 *
 * @since 1.4.0
 * @access private
 */
function omega_customize_preview_footer_script() {

	/* Create a nonce for the Ajax. */
	$nonce = wp_create_nonce( 'omega_customize_footer_content_nonce' );

	?>
	<script type="text/javascript">
	wp.customize(
		'custom_footer',
		function( value ) {
			value.bind(
				function( to ) {
					jQuery.post( 
						'<?php echo admin_url( 'admin-ajax.php' ); ?>', 
						{ 
							action: 'omega_customize_footer_content',
							_ajax_nonce: '<?php echo $nonce; ?>',
							footer_content: to
						},
						function( response ) {
							jQuery( '.footer-content' ).html( response );
						}
					);
				}
			);
		}
	);
	</script>
	<?php
}

?>