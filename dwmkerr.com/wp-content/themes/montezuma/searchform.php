<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" class="field" name="s" value="<?php esc_attr( the_search_query() ); ?>" />
	<input type="submit" class="submit" name="submit" value="" />
</form>
