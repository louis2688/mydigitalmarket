<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Circle
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( '' ); ?>>
	<header class="entry-header">
	<?php get_template_part( 'template-parts/feature-image' ); ?>
	<?php the_title( '<h1 class="bx-blog__name entry-title font-second h3"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
	?>
	</header>
	<div class="entry-content">
	<?php
		the_content();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'circle' ),
			'after'  => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		) );
	?>
	</div>
	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<div class="bx-blog__button clearfix">
			<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'circle' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
			</div>
		</footer><!-- /.entry-footer -->
	<?php endif; ?>
</article>
