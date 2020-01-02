<?php 

/**
 *
 *
 *
 *
 *
 *
 */
function bfa_get_virtual_template() {

		global $montezuma;
		
		$object = get_queried_object();
		$templates = array();

		
		if( is_404() ) {
		
			$templates[] = '404';
		
		
		} elseif ( is_search() ) {
		
			$templates[] = 'search';
		
		
		} elseif( is_post_type_archive() ) {
		
			$templates[] = "taxonomy-" . $object->post_type;
			$templates[] = 'archive';
			
			
		} elseif( is_tax() ) {
		
			$templates[] = 'taxonomy-' . $object->taxonomy . '-' . $object->slug;
			$templates[] = 'taxonomy-' . $object->taxonomy;
			$templates[] = 'taxonomy';
			$templates[] = 'archive';
			
			
		} elseif( is_front_page() ) {
		
			$templates[] = 'front-page';
			
			if( get_option( 'show_on_front' ) == 'posts' ) {
				$templates[] = 'home'; 
				
			} elseif ( get_option( 'show_on_front' ) == 'page' ) {

				$frontpage_id = get_option( 'page_on_front' );
				
				$frontpage_virtualtpl = get_post_meta( $frontpage_id, 'bfa_virtual_template' );
				if( $frontpage_virtualtpl != 'hierarchy' ) {
					$templates[] = $frontpage_virtualtpl;
				}

				$templates[] = 'page-' . $object->post_name; // page-slug
				$templates[] = 'page-' . $object->ID; // page-id
				$templates[] = 'page';
			}
			
			
		} elseif( is_home() ) {
		
			$templates[] = 'home';
		
		
		} elseif( is_attachment() ) {
		
			global $posts;
			
			$type = explode('/', $posts[0]->post_mime_type);
					
			$templates[] = $type[0];
			$templates[] = $type[1];
			$templates[] = $type[0] . '_' . $type[1];
			$templates[] = 'attachment';
			$templates[] = 'single';	
		
		
		} elseif( is_single() ) {
			
			$virtualtpl = get_post_meta( $object->ID, 'bfa_virtual_template', TRUE );
			if( $virtualtpl != 'hierarchy' ) {
				$templates[] = $virtualtpl;
			}
			$templates[] = 'single-' . $object->post_type;
			$templates[] = 'single';		
		
		
		} elseif( is_page() ) {
		
			$template = get_page_template_slug();
			$pagename = get_query_var('pagename');

			if ( ! $pagename && $object->ID ) {
				// If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object
				$pagename = $object->post_name;
			}
					
			$virtualtpl = get_post_meta( $object->ID, 'bfa_virtual_template', TRUE );
			if( $virtualtpl != 'hierarchy' ) {
				$templates[] = $virtualtpl;
			}
		
			$templates[] = $template;
			
			if ( $pagename )
				$templates[] = 'page-' . $pagename;
			if ( $object->ID )
				$templates[] = 'page-' . $object->ID;
				
			$templates[] = 'page';
	
			
		} elseif( is_category() ) {
		
			$templates[] = 'category-' . $object->slug;
			$templates[] = 'category-' . $object->term_id;
			$templates[] = 'category';	
			$templates[] = 'archive';			
		
		
		} elseif( is_tag() ) {

			$templates[] = 'tag-' . $object->slug;
			$templates[] = 'tag-' . $object->term_id;
			$templates[] = 'tag';		
			$templates[] = 'archive';
		
		
		} elseif( is_author() ) {
			
			$templates[] = 'author-' . $object->user_nicename;
			$templates[] = 'author-' . $object->ID;
			$templates[] = 'author';	
			$templates[] = 'archive';			
		
		
		} elseif( is_date() ) {
		
			$templates[] = 'date';
			$templates[] = 'archive';
				
		
		
		} elseif( is_archive() ) {
		
			$post_type = get_query_var( 'post_type' );

			if ( $post_type )
				$templates[] = 'archive-' . $post_type;
			$templates[] = 'archive';				
		
		
		} elseif( is_comments_popup() ) {
		
			$templates[] = 'comments-popup';			
		
		
		} elseif( is_paged() ) {
		
			$templates[] = 'paged';	
		
		}
		
		// index as last fallback
		$templates[] = 'index';
		
		
		// find best match
		foreach( $templates as $tpl ) {
			if( isset( $montezuma['maintemplate-' . $tpl] ) && $montezuma['maintemplate-' . $tpl] != '' ) 
			return $tpl;
		}
		
		return;

}

