<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package circle
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function circle_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'circle_body_classes' );

/**
 * Display site pager.
 */
function circle_pager() {
	print '<nav>';

	$paginate_links = paginate_links( array(
		'type'      => 'list',
		'prev_text' => sprintf( '<span class="screen-reader-text">%s</span> <i class="previous %s"></i>', esc_html__( 'Previous', 'circle' ), esc_attr( apply_filters( 'circle_next_icon', 'fa fa-arrow-left' ) ) ),
		'next_text' => sprintf( '<span class="screen-reader-text">%s</span> <i class="next %s"></i>', esc_html__( 'Next', 'circle' ), esc_attr( apply_filters( 'circle_next_icon', 'fa fa-arrow-right' ) ) ),
	) );

	printf( '%s', $paginate_links ); // WPCS: XSS OK.

	print '</nav>';
}

/**
 * Site layout classes.
 *
 * @param array $classes Classes for the layout element.
 */
function circle_layout_class( $classes ) {
	$classes = (array) $classes;
	$classes[] = 'main-layout';

	if ( Circle_Sidebar::has_sidebar() ) {
		$classes[] = sprintf( 'sidebar-%s', Circle_Sidebar::get_sidebar_area() );
	} else {
		$classes[] = 'sidebar-none';
	}

	$classes = apply_filters( 'circle_layout_class', $classes );

	echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
}

/**
 * Circle get site logo.
 *
 * @param  string  $logo       Logo type to get.
 * @param  boolean $echo       Echo output.
 * @param  boolean $with_link  Display site logo with link.
 * @param  string  $before     Before output logo.
 * @param  string  $after      After output logo.
 * @return string
 */
function circle_site_logo( $logo = 'site_logo_scroll', $echo = true, $with_link = true, $before = '', $after = '' ) {
	$output = '';

	if ( $site_logo = circle_option( $logo ) ) {
		if ( is_int( $site_logo ) ) {
			$site_logo = wp_get_attachment_image_url( $site_logo, 'full' );
		}

		$image = sprintf( '<img src="%1$s" class="site-logo-img %3$s" alt="%2$s">', $site_logo, get_bloginfo( 'name' ), $logo );

		if ( $with_link ) {
			$image = sprintf( '<a class="navbar-brand navbar-brand--image h1 %3$s" href="%1$s" rel="home">%2$s</a>', esc_url( home_url( '/' ) ), $image, $logo );
		} else {
			$image = sprintf( '<span class="site-logo %2$s">%1$s</span>', $image, $logo );
		}

		$output  = $before . $image . $after;
	} else {
		$site_title = get_bloginfo( 'name' );
		$first_char = substr( $site_title, 0, 1 );
		$remain_chars = substr( $site_title, 1 );

		$image = sprintf( '<span class="logo-label">%1$s</span><span class="logo-span">%2$s</span>', $first_char, $remain_chars );
		if ( $with_link ) {
			$image = sprintf( '<a class="navbar-brand h1 navbar-brand--text %3$s" href="%1$s" rel="home">%2$s</a>', esc_url( home_url( '/' ) ), $image, $logo );
		}
		$output  = $output  = $before . $image . $after;
	}

	/**
	 * Apply filter to site logo hooks.
	 *
	 * @var string
	 */
	$output = apply_filters( 'circle_site_logo', $output, $logo, $with_link, $before, $after );

	if ( ! $echo ) {
		return $output;
	}

	print $output; // WPCS: XSS OK.
}

/**
 * Display the excerpt.
 */
function circle_excerpt() {
	global $postdata;
	if ( 'quote' == $postdata['post_format'] ) {
		return;
	}
	sprintf( '<p class="bx-blog__desc">%1$s</p>', the_excerpt() );
}

/**
 * Small function to display site copyright.
 *
 * @param  string $before Before output copyright.
 * @param  string $after  After output copyright.
 * @param  bool   $echo   Echo or return output.
 */
function circle_site_copyright( $before = '', $after = '', $echo = true ) {
	if ( ! $copyright = circle_option( 'copyright' ) ) {
		return;
	}

	$theme  = wp_get_theme();
	$search = array(
		'{c}',
		'{year}',
		'{sitename}',
		'{theme}',
		'{author}',
	);

	$replace = array(
		' &copy; ',
		date( 'Y' ),
		get_bloginfo( 'name' ),
		sprintf( esc_html__( '%1$s by %2$s', 'circle' ), $theme->name, $theme->display( 'Author' ) ),
		$theme->display( 'Author' ),
	);

	$output  = $before;
	$output .= str_replace( $search, $replace, $copyright );
	$output .= $after;

	/**
	 * Fire a filter $output.
	 *
	 * @var string
	 */
	$output = apply_filters( 'circle_site_copyright', $output );

	if ( ! $echo ) {
		return $output;
	}

	print ( wpautop( $output ) ); // WPCS: XSS OK.
}

/**
 * Display copyright in primary menu.
 *
 * @param  string  $before before.
 * @param  string  $after  after.
 * @param  boolean $echo   echo.
 * @return [html]          output.
 */
function circle_primarymenu_copyright( $before = '', $after = '', $echo = true ) {
	if ( ! $copyright = circle_option( 'menu_copyright' ) ) {
		return;
	}

	$theme  = wp_get_theme();
	$search = array(
		'{c}',
		'{year}',
		'{sitename}',
		'{theme}',
		'{author}',
	);

	$replace = array(
		' &copy; ',
		date( 'Y' ),
		get_bloginfo( 'name' ),
		sprintf( esc_html__( '%1$s by %2$s', 'circle' ), $theme->name, $theme->display( 'Author' ) ),
		$theme->display( 'Author' ),
	);

	$output  = $before;
	$output .= str_replace( $search, $replace, $copyright );
	$output .= $after;

	/**
	 * Fire a filter $output.
	 *
	 * @var string
	 */
	$output = apply_filters( 'circle_primarymenu_copyright', $output );

	if ( ! $echo ) {
		return $output;
	}

	print ( wpautop( $output ) ); // WPCS: XSS OK.
}
/**
 * Circle set header fixed.
 */
function circle_header_fixed() {
	$output = '';
	if ( circle_option( 'navbar_fixed' ) ) {
		$output = sprintf( 'data-spy="affix" data-offset-top="120"' );
	}
	print $output; // WPCS: XSS OK.
}

/**
 * Settings 404 page background.
 */
function circle_404_background() {
	$output = '';
	$background_image = circle_option( '404_background' );
	if ( is_int( $background_image ) ) {
		$background_image = wp_get_attachment_image_url( $background_image, 'full' );
	}
	if ( ! circle_option( 'enable_404_parallax' ) ) {
		$output = sprintf( 'style="background-image: url( %1$s );"', $background_image );
	} else {
		$output = sprintf( 'data-init="parallax" data-parallax-image="%1$s"', $background_image );
	}
	print $output; // WPCS: XSS OK.
}

/**
 * Display footer background.
 *
 * @param  [string] $bg_type  background type.
 * @param  [type]   $bg_color background color.
 * @param  [type]   $bg_image background image.
 * @return [html]           output.
 */
function circle_footer_background( $bg_type, $bg_color, $bg_image ) {
	$background = 'color' === $bg_type ? $bg_color:'url('. $bg_image . ')' . esc_attr( '; background-size: cover' );
	return $background;
}

if ( function_exists( 'vc_manager' ) ) {
	/**
	 * Add vc custom from header content
	 *
	 * @param  [int] $page_id [header content page id].
	 * @return [string]          [css code]
	 */
	function circle_vc_custom_css( $page_id ) {

		$header_custom = array(
			'content_type' => circle_option( 'header_content_type' ),
			'content_page' => circle_option( 'header_content_page' ),
		);
		$_metadata = $content = '';
		if ( is_tax() || is_archive() ) {
			$_term = get_queried_object();

			if ( $_term && isset( $_term->term_id ) ) {
				$_metadata = get_term_meta( $_term->term_id, 'circle-header-custom', true );
			}
		} elseif ( is_single() || is_page() ) {
			$_metadata = get_post_meta( get_the_ID(), 'circle-header-custom', true );
		} elseif ( is_home() ) {
			$_metadata = get_post_meta( get_option( 'page_for_posts' ), 'circle-header-custom', true );
		}
		// Build $_metadata.
		if ( isset( $_metadata ) && is_array( $_metadata ) && $_metadata['is_header_custom'] ) {
			$header_custom = circle_parse_custom_header( $_metadata );
		}

		if ( 'none' === $header_custom['content_type'] ) {
			return;
		}

		if ( 'page' === $header_custom['content_type'] && $page_id = $header_custom['content_page'] ) {
			$content = get_post_field( 'post_content', $page_id );
		}
		// Don't display anything if $content is empty.
		if ( ! $content ) {
			return;
		}

		if ( is_home() ) {
			$shortcodes_custom_css = get_post_meta( $page_id, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $shortcodes_custom_css ) ) {
				$shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
				echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
				print $shortcodes_custom_css; // Wpcs: xss ok.
				echo '</style>';
			}
		} else {
			vc_manager()->vc()->addShortcodesCustomCss( $page_id );
		}
	}
	add_action( 'wp_head', 'circle_vc_custom_css' );
}
