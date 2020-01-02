<?php 

add_filter( 'post_gallery', 'bfa_post_gallery_filter', 10, 2 );

/**
 * Replaces "dl/dt/dd" markup with "figure/figcaption", removes inline CSS
 *
 */
// http://wordpress.stackexchange.com/questions/43558/how-to-manually-fix-the-wordpress-gallery-code-using-php-in-functions-php
function bfa_post_gallery_filter( $output, $attr ) {
    global $post;

    static $instance = 0;
    $instance++;


    /**
     *  will remove this since we don't want an endless loop going on here
     */
    // Allow plugins/themes to override the default gallery template.
    //$output = apply_filters('post_gallery', '', $attr);

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post->ID,
        'itemtag'    => 'figure', // dl
        'icontag'    => 'dt',
        'captiontag' => 'figcaption', // dd - whole thing does not validate if caption missing. dl/dt needs to have dd, too as per W3C
        'columns'    => 3,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => ''
    ), $attr));

    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $include = preg_replace( '/[^0-9,]+/', '', $include );
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if ( empty($attachments) )
        return '';

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $columns = intval($columns);
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $gallery_style = $gallery_div = '';
    if ( apply_filters( 'use_default_gallery_style', true ) )
        /**
         * this is the css you want to remove
         *  #1 in question
         */
        /*
        $gallery_style = "
        <style type='text/css'>
            #{$selector} {
                margin: auto;
            }
            #{$selector} .gallery-item {
                float: {$float};
                margin-top: 10px;
                text-align: center;
                width: {$itemwidth}%;
            }
            #{$selector} img {
                border: 2px solid #cfcfcf;
            }
            #{$selector} .gallery-caption {
                margin-left: 0;
            }
        </style>
        <!-- see gallery_shortcode() in wp-includes/media.php -->";
        */
    $size_class = sanitize_html_class( $size );
    $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
    $output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
        $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

		  $link = str_replace( '</a>', '<span></span></a>', $link );
		  /*
        $output .= "<{$itemtag} class='gallery-item'>";
        $output .= "
            <{$icontag} class='gallery-icon'>
                $link
            </{$icontag}>";
			*/	
			/* no need for a class here, can style through parent. Also replace
			"icontag" dd element with span after item (see above), useful for inner shadow on image
			*/
			$output .= "<{$itemtag}>{$link}"; 
			
				
				
        /*
         * This is the caption part so i'll comment that out
         * #2 in question
         */
        /*
        if ( $captiontag && trim($attachment->post_excerpt) ) {
            $output .= "
                <{$captiontag} class='wp-caption-text gallery-caption'>
                " . wptexturize($attachment->post_excerpt) . "
                </{$captiontag}>";
        }*/
        $output .= "<span></span></{$itemtag}>";
		  
		  /*
        if ( $columns > 0 && ++$i % $columns == 0 )
            $output .= '<br style="clear: both" />';
			*/
    }

    /**
     * this is the extra br you want to remove so we change it to jus closing div tag
     * #3 in question
     */
    /*$output .= "
            <br style='clear: both;' />
        </div>\n";
     */

    $output .= "</div>\n";
    return $output;
}


