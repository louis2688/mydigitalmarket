<?php
/**
 * Custom color support in Customizer.
 *
 * @package Circle
 */

/**
 * Get color
 *
 * @param  array $color //.
 * @return string
 */
function circle_get_color_scheme( $color = array() ) {
	$color_scheme = array(
		'text_color' => '#717171',
		'link_color' => '#000000',
		'link_hover' => '#fe5252',
	);

	$color_scheme = apply_filters( 'circle_default_color_scheme', $color_scheme );

	return wp_parse_args( $color, $color_scheme );
}

/**
 * Add support color in Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function circle_customizer_color_register( $wp_customize ) {
	// Default Color.
	$color_scheme = circle_get_color_scheme();

	// Add main text color setting and control.
	$wp_customize->add_setting( 'circle_color[text_color]', array(
		'default'           => $color_scheme['text_color'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'circle_color[text_color]', array(
		'label'       => esc_html__( 'Text Color', 'circle' ),
		'section'     => 'colors',
	) ) );

	// Add link color setting and control.
	$wp_customize->add_setting( 'circle_color[link_color]', array(
		'default'           => $color_scheme['link_color'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'circle_color[link_color]', array(
		'label'       => esc_html__( 'Link Color', 'circle' ),
		'section'     => 'colors',
	) ) );

	// Add link hover color setting and control.
	$wp_customize->add_setting( 'circle_color[link_hover]', array(
		'default'           => $color_scheme['link_hover'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'circle_color[link_hover]', array(
		'label'       => esc_html__( 'Link Hover Color', 'circle' ),
		'section'     => 'colors',
	) ) );
}
add_action( 'customize_register', 'circle_customizer_color_register' );

/**
 * Binds the JS listener to make Customizer color_scheme control.
 */
function circle_customize_control_js() {
	wp_enqueue_script( 'angelline-color-scheme-control', get_template_directory_uri() . '/js/color-scheme-control.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '08112016', true );
}
add_action( 'customize_controls_enqueue_scripts', 'circle_customize_control_js' );

/**
 * Outputs an Underscore template for generating CSS for the color scheme.
 *
 * The template generates the css dynamically for instant display in the
 * Customizer preview.
 */
function circle_color_scheme_css_template() {
	?><script type="text/html" id="tmpl-circle-color-scheme">
		<?php echo circle_get_color_scheme_css( array(  // WPCS: XSS OK.
			'text_color' => '{{ data.text_color }}',
			'link_color' => '{{ data.link_color }}',
			'link_hover' => '{{ data.link_hover }}',
		) ); ?>
	</script><?php
}
add_action( 'customize_controls_print_footer_scripts', 'circle_color_scheme_css_template' );

/**
 * Returns CSS for the color schemes.
 *
 * @param array $color Color scheme color.
 * @return string
 */
function circle_get_color_scheme_css( $color ) {
	$color = circle_get_color_scheme( $color );

	return <<<CSS

	/* Text color */
	body {
		color: {$color['text_color']};
	}

	/* Link */
	a {
		color: {$color['link_color']};
	}
	a:hover {
		color: {$color['link_hover']};
	}
CSS;
}

/**
 * Enqueues front-end CSS
 *
 * @see wp_add_inline_style()
 */
function circle_enqueue_custom_css() {
	$default = circle_get_color_scheme();
	$color  = wp_parse_args( circle_option( 'circle_color' ), $default );

	$css = '';

	if ( $color['text_color'] !== $default['text_color'] ) {
		$css .= <<<CSS
		/* Text color */
		body {
			color: {$color['text_color']};
		}
CSS;
	}

	if ( $color['link_color'] !== $default['link_color'] ) {
		$css .= <<<CSS
		a {
			color: {$color['link_color']};
		}
CSS;
	}

	if ( $color['link_hover'] !== $default['link_hover'] ) {
		$css .= <<<CSS
		a:hover {
			color: {$color['link_hover']};
		}
CSS;
	}
	wp_add_inline_style( 'circle', $css );
}
add_action( 'wp_enqueue_scripts', 'circle_enqueue_custom_css', 20 );
