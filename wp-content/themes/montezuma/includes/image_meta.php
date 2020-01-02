<?php 

function bfa_image_size() {
	$meta = wp_get_attachment_metadata();
	echo $meta['width']. '&times;' . $meta['height'];
}


function bfa_image_meta( $args = '' ) {

	$defaults = array(
		'keys' => '',
		'before' => '', 
		'after' => '',
		'item_before' => '', 
		'item_after' => '',
		'item_sep' => ' &middot; ',
		'key_before' => '',
		'key_after' => ': ',
		'value_before' => '',
		'value_after' => '',
		'display_empty' => FALSE	
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );
	
	$meta = wp_get_attachment_metadata();
	
	$string_array = array();
	
	// All keys, alphabetically sorted, as provided by wp_get_attachment_metadata()
	if( $keys == '' ) {
		$array_keys = array_keys( $meta['image_meta'] );		
	// Only keys specificed in parameter:
	} else {
		$array_keys = array_map( 'trim', explode( ',', $keys ) );
	}

	foreach( $array_keys as $key ) {
	
		$value = $meta['image_meta'][$key];

		if( $display_empty === TRUE || ( $value != '' && $value != '0' ) ) {
		
			if( $key == 'created_timestamp' )
				// Transform timestamp into readable date, based on default WP date/time settings:
				$value = date( get_option('date_format') . ' - ' . get_option('time_format'), $value );
				
			// Prettify key
			$key = ucwords( str_replace( '_', ' ', $key ) );
			$key = $key == 'Iso' ? 'ISO' : $key;
			
			
			$key = str_replace( 
				array(
					'Aperture',
					'Credit',
					'Camera',
					'Caption',
					'Created Timestamp',
					'Copyright',
					'Focal Length',
					'ISO',
					'Shutter Speed',
					'Title'
				),
				array(
					__( 'Aperture', 'montezuma' ),
					__( 'Credit', 'montezuma' ),
					__( 'Camera', 'montezuma' ),
					__( 'Caption', 'montezuma' ),
					__( 'Timestamp', 'montezuma' ),
					__( 'Copyright', 'montezuma' ),
					__( 'Focal Length', 'montezuma' ),
					__( 'ISO', 'montezuma' ),
					__( 'Shutter Speed', 'montezuma' ),
					__( 'Title', 'montezuma' )
				),		
				$key
			);
			
			
			// Glue it together
			$string = $item_before 
						. $key_before 
						. $key 
						. $key_after 
						. $value_before 
						. $value 
						. $value_after
						. $item_after;
						
			$string_array[] = $string;
		}	
	}
	
	$final_string = '';
	
	// Glue together with item separator
	if( ! empty( $string_array ) )
		$final_string = implode( $item_sep, $string_array );
		
	// Wrap into parent container
	if( $final_string != '' )
		$final_string = $before . $final_string . $after;
	
	echo $final_string;
}

