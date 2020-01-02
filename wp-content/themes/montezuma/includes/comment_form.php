<?php 

function bfa_comment_form() {	

	global $aria_req, $post_id, $required_text, $montezuma; 

	// Global $commenter and $user_identity not available here (they would be / are from inside comments.php)
	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user(); 
	$user_identity = $user->display_name;

	// author, email and url fields are set in a separate variable first:
	$fields =  array(
		'author' => 
			'<p><input class="text author" id="comment-author" name="author" type="text" value="' 
			. esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req 
			. ' tabindex="1" />&nbsp;&nbsp;<label for="comment-author">' . __( '<strong>Author</strong> (required)', 'montezuma' ) . '</label></p>',
		'email' => 
			'<p><input class="text email" id="comment-email" name="email" type="text" value="' 
			. esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req 
			. '  tabindex="2" />&nbsp;&nbsp;<label for="comment-email">' . __( '<strong>Email</strong> (will not be published)(required)', 'montezuma' ) . '</label></p>',
		'url' => 
			'<p><input class="text url" id="comment-url" name="url" type="text" value="' 
			. esc_attr( $commenter['comment_author_url'] ) 
			. '" size="30"  tabindex="3" />&nbsp;&nbsp;<label for="comment-url">' . __( 'Website', 'montezuma' ) . '</label></p>'
	); 

	// The rest is set here:
	$comment_form_settings = array(
	'fields' => 
		apply_filters( 'comment_form_default_fields', $fields ),
	'comment_field' => 
		'<p><textarea name="comment" id="comment-form" rows="10" cols="60" tabindex="4"></textarea></p>',
	'must_log_in' => 
		'<p class="must-log-in">' 
		. sprintf( 
			__( 'You must be <a href="%s">logged in</a> to post a comment.', 'montezuma' ), 
			wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) 
		) 
		. '</p>',
	'logged_in_as' => 
		'<p class="logged-in-as">' 
		. sprintf( 
			__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'montezuma' ), 
			admin_url( 'profile.php' ), 
			$user_identity, 
			wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) 
		) 
		. '</p>',
	'comment_notes_before' => $montezuma['comment_notes_before'],
	'comment_notes_after' => $montezuma['comment_notes_after'],
	'id_form' => 'commentform',
	'id_submit' => 'submit',
	'title_reply' => __( 'Leave a Reply', 'montezuma' ),
	'title_reply_to' => __( 'Leave a Reply to %s', 'montezuma' ),
	'cancel_reply_link' => __( 'Cancel Reply', 'montezuma' ),
	'label_submit' => __( 'Post Comment', 'montezuma' )
	);

	comment_form($comment_form_settings);
		
}		
		
