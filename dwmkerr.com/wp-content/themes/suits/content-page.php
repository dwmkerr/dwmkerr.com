<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Suits
 * @since Suits 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
		<div class="entry-thumbnail">
			<?php the_post_thumbnail(); ?>
		</div>
		<?php endif; ?>

		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'suits' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
	</div><!-- .entry-content -->

	<?php if ( current_user_can( 'edit_post', get_the_ID() ) ) : ?>
	<footer class="entry-meta">
		<?php edit_post_link( __( 'Edit', 'suits' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	<?php endif; ?>
</article><!-- #post -->
