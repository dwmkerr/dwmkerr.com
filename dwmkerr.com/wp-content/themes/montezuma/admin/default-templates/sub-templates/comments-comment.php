<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
	<div>

		<?php bfa_avatar(); ?>

		<?php comment_reply_link( array( 
			'reply_text' => __( 'Reply', 'montezuma' ), 
			'login_text' => __( 'Log in to Reply', 'montezuma' ),
			'depth' => 1,
			'max_depth' => 3) ); ?>

		<span class="comment-author">
			<?php comment_author_link(); ?>
		</span>
		
		<span class="comment-date-link">
			<a href="<?php comment_link(); ?>">
				<?php comment_date( 'M j, Y' ); ?> 
				<?php comment_time(); ?>
			</a>
		</span>				

		<?php edit_comment_link( __( 'Edit', 'montezuma' ) ); ?> 
		<?php bfa_comment_delete_link( __( 'Delete', 'montezuma' ) ); ?>
		<?php bfa_comment_spam_link( __( 'Spam', 'montezuma' ) ); ?>

		<div class="comment-text">
			<?php comment_text(); ?>
			<?php bfa_comment_awaiting( __( 'Your comment is awaiting moderation.', 'montezuma' ) ); ?>
		</div>

	</div>
