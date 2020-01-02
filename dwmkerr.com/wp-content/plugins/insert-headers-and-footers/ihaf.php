<?php
/*
Plugin Name: Insert Headers and Footers
Plugin URI: http://www.wpbeginner.com/
Description: Allows you to insert code or text in the header or footer of your WordPress blog
Version: 1.3
Author: iamdpegg
Author URI: http://www.wpbeginner.com/
License: This software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/


if ( !class_exists( 'InsertHeadersAndFooters' ) ) {
	
	define('IHAFURL', plugins_url('', __FILE__));
	if (is_admin()) {
		wp_register_style('IHAFStyleSheet', IHAFURL . '/ihaf.css');
		wp_enqueue_style( 'IHAFStyleSheet');
	}
	
	class InsertHeadersAndFooters {

		function InsertHeadersAndFooters() {
		
			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
			add_action( 'wp_head', array( &$this, 'wp_head' ) );
			add_action( 'wp_footer', array( &$this, 'wp_footer' ) );
		
		}
	
		function init() {
			load_plugin_textdomain( 'insert-headers-and-footers', false, dirname( plugin_basename ( __FILE__ ) ).'/lang' );
		}
	
		function admin_init() {
			register_setting( 'insert-headers-and-footers', 'ihaf_insert_header', 'trim' );
			register_setting( 'insert-headers-and-footers', 'ihaf_insert_footer', 'trim' );
	}
	
		function admin_menu() {
			add_submenu_page( 'options-general.php', 'Insert Headers and Footers', 'Insert Headers and Footers', 'manage_options', __FILE__, array( &$this, 'options_panel' ) );
			}
	
		function wp_head() {
			$meta = get_option( 'ihaf_insert_header', '' );
				if ( $meta != '' ) {
					echo $meta, "\n";
				}
		}
	
		function wp_footer() {
			if ( !is_admin() && !is_feed() && !is_robots() && !is_trackback() ) {
				$text = get_option( 'ihaf_insert_footer', '' );
				$text = convert_smilies( $text );
				$text = do_shortcode( $text );
			
			if ( $text != '' ) {
				echo $text, "\n";
			}
			}
		}
	
		function fetch_rss_items( $num, $feed ) {
			include_once( ABSPATH . WPINC . '/feed.php' );
			$rss = fetch_feed( $feed );

			// Bail if feed doesn't work
			if ( !$rss || is_wp_error( $rss ) )
			return false;

			$rss_items = $rss->get_items( 0, $rss->get_item_quantity( $num ) );

			// If the feed was erroneous 
			if ( !$rss_items ) {
				$md5 = md5( $feed );
				delete_transient( 'feed_' . $md5 );
				delete_transient( 'feed_mod_' . $md5 );
				$rss = fetch_feed( $feed );
				$rss_items = $rss->get_items( 0, $rss->get_item_quantity( $num ) );
			}

			return $rss_items;
		}
	        
	
		function options_panel() { ?>
			<div id="ihaf-wrap">
				<div class="wrap">
				<?php screen_icon(); ?>
					<h2>Insert Headers and Footers - Options</h2>
					<div class="ihaf-wrap">
						<form name="dofollow" action="options.php" method="post">
							<?php settings_fields( 'insert-headers-and-footers' ); ?>
                        	<label class="ihaf-labels" for="ihaf_insert_header">Scripts in header:</label>
                            <textarea rows="5" cols="57" id="insert_header" name="ihaf_insert_header"><?php echo esc_html( get_option( 'ihaf_insert_header' ) ); ?></textarea><br />
						These scripts will be printed to the <code>&lt;head&gt;</code> section. 
                        <label class="ihaf-labels footerlabel" for="ihaf_insert_footer">Scripts in footer:</label>
                        <textarea rows="5" cols="57" id="ihaf_insert_footer" name="ihaf_insert_footer"><?php echo esc_html( get_option( 'ihaf_insert_footer' ) ); ?></textarea><br />
						These scripts will be printed to the <code>&lt;footer&gt;</code> section.

						<p class="submit">
							<input type="submit" name="Submit" value="Save settings" /> 
						</p>

						</form>
					</div>

					<div class="ihaf-sidebar">
						<div class="ihaf-improve-site">
							<h2>Improve Your Site!</h2>
							<p>Want to take your site to the next level? Look behind the scenes of WPBeginner to see what you can do!</p>
							<p><a href="http://www.wpbeginner.com/blueprint/" target="_blank">WPBeginner's Blueprint &raquo;</a></p>
						</div>
						<div class="ihaf-support">
							<h2>Need Support?</h2>
							<p>If you are having problems with this plugin, please talk about them in the</p>
							<p><a href="http://www.wpbeginner.com/contact/" target="_blank">Support Forums</a></p>
						</div>
						<div class="ihaf-donate">
							<h2>Spread the Word!</h2>
							<p>Want to help make this plugin even better? All donations are used to improve this plugin, so donate $10, $20 or $50 now!</p>
							<p><a href="http://www.wpbeginner.com/wpbeginner-needs-your-help/" target="_blank">Donate!</a></p>
						</div>
						<div class="ihaf-wpb-recent">
							<h2>Latest News From WPBeginner</h2>
			<?php
			$rss_items = $this->fetch_rss_items( 3, 'http://wpbeginner.com/feed/' );
			$content = '<ul>';
			if ( !$rss_items ) {
				$content .= '<li class="ihaf-list">No news items, feed might be broken...</li>';
			} else {
				foreach ( $rss_items as $item ) {
					$url = preg_replace( '/#.*/', '', esc_url( $item->get_permalink(), null, 'display' ) );
					$content .= '<li class="ihaf-list">';
					$content .= '<a href="' . $url . '#utm_source=wpadmin&utm_medium=sidebarwidget&utm_term=newsitem&utm_campaign=ihaf">' . esc_html( $item->get_title() ) . '</a> ';
					$content .= '</li>';
				}}
				$content .= '<li class="facebook"><a href="https://www.facebook.com/wpbeginner" target="_blank">Like WPBeginner on Facebook</a></li>';
				$content .= '<li class="twitter"><a href="http://twitter.com/wpbeginner"target="_blank">Follow WPBeginner on Twitter</a></li>';
				$content .= '<li class="googleplus"><a href="https://plus.google.com/101634180904808003404/posts" target="_blank">Circle WPBeginner on Google+</a></li>';
				$content .= '<li class="email"><a href="http://wpbeginner.us1.list-manage.com/subscribe?u=549b83cc29ff23c36e5796c38&id=4c340fd3aa" target="_blank">Subscribe by email</a></li>';
				$content .= '</ul>';
				echo $content;
				?>
				</div>
				</div>
				</div>
				</div>
				<?php
		}
	}


	add_action('wp_dashboard_setup', 'ihaf_dashboard_widgets');

	function ihaf_dashboard_widgets() {
  		global $wp_meta_boxes;
		wp_add_dashboard_widget('wpbeginnerihafwidget', 'Latest from WPBeginner', 'ihaf_widget');
}		

		function ihaf_widget() {		
			require_once(ABSPATH.WPINC.'/rss.php');
			if ( $rss = fetch_rss( 'http://wpbeginner.com/feed/' ) ) { ?>
				<div class="rss-widget">
                	<a href="http://www.wpbeginner.com/" title="WPBeginner - Beginner's guide to WordPress"><img src="http://cdn.wpbeginner.com/pluginimages/wpbeginner.gif"  class="alignright" alt="WPBeginner"/></a>			
					<ul>
                		<?php 
						$rss->items = array_slice( $rss->items, 0, 5 );
						foreach ( (array) $rss->items as $item ) {
							echo '<li>';
							echo '<a class="rsswidget" href="'.clean_url( $item['link'], $protocolls=null, 'display' ).'">'. ($item['title']) .'</a> ';
							echo '<span class="rss-date">'. date('F j, Y', strtotime($item['pubdate'])) .'</span>';
							echo '</li>';
						}
						?> 
					</ul>
					<div style="border-top: 1px solid #ddd; padding-top: 10px; text-align:center;">
						<a href="http://feeds2.feedburner.com/wpbeginner"><img src="http://cdn.wpbeginner.com/pluginimages/feed.png" alt="Subscribe to our Blog" style="margin: 0 5px 0 0; vertical-align: top; line-height: 18px;"/> Subscribe with RSS</a>
						&nbsp; &nbsp; &nbsp;
						<a href="http://wpbeginner.us1.list-manage.com/subscribe?u=549b83cc29ff23c36e5796c38&id=4c340fd3aa"><img src="http://cdn.wpbeginner.com/pluginimages/email.gif" alt="Subscribe via Email"/> Subscribe by email</a>
                		&nbsp; &nbsp; &nbsp;
                		<a href="http://facebook.com/wpbeginner/"><img src="http://cdn.wpbeginner.com/pluginimages/facebook.png" alt="Join us on Facebook" style="margin: 0 5px 0 0; vertical-align: middle; line-height: 18px;" />Join us on Facebook</a>
					</div>
				</div>
		<?php }
		
	}

$wp_insert_headers_and_footers = new InsertHeadersAndFooters();

}


