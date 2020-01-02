<?php 

/* template tags that couldn't be covered by the basic bfa_parse_php function because they 
require some conditionals, e.g. "don't display the comment number if no comments yet"
*/

function bfa_comments_title() {	
	printf( _n( 'One comment on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'montezuma' ),
		number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
}


function bfa_comments_popup_link($zero = '0', $one = '1', $more = '%', $class= 'comment-bubble', $none = 'comments off', $comments_open = TRUE, $password_required = FALSE, $have_comments = TRUE ) {

	$id = get_the_ID();
	$number = get_comments_number( $id );
	

	/*if( comments_open() && ! post_password_required() && $post->comment_count > 0 ) : 
		comments_popup_link( 
			_x( '0', 'comments number', 'montezuma' ), 
			_x( '1', 'comments number', 'montezuma' ), 
			_x( '%', 'comments number', 'montezuma' ), 
			'comment-bubble' 
		); 
	endif; 
	*/
	
	if( $number > 0 && comments_open( $id ) && ! post_password_required( $id ) )
		comments_popup_link( $zero, $one, $more, $class, $none );

}


function bfa_comments_number( $class = 'comment-bubble' ) {

	$id = get_the_ID();
	$number = get_comments_number( $id );

	if( $number > 0 && ! post_password_required( $id ) ) {
		echo '<i class="' . $class . '">'; 
		comments_number( '0', '1', '%' );
		echo '</i>';
	}
}


function bfa_comment_delete_link( $text = '' ) { 
	$id = get_comment_ID();
	if( current_user_can( 'edit_post' ) ) 
		echo '<a class="comment-delete-link" href="' . admin_url("comment.php?action=cdc&c=$id") . '">' . $text . '</a>';    
}


function bfa_comment_spam_link( $text = '' ) { 
	$id = get_comment_ID();
	if( current_user_can( 'edit_post' ) ) 
		echo '<a class="comment-spam-link" title="" href="' . admin_url("comment.php?action=cdc&dt=spam&c=$id") . '">' . $text . '</a>';  
}


function bfa_avatar( ) {
	global $montezuma, $comment;
	$avatar_size = $montezuma['avatar_size'];
	if ( '0' != $comment->comment_parent )
		$avatar_size = $montezuma['avatar_size_small'];
	if( $montezuma['avatar_url'] != '' ) 
		echo get_avatar( $comment, $avatar_size, $montezuma['avatar_url'] );
	else 
		echo get_avatar( $comment, $avatar_size ); // Use default image
}
					

function bfa_comment_awaiting( $text ) {
	global $comment;
	if( $comment->comment_approved == "0" ) : ?>
		<p class="comment-awaiting-moderation">
			<?php echo $text; ?>
		</p>
	<?php endif;
}


function bfa_loop( $postformat = 'postformat' ) {

	if( have_posts() ) : ?>
		
		<div class="post-list">
			<?php while( have_posts() ): the_post(); 
				bfa_get_template_part( $postformat, get_post_format() ); 
			endwhile; ?>
		</div>
		
	<?php else : ?>
	
		<div id="post-0" class="post not-found">
			<h2><?php _e( 'Nothing Found', 'montezuma' ); ?></h2>
			<div class="post-bodycopy">
				<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'montezuma' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		</div>
		
	<?php endif; 

}


function bfa_paginate_comments( $id = '' ) {
		$paginateCommentsLinks = paginate_comments_links("echo=0"); 
		if ($paginateCommentsLinks != ""): 
			if( $id == '' ) 
				$cssid = '';
			else 
				$cssid = ' id="' . $id . '"';
			?>
			<div class="comment-pagination"<?php echo $cssid; ?>>
				<?php echo $paginateCommentsLinks; ?>
			</div>
		<?php endif; 
}


function bfa_excerpt( $num_words = 55, $more = ' ...' ) {

	$more = str_replace( 
		array( '%title%', '%url%' ), 
		array( the_title( '', '', FALSE ), esc_url( get_permalink() ) ), 
		$more
	);

	$content = get_the_content('');
	$content = strip_shortcodes( $content );
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);

	/* the new wp_trim_words uses wp_strip_all_tags so bfa_excerpt does not 
	offer its own "strip_tags" with the parameter "allowable tags"
	*/
	$excerpt = wp_trim_words( $content, $num_words, $more );
	
	echo $excerpt;
}


function bfa_if_front_else( $if, $else ) {
	if( is_front_page() ) echo $if;
	else echo $else;
}



function bfa_attachment_url( $echo = TRUE ) {
	global $post;
	if( $echo == TRUE ) 
		echo wp_get_attachment_url($post->ID);
	else 
		return wp_get_attachment_url($post->ID);
}


function bfa_attachment_image( $size,  $echo = TRUE ) {
	global $post;
	if( $echo == TRUE ) 
		echo wp_get_attachment_image( $post->ID, $size );
	else 
		return wp_get_attachment_image( $post->ID, $size );	
}


function bfa_parent_permalink( $echo = TRUE ) {
	global $post;
	if( $echo == TRUE ) 
		echo get_permalink($post->post_parent);
	else 
		return get_permalink($post->post_parent);	
}


function bfa_parent_title( $echo = TRUE ) {
	global $post;
	if( $echo == TRUE ) 
		echo get_the_title($post->post_parent);
	else 
		return get_the_title($post->post_parent);
}


function bfa_attachment_caption( $before = '<p class="wp-caption-text">', $after = '</p>' ) {
	global $post;
	if ( ! empty( $post->post_excerpt ) ) : 
		echo $before;
		// the_excerpt(); 
		echo $post->post_excerpt;
		echo $after;
	endif; 
}



				











