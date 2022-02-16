<?php
namespace TotalTheme\Integration\WPBakery;

defined( 'ABSPATH' ) || exit;

final class Shape_Dividers {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Elements to add shape divider settings to.
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

		// Register params via vc_add_params.
		add_action( 'vc_after_init', __CLASS__ . '::add_params', 40);

		// Add classes to the shortcodes.
		add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, __CLASS__ . '::add_classes', 10, 3 );

		// Add hooks to insert the dividers into shortcodes.
		$shortcodes = self::get_shortcodes();

		if ( $shortcodes ) {
			foreach ( $shortcodes as $shortcode ) {
				add_filter( 'wpex_hook_' . $shortcode . '_bottom', __CLASS__ . '::insert_dividers', 100, 2 );
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
	 * Add new params.
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

		$divider_types = array_flip( wpex_get_shape_divider_types() );

		return array(
			array(
				'type' => 'vcex_notice',
				'param_name' => 'vcex_notice__dividers',
				'text' => esc_html__( 'Insert a SVG shape above or below your row. Works best with stretched rows and you may want to add a padding to the row to offset your divider and prevent text from overlapping.', 'total-theme-core' ),
				'group' => esc_html__( 'Dividers', 'total' ),
			),
			array(
				'type' => 'vcex_subheading',
				'text' => esc_html__( 'Top Divider', 'total' ),
				'param_name' => 'vcex_subheading__divider',
				'group' => esc_html__( 'Dividers', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Divider Type', 'total' ),
				'param_name' => 'wpex_shape_divider_top',
				'group' => esc_html__( 'Dividers', 'total' ),
				'value' => $divider_types,
			),
			array(
				'type' => 'vcex_visibility',
				'heading' => esc_html__( 'Visibility', 'total' ),
				'group' => esc_html__( 'Dividers', 'total' ),
				'param_name' => 'wpex_shape_divider_top_visibility',
				'dependency' => array( 'element' => 'wpex_shape_divider_top', 'not_empty' => true ),
			),
			array(
				'type' => 'vcex_ofswitch',
				'heading' => esc_html__( 'Invert', 'total' ),
				'param_name' => 'wpex_shape_divider_top_invert',
				'std' => 'false',
				'group' => esc_html__( 'Dividers', 'total' ),
				'dependency' => array(
					'element' => 'wpex_shape_divider_top',
					'value' => array( 'triangle', 'triangle_asymmetrical', 'arrow', 'clouds', 'curve', 'waves' ),
				),
			),
			array(
				'type' => 'vcex_ofswitch',
				'heading' => esc_html__( 'Flip', 'total' ),
				'param_name' => 'wpex_shape_divider_top_flip',
				'std' => 'false',
				'group' => esc_html__( 'Dividers', 'total' ),
				'dependency' => array(
					'element' => 'wpex_shape_divider_top',
					'value' => array( 'tilt', 'triangle_asymmetrical', 'clouds', 'waves' ),
				),
			),
			array(
				'type' => 'vcex_colorpicker',
				'heading' => esc_html__( 'Divider Color', 'total' ),
				'param_name' => 'wpex_shape_divider_top_color',
				'group' => esc_html__( 'Dividers', 'total' ),
				'description' => esc_html__( 'Your color should equal the background color of the previous or next section.', 'total' ),
				'dependency' => array( 'element' => 'wpex_shape_divider_top', 'not_empty' => true ),
			),
			array(
				'type' => 'vcex_number',
				'heading' => esc_html__( 'Divider Height', 'total' ),
				'param_name' => 'wpex_shape_divider_top_height',
				'group' => esc_html__( 'Dividers', 'total' ),
				'description' => esc_html__( 'Enter your custom height in pixels.', 'total' ),
				'dependency' => array(
					'element' => 'wpex_shape_divider_top',
					'value' => array( 'tilt', 'triangle', 'triangle_asymmetrical', 'arrow', 'clouds', 'curve', 'waves' ),
				),
				'min' => 1,
				'step' => 1,
				'max' => 500,
			),
			array(
				'type' => 'vcex_number',
				'heading' => esc_html__( 'Divider Width', 'total' ),
				'param_name' => 'wpex_shape_divider_top_width',
				'group' => esc_html__( 'Dividers', 'total' ),
				'description' => esc_html__( 'Enter your custom percentage based width. For example to make your shape twice as big enter 200.', 'total' ),
				'dependency' => array(
					'element' => 'wpex_shape_divider_top',
					'value' => array( 'triangle', 'triangle_asymmetrical', 'arrow', 'curve', 'waves' ),
				),
				'min' => 100,
				'step' => 1,
				'max' => 300,
			),
			array(
				'type' => 'vcex_subheading',
				'text' => esc_html__( 'Bottom Divider', 'total' ),
				'param_name' => 'vcex_subheading__divider--bottom',
				'group' => esc_html__( 'Dividers', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Divider Type', 'total' ),
				'param_name' => 'wpex_shape_divider_bottom',
				'group' => esc_html__( 'Dividers', 'total' ),
				'value' => $divider_types,
			),
			array(
				'type' => 'vcex_visibility',
				'heading' => esc_html__( 'Visibility', 'total' ),
				'group' => esc_html__( 'Dividers', 'total' ),
				'param_name' => 'wpex_shape_divider_bottom_visibility',
				'dependency' => array( 'element' => 'wpex_shape_divider_bottom', 'not_empty' => true ),
			),
			array(
				'type' => 'vcex_ofswitch',
				'heading' => esc_html__( 'Invert', 'total' ),
				'param_name' => 'wpex_shape_divider_bottom_invert',
				'std' => 'false',
				'group' => esc_html__( 'Dividers', 'total' ),
				'dependency' => array(
					'element' => 'wpex_shape_divider_bottom',
					'value' => array( 'triangle', 'triangle_asymmetrical', 'arrow', 'clouds', 'curve', 'waves' ),
				),
			),
			array(
				'type' => 'vcex_ofswitch',
				'heading' => esc_html__( 'Flip', 'total' ),
				'param_name' => 'wpex_shape_divider_bottom_flip',
				'std' => 'false',
				'group' => esc_html__( 'Dividers', 'total' ),
				'dependency' => array(
					'element' => 'wpex_shape_divider_bottom',
					'value' => array( 'tilt', 'triangle_asymmetrical', 'clouds', 'waves' )
				),
			),
			array(
				'type' => 'vcex_colorpicker',
				'heading' => esc_html__( 'Divider Color', 'total' ),
				'param_name' => 'wpex_shape_divider_bottom_color',
				'group' => esc_html__( 'Dividers', 'total' ),
				'dependency' => array( 'element' => 'wpex_shape_divider_bottom', 'not_empty' => true ),
			),
			array(
				'type' => 'vcex_number',
				'heading' => esc_html__( 'Divider Height', 'total' ),
				'param_name' => 'wpex_shape_divider_bottom_height',
				'group' => esc_html__( 'Dividers', 'total' ),
				'description' => esc_html__( 'Enter your custom height in pixels.', 'total' ),
				'dependency' => array(
					'element' => 'wpex_shape_divider_bottom',
					'value' => array(
						'tilt',
						'triangle',
						'triangle_asymmetrical',
						'arrow',
						'clouds',
						'curve',
						'waves'
					),
				),
				'min' => 1,
				'step' => 1,
				'max' => 500,
			),
			array(
				'type' => 'vcex_number',
				'heading' => esc_html__( 'Divider Width', 'total' ),
				'param_name' => 'wpex_shape_divider_bottom_width',
				'group' => esc_html__( 'Dividers', 'total' ),
				'description' => esc_html__( 'Enter your custom percentage based width. For example to make your shape twice as big enter 200.', 'total' ),
				'dependency' => array(
					'element' => 'wpex_shape_divider_bottom',
					'value' => array( 'triangle', 'triangle_asymmetrical', 'arrow', 'curve', 'waves' )
				),
				'min' => 100,
				'step' => 1,
				'max' => 300,
			),
		);

	}

	/**
	 * Adds classes to shortcodes that have dividers.
	 */
	public static function add_classes( $class_string, $tag, $atts ) {

		if ( ! empty( $atts['wpex_shape_divider_top'] ) ) {
			$class_string .= ' wpex-has-shape-divider-top';
		}

		if ( ! empty( $atts['wpex_shape_divider_bottom'] ) ) {
			$class_string .= ' wpex-has-shape-divider-bottom';
		}

		return $class_string;
	}

	/**
	 * Inserts the divider HTML into the shortcodes.
	 */
	public static function insert_dividers( $content, $atts ) {

		// Top Divider
		if ( ! empty( $atts['wpex_shape_divider_top'] ) && is_string( $atts['wpex_shape_divider_top'] ) ) {
			$content .= self::top_divider( $atts );
		}

		// Bottom Divider.
		if ( ! empty( $atts['wpex_shape_divider_bottom'] ) && is_string( $atts['wpex_shape_divider_bottom'] ) ) {
			$content .= self::bottom_divider( $atts );
		}

		// Return filter content.
		return $content;

	}

	/**
	 * Returns the top divider html.
	 */
	public static function top_divider( $atts ) {
		return wpex_get_shape_divider(
			'top',
			$atts['wpex_shape_divider_top'],
			wpex_get_shape_divider_settings( 'top', $atts )
		);
	}

	/**
	 * Returns the bottom divider html.
	 */
	public static function bottom_divider( $atts ) {
		return wpex_get_shape_divider(
			'bottom',
			$atts['wpex_shape_divider_bottom'],
			wpex_get_shape_divider_settings( 'bottom', $atts )
		);
	}

}