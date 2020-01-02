<?php 

class BFA_Widget_Meta extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_bfa_meta', 'description' => 'Advanced Meta Widget' );
		parent::__construct('bfa_meta', 'Montezuma Meta', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Meta', 'montezuma') : $instance['title'], $instance, $this->id_base);
		$show_wp_register = $instance['show_wp_register'];
		$show_wp_loginout = $instance['show_wp_loginout'];
		$show_posts_rss = $instance['show_posts_rss'];
		$show_comments_rss = $instance['show_comments_rss'];
		$show_wp_link = $instance['show_wp_link'];
		
		$on_front_page = $instance['on_front_page'];
		$on_home_page = $instance['on_home_page'];
		$on_everywhere_else = $instance['on_everywhere_else'];		

		if( ( is_front_page() && $on_front_page == TRUE ) OR 
			( is_home() && $on_home_page == TRUE ) OR 
			( ! is_home() && ! is_front_page() && $on_everywhere_else == TRUE ) ) {
		
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
?>
			<ul>
			<?php if( $show_wp_register == TRUE ) { ?>
			<?php wp_register(); ?>
			<?php } ?>
			
			<?php if( $show_wp_loginout == TRUE ) { ?>
			<li><?php wp_loginout(); ?></li>
			<?php } ?>
			
			<?php if( $show_posts_rss == TRUE ) { ?>
			<li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php echo esc_attr(__('Syndicate this site using RSS 2.0', 'montezuma')); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>', 'montezuma'); ?></a></li>
			<?php } ?>
			
			<?php if( $show_comments_rss == TRUE ) { ?>
			<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php echo esc_attr(__('The latest comments to all posts in RSS', 'montezuma')); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>', 'montezuma'); ?></a></li>
			<?php } ?>
			
			<?php if( $show_wp_link == TRUE ) { ?>
			<li><a href="<?php esc_attr_e( 'http://wordpress.org/' ); ?>" title="<?php echo esc_attr(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.', 'montezuma')); ?>"><?php
			/* translators: meta widget link text */
			_e( 'WordPress.org', 'montezuma' );
			?></a></li>
			<?php } ?>
			
			<?php wp_meta(); ?>
			</ul>
<?php
		echo $after_widget;
		
		}
		
		
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['show_wp_register'] = strip_tags($new_instance['show_wp_register']);
		$instance['show_wp_loginout'] = strip_tags($new_instance['show_wp_loginout']);
		$instance['show_posts_rss'] = strip_tags($new_instance['show_posts_rss']);
		$instance['show_comments_rss'] = strip_tags($new_instance['show_comments_rss']);
		$instance['show_wp_link'] = strip_tags($new_instance['show_wp_link']);

		$instance['on_front_page'] = strip_tags($new_instance['on_front_page']);
		$instance['on_home_page'] = strip_tags($new_instance['on_home_page']);
		$instance['on_everywhere_else'] = strip_tags($new_instance['on_everywhere_else']);
		
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => '', 
			'show_wp_register' => TRUE,
			'show_wp_loginout' => TRUE,
			'show_posts_rss' => TRUE,
			'show_comments_rss' => TRUE,
			'show_wp_link' => TRUE,

			'on_front_page' => TRUE,
			'on_home_page' => FALSE,
			'on_everywhere_else' => FALSE,
			) 
		);
		
		$title = strip_tags($instance['title']);
		$show_wp_register = strip_tags($instance['show_wp_register']);
		$show_wp_loginout = strip_tags($instance['show_wp_loginout']);
		$show_posts_rss = strip_tags($instance['show_posts_rss']);
		$show_comments_rss = strip_tags($instance['show_comments_rss']);
		$show_wp_link = strip_tags($instance['show_wp_link']);

		$on_front_page = strip_tags($instance['on_front_page']);
		$on_home_page = strip_tags($instance['on_home_page']);
		$on_everywhere_else = strip_tags($instance['on_everywhere_else']);
?>

			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title</label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>

			<p><strong>Display these links:</strong></p>
			<p>
			<input id="<?php echo $this->get_field_id('show_wp_register'); ?>" name="<?php echo $this->get_field_name('show_wp_register'); ?>" type="checkbox" value="1" <?php checked( '1', $show_wp_register ); ?>/>
			<label for="<?php echo $this->get_field_id('show_wp_register'); ?>">Site Admin / Register</label>
			<br><em style="color:#666;font-size:11px">(Shows "Site Admin" if logged in and "Register" if not logged in *AND* <code>WP</code> &raquo; <code>Settings</code> &raquo; <code>General</code> &raquo; <code>Membership:</code> [x] "Anyone can register" is checked as well)</em>
			</p>

			<p>
			<input id="<?php echo $this->get_field_id('show_wp_loginout'); ?>" name="<?php echo $this->get_field_name('show_wp_loginout'); ?>" type="checkbox" value="1" <?php checked( '1', $show_wp_loginout ); ?>/>
			<label for="<?php echo $this->get_field_id('show_wp_loginout'); ?>">Log In / Log Out</label>
			</p>

			<p>
			<input id="<?php echo $this->get_field_id('show_posts_rss'); ?>" name="<?php echo $this->get_field_name('show_posts_rss'); ?>" type="checkbox" value="1" <?php checked( '1', $show_posts_rss ); ?>/>
			<label for="<?php echo $this->get_field_id('show_posts_rss'); ?>">Posts RSS Feed</label>
			</p>

			<p>
			<input id="<?php echo $this->get_field_id('show_comments_rss'); ?>" name="<?php echo $this->get_field_name('show_comments_rss'); ?>" type="checkbox" value="1" <?php checked( '1', $show_comments_rss ); ?>/>
			<label for="<?php echo $this->get_field_id('show_comments_rss'); ?>">Comments RSS Feed</label>
			</p>
			
			<p>
			<input id="<?php echo $this->get_field_id('show_wp_link'); ?>" name="<?php echo $this->get_field_name('show_wp_link'); ?>" type="checkbox" value="1" <?php checked( '1', $show_wp_link ); ?>/>
			<label for="<?php echo $this->get_field_id('show_wp_link'); ?>">"WordPress.org" Credit</label>
			</p>

			<p><strong>Show this widget on...</strong></p>
			<p>
			<input id="<?php echo $this->get_field_id('on_front_page'); ?>" name="<?php echo $this->get_field_name('on_front_page'); ?>" type="checkbox" value="1" <?php checked( '1', $on_front_page ); ?>/>
			<label for="<?php echo $this->get_field_id('on_front_page'); ?>">Site Front Page</label>
			</p>
			<p>
			<input id="<?php echo $this->get_field_id('on_home_page'); ?>" name="<?php echo $this->get_field_name('on_home_page'); ?>" type="checkbox" value="1" <?php checked( '1', $on_home_page ); ?>/>
			<label for="<?php echo $this->get_field_id('on_home_page'); ?>">Blog Posts Index Page</label>
			<br><em style="color:#666;font-size:11px">('Blog Posts Index Page' is the same as 'Site Front Page' if <code>WP</code> &raquo; <code>Settings</code> &raquo; <code>Reading</code> &raquo; <code>Front page displays:</code> [x] "Your latest posts" is checked)</em>

			</p>
			<p>
			<input id="<?php echo $this->get_field_id('on_everywhere_else'); ?>" name="<?php echo $this->get_field_name('on_everywhere_else'); ?>" type="checkbox" value="1" <?php checked( '1', $on_everywhere_else ); ?>/>
			<label for="<?php echo $this->get_field_id('on_everywhere_else'); ?>">All other pages</label>
			</p>
			
			
			
<?php
	}
}

