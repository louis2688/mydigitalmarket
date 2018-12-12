<?php
/**
 * The template part for displaying an Author biography
 *
 * @package Circle
 */

$author_bio = get_the_author_meta( 'description' );

if ( ! $author_bio ) {
	return;
}

?>
<div class="about-me">
	<div class="about-me__container clearfix">
		<div class="about-me__avatar">
			<?php
			/**
			 * Filter the The Pearl author bio avatar size.
			 *
			 * @param int $size The avatar height and width size in pixels.
			 */
			$author_bio_avatar_size = apply_filters( 'circle_author_bio_avatar_size', 260 );

			echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
			?>
		</div>
		<div class="about-me__info">
			<h3 class="about-me__title h4"><?php echo get_the_author(); ?></h3>
			<div class="mb-30"><?php the_author_meta( 'description' ); ?></div>
			<div class="about-me__follow text-uppercase">
				<label><?php esc_html( 'Follow:', 'circle' );?></label>
				<span class="bx-blog__share">
				<?php foreach ( Circle_Social_Support::get_profile_providers() as $key => $name ) :
					if ( $social_link = get_the_author_meta( $key ) ) :?>

					<a href="<?php echo esc_url( $social_link ) ?>" title="<?php echo esc_html( $name ) ?>"><i class="fa fa-<?php echo esc_attr( $key ) ?>"></i></a>
					<?php endif; ?>
				<?php endforeach; ?>
				</span>
			</div>
		</div>
	</div>
</div><!-- /.about-me -->
