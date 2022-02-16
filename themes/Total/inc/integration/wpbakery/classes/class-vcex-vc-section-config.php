<?php
defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Section Configuration.
 *
 * @package TotalTheme
 * @subpackage WPBakery
 * @version 5.3
 */
if ( ! class_exists( 'VCEX_VC_Section_Config' ) ) {

	class VCEX_VC_Section_Config {

		/**
		 * Main constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {
			add_action( 'vc_after_init', __CLASS__ . '::add_params', 40 ); // add params first
			add_action( 'vc_after_init', __CLASS__ . '::modify_params', 40 ); // priority is crucial.
			add_filter( 'shortcode_atts_vc_section', __CLASS__ . '::parse_attributes', 99 );
			add_filter( 'wpex_vc_section_wrap_atts', __CLASS__ . '::wrap_attributes', 10, 2 );
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, __CLASS__ . '::shortcode_classes', 10, 3 );
			add_filter( 'vc_shortcode_output', __CLASS__ . '::custom_output', 10, 4 );
		}

		/**
		 * Add custom params.
		 *
		 * @since 4.0
		 */
		public static function add_params() {

			if ( ! function_exists( 'vc_add_params' ) ) {
				return;
			}

			$custom_params = array(
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Access', 'total' ),
					'param_name' => 'vcex_user_access',
					'weight' => 99,
					'value' => array(
						esc_html__( 'All', 'total' ) => '',
						esc_html__( 'Logged in', 'total' ) => 'logged_in',
						esc_html__( 'Logged out', 'total' ) => 'logged_out',
						esc_html__( 'First paginated page only', 'total' ) => 'not_paged',
						esc_html__( 'Custom', 'total' ) => 'custom',
					)
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Access', 'total' ),
					'param_name' => 'vcex_user_access_callback',
					'description' => esc_html__( 'Enter your callback function name here.', 'total' ),
					'weight' => 99,
					'dependency' => array( 'element' => 'vcex_user_access', 'value' => 'custom' ),
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total' ),
					'param_name' => 'visibility',
					'weight' => 99,
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Use Featured Image as Background?', 'total' ),
					'param_name' => 'wpex_post_thumbnail_bg',
					'std' => 'false',
					'description' => esc_html__( 'Enable this option to use the current post featured image as the row background.', 'total' ),
					'group' => esc_html__( 'Design Options', 'total' ),
					'weight' => -2,
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Fixed Background Style', 'total' ),
					'param_name' => 'wpex_fixed_bg',
					'group' => esc_html__( 'Design Options', 'total' ),
					'weight' => -2,
					'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
					'value' => array(
						esc_html__( 'None', 'total' ) => '',
						esc_html__( 'Fixed', 'total' ) => 'fixed',
						esc_html__( 'Fixed top', 'total' ) => 'fixed-top',
						esc_html__( 'Fixed bottom', 'total' ) => 'fixed-bottom',
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Background Position', 'total' ),
					'param_name' => 'wpex_bg_position',
					'group' => esc_html__( 'Design Options', 'total' ),
					'description' => esc_html__( 'Enter your custom background position. Example: "center center"', 'total' ),
					'weight' => -2,
					'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Background Image Size', 'total' ),
					'param_name' => 'wpex_bg_size',
					'group' => esc_html__( 'Design Options', 'total' ),
					'description' => esc_html__( 'Specify the size of the background image. Example: 100% 100% ', 'total' ),
					'weight' => -2,
					'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Z-Index', 'total' ),
					'param_name' => 'wpex_zindex',
					'group' => esc_html__( 'Design Options', 'total' ),
					'description' => esc_html__( 'Note: Adding z-index values on rows containing negative top/bottom margins will allow you to overlay the rows, however, this can make it hard to access the page builder tools in the frontend editor and you may need to use the backend editor to modify the overlapped rows.', 'total' ),
					'weight' => -2,
					'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Local Scroll ID', 'total' ),
					'param_name' => 'local_scroll_id',
					'description' => esc_html__( 'Unique identifier for local scrolling links.', 'total' ),
					'weight' => 99,
				),
			);

			vc_add_params( 'vc_section', $custom_params );

		}

		/**
		 * Modify core params.
		 *
		 * @since 4.0
		 */
		public static function modify_params() {

			if ( ! function_exists( 'vc_update_shortcode_param' ) ) {
				return;
			}

			// Move el_id.
			$param = \WPBMap::getParam( 'vc_section', 'el_id' );
			if ( $param ) {
				$param['weight'] = 99;
				vc_update_shortcode_param( 'vc_section', $param );
			}

			// Move el_class.
			$param = \WPBMap::getParam( 'vc_section', 'el_class' );
			if ( $param ) {
				$param['weight'] = 99;
				vc_update_shortcode_param( 'vc_section', $param );
			}

			// Move css_animation.
			$param = \WPBMap::getParam( 'vc_section', 'css_animation' );
			if ( $param ) {
				$param['weight'] = 99;
				vc_update_shortcode_param( 'vc_section', $param );
			}

			// Move full_width.
			$param = \WPBMap::getParam( 'vc_section', 'full_width' );
			if ( $param ) {
				$param['weight'] = 99;
				vc_update_shortcode_param( 'vc_section', $param );
			}

			// Move css.
			$param = \WPBMap::getParam( 'vc_section', 'css' );
			if ( $param ) {
				$param['group'] = esc_html__( 'Design Options', 'total' );
				$param['weight'] = -1;
				vc_update_shortcode_param( 'vc_section', $param );
			}

		}

		/**
		 * Parse VC section attributes on front-end.
		 *
		 * @since 4.0
		 */
		public static function parse_attributes( $atts ) {
			if ( ! empty( $atts['full_width'] )
				&& apply_filters( 'wpex_boxed_layout_vc_stretched_rows_reset', true )
				&& 'boxed' === wpex_site_layout()
			) {
				$atts['full_width'] = '';
				$atts['full_width_boxed_layout'] = 'true';
			}
			return $atts;
		}

		/**
		 * Add custom attributes to the row wrapper.
		 *
		 * @since 4.0
		 */
		public static function wrap_attributes( $wrapper_attributes, $atts ) {
			$inline_style = '';

			// Local scroll ID.
			if ( ! empty( $atts['local_scroll_id'] ) ) {
				$wrapper_attributes[] = 'data-ls_id="#' . esc_attr( $atts['local_scroll_id'] ) . '"';
			}

			// Z-Index.
			if ( ! empty( $atts['wpex_zindex'] ) ) {
				$inline_style .= 'z-index:' . esc_attr( $atts['wpex_zindex'] ) . '!important;';
			}

			// Custom background.
			if ( isset( $atts['wpex_post_thumbnail_bg'] )
				&& 'true' == $atts['wpex_post_thumbnail_bg']
				&& has_post_thumbnail()
			) {
				$inline_style .= 'background-image:url(' . esc_url( get_the_post_thumbnail_url() ) . ')!important;';
			}

			// Background position.
			if ( ! empty( $atts['wpex_bg_position'] ) ) {
				$inline_style .= 'background-position:'. $atts['wpex_bg_position'] .' !important;';
			}

			// Background size.
			if ( ! empty( $atts['wpex_bg_size'] ) ) {
				$inline_style .= 'background-size:'. $atts['wpex_bg_size'] .' !important;';
			}

			// Add inline style to wrapper attributes.
			if ( $inline_style ) {
				$wrapper_attributes[] = 'style="'. $inline_style .'"';
			}

			// Return attributes.
			return $wrapper_attributes;

		}

		/**
		 * Tweak shortcode classes.
		 *
		 * @since 4.0
		 */
		public static function shortcode_classes( $class_string, $tag, $atts ) {

			if ( 'vc_section' !== $tag ) {
				return $class_string;
			}

			$add_classes = array();

			// Tweak vc_section-has-fill class and add custom fill class.
			if ( false !== strpos( $class_string, 'vc_section-has-fill' ) ) {
				$class_string = str_replace( 'vc_section-has-fill', '', $class_string );
				$add_classes['wpex-vc_section-has-fill'] = 'wpex-vc_section-has-fill';
			}

			// Add fill class for parallax and video backgrounds.
			elseif ( ! empty( $atts['vcex_parallax'] ) || ! empty( $atts['wpex_self_hosted_video_bg'] ) ) {
				$add_classes['wpex-vc_section-has-fill'] = 'wpex-vc_section-has-fill';
			}

			// Visibility.
			if ( ! empty( $atts['visibility'] ) ) {
				$add_classes[] = wpex_visibility_class( $atts['visibility'] );
			}

			// Full width.
			if ( ! empty( $atts['full_width'] ) ) {
				$add_classes[] = 'wpex-vc-row-stretched';
			}

			if ( ! empty( $atts['full_width_boxed_layout'] ) ) {
				$add_classes[] = 'wpex-vc-section-boxed-layout-stretched';
			}

			// Remove negative margins.
			if ( empty( $atts['full_width'] ) && isset( $add_classes['wpex-vc_section-has-fill'] ) ) {
				$add_classes[] = 'wpex-vc-reset-negative-margin';
			}

			// Fixed background.
			if ( ! empty( $atts['wpex_fixed_bg'] ) ) {
				$add_classes[] = 'bg-' . esc_attr( $atts['wpex_fixed_bg'] );
			}

			// Add the classes.
			if ( $add_classes ) {
				$add_classes = array_filter( $add_classes, 'sanitize_html_class' );
				$add_classes = array_filter( $add_classes, 'trim' );
				$class_string .= ' ' . implode( ' ', $add_classes );
			}

			// Return class string.
			return $class_string;

		}

		/**
		 * Custom HTML output.
		 *
		 * @since 4.1
		 */
		public static function custom_output( $output, $obj, $atts, $shortcode ) {
			if ( 'vc_section' !== $shortcode ) {
				return $output;
			}

			// Check user settings.
			if ( isset( $atts['vcex_user_access'] )
				&& ! is_admin()
				&& ! vc_is_inline()
			) {
				$callback = ( 'custom' === $atts['vcex_user_access'] && isset( $atts['vcex_user_access_callback'] ) ) ? $atts['vcex_user_access_callback'] : '';
				if ( ! wpex_user_can_access( $atts['vcex_user_access'], $callback ) ) {
					return;
				}
			}

			// Return output.
			return $output;

		}

	}

}
new VCEX_VC_Section_Config();