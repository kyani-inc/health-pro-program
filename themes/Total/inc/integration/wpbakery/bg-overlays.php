<?php
namespace TotalTheme\Integration\WPBakery;

defined( 'ABSPATH' ) || exit;

final class BG_Overlays {

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

		// Register params via vc_attributes.
		add_action( 'vc_after_init', __CLASS__ . '::add_params' );

		// Add classes to the shortcodes.
		add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, __CLASS__ . '::add_classes', 10, 3 );

		// Parse old vc_row settings.
		add_filter( 'vc_edit_form_fields_attributes_vc_row', __CLASS__ . '::edit_form_fields' );

		// Add hooks to insert the overlays into shortcodes.
		$shortcodes = self::get_shortcodes();

		if ( $shortcodes ) {
			foreach ( $shortcodes as $shortcode ) {
				add_filter( 'wpex_hook_' . $shortcode . '_bottom', __CLASS__ . '::insert_overlay', 20, 2 ); // priority is important.
			}
		}

	}

	/**
	 * Defines target shortcodes.
	 */
	private static function get_shortcodes() {
		if ( ! self::$shortcodes ) {
			self::$shortcodes = array(
				'vc_section',
				'vc_row',
			);
		}
		return self::$shortcodes;
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
				'heading' => esc_html__( 'Background Overlay', 'total' ),
				'param_name' => 'wpex_bg_overlay',
				'group' => esc_html__( 'Overlay', 'total' ),
				'value' => array(
					esc_html__( 'None', 'total' ) => '',
					esc_html__( 'Color', 'total' ) => 'color',
					esc_html__( 'Dark', 'total' ) => 'dark',
					esc_html__( 'Dotted', 'total' ) => 'dotted',
					esc_html__( 'Diagonal Lines', 'total' ) => 'dashed',
					esc_html__( 'Custom', 'total' ) => 'custom',
				),
			),
			array(
				'type' => 'vcex_colorpicker',
				'heading' => esc_html__( 'Background Overlay Color', 'total' ),
				'param_name' => 'wpex_bg_overlay_color',
				'group' => esc_html__( 'Overlay', 'total' ),
				'dependency' => array( 'element' => 'wpex_bg_overlay', 'value' => array( 'color', 'dark', 'dotted', 'dashed', 'custom' ) ),
			),
			array(
				'type' => 'attach_image',
				'heading' => esc_html__( 'Custom Overlay Pattern', 'total' ),
				'param_name' => 'wpex_bg_overlay_image',
				'group' => esc_html__( 'Overlay', 'total' ),
				'dependency' => array( 'element' => 'wpex_bg_overlay', 'value' => array( 'custom' ) ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Background Overlay Opacity', 'total' ),
				'param_name' => 'wpex_bg_overlay_opacity',
				'dependency' => array( 'element' => 'wpex_bg_overlay', 'value' => array( 'color', 'dark', 'dotted', 'dashed', 'custom' ) ),
				'group' => esc_html__( 'Overlay', 'total' ),
				'description' => esc_html__( 'Default', 'total' ) . ': 0.65',
			),
		);
	}

	/**
	 * Parses shortcode attributes when editing the shortcodes.
	 */
	public static function edit_form_fields( $atts ) {
		if ( ! empty( $atts['video_bg_overlay'] ) && 'none' !== $atts['video_bg_overlay'] ) {
			$atts['wpex_bg_overlay'] = $atts['video_bg_overlay'];
			unset( $atts['video_bg_overlay'] );
		}
		return $atts;
	}

	/**
	 * Adds classes to shortcodes that have overlays.
	 */
	public static function add_classes( $class_string, $tag, $atts ) {
		if ( ! empty( $atts['wpex_bg_overlay'] ) && 'none' !== $atts['wpex_bg_overlay'] ) {
			$class_string .= ' wpex-has-overlay';
		}
		return $class_string;
	}

	/**
	 * Inserts the overlay HTML into the shortcodes.
	 */
	public static function insert_overlay( $content, $atts ) {
		if ( $overlay = self::render_overlay( $atts ) ) {
			$content .= $overlay;
		}
		return $content;
	}

	/**
	 * Render the overlay.
	 */
	private static function render_overlay( $atts ) {

		$overlay = isset( $atts['wpex_bg_overlay'] ) ? $atts['wpex_bg_overlay'] : '';

		if ( $overlay && 'none' !== $overlay ) {

			$style = '';

			if ( 'custom' === $overlay && ! empty( $atts['wpex_bg_overlay_image'] ) ) {
				if ( $custom_img = wp_get_attachment_url( $atts['wpex_bg_overlay_image'] ) ) {
					$style .= 'background-image:url(' . esc_url( $custom_img ) . ');';
				}
			}

			if ( ! empty( $atts['wpex_bg_overlay_color'] ) ) {
				$overlay_color = wpex_parse_color( $atts['wpex_bg_overlay_color'] );
				$style .= 'background-color:' . esc_attr( $overlay_color ) . ';';
			}

			if ( ! empty( $atts['wpex_bg_overlay_opacity'] ) ) {
				$style .= 'opacity:' . esc_attr( $atts['wpex_bg_overlay_opacity'] ) . ';';
			}

			$overlay_attributes = array(
				'class' => 'wpex-bg-overlay ' . sanitize_html_class( $overlay ),
			);

			if ( $style ) {
				$overlay_attributes['style'] = $style;
				//$overlay_attributes['data-style'] = $style; // removed in 5.3 as it's no longer needed.
			}

			$overlay = wpex_parse_html( 'span', $overlay_attributes );

			return '<div class="wpex-bg-overlay-wrap">' . $overlay . '</div>';

		}

	}

}