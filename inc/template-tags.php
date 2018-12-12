<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package circle
 */

if ( ! function_exists( 'circle_posted_time' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function circle_posted_time() {
		$time_string = '<time class="entry-date published updated text-uppercase date-published" datetime="%1$s" data-waypoint="waypointEffect">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published text-uppercase date-published" datetime="%1$s">%2$s</time><time class="published updated text-uppercase date-published" datetime="%3$s" data-waypoint="waypointEffect">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		echo '<span class="posted-on"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a></span>'; // WPCS: XSS OK.
	}
endif;

if ( ! function_exists( 'circle_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function circle_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() && is_single() ) :
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ' ', 'circle' ) );
			if ( $categories_list && circle_categorized_blog() ) :
				printf( '<p class="bx-blog__tags pull-left"><label>' . esc_html__( 'Categories: &nbsp;', 'circle' ) . '</label>%1$s</p>', $categories_list ); // WPCS: XSS OK.
			endif;

			echo sprintf( '<span class="sr-only">%&s</span>', get_the_tag_list() ); // WPCS: XSS OK.

			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( ' - Edit %s', 'circle' ),
					the_title( '<p class="screen-reader-text">"', '"</p>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
			if ( circle_option( 'display_single_sharing' ) ) :
				get_template_part( 'template-parts/post-social-sharing' );
			endif;
		else : ?>
			<a href="<?php echo esc_url( get_permalink() );?>" class="btn btn-default pull-left" title="<?php echo esc_html( the_title() );?>"><?php echo esc_html( 'Learn more', 'circle' );?></a>
		<?php
		if ( circle_option( 'display_archive_sharing' ) ) :
			get_template_part( 'template-parts/post-social-sharing' );
		endif;
		endif;
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function circle_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'circle_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'circle_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so circle_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so circle_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in circle_categorized_blog.
 */
function circle_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'circle_categories' );
}
add_action( 'edit_category', 'circle_category_transient_flusher' );
add_action( 'save_post',     'circle_category_transient_flusher' );

/**
 * Display breadcrumb by breadcrumb_trail.
 */
function circle_breadcrumb() {
	if ( ! class_exists( 'Breadcrumb_Trail' ) ) {
		return;
	}

	return breadcrumb_trail( array(
		'before'          => '',
		'after'           => '',
		'container'       => 'div',
		'show_title'      => true,
		'show_on_front'   => false,
		'show_browse'     => false,
		'echo'            => true,
	) );
}

/**
 * Display post navigation (next/prev) in single.
 */
function circle_post_navigation() {
	get_template_part( 'template-parts/post-navigation' );
}
add_action( 'circle_after_single_post', 'circle_post_navigation', 20 );

/**
 * Display author biography in single.
 */
function circle_author_biography() {
	if ( ! circle_option( 'display_author_bio' ) ) {
		return;
	}
	get_template_part( 'template-parts/biography' );
}
add_action( 'circle_after_single_post', 'circle_author_biography', 30 );

/**
 * Custom excerpt length
 */
function circle_excerpt_length() {
	return apply_filters( 'circle_excerpt_length', 120 );
}
add_filter( 'excerpt_length', 'circle_excerpt_length' );

/**
 * Custom excerpt more
 */
function circle_excerpt_more() {
	return apply_filters( 'circle_excerpt_more', '...' );
}
add_filter( 'excerpt_more', 'circle_excerpt_more' );

/**
 * Display content-part in header.php
 */
function circle_header_content() {
	get_template_part( 'template-parts/header-content' );
}
add_action( 'circle_header', 'circle_header_content', 10 );

/**
 * Display logo-part in footer.php
 */
function circle_footer_logo() {
	circle_site_logo( 'footer_logo', true, false, '<div class="footer__logo">', '</div>' );
}
add_action( 'circle_footer', 'circle_footer_logo', 10 );

/**
 * Display social-part in footer.php
 */
function circle_footer_social() {
	get_template_part( 'template-parts/footer-social' );
}
add_action( 'circle_footer', 'circle_footer_social', 20 );

/**
 * Display copyright-part in footer.php
 */
function circle_footer_copyright() {
	circle_site_copyright( '<div class="footer__copyright">', '</div>' );
}
add_action( 'circle_footer', 'circle_footer_copyright', 30 );

/**
 * Display logo-part in header.php
 */
function circle_menu_logo() {
	circle_site_logo( 'menu_logo', true, false, '<div class="footer__logo">', '</div>' );
}
add_action( 'circle_menu', 'circle_menu_logo', 10 );

/**
 * Display social-part in header.php
 */
function circle_menu_social() {
	get_template_part( 'template-parts/menu-social' );
}
add_action( 'circle_menu', 'circle_menu_social', 20 );

/**
 * Display copyright-part in header.php
 */
function circle_menu_copyright() {
	circle_primarymenu_copyright( '<div class="footer__copyright">', '</div>' );
}
add_action( 'circle_menu', 'circle_menu_copyright', 30 );

/**
 * Display social sharing in single.
 */
function circle_display_single_sharing() {
	if ( ! circle_option( 'display_single_sharing' ) ) {
		return;
	}
	get_template_part( 'template-parts/post-social-sharing' );
}

/**
 * Display social sharing in archive.
 */
function circle_display_archive_sharing() {
	if ( ! circle_option( 'display_archive_sharing' ) ) {
		return;
	}
	get_template_part( 'template-parts/post-social-sharing' );
}
