<?php
/**
 * Template part for post social sharing.
 *
 * @package Circle
 */

if ( ! $share = Circle_Social_Support::active_share() ) {
	return;
}
if ( is_single() ) {
	$el_class = 'share__single';
} else {
	$el_class = 'share__archive';
}

?>
<p class="bx-blog__share pull-right <?php echo esc_html( $el_class );?>">
	<?php foreach ( $share as $key => $value ) : ?>
		<a href="<?php echo esc_url( $value['share'] ) ?>" title="<?php echo esc_attr( $value['name'] ) ?>"><i class="fa fa-<?php echo esc_attr( $key ) ?>"></i></a>
	<?php endforeach; ?>
</p>
