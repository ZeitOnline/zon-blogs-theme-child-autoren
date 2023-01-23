<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js  color-scheme-light" itemscope itemtype="http://schema.org/WebPage" data-color-scheme="light" data-rebrushed="true">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php do_action( 'zon_theme_after_opening_head' ); ?>
	<link rel="index" href="<?php echo esc_url( home_url( '/' ) ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<meta property="al:ios:app_name" content="ZEIT ONLINE">
	<meta property="al:ios:app_store_id" content="828889166">
	<meta property="al:ios:url" content="<?php echo trailingslashit( home_url( $wp->request ) ); ?>">
	<meta property="fb:pages" content="37816894428, 63948163305, 327602816926, 114803848589834" />
	<!-- wp_head() -->
	<?php wp_head(); ?>
	<!-- /wp_head() -->
</head>

<body <?php body_class(); ?> id="zon1000">
<?php

do_action( 'zon_theme_after_opening_body', zb_is_wrapped() );
do_action( 'wp_body_start' );

?>
<div id="page" class="hfeed site">
	<header id="masthead" class="site-header" style="background-image:url(<?php header_image(); ?>)">
		<div class="site-branding" >
			<div>
				<span class="site-header__site-supertitle"><?php esc_html_e( 'Blog', 'zb' ); ?></span>
			</div>
			<div class="site-branding__main">
				<?php if( is_front_page() ): ?>
				<h1 class="site-branding__site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else: ?>
				<h2 class="site-branding__site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
				<?php endif; ?>

				<div class="site-branding__site-description">
					<div><?php bloginfo( 'description' ); ?></div>
					<?php if( display_header_text() ): ?>
					<div class="site-branding__site-about-link"><a href="<?php echo get_theme_mod( 'header_link_url', '/hamburg/alle-zeit-online-hamburg-blogger/' ); ?>"><?php echo get_theme_mod( 'header_link_text', 'Über die Autoren' ); ?></a></div>
					<?php endif; ?>
				</div>
			</div>
		</div><!-- .site-branding -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
