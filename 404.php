<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Circle
 */

$GLOBALS['hidden_header_page'] = true;
$GLOBALS['hidden_breadcrumbs'] = true;
$GLOBALS['hidden_layout']      = true;
$GLOBALS['hidden_container']   = true;
get_header(); ?>
	<div class="error-404" <?php circle_404_background();?>>
		<div class="error-wrap">
				<p class="h3 text-uppercase"><?php esc_html_e( 'Something went wrong!', 'circle' ); ?></p>
				<p class="h1 error-404__title"><?php esc_html_e( '404', 'circle' ); ?></p>
				<p class="h3 font-second"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'circle' ); ?></p>
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'circle' ); ?></p>
				<div class="404_searchbar">
				<?php get_template_part( 'template-parts/404-searchbar' );?>
				</div>
		</div>
	</div>
<?php
get_footer();
