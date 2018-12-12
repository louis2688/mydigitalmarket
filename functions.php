<?php
/**
 * Circle functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Circle
 */

/**
 * WordPress and PHP version compat.
 */
require get_template_directory() . '/inc/version-compat.php';

if ( ! function_exists( 'circle_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function circle_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Circle, use a find and replace
		 * to change 'circle' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'circle', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		set_post_thumbnail_size( 900, 560, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary'      => esc_html__( 'Primary', 'circle' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'gallery',
			'quote',
			'image',
			'video',
			'audio',
			'link',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'circle_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
	}
endif;
add_action( 'after_setup_theme', 'circle_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function circle_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'circle_content_width', 640 );
}
add_action( 'after_setup_theme', 'circle_content_width', 0 );

/**
 * Registers an editor stylesheet for the theme.
 */
function wpdocs_theme_add_editor_styles() {
	add_editor_style( get_template_directory_uri() . '/css/editor-styles.css' );
}
add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function circle_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'circle' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'circle' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'circle_widgets_init' );

/**
 * Register Google Fonts
 */
function circle_google_fonts_url() {
	$font_url = '';

	/*
	Translators: If there are characters in your language that are not supported
	by chosen font(s), translate this to 'off'. Do not translate into your own language.
	*/
	if ( 'off' !== _x( 'on', 'Google fonts: on or off', 'circle' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Poppins:400,500,600|Lato:400,400italic,700,700italic|Playfair Display:400,400italic,700italic,700|Hind:400,700&subset=latin,latin-ext' ), '//fonts.googleapis.com/css' );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles.
 */
function circle_scripts() {
	// Register CSS.
	wp_enqueue_style( 'circle-google-fonts', circle_google_fonts_url(), array(), null );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array(), '4.6.3' );

	$main_css = is_rtl() ? '/css/main-rtl.css' : '/css/main.css';
	wp_enqueue_style( 'circle', get_template_directory_uri() . $main_css );

	// Register JS.
	wp_enqueue_script( 'masonry' );
	wp_enqueue_script( 'jquery-ui-spinner' );
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/vendor/modernizr-2.8.3.min.js', array(), '2.8.3' );
	wp_enqueue_script( 'fastclick', get_template_directory_uri() . '/js/vendor/fastclick.js', array(), '1982016' );
	wp_enqueue_script( 'jff-vendor', get_template_directory_uri() . '/js/vendor/jff-vendor.min.js', array(), '0.6.30', true );
	wp_enqueue_script( 'waypoints', get_template_directory_uri() . '/js/vendor/waypoints.min.js', array(), '1.6.2', true );
	wp_enqueue_script( 'jff-utils', get_template_directory_uri() . '/js/plugins/jff-utils.js', array(), '0.1.1', true );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/plugins/bootstrap.min.js', array(), '3.3.6', true );
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/plugins/slick.min.js', array(), '1.6.0', true );
	wp_enqueue_script( 'imagesloaded', get_template_directory_uri() . '/js/plugins/imagesloaded.pkgd.min.js', array(), '4.1.0', true );
	wp_enqueue_script( 'masonry', get_template_directory_uri() . '/js/plugins/masonry.pkgd.min.js', array(), '4.0.0', true );
	wp_enqueue_script( 'isotope', get_template_directory_uri() . '/js/plugins/isotope.pkgd.min.js', array(), '3.0.1', true );
	wp_enqueue_script( 'packery', get_template_directory_uri() . '/js/plugins/packery.pkgd.min.js', array(), '3.0.1', true );
	wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/js/plugins/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
	wp_enqueue_script( 'jquery-hoverdir', get_template_directory_uri() . '/js/plugins/jquery.hoverdir.js', array( 'jquery' ), '1.1.0', true );

	wp_enqueue_script( 'circle', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), '1.0.0', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Setup global Circle JS.
	wp_localize_script( 'circle', 'Circle', apply_filters( 'circle_setup_global_js', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'template_directory_uri' => get_template_directory_uri(),
	) ) );

	if ( get_theme_mod( 'custom_css' ) ) {
		wp_add_inline_style( 'circle', get_theme_mod( 'custom_css' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'circle_scripts', 20 );

/**
 * Get default option for Circle.
 *
 * @param  string $name Option key name to get.
 * @return mixin
 */
function circle_default( $name ) {
	static $defaults;

	if ( ! $defaults ) {
		$defaults = apply_filters( 'circle_defaults', array(
			// Header.
			'header_content_type'       => 'none',
			'navbar_fixed'				=> true,
			// General.
			'display_archive_sharing'	=> true,
			'display_single_sharing'	=> true,
			'display_author_bio'		=> true,

			// Footer.
			'footer_logo'             => get_template_directory_uri() . '/img/logo/logo-footer.png',
			'copyright'             => esc_html__( 'All Right Reserved', 'circle' ),
			'display_footer_social' => true,
			'footer_bg_type'        => 'color',
			'footer_bg_color'       => '#f4f4f4',
			'footer_bg_image'       => '', // TODO:...

			// Primary menu.
			'menu_logo'             => get_template_directory_uri() . '/img/logo/logo-footer.png',
			'menu_copyright'        => esc_html__( 'All Right Reserved', 'circle' ),
			'display_menu_social'   => true,

			// 404 page.
			'404_background'        => get_template_directory_uri() . '/img/bg_404.png',
			'display_404_searchbar' => true,
			'enable_404_parallax' 	=> true,
		) );
	}

	return isset( $defaults[ $name ] ) ? $defaults[ $name ] : null;
}

/**
 * Get option for Circle.
 *
 * @param  string $name    Customize option key-name.
 * @param  mixed  $default Default value return.
 * @param  string $source  The source to to get data. Optional: theme_mod or option.
 * @return mixed|null
 */
function circle_option( $name, $default = null, $source = 'theme_mod' ) {
	$name = sanitize_key( $name );

	if ( is_null( $default ) ) {
		$default = circle_default( $name );
	}

	if ( 'option' === $source ) {
		$option = get_option( $name, $default );
	} else {
		$option = get_theme_mod( $name, $default );
	}

	/**
	 * Apply filter to custom option value.
	 *
	 * @param string $option Option value.
	 *
	 * @var mixed
	 */
	$option = apply_filters( 'circle_option_' . $name, $option );

	/**
	 * Apply filter to custom option value.
	 *
	 * @param string $option Option value.
	 * @param string $name   Option name.
	 *
	 * @var mixed
	 */
	$option = apply_filters( 'circle_option', $option, $name );

	// Return it.
	return $option;
}

/**
 * Move comment field to bottom
 *
 * @param  array $fields Comment fields.
 * @return array
 */
function circle_change_comment_field( $fields ) {
	$comment_field = $fields['comment'];

	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;

	return $fields;
}
add_filter( 'comment_form_fields', 'circle_change_comment_field' );

/**
 * Register import demos.
 */
function circle_register_import_demo() {
	if ( ! function_exists( 'at_importer_register' ) ) {
		return;
	}

	at_importer_register( 'circle', array(
		'name'        => esc_html__( 'Circle', 'circle' ),
		'preview'     => esc_url( 'http://demo.awethemes.com/circle' ),
		'screenshot'  => get_template_directory_uri() . '/dummy-data/screenshot.png',
		'archive'     => get_template_directory() . '/dummy-data/dummy-data.zip',
	) );
}
add_action( 'admin_init', 'circle_register_import_demo' );

/**
 * Change dummy_attachments demos.
 *
 * @param  array $settings input.
 */
function circle_dummy_attachments( $settings ) {
	$settings['dummy_attachments'] = false;
	return $settings;
}
add_action( 'at_importer_settings', 'circle_dummy_attachments' );

/**
 * Post format support.
 */
require get_template_directory() . '/inc/class-circle-post-format.php';

/**
 * Load sidebar feature class.
 */
require get_template_directory() . '/inc/class-circle-sidebar.php';


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load menu support.
 */
require get_template_directory() . '/inc/menu.php';

/**
 * Header custom support.
 */
require get_template_directory() . '/inc/header-custom.php';

/**
 * Social network support.
 */
require get_template_directory() . '/inc/social-support.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/custom-color.php';

/**
 * Make W3C-validate happy with WordPress.
 */
require get_template_directory() . '/inc/browser-happy.php';

/**
 * Register required plugins.
 */
require get_template_directory() . '/inc/tgm-register.php';
