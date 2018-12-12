<?php
/**
 * This file represents an example of the code
 * that themes would use to register the required plugins.
 *
 * @see https://github.com/TGMPA/TGM-Plugin-Activation/blob/develop/example.php
 *
 * @package Circle
 */

if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
	require get_template_directory() . '/inc/class-tgm-plugin-activation.php';
}

/**
 * Register the required plugins for this theme.
 */
function circle_register_required_plugins() {
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 */
	$plugins = array(
		array(
			'name'     => esc_html__( 'WPBakery Visual Composer', 'circle' ),
			'slug'     => 'js_composer',
			'source'   => get_template_directory_uri() . '/plugins/js_composer.zip',
			'version'  => '4.12.1',
			'required' => true,
		),
		array(
			'name'     => esc_html__( 'Circle Required', 'circle' ),
			'slug'     => 'circle-required',
			'source'   => get_template_directory_uri() . '/plugins/circle-required.zip',
			'version'  => '1.0.0',
			'required' => true,
		),
		array(
			'name'     => esc_html__( 'Contact Form 7', 'circle' ),
			'slug'     => 'contact-form-7',
			'required' => false,
		),
	);

	/**
	 * Register plugins required
	 */
	tgmpa( $plugins, array(
		'id' => 'circle',
		'is_automatic' => true,
		'strings' => array( 'nag_type' => 'notice-warning' ),
	) );

	// We don't need check is_admin(), tgmpa_register hook already in admin by default.
	if ( class_exists( 'AT_Admin_Welcome' ) && ! $GLOBALS['tgmpa']->is_tgmpa_complete() ) {
		$tab_config = array(
			'id'    => 'tgm-register',
			'title' => 'Install Plugins',
			'link'  => admin_url( 'themes.php?page=tgmpa-install-plugins' ),
		);

		// Add welcome "Install Plugin" tab.
		AT_Admin_Welcome::instance()->add_tab( $tab_config, 5 );
	}
}
add_action( 'tgmpa_register', 'circle_register_required_plugins' );
