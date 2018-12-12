<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Circle
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<!-- Blog list -->
			<?php
			if ( have_posts() ) :

				if ( is_home() && ! is_front_page() ) : ?>
					<h1 class="screen-reader-text"><?php single_post_title(); ?></h1>
				<?php
				endif;

				/**
				 * Begin the loop
				 */

				/* Start the Loop */
				while ( have_posts() ) : the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() );

				endwhile;

				/**
				 * End the loop
				 */

				circle_pager();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif; ?>
			
		</main>
		<!-- #main -->
	</div>
<!-- #primary -->
<?php
get_sidebar();
get_footer();
