<?php
/**
 * Implementation of the Custom Header feature as Header.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-header/
 *
 * @package Circle
 */

/**
 * //
 *
 * @param array $metadata Metadata custom header setting.
 */
function circle_parse_custom_header( $metadata ) {
	unset( $metadata['is_header_custom'] );

	$metadata = wp_parse_args( $metadata, array(
		'content_type' => circle_default( 'header_content_type' ),
		'content_page' => '',
	) );

	return $metadata;
}

/**
 * Register metabox and term-metabox for custom header.
 *
 * @param  ATFW $atfw ATFW Instance.
 */
function circle_register_header_custom_metabox( $atfw ) {
	$fields = array(
		array(
			'id'      => 'is_header_custom',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Custom header setting?', 'circle' ),
			'default' => false,
		),
		array(
			'id'      => 'content_type',
			'type'    => 'select',
			'title'   => esc_html__( 'Header Content', 'circle' ),
			'default' => circle_default( 'header_content_type' ),
			'options' => array(
				'none' => esc_html__( 'Hidden', 'circle' ),
				'page' => esc_html__( 'Use Page Content', 'circle' ),
			),
			'dependency' => array( 'is_header_custom', '==', 'true' ),
		),
		array(
			'id'      => 'content_page',
			'type'    => 'select',
			'title'   => esc_html__( 'Select page for Header content:', 'circle' ),
			'options' => get_data_source( 'pages', array( 'post_status' => 'publish,private' ) ),
			'dependency' => array( 'is_header_custom|content_type', '==|==', 'true|page' ),
		),
	);

	// Register metabox.
	$atfw->register_metabox( new ATFW_Metabox( 'circle-header-custom', array(
		'title'   => esc_html__( 'Header Custom', 'circle' ),
		'screen'  => apply_filters( 'circle_header_custom_post_type_support', array( 'post', 'page' ) ),
		'fields'  => $fields,
		'context' => 'side',
	) ) );

	// Register term-metabox.
	$atfw->register_term_metabox( array(
		'id'       => 'circle-header-custom',
		'title'    => esc_html__( 'Header Custom', 'circle' ),
		'taxonomy' => apply_filters( 'circle_header_custom_taxonomy_support', array( 'category', 'post_tag' ) ),
		'fields'   => $fields,
	) );
}
add_action( 'atfw_init', 'circle_register_header_custom_metabox' );

/**
 * Get data source.
 *
 * @param  string $source Source to get.
 * @param  array  $args   Optional: Source arguments.
 * @return array
 */
function get_data_source( $source, $args = array() ) {
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
