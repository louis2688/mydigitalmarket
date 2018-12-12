<?php
/**
 * Make W3C-validate happy with WordPress.
 *
 * @package Circle
 */

/**
 * Remove un-used property oembed parse
 *
 * @param  string $return Return embed
 * @param  object $data   Data object
 * @param  string $url    URL given
 * @return string
 */
function circle_oembed_reparse( $return, $data, $url ) {
	if ( is_object( $data ) && property_exists( $data, 'provider_name' ) ) {
		$remove = array( 'frameborder="0"', 'frameborder="no"', 'scrolling="no"', 'webkitallowfullscreen', 'mozallowfullscreen' );
		$return = str_replace( $remove, '', $return );
		$return = preg_replace( '/&(?!amp;)/', '&amp;', $return );
	}

	return $return;
}
add_filter( 'oembed_dataparse', 'circle_oembed_reparse', 10, 3 );

/**
 * Clean breadcrumb trail
 *
 * @param  stirng $breadcrumb Breadcrumb before echo HTML
 * @return string
 */
function circle_clean_breadcrumb_trail( $breadcrumb ) {
	$breadcrumb = str_replace( 'itemprop="breadcrumb"', '', $breadcrumb );
	$breadcrumb = preg_replace( '/(<meta[^>]+name="[^"][numberOfItems|itemListOrder][^>]+>)/', '', $breadcrumb );

	return $breadcrumb;
}
add_filter( 'breadcrumb_trail', 'circle_clean_breadcrumb_trail' );
