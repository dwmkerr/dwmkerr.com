<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Omega
 */

get_header(); ?>

	<main class="content"  role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'omega' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'omega' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

	</main><!-- .content -->

<?php get_footer(); ?>