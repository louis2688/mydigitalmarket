<?php
/**
 * Template for displaying search forms.
 *
 * @package Circle
 */

?>
<form role="search" method="GET" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="sr-only"><?php echo esc_html_x( 'Search for:', 'label', 'circle' ); ?></span>
		<input name="s" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'circle' ); ?>" type="search">
	</label>
	<button type="submit" class="search-submit">
		<span class="sr-only"><?php echo esc_html_x( 'Search', 'submit button', 'circle' ); ?></span>
	</button>
</form>
