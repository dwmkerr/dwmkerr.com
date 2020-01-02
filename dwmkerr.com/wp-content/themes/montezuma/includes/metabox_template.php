<?php 

/* Use the admin_menu action to define the custom boxes */
add_action( 'add_meta_boxes', 'bfa_metabox_template' );

/* Use the save_post action to do something with the data entered */
add_action( 'save_post', 'bfa_metabox_template_save' );


function bfa_metabox_template() {

	// add_meta_box( $css-id, $title, $callback, $post_type, $context, $priority, $callback_args );
    add_meta_box( 'bfa_virtual_template', 'Virtual Template', 
                'bfa_metabox_template_admin', 'post', 'side' );
    add_meta_box( 'bfa_virtual_template', 'Virtual Template', 
                'bfa_metabox_template_admin', 'page', 'side' );
}
   
   
/* Prints the inner fields for the custom post/page section */
function bfa_metabox_template_admin() {

	global $montezuma, $post;
	wp_nonce_field( 'bfa_tpl', 'bfa_template_nonce' ); // Use nonce for verification
  	$thePostID = $post->ID;
	$post_id = get_post($thePostID);
	$bfa_virtual_template = get_post_meta( $post->ID, 'bfa_virtual_template', true );

	if( $bfa_virtual_template == 'hierarchy' ) 
		$defaultselected = " selected='selected'"; 
	else 
		$defaultselected = '';
	
	$screen = get_current_screen();
	?>
	<p>Choose Virtual Template for this <?php echo $screen->post_type; ?>:</p>
	<?php
	echo '<select name="bfa_virtual_template">
	<option value="hierarchy"' . $defaultselected . '>Best match based on WP Hierarchy &nbsp;</option>';
	
	$existing_templates = array();
	foreach( $montezuma as $key => $value ) {
		if( strpos( $key, 'maintemplate-' ) === 0 && ! in_array( $key, $existing_templates ) ) {
			$tpl = str_replace( substr( $key, 0, 13 ), '', $key );
			if( $bfa_virtual_template == $tpl ) 
				$selected = " selected='selected'"; 
			else 
				$selected = '';
			echo "<option value='$tpl'$selected>$tpl</option>";
		}
	}
	foreach( $existing_templates as $tpl ) 
		echo "<option value='$tpl'$selected>$tpl</option>";
	echo '</select>';
	
	if ( "page" == $screen->post_type ) {
		$physical_templates = get_page_templates(); 
		if( ! empty( $physical_templates ) ) 
			echo '
			<p style="border:solid 1px red;padding: 5px" id="bfa_change_page_attr_template"><strong>Page Attributes</strong> &raquo;  
			<strong>Template</strong> (see above) must be set to <code>Default Template</code> or 
			else it will take precedence over the "Virtual Template" setting:<br><br>
			<img src="' . get_template_directory_uri() . '/admin/images/pageattributes-template.png" />
			</p>';
	}
	
	?>
	<script>
	jQuery(document).ready(function() {
		jQuery('#bfa_change_page_attr_template').css( 'display', 'none');
		if( jQuery('select#page_template').val() != 'default' ) {
			jQuery('#bfa_change_page_attr_template').css( 'display', 'block');
			jQuery('select#page_template').css({'border':'solid 1px red'});
		}
		jQuery(document).on('change', 'select#page_template', function() {
			if( jQuery(this).val() == 'default' ) {
				jQuery('#bfa_change_page_attr_template').css( 'display', 'none');
				jQuery('select#page_template').css({'border':'none'});
			} else {
				jQuery('#bfa_change_page_attr_template').css( 'display', 'block');
				jQuery('select#page_template').css({'border':'solid 1px red'});
			}
		});
	});
	</script>
	<?php
	
}



/* When the post is saved, save our custom data */
function bfa_metabox_template_save( $post_id ) {

	// verify if this is an auto save routine. 
	// If it is our form has not been submitted, so we dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;

	$mynonce = isset( $_POST['bfa_template_nonce'] ) ? $_POST['bfa_template_nonce']: '';
	if ( ! wp_verify_nonce( $mynonce, 'bfa_tpl' ) ) 
		return $post_id;

	if ( 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}

	update_post_meta( $post_id, 'bfa_virtual_template', $_POST['bfa_virtual_template'] );
}


