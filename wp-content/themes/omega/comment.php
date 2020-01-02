<?php
/**
 * Comment Template
 *
 * The comment template displays an individual comment. This can be overwritten by templates specific
 * to the comment type (comment.php, comment-{$comment_type}.php, comment-pingback.php, 
 * comment-trackback.php) in a child theme.
 *
 * @package Omega
 * @subpackage Template
 */
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

	<article <?php omega_attr( 'comment' ); ?>>
		<p <?php omega_attr( 'comment-author' ); ?>>
			<?php echo get_avatar( $comment, 48 ); ?>
			<?php printf( __( '<cite class="fn">%s</cite> <span class="says">%s:</span>', 'omega' ), get_comment_author_link(), apply_filters( 'comment_author_says_text', __( 'says', 'omega' ) ) ); ?>
		</p>
		<p class="comment-meta"> 
			<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '%1$s at %2$s', 'omega' ), get_comment_date(), get_comment_time() ); ?></a>
				<?php edit_comment_link( __( '(Edit)', 'omega' ), '' ); ?>
		<p>
		<div class="comment-content">
			<?php comment_text(); ?>
		</div><!-- .comment-content -->

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>

		<?php omega_do_atomic( 'omega_after_comment' );?>
		
	</article>	

<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>