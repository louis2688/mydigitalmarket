<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Circle
 */

global $postdata, $wp_query;

/**
 * The content of the current post.
 *
 * @var string
 */
$the_content = get_the_content( sprintf(
	wp_kses( __( 'Read more %s', 'circle' ), array( 'span' => array( 'class' => array() ) ) ),
	the_title( '<span class="screen-reader-text">"', '"</span>', false )
) );

$post_class = 'bx-blog__item';
// Parse post content by post format.
$postdata = Circle_Post_Format::parse_post_content( $the_content );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
		<?php circle_posted_time(); ?>
	<header class="entry-header">
	<?php get_template_part( 'template-parts/feature-image' ); ?>

	<?php
	if ( is_single() ) {
		the_title( '<h1 class="bx-blog__name entry-title font-second h3">', '</h1>' );
	} else {
		the_title( '<h2 class="bx-blog__name font-second h3"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
	}
	?>
	</header>
	<div class="entry-content">
		
	<?php
	if ( is_single() ) :
		/**
		 * Filter the post content.
		 *
		 * @param string $content Content of the current post.
		 */
		$content = apply_filters( 'the_content', $postdata['content'] );
		$content = str_replace( ']]>', ']]&gt;', $content );

		print apply_filters( 'circle_the_content', $content ); // WPCS: XSS OK.

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'circle' ),
			'after'  => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		) );
	else :
		circle_excerpt();
	endif;
	?>
	</div>
	<footer class="entry-footer">
		<div class="bx-blog__button clearfix">
			<?php circle_entry_footer();?>
		</div>
	</footer>
</article>
