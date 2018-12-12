<?php
/**
 * Circle Theme Customizer.
 *
 * @package Circle
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function circle_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	$wp_customize->get_panel( 'circle_sidebar' )->priority    = 28;
	$wp_customize->get_panel( 'circle_social' )->priority     = 34;

	$wp_customize->get_section( 'header_image' )->priority       = 21;

	if ( $wp_customize->get_panel( 'tt_font_typography_panel' ) ) {
		$wp_customize->get_panel( 'tt_font_typography_panel' )->priority = 32;
	}

	$wp_customize->get_section( 'colors' )->panel               = 'circle_colors';
	$wp_customize->get_section( 'background_image' )->panel     = 'circle_colors';

	// Register colors panel.
	$wp_customize->add_panel( 'circle_colors', array(
		'title'       => esc_html__( 'Colors', 'circle' ),
		'priority'    => 40,
	) );
}
add_action( 'customize_register', 'circle_customize_register', 30 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function circle_customize_preview_js() {
	wp_enqueue_script( 'circle_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'circle_customize_preview_js' );

/**
 * Circle_Customizer_Manager Class.
 */
final class Circle_Customizer_Manager {
	/**
	 * Instance implements.
	 *
	 * @var static
	 */
	private static $instance;

	/**
	 * Alias of static::get_instance().
	 */
	public static function init() {
		return static::get_instance();
	}

	/**
	 * Instance the class.
	 */
	public final static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	/**
	 * Register settings for the Theme Customizer.
	 */
	private function __construct() {
		add_action( 'customize_register', array( $this, 'register' ) );
	}

	/**
	 * Register settings for the Theme Customizer.
	 *
	 * @param  WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register( WP_Customize_Manager $wp_customize ) {
		$this->register_logo( $wp_customize );
		$this->register_header( $wp_customize );
		$this->register_general( $wp_customize );
		$this->register_footer( $wp_customize );
		$this->register_custom_code( $wp_customize );
		$this->register_menu( $wp_customize );
		$this->register_404( $wp_customize );
	}

	/**
	 * Register site logo setting for the Theme Customizer
	 *
	 * @param  WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_logo( WP_Customize_Manager $wp_customize ) {
		// Site logo settings.
		$wp_customize->add_setting( 'site_logo_fixed' , array(
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
			'transport'     => 'postMessage',
		) );

		$wp_customize->add_setting( 'site_logo_scroll' , array(
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
			'transport'     => 'postMessage',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'site_logo_fixed', array(
			'section'   => 'title_tagline',
			'label'     => esc_html__( 'Logo fixed', 'circle' ),
			'priority'  => 55,
		) ) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'site_logo_scroll', array(
			'section'   => 'title_tagline',
			'label'     => esc_html__( 'Logo scroll', 'circle' ),
			'priority'  => 50,
		) ) );

		$wp_customize->selective_refresh->add_partial( 'site_logo_fixed', array(
			'selector'            => '#header .awemenu-logo-fixed',
			'settings'            => array( 'site_logo_fixed', 'blogname' ),
			'container_inclusive' => false,
			'render_callback'     => function() {
				circle_site_logo( 'site_logo_fixed', true, true );
			},
		) );

		$wp_customize->selective_refresh->add_partial( 'site_logo_scroll', array(
			'selector'            => '#header .awemenu-logo-scroll',
			'settings'            => array( 'site_logo_scroll', 'blogname' ),
			'container_inclusive' => false,
			'render_callback'     => function() {
				circle_site_logo( 'site_logo_scroll', true, true );
			},
		) );
	}

	/**
	 * Register header setting for the Theme Customizer.
	 *
	 * @param  WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_header( WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_section( 'header', array(
			'title'    => esc_html__( 'Header', 'circle' ),
			'priority' => 25,
		) );

		// ...
		$wp_customize->add_setting( 'navbar_fixed', array(
			// 'transport' => 'postMessage',
			'default'   => circle_default( 'navbar_fixed' ),
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'navbar_fixed', array(
			'type'    => 'checkbox',
			'section' => 'header',
			'label'   => esc_html__( 'Fixed Navigation on Scroll', 'circle' ),
		) );

		// Header Content.
		$wp_customize->add_setting( 'header_content_type', array(
			'default'   => circle_default( 'header_content_type' ),
			// 'transport' => 'postMessage',
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'header_content_type', array(
			'type'    => 'select',
			'section' => 'header',
			'label'   => esc_html__( 'Header Content', 'circle' ),
			'default' => 'type',
			'choices' => array(
				'none' => esc_html__( 'Hidden', 'circle' ),
				'page' => esc_html__( 'Use Page Content', 'circle' ),
			),
		) );
		$wp_customize->add_setting( 'header_content_page', array(
			'default'   => circle_default( 'header_content_page' ),
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );
		$wp_customize->add_control( 'header_content_page', array(
			'type'    => 'select',
			'section' => 'header',
			'label'   => esc_html__( 'Select page for header content:', 'circle' ),
			'choices' => $this->get_data_source( 'pages', array( 'post_status' => 'publish,private' ) ),
			'active_callback'  => function() {
				return 'page' === circle_option( 'header_content_type' );
			},
		) );
	}

	/**
	 * Register general setting for the Theme Customizer
	 *
	 * @param  WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_general( WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_section( 'general', array(
			'title'    => esc_html__( 'Blog General', 'circle' ),
			'priority' => 20,
		) );

		$wp_customize->selective_refresh->add_partial( 'pager', array(
			'selector'            => '.blog-pager-container',
			'render_callback'     => 'circle_pager',
			'container_inclusive' => true,
		) );

		// Show Archive sharing.
		$wp_customize->add_setting( 'display_archive_sharing', array(
			// 'transport' => 'postMessage',
			'default'   => circle_default( 'display_archive_sharing' ),
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'display_archive_sharing', array(
			'type'    => 'checkbox',
			'section' => 'general',
			'label'   => esc_html__( 'Display social sharing in archive post?', 'circle' ),
		) );

		// Show Single sharing.
		$wp_customize->add_setting( 'display_single_sharing', array(
			// 'transport' => 'postMessage',
			'default'   => circle_default( 'display_single_sharing' ),
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'display_single_sharing', array(
			'type'    => 'checkbox',
			'section' => 'general',
			'label'   => esc_html__( 'Display social sharing in single post?', 'circle' ),
		) );

		// Show display_author_bio.
		$wp_customize->add_setting( 'display_author_bio', array(
			// 'transport' => 'postMessage',
			'default'   => circle_default( 'display_author_bio' ),
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'display_author_bio', array(
			'type'    => 'checkbox',
			'section' => 'general',
			'label'   => esc_html__( 'Display Author bio', 'circle' ),
		) );
	}

	/**
	 * Register footer setting for the Theme Customizer.
	 *
	 * @param  WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_footer( WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_section( 'footer', array(
			'title'    => esc_html__( 'Footer', 'circle' ),
			'priority' => 25,
		) );

		// Footer logo setting.
		$wp_customize->add_setting( 'footer_logo', array(
			'default'           => circle_default( 'footer_logo' ),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'footer_logo', array(
			'section'  => 'footer',
			'label'    => esc_html__( 'Footer Logo', 'circle' ),
			'priority' => 10,
		) ) );

		$wp_customize->selective_refresh->add_partial( 'footer_logo', array(
			'selector'            => '.footer__logo',
			'container_inclusive' => false,
			'render_callback'     => 'circle_footer_logo',
		) );

		// Footer copyright setting.
		$wp_customize->add_setting( 'copyright', array(
			'default'   => circle_default( 'copyright' ),
			'transport' => 'postMessage',
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'copyright', array(
			'type'     => 'textarea',
			'section'  => 'footer',
			'label'    => esc_html__( 'Copyright', 'circle' ),
			'description' => esc_html__( 'Tips: Use {c}, {year}, {sitename}, {theme}, {author} to generate dynamic value.', 'circle' ),
			'priority' => 20,
		) );

		$wp_customize->selective_refresh->add_partial( 'copyright', array(
			'selector'            => '.footer__copyright',
			'container_inclusive' => false,
			'render_callback'     => 'circle_footer_copyright',
		) );

		// Footer Social.
		$wp_customize->add_setting( 'display_footer_social', array(
			'default'   => circle_default( 'display_footer_social' ),
			'transport' => 'postMessage',
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'display_footer_social', array(
			'type'     => 'checkbox',
			'section'  => 'footer',
			'label'    => esc_html__( 'Display social follow in Footer', 'circle' ),
			'priority' => 30,
		) );

		$wp_customize->selective_refresh->add_partial( 'display_footer_social', array(
			'selector'            => '.footer__socials',
			'container_inclusive' => false,
			'render_callback'     => 'circle_footer_social',
		) );

		// Footer background.
		$wp_customize->add_setting( 'footer_bg_type', array(
			'default'   => circle_default( 'footer_bg_type' ),
			'sanitize_callback' => array( 'Circle_Customizer_Manager', 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'footer_bg_type', array(
			'type'     => 'select',
			'section'  => 'footer',
			'label'    => esc_html__( 'Background Style', 'circle' ),
			'choices'  => array(
				'color' => esc_html__( 'Background Color', 'circle' ),
				'image' => esc_html__( 'Background Image', 'circle' ),
			),
			'priority' => 40,
		) );

		// Background Color.
		$wp_customize->add_setting( 'footer_bg_color', array(
			'transport' => 'postMessage',
			'default'   => circle_default( 'footer_bg_color' ),
			'sanitize_callback' => array( 'Circle_Customizer_Manager', 'sanitize_value' ),
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_bg_color', array(
			'label'    => esc_html__( 'Background Color', 'circle' ),
			'section'  => 'footer',
			'priority' => 50,
			'active_callback' => function() {
				return 'color' === circle_option( 'footer_bg_type' );
			},
		) ) );

		// Background Image Styled.
		$wp_customize->add_setting( 'footer_bg_image', array(
			'transport' => 'postMessage',
			'default'   => circle_default( 'footer_bg_image' ),
			'sanitize_callback' => array( 'Circle_Customizer_Manager', 'sanitize_value' ),
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'footer_bg_image', array(
			'label'    => esc_html__( 'Background Image', 'circle' ),
			'section'  => 'footer',
			'priority' => 60,
			'active_callback' => function() {
				return 'image' === circle_option( 'footer_bg_type' );
			},
		) ) );
		$wp_customize->selective_refresh->add_partial( 'footer_bg_type', array(
			'selector'            => '#footer',
			'settings'            => array( 'footer_bg_type', 'footer_bg_color', 'footer_bg_image' ),
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'footer' );
			},
		) );
	}
	/**
	 * Register Custom code setting for the Theme Customizer.
	 *
	 * @param  WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_custom_code( $wp_customize ) {
		$wp_customize->add_section( 'custom_code', array(
			'title'    => esc_html__( 'Custom CSS', 'circle' ),
			'priority' => 45,
		) );

		// Custom CSS.
		$wp_customize->add_setting( 'custom_css', array(
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'custom_css', array(
			'type'    => 'textarea',
			'section' => 'custom_code',
			'label'   => esc_html__( 'Custom CSS', 'circle' ),
		) );
	}
	/**
	 * Register menu setting for the Theme Customizer.
	 *
	 * @param  WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_menu( WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_section( 'primarymenu', array(
			'title'    => esc_html__( 'Navigation', 'circle' ),
			'priority' => 26,
		) );

		// Menu logo setting.
		$wp_customize->add_setting( 'menu_logo', array(
			'default'           => circle_default( 'menu_logo' ),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'menu_logo', array(
			'section'  => 'primarymenu',
			'label'    => esc_html__( 'Menu Logo', 'circle' ),
			'priority' => 10,
		) ) );

		$wp_customize->selective_refresh->add_partial( 'menu_logo', array(
			'selector'            => '.footer__logo',
			'container_inclusive' => false,
			'render_callback'     => 'circle_menu_logo',
		) );

		// Menu copyright setting.
		$wp_customize->add_setting( 'menu_copyright', array(
			'default'   => circle_default( 'menu_copyright' ),
			'transport' => 'postMessage',
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'menu_copyright', array(
			'type'     => 'textarea',
			'section'  => 'primarymenu',
			'label'    => esc_html__( 'Menu Copyright', 'circle' ),
			'description' => esc_html__( 'Tips: Use {c}, {year}, {sitename}, {theme}, {author} to generate dynamic value.', 'circle' ),
			'priority' => 20,
		) );

		$wp_customize->selective_refresh->add_partial( 'menu_copyright', array(
			'selector'            => '.footer__copyright',
			'container_inclusive' => false,
			'render_callback'     => 'circle_menu_copyright',
		) );

		// Menu Social.
		$wp_customize->add_setting( 'display_menu_social', array(
			'default'   => circle_default( 'display_menu_social' ),
			'transport' => 'postMessage',
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'display_menu_social', array(
			'type'     => 'checkbox',
			'section'  => 'primarymenu',
			'label'    => esc_html__( 'Display social follow in Primay menu', 'circle' ),
			'priority' => 30,
		) );

		$wp_customize->selective_refresh->add_partial( 'display_menu_social', array(
			'selector'            => '.footer__socials',
			'container_inclusive' => false,
			'render_callback'     => 'circle_menu_social',
		) );
	}

	/**
	 * Register 404 setting for the Theme Customizer.
	 *
	 * @param  WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_404( WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_section( 'page_404', array(
			'title'    => esc_html__( '404 page', 'circle' ),
			'priority' => 25,
		) );

		// 404 image setting.
		$wp_customize->add_setting( '404_background', array(
			'default'           => circle_default( '404_background' ),
			// 'transport'      => 'postMessage',
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, '404_background', array(
			'section' => 'page_404',
			'label'   => esc_html__( '404 Background', 'circle' ),
		) ) );

		// 404 display search bar settings.
		$wp_customize->add_setting( 'display_404_searchbar', array(
			'default'           => circle_default( 'display_404_searchbar' ),
			// 'transport'         => 'postMessage',
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'display_404_searchbar', array(
			'section' => 'page_404',
			'label'   => esc_html( 'Display 404 Search bar?', 'circle' ),
			'type'    => 'checkbox',
		) );

		// 404 parallax effect.
		$wp_customize->add_setting( 'enable_404_parallax', array(
			'default'           => circle_default( 'enable_404_parallax' ),
			// 'transport'         => 'postMessage',
			'sanitize_callback' => array( __CLASS__, 'sanitize_value' ),
		) );

		$wp_customize->add_control( 'enable_404_parallax', array(
			'section' => 'page_404',
			'label'   => esc_html( 'Enable 404 parallax?', 'circle' ),
			'type'    => 'checkbox',
		) );
	}

	/**
	 * Sanitize raw value.
	 *
	 * @param  mixed $value Raw string value.
	 * @return string
	 */
	public static function sanitize_value( $value ) {
		return trim( $value );
	}

	/**
	 * Get data source.
	 *
	 * @param  string $source Source to get.
	 * @param  array  $args   Optional: Source arguments.
	 * @return array
	 */
	public function get_data_source( $source, $args = array() ) {
		$options = array();

		switch ( $source ) {
			case 'page':
			case 'pages':
				$pages = get_pages( $args );

				if ( ! is_wp_error( $pages ) && ! empty( $pages ) ) {
					foreach ( $pages as $page ) {
						$options[ $page->ID ] = $page->post_title;
					}
				}
				break;
		}

		return $options;
	}
}

/**
 * Init Circle_Customizer_Manager.
 */
Circle_Customizer_Manager::init();
