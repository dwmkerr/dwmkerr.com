<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Casper
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="HandheldFriendly" content="True" />
<meta name="MobileOptimized" content="320" />
<meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>" />
<meta property="og:type" content="article" />

<title><?php wp_title( '|', true, 'right' ); ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header id="masthead" role="banner" class="site-head site-header" <?php if(get_header_image() ) : ?>style="background-image: url(<?php header_image(); ?>);"<?php endif ?>>
    <div class="vertical">
        <nav id="site-navigation" class="main-navigation" role="navigation">
            <div>
                <h1 class="menu-toggle">
                    <a class="icon-menu" href="#">
                        <span class="hidden"><?php _e( 'Menu', 'casper' ); ?></span>
                    </a>
                </h1>
                <a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'casper' ); ?></a>
                <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
            </div>
        </nav><!-- #site-navigation -->
        <div class="site-head-content inner">
            <?php if ( get_theme_mod( 'casper_logo' ) ) : ?>
                <a class="blog-logo" href='<?php echo esc_url( home_url( '/' ) ); ?>' rel='home'><img src='<?php echo esc_url( get_theme_mod( 'casper_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></a>
            <?php endif; ?>

            <div class="social-icons">
                <?php if ( false != get_theme_mod( 'casper_social_youtube')) { ?>
                    <a class="icon-youtube" href="<?php echo get_theme_mod( 'casper_social_youtube'); ?>">
                        <span class="hidden"><?php _e( 'Youtube', 'casper' ); ?></span>
                    </a>
                <?php } ?>
                <?php if ( false != get_theme_mod( 'casper_social_tumblr')) { ?>
                    <a class="icon-tumblr" href="<?php echo get_theme_mod( 'casper_social_tumblr'); ?>">
                        <span class="hidden"><?php _e( 'Tumblr', 'casper' ); ?></span>
                    </a>
                <?php } ?>
                <?php if ( false != get_theme_mod( 'casper_social_google')) { ?>
                    <a class="icon-google-plus" href="<?php echo get_theme_mod( 'casper_social_google'); ?>">
                        <span class="hidden"><?php _e( 'Google+', 'casper' ); ?></span>
                    </a>
                <?php } ?>
                <?php if ( false != get_theme_mod( 'casper_social_facebook')) { ?>
                    <a class="icon-facebook" href="<?php echo get_theme_mod( 'casper_social_facebook'); ?>">
                        <span class="hidden"><?php _e( 'Facebook', 'casper' ); ?></span>
                    </a>
                <?php } ?>
                <?php if ( false != get_theme_mod( 'casper_social_twitter')) { ?>
                    <a class="icon-twitter" href="<?php echo get_theme_mod( 'casper_social_twitter' ); ?>">
                        <span class="hidden"><?php _e( 'Twitter', 'casper' ); ?></span>
                    </a>
                <?php } ?>
                <?php if ( false != get_theme_mod( 'casper_social_website')) { ?>
                    <a class="icon-home" href="<?php echo get_theme_mod( 'casper_social_website'); ?>">
                        <span class="hidden"><?php _e( 'Home', 'casper' ); ?></span>
                    </a>
                <?php } ?>
                <?php if ( false != get_theme_mod( 'casper_social_mail')) { ?>
                    <a class="icon-envelope" href="mailto:<?php echo get_theme_mod( 'casper_social_mail'); ?>">
                        <span class="hidden"><?php _e( 'Email', 'casper' ); ?></span>
                    </a>
                <?php } ?>
                <?php if ( false != get_theme_mod( 'casper_social_linkedin')) { ?>
                    <a class="icon-linkedin" href="<?php echo get_theme_mod( 'casper_social_linkedin'); ?>">
                        <span class="hidden"><?php _e( 'LinkedIn', 'casper' ); ?></span>
                    </a>
                <?php } ?>
                <?php if ( false != get_theme_mod( 'casper_social_github')) { ?>
                    <a class="icon-github" href="<?php echo get_theme_mod( 'casper_social_github'); ?>">
                        <span class="hidden"><?php _e( 'GitHub', 'casper' ); ?></span>
                    </a>
                <?php } ?>
                <?php if ( false != get_theme_mod( 'casper_social_dribbble')) { ?>
                    <a class="icon-dribbble" href="<?php echo get_theme_mod( 'casper_social_dribbble'); ?>">
                        <span class="hidden"><?php _e( 'Dribbble', 'casper' ); ?></span>
                    </a>
                <?php } ?>
            </div>
            <h1 class="blog-title"><a class="blog-logo" href='<?php echo esc_url( home_url( '/' ) ); ?>' rel='home'><?php bloginfo( 'name' ); ?></a></h1>
            <h2 class="blog-description"><?php bloginfo( 'description' ); ?></h2>
        </div>
    </div>
</header><!-- #masthead -->

<main id="content" class="content" role="main">