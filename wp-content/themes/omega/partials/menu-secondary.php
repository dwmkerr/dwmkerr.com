<?php
/**
 * Secondary Menu Template
 */
?>	
<nav class="nav-secondary" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
	
	<?php omega_do_atomic( 'before_secondary_menu' ); // omega_before_secondary_menu ?>

	<?php 
	wp_nav_menu( array(
		'theme_location' => 'secondary',
		'container'      => '',
		'menu_class'     => 'menu omega-nav-menu menu-secondary'
		)); 
	?>

	<?php omega_do_atomic( 'after_secondary_menu' ); // omega_after_secondary_menu ?>

	
</nav><!-- .nav-secondary -->
