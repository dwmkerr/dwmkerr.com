<?php
/**
 * Shows the sidebar content.
 *
 * @package 		Theme Horse
 * @subpackage 	Attitude
 * @since 			Attitude 1.0
 * @license 		http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link 			http://themehorse.com/themes/attitude
 */

/****************************************************************************************/

add_action( 'attitude_left_sidebar', 'attitude_display_left_sidebar', 10 );
/**
 * Show widgets of left sidebar.
 *
 * Shows all the widgets that are dragged and dropped on the left Sidebar.
 */
function attitude_display_left_sidebar() {
	// Calling the left sidebar if it exists.
	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'attitude_left_sidebar' ) ):
	endif;
}

/****************************************************************************************/

add_action( 'attitude_right_sidebar', 'attitude_display_right_sidebar', 10 );
/**
 * Show widgets of right sidebar.
 *
 * Shows all the widgets that are dragged and dropped on the right Sidebar.
 */
function attitude_display_right_sidebar() {
	// Calling the right sidebar if it exists.
	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'attitude_right_sidebar' ) ):
	endif;
}

/****************************************************************************************/

add_action( 'attitude_contact_page_sidebar', 'attitude_display_contact_page_sidebar', 10 );
/**
 * Show widgets on Contact Page Template's sidebar.
 *
 * Shows all the widgets that are dragged and dropped on the Contact Page Sidebar.
 */
function attitude_display_contact_page_sidebar() {
	// Calling the conatact page sidebar if it exists.
	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'attitude_contact_page_sidebar' ) ):
	endif;
}

/****************************************************************************************/

add_action( 'attitude_footer_sidebar', 'attitude_display_footer_sidebar', 10 );
/**
 * Show widgets on Footer of the theme.
 *
 * Shows all the widgets that are dragged and dropped on the Footer Sidebar.
 */
function attitude_display_footer_sidebar() {
	if( is_active_sidebar( 'attitude_footer_sidebar' ) ) {
		?>
		<div class="widget-wrap">
			<div class="container">
				<div class="widget-area clearfix">
				<?php
					// Calling the footer sidebar if it exists.
					if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'attitude_footer_sidebar' ) ):
					endif;
				?>
				</div><!-- .widget-area -->
			</div><!-- .container -->
		</div><!-- .widget-wrap -->
		<?php
	}
}

?>