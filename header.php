<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Expound
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta
        charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php wp_title('|', true, 'right'); ?>
    </title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback"
        href="<?php bloginfo('pingback_url'); ?>" />
    <!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"
    type="text/javascript"></script>
    <![endif]-->

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site dres">
	<?php do_action('expound_header_before'); ?>
	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<div class="site-title-group">
				<div class="logo">
				<?php if (function_exists('the_custom_logo')) {
    the_custom_logo();
}?>
				</div>
				<div class= "title_description">
				<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
				<h2 class="site-description"><?php bloginfo('description'); ?></h2>
				<p class="site-description2">Looking at the Past, Present and Future of the Railway Industry.<br>Open to all with an interest in Railways.</p>
			</div>
			</div>
		</div>

		<nav id="site-navigation" class="navigation-main" role="navigation">
			<h1 class="menu-toggle"><?php _e('Menu', 'expound'); ?></h1>
			<a class="skip-link screen-reader-text" href="#content"><?php _e('Skip to content', 'expound'); ?></a>

			<?php wp_nav_menu(['theme_location' => 'primary', 'depth' => 3]); ?>
			<?php wp_nav_menu([
			    'theme_location' => 'social',
			    'depth' => 1,
			    'container_id' => 'expound-social',
			    'link_before' => '<span>',
			    'link_after' => '</span>',
			    'fallback_cb' => '',
			]); ?>
			<?php do_action('expound_navigation_after'); ?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->
	<?php do_action('expound_header_after'); ?>

	<div id="main" class="site-main">