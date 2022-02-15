<?php
/**
 * Next & Previous Posts Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Post_Next_Prev_Shortcode' ) ) {

	class VCEX_Post_Next_Prev_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_post_next_prev', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Post_Next_Prev::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_post_next_prev', $atts );
			include( vcex_get_shortcode_template( 'vcex_post_next_prev' ) );
			do_action( 'vcex_shortcode_after', 'vcex_post_next_prev', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				array(
					'type' => 'vcex_select_buttons',
					'std' => 'icon',
					'heading' => esc_html__( 'Link Format', 'total-theme-core' ),
					'param_name' => 'link_format',
					'choices' => array(
						'icon' => esc_html__( 'Icon Only', 'total-theme-core' ),
						'title' => esc_html__( 'Post Name', 'total-theme-core' ),
						'custom' => esc_html__( 'Custom Text', 'total-theme-core' ),
					),
				),
				array(
					'type' => 'dropdown',
					'std' => 'chevron',
					'heading' => esc_html__( 'Arrows Style', 'total-theme-core' ),
					'param_name' => 'icon_style',
					'value' => array(
						esc_html__( 'Chevron', 'total-theme-core' ) => 'chevron',
						esc_html__( 'Chevron Circle', 'total-theme-core' ) => 'chevron-circle',
						esc_html__( 'Angle', 'total-theme-core' ) => 'angle',
						esc_html__( 'Double Angle', 'total-theme-core' ) => 'angle-double',
						esc_html__( 'Arrow', 'total-theme-core' ) => 'arrow',
						esc_html__( 'Long Arrow', 'total-theme-core' ) => 'long-arrow',
						esc_html__( 'Caret', 'total-theme-core' ) => 'caret',
						esc_html__( 'Cirle', 'total-theme-core' ) => 'arrow-circle',
						esc_html__( 'None', 'total-theme-core' ) => '',
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Arrow Side Margin', 'total' ),
					'param_name' => 'icon_margin',
					'value' => vcex_margin_choices(),
					'dependency' => array( 'element' => 'link_format', 'value' => array( 'title', 'custom' ) ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Previous Text', 'total-theme-core' ),
					'param_name' => 'previous_link_custom_text',
					'dependency' => array( 'element' => 'link_format', 'value' => 'custom' )
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Next Text', 'total-theme-core' ),
					'param_name' => 'next_link_custom_text',
					'dependency' => array( 'element' => 'link_format', 'value' => 'custom' )
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Previous Link', 'total-theme-core' ),
					'param_name' => 'previous_link',
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Next Link', 'total-theme-core' ),
					'param_name' => 'next_link',
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Reverse Order', 'total-theme-core' ),
					'param_name' => 'reverse_order',
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'In Same Term?', 'total-theme-core' ),
					'param_name' => 'in_same_term',
				),
				array(
					'type' => 'textfield',
					'std' => '',
					'heading' => esc_html__( 'Same Term Taxonomy Name', 'total-theme-core' ),
					'param_name' => 'same_term_tax',
					'description' => esc_html__( 'If you want to display posts from the same term enter the taxonomy name here. Such as category, portfolio_category, staff_category..etc.', 'total-theme-core' ),
					'dependency' => array( 'element' => 'in_same_term', 'value' => 'true' ),
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total-theme-core' ),
					'param_name' => 'visibility',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-theme-core' ),
				),
				vcex_vc_map_add_css_animation(),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Duration', 'total'),
					'param_name' => 'animation_duration',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total'),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Delay', 'total'),
					'param_name' => 'animation_delay',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total'),
				),
				// Style
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Max Width', 'total-theme-core' ),
					'param_name' => 'max_width',
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Aligment', 'total-theme-core' ),
					'param_name' => 'float', // can't use "align" because it's already taken for the Text Align.
					'dependency' => array( 'element' => 'max_width', 'not_empty' => true ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Expand', 'total-theme-core' ),
					'param_name' => 'expand',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Text Align', 'total-theme-core' ),
					'param_name' => 'align',
					'dependency' => array( 'element' => 'expand', 'value' => 'false' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Button Spacing', 'total' ),
					'param_name' => 'spacing',
					'value' => vcex_margin_choices(),
					'description' => esc_html__( 'Margin applied to each button. If you want a 10px spacing between your buttons select 5px.', 'total' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				// Buttons
				array(
					'type' => 'vcex_button_styles',
					'heading' => esc_html__( 'Style', 'total-theme-core' ),
					'param_name' => 'button_style',
					'group' => esc_html__( 'Buttons', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Buttons', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_button_colors',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'button_color',
					'group' => esc_html__( 'Buttons', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Line Height', 'total-theme-core' ),
					'param_name' => 'line_height',
					'description' => vcex_shortcode_param_description( 'line_height' ),
					'group' => esc_html__( 'Buttons', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Min-Width', 'total-theme-core' ),
					'param_name' => 'button_min_width',
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'Buttons', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_post_next_prev' );

		}

	}

}
new VCEX_Post_Next_Prev_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Post_Next_Prev' ) ) {
	class WPBakeryShortCode_Vcex_Post_Next_Prev extends WPBakeryShortCode {}
}