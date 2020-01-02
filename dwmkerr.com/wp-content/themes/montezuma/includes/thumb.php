<?php 
if ( ! function_exists( 'bfa_delete_thumb_transient' ) ) :
function bfa_delete_thumb_transient( $post_id ) {
	delete_transient( 'bfa_thumb_transient' );
}
endif;
add_action( 'save_post', 'bfa_delete_thumb_transient' );



if ( ! function_exists( 'bfa_thumb' ) ) :
function bfa_thumb( $width, $height, $crop = false, $before = '', $after = '', $link = 'permalink' ) { // TODO: Add parameter $link = 'fullsize' to link to full size image 
	global $post, $upload_dir, $bfa_thumb_transient;
	#$bfa_thumb_transient = get_transient( 'bfa_thumb_transient' );
	if( ! is_writable( $upload_dir['basedir'] ) ) {
		echo "WP Upload Directory not writable! Check file and directory permissions"; 
		return;
	}

	// Unique thumb per size & post
	$id = get_the_id() . '_' . $width . '_' . $height . '_' . ( $crop === FALSE ? '0' : '1' ); 

	if( array_key_exists( $id, $bfa_thumb_transient ) AND strpos( $bfa_thumb_transient[$id], 'src=""' ) === FALSE ) 
		$this_thumb = $bfa_thumb_transient[$id];
	else 
		$this_thumb = FALSE;
		
	if ( $this_thumb === FALSE ) {
		$this_thumb = ''; 
		$hasthumb = FALSE; 
		$hassrc = FALSE; 
		$has_thumbnail = FALSE;
		
		if( '' != ( $thumb = get_post_thumbnail_id() ) ) 
			$hasthumb = TRUE; 
		elseif ( FALSE !== ( $thumb = bfa_get_first_attachment_id() ) ) 
			$hasthumb = TRUE; 
		elseif ( FALSE !== ( $thumb = bfa_get_first_unattached_gallery_img_id() ) ) 
			$hasthumb = TRUE; 
		// if local image not added with WP uploader but added as manual HTML link
		elseif( FALSE !== ( $thumb = bfa_get_first_img_src() ) ) 
			$hassrc = TRUE; 
		
		if( $hasthumb === TRUE ) { 
			$thumbimage = bfa_vt_resize( $thumb,'' , $width, $height, $crop ); 
			$has_thumbnail = TRUE; 
		} elseif( $hassrc === TRUE ) { 
			$thumbimage = bfa_vt_resize( '', $thumb , $width, $height, $crop ); 
			$has_thumbnail = TRUE; 
		}	
		
		if( $has_thumbnail === TRUE ) { 
			$this_thumb .= '<img src="' . $thumbimage['url'] . '" width="' . $thumbimage['width'] . '" height="' . $thumbimage['height'] . '" alt="' . $post->post_title . '"/>';
		} 
		#$bfa_thumb_transient = get_transient( 'bfa_thumb_transient' );
		$bfa_thumb_transient[$id] = $this_thumb;
		set_transient( 'bfa_thumb_transient', $bfa_thumb_transient, 60*60*1 );
	} 
	if( trim( $this_thumb ) != '' AND $this_thumb != FALSE ) {
		if( $link == 'permalink' ) 
			$this_thumb = '<a href="'.get_permalink( $id ).'">'.$this_thumb.'</a>';	
		echo $before . $this_thumb . $after;
	}
}	
endif;


if ( ! function_exists( 'bfa_get_first_attachment_id' ) ) :
function bfa_get_first_attachment_id() {
	global $post; 
	$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $post->ID ); 
	$attachments = get_posts($args);
	if( $attachments ) 
		return $attachments[0]->ID;
	return FALSE;
}
endif;


// For galleries with images not attached to current post: [gallery ids="xxx,xxx,xxx,xxx,xxx,xxx,xxx"]
if ( ! function_exists( 'bfa_get_first_unattached_gallery_img_id' ) ) :
function bfa_get_first_unattached_gallery_img_id( $args = array() ) {
	global $post; 
	preg_match_all( '|\[gallery \s*ids\s*=\s*"\s*(.*?)\s*,|i', $post->post_content, $matches );
	foreach( $matches[1] as $match ) {
		if ( isset( $match ) ) 
			return $match;
	}
	return false;
}
endif;


if ( ! function_exists( 'bfa_get_first_img_src' ) ) :
function bfa_get_first_img_src( $args = array() ) {
	global $post; $site_url = site_url();
	preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post->post_content, $matches );
	foreach( $matches[1] as $match ) {
		if ( isset( $match ) && strpos( $match, $site_url ) !== FALSE ) 
			return $match;
	}
	return false;
}
endif;


if ( ! function_exists( 'bfa_vt_resize' ) ) :
function bfa_vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) { // Based on: vt_resize - Resize images dynamically using wp built in functions - Victor Teixeira 
	if ( $attach_id ) { // is an attachment, we have the ID
		$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
		$file_path = get_attached_file( $attach_id );
	} else if ( $img_url ) { // not an attachment, use image url
		$file_path = parse_url( $img_url );
		$file_path = str_replace( '//', '/', $_SERVER['DOCUMENT_ROOT'] . $file_path['path']);
		$orig_size = getimagesize( $file_path );
		$image_src[0] = $img_url; 
		$image_src[1] = $orig_size[0]; 
		$image_src[2] = $orig_size[1];
	}
	$file_info = pathinfo( $file_path );
	$extension = '.'. $file_info['extension'];
	$no_ext_path = $file_info['dirname'] . '/' . $file_info['filename']; // image path without the extension
	
	#$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
	$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . '-' . ( $crop === FALSE ? '0' : '1' ) . $extension;
	
	if ( $image_src[1] > $width || $image_src[2] > $height ) { // if file size larger than target size.
	
		// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
		if ( file_exists( $cropped_img_path ) ) {
			$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
			$vt_image = array ( 
				'url' => $cropped_img_url, 
				'width' => $width, 
				'height' => $height, 
				#'final_image' =>  $final_image, 
				'image_url' => $img_url
			);
			return $vt_image;
		}
		
		// $crop = false
		if ( $crop === false ) {
			$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height ); // calculate the size proportionaly
			
			#$resized_img_path = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;		
			$resized_img_path = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . '-' . ( $crop === FALSE ? '0' : '1' ) . $extension;	
			
			if ( file_exists( $resized_img_path ) ) { // checking if the file already exists
				$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
				$vt_image = array ( 
					'url' => $resized_img_url, 
					'width' => $proportional_size[0], 
					'height' => $proportional_size[1], 
					#'final_image' =>  $final_image, 
					'image_url' => $img_url
				);
				return $vt_image;
			}
		}
		
		// no cache files - let's finally resize it
		$image = wp_get_image_editor( $file_path ); // wp_get_image_editor since WP 3.5
		if ( ! is_wp_error( $image ) ) {
			 $image->resize( $width, $height, $crop );
			 $image->set_quality( 30 );
			 $final_image = $image->save( $cropped_img_path );
		
			$img_url = str_replace( basename( $image_src[0] ), basename( $final_image['path'] ), $image_src[0] );
			/* Sample output: final_image=
			Array ( 
				[path] => C:\UniServer_5.3.10\www\wordpress351/wp-content/uploads/2012/11/AmazingFlash_size1.png 
				[file] => AmazingFlash_size1.png 
				[width] => 440 
				[height] => 260 
				[mime-type] => image/png ) 
			*/

			// resized output
			$vt_image = array ( 
				'url' => $img_url, 
				'width' => $final_image['width'], 
				'height' => $final_image['height'], 
				'final_image' =>  $final_image, 
				'image_url' => $img_url
			);
			return $vt_image;
		}
	}
	// default output - without resizing
	$vt_image = array ( 
		'url' => $image_src[0], 
		'width' => $image_src[1], 
		'height' => $image_src[2], 
		#'final_image' =>  $final_image, 
		'image_url' => $img_url
	);
	return $vt_image;
}
endif;
