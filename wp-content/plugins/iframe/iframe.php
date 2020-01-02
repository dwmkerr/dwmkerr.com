<?php
/*
Plugin Name: iframe
Plugin URI: http://wordpress.org/plugins/iframe/
Description: [iframe src="http://www.youtube.com/embed/A3PDXmYoF5U" width="100%" height="480"] shortcode
Version: 2.7
Author: webvitaly
Author URI: http://web-profile.com.ua/wordpress/plugins/
License: GPLv2 or later
*/


if ( ! function_exists( 'iframe_unqprfx_embed_shortcode' ) ) :

	function iframe_unqprfx_enqueue_script() {
		wp_enqueue_script( 'jquery' );
	}
	add_action( 'wp_enqueue_scripts', 'iframe_unqprfx_enqueue_script' );
	
	function iframe_unqprfx_embed_shortcode( $atts, $content = null ) {
		$defaults = array(
			'src' => 'http://www.youtube.com/embed/A3PDXmYoF5U',
			'width' => '100%',
			'height' => '480',
			'scrolling' => 'no',
			'class' => 'iframe-class',
			'frameborder' => '0'
		);

		foreach ( $defaults as $default => $value ) { // add defaults
			if ( ! @array_key_exists( $default, $atts ) ) { // hide warning with "@" when no params at all
				$atts[$default] = $value;
			}
		}

		$src_cut = substr( $atts["src"], 0, 35 ); // special case for google maps
		if( strpos( $src_cut, 'maps.google' ) ){
			$atts["src"] .= '&output=embed';
		}

		// get_params_from_url
		if( isset( $atts["get_params_from_url"] ) && ( $atts["get_params_from_url"] == '1' || $atts["get_params_from_url"] == 1 || $atts["get_params_from_url"] == 'true' ) ) {
			if( $_GET != NULL ){
				if( strpos( $atts["src"], '?' ) ){ // if we already have '?' and GET params
					$encode_string = '&';
				}else{
					$encode_string = '?';
				}
				foreach( $_GET as $key => $value ){
					$encode_string .= $key.'='.$value.'&';
				}
			}
			$atts["src"] .= $encode_string;
		}

		$html = '';
		if( isset( $atts["same_height_as"] ) ){
			$same_height_as = $atts["same_height_as"];
		}else{
			$same_height_as = '';
		}
		
		if( $same_height_as != '' ){
			$atts["same_height_as"] = '';
			if( $same_height_as != 'content' ){ // we are setting the height of the iframe like as target element
				if( $same_height_as == 'document' || $same_height_as == 'window' ){ // remove quotes for window or document selectors
					$target_selector = $same_height_as;
				}else{
					$target_selector = '"' . $same_height_as . '"';
				}
				$html .= '
					<script>
					jQuery(function($){
						var target_height = $(' . $target_selector . ').height();
						$("iframe.' . $atts["class"] . '").height(target_height);
						//alert(target_height);
					});
					</script>
				';
			}else{ // set the actual height of the iframe (show all content of the iframe without scroll)
				$html .= '
					<script>
					jQuery(function($){
						$("iframe.' . $atts["class"] . '").bind("load", function() {
							var embed_height = $(this).contents().find("body").height();
							$(this).height(embed_height);
						});
					});
					</script>
				';
			}
		}
        $html .= "\n".'<!-- iframe plugin v.2.7 wordpress.org/plugins/iframe/ -->'."\n";
		$html .= '<iframe';
        foreach( $atts as $attr => $value ) {
			if( $attr != 'same_height_as' ){ // remove some attributes
				if( $value != '' ) { // adding all attributes
					$html .= ' ' . $attr . '="' . $value . '"';
				} else { // adding empty attributes
					$html .= ' ' . $attr;
				}
			}
		}
		$html .= '></iframe>';
		return $html;
	}
	add_shortcode( 'iframe', 'iframe_unqprfx_embed_shortcode' );
	

	function iframe_unqprfx_plugin_meta( $links, $file ) { // add 'Plugin page' and 'Donate' links to plugin meta row
		if ( strpos( $file, 'iframe.php' ) !== false ) {
			$links = array_merge( $links, array( '<a href="http://web-profile.com.ua/wordpress/plugins/iframe/" title="Plugin page">' . __('Iframe') . '</a>' ) );
			$links = array_merge( $links, array( '<a href="http://web-profile.com.ua/donate/" title="Support the development">' . __('Donate') . '</a>' ) );
		}
		return $links;
	}
	add_filter( 'plugin_row_meta', 'iframe_unqprfx_plugin_meta', 10, 2 );
	
endif; // end of if(function_exists('iframe_unqprfx_embed_shortcode'))