<?php
/**
 * Template part for post-navigation for single post.
 *
 * @package Circle
 */

$prev_post = get_previous_post();
$next_post = get_next_post();

if ( ! $prev_post && ! $next_post ) {
	return;
}

$posts = array();
$prev_post_id = $next_post_id = 0;

if ( $prev_post ) {
	$posts[] = $prev_post;
	$prev_post_id = $prev_post->ID;
}

if ( $next_post ) {
	$posts[] = $next_post;
	$next_post_id = $next_post->ID;
}

if ( empty( $posts ) ) {
	return;
}
?>
<div class="post-page clearfix">
	<?php foreach ( $posts as $post ) : setup_postdata( $post ); ?>
		<?php
		$class_post_navigation = '';
		if ( get_the_ID() === $prev_post_id ) {
			$class_post_navigation = 'post-page__next pull-left';
			$name_post_navigation = 'Prev';
		} elseif ( get_the_ID() === $next_post_id ) {
			$class_post_navigation = 'post-page__prev pull-right';
			$name_post_navigation = 'Next';
		}
		?>
	<a href="<?php echo esc_url( get_permalink() );?>" class="<?php echo esc_html( $class_post_navigation );?>" title="<?php echo esc_html( the_title() );?>"><?php echo esc_html( $name_post_navigation, 'circle' );?></a>
	<?php endforeach; ?>
</div><!-- /.post-page -->

<?php wp_reset_postdata(); ?>
