<!DOCTYPE html>
<?php global $montezuma, $bfa_css, $is_IE; if( $is_IE ): ?>
<!--[if IE 8 ]><html class="ie8 notie67" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 9 ]><html class="ie9 notie67" <?php language_attributes(); ?>><![endif]-->
<!--[if (gt IE 9)]><html class="notie67" <?php language_attributes(); ?>><![endif]-->
<?php else: ?>
<html class="notie67" <?php language_attributes(); ?>>
<?php endif; ?>
<head>
<?php echo $montezuma['insert_head_top']; ?>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php if( $is_IE ) echo $montezuma['meta_xua_compatible']; ?>
<?php echo $montezuma['meta_viewport']; ?>
<title><?php wp_title( '&larr;', true, 'right' ); ?></title>
<?php if( $montezuma['xfn_link'] === 1 ) { ?><link rel="profile" href="http://gmpg.org/xfn/11" /><?php } ?>
<?php echo $bfa_css; ?>
<?php if( $montezuma['favicon_url'] != '' ) { ?>
<link rel="shortcut icon" href="<?php echo $montezuma['favicon_url']; ?>" type="image/ico" />
<?php } ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
<?php echo $montezuma['insert_head_bottom']; ?>
