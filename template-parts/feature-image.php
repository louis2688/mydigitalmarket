<?php
/**
 * Template part for feature image post.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Circle
 */

global $postdata;

/**
 * //
 *
 * @var array
 */
$parser_data = wp_parse_args( $postdata['data'], array(
	'output' => '',
	'shortcode' => '',
) );

/**
 * Display feature thumbnail
 */

if ( empty( $postdata['data']['shortcode'] ) ) :

	if ( has_post_thumbnail() ) : ?>
		<div class="bx-blog__media" data-blog-type="image">
			<?php if ( is_single() ) : ?>
			<span class="bx-blog__link">
				<?php the_post_thumbnail(); ?>
			</span>
			<?php else : ?>
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="bx-blog__link">
				<?php the_post_thumbnail(); ?>
			</a>
			<?php endif; ?>
		</div>
		
	<?php endif;

	return;
endif;

/**
 * Display post format feature
 */
switch ( $postdata['post_format'] ) {
	case 'audio':
		printf( '<div class="embed-responsive embed-responsive-audio">%s</div>', $parser_data['output'] ); // WPCS: XSS OK.
		break;

	case 'video':
		if ( has_post_thumbnail() ) : ?>

		<div class="entry-media bx-blog__media" data-blog-type="video">
			<a href="<?php echo esc_url( $parser_data['link'] ); ?>" class="bx-blog__link video-bg">
				<?php the_post_thumbnail(); ?>
			</a>
			<?php printf( '<div class="bx-blog__embed-video">%s</div>', $parser_data['output'] ); // WPCS: XSS OK.?> 
		</div><!-- /.entry-media -->

		<?php else : ?>
			<?php printf( '<div class="bx-blog__no-thumbnail">%s</div>', $parser_data['output'] ); // WPCS: XSS OK.?> 
		<?php
		endif;
		break;

	case 'gallery':
		if ( empty( $parser_data['ids'] ) ) {
			return;
		}
		?>
		<div class="bx-blog__media" data-arrows="true" data-blog-type="slider" data-init="slick" data-adaptive-height="true">
			<?php foreach ( $parser_data['ids'] as $id ) :
				$attachment_meta = wp_prepare_attachment_for_js( $id ); ?>
					<span class="bx-blog__link">
					<?php echo wp_get_attachment_image( $id, 'post-thumbnail' ); ?>
					</span>
			<?php endforeach; ?>
		</div>
		<?php
		break;

	case 'quote': ?>

		<div class="entry-quote">
			<blockquote class="blockquote-white">
				<div class="mb-30"><?php echo wp_kses_post( $parser_data['quote'] ); ?></div>
				<?php if ( $parser_data['cite'] ) : ?>
				<small><?php echo esc_html( $parser_data['cite'] ); ?></small>
				<?php endif;?>
			</blockquote>
		</div><!-- /.entry-quote -->

		<?php
		break;

	case 'link': ?>
		<div class="entry-media entry-media-link">
			<a href="<?php echo esc_url( $parser_data['output'] ); ?>"><?php echo esc_html( $parser_data['output'] ); ?></a>
		</div><?php
		break;
}
