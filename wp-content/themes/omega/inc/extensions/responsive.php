<?php

/* add meta viewport for responsive layout */
function omega_viewport () {
	echo '<meta name="viewport" content="width=device-width">';
}

add_action('wp_head', 'omega_viewport', 1 );
?>