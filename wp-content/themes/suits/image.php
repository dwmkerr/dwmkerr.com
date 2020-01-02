<?php
/**
 * The template for displaying image attachments.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Suits
 * @since Suits 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php /* The loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>

					<nav id="image-navigation" class="navigation image-navigation" role="navigation">
						<span class="nav-previous"><?php previous_image_link( false, __( '<span class="meta-nav">&larr;</span> Previous', 'suits' ) ); ?></span>
						<span class="nav-next"><?php next_image_link( false, __( 'Next <span class="meta-nav">&rarr;</span>', 'suits' ) ); ?></span>
					</nav><!-- #image-navigation -->
				</header><!-- .entry-header -->

				<div class="entry-content">

					<div class="entry-attachment">
						<div class="attachment">
							<?php suits_the_attached_image(); ?>
						</div><!-- .attachment -->

						<?php if ( has_excerpt() ) : ?>
						<div class="entry-caption">
							<?php the_excerpt(); ?>
						</div>
						<?php endif; ?>
					</div><!-- .entry-attachment -->

					<?php if ( ! empty( $post->post_content ) ) : ?>
					<div class="entry-description">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'suits' ), 'after' => '</div>' ) ); ?>
					</div><!-- .entry-description -->
					<?php endif; ?>

				</div><!-- .entry-content -->

				<footer class="entry-meta">
					<?php
						$metadata = wp_get_attachment_metadata();
						printf( __( '<span class="attachment-meta">Published</span> <time class="entry-date" datetime="%1$s">%2$s</time> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%8$s</a>.', 'suits' ),
							esc_attr( get_the_date( 'c' ) ),
							esc_html( get_the_date() ),
							esc_url( wp_get_attachment_url() ),
							$metadata['width'],
							$metadata['height'],
							esc_url( get_permalink( $post->post_parent ) ),
							esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
							get_the_title( $post->post_parent )
						);
					?>
					<?php edit_post_link( __( 'Edit', 'suits' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-meta -->
			</article><!-- #post -->

			<?php comments_template(); ?>

		<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>