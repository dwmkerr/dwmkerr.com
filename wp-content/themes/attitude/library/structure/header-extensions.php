<?php
/**
 * Adds header structures.
 *
 * @package 		Theme Horse
 * @subpackage 	Attitude
 * @since 			Attitude 1.0
 * @license 		http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link 			http://themehorse.com/themes/attitude
 */

/****************************************************************************************/

add_action( 'attitude_title', 'attitude_add_meta', 5 );
/**
 * Add meta tags.
 */ 
function attitude_add_meta() {
?>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php
}

/****************************************************************************************/

add_action( 'attitude_title', 'attitude_show_title', 10 );
/**
 * Showing the title in the browser tab.
 * 
 * @uses wp_title() Display the title on the browser tab.
 */
function attitude_show_title() {
?>
	<title>
		<?php
		/**
		 * Print the <title> tag based on what is being viewed.
		 */
		wp_title( '|', true, 'right' );
		?>
	</title>
<?php
}

add_filter( 'wp_title', 'attitude_filter_wp_title' );
/**
 * Modifying the Title
 *
 * Function tied to the wp_title filter hook.
 * @uses filter wp_title
 */
function attitude_filter_wp_title( $title ) {
	global $page, $paged;
	
	// Get the Site Name
   $site_name = get_bloginfo( 'name' );

   // Get the Site Description
   $site_description = get_bloginfo( 'description' );

   $filtered_title = ''; 

	// For Homepage or Frontpage
   if(  is_home() || is_front_page() ) {		
		$filtered_title .= $site_name;	
		if ( !empty( $site_description ) )  {
        	$filtered_title .= ' &#124; '. $site_description;
		}
   }
	elseif( is_feed() ) {
		$filtered_title = '';
	}
	else{	
		$filtered_title = $title . $site_name;
	}

	// Add a page number if necessary:
	if( $paged >= 2 || $page >= 2 ) {
		$filtered_title .= ' &#124; ' . sprintf( __( 'Page %s', 'attitude' ), max( $paged, $page ) );
	}
	
	// Return the modified title
   return $filtered_title;
}

/****************************************************************************************/

add_action( 'attitude_links', 'attitude_add_links', 10 );
/**
 * Adding link to stylesheet file
 *
 * @uses get_stylesheet_uri()
 */
function attitude_add_links() {
?>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />	
<?php
}

/****************************************************************************************/

// Load Favicon in Header Section
add_action( 'attitude_links', 'attitude_favicon', 15 );
// Load Favicon in Admin Section
add_action( 'admin_head', 'attitude_favicon' );
/**
 * Get the favicon Image from theme options
 * display favicon
 *
 * @uses set_transient and delete_transient 
 */
function attitude_favicon() {	
	
	$attitude_favicon = '';
	if( ( !$attitude_favicon = get_transient( 'attitude_favicon' ) ) ) {
		global $attitude_theme_options_settings;
      $options = $attitude_theme_options_settings;

		if ( "0" == $options[ 'disable_favicon' ] ) {
			if ( !empty( $options[ 'favicon' ] ) ) {
				$attitude_favicon .= '<link rel="shortcut icon" href="'.esc_url( $options[ 'favicon' ] ).'" type="image/x-icon" />';
			}
		}
		
	set_transient( 'attitude_favicon', $attitude_favicon, 86940 );	
	}	
	echo $attitude_favicon ;	
}

/****************************************************************************************/

// Load webpageicon in Header Section
add_action( 'attitude_links', 'attitude_webpageicon', 20 );
/**
 * Get the webpageicon Image from theme options
 * display webpageicon
 *
 * @uses set_transient and delete_transient 
 */
function attitude_webpageicon() {	
	
	$attitude_webpageicon = '';
	if( ( !$attitude_webpageicon = get_transient( 'attitude_webpageicon' ) ) ) {
		global $attitude_theme_options_settings;
      $options = $attitude_theme_options_settings;

		if ( "0" == $options[ 'disable_webpageicon' ] ) {
			if ( !empty( $options[ 'webpageicon' ] ) ) {
				$attitude_webpageicon .= '<link rel="apple-touch-icon-precomposed" href="'.esc_url( $options[ 'webpageicon' ] ).'" />';
			}
		}
		
	set_transient( 'attitude_webpageicon', $attitude_webpageicon, 86940 );	
	}	
	echo $attitude_webpageicon ;	
}

/****************************************************************************************/

add_action( 'attitude_header', 'attitude_headerdetails', 10 );
/**
 * Shows Header Part Content
 *
 * Shows the site logo, title, description, searchbar, social icons etc.
 */
function attitude_headerdetails() {	
?>
	<?php
		global $attitude_theme_options_settings;
   	$options = $attitude_theme_options_settings;

   	$elements = array();
		$elements = array( 	$options[ 'social_facebook' ], 
									$options[ 'social_twitter' ],
									$options[ 'social_googleplus' ],
									$options[ 'social_linkedin' ],
									$options[ 'social_pinterest' ],
									$options[ 'social_youtube' ],
									$options[ 'social_vimeo' ],
									$options[ 'social_flickr' ],
									$options[ 'social_tumblr' ],
									$options[ 'social_myspace' ],
									$options[ 'social_rss' ]
							 	);	

		$flag = 0;
		if( !empty( $elements ) ) {
			foreach( $elements as $option) {
				if( !empty( $option ) ) {
					$flag = 1;
				}
				else {
					$flag = 0;
				}
				if( 1 == $flag ) {
					break;
				}
			}
		}
	?>

	<div class="container clearfix">
		<div class="hgroup-wrap clearfix">
			<?php 
				if( 0 == $options[ 'hide_header_searchform' ] || 1 == $flag ) {
			?>
					<section class="hgroup-right">
						<?php attitude_socialnetworks( $flag ); ?>
						<?php if( 0 == $options[ 'hide_header_searchform' ] ) get_search_form(); ?>
					</section><!-- .hgroup-right -->	
			<?php
				}
			?>
				<hgroup id="site-logo" class="clearfix">
					<?php 
						if( $options[ 'header_show' ] != 'disable-both' && $options[ 'header_show' ] == 'header-text' ) {
						?>
							<h1 id="site-title"> 
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
									<?php bloginfo( 'name' ); ?>
								</a>
							</h1>
							<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
						<?php
						}
						elseif( $options[ 'header_show' ] != 'disable-both' && $options[ 'header_show' ] == 'header-logo' ) {
						?>
							<h1 id="site-title"> 
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
									<img src="<?php echo $options[ 'header_logo' ]; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
								</a>
							</h1>
						<?php
						}
						?>
					
				</hgroup><!-- #site-logo -->
			
		</div><!-- .hgroup-wrap -->
	</div><!-- .container -->	
	<?php $header_image = get_header_image();
			if( !empty( $header_image ) ) :?>
				<img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
			<?php endif; ?>	
	<?php
		if ( has_nav_menu( 'primary' ) ) { 
			$args = array(
				'theme_location'    => 'primary',
				'container'         => '',
				'items_wrap'        => '<ul class="root">%3$s</ul>' 
			);
			echo '<nav id="access" class="clearfix">
					<div class="container clearfix">';
				wp_nav_menu( $args );
			echo '</div><!-- .container -->
					</nav><!-- #access -->';
		}
		else {
			echo '<nav id="access" class="clearfix">
					<div class="container clearfix">';
				wp_page_menu( array( 'menu_class'  => 'root' ) );
			echo '</div><!-- .container -->
					</nav><!-- #access -->';
		}
	?> 		
		<?php	
		if( 'above-slider' == $options[ 'slogan_position' ] &&  ( is_home() || is_front_page() ) ) 
			if( function_exists( 'attitude_home_slogan' ) )
				attitude_home_slogan(); 

		if( is_home() || is_front_page() ) {
			if( "0" == $options[ 'disable_slider' ] ) {
				if( function_exists( 'attitude_pass_cycle_parameters' ) ) 
   				attitude_pass_cycle_parameters();
   			if( function_exists( 'attitude_featured_post_slider' ) ) 
   				attitude_featured_post_slider();
   		}
		}
		else { 
			if( ( '' != attitude_header_title() ) || function_exists( 'bcn_display_list' ) ) { 
		?>
			<div class="page-title-wrap">
	    		<div class="container clearfix">
	    			<?php
		    		if( function_exists( 'attitude_breadcrumb' ) )
						attitude_breadcrumb();
					?>
				   <h3 class="page-title"><?php echo attitude_header_title(); ?></h3><!-- .page-title -->
				</div>
	    	</div>
	   <?php
	   	}
		} 
		if( 'below-slider' == $options[ 'slogan_position' ] && ( is_home() || is_front_page() ) ) 
			if( function_exists( 'attitude_home_slogan' ) )
				attitude_home_slogan(); 

}

/****************************************************************************************/

if ( ! function_exists( 'attitude_socialnetworks' ) ) :
/**
 * This function for social links display on header
 *
 * Get links through Theme Options
 */
function attitude_socialnetworks( $flag ) {
	
	global $attitude_theme_options_settings;
   $options = $attitude_theme_options_settings;

	$attitude_socialnetworks = '';
	if ( ( !$attitude_socialnetworks = get_transient( 'attitude_socialnetworks' ) ) && ( 1 == $flag ) )  {
		
		$attitude_socialnetworks .='
			<div class="social-profiles clearfix">
				<ul>';

				$social_links = array(); 
				$social_links_name = array();
				$social_links_name = array( __( 'Facebook', 'attitude' ),
											__( 'Twitter', 'attitude' ),
											__( 'Google Plus', 'attitude' ),
											__( 'Pinterest', 'attitude' ),
											__( 'Youtube', 'attitude' ),
											__( 'Vimeo', 'attitude' ),
											__( 'LinkedIn', 'attitude' ),
											__( 'Flickr', 'attitude' ),
											__( 'Tumblr', 'attitude' ),
											__( 'Myspace', 'attitude' ),
											__( 'RSS', 'attitude' )
											);
				$social_links = array( 	'Facebook' 		=> 'social_facebook',
												'Twitter' 		=> 'social_twitter',
												'Google-Plus'	=> 'social_googleplus',
												'Pinterest' 	=> 'social_pinterest',
												'You-tube'		=> 'social_youtube',
												'Vimeo'			=> 'social_vimeo',
												'Linked'			=> 'social_linkedin',
												'Flickr'			=> 'social_flickr',
												'Tumblr'			=> 'social_tumblr',
												'My-Space'		=> 'social_myspace',
												'RSS'				=> 'social_rss'  
											);
				
				$i=0;
				foreach( $social_links as $key => $value ) {
					if ( !empty( $options[ $value ] ) ) {
						$attitude_socialnetworks .=
							'<li class="'.strtolower($key).'"><a href="'.esc_url( $options[ $value ] ).'" title="'.sprintf( esc_attr__( '%1$s on %2$s', 'attitude' ), get_bloginfo( 'name' ), $social_links_name[$i] ).'" target="_blank">'.get_bloginfo( 'name' ).' '.$social_links_name[$i].'</a></li>';
					}
					$i++;
				}		
		
				$attitude_socialnetworks .='
			</ul>
			</div><!-- .social-profiles -->';
		
		set_transient( 'attitude_socialnetworks', $attitude_socialnetworks, 86940 );	 
	}
	echo $attitude_socialnetworks;
}
endif;

/****************************************************************************************/

if ( ! function_exists( 'attitude_home_slogan' ) ) :
/**
 * Display Home Slogan.
 *
 * Function that enable/disable the home slogan1 and home slogan2.
 */
function attitude_home_slogan() {	
	global $attitude_theme_options_settings;
   $options = $attitude_theme_options_settings;
	
	$attitude_home_slogan = '';
	if( ( !$attitude_home_slogan = get_transient( 'attitude_home_slogan' ) ) && ( !empty( $options[ 'home_slogan1' ] ) || !empty( $options[ 'home_slogan2' ] ) ) ) {
      
		if ( "0" == $options[ 'disable_slogan' ] ) {
			$attitude_home_slogan .= '<section class="slogan-wrap clearfix"><div class="container"><div class="slogan">';
			if ( !empty( $options[ 'home_slogan1' ] ) ) {
				$attitude_home_slogan .= esc_html( $options[ 'home_slogan1' ] );
			}
			if ( !empty( $options[ 'home_slogan2' ] ) ) {
				$attitude_home_slogan .= '<span>'.esc_html( $options[ 'home_slogan2' ] ).'</span>';
			}
			$attitude_home_slogan .= '</div><!-- .slogan -->';
			if ( !empty( $options[ 'button_text' ] ) && !empty( $options[ 'redirect_button_link' ] ) ) {
				$attitude_home_slogan .= '<a class="view-work" href="'.esc_url( $options[ 'redirect_button_link' ] ).'" title="'.esc_attr( $options[ 'button_text' ] ).'">'.esc_html( $options[ 'button_text' ] ).'</a><!-- .view-work -->';
			}
			$attitude_home_slogan .= '</div><!-- .container --></section><!-- .slogan-wrap -->';
		}
		
	set_transient( 'attitude_home_slogan', $attitude_home_slogan, 86940 );	
	}	
	echo $attitude_home_slogan;
}
endif;

/****************************************************************************************/

if ( ! function_exists( 'attitude_featured_post_slider' ) ) :
/**
 * display featured post slider
 *
 * @uses set_transient and delete_transient
 */
function attitude_featured_post_slider() {	
	global $post;
		
	global $attitude_theme_options_settings;
   $options = $attitude_theme_options_settings;
	
	$attitude_featured_post_slider = '';
	if( ( !$attitude_featured_post_slider = get_transient( 'attitude_featured_post_slider' ) ) && !empty( $options[ 'featured_post_slider' ] ) ) {

		if( 'wide-layout' == $options[ 'site_layout' ] ) {
			$slider_size = 'slider-wide';
		}
		else {
			$slider_size = 'slider-narrow';
		}
		
		$attitude_featured_post_slider .= '
		<section class="featured-slider"><div class="slider-cycle">';
			$get_featured_posts = new WP_Query( array(
				'posts_per_page' 			=> $options[ 'slider_quantity' ],
				'post_type'					=> array( 'post', 'page' ),
				'post__in'		 			=> $options[ 'featured_post_slider' ],
				'orderby' 		 			=> 'post__in',
				'ignore_sticky_posts' 	=> 1 						// ignore sticky posts
			));
			$i=0; while ( $get_featured_posts->have_posts()) : $get_featured_posts->the_post(); $i++;
				$title_attribute = apply_filters( 'the_title', get_the_title( $post->ID ) );
				$excerpt = get_the_excerpt();
				if ( 1 == $i ) { $classes = "slides displayblock"; } else { $classes = "slides displaynone"; }
				$attitude_featured_post_slider .= '
				<div class="'.$classes.'">';
						if( has_post_thumbnail() ) {
	
							$attitude_featured_post_slider .= '<figure><a href="' . get_permalink() . '" title="'.the_title('','',false).'">';
	
							$attitude_featured_post_slider .= get_the_post_thumbnail( $post->ID, $slider_size, array( 'title' => esc_attr( $title_attribute ), 'alt' => esc_attr( $title_attribute ), 'class'	=> 'pngfix' ) ).'</a></figure>';
						}
						if( $title_attribute != '' || $excerpt !='' ) {
						$attitude_featured_post_slider .= '
							<article class="featured-text">';
							if( $title_attribute !='' ) {
									$attitude_featured_post_slider .= '<div class="featured-title"><a href="' . get_permalink() . '" title="'.the_title('','',false).'">'. get_the_title() . '</a></div><!-- .featured-title -->';
							}
							if( $excerpt !='' ) {								
								$attitude_featured_post_slider .= '<div class="featured-content">'.$excerpt.'</div><!-- .featured-content -->';
							}
						$attitude_featured_post_slider .= '
							</article><!-- .featured-text -->';
						}
				$attitude_featured_post_slider .= '
				</div><!-- .slides -->';
			endwhile; wp_reset_query();
		$attitude_featured_post_slider .= '</div>				
		<nav id="controllers" class="clearfix">
		</nav><!-- #controllers --></section><!-- .featured-slider -->';
			
	set_transient( 'attitude_featured_post_slider', $attitude_featured_post_slider, 86940 );
	}
	echo $attitude_featured_post_slider;	
}
endif;

/****************************************************************************************/

if ( ! function_exists( 'attitude_breadcrumb' ) ) :
/**
 * Display breadcrumb on header.
 *
 * If the page is home or front page, slider is displayed.
 * In other pages, breadcrumb will display if breadcrumb NavXT plugin exists.
 */
function attitude_breadcrumb() {
	if( function_exists( 'bcn_display' ) ) {
		echo '<div class="breadcrumb">';                
		bcn_display();               
		echo '</div> <!-- .breadcrumb -->'; 
	}   
}
endif;

/****************************************************************************************/

if ( ! function_exists( 'attitude_header_title' ) ) :
/**
 * Show the title in header
 *
 * @since Attitude 1.0
 */
function attitude_header_title() {
	if( is_archive() ) {
		$attitude_header_title = single_cat_title( '', FALSE );
	}
	elseif( is_404() ) {
		$attitude_header_title = __( 'Page NOT Found', 'attitude' );
	}
	elseif( is_search() ) {
		$attitude_header_title = __( 'Search Results', 'attitude' );
	}
	elseif( is_page_template()  ) {
		$attitude_header_title = get_the_title();
	}
	else {
		$attitude_header_title = '';
	}

	return $attitude_header_title;

}
endif;
?>