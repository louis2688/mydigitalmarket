<?php
/**
 * Post format parser content.
 *
 * @package Circle
 */

/**
 * Circle_Post_Format
 */
class Circle_Post_Format {

	/**
	 * //
	 *
	 * @param  string $content Content of the current post.
	 * @return array
	 */
	public static function parse_post_content( $content ) {
		$post_format = get_post_format();
		$callback = sprintf( 'parse_%s', $post_format );

		$class = new static;
		$results = array();

		if ( method_exists( $class, $callback ) ) {
			$results = call_user_func_array( array( $class, $callback ), array( $content ) );

			if ( ! empty( $results['shortcode'] ) ) {
				$content = str_replace( $results['shortcode'], '', $content );
			}
		}

		/**
		 * Filter the post content.
		 *
		 * @param string $content Content of the current post.
		 */
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		/**
		 * //
		 *
		 * @var array
		 */
		$return = apply_filters( 'circle_parse_post_content', array(
			'data' => $results,
			'content' => $content,
			'post_format' => $post_format,
		) );

		return $return;
	}

	/**
	 * //
	 *
	 * <blockquote cite="Source">
	 * 		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
	 * 		<cite>Source</cite>
	 * </blockquote>
	 *
	 * @param  string $content Content of the current post to parser.
	 * @return array
	 */
	public function parse_quote( $content ) {
		if ( ! preg_match( '/<blockquote.*?>((.|\n)*?)<\/blockquote>/', $content, $matches ) ) {
			return;
		}

		$shortcode = $matches[0];
		$cite = '';

		if ( preg_match( '/cite=["|\'](.*)["|\']/', $shortcode, $cites ) ||
			preg_match( '/<cite.*?>(.*)<\/cite>/', $shortcode, $cites ) ) {
			$cite = strip_tags( $cites[1] );
		}

		$quote = strip_tags( $matches[1] );
		$quote = str_replace( $cite, '', $quote );
		$quote = trim( $quote );

		return array(
			'cite' => $cite,
			'quote' => $quote,
			'output' => $shortcode,
			'shortcode' => $shortcode,
		);
	}

	/**
	 * //
	 *
	 * @param  string $content Content of the current post to parser.
	 * @return array
	 */
	public function parse_gallery( $content ) {
		if ( ! preg_match( '/\[gallery.+?\]/', $content, $matches ) ) {
			return;
		}

		$shortcode = $matches[0];
		preg_match( '/ids=["|\'](.*)["|\']/', $shortcode, $ids );

		if ( ! empty( $ids[1] ) ) {
			$ids = explode( ',', $ids[1] );
			$ids = array_filter( $ids, array( __CLASS__, 'is_valid_id' ) );
		}

		return array(
			'ids' => $ids,
			'output' => do_shortcode( $shortcode ),
			'shortcode' => $shortcode,
		);
	}

	/**
	 * //
	 *
	 * @param  string $content Content of the current post to parser.
	 * @return array
	 */
	public function parse_audio( $content ) {
		if ( preg_match( '/(\[audio.+?\])(\[\/audio\])?/', $content, $matches ) ) {
			return array(
				'output' => do_shortcode( $matches[0] ),
				'shortcode' => $matches[0],
			);
		}

		return static::parse_oembed( $content );
	}

	/**
	 * //
	 *
	 * @param  string $content Content of the current post to parser.
	 * @return array
	 */
	public function parse_video( $content ) {
		if ( preg_match( '/(\[video.+?\])(\[\/video\])?/', $content, $matches ) ) {
			return array(
				'output' => do_shortcode( $matches[0] ),
				'shortcode' => $matches[0],
			);
		}

		return static::parse_oembed( $content );
	}

	/**
	 * //
	 *
	 * @param  string $content Content of the current post to parser.
	 * @return array
	 */
	public function parse_oembed( $content ) {
		global $wp_embed;

		if ( preg_match( '/\[embed.+?\]/', $content, $matches ) ) {
			$shortcode = $matches[0];

			if ( $embed = $wp_embed->run_shortcode( $shortcode ) ) {
				return array(
					'output' => do_shortcode( $embed ),
					'shortcode' => $shortcode,
				);
			}
		}

		if ( ! preg_match( '/^\s*(https?:\/\/[^\s"]+)\s*$/im', $content, $matches ) ) {
			return;
		}

		return array(
			'link' => $matches[1],
			'output' => wp_oembed_get( $matches[1] ),
			'shortcode' => $matches[1],
		);
	}

	/**
	 * //
	 *
	 * @param  string $content Content of the current post to parser.
	 * @return array
	 */
	public function parse_link( $content ) {
		if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', $content, $matches ) ) {
			return;
		}
		$shortcode = '';
		if ( preg_match( '%(<a[^>]*>.*?</a>)%i', $content, $regs ) ) {
		    $shortcode = $regs[1];
		}
		$link = esc_url_raw( $matches[1] );
		return array(
			'link' => $link,
			'output' => $link,
			'shortcode' => $shortcode,
		);
	}

	/**
	 * //
	 *
	 * @param  integet $id //.
	 * @return boolean
	 */
	public static function is_valid_id( $id ) {
		return is_numeric( $id );
	}
}
