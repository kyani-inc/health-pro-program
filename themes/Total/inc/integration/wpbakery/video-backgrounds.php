<?php
namespace TotalTheme\Integration\WPBakery;

defined( 'ABSPATH' ) || exit;

final class Video_Backgrounds {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Shortcodes to add overlay settings to.
	 *
	 * @access private
	 * @var array $shortcodes
	 */
	private static $shortcodes = array();

	/**
	 * Create or retrieve the instance of Disable_About_Screen.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}
		return static::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Modify & Add VC Params.
		add_action( 'vc_after_init', __CLASS__ . '::vc_after_init' );

		// Add classes to the shortcodes.
		add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, __CLASS__ . '::add_classes', 10, 3 );

		// Parse old settings vc popup.
		add_filter( 'vc_edit_form_fields_attributes_vc_section', __CLASS__ . '::vc_section_parse_atts' );
		add_filter( 'vc_edit_form_fields_attributes_vc_row', __CLASS__ . '::vc_row_parse_atts' );

		// Parse old settigns front-end.
		add_filter( 'shortcode_atts_vc_section', __CLASS__ . '::vc_section_parse_atts', 99 );
		add_filter( 'shortcode_atts_vc_row', __CLASS__ . '::vc_row_parse_atts', 99 );

		// Add hooks to insert the overlays into shortcodes.
		$shortcodes = self::get_shortcodes();

		if ( $shortcodes ) {
			foreach ( $shortcodes as $shortcode ) {
				add_filter( 'shortcode_atts_' . $shortcode, __CLASS__ . '::frontend_atts', 99 );
				add_filter( 'wpex_hook_' . $shortcode . '_bottom', __CLASS__ . '::insert_video', 40, 2 ); // priority is important.
			}
		}

	}

	/**
	 * Defines target shortcodes.
	 */
	private static function get_shortcodes() {
		if ( ! self::$shortcodes ) {
			self::$shortcodes = array(
				'vc_row',
				'vc_section',
			);
		}
		return self::$shortcodes;
	}

	/**
	 * Runs on vc_after_init
	 */
	public static function vc_after_init() {
		self::modify_params();
		self::add_params();
	}

	/**
	 * Modify shortcode params.
	 */
	public static function modify_params() {

		if ( ! function_exists( 'vc_update_shortcode_param' ) ) {
			return;
		}

		$shortcodes = self::get_shortcodes();

		if ( $shortcodes ) {
			foreach ( $shortcodes as $shortcode ) {

				// Modify the video_bg_url setting.
				$param = \WPBMap::getParam( $shortcode, 'video_bg_url' );
				if ( $param ) {
					$param['description'] = esc_html__( 'Note: Because of how Youtube works, videos may not always play so it\'s generally recommended to use Self Hosted video backgrounds.', 'total' );
					vc_update_shortcode_param( $shortcode, $param );
				}

				// Modify video_bg_parallax setting.
				$param = \WPBMap::getParam( $shortcode, 'video_bg_parallax' );
				if ( $param ) {
					$param['group'] = esc_html__( 'Video', 'total' );
					$param['dependency'] = array(
						'element' => 'video_bg',
						'value' => 'youtube',
					);
					vc_update_shortcode_param( $shortcode, $param );
				}

				// Modify video_bg_url setting.
				$param = \WPBMap::getParam( $shortcode, 'video_bg_url' );
				if ( $param ) {
					$param['group'] = esc_html__( 'Video', 'total' );
					$param['dependency'] = array(
						'element' => 'video_bg',
						'value' => 'youtube',
					);
					vc_update_shortcode_param( $shortcode, $param );
				}

				// Modify parallax_speed_video setting.
				$param = \WPBMap::getParam( $shortcode, 'parallax_speed_video' );
				if ( $param ) {
					$param['group'] = esc_html__( 'Video', 'total' );
					$param['dependency'] = array(
						'element' => 'video_bg',
						'value' => 'youtube',
					);
					vc_update_shortcode_param( $shortcode, $param );
				}

			}
		}
	}

	/**
	 * Hooks into "wpex_vc_attributes" to add new params.
	 */
	public static function add_params() {
		if ( ! function_exists( 'vc_add_params' ) ) {
			return;
		}
		$shortcodes = self::get_shortcodes();
		if ( $shortcodes ) {
			foreach ( $shortcodes as $shortcode ) {
				vc_add_params( $shortcode, self::get_attributes() );
			}
		}
	}

	/**
	 * Returns vc_map params.
	 */
	private static function get_attributes() {
		return array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Video Background', 'total' ),
				'param_name' => 'video_bg',
				'value' => array(
					esc_html__( 'None', 'total' ) => '',
					esc_html__( 'Youtube', 'total' ) => 'youtube',
					esc_html__( 'Self Hosted', 'total' ) => 'self_hosted',
				),
				'description' => esc_html__( 'Video backgrounds may not display on certain devices that do not allow auto playing videos. It is recommended to apply a standard background image or color as a fallback.', 'total' ),
				'group' => esc_html__( 'Video', 'total' ),
			),
			array(
				'type' => 'vcex_ofswitch',
				'heading' => esc_html__( 'Center Video?', 'total' ),
				'param_name' => 'video_bg_center',
				'std' => 'false',
				'group' => esc_html__( 'Video', 'total' ),
				'dependency'  => array( 'element' => 'video_bg', 'value' => 'self_hosted' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Video URL: MP4 URL', 'total' ),
				'param_name' => 'video_bg_mp4',
				'dependency' => array( 'element' => 'video_bg', 'value' => 'self_hosted' ),
				'group' => esc_html__( 'Video', 'total' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Video URL: WEBM URL', 'total' ),
				'param_name' => 'video_bg_webm',
				'description' => esc_html__( '(Optional)', 'total' ),
				'dependency' => array( 'element' => 'video_bg', 'value' => 'self_hosted' ),
				'group' => esc_html__( 'Video', 'total' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Video URL: OGV URL', 'total' ),
				'param_name' => 'video_bg_ogv',
				'description' => esc_html__( '(Optional)', 'total' ),
				'dependency' => array( 'element' => 'video_bg', 'value' => 'self_hosted' ),
				'group' => esc_html__( 'Video', 'total' ),
			),
		);
	}

	/**
	 * Parses vc_section atts.
	 */
	public static function vc_section_parse_atts( $atts ) {
		if ( ! empty( $atts['video_bg'] ) && 'yes' === $atts['video_bg'] ) {
			$atts['video_bg'] = 'youtube';
		}
		return $atts;
	}

	/**
	 * Parses vc_row atts.
	 */
	public static function vc_row_parse_atts( $atts ) {
		if ( isset( $atts['video_bg'] ) && 'yes' === $atts['video_bg'] ) {
			$atts['video_bg'] = 'self_hosted';
		}
		return $atts;
	}

	/**
	 * Parses atts on front-end to add mock "wpex_self_hosted_video_bg" attribute.
	 */
	public static function frontend_atts( $atts ) {
		if ( ! empty( $atts['video_bg'] ) && 'self_hosted' === $atts['video_bg'] ) {
			$atts['video_bg'] = ''; // prevent VC from loading it's own video struff.
			$atts['wpex_self_hosted_video_bg'] = true;
		}
		return $atts;
	}

	/**
	 * Adds classes to shortcodes that have video backgrounds.
	 */
	public static function add_classes( $class_string, $tag, $atts ) {
		if ( isset( $atts['wpex_self_hosted_video_bg'] ) && true === $atts['wpex_self_hosted_video_bg'] ) {
			$class_string .= ' wpex-has-video-bg';
		}
		return $class_string;
	}

	/**
	 * Inserts the video background HTML into the shortcodes.
	 */
	public static function insert_video( $content, $atts ) {
		if ( $video_bg = self::render_video( $atts ) ) {
			$content .= $video_bg;
		}
		return $content;
	}

	/**
	 * Render the video background.
	 */
	private static function render_video( $atts ) {

		if ( empty( $atts['wpex_self_hosted_video_bg'] )
			&& empty( $atts['video_bg_webm'] )
			&& empty( $atts['video_bg_ogv'] )
			&& empty( $atts['video_bg_mp4'] )
		) {
			return;
		}

		// Video output.
		$video_html = '<div class="wpex-video-bg-wrap">';

			$video_attributes = array(
				'class'       => 'wpex-video-bg',
				'preload'     => 'auto',
				'autoplay'    => 'true',
				'loop'        => 'loop',
				'aria-hidden' => 'true',
				'playsinline' => '',
			);

			if ( ! apply_filters( 'vcex_self_hosted_row_video_sound', false ) ) {
				$video_attributes['muted']  = '';
				$video_attributes['volume'] = '0';
			}

			if ( isset( $atts['video_bg_center'] ) && 'true' == $atts['video_bg_center'] ) {
				$video_attributes['class'] .= ' wpex-video-bg-center';
			}

			/**
			 * Filters the self hosted video background attributes.
			 *
			 * @param array $video_attributes
			 * @param array $atts
			 */
			$video_attributes = apply_filters( 'wpex_self_hosted_video_bg_attributes', $video_attributes, $atts );

			$video_html .= '<video';

				if ( ! empty( $video_attributes ) && is_array( $video_attributes ) ) {
					foreach ( $video_attributes as $name => $value ) {
						if ( $value || '0' === $value ) {
							$video_html .= ' ' . $name . '="' . esc_attr( $value ) . '"';
						} else {
							$video_html .= ' ' . $name;
           				}
       				}
				}

			$video_html .= '>';

				if ( ! empty( $atts['video_bg_webm'] ) ) {
					$video_html .= '<source src="' . esc_url( $atts['video_bg_webm'] ) . '" type="video/webm">';
				}

				if ( ! empty( $atts['video_bg_ogv'] ) ) {
					$video_html .= '<source src="' . esc_url( $atts['video_bg_ogv'] ) . '" type="video/ogg ogv">';
				}

				if ( ! empty( $atts['video_bg_mp4'] ) ) {
					$video_html .= '<source src="' . esc_url( $atts['video_bg_mp4'] ) . '" type="video/mp4">';
				}

			$video_html .= '</video>';

		$video_html .= '</div>';

		/**
		 * Video overlay fallack.
		 *
		 * @deprecated in 3.6.0
		 * @todo Remove. Hook into shortcode_atts to swap video_bg_overlay for standard overlay.
		 */
		if ( ! empty( $atts['video_bg_overlay'] ) && 'none' !== $atts['video_bg_overlay'] ) {
			$video_html .= '<span class="wpex-video-bg-overlay ' . esc_attr( $atts['video_bg_overlay'] ) . '"></span>';
		}

		return $video_html;

	}

}