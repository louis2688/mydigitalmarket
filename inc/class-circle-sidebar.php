<?php
/**
 * Sidebar feature support for Circle theme.
 *
 * @package Circle
 */

/**
 * Circle Sidebar Class.
 */
final class Circle_Sidebar {
	/**
	 * //
	 *
	 * @var string
	 */
	protected static $cache_setting = array();

	/**
	 * Conditionally hook into WordPress.
	 */
	public static function init() {
		add_action( 'atfw_init', array( __CLASS__, '_register_metabox' ) );
		add_action( 'atfw_init', array( __CLASS__, '_register_termmeta' ) );
		add_action( 'customize_register', array( __CLASS__, '_register_customizer' ) );
	}

	/**
	 * Get sidebar name in current screen.
	 *
	 * @return string
	 */
	public static function get_sidebar() {
		return static::has_sidebar() ? static::get_setting( 'name' ) : '';
	}

	/**
	 * Get sidebar area in current screen.
	 *
	 * @return string
	 */
	public static function get_sidebar_area() {
		return static::get_setting( 'area' );
	}

	/**
	 * If current screen is no sidebar.
	 *
	 * @return boolean
	 */
	public static function is_no_sidebar() {
		return static::get_setting( 'area' ) === 'none';
	}

	/**
	 * If current screen have a sidebar.
	 *
	 * @return boolean
	 */
	public static function has_sidebar() {
		return static::get_setting( 'area' ) !== 'none';
	}

	/**
	 * Get sidebar setting in current screen.
	 *
	 * @param  string $get Key name to get.
	 * @return string|array
	 */
	public static function get_setting( $get = null ) {
		if ( $setting = static::$cache_setting ) {
			return isset( $setting[ $get ] ) ? $setting[ $get ] : $setting;
		}

		$options = (array) get_option( 'circle-sidebar', array() );

		$default = array(
			'name' => static::default_sidebar(),
			'area' => static::default_area(),
		);

		foreach ( static::allowed_pages() as $id => $name ) {
			if ( isset( $options[ $id ] ) ) {
				$options[ $id ] = wp_parse_args( $options[ $id ], $default );
			} else {
				$options[ $id ] = $default;
			}
		}

		// Set setting as default.
		$setting = $default;

		if ( is_category() || is_tag() || is_tax() ) {

			$setting = $options['archive']; // Change "archive" to "category" if Category enable.

			$term = get_queried_object();
			$meta_data = get_term_meta( $term->term_id, 'circle-sidebar', true );

			if ( is_array( $meta_data ) && ! empty( $meta_data['is_overwrite'] ) ) {
				unset( $meta_data['is_overwrite'] );
				$setting = wp_parse_args( $meta_data, $options['archive'] ); // Change "archive" to "category" if Category enable.
			}
		} elseif ( is_single() || is_page() ) {

			$key = is_single() ? 'single' : 'page';
			$setting = $options[ $key ];

			$meta_data = get_post_meta( get_the_ID(), 'circle-sidebar', true );

			if ( is_array( $meta_data ) && ! empty( $meta_data['is_overwrite'] ) ) {
				unset( $meta_data['is_overwrite'] );
				$setting = wp_parse_args( $meta_data, $options[ $key ] );
			}
		} elseif ( is_archive() || is_search() ) {

			$setting = $options['archive'];

		} elseif ( is_home() ) {

			$setting = $options['home'];
		}

		static::$cache_setting = apply_filters( 'circle_get_sidebar_setting', $setting, $options );

		return isset( static::$cache_setting[ $get ] ) ? static::$cache_setting[ $get ] : static::$cache_setting;
	}

	/**
	 * //
	 *
	 * @return string
	 */
	public static function default_sidebar() {
		$default_sidebar = 'sidebar-1';

		return apply_filters( 'circle_sidebar_default', $default_sidebar );
	}

	/**
	 * //
	 *
	 * @return string
	 */
	public static function default_area() {
		$default_sidebar = 'right';

		return apply_filters( 'circle_sidebar_area_default', $default_sidebar );
	}

	/**
	 * Get default sidebar area.
	 *
	 * @return array
	 */
	public static function sidebar_area() {
		$sidebar_area = array(
			'none'  => esc_html__( 'No Sidebar', 'circle' ),
			'left'  => esc_html__( 'Sidebar Left', 'circle' ),
			'right' => esc_html__( 'Sidebar Right', 'circle' ),
		);

		/**
		 * Apply filter and return sidebar area.
		 */
		return apply_filters( 'circle_sidebar_area', $sidebar_area );
	}

	/**
	 * Allowed pages can register in customizer.
	 *
	 * @return array
	 */
	protected static function allowed_pages() {
		$pages = array(
			'home'     => esc_html__( 'Blog (Index)', 'circle' ),
			'archive'  => esc_html__( 'Archive', 'circle' ),
			'page'     => esc_html__( 'Page', 'circle' ),
			'single'   => esc_html__( 'Single', 'circle' ),
		);

		return $pages;
	}

	/**
	 * Add settings to the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 */
	public static function _register_customizer( $wp_customize ) {
		$wp_customize->add_panel( 'circle_sidebar', array(
			'title'          => esc_html__( 'Sidebar', 'circle' ),
			'theme_supports' => '',
		) );

		foreach ( static::allowed_pages() as $id => $name ) {
			$id = sanitize_key( $id );
			$section_id = sprintf( 'circle_sidebar_%s', $id );

			$sidebar_id = sprintf( 'circle-sidebar[%s][name]', $id );
			$sidebar_area_id = sprintf( 'circle-sidebar[%s][area]', $id );

			// Add Customizer Section.
			$wp_customize->add_section( $section_id, array(
				'title'  => $name,
				'panel'  => 'circle_sidebar',
			) );

			// Add Customizer Settings.
			$wp_customize->add_setting( $sidebar_area_id, array(
				'default'           => static::default_area(),
				'type'              => 'option',
				'sanitize_callback' => array( __CLASS__, 'sanitize_sidebar_area' ),
			) );

			$wp_customize->add_setting( $sidebar_id, array(
				'default'           => static::default_sidebar(),
				'type'              => 'option',
				'sanitize_callback' => array( __CLASS__, 'sanitize_sidebar' ),
			) );

			// Add Customizer Controls.
			$wp_customize->add_control( $sidebar_area_id, array(
				'type'    => 'select',
				'section' => $section_id,
				'label'   => esc_html__( 'Sidebar Area', 'circle' ),
				'choices' => static::sidebar_area(),
			) );

			$wp_customize->add_control( $sidebar_id, array(
				'type'    => 'select',
				'section' => $section_id,
				'label'   => esc_html__( 'Sidebar Name', 'circle' ),
				'choices' => static::registered_sidebars(),
			) );
		}
	}

	/**
	 * Register sidebar metabox.
	 *
	 * @param  ATFW $atfw ATFW Instance.
	 */
	public static function _register_metabox( ATFW $atfw ) {
		 // Add 'product' if you want single product sidebar active.
		$screen = apply_filters( 'circle_sidebar_metabox_screen', array( 'post', 'page', 'product' ) );

		$args = array(
			'title'   => esc_html__( 'Sidebar', 'circle' ),
			'screen'  => $screen,
			'fields'  => static::metabox_fields(),
			'context' => 'side',
		);

		$atfw->register_metabox( new ATFW_Metabox( 'circle-sidebar', $args ) );
	}

	/**
	 * Register sidebar term meta.
	 *
	 * @param  ATFW $atfw ATFW Instance.
	 */
	public static function _register_termmeta( ATFW $atfw ) {
		$taxonomy = apply_filters( 'circle_sidebar_taxonomy', array( 'category', 'post_tag', 'product_cat', 'product_tag' ) );

		$args = array(
			'id'       => 'circle-sidebar',
			'title'    => esc_html__( 'Sidebar', 'circle' ),
			'taxonomy' => $taxonomy,
		);

		$atfw->register_term_metabox( $args, static::metabox_fields() );
	}

	/**
	 * //
	 *
	 * @return array
	 */
	protected static function metabox_fields() {
		return array(
			array(
				'id'      => 'is_overwrite',
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Use custom sidebar setting?', 'circle' ),
				'default' => false,
			),

			array(
				'id'      => 'area',
				'type'    => 'select',
				'title'   => esc_html__( 'Sidebar Area', 'circle' ),
				'options' => static::sidebar_area(),
				'default' => static::default_area(),
				'dependency' => array( 'is_overwrite', '==', 'true' ),
			),

			array(
				'id'      => 'name',
				'type'    => 'select',
				'title'   => esc_html__( 'Sidebar Name', 'circle' ),
				'options' => static::registered_sidebars(),
				'default' => static::default_sidebar(),
				'dependency' => array( 'is_overwrite|area', '==|!=', 'true|none' ),
			),
		);
	}

	/**
	 * Get WP registered sidebar.
	 *
	 * @return array
	 */
	public static function registered_sidebars() {
		global $wp_registered_sidebars;

		$sidebars = array();

		foreach ( $wp_registered_sidebars as $id => $sidebar ) {
			$sidebars[ $id ] = $sidebar['name'];
		}

		return $sidebars;
	}

	/**
	 * Helper sanitize sidebar name.
	 *
	 * @param  string $sidebar Raw sidebar name.
	 * @return string
	 */
	public static function sanitize_sidebar( $sidebar ) {
		$allowed_sidebars = (array) static::registered_sidebars();

		if ( ! array_key_exists( $sidebar, $allowed_sidebars ) ) {
			$sidebar = static::default_sidebar();
		}

		return $sidebar;
	}

	/**
	 * Helper sanitize sidebar area.
	 *
	 * @param  string $sidebar_area Raw sidebar area.
	 * @return string
	 */
	public static function sanitize_sidebar_area( $sidebar_area ) {
		$allowed_sidebar_area = (array) static::sidebar_area();

		if ( ! array_key_exists( $sidebar_area, $allowed_sidebar_area ) ) {
			$sidebar_area = static::default_area();
		}

		return $sidebar_area;
	}
}

Circle_Sidebar::init();
