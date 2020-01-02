<?php

add_shortcode( 'post_date', 'omega_post_date_shortcode' );
/**
 * Produces the date of post publication.
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is empty string),
 *   format (date format, default is value in date_format option field),
 *   label (text following 'before' output, but before date).
 *
 * Output passes through 'omega_post_date_shortcode' filter before returning.
 *
 * @since 0.9.0
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string Shortcode output
 */
function omega_post_date_shortcode( $atts ) {

	$defaults = array(
		'after'  => '',
		'before' => '',
		'format' => get_option( 'date_format' ),
		'label'  => '',
	);

	$atts = shortcode_atts( $defaults, $atts, 'post_date' );

	$display = ( 'relative' === $atts['format'] ) ? omega_human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'omega' ) : get_the_time( $atts['format'] );

	$output = sprintf( '<time %s>', omega_get_attr( 'entry-published' ) ) . $atts['before'] . $atts['label'] . $display . $atts['after'] . '</time>';

	return apply_filters( 'omega_post_date_shortcode', $output, $atts );

}

add_shortcode( 'post_time', 'omega_post_time_shortcode' );
/**
 * Produces the time of post publication.
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is empty string),
 *   format (date format, default is value in date_format option field),
 *   label (text following 'before' output, but before date).
 *
 * Output passes through 'omega_post_time_shortcode' filter before returning.
 *
 * @since 0.9.0
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string Shortcode output
 */
function omega_post_time_shortcode( $atts ) {

	$defaults = array(
		'after'  => '',
		'before' => '',
		'format' => get_option( 'time_format' ),
		'label'  => '',
	);

	$atts = shortcode_atts( $defaults, $atts, 'post_time' );

	$output = sprintf( '<time %s>', omega_get_attr( 'entry-time' ) ) . $atts['before'] . $atts['label'] . get_the_time( $atts['format'] ) . $atts['after'] . '</time>';

	return apply_filters( 'omega_post_time_shortcode', $output, $atts );

}

add_shortcode( 'post_author', 'omega_post_author_shortcode' );
/**
 * Produces the author of the post (unlinked display name).
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is empty string).
 *
 * Output passes through 'omega_post_author_shortcode' filter before returning.
 *
 * @since 0.9.0
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string Shortcode output
 */
function omega_post_author_shortcode( $atts ) {

	$defaults = array(
		'after'  => '',
		'before' => '',
	);

	$atts = shortcode_atts( $defaults, $atts, 'post_author' );

	$author = get_the_author();

	$output  = sprintf( '<span %s>', omega_get_attr( 'entry-author' ) );
	$output .= $atts['before'];
	$output .= sprintf( '<span %s>', omega_get_attr( 'entry-author-name' ) ) . esc_html( $author ) . '</span>';
	$output .= $atts['after'];
	$output .= '</span>';
	

	return apply_filters( 'omega_post_author_shortcode', $output, $atts );

}

add_shortcode( 'post_author_link', 'omega_post_author_link_shortcode' );
/**
 * Produces the author of the post (link to author URL).
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is empty string).
 *
 * Output passes through 'omega_post_author_link_shortcode' filter before returning.
 *
 * @since 0.9.0
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string Shortcode output
 */
function omega_post_author_link_shortcode( $atts ) {

	$defaults = array(
		'after'    => '',
		'before'   => '',
	);

	$atts = shortcode_atts( $defaults, $atts, 'post_author_link' );

	$url = get_the_author_meta( 'url' );

	//* If no url, use post author shortcode function.
	if ( ! $url )
		return omega_post_author_shortcode( $atts );

	$author = get_the_author();

	$output  = sprintf( '<span %s>', omega_get_attr( 'entry-author' ) );
	$output .= $atts['before'];
	$output .= sprintf( '<a href="%s" %s>', $url, omega_get_attr( 'entry-author-link' ) );
	$output .= sprintf( '<span %s>', omega_get_attr( 'entry-author-name' ) );
	$output .= esc_html( $author );
	$output .= '</span></a>' . $atts['after'] . '</span>';

	return apply_filters( 'omega_post_author_link_shortcode', $output, $atts );

}

add_shortcode( 'post_author_posts_link', 'omega_post_author_posts_link_shortcode' );
/**
 * Produces the author of the post (link to author archive).
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is empty string).
 *
 * Output passes through 'omega_post_author_posts_link_shortcode' filter before returning.
 *
 * @since 0.9.0
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string Shortcode output
 */
function omega_post_author_posts_link_shortcode( $atts ) {

	$defaults = array(
		'after'  => '',
		'before' => '',
	);

	$atts = shortcode_atts( $defaults, $atts, 'post_author_posts_link' );

	$author = get_the_author();
	$url    = get_author_posts_url( get_the_author_meta( 'ID' ) );

	$output  = sprintf( '<span %s>', omega_get_attr( 'entry-author' ) );
	$output .= $atts['before'];
	$output .= sprintf( '<a href="%s" %s>', $url, omega_get_attr( 'entry-author-link' ) );
	$output .= sprintf( '<span %s>', omega_get_attr( 'entry-author-name' ) );
	$output .= esc_html( $author );
	$output .= '</span></a>' . $atts['after'] . '</span>';

	return apply_filters( 'omega_post_author_posts_link_shortcode', $output, $atts );

}

add_shortcode( 'post_comments', 'omega_post_comments_shortcode' );
/**
 * Produces the link to the current post comments.
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is empty string),
 *   hide_if_off (hide link if comments are off, default is 'enabled' (true)),
 *   more (text when there is more than 1 comment, use % character as placeholder
 *     for actual number, default is '% Comments')
 *   one (text when there is exactly one comment, default is '1 Comment'),
 *   zero (text when there are no comments, default is 'Leave a Comment').
 *
 * Output passes through 'omega_post_comments_shortcode' filter before returning.
 *
 * @since 0.9.0
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string Shortcode output
 */
function omega_post_comments_shortcode( $atts ) {

	$defaults = array(
		'after'       => '',
		'before'      => '',
		'hide_if_off' => 'enabled',
		'more'        => __( '% Comments', 'omega' ),
		'one'         => __( '1 Comment', 'omega' ),
		'zero'        => __( 'Leave a Comment', 'omega' ),
	);
	$atts = shortcode_atts( $defaults, $atts, 'post_comments' );

	if ( ( ! omega_get_setting( 'comments_posts' ) || ! comments_open() ) && 'enabled' === $atts['hide_if_off'] )
		return;

	// Darn you, WordPress!
	ob_start();
	comments_number( $atts['zero'], $atts['one'], $atts['more'] );
	$comments = ob_get_clean();

	$comments = sprintf( '<a href="%s">%s</a>', get_comments_link(), $comments );

	$output = '<span class="entry-comments-link">' . $atts['before'] . $comments . $atts['after'] . '</span>';

	return apply_filters( 'omega_post_comments_shortcode', $output, $atts );

}

add_shortcode( 'post_tags', 'omega_post_tags_shortcode' );
/**
 * Produces the tag links list.
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is 'Tagged With: '),
 *   sep (separator string between tags, default is ', ').
 *
 * Output passes through 'omega_post_tags_shortcode' filter before returning.
 *
 * @since 0.9.0
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string Shortcode output
 */
function omega_post_tags_shortcode( $atts ) {

	$defaults = array(
		'after'  => '',
		'before' => __( 'Tagged With: ', 'omega' ),
		'sep'    => ', ',
	);
	$atts = shortcode_atts( $defaults, $atts, 'post_tags' );

	$tags = get_the_tag_list( $atts['before'], trim( $atts['sep'] ) . ' ', $atts['after'] );

	//* Do nothing if no tags
	if ( ! $tags )
		return;

	$output = sprintf( '<span %s>', omega_get_attr( 'entry-tags' ) ) . $tags . '</span>';

	return apply_filters( 'omega_post_tags_shortcode', $output, $atts );

}

add_shortcode( 'post_categories', 'omega_post_categories_shortcode' );
/**
 * Produces the category links list.
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is 'Tagged With: '),
 *   sep (separator string between tags, default is ', ').
 *
 * Output passes through 'omega_post_categories_shortcode' filter before returning.
 *
 * @since 0.9.0
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string Shortcode output
 */
function omega_post_categories_shortcode( $atts ) {

	$defaults = array(
		'sep'    => ', ',
		'before' => __( 'Filed Under: ', 'omega' ),
		'after'  => '',
	);

	$atts = shortcode_atts( $defaults, $atts, 'post_categories' );

	$cats = get_the_category_list( trim( $atts['sep'] ) . ' ' );

	$output = sprintf( '<span %s>', omega_get_attr( 'entry-categories' ) ) . $atts['before'] . $cats . $atts['after'] . '</span>';

	return apply_filters( 'omega_post_categories_shortcode', $output, $atts );

}

add_shortcode( 'post_terms', 'omega_post_terms_shortcode' );
/**
 * Produces the linked post taxonomy terms list.
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is 'Tagged With: '),
 *   sep (separator string between tags, default is ', '),
 *    taxonomy (name of the taxonomy, default is 'category').
 *
 * Output passes through 'omega_post_terms_shortcode' filter before returning.
 *
 * @since 0.9.0
 *
 * @global stdClass $post Post object
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string|boolean Shortcode output or false on failure to retrieve terms
 */
function omega_post_terms_shortcode( $atts ) {

	global $post;

	$defaults = array(
			'after'    => '',
			'before'   => __( 'Filed Under: ', 'omega' ),
			'sep'      => ', ',
			'taxonomy' => 'category',
	);

	$atts = shortcode_atts( $defaults, $atts, 'post_terms' );

	$terms = get_the_term_list( $post->ID, $atts['taxonomy'], $atts['before'], trim( $atts['sep'] ) . ' ', $atts['after'] );

	if ( is_wp_error( $terms ) )
			return;

	if ( empty( $terms ) )
			return;

	$output = sprintf( '<span %s>', omega_get_attr( 'entry-terms' ) ) . $terms . '</span>';

	return apply_filters( 'omega_post_terms_shortcode', $output, $terms, $atts );

}

add_shortcode( 'post_edit', 'omega_post_edit_shortcode' );
/**
 * Produces the edit post link for logged in users.
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is 'Tagged With: '),
 *   link (link text, default is '(Edit)').
 *
 * Output passes through 'omega_post_edit_shortcode' filter before returning.
 *
 * @since 0.9.0
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string Shortcode output
 */
function omega_post_edit_shortcode( $atts ) {

	if ( ! apply_filters( 'omega_edit_post_link', true ) )
		return;

	$defaults = array(
		'after'  => '',
		'before' => '',
		'link'   => __( '(Edit)', 'omega' ),
	);

	$atts = shortcode_atts( $defaults, $atts, 'post_edit' );

	//* Darn you, WordPress!
	ob_start();
	edit_post_link( $atts['link'], $atts['before'], $atts['after'] );
	$edit = ob_get_clean();

	$output = $edit;

	return apply_filters( 'omega_post_edit_shortcode', $output, $atts );

}