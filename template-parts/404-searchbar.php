<?php
/**
 * Template part for 404-search.
 *
 * @package Circle
 */

?>
<?php if ( circle_option( 'display_404_searchbar' ) ) : ?>
	<p>
		<form role="search" method="GET" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input name="s" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'circle' ); ?>" type="search">
			<button type="submit" class="btn">
				<span class="sr-only"><?php echo esc_html_x( 'Search', 'submit button', 'circle' ); ?></span>
				<i class="fa fa-search"></i>
			</button>
		</form>
	</p>
	<p><?php esc_html_e( 'or go back to the', 'circle' ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"> <?php esc_html_e( 'Home Page', 'circle' ); ?></a></p>
<?php endif;?>
