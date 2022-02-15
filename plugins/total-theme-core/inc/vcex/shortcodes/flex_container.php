<?php
/**
 * Flex Container Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Flex_Container_Shortcode' ) ) {

	class VCEX_Flex_Container_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {

			add_shortcode( 'vcex_flex_container', __CLASS__ . '::output' );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Flex_Container::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public static function output( $atts, $content = null ) {
			if ( ! vcex_maybe_display_shortcode( 'vcex_flex_container', $atts ) ) {
				return;
			}

			ob_start();
				do_action( 'vcex_shortcode_before', 'vcex_flex_container', $atts );
				include( vcex_get_shortcode_template( 'vcex_flex_container' ) );
				do_action( 'vcex_shortcode_after', 'vcex_flex_container', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				array(
					'type' => 'vcex_notice',
					'param_name' => 'editor_notice',
					'text' => esc_html__( 'Because of how the frontend editor works, there could be some design inconsistencies when using this element, so it\'s best used via the backend.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Direction', 'total-theme-core' ),
					'param_name' => 'flex_direction',
					'choices' => array(
						'row' => esc_html__( 'Horizontal (Row)', 'total-theme-core' ),
						'column' => esc_html__( 'Vertical', 'total-theme-core' ),
					),
					'description' => sprintf( esc_html__( 'If you are not familiar with the flex model you can learn more via the %sFirefox manual%s.', 'total-theme-core' ), '<a href="https://developer.mozilla.org/en-US/docs/Learn/CSS/CSS_layout/Flexbox" target="_blank" rel="noopener noreferrer">', '</a>' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Align Items', 'total-theme-core' ),
					'param_name' => 'align_items',
					'value' => vcex_align_items_choices(),
					'description' => esc_html__( 'Set the align-items CSS property.', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Justify Content', 'total-theme-core' ),
					'param_name' => 'justify_content',
					'value' => vcex_justify_content_choices(),
					'description' => esc_html__( 'Set the justify-content CSS property.', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Stack Elements Breakpoint', 'total-theme-core' ),
					'param_name' => 'row_stack_bp',
					'value' => vcex_breakpoint_choices(),
					'dependency' => array( 'element' => 'flex_direction', 'value' => 'row' ),
					'description' => esc_html__( 'Select a breakpoint if you wish to stack your elements vertically at a certain point. Note: Flex Basis, Align Items and Justify Content values are ignored at the stacking point to prevent issues as the flex direction will change from row to column.', 'total' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Stack Reverse order', 'total-theme-core' ),
					'param_name' => 'row_stack_reverse',
					'std' => 'false',
					'dependency' => array( 'element' => 'row_stack_bp', 'not_empty' => true ),
					'description' => esc_html__( 'Reverse the order of elements when they stack.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Gap', 'total-theme-core' ),
					'param_name' => 'gap',
					'description' => esc_html__( 'Spacing between elements. Default is 20px.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Stack Gap', 'total-theme-core' ),
					'param_name' => 'row_stack_gap',
					'dependency' => array( 'element' => 'row_stack_bp', 'not_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Element Width (Flex Basis)', 'total-theme-core' ),
					'param_name' => 'flex_basis',
					'description' => esc_html__( 'Set the initial width for the inner elements. Enter a single value to target all elements or a coma separated string to target each elements individually. Make sure to keep the Gap in consideration, for example if you enter 50% for the flex-basis each item will have an initial width of 50% but the default gap is 20px so there will not be enough room for both elements.', 'total-theme-core' ),
					'dependency' => array( 'element' => 'flex_direction', 'value' => 'row' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Flex Grow', 'total-theme-core' ),
					'param_name' => 'flex_grow',
					'std' => 'false',
					'description' => esc_html__( 'When enabled it will set the inner items flex-grow property to 1 so that they will stretch to fill up empty space. Note: If you have set a custom flex basis for your items only items with an "auto" value will be allowed to grow.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Flex Wrap', 'total-theme-core' ),
					'param_name' => 'flex_wrap',
					'std' => 'false',
					'description' => esc_html__( 'Automatically wrap elements so they can take up as much space as needed.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'param_name' => 'el_class',
					'description' => vcex_shortcode_param_description( 'el_class' ),
				),
				// Design
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'width',
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Shadow', 'total' ),
					'param_name' => 'shadow',
					'value' => vcex_shadow_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_flex_container' );

		}

	}

}
new VCEX_Flex_Container_Shortcode;

if ( class_exists( 'WPBakeryShortCodesContainer' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Flex_Container' ) ) {
	class WPBakeryShortCode_Vcex_Flex_Container extends WPBakeryShortCodesContainer {}
}