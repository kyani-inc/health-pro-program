<?php
/**
 * Multi Buttons Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Multi_Buttons_Shortcode' ) ) {

	class VCEX_Multi_Buttons_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_multi_buttons', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Multi_Buttons::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_multi_buttons', $atts );
			include( vcex_get_shortcode_template( 'vcex_multi_buttons' ) );
			do_action( 'vcex_shortcode_after', 'vcex_multi_buttons', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				// Buttons
				array(
					'type' => 'param_group',
					'param_name' => 'buttons',
					'group' => esc_html__( 'Buttons', 'total' ),
					'value' => urlencode( json_encode( array(
						array(
							'text' => esc_html__( 'Button 1', 'total' ),
							'link' => 'url:#',
						),
						array(
							'text' => esc_html__( 'Button 2', 'total' ),
							'link' => 'url:#',
						),
					) ) ),
					'params' => array(
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Text', 'total' ),
							'param_name' => 'text',
							'admin_label' => true,
						),
						array(
							'type' => 'vc_link',
							'heading' => esc_html__( 'Link', 'total' ),
							'param_name' => 'link',
						),
						array(
							'type' => 'vcex_select_buttons',
							'std' => 'flat',
							'heading' => esc_html__( 'Style', 'total' ),
							'param_name' => 'style',
							'choices' => apply_filters( 'wpex_button_styles', array(
								'flat' => esc_html__( 'Flat', 'total' ),
								'outline' => esc_html__( 'Outline', 'total' ),
								'plain-text' => esc_html__( 'Plain Text', 'total' ),
							) ),
						),
						array(
							'type' => 'vcex_button_colors',
							'heading' => esc_html__( 'Prefixed Color', 'total' ),
							'param_name' => 'color',
							'description' => esc_html__( 'Custom color options can be added via a child theme.', 'total' ),
						),
						array(
							'type' => 'vcex_colorpicker',
							'heading' => esc_html__( 'Custom Color', 'total' ),
							'param_name' => 'custom_color',
						),
						array(
							'type' => 'vcex_colorpicker',
							'heading' => esc_html__( 'Custom Color: Hover', 'total' ),
							'param_name' => 'custom_color_hover',
						),
						array(
							'type' => 'vcex_ofswitch',
							'heading' => esc_html__( 'Local Scroll', 'total' ),
							'param_name' => 'local_scroll',
							'std' => 'false',
						),
						array(
							'type' => 'vcex_ofswitch',
							'std' => 'false',
							'heading' => esc_html__( 'Use Download Attribute?', 'total-theme-core' ),
							'param_name' => 'download_attribute',
						),
						vcex_vc_map_add_css_animation(),
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Animation Duration', 'total'),
							'param_name' => 'animation_duration',
							'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total' ),
						),
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Animation Delay', 'total'),
							'param_name' => 'animation_delay',
							'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total' ),
						),
					),
				),
				// General
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Align', 'total' ),
					'param_name' => 'align',
					'std' => 'center',
					'exclude_choices' => array( 'default' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Button Width', 'total' ),
					'param_name' => 'width',
					'description' => vcex_shortcode_param_description( 'px' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Line Height', 'total' ),
					'param_name' => 'line_height',
					'description' => esc_html__( 'Number in pixels.', 'total' ),
					'description' => vcex_shortcode_param_description( 'line_height' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Button Padding', 'total' ),
					'param_name' => 'padding',
					'description' => vcex_shortcode_param_description( 'padding' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Gap', 'total' ),
					'param_name' => 'spacing',
					'description' => esc_html__( 'Enter a custom spacing in pixels that will be added between the buttons.', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border Radius', 'total' ),
					'param_name' => 'border_radius',
					'description' => vcex_shortcode_param_description( 'border_radius' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border Width', 'total' ),
					'param_name' => 'border_width',
					'description' => esc_html__( 'Please enter a px value. This will control the border width when using the outline style button. Default is 3px.', 'total' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Full-Width on Small Screens', 'total' ),
					'param_name' => 'small_screen_full_width',
					'description' => esc_html__( 'If enabled the buttons will render at 100% width on devices under 480px.', 'total' ),
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total' ),
					'param_name' => 'visibility',
				),
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => esc_html__( 'Extra class name', 'total' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
				),
				// Typography
				array(
					'type' => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total' ),
					'param_name' => 'font_family',
					'group' => esc_html__( 'Typography', 'total' ),
				),
				array(
					'type' => 'vcex_responsive_sizes',
					'heading' => esc_html__( 'Font Size', 'total' ),
					'param_name' => 'font_size',
					'group' => esc_html__( 'Typography', 'total' ),
					'target' => 'font-size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total' ),
					'param_name' => 'font_weight',
					'group' => esc_html__( 'Typography', 'total' ),
				),
				array(
					'type' => 'vcex_text_transforms',
					'heading' => esc_html__( 'Text Transform', 'total-theme-core' ),
					'param_name' => 'text_transform',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Letter Spacing', 'total' ),
					'param_name' => 'letter_spacing',
					'group' => esc_html__( 'Typography', 'total' ),
					'description' => vcex_shortcode_param_description( 'letter_spacing' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_multi_buttons' );

		}

	}

}
new VCEX_Multi_Buttons_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Multi_Buttons' ) ) {
	class WPBakeryShortCode_Vcex_Multi_Buttons extends WPBakeryShortCode {}
}