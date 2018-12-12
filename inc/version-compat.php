<?php
/**
 * Circle back compat functionality
 *
 * @package Circle
 */

/**
 * Circle only works in WordPress 4.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.6', '<' ) ) :
	/**
	 * Prevent switching to Circle on old versions of WordPress.
	 * Switches to the default theme.
	 */
	function circle_switch_theme() {
		switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );

		unset( $_GET['activated'] );

		add_action( 'admin_notices', 'circle_upgrade_notice' );
	}
	add_action( 'after_switch_theme', 'circle_switch_theme' );

	/**
	 * Adds a message for unsuccessful theme switch.
	 *
	 * Prints an update nag after an unsuccessful attempt to switch to
	 * Circle on WordPress versions prior to 4.6.
	 *
	 * @global string $wp_version WordPress version.
	 */
	function circle_upgrade_notice() {
		$message = sprintf( esc_html__( 'Circle requires at least WordPress version 4.6. You are running version %s. Please upgrade and try again.', 'circle' ), $GLOBALS['wp_version'] );
		printf( '<div class="error"><p>%s</p></div>', $message ); // WPCS: XSS OK.
	}
endif;

/**
 * And only works with PHP 5.3.9 or later.
 */
if ( version_compare( phpversion(), '5.3.9', '<' ) ) :
	/**
	 * Prevent switching to Circle on old versions of WordPress.
	 *
	 * Switches to the default theme.
	 */
	function circle_phpcompat_switch_theme() {
		switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );

		unset( $_GET['activated'] );

		add_action( 'admin_notices', 'circle_phpcompat_upgrade_notice' );
	}
	add_action( 'after_switch_theme', 'circle_phpcompat_switch_theme' );

	/**
	 * Adds a message for outdate PHP version.
	 */
	function circle_phpcompat_upgrade_notice() {
		$message = sprintf( esc_html__( 'Circle requires at least PHP version 5.3.9. You are running version %s.', 'circle' ), phpversion() );
		printf( '<div class="error"><p>%s</p></div>', $message ); // WPCS: XSS OK.
	}
endif;
