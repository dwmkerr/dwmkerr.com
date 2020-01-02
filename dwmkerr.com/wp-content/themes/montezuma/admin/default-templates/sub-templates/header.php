<div id="banner-bg" class="cf">
	<div id="banner" class="row">
		<div id="logo-area" class="col5">
			<<?php bfa_if_front_else( 'h1', 'h3' ); ?> id="sitetitle">
				<a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a>
			</<?php bfa_if_front_else( 'h1', 'h3' ); ?>>
			<p id="tagline"><?php bloginfo( 'description' ); ?></p>
		</div>
		<?php wp_nav_menu( array( 
			'container' => 'nav', 
			'container_class' => 'menu-wrapper col7', 
			'container_id' => 'menu1-wrapper', 
			'menu_id' => 'menu1', 
			'menu_class' => 'cf menu', 
			'theme_location' => 'menu1', 
			'fallback_cb' => 'bfa_page_menu' 
		) ); ?>
	</div>
</div>

<a href="<?php bloginfo( 'rss2_url' ); ?>" class="rsslink" title="<?php _e( 'Subscribe to RSS Feed', 'montezuma' ); ?>"></a>
		

<div id="breadcrumbs1-bg">
	<nav id="breadcrumbs1" class="breadcrumbs lw">
		<?php bfa_breadcrumbs( 'breadcrumbs1' ); ?>
	</nav>
</div>


