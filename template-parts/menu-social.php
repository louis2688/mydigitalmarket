<?php
/**
 * Template part for footer social.
 *
 * @package circle
 */

if ( ! circle_option( 'display_menu_social' ) ) {
	return;
}

?>
<!-- Display social -->
<nav class="footer__socials">
<?php if ( $profiles = Circle_Social_Support::active_profile() ) : ?>
	<?php foreach ( $profiles as $key => $value ) : ?>
		<a class="footer__icon" href="<?php echo esc_url( $value['link'] ) ?>" title="<?php echo esc_attr( $value['name'] ) ?>"><i class="fa fa-<?php echo esc_attr( $key ) ?>"></i></a>
	<?php endforeach; ?>
<?php endif ?>
</nav>
