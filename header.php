<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package circle
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="page" class="site">
		<div id="go_top"></div>
		<a class="skip-link sr-only" href="#main"><?php esc_html_e( 'Skip to content', 'circle' );?></a>
		<header id="header" class="">
			<nav class="navbar navbar-default " <?php circle_header_fixed();?> role="navigation">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-target=".navigation">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar bar-1"></span>
					<span class="icon-bar bar-2"></span>
					<span class="icon-bar bar-3"></span>
					</button>
					<?php circle_site_logo( 'site_logo_scroll', true, true, '<div class="awemenu-logo-scroll">', '</div>' );?>
					<?php circle_site_logo( 'site_logo_fixed', true, true, '<div class="awemenu-logo-fixed">', '</div>' );?>
				</div>
			</nav>
		</header>
		<!-- /header -->
		<nav class="navigation">
			<div class="container">
				<div class="row">
					<div class="col-sm-7 col-md-7">
						<div>
							<p class="navigation__breadcrumb">
								<label><?php esc_html_e( 'Navigation', 'circle' );?> <i class="fa fa-angle-right"></i></label>
							</p>
						</div>
						<?php
							// Primary Menu.
							wp_nav_menu( array(
								'theme_location' => 'primary',
								'container'      => '',
								'menu_id'        => 'primary-menu',
								'menu_class'     => 'nav navbar-nav',
								'walker'         => new Circle_Walker_Nav_Menu(),
								'fallback_cb'    => array( 'Circle_Menu_Support', 'fallback' ),
							) );
						?>
					</div>
					<div class="col-sm-5 col-md-5">
						<div class="footer-wrap text-center">
								<?php
								/**
								 * Circle menu Hook.
								 *
								 * @hooked circle_menu_logo - 10
								 * @hooked circle_menu_social - 20
								 * @hooked circle_menu_copyright - 30
								 */
								do_action( 'circle_menu' );
								?>
						</div>
					</div>
				</div>
			</div>
		</nav>
		<div class="site-container">
			<?php
			/**
			 * Circle Header Hook.
			 *
			 * @hooked circle_header_content - 10
			 */
			if ( empty( $GLOBALS['hidden_header_page'] ) ) {
				do_action( 'circle_header' );
			}
			?>
			<section id="page-container">
				<!-- Breadcrumb -->
				<?php if ( empty( $GLOBALS['hidden_breadcrumbs'] ) ) : ?>
				<?php circle_breadcrumb();?>
				<?php endif;?>
				<!-- /.breadcrumb -->
				<?php if ( empty( $GLOBALS['hidden_layout'] ) ) : ?>
				<div class="bx-content bx-blog" data-waypoint="waypointEffect">
					<div class="bx-content__info">
					<?php endif;?>
						<!-- /.bx-content__header -->
						<?php if ( empty( $GLOBALS['hidden_container'] ) ) : ?>
						<div id="container" class="container">
						<?php endif;?>
							<?php if ( empty( $GLOBALS['hidden_layout'] ) ) : ?>
							<div id="layout" <?php circle_layout_class( '' ); ?>>
							<?php endif;?>
