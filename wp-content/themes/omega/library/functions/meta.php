<?php
/**
 * Metadata functions used in the core framework.  This file registers meta keys for use in WordPress 
 * in a safe manner by setting up a custom sanitize callback.
 */

/* Register meta on the 'init' hook. */
add_action( 'init', 'omega_register_meta' );

/**
 * Registers the framework's custom metadata keys and sets up the sanitize callback function.
 *
 * @since 0.9.0
 * @return void
 */
function omega_register_meta() {

	/* Register meta if the theme supports the 'omega-template-hierarchy' feature. */
	if ( current_theme_supports( 'omega-template-hierarchy' ) ) {

		$post_types = get_post_types( array( 'public' => true ) );

		foreach ( $post_types as $post_type ) {
			if ( 'page' !== $post_type )
				register_meta( 'post', "_wp_{$post_type}_template", 'omega_sanitize_meta' );
		}
	}
}

/**
 * Callback function for sanitizing meta when add_metadata() or update_metadata() is called by WordPress. 
 * If a developer wants to set up a custom method for sanitizing the data, they should use the 
 * "sanitize_{$meta_type}_meta_{$meta_key}" filter hook to do so.
 *
 * @since 0.9.0
 * @param mixed $meta_value The value of the data to sanitize.
 * @param string $meta_key The meta key name.
 * @param string $meta_type The type of metadata (post, comment, user, etc.)
 * @return mixed $meta_value
 */
function omega_sanitize_meta( $meta_value, $meta_key, $meta_type ) {
	return strip_tags( $meta_value );
}

?>