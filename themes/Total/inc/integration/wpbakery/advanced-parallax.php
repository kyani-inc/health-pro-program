<?php
namespace TotalTheme\Integration\WPBakery;

defined( 'ABSPATH' ) || exit;

final class Advanced_Parallax {

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

		// Parse old vc_row settings.
		add_filter( 'vc_edit_form_fields_attributes_vc_row', __CLASS__ . '::edit_form_fields' );

		// Add hooks to insert the parallax HTML into shortcodes.
		$shortcodes = self::get_shortcodes();

		if ( $shortcodes ) {
			foreach ( $shortcodes as $shortcode ) {

				// Insert the parallax html to the element.
				add_filter( 'wpex_hook_' . $shortcode . '_bottom', __CLASS__ . '::insert_parallax', 30, 2 ); // priority is important.

				// Parse attributes to set parallax to null when vcex_parallax is selected and assign bg image.
				add_filter( 'shortcode_atts_' . $shortcode, __CLASS__ . '::parse_shortcode_atts', 99 );

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

				// Alter Parallax dropdown.
				$param = \WPBMap::getParam( $shortcode, 'parallax' );
				if ( $param ) {
					$param['group'] = esc_html__( 'Parallax', 'total' );
					$param['value'][esc_html__( 'Advanced Parallax', 'total' )] = 'vcex_parallax';
					vc_update_shortcode_param( $shortcode, $param );
				}

				// Alter Parallax image location.
				$param = \WPBMap::getParam( $shortcode, 'parallax_image' );
				if ( $param ) {
					$param['group'] = esc_html__( 'Parallax', 'total' );
					vc_update_shortcode_param( $shortcode, $param );
				}

				// Alter Parallax speed location.
				$param = \WPBMap::getParam( $shortcode, 'parallax_speed_bg' );
				if ( $param ) {
					$param['group'] = esc_html__( 'Parallax', 'total' );
					$param['dependency'] = array(
						'element' => 'parallax',
						'value' => array( 'content-moving', 'content-moving-fade' ),
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
				'type'        => 'vcex_ofswitch',
				'heading'     => esc_html__( 'Enable parallax for mobile devices', 'total' ),
				'param_name'  => 'parallax_mobile',
				'vcex'        => array( 'off' => 'no', 'on'  => 'yes' ),
				'std'         => 'no',
				'description' => esc_html__( 'Parallax effects would most probably cause slowdowns when your site is viewed in mobile devices. By default it is disabled.', 'total' ),
				'group'       => esc_html__( 'Parallax', 'total' ),
				'dependency'  => array(
					'element' => 'parallax',
					'value'   => 'vcex_parallax',
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Parallax Style', 'total' ),
				'param_name'  => 'parallax_style',
				'group'       => esc_html__( 'Parallax', 'total' ),
				'value'       => array(
					esc_html__( 'Cover', 'total' )               => '',
					esc_html__( 'Fixed and Repeat', 'total' )    => 'fixed-repeat',
					esc_html__( 'Fixed and No-Repeat', 'total' ) => 'fixed-no-repeat',
				),
				'dependency'  => array(
					'element' => 'parallax',
					'value'   => 'vcex_parallax',
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Parallax Direction', 'total' ),
				'param_name'  => 'parallax_direction',
				'value'       => array(
					esc_html__( 'Up', 'total' )    => '',
					esc_html__( 'Down', 'total' )  => 'down',
					esc_html__( 'Left', 'total' )  => 'left',
					esc_html__( 'Right', 'total' ) => 'right',
				),
				'group'       => esc_html__( 'Parallax', 'total' ),
				'dependency'  => array(
					'element' => 'parallax',
					'value'   => 'vcex_parallax',
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Parallax Speed', 'total' ),
				'param_name'  => 'parallax_speed',
				'description' => esc_html__( 'The movement speed, value should be between 0.1 and 1.0. A lower number means slower scrolling speed. Be mindful of the background size and the dimensions of your background image when setting this value. Faster scrolling means that the image will move faster, make sure that your background image has enough width or height for the offset.', 'total' ),
				'group'       => esc_html__( 'Parallax', 'total' ),
				'dependency'  => array(
					'element' => 'parallax',
					'value'   => 'vcex_parallax',
				),
			),
		);
	}

	/**
	 * Adds classes to shortcodes that have parallax.
	 *
	 * @param string $class_string The class string to add to the shortcode.
	 * @param string $tag The shortcode tag.
	 * @param array $atts The shortcode attributes.
	 */
	public static function add_classes( $class_string, $tag, $atts ) {
		if ( ! empty( $atts['vcex_parallax'] ) ) {
			$class_string .= ' wpex-parallax-bg-wrap';
		}
		return $class_string;
	}

	/**
	 * Inserts the parallax HTML into the shortcodes.
	 *
	 * @param  string $content The wpex_hook_{shortcode}_bottom content.
	 * @param array $atts The shortcode attributes.
	 */
	public static function insert_parallax( $content, $atts ) {
		if ( $parallax = self::render_parallax_bg( $atts ) ) {
			$content .= $parallax;
		}
		return $content;
	}

	/**
	 * Parses the shortcode attributes to set parallax to null if vcex_parallax is selected.
	 *
	 * @param array $atts The shortcode attributes.
	 */
	public static function parse_shortcode_atts( $atts ) {

		// Set parallax image equal to post thumbnail.
		if ( ( ! empty( $atts['parallax'] ) || ! empty( $atts['vcex_parallax'] ) )
			&& isset( $atts['wpex_post_thumbnail_bg'] )
			&& 'true' == $atts['wpex_post_thumbnail_bg']
			&& has_post_thumbnail()
		) {
			$atts['parallax_image'] = get_post_thumbnail_id();
		}

		// Advanced parallax.
		$advanced_parallax = false;

		if ( ! empty( $atts['parallax'] ) ) {
			if ( 'vcex_parallax' === $atts['parallax']
				|| 'simple' === $atts['parallax']
				|| 'advanced' === $atts['parallax']
				|| 'true' === $atts['parallax']
			) {
				$advanced_parallax = true;
			}
		} elseif ( ! empty( $atts['bg_style'] )
			&& ( 'parallax' === $atts['bg_style'] || 'parallax-advanced' === $atts['bg_style'] )
		) {
			$advanced_parallax = true;
		}

		if ( $advanced_parallax ) {
			$atts['parallax']      = '';
			$atts['vcex_parallax'] = true; // this is a "fake" attribute.

			// Set the correct bg image from css param if not defined.
			if ( empty( $atts['parallax_image'] ) && empty( $atts['bg_image'] ) ) {
				$bg_image = self::get_image_from_css( $atts );
				if ( $bg_image ) {
					$atts['bg_image'] = $bg_image;
				}
			}

		}

		return $atts;
	}

	/**
	 * Parses shortcode attributes when editing the shortcodes.
	 *
	 * @param array $atts The shortcode attributes.
	 */
	public static function edit_form_fields( $atts ) {
		if ( ! empty( $atts['parallax'] ) ) {
			if ( 'simple' === $atts['parallax'] || 'advanced' === $atts['parallax'] || 'true' === $atts['parallax'] ) {
				$atts['parallax'] = 'vcex_parallax';
			}
		} elseif ( ! empty( $atts['bg_style'] )
			&& ( 'parallax' == $atts['bg_style'] || 'parallax-advanced' == $atts['bg_style'] )
		) {
			$atts['parallax'] = 'vcex_parallax';
			unset( $atts['bg_style'] );
		}
		return $atts;
	}

	/**
	 * Parses shortcode attributes when editing the shortcodes.
	 *
	 * @param array $atts The shortcode attributes.
	 */
	private static function get_image_from_css( $atts ) {
		if ( empty( $atts['css'] ) ) {
			return false;
		}
		if ( preg_match( '/\?id=(\d+)/', $atts['css'], $id ) === false ) {
			return false;
		}
		if ( count( $id ) < 2 ) {
			return false;
		}
		$id = $id[1];
		return wp_get_attachment_url( $id );
	}

	/**
	 * Render the parallax bg.
	 */
	public static function render_parallax_bg( $shortcode_atts ) {

		// Return if disabled or there is a video background.
		if ( empty( $shortcode_atts['vcex_parallax'] ) || ! empty( $shortcode_atts['wpex_self_hosted_video_bg'] ) ) {
			return;
		}

		// Sanitize $bg_image.
		if ( ! empty( $shortcode_atts['parallax_image'] ) ) {
			$bg_image = wp_get_attachment_url( $shortcode_atts['parallax_image'] );
		} elseif ( ! empty( $shortcode_atts['bg_image'] ) ) {
			$bg_image = $shortcode_atts['bg_image']; // Old deprecated setting
		} else {
			return;
		}

		// Default settings.
		$parallax_style = '';
		$parallax_speed = '0.2';
		$parallax_direction = 'up';
		$fixed_bg = 'false';

		// Custom settings.
		if ( ! empty( $shortcode_atts['parallax_style'] ) ) {
			$parallax_style = wp_strip_all_tags( $shortcode_atts['parallax_style'] );
			if ( 'fixed-repeat' === $shortcode_atts['parallax_style'] || 'fixed-no-repeat' === $shortcode_atts['parallax_style'] ) {
			//	$fixed_bg = 'true';
			}
		}

		if ( ! empty( $shortcode_atts['parallax_speed'] ) ) {
			$parallax_speed = floatval( $shortcode_atts['parallax_speed'] );
		}

		if ( ! empty( $shortcode_atts['parallax_direction'] ) ) {
			$parallax_direction = wp_strip_all_tags( $shortcode_atts['parallax_direction'] );
		}

		// Classes.
		$classes = array( 'wpex-parallax-bg' );

		if ( $parallax_style ) {
			$classes[] = $parallax_style;
		}

		if ( isset( $shortcode_atts['parallax_mobile'] ) && 'no' === $shortcode_atts['parallax_mobile'] ) {
			$classes[] = 'not-mobile';
		}

		/**
		 * Filters the parallax classes
		 *
		 * @param array $classes
		 * @param array $shortcode_atts
		 */
		$classes = apply_filters( 'wpex_parallax_classes', $classes, $shortcode_atts );

		$html_attributes = array(
			'class'          => $classes,
			'data-direction' => $parallax_direction,
			'data-velocity'  => '-' . $parallax_speed,
			'data-fixed'     => $fixed_bg,
			'style'          => 'background-image:url(' . esc_url( $bg_image ) . ');',
		);

		/**
		 * Filters the parallax background html attributes.
		 *
		 * @param array $html_attributes.
		 * @param array $shortcode_attributes
		 */
		$attributes = apply_filters( 'wpex_parallax_html_attributes', $html_attributes, $shortcode_atts );

		return wpex_parse_html( 'div', $attributes );

	}

}