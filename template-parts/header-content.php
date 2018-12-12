<?php
/**
 * Display header content for Circle
 *
 * @package Circle
 */

// Build page title data.
$header_custom = array(
	'content_type' => circle_option( 'header_content_type' ),
	'content_page' => circle_option( 'header_content_page' ),
);

if ( is_tax() || is_archive() ) {
	$_term = get_queried_object();

	if ( $_term && isset( $_term->term_id ) ) {
		$_metadata = get_term_meta( $_term->term_id, 'circle-header-custom', true );
	}
} elseif ( is_single() || is_page() ) {
	$_metadata = get_post_meta( get_the_ID(), 'circle-header-custom', true );
}

// Build $_metadata.
if ( isset( $_metadata ) && is_array( $_metadata ) && $_metadata['is_header_custom'] ) {
	$header_custom = circle_parse_custom_header( $_metadata );
}

if ( 'none' === $header_custom['content_type'] ) {
	return;
}

$content = '';
if ( 'page' === $header_custom['content_type'] && $page_id = $header_custom['content_page'] ) {
	$content = get_post_field( 'post_content', $page_id );
}

// Don't display anything if $content is empty.
if ( ! $content ) {
	return;
}

// Apply the_content for $content.
$content = apply_filters( 'the_content', $content );
$content = str_replace( ']]>', ']]&gt;', $content );
?>
<div class="header-content">
	<?php print apply_filters( 'circle_header_content', $content ); // Wpcs xss:ok.?>
</div>
