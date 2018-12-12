<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Circle
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area pt-50 pb-50">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<h3 class="comments-title h4">
			<?php
				printf( // WPCS: XSS OK.
					esc_html( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'circle' ) ),
					number_format_i18n( get_comments_number() ),
					'<span>' . get_the_title() . '</span>'
				);
			?>
		</h3>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'circle' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'circle' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'circle' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>

		<ol class="comment-list mb-50">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 140,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'circle' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'circle' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'circle' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php
		endif; // Check for comment navigation.

	endif; // Check for have_comments().


	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'circle' ); ?></p>
	<?php
	endif;

	// Comment form.
	comment_form( array(
		'class_submit'  => 'btn btn-default',
		'class_form'    => 'comment-form',
		'label_submit'  => 'submit',
		'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
		'comment_field' => '<p class="mb-35"><label class="screen-reader-text" for="comment">' . esc_html__( 'Comment', 'circle' ) . '</label> <textarea id="comment" name="comment" cols="45" rows="5" required="required" placeholder="' .  esc_html__( 'Comment', 'circle' ) . '"></textarea></p>',
		'comment_notes_before' => '',
		'title_reply_before'   => '<h3 id="reply-title" class="comments-title h4">',
		'title_reply_after'    => '</h3>',

		'fields' => array(
			'author' => '<p class="mb-35"> <label class="sr-only" for="author">' . esc_html__( 'Name', 'circle' ) . '</label> <input id="author" name="author" type="text" placeholder="' .  esc_html__( 'Name*', 'circle' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" required="required"></p>',
			'email'  => '<p class="mb-35"><label class="sr-only" for="email">' . esc_html__( 'Email', 'circle' ) . '</label> <input id="email" name="email" type="email" placeholder="' .  esc_html__( 'Email*', 'circle' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" required="required"></p>',
			'url'    => '',
		),
	) );

	?>

</div><!-- #comments -->
