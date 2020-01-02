<?php

if ( is_admin() ) {
	/* Hook the omega child themes page function to 'admin_menu'. */
	add_action( 'admin_menu', 'omega_child_themes_page_init', 11 );

	function omega_child_themes_page_init() {

		$page = add_theme_page( 
			sprintf( esc_html__( 'Omega Child Themes', 'omega' ) ),	// Settings page name.
			esc_html__( 'Omega Child Themes', 'omega' ), 			// Menu item name.
			'edit_theme_options', 						// Required capability.
			'omega-child-themes', 									// Screen name.
			'omega_child_themes_list' );							// Callback function.

		add_action ( 'omega_child_theme', 'child_theme_button' );
	}

	function omega_child_themes_list() {

		// Set template directory uri
	    $screenshot_dir = get_template_directory_uri() . '/inc/images/';

		$omegachilds = array(
			 	array( 'name' => 'Alpha',
			 		   'url' => 'http://themehall.com/alpha-first-omega-child-theme',
			 		   'img' => 'alpha.png'),
			 	array( 'name' => 'Beta',
			 		   'url' => 'http://themehall.com/beta-second-omega-child-theme',
			 		   'img' => 'beta.png'),
			 	array( 'name' => 'Omega Child',
			 		   'url' => 'http://themehall.com/product/omega-child',
			 		   'img' => 'omega-child.png'),
			 	array( 'name' => 'Church',
			 		   'url' => 'http://themehall.com/free-responsive-church-theme-wordpress',
			 		   'img' => 'church.png'),
			 	array( 'name' => 'Custom',
			 		   'url' => 'http://themehall.com/custom-free-omega-child-theme-wordpress',
			 		   'img' => 'custom.png'),
			 	array( 'name' => 'Mobile',
			 		   'url' => 'http://themehall.com/mobile-theme-mobile-friendly-start',
			 		   'img' => 'mobile.png')
			 );
		?>
	 	<div class="wrap">

			<?php screen_icon(); ?>
			<h2>
				<?php printf( __( 'Omega Child Themes', 'Omega' ) ); ?>
			</h2>

			<p>
				<?php printf( __( 'Personalize your Omega powered site with one of Omega child themes below', 'Omega' ) ); ?>
			</p>

			<div id="availablethemes">
			<?php
			$currenttheme = wp_get_theme();

			foreach ( $omegachilds as $omegachild) {
				if ($omegachild['name'] != $currenttheme->name) {
					echo '<div class="available-theme">
						<a class="screenshot" target="_blank" href="' . $omegachild['url'] .'">
							<img alt="'.$omegachild['name'].'" src="'. $screenshot_dir.$omegachild['img'] .'">
						</a>
					</div>';
				}
			}
			?>			
				<div class="available-theme">
					<a class="screenshot">
						<img alt="More to Come" src="<?php echo $screenshot_dir . 'more.png';?>">
					</a>
				</div>		
			</div>

		</div>
		<?php
	}

	function child_theme_button() {
		?>
		<a href="<?php echo admin_url( 'themes.php?page=omega-child-themes' ); ?>" class="add-new-h2"><?php esc_html_e( 'Child Themes', 'omega' ); ?></a>
		<?php
	}
}


/**
 * Add Child Theme menu item to Admin Bar.
 */

function omega_child_theme_adminbar() {

	global $wp_admin_bar;

	if ( !is_super_admin() || !is_admin_bar_showing() )
        return;
    
	$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id' => 'omega-child-themes',
			'title' => __( 'Omega Child Themes', 'omega' ),
			'href' => admin_url( 'themes.php?page=omega-child-themes' )
		));
}
add_action( 'wp_before_admin_bar_render', 'omega_child_theme_adminbar' );