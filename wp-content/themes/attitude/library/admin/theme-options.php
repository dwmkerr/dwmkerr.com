<?php
/**
 * Attitude Theme Options
 *
 * Contains all the function related to theme options.
 *
 * @package Theme Horse
 * @subpackage Attitude
 * @since Attitude 1.0
 */

/****************************************************************************************/

add_action( 'admin_enqueue_scripts', 'attitude_jquery_cookie' );
/**
 * Register jquery cookie javascript file.
 *
 * jquery cookie used for remembering admin tabs, and potential future features... so let's register it early
 *
 * @uses wp_register_script
 */
function attitude_jquery_cookie() {
   wp_register_script( 'jquery-cookie', ATTITUDE_ADMIN_JS_URL . '/jquery.cookie.min.js', array( 'jquery' ) );
}

/****************************************************************************************/

add_action( 'admin_print_scripts-appearance_page_theme_options', 'attitude_admin_scripts' );
/**
 * Enqueuing some scripts.
 *
 * @uses wp_enqueue_script to register javascripts.
 * @uses wp_enqueue_script to add javascripts to WordPress generated pages.
 */
function attitude_admin_scripts() {
   wp_enqueue_script( 'attitude_admin', ATTITUDE_ADMIN_JS_URL . '/admin.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-cookie', 'jquery-ui-sortable', 'jquery-ui-draggable' ) );
   wp_enqueue_script( 'attitude_toggle_effect', ATTITUDE_ADMIN_JS_URL . '/toggle-effect.js' );
   wp_enqueue_script( 'attitude_image_upload', ATTITUDE_ADMIN_JS_URL . '/add-image-script.js', array( 'jquery','media-upload', 'thickbox' ) );
}

/****************************************************************************************/

add_action( 'admin_print_styles-appearance_page_theme_options', 'attitude_admin_styles' );
/**
 * Enqueuing some styles.
 *
 * @uses wp_enqueue_style to register stylesheets.
 * @uses wp_enqueue_style to add styles.
 */
function attitude_admin_styles() {
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_style( 'attitude_admin_style', ATTITUDE_ADMIN_CSS_URL. '/admin.css' );
}

/****************************************************************************************/

add_action( 'admin_print_styles-appearance_page_theme_options', 'attitude_social_script', 100);
/**
 * Facebook, twitter script hooked at head
 * 
 * @useage for Facebook, Twitter and Print Script 
 * @Use add_action to display the Script on header
 */
function attitude_social_script() 
{ ?>
	<!-- Facebook script --> 
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=284802028306078";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    
    <!-- Twitter script -->   
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    
    <!-- Print Script -->
	<script src="http://cdn.printfriendly.com/printfriendly.js" type="text/javascript"></script>
<?php     
}

/****************************************************************************************/

add_action( 'admin_menu', 'attitude_options_menu' );
/**
 * Create sub-menu page.
 *
 * @uses add_theme_page to add sub-menu under the Appearance top level menu.
 */
function attitude_options_menu() {
    
	add_theme_page( 
		__( 'Theme Options', 'attitude' ),           // Name of page
		__( 'Theme Options', 'attitude' ),           // Label in menu
		'edit_theme_options',                           // Capability required
		'theme_options',                                // Menu slug, used to uniquely identify the page
		'attitude_theme_options_do_page'             // Function that renders the options page
	);

}

/****************************************************************************************/

add_action( 'admin_init', 'attitude_register_settings' );
/**
 * Register options and validation callbacks
 *
 * @uses register_setting
 */
function attitude_register_settings() {
   register_setting( 'attitude_theme_options', 'attitude_theme_options', 'attitude_theme_options_validate' );
}

/****************************************************************************************/

/**
 * Render Attitude Theme Options page
 */
function attitude_theme_options_do_page() {
?>
	   
	<div class="them_option_block clearfix">
		<div class="theme_option_title"><h2><?php _e( 'Theme Options by', 'attitude' ); ?></h2></div><div class="theme_option_link"><a href="<?php echo esc_url( __( 'http://themehorse.com/', 'attitude' ) ); ?>" title="<?php esc_attr_e( 'Theme Horse', 'attitude' ); ?>" target="_blank"><img src="<?php echo ATTITUDE_ADMIN_IMAGES_URL . '/theme-horse.png'; ?>" alt="'<?php _e( 'Theme Horse', 'attitude' ); ?>" /></a> </div>
		<div style="margin: 20px 20px 20px 0px; float:right; font-size: 13px; font-weight: bold;">
			<?php _e( 'Confused about something? See', 'attitude' ); ?> 
			<a href="<?php echo esc_url( 'http://themehorse.com/theme-instruction/attitude/' ); ?>" title="<?php esc_attr_e( 'Attitude Theme Instructions', 'attitude' ); ?>" target="_blank"><?php _e( 'Theme Instructions', 'attitude' ); ?></a> &nbsp; | &nbsp; 
			<a class="support" href="<?php echo esc_url( 'http://themehorse.com/support-forum/' ); ?>" title="<?php esc_attr_e( 'Support Forum', 'attitude' ); ?>" target="_blank"><?php _e( 'Support Forum', 'attitude' ); ?></a> &nbsp; | &nbsp;
			<a class="demo" href="<?php echo esc_url( 'http://themehorse.com/preview/attitude/' ); ?>" title="<?php esc_attr_e( 'Attitude Demo', 'attitude' ); ?>" target="_blank"><?php _e( 'View Demo', 'attitude' ); ?></a>
		</div>
	</div><br/><br/><br/>
	<div class="donate-info">
		<strong><?php _e( 'Want to add bunch of additional features? Upgrade to Pro version!', 'attitude' ); ?></strong><br/>
		<a title="<?php esc_attr_e( 'Upgrade to Pro', 'attitude' ); ?>" href="<?php echo esc_url( 'http://themehorse.com/themes/attitude-pro' ); ?>" target="_blank" class="upgrade"><?php _e( 'Upgrade to Pro', 'attitude' ); ?></a>
		<a title="<?php esc_attr_e( 'Donate', 'attitude' ); ?>" href="<?php echo esc_url( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=BRLCCUGP2ACYN' ); ?>" target="_blank" class="donate"><?php _e( 'Donate', 'attitude' ); ?></a>
		<a title="<?php esc_attr_e( 'Review Clean Retina', 'attitude' ); ?>" href="<?php echo esc_url( 'http://wordpress.org/support/view/theme-reviews/attitude' ); ?>" target="_blank" class="review"><?php _e( 'Rate Attitude', 'attitude' ); ?></a>
		<div id="social-share">
	    	<div class="fb-like" data-href="https://www.facebook.com/themehorse" data-send="false" data-layout="button_count" data-width="90" data-show-faces="true"></div>
	    	<div class="tw-follow" ><a href="<?php echo esc_url( 'http://twitter.com/Theme_Horse' ); ?>" class="twitter-follow-button" data-button="grey" data-text-color="#FFFFFF" data-link-color="#00AEFF" data-width="150px" data-show-screen-name="true" data-show-count="false"></a></div>
		</div>	
	</div>
   <div id="themehorse" class="wrap">
        
      <form method="post" action="options.php">
			<?php
				settings_fields( 'attitude_theme_options' );
				global $attitude_theme_options_settings;
				$options = $attitude_theme_options_settings;             
			?>        
	            
			<?php if( isset( $_GET [ 'settings-updated' ] ) && 'true' == $_GET[ 'settings-updated' ] ): ?>
					<div class="updated" id="message">
					   <p><strong><?php _e( 'Settings saved.', 'attitude' );?></strong></p>
					</div>
			<?php endif; ?> 
            
         <div id="attitude_tabs">
				<ul id="main-navigation" class="tab-navigation">
					<li><a href="#designoptions"><?php _e( 'Design Options', 'attitude' );?></a></li>
					<li><a href="#advancedoptions"><?php _e( 'Advance Options', 'attitude' );?></a></li>
					<li><a href="#featuredpostslider"><?php _e( 'Featured Post/Page Slider', 'attitude' );?></a></li>
					<li><a href="#sociallinks"><?php _e( 'Social Links', 'attitude' );?></a></li>
					<li><a href="#webmastertools"><?php _e( 'Webmaster Tools', 'attitude' );?></a></li>
				</ul><!-- .tab-navigation #main-navigation -->
                   
				<!-- Option for Design Options -->
				<div id="designoptions">
					<div class="option-container">
						<h3 class="option-toggle"><a href="#"><?php _e( 'Custom Header', 'attitude' ); ?></a></h3>
						<div class="option-content inside">
							<table class="form-table">
								<tbody>
									<tr>                            
										<th scope="row"><label for="header_logo"><?php _e( 'Header Logo', 'attitude' ); ?></label></th>
										<td>
										   <input class="upload" size="65" type="text" id="header_logo" name="attitude_theme_options[header_logo]" value="<?php echo esc_url( $options [ 'header_logo' ] ); ?>" />
										   <input class="upload-button button" name="image-add" type="button" value="<?php esc_attr_e( 'Change Header Logo', 'attitude' ); ?>" />
										</td>
									</tr>
									<tr>
										<th scope="row"><?php _e( 'Preview', 'attitude' ); ?></th>
										<td> 
										   <?php
										       echo '<img src="'.esc_url( $options[ 'header_logo' ] ).'" alt="'.__( 'Header Logo', 'attitude' ).'" />';
										   ?>
										</td>
									</tr>
									<tr>
										<th scope="row"><label><?php _e( 'Show', 'attitude' ); ?></label></th>
										<td>
											<input type="radio" name="attitude_theme_options[header_show]" id="header-logo" <?php checked($options['header_show'], 'header-logo') ?> value="header-logo"  />
											<?php _e( 'Header Logo Only', 'attitude' ); ?></br>
											
											<input type="radio" name="attitude_theme_options[header_show]" id="header-text" <?php checked($options['header_show'], 'header-text') ?> value="header-text"  />
											<?php _e( 'Header Text Only', 'attitude' ); ?></br>

											<input type="radio" name="attitude_theme_options[header_show]" id="header-text" <?php checked($options['header_show'], 'disable-both') ?> value="disable-both"  />
											<?php _e( 'Disable', 'attitude' ); ?></br>
										</td>
									</tr>
									<tr>
										<th>
											<?php _e( 'Need to replace Header Image?', 'attitude' ); ?>
										</th>
										<td>
											<?php printf( __('<a class="button" href="%s">Click here</a>', 'attitude' ), admin_url('themes.php?page=custom-header')); ?>
										</td>
									</tr>
									<tr>                            
										<th scope="row"><?php _e( 'Hide Searchform from Header', 'attitude' ); ?></th>
										<input type='hidden' value='0' name='attitude_theme_options[hide_header_searchform]'>
										<td><input type="checkbox" id="headerlogo" name="attitude_theme_options[hide_header_searchform]" value="1" <?php checked( '1', $options['hide_header_searchform'] ); ?> /> <?php _e('Check to hide', 'attitude'); ?></td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p>
						</div><!-- .option-content -->
					</div><!-- .option-container -->

					<div class="option-container">
						<h3 class="option-toggle"><a href="#"><?php _e( 'Fav Icon Options', 'attitude' ); ?></a></h3>
						<div class="option-content inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th scope="row"><label for="disable_favicon"><?php _e( 'Disable Favicon', 'attitude' ); ?></label></th>
										<input type='hidden' value='0' name='attitude_theme_options[disable_favicon]'>
										<td><input type="checkbox" id="disable_favicon" name="attitude_theme_options[disable_favicon]" value="1" <?php checked( '1', $options['disable_favicon'] ); ?> /> <?php _e('Check to disable', 'attitude'); ?></td>
									</tr>
									<tr>                            
									<th scope="row"><label for="fav_icon_url"><?php _e( 'Fav Icon URL', 'attitude' ); ?></label></th>
										<td>
										   <input class="upload" size="65" type="text" id="fav_icon_url" name="attitude_theme_options[favicon]" value="<?php echo esc_url( $options [ 'favicon' ] ); ?>" />
										   <input class="upload-button button" name="image-add" type="button" value="<?php esc_attr_e( 'Change Fav Icon', 'attitude' ); ?>" />
										</td>
									</tr>
									<tr>
										<th scope="row"><?php _e( 'Preview', 'attitude' ); ?></th>
										<td> 
										   <?php
										       echo '<img src="'.esc_url( $options[ 'favicon' ] ).'" alt="'.__( 'favicon', 'attitude' ).'" />';
										   ?>
										</td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p> 
						</div><!-- .option-content -->
					</div><!-- .option-container -->

					<div class="option-container">
						<h3 class="option-toggle"><a href="#"><?php _e( 'Web Clip Icon Options', 'attitude' ); ?></a></h3>
						<div class="option-content inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th scope="row"><label for="disable_webpageicon"><?php _e( 'Disable Web Clip Icon', 'attitude' ); ?></label></th>
										<input type='hidden' value='0' name='attitude_theme_options[disable_webpageicon]'>
										<td><input type="checkbox" id="disable_webpageicon" name="attitude_theme_options[disable_webpageicon]" value="1" <?php checked( '1', $options['disable_webpageicon'] ); ?> /> <?php _e('Check to disable', 'attitude'); ?></td>
									</tr>
									<tr>                            
									<th scope="row"><label for="webpageicon_icon_url"><?php _e( 'Web Clip Icon URL', 'attitude' ); ?></label></th>
										<td>
										   <input class="upload" size="65" type="text" id="webpageicon_icon_url" name="attitude_theme_options[webpageicon]" value="<?php echo esc_url( $options [ 'webpageicon' ] ); ?>" />
										   <input class="upload-button button" name="image-add" type="button" value="<?php esc_attr_e( 'Change Web Clip Icon', 'attitude' ); ?>" />
										</td>
									</tr>
									<tr>
										<th scope="row"><?php _e( 'Preview', 'attitude' ); ?></th>
										<td> 
										   <?php
										       echo '<img src="'.esc_url( $options[ 'webpageicon' ] ).'" alt="'.__( 'webpage icon', 'attitude' ).'" />';
										   ?>
										</td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p> 
						</div><!-- .option-content -->
					</div><!-- .option-container -->

					<div class="option-container">
						<h3 class="option-toggle"><a href="#"><?php _e( 'Site Layout Options', 'attitude' ); ?></a></h3>
						<div class="option-content inside">
							<table class="form-table">  
								<tbody>
									<tr>
										<th scope="row"><label><?php _e( 'Site Layout', 'attitude' ); ?></label>
											<p><small><?php _e( 'This change is reflected in whole website', 'attitude' ); ?></small></p>
										</th>
										<td>
											<label title="narrow-layout" class="box" style="margin-right: 18px"><img src="<?php echo ATTITUDE_ADMIN_IMAGES_URL; ?>/one-column.png" alt="Content-Sidebar" /><br />
											<input type="radio" name="attitude_theme_options[site_layout]" id="narrow-layout" <?php checked($options['site_layout'], 'narrow-layout') ?> value="narrow-layout"  />
											<?php _e( 'Narrow Layout', 'attitude' ); ?>
											</label>

											<label title="wide-layout" class="box" style="margin-right: 18px"><img src="<?php echo ATTITUDE_ADMIN_IMAGES_URL; ?>/no-sidebar-fullwidth.png" alt="Content-Sidebar" /><br />
											<input type="radio" name="attitude_theme_options[site_layout]" id="wide-layout" <?php checked($options['site_layout'], 'wide-layout') ?> value="wide-layout"  />
											<?php _e( 'Wide Layout', 'attitude' ); ?>
											</label>                                         
										</td>
									</tr> 
								</tbody>
							</table>
							<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p> 
						</div><!-- .option-content -->
					</div><!-- .option-container -->

					<div class="option-container">
						<h3 class="option-toggle"><a href="#"><?php _e( 'Default Layout Options', 'attitude' ); ?></a></h3>
						<div class="option-content inside">
							<table class="form-table">  
								<tbody>
									<tr>
										<th scope="row"><label><?php _e( 'Default Layout', 'attitude' ); ?></label></th>
										<td>
											<label title="no-sidebar" class="box" style="margin-right: 18px"><img src="<?php echo ATTITUDE_ADMIN_IMAGES_URL; ?>/no-sidebar.png" alt="Content-Sidebar" /><br />
											<input type="radio" name="attitude_theme_options[default_layout]" id="no-sidebar" <?php checked($options['default_layout'], 'no-sidebar') ?> value="no-sidebar"  />
											<?php _e( 'No Sidebar', 'attitude' ); ?>
											</label>
											<label title="no-sidebar-full-width" class="box" style="margin-right: 18px"><img src="<?php echo ATTITUDE_ADMIN_IMAGES_URL; ?>/no-sidebar-fullwidth.png" alt="Content-Sidebar" /><br />
											<input type="radio" name="attitude_theme_options[default_layout]" id="no-sidebar-full-width" <?php checked($options['default_layout'], 'no-sidebar-full-width') ?> value="no-sidebar-full-width"  />
											<?php _e( 'No Sidebar, Full Width', 'attitude' ); ?>
											</label>
											<label title="no-sidebar-one-column" class="box" style="margin-right: 18px"><img src="<?php echo ATTITUDE_ADMIN_IMAGES_URL; ?>/one-column.png" alt="Content-Sidebar" /><br />
											<input type="radio" name="attitude_theme_options[default_layout]" id="no-sidebar-one-column" <?php checked($options['default_layout'], 'no-sidebar-one-column') ?> value="no-sidebar-one-column"  />
											<?php _e( 'No Sidebar, One Column', 'attitude' ); ?>
											</label>
											<label title="left-Sidebar" class="box" style="margin-right: 18px"><img src="<?php echo ATTITUDE_ADMIN_IMAGES_URL; ?>/left-sidebar.png" alt="Content-Sidebar" /><br />
											<input type="radio" name="attitude_theme_options[default_layout]" id="left-sidebar" <?php checked($options['default_layout'], 'left-sidebar') ?> value="left-sidebar"  />
											<?php _e( 'Left Sidebar', 'attitude' ); ?>
											</label>                                            
											<label title="right-sidebar" class="box" style="margin-right: 18px"><img src="<?php echo ATTITUDE_ADMIN_IMAGES_URL; ?>/right-sidebar.png" alt="Content-Sidebar" /><br />
											<input type="radio" name="attitude_theme_options[default_layout]" id="right-sidebar" <?php checked($options['default_layout'], 'right-sidebar') ?> value="right-sidebar"  />
											<?php _e( 'Right Sidebar', 'attitude' ); ?>
											</label>                                            
										</td>
									</tr>  
									<?php if( "1" == $options[ 'reset_layout' ] ) { $options[ 'reset_layout' ] = "0"; } ?>
									<tr>                            
									<th scope="row"><label for="reset_layout"><?php _e( 'Reset Layout', 'attitude' ); ?></th>
									<input type='hidden' value='0' name='attitude_theme_options[reset_layout]'>
									<td>
									<input type="checkbox" id="reset_layout" name="attitude_theme_options[reset_layout]" value="1" <?php checked( '1', $options['reset_layout'] ); ?> /> <?php _e('Check to reset', 'attitude'); ?>
									</td>
									</tr>  
								</tbody>
							</table>
							<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p> 
						</div><!-- .option-content -->
					</div><!-- .option-container -->				

					<div class="option-container">
						<h3 class="option-toggle"><a href="#"><?php _e( 'Custom Background', 'attitude' ); ?></a></h3>
						<div class="option-content inside">
							<table class="form-table">
								<tbody> 
									<tr>
										<th>
											<?php _e( 'Need to replace default background?', 'attitude' ); ?>
										</th>
										<td style="padding-bottom: 64px;">
											<?php printf(__('<a class="button" href="%s">Click here</a>', 'attitude'), admin_url('themes.php?page=custom-background')); ?>
										</td>
										<td style="padding-bottom: 20px;">
											<p><small><?php _e( 'Note: The custom background change will be reflected in the background if the site layout is set to be narrow layout instead of the wide layout.', 'attitude' ); ?></small></p>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- .option-content -->
					</div><!-- .option-container -->
            
					<div class="option-container">
						<h3 class="option-toggle"><a href="#"><?php _e( 'Custom CSS', 'attitude' ); ?></a></h3>
						<div class="option-content inside"> 
							<table class="form-table">  
								<tbody>       
									<tr>
										<th scope="row"><label for="custom-css"><?php _e( 'Enter your custom CSS styles.', 'attitude' ); ?></label>										
											<p><small><?php _e( 'This CSS will overwrite the CSS of style.css file.', 'attitude' ); ?></small></p>
										</th>
										<td>
										<textarea name="attitude_theme_options[custom_css]" id="custom-css" cols="90" rows="12"><?php echo esc_attr( $options[ 'custom_css' ] ); ?></textarea>
										</td>
									</tr>

									<tr>
										<th scope="row"><?php _e( 'CSS Tutorial from W3Schools.', 'attitude' ); ?></th>
										<td>
										<a class="button" href="<?php echo esc_url( __( 'http://www.w3schools.com/css/default.asp','attitude' ) ); ?>" title="<?php esc_attr_e( 'CSS Tutorial', 'attitude' ); ?>" target="_blank"><?php _e( 'Click Here to Read', 'attitude' );?></a>
										</td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p> 
						</div><!-- .option-content -->
					</div><!-- .option-container -->                    

				</div> <!-- #designoptions -->  


				<!-- Options for Theme Options -->
				<div id="advancedoptions">
					<div class="option-container">
						<h3 class="option-toggle"><a href="#"><?php _e( 'Home Slogan Options', 'attitude' ); ?></a></h3>
						<div class="option-content inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th scope="row">
											<label for="slogan"><?php _e( 'Disable Slogan Part', 'attitude' ); ?></label>
										</th>
										<input type='hidden' value='0' name='attitude_theme_options[disable_slogan]'>
										<td><input type="checkbox" id="slogan" name="attitude_theme_options[disable_slogan]" value="1" <?php checked( '1', $options['disable_slogan'] ); ?> /> <?php _e('Check to disable', 'attitude'); ?></td>
									</tr>
									<tr>
                            <th scope="row"><label><?php _e( 'Slogan Position', 'attitude' ); ?></label></th>
                            <td>
                                <label title="above-slider" class="box">
                                <input type="radio" name="attitude_theme_options[slogan_position]" id="above-slider" <?php checked($options['slogan_position'], 'above-slider') ?> value="above-slider"  />
                                <?php _e( 'Above Slider', 'attitude' ); ?>
                                </label>
                                <label title="below-slider" class="box">
                                <input type="radio" name="attitude_theme_options[slogan_position]" id="below-slider" <?php checked($options['slogan_position'], 'below-slider') ?> value="below-slider"  />
                                 <?php _e( 'Below Slider', 'attitude' ); ?>
                                </label>                               
                            </td>
                        </tr>
									<tr>
										<th scope="row"><label for="slogan_1"><?php _e( 'Home Page Slogan1', 'attitude' ); ?></label>
											<p><small><?php _e( 'The appropriate length of the slogan is around 10 words.', 'attitude' ); ?></small></p>
										</th>
										<td>
											<textarea class="textarea input-bg" id="slogan_1" name="attitude_theme_options[home_slogan1]" cols="60" rows="3"><?php echo esc_textarea( $options[ 'home_slogan1' ] ); ?></textarea>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="slogan_2"><?php _e( 'Home Page Slogan2', 'attitude' ); ?></label>
											<p><small><?php _e( 'The appropriate length of the slogan is around 10 words.', 'attitude' ); ?></small></p>
										</th>
										<td>
											<textarea class="textarea input-bg" id="slogan_2" name="attitude_theme_options[home_slogan2]" cols="60" rows="3"><?php echo esc_textarea( $options[ 'home_slogan2' ] ); ?></textarea>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="button_text"><?php _e( 'Redirect Button Text', 'attitude' ); ?></label>
											<p><small><?php _e( 'Text to show in Button', 'attitude' ); ?></small></p>
										</th>
										<td><input type="text" id="button_text" size="45" name="attitude_theme_options[button_text]" value="<?php echo esc_attr( $options[ 'button_text' ] ); ?>" />
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="button_text"><?php _e( 'Redirect Button Link', 'attitude' ); ?></label>
											<p><small><?php _e( 'Link this button to show your special work, portfolio', 'attitude' ); ?></small></p>
										</th>
										<td><input type="text" id="button_text" size="90" name="attitude_theme_options[redirect_button_link]" value="<?php echo esc_url( $options[ 'redirect_button_link' ] ); ?>" />
										</td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p>
						</div><!-- .option-content -->
					</div><!-- .option-container -->

					<div class="option-container">
						<h3 class="option-toggle"><a href="#"><?php _e( 'Feed Redirect', 'attitude' ); ?></a></h3>
						<div class="option-content inside">
							<table class="form-table">  
								<tbody>
									<tr>
										<th scope="row">
											<label for="feed-redirect"><?php _e( 'Feed Redirect URL', 'attitude' ); ?></label>
										</th>
										<td><input type="text" id="feed-redirect" size="70" name="attitude_theme_options[feed_url]" value="<?php echo esc_attr( $options[ 'feed_url' ] ); ?>" />
										</td>
									</tr>  
								</tbody>
							</table>
							<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p> 
						</div><!-- .option-content -->
					</div><!-- .option-container -->

               <div class="option-container">
                  <h3 class="option-toggle"><a href="#"><?php _e( 'Homepage / Frontpage Category Setting', 'attitude' ); ?></a></h3>
                  <div class="option-content inside">
                  	<table class="form-table">
                     	<tbody>
                        	<tr>
                           	<th scope="row">
                              	<label for="frontpage_posts_cats"><?php _e( 'Front page posts categories:', 'attitude' ); ?></label>
                                 <p>
                                 	<small><?php _e( 'Only posts that belong to the categories selected here will be displayed on the front page.', 'attitude' ); ?></small>
                                 </p>
                              </th>
                              <td>
	                              <select name="attitude_theme_options[front_page_category][]" id="frontpage_posts_cats" multiple="multiple" class="select-multiple">
	                              	<option value="0" <?php if ( empty( $options['front_page_category'] ) ) { selected( true, true ); } ?>><?php _e( '--Disabled--', 'attitude' ); ?></option>
                                 	<?php /* Get the list of categories */ 
                                 	if( empty( $options[ 'front_page_category' ] ) ) {
                                    	$options[ 'front_page_category' ] = array();
                                  	}
                                  	$categories = get_categories();
                                  	foreach ( $categories as $category) :?>
	                                 	<option value="<?php echo $category->cat_ID; ?>" <?php if ( in_array( $category->cat_ID, $options['front_page_category'] ) ) {echo 'selected="selected"';}?>><?php echo $category->cat_name; ?></option>
	                                 <?php endforeach; ?>
	                              </select><br />
                                 <span class="description"><?php _e( 'You may select multiple categories by holding down the CTRL key.', 'attitude' ); ?></span>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                     <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p> 
                  </div><!-- .option-content -->
              	</div><!-- .option-container -->   
             
				</div> <!-- #advancedoptions --> 

				<!-- Option for Featured Post Slier -->
				<div id="featuredpostslider">
					<!-- Option for More Slider Options -->
					<div class="option-container">
						<h3 class="option-toggle"><a href="#"><?php _e( 'Slider Options', 'attitude' ); ?></a></h3>
						<div class="option-content inside">
							<table class="form-table">
								<tr>                            
									<th scope="row"><?php _e( 'Disable Slider', 'attitude' ); ?></th>
									<input type='hidden' value='0' name='attitude_theme_options[disable_slider]'>
									<td><input type="checkbox" id="headerlogo" name="attitude_theme_options[disable_slider]" value="1" <?php checked( '1', $options['disable_slider'] ); ?> /> <?php _e('Check to disable', 'attitude'); ?></td>
								</tr>							                        
								<tr>
									<th scope="row"><?php _e( 'Number of Slides', 'attitude' ); ?></th>
									<td><input type="text" name="attitude_theme_options[slider_quantity]" value="<?php echo intval( $options[ 'slider_quantity' ] ); ?>" size="2" /></td>
								</tr>
								<tr>
									<th>
									<label for="attitude_cycle_style"><?php _e( 'Transition Effect:', 'attitude' ); ?></label>
									</th>
									<td>
										<select id="attitude_cycle_style" name="attitude_theme_options[transition_effect]">
											<?php 
												$transition_effects = array();
												$transition_effects = array( 	'fade',
																						'wipe',
																						'scrollUp',
																						'scrollDown',
																						'scrollLeft',
																						'scrollRight',
																						'blindX',
																						'blindY',
																						'blindZ',
																						'cover',
																						'shuffle'
																			);
										foreach( $transition_effects as $effect ) {
											?>
											<option value="<?php echo $effect; ?>" <?php selected( $effect, $options['transition_effect']); ?>><?php printf( __( '%s', 'attitude' ), $effect ); ?></option>
											<?php 
										}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php _e( 'Transition Delay', 'attitude' ); ?></th>
									<td>
										<input type="text" name="attitude_theme_options[transition_delay]" value="<?php echo $options[ 'transition_delay' ]; ?>" size="2" />
										<span class="description"><?php _e( 'second(s)', 'attitude' ); ?></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php _e( 'Transition Length', 'attitude' ); ?></th>
									<td>
										<input type="text" name="attitude_theme_options[transition_duration]" value="<?php echo $options[ 'transition_duration' ]; ?>" size="2" />
										<span class="description"><?php _e( 'second(s)', 'attitude' ); ?></span>
									</td>
								</tr>
							</table>
							<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p> 
						</div><!-- .option-content -->
					</div><!-- .option-container -->

					<div class="option-container">
						<h3 class="option-toggle"><a href="#"><?php _e( 'Featured Post/Page Slider Options', 'attitude' ); ?></a></h3>
						<div class="option-content inside">
							<table class="form-table">
								<tr>                            
									<th scope="row"><?php _e( 'Exclude Slider post from Homepage posts?', 'attitude' ); ?></th>
									<input type='hidden' value='0' name='attitude_theme_options[exclude_slider_post]'>
									<td><input type="checkbox" id="headerlogo" name="attitude_theme_options[exclude_slider_post]" value="1" <?php checked( '1', $options['exclude_slider_post'] ); ?> /> <?php _e('Check to exclude', 'attitude'); ?></td>
								</tr>
								<tbody class="sortable">
									<?php for ( $i = 1; $i <= $options[ 'slider_quantity' ]; $i++ ): ?>
									<tr>
										<th scope="row"><label class="handle"><?php _e( 'Featured Slider Post/Page #', 'attitude' ); ?><span class="count"><?php echo absint( $i ); ?></span></label></th>
										<td><input type="text" name="attitude_theme_options[featured_post_slider][<?php echo absint( $i ); ?>]" value="<?php if( array_key_exists( 'featured_post_slider', $options ) && array_key_exists( $i, $options[ 'featured_post_slider' ] ) ) echo absint( $options[ 'featured_post_slider' ][ $i ] ); ?>" />
										<a href="<?php bloginfo ( 'url' );?>/wp-admin/post.php?post=<?php if( array_key_exists ( 'featured_post_slider', $options ) && array_key_exists ( $i, $options[ 'featured_post_slider' ] ) ) echo absint( $options[ 'featured_post_slider' ][ $i ] ); ?>&action=edit" class="button" title="<?php esc_attr_e('Click Here To Edit'); ?>" target="_blank"><?php _e( 'Click Here To Edit', 'attitude' ); ?></a>
										</td>
									</tr>                           
									<?php endfor; ?>
								</tbody>
							</table>
							<p><?php _e( '<strong>Following are the steps on how to use the featured slider.</strong><br />* Create Post, Add featured image to the Post.<br />* Add all the Post ID that you want to use in the featured slider. <br /> &nbsp;(You can now see the Posts\' respective ID in the All Posts\' table in last column.)<br />* Featured Slider will show featured images, Title and excerpt of the respected added post\'s IDs.', 'attitude' ); ?> </p>
							<p><?php _e( '<strong>Note:</strong> You can now add Pages ID too. (You can now see the Pages\' respective ID in the All Pages\' table in last column.) .', 'attitude' ); ?> </p>
							<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p> 
						</div><!-- .option-content -->
					</div><!-- .option-container -->

				</div> <!-- #featuredpostslider -->

				<!-- Option for Design Settings -->
				<div id="sociallinks">
					<?php 
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
					?>
					<table class="form-table">
						<tbody>
						<?php
						$i = 0;
						foreach( $social_links as $key => $value ) {
						?>
							<tr>
								<th scope="row" style="padding: 0px;"><h4><?php printf( __( '%s', 'attitude' ), $social_links_name[ $i ] ); ?></h4></th>
								<td><input type="text" size="45" name="attitude_theme_options[<?php echo $value; ?>]" value="<?php echo esc_url( $options[$value] ); ?>" />
								</td>
							</tr>
						<?php
						$i++;
						}
						?>
						</tbody>
					</table>                                    
	            <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p>


				</div> <!-- #sociallinks --> 

				<!-- Option for Design Settings -->
				<div id="webmastertools">        
					<div class="option-container">
						<h3 class="option-toggle"><a href="#"><?php _e( 'Analytics', 'attitude' ); ?></a></h3>
						<div class="option-content inside">
							<table class="form-table">  
								<tbody>       
									<tr>
										<th scope="row"><?php _e( 'Code to display on Header', 'attitude' ); ?></th>
										<td>
										<textarea name="attitude_theme_options[analytic_header]" id="analytics" rows="7" cols="80" ><?php echo esc_html( $options[ 'analytic_header' ] ); ?></textarea>
										</td>
									</tr>
									<tr>
										<td></td><td><?php _e('Note: Enter your custom header script.', 'attitude' ); ?></td>
									</tr>
									<tr>
										<th scope="row"><?php _e('Code to display on Footer', 'attitude' ); ?></th>
										<td>
										<textarea name="attitude_theme_options[analytic_footer]" id="analytics" rows="7" cols="80" ><?php echo esc_html( $options[ 'analytic_footer' ] ); ?></textarea>

										</td>
									</tr>
									<tr>
										<td></td><td><?php _e( 'Note: Enter your custom footer script.', 'attitude' ); ?></td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save All Changes', 'attitude' ); ?>" /></p> 
						</div><!-- .option-content -->
					</div><!-- .option-container -->

				</div> <!-- #webmastertools -->   
                        
         </div><!-- #attitude_tabs -->
            
      </form>
        
   </div><!-- .wrap -->
<?php
}

/****************************************************************************************/

/**
 * Validate all theme options values
 * 
 * @uses esc_url_raw, absint, esc_textarea, sanitize_text_field, attitude_invalidate_caches
 */
function attitude_theme_options_validate( $options ) {
	global $attitude_theme_options_settings, $attitude_theme_options_defaults;
	$input_validated = $attitude_theme_options_settings;
	$input = array();
	$input = $options;

	if ( isset( $input[ 'header_logo' ] ) ) {
		$input_validated[ 'header_logo' ] = esc_url_raw( $input[ 'header_logo' ] );
	}

	if( isset( $input[ 'header_show' ] ) ) {
		$input_validated[ 'header_show' ] = $input[ 'header_show' ];
	}

   if ( isset( $options[ 'hide_header_searchform' ] ) ) {
		$input_validated[ 'hide_header_searchform' ] = $input[ 'hide_header_searchform' ];
	}
    
	if ( isset( $options[ 'disable_slogan' ] ) ) {
		$input_validated[ 'disable_slogan' ] = $input[ 'disable_slogan' ];
	}

	if( isset( $options[ 'home_slogan1' ] ) ) {
		$input_validated[ 'home_slogan1' ] = sanitize_text_field( $input[ 'home_slogan1' ] );
	}

	if( isset( $options[ 'home_slogan2' ] ) ) {
		$input_validated[ 'home_slogan2' ] = sanitize_text_field( $input[ 'home_slogan2' ] );
	}

	if( isset( $input[ 'slogan_position' ] ) ) {
		$input_validated[ 'slogan_position' ] = $input[ 'slogan_position' ];
	}	

	if( isset( $options[ 'button_text' ] ) ) {
		$input_validated[ 'button_text' ] = sanitize_text_field( $input[ 'button_text' ] );
	}

	if( isset( $options[ 'redirect_button_link' ] ) ) {
		$input_validated[ 'redirect_button_link' ] = esc_url_raw( $input[ 'redirect_button_link' ] );
	}
        
	if ( isset( $input[ 'favicon' ] ) ) {
		$input_validated[ 'favicon' ] = esc_url_raw( $input[ 'favicon' ] );
	}

	if ( isset( $input['disable_favicon'] ) ) {
		$input_validated[ 'disable_favicon' ] = $input[ 'disable_favicon' ];
	}

	if ( isset( $input[ 'webpageicon' ] ) ) {
		$input_validated[ 'webpageicon' ] = esc_url_raw( $input[ 'webpageicon' ] );
	}

	if ( isset( $input['disable_webpageicon'] ) ) {
		$input_validated[ 'disable_webpageicon' ] = $input[ 'disable_webpageicon' ];
	}

	//Site Layout
	if( isset( $input[ 'site_layout' ] ) ) {
		$input_validated[ 'site_layout' ] = $input[ 'site_layout' ];
	}

   // Front page posts categories
	if( isset( $input['front_page_category' ] ) ) {
		$input_validated['front_page_category'] = $input['front_page_category'];
	}
    
	// Data Validation for Featured Slider
	if( isset( $input[ 'disable_slider' ] ) ) {
		$input_validated[ 'disable_slider' ] = $input[ 'disable_slider' ];
	}

	if ( isset( $input[ 'slider_quantity' ] ) ) {
		$input_validated[ 'slider_quantity' ] = absint( $input[ 'slider_quantity' ] ) ? $input [ 'slider_quantity' ] : 4;
	}
	if ( isset( $input['exclude_slider_post'] ) ) {
		$input_validated[ 'exclude_slider_post' ] = $input[ 'exclude_slider_post' ];	

	}
	if ( isset( $input[ 'featured_post_slider' ] ) ) {
		$input_validated[ 'featured_post_slider' ] = array();
	}   
	if( isset( $input[ 'slider_quantity' ] ) )   
	for ( $i = 1; $i <= $input [ 'slider_quantity' ]; $i++ ) {
		if ( intval( $input[ 'featured_post_slider' ][ $i ] ) ) {
			$input_validated[ 'featured_post_slider' ][ $i ] = absint($input[ 'featured_post_slider' ][ $i ] );
		}
	}
    
   // data validation for transition effect
	if( isset( $input[ 'transition_effect' ] ) ) {
		$input_validated['transition_effect'] = wp_filter_nohtml_kses( $input['transition_effect'] );
	}

	// data validation for transition delay
	if ( isset( $input[ 'transition_delay' ] ) && is_numeric( $input[ 'transition_delay' ] ) ) {
		$input_validated[ 'transition_delay' ] = $input[ 'transition_delay' ];
	}

	// data validation for transition length
	if ( isset( $input[ 'transition_duration' ] ) && is_numeric( $input[ 'transition_duration' ] ) ) {
		$input_validated[ 'transition_duration' ] = $input[ 'transition_duration' ];
	}
    
   // data validation for Social Icons
	if( isset( $input[ 'social_facebook' ] ) ) {
		$input_validated[ 'social_facebook' ] = esc_url_raw( $input[ 'social_facebook' ] );
	}
	if( isset( $input[ 'social_twitter' ] ) ) {
		$input_validated[ 'social_twitter' ] = esc_url_raw( $input[ 'social_twitter' ] );
	}
	if( isset( $input[ 'social_googleplus' ] ) ) {
		$input_validated[ 'social_googleplus' ] = esc_url_raw( $input[ 'social_googleplus' ] );
	}
	if( isset( $input[ 'social_pinterest' ] ) ) {
		$input_validated[ 'social_pinterest' ] = esc_url_raw( $input[ 'social_pinterest' ] );
	}   
	if( isset( $input[ 'social_youtube' ] ) ) {
		$input_validated[ 'social_youtube' ] = esc_url_raw( $input[ 'social_youtube' ] );
	}
	if( isset( $input[ 'social_vimeo' ] ) ) {
		$input_validated[ 'social_vimeo' ] = esc_url_raw( $input[ 'social_vimeo' ] );
	}   
	if( isset( $input[ 'social_linkedin' ] ) ) {
		$input_validated[ 'social_linkedin' ] = esc_url_raw( $input[ 'social_linkedin' ] );
	}
	if( isset( $input[ 'social_flickr' ] ) ) {
		$input_validated[ 'social_flickr' ] = esc_url_raw( $input[ 'social_flickr' ] );
	}
	if( isset( $input[ 'social_tumblr' ] ) ) {
		$input_validated[ 'social_tumblr' ] = esc_url_raw( $input[ 'social_tumblr' ] );
	}   
	if( isset( $input[ 'social_myspace' ] ) ) {
		$input_validated[ 'social_myspace' ] = esc_url_raw( $input[ 'social_myspace' ] );
	}  
	if( isset( $input[ 'social_rss' ] ) ) {
		$input_validated[ 'social_rss' ] = esc_url_raw( $input[ 'social_rss' ] );
	}   
    
	//Custom CSS Style Validation
	if ( isset( $input['custom_css'] ) ) {
		$input_validated['custom_css'] = wp_kses_stripslashes($input['custom_css']);
	}
	   
	if( isset( $input[ 'analytic_header' ] ) ) {
		$input_validated[ 'analytic_header' ] = wp_kses_stripslashes( $input[ 'analytic_header' ] );
	}
	if( isset( $input[ 'analytic_footer' ] ) ) {
		$input_validated[ 'analytic_footer' ] = wp_kses_stripslashes( $input[ 'analytic_footer' ] );    
	}       
    
	// Layout settings verification
	if( isset( $input[ 'reset_layout' ] ) ) {
		$input_validated[ 'reset_layout' ] = $input[ 'reset_layout' ];
	}
	if( 0 == $input_validated[ 'reset_layout' ] ) {
		if( isset( $input[ 'default_layout' ] ) ) {
			$input_validated[ 'default_layout' ] = $input[ 'default_layout' ];
		}
	}
	else {
		$input_validated['default_layout'] = $attitude_theme_options_defaults[ 'default_layout' ];
	}

	//Feed Redirect
	$input_validated['feed_url'] = esc_url_raw($input['feed_url']);
    
	//Clearing the theme option cache
	if( function_exists( 'attitude_themeoption_invalidate_caches' ) ) attitude_themeoption_invalidate_caches();
    
   return $input_validated;
}


/**
 * Clearing the cache if any changes in Admin Theme Option
 */
function attitude_themeoption_invalidate_caches(){
	delete_transient( 'attitude_favicon' );
	delete_transient( 'attitude_webpageicon' );
	delete_transient( 'attitude_featured_post_slider' );
	delete_transient( 'attitude_socialnetworks' );  
	delete_transient( 'attitude_footercode' );
	delete_transient( 'attitude_home_slogan' );
	delete_transient( 'attitude_internal_css' );
	delete_transient( 'attitude_verification' );
}


add_action( 'save_post', 'attitude_post_invalidate_caches' );
/**
 * Clearing the cache if any changes in post or page
 */
function attitude_post_invalidate_caches(){
   delete_transient( 'attitude_featured_post_slider' );
}

?>