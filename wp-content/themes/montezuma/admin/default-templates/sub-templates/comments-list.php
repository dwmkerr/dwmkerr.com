<h2 id="comments-title"><?php bfa_comments_title(); ?></h2>

<?php bfa_paginate_comments( 'comment-pagination-1' ); ?>

<ol class="commentlist">
	<?php wp_list_comments( array( 'callback' => 'bfa_comments_callback', 'type' => 'all' ) ); ?>
</ol>

<?php bfa_paginate_comments( 'comment-pagination-2' ); ?>
