<?php
/**
 * Newsletter Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Newsletter_Shortcode' ) ) {

	class VCEX_Newsletter_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_newsletter_form', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Newsletter_Form::instance();
			}
		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_newsletter_form', $atts );
			include( vcex_get_shortcode_template( 'vcex_newsletter_form' ) );
			do_action( 'vcex_shortcode_after', 'vcex_newsletter_form', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {
			$params = array(
				// General
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Form Action URL', 'total-theme-core' ),
					'param_name' => 'form_action',
					'admin_label' => true,
					'value'       => '',
					'description' => esc_html__( 'Enter your newsletter service form action URL.', 'total-theme-core' ) . ' <a href="https://wpexplorer-themes.com/total/docs/mailchimp-form-action-url/" target="_blank" rel="noopener noreferrer">'. esc_html__( 'Learn More', 'total-theme-core' ) .' &rarr;</a>',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin', // can't name it margin_bottom due to WPBakery parsing issue
					'value' => vcex_margin_choices(),
					'admin_label' => true,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'input_width',
					'description' => vcex_shortcode_param_description( 'width' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Alignment', 'total-theme-core' ),
					'param_name' => 'input_align',
					'std' => '',
					'dependency' => array( 'element' => 'input_width', 'not_empty' => true ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Stack Fields?', 'total-theme-core'),
					'param_name' => 'stack_fields',
					'std' => 'false',
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Full-Width on Mobile', 'total-theme-core'),
					'param_name' => 'fullwidth_mobile',
					'dependency' => array( 'element' => 'stack_fields', 'value' => 'false' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Gap', 'total-theme-core' ),
					'param_name' => 'gap',
					'value' => vcex_margin_choices(),
					'description' => esc_html__( 'Spacing between the input field and the button.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total-theme-core' ),
					'param_name' => 'visibility',
				),
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => esc_html__( 'Element ID', 'total-theme-core' ),
					'param_name' => 'unique_id',
					'description' => vcex_shortcode_param_description( 'unique_id' ),
				),
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-theme-core' ),
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
				// Input
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Placeholder', 'total-theme-core' ),
					'param_name' => 'placeholder_text',
					'value' => esc_html__( 'Enter your email address', 'total-theme-core' ),
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Name Attribute', 'total-theme-core' ),
					'param_name' => 'input_name',
					'value' => 'EMAIL',
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background', 'total-theme-core' ),
					'param_name' => 'input_bg',
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'input_color',
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Border Color', 'total-theme-core' ),
					'param_name' => 'input_border_color',
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Height', 'total-theme-core' ),
					'param_name' => 'input_height',
					'description' => vcex_shortcode_param_description( 'height' ),
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'input_padding',
					'description' => vcex_shortcode_param_description( 'padding' ),
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border', 'total-theme-core' ),
					'param_name' => 'input_border',
					'description' => esc_html__( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total-theme-core' ),
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
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'input_weight',
					'group' => esc_html__( 'Input', 'total-theme-core' ),
				),
				// Submit
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Text', 'total-theme-core' ),
					'param_name' => 'submit_text',
					'std' => esc_html__( 'Sign Up', 'total-theme-core' ),
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background', 'total-theme-core' ),
					'param_name' => 'submit_bg',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background: Hover', 'total-theme-core' ),
					'param_name' => 'submit_hover_bg',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'submit_color',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color: Hover', 'total-theme-core' ),
					'param_name' => 'submit_hover_color',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Height', 'total-theme-core' ),
					'param_name' => 'submit_height',
					'description' => vcex_shortcode_param_description( 'height' ),
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'submit_padding',
					'description' => vcex_shortcode_param_description( 'padding' ),
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border', 'total-theme-core' ),
					'param_name' => 'submit_border',
					'description' => esc_html__( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total-theme-core' ),
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border Radius', 'total-theme-core' ),
					'param_name' => 'submit_border_radius',
					'description' => vcex_shortcode_param_description( 'border_radius' ),
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'submit_font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Letter Spacing', 'total-theme-core' ),
					'param_name' => 'submit_letter_spacing',
					'description' => vcex_shortcode_param_description( 'letter_spacing' ),
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'submit_weight',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_transforms',
					'heading' => esc_html__( 'Text Transform', 'total-theme-core' ),
					'param_name' => 'submit_text_transform',
					'group' => esc_html__( 'Submit', 'total-theme-core' ),
				),
				// Hidden Fields
				array(
					'type' => 'exploded_textarea',
					'heading' => esc_html__( 'Hidden Fields', 'total-theme-core' ),
					'param_name' => 'hidden_fields',
					'description' => esc_html__( 'Here you can define hidden fields to be added to the newsletter form. Enter each set of hidden fields using the format name|value. One per line.', 'total-theme-core' ),
					'group' => esc_html__( 'Hidden Fields', 'total-theme-core' ),
				),
				// Deprecated attributes.
				array( 'type' => 'hidden', 'param_name' => 'mailchimp_form_action' ),
			);

			/**
			 * Filters the vcex_newsletter_form shortcode params.
			 *
			 * @param array $params
			 */
			$params = (array) apply_filters( 'vcex_shortcode_params', $params, 'vcex_newsletter_form' );

			return $params;
		}

		/**
		 * Parses deprecated params.
		 */
		public static function parse_deprecated_attributes( $atts = '' ) {
			if ( empty( $atts ) || ! is_array( $atts ) ) {
				return $atts;
			}

			if ( ! empty( $atts['mailchimp_form_action'] ) ) {
				if ( empty( $atts['form_action'] ) ) {
					$atts['form_action'] = $atts['mailchimp_form_action'];
				}
				unset( $atts['mailchimp_form_action'] );
			}

			return $atts;
		}

	}

}
new VCEX_Newsletter_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Newsletter_Form' ) ) {
	class WPBakeryShortCode_Vcex_Newsletter_Form extends WPBakeryShortCode {}
}