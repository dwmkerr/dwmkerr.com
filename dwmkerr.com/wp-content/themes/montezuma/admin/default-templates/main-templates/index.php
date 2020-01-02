<!--<div id="container">-->

<?php get_header(); ?>

<div id="main" class="row">

	<div id="content" class="cf col8">
		
		<?php bfa_content_nav( 'multinav1' ); ?>
		
		<?php bfa_loop( 'postformat' ); ?>
		
		<?php bfa_content_nav( 'multinav2' ); ?>
		
	</div>
	
	<div id="widgetarea-one" class="col4">
		<?php dynamic_sidebar( 'Widget Area ONE' ); ?>
	</div>

</div>
	
<?php get_footer(); ?>

<!--</div>-->