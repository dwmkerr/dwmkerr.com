<?php
/**
 * Creates a meta box for the theme settings page, which holds textareas for custom scripts within 
 * the theme. 
 *
 */

add_action( 'admin_menu', 'omega_theme_admin_archives' );

function omega_theme_admin_archives() {

	global $theme_settings_page;
	
	/* Create a settings meta box only on the theme settings page. */
	add_action( 'load-appearance_page_theme-settings', 'omega_theme_settings_archives' );

	/* Sanitize the scripts settings before adding them to the database. */
	add_filter( "sanitize_option_omega_theme_settings", 'omega_theme_validate_archives' );

	/* Adds my_help_tab when my_admin_page loads */
    add_action('load-'.$theme_settings_page, 'omega_theme_settings_archives_help');
}

/**
 * Adds Content Archives meta box to the theme settings page in the admin.
 *
 * @since 0.3.0
 * @return void
 */
function omega_theme_settings_archives() {

	add_meta_box( 
		'omega-theme-archives', 
		__( 'Content Archives', 'omega' ), 
		'omega_meta_box_theme_display_archives', 
		'appearance_page_theme-settings', 'normal', 'high' );

}

/**
 * Callback for Theme Settings Post Archives meta box.
 */
function omega_meta_box_theme_display_archives() {
?>
	<p>
		<label for="<?php echo omega_settings_field_id( 'content_archive' ); ?>"><?php _e( 'Select one of the following:', 'omega' ); ?></label>
		<select name="<?php echo omega_settings_field_name( 'content_archive' ); ?>" id="<?php echo omega_settings_field_id( 'content_archive' ); ?>">
		<?php
		$archive_display = apply_filters(
			'omega_archive_display_options',
			array(
				'full'     => __( 'Display full post', 'omega' ),
				'excerpts' => __( 'Display post excerpts', 'omega' ),
			)
		);
		foreach ( (array) $archive_display as $value => $name ) 
			echo '<option value="' . esc_attr( $value ) . '"' . selected( omega_get_setting( 'content_archive' ), esc_attr( $value ), false ) . '>' . esc_html( $name ) . '</option>' . "\n";
		?>
		</select>
	</p>

	<div id="omega_more_link_scroll" <?php if ( 'full' != omega_get_setting( 'content_archive' )) echo 'class="hidden"';?>>
	<p>
		<label for="<?php echo omega_settings_field_id( 'more_link_scroll' ); ?>"><input type="checkbox" name="<?php echo omega_settings_field_name( 'more_link_scroll' ); ?>" id="<?php echo omega_settings_field_id( 'more_link_scroll' ); ?>" value="1" <?php checked( omega_get_setting( 'more_link_scroll' ) ); ?> />
		<?php _e( 'Prevent page scroll when clicking the More Link', 'omega' ); ?></label>
	</p>
	</div>

	<div id="omega_content_limit_setting" <?php if ( 'full' == omega_get_setting( 'content_archive' )) echo 'class="hidden"';?>>
		<p>
			<label for="<?php echo omega_settings_field_id( 'content_archive_limit' ); ?>"><?php _e( 'Limit content to', 'omega' ); ?>
			<input type="text" name="<?php echo omega_settings_field_name( 'content_archive_limit' ); ?>" id="<?php echo omega_settings_field_id( 'content_archive_limit' ); ?>" value="<?php echo esc_attr( omega_get_setting( 'content_archive_limit' ) ); ?>" size="3" />
			<?php _e( 'characters', 'omega' ); ?></label>
		</p>

		<p><span class="description"><?php _e( 'Select "Display post excerpts" will limit the text and strip all formatting from the text displayed. Set 0 characters will display the first 55 words (default)', 'omega' ); ?></span></p>
	</div>

	<p>
		<?php _e( 'More Text (if applicable):', 'omega' ); ?> <input type="text" name="<?php echo omega_settings_field_name( 'content_archive_more' ); ?>" id="<?php echo omega_settings_field_id( 'content_archive_more' ); ?>" value="<?php echo esc_attr( omega_get_setting( 'content_archive_more' ) ); ?>" size="25" />			
	</p>

	<p class="collapsed">
		<label for="<?php echo omega_settings_field_id( 'content_archive_thumbnail' ); ?>"><input type="checkbox" name="<?php echo omega_settings_field_name( 'content_archive_thumbnail' ); ?>" id="<?php echo omega_settings_field_id( 'content_archive_thumbnail' ); ?>" value="1" <?php checked( omega_get_setting( 'content_archive_thumbnail' ) ); ?> />
		<?php _e( 'Include the Featured Image?', 'omega' ); ?></label>
	</p>

	<p id="omega_image_size" <?php if (!omega_get_setting( 'content_archive_thumbnail' )) echo 'class="hidden"';?>>
		<label for="<?php echo omega_settings_field_id( 'image_size' ); ?>"><?php _e( 'Image Size:', 'omega' ); ?></label>
		<select name="<?php echo omega_settings_field_name( 'image_size' ); ?>" id="<?php echo omega_settings_field_id( 'image_size' ); ?>">
		<?php
		$sizes = omega_get_image_sizes();
		foreach ( (array) $sizes as $name => $size )
			echo '<option value="' . esc_attr( $name ) . '"' . selected( omega_get_setting( 'image_size' ), $name, FALSE ) . '>' . esc_html( $name ) . ' (' . absint( $size['width'] ) . ' &#x000D7; ' . absint( $size['height'] ) . ')</option>' . "\n";
		?>
		</select>
	</p>
	<p>
		<label for="<?php echo omega_settings_field_id( 'posts_nav' ); ?>"><?php _e( 'Select Post Navigation Format:', 'omega' ); ?></label>
		<select name="<?php echo omega_settings_field_name( 'posts_nav' ); ?>" id="<?php echo omega_settings_field_id( 'posts_nav' ); ?>">
			<option value="prev-next"<?php selected( 'prev-next', omega_get_setting( 'posts_nav' ) ); ?>><?php _e( 'Previous / Next', 'omega' ); ?></option>
			<option value="numeric"<?php selected( 'numeric', omega_get_setting( 'posts_nav' ) ); ?>><?php _e( 'Numeric', 'omega' ); ?></option>
		</select>
	</p>
	<p><span class="description"><?php _e( 'These options will affect any blog listings page, including archive, author, blog, category, search, and tag pages.', 'omega' ); ?></span></p>	
	<p>
		<label for="<?php echo omega_settings_field_id( 'single_nav' ); ?>"><input type="checkbox" name="<?php echo omega_settings_field_name( 'single_nav' ); ?>" id="<?php echo omega_settings_field_id( 'single_nav' ); ?>" value="1" <?php checked( omega_get_setting( 'single_nav' ) ); ?> />
		<?php _e( 'Disable single post navigation link?', 'omega' ); ?></label>
	</p>

<?php }


/**
 * Saves the scripts meta box settings by filtering the "sanitize_option_omega_theme_settings" hook.
 *
 * @since 0.3.0
 * @param array $settings Array of theme settings passed by the Settings API for validation.
 * @return array $settings
 */
function omega_theme_validate_archives( $settings ) {

	if ( !isset( $_POST['reset'] ) ) {
		$settings['content_archive_limit'] =  absint( $settings['content_archive_limit'] );
		$settings['content_archive_thumbnail'] =  absint( $settings['content_archive_thumbnail'] );
	}

	/* Return the theme settings. */
	return $settings;
}

/**
 * Contextual help content.
 */
function omega_theme_settings_archives_help() {

	$screen = get_current_screen();

	$archives_help =
		'<h3>' . __( 'Content Archives', 'omega' ) . '</h3>' .
		'<p>'  . __( 'You may change the site wide Content Archives options to control what displays in the site\'s Archives.', 'omega' ) . '</p>' .
		'<p>'  . __( 'Archives include any pages using the blog template, category pages, tag pages, date archive, author archives, and the latest posts if there is no custom home page.', 'omega' ) . '</p>' .
		'<p>'  . __( 'The first option allows you to display the full post or the post excerpt. The Display full post setting will display the entire post including HTML code up to the <!--more--> tag if used (this is HTML for the comment tag that is not displayed in the browser).', 'omega' ) . '</p>' .
		'<p>'  . __( 'The Display post excerpt setting will display the first 55 words of the post after also stripping any included HTML or the manual/custom excerpt added in the post edit screen.', 'omega' ) . '</p>' .
		'<p>'  . __( 'It may also be coupled with the second field "Limit content to [___] characters" to limit the content to a specific number of letters or spaces.', 'omega' ) . '</p>' .
		'<p>'  . __( 'The \'Include post image?\' setting allows you to show a thumbnail of the first attached image or currently set featured image.', 'omega' ) . '</p>' .
		'<p>'  . __( 'This option should not be used with the post content unless the content is limited to avoid duplicate images.', 'omega' ) . '</p>' .
		'<p>'  . __( 'The \'Image Size\' list is populated by the available image sizes defined in the theme.', 'omega' ) . '</p>' .
		'<p>'  . __( 'Post Navigation format allows you to select one of two navigation methods.', 'omega' ) . '</p>';
		'<p>'  . __( 'There is also a checkbox to disable previous & next navigation links on single post', 'omega' ) . '</p>';

	$screen->add_help_tab( array(
		'id'      => 'omega-settings' . '-archives',
		'title'   => __( 'Content Archives', 'omega' ),
		'content' => $archives_help,
	) );

}

?>