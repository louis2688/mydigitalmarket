<?php
/**
 * Circle social support.
 *
 * @package Circle
 */

/**
 * Circle_Social_Support
 */
class Circle_Social_Support {

	/**
	 * Conditionally hook into WordPress.
	 */
	public static function init() {
		add_action( 'customize_register', array( __CLASS__, 'customize_register' ), 9 );
	}

	/**
	 * Add settings to the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 */
	public static function customize_register( $wp_customize ) {
		$wp_customize->add_panel( 'circle_social', array(
			'title'          => esc_html__( 'Social Network', 'circle' ),
			'theme_supports' => '',
		) );

		$wp_customize->add_section( 'circle_social_share', array(
			'title'       => esc_html__( 'Social Sharing', 'circle' ),
			'description' => esc_html__( 'Select sharing providers to display.', 'circle' ),
			'panel'       => 'circle_social',
		) );

		$wp_customize->add_section( 'circle_social_profile', array(
			'title'       => esc_html__( 'Social Follow', 'circle' ),
			'description' => esc_html__( 'Edit your social profiles.', 'circle' ),
			'panel'       => 'circle_social',
		) );

		foreach ( static::get_sharing_providers() as $id => $provider ) {
			$id = sprintf( 'at-social[share][%1$s]', sanitize_key( $id ) );

			$wp_customize->add_setting( $id, array(
				'default'           => false,
				'type'              => 'option',
				'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( $id, array(
				'type'    => 'checkbox',
				'label'   => sprintf( esc_html__( 'Share on %s', 'circle' ), $provider['name'] ),
				'section' => 'circle_social_share',
			) );
		}

		foreach ( static::get_profile_providers() as $id => $name ) {
			$id = sprintf( 'at-social[profile][%1$s]', sanitize_key( $id ) );

			$wp_customize->add_setting( $id, array(
				'default'           => '',
				'type'              => 'option',
				'sanitize_callback' => 'esc_url_raw',
			) );

			$wp_customize->add_control( $id, array(
				'label'   => $name,
				'type'    => 'text',
				'section' => 'circle_social_profile',
			) );
		}
	}

	/**
	 * Add social field to user profile.
	 *
	 * @param  array $fields User contact fields.
	 * @return array
	 */
	public static function register_profile_social( $fields ) {
		$providers = static::get_profile_providers();

		$social_fields = apply_filters( 'circle_social_profile_fields', $providers );

		return wp_parse_args( $fields, $social_fields );
	}

	/**
	 * Get social network settings.
	 *
	 * @param  string $key The key of a recognized setting.
	 * @return array|false
	 */
	public static function get_setting( $key = 'all' ) {
		$saved = (array) get_option( 'at-social' );

		$defaults = array(
			'share'   => array(),
			'profile' => array(),
		);

		$options = wp_parse_args( $saved, $defaults );

		if ( 'all' !== $key ) {
			return isset( $options[ $key ] ) ? $options[ $key ] : false;
		}

		return $options;
	}

	/**
	 * Social sharing providers.
	 *
	 * @return array
	 */
	public static function get_sharing_providers() {
		$providers = array(
			'facebook'    => array(
				'name' => esc_html__( 'Facebook', 'circle' ),
				'link' => 'http://www.facebook.com/sharer.php?u={url}',
			),

			'twitter'     => array(
				'name' => esc_html__( 'Twitter', 'circle' ),
				'link' => 'https://twitter.com/share?url={url}&text={title}',
			),

			'google-plus' => array(
				'name' => esc_html__( 'Google Plus', 'circle' ),
				'link' => 'https://plus.google.com/share?url={url}',
			),

			'pinterest'   => array(
				'name' => esc_html__( 'Pinterest', 'circle' ),
				'link' => 'https://pinterest.com/pin/create/bookmarklet/?url={url}&description={title}',
			),

			'linkedin'    => array(
				'name' => esc_html__( 'LinkedIn', 'circle' ),
				'link' => 'http://www.linkedin.com/shareArticle?url={url}&title={title}',
			),

			'digg'        => array(
				'name' => esc_html__( 'Digg', 'circle' ),
				'link' => 'http://digg.com/submit?url={url}&title={title}',
			),

			'tumblr'      => array(
				'name' => esc_html__( 'Tumblr', 'circle' ),
				'link' => 'https://www.tumblr.com/widgets/share/tool?canonicalUrl={url}&title={title}',
			),

			'reddit'      => array(
				'name' => esc_html__( 'Reddit', 'circle' ),
				'link' => 'http://reddit.com/submit?url={url}&title={title}',
			),

			'stumbleupon' => array(
				'name' => esc_html__( 'Stumbleupon', 'circle' ),
				'link' => 'http://www.stumbleupon.com/submit?url={url}&title={title}',
			),
		);

		/**
		 * //
		 *
		 * @var array
		 */
		$providers = apply_filters( 'circle_social_sharing_providers', $providers );

		return $providers;
	}

	/**
	 * Social profile providers.
	 *
	 * @return array
	 */
	public static function get_profile_providers() {
		$providers = array(
			'facebook'   => esc_html__( 'Facebook', 'circle' ),
			'google'     => esc_html__( 'Google plus', 'circle' ),
			'twitter'    => esc_html__( 'Twitter', 'circle' ),
			'github'     => esc_html__( 'Github', 'circle' ),
			'instagram'  => esc_html__( 'Instagram', 'circle' ),
			'pinterest'  => esc_html__( 'Pinterest', 'circle' ),
			'linkedin'   => esc_html__( 'LinkedIn', 'circle' ),
			'skype'      => esc_html__( 'Skype', 'circle' ),
			'tumblr'     => esc_html__( 'Tumblr', 'circle' ),
			'youtube'    => esc_html__( 'Youtube', 'circle' ),
			'vimeo'      => esc_html__( 'Vimeo', 'circle' ),
			'flickr'     => esc_html__( 'Flickr', 'circle' ),
			'dribbble'   => esc_html__( 'Dribbble', 'circle' ),
		);

		/**
		 * //
		 *
		 * @var array
		 */
		$providers = apply_filters( 'circle_social_profile_providers', $providers );

		return $providers;
	}

	/**
	 * Parse share raw link with the_post.
	 *
	 * @param  string $link Raw link to parser.
	 * @return string
	 */
	public static function parse_share_link( $link ) {
		$link = str_replace( array( '{url}', '{title}' ), array( get_permalink(), get_the_title() ), $link );
		return apply_filters( 'circle_social_parse_sharing', $link );
	}

	/**
	 * //
	 *
	 * @param  string $value Input value.
	 * @return array
	 */
	public static function sanitize_checkbox( $value ) {
		return (bool) $value;
	}

	/**
	 * //
	 *
	 * @return array
	 */
	public static function active_share() {
		$setting = static::get_setting( 'share' );
		$providers = static::get_sharing_providers();

		$return = array();

		foreach ( $setting as $id => $active ) {
			if ( $active && isset( $providers[ $id ] ) ) {
				$return[ $id ] = $providers[ $id ];

				if ( in_the_loop() ) {
					$return[ $id ]['share'] = static::parse_share_link( $return[ $id ]['link'] );
				} else {
					$return[ $id ]['share'] = '';
				}
			}
		}

		return $return;
	}

	/**
	 * //
	 *
	 * @return array
	 */
	public static function active_profile() {
		$setting = static::get_setting( 'profile' );
		$providers = static::get_profile_providers();

		$return = array();

		foreach ( $setting as $id => $link ) {
			if ( ! empty( $link ) && isset( $providers[ $id ] ) ) {
				$return[ $id ] = array(
					'name' => $providers[ $id ],
					'link' => $link,
				);
			}
		}

		return $return;
	}
}

/**
 * Social Init.
 */
Circle_Social_Support::init();
