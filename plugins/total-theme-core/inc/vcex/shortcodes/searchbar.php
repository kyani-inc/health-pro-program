<?php
/**
 * Searchbar Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Searchbar_Shortcode' ) ) {

	class VCEX_Searchbar_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_searchbar', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Searchbar::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_searchbar', $atts );
			include( vcex_get_shortcode_template( 'vcex_searchbar' ) );
			do_action( 'vcex_shortcode_after', 'vcex_searchbar', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {
			$params = array(
				// General
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Autofocus', 'total-theme-core'),
					'param_name' => 'autofocus',
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Full-Width on Mobile', 'total-theme-core'),
					'param_name' => 'fullwidth_mobile',
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total-theme-core' ),
					'param_name' => 'visibility',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Element ID', 'total-theme-core' ),
					'param_name' => 'unique_id',
					'admin_label' => true,
					'description' => vcex_shortcode_param_description( 'unique_id' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'el_class' ),
					'param_name' => 'classes',
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
				// Query
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Advanced Search', 'total-theme-core' ),
					'param_name' => 'advanced_query',
					'group' => esc_html__( 'Query', 'total-theme-core' ),
					'description' => esc_html__( 'Example: ', 'total-theme-core' ) . 'post_type=portfolio&taxonomy=portfolio_category&term=advertising' . '<br>' . esc_html__( 'You can use term=current_term and author=current_author for dynamic templates.', 'total-theme-core' ),
				),
				// Widths
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Wrap Width', 'total-theme-core' ),
					'param_name' => 'wrap_width',
					'group' => esc_html__( 'Widths', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Aligment', 'total-theme-core' ),
					'param_name' => 'wrap_float',
					'std' => 'left',
					'exclude_choices' => array( '', 'default' ),
					'dependency' => array( 'element' => 'wrap_width', 'not_empty' => true ),
					'group' => esc_html__( 'Widths', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Input Width', 'total-theme-core' ),
					'param_name' => 'input_width',
					'group' => esc_html__( 'Widths', 'total-theme-core' ),
					'description' => '70%',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Button Width', 'total-theme-core' ),
					'param_name' => 'button_width',
					'group' => esc_html__( 'Widths', 'total-theme-core' ),
					'description' => '28%',
				),
				// Input
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Placeholder', 'total-theme-core' ),
					'param_name' => 'placeholder',
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'input_color',
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'input_font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Letter Spacing', 'total-theme-core' ),
					'param_name' => 'input_letter_spacing',
					'description' => vcex_shortcode_param_description( 'letter_spacing' ),
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_transforms',
					'heading' => esc_html__( 'Text Transform', 'total-theme-core' ),
					'param_name' => 'input_text_transform',
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'input_font_weight',
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border Radius', 'total-theme-core' ),
					'param_name' => 'input_border_radius',
					'description' => vcex_shortcode_param_description( 'border_radius' ),
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Width', 'total' ),
					'param_name' => 'input_border_width',
					'value' => vcex_border_width_choices(),
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Border Color', 'total-theme-core' ),
					'param_name' => 'input_border_color',
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'input_padding',
					'description' => vcex_shortcode_param_description( 'padding' ),
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				// Submit
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Button Text', 'total-theme-core' ),
					'param_name' => 'button_text',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_transforms',
					'heading' => esc_html__( 'Text Transform', 'total-theme-core' ),
					'param_name' => 'button_text_transform',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'button_font_weight',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'button_font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Letter Spacing', 'total-theme-core' ),
					'param_name' => 'button_letter_spacing',
					'description' => vcex_shortcode_param_description( 'letter_spacing' ),
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border Radius', 'total-theme-core' ),
					'param_name' => 'button_border_radius',
					'description' => vcex_shortcode_param_description( 'border_radius' ),
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background', 'total-theme-core' ),
					'param_name' => 'button_bg',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background: Hover', 'total-theme-core' ),
					'param_name' => 'button_bg_hover',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'button_color',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color: Hover', 'total-theme-core' ),
					'param_name' => 'button_color_hover',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'Input CSS', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
			);
			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_searchbar' );
		}

	}

}
new VCEX_Searchbar_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Searchbar' ) ) {
	class WPBakeryShortCode_Vcex_Searchbar extends WPBakeryShortCode {}
}