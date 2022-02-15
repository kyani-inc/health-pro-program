<?php
/**
 * Animated Text Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Animated_Text_Shortcode' ) ) {

	class VCEX_Animated_Text_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', __CLASS__ . '::register_scripts' );
			add_shortcode( 'vcex_animated_text', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Animated_Text::instance();
			}

		}

		/**
		 * Register scripts.
		 */
		public static function register_scripts() {

			$js_extension = '.js';

			if ( defined( 'WPEX_MINIFY_JS' ) && WPEX_MINIFY_JS ) {
				$js_extension = '.min.js';
			}

			wp_register_script(
				'typed',
				vcex_asset_url( 'js/lib/typed' . $js_extension ),
				array(),
				'2.0.12',
				true
			);

			wp_register_script(
				'vcex-animated-text',
				vcex_asset_url( 'js/shortcodes/vcex-animated-text' . $js_extension ),
				array( 'typed' ),
				TTC_VERSION,
				true
			);

		}

		/**
		 * Shortcode scripts.
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'vcex-animated-text' );
		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_animated_text', $atts );
			include( vcex_get_shortcode_template( 'vcex_animated_text' ) );
			do_action( 'vcex_shortcode_after', 'vcex_animated_text', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				// General
				array(
					'type' => 'param_group',
					'param_name' => 'strings',
					'heading' => esc_html__( 'Strings', 'total-theme-core' ),
					'value' => urlencode( json_encode( array(
						array(
							'text' => esc_html__( 'Welcome', 'total-theme-core' ),
						),
						array(
							'text' => esc_html__( 'Bienvenido', 'total-theme-core' ),
						),
						array(
							'text' => esc_html__( 'Welkom', 'total-theme-core' ),
						),
						array(
							'text' => esc_html__( 'Bienvenue', 'total-theme-core' ),
						),
					) ) ),
					'params' => array(
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Text', 'total-theme-core' ),
							'param_name' => 'text',
							'admin_label' => true,
						),
					),
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
					'description' => vcex_shortcode_param_description( 'el_class' ),
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
					'param_name' => 'bottom_margin', // can't name it margin_bottom due to WPBakery parsing issue
					'value' => vcex_margin_choices(),
					'admin_label' => true,
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background Color', 'total' ),
					'param_name' => 'background_color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'padding_all',
					'value' => vcex_padding_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Radius', 'total' ),
					'param_name' => 'border_radius',
					'value' => vcex_border_radius_choices(),
					'group' => esc_html__( 'Style', 'total' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Style', 'total-theme-core' ),
					'param_name' => 'border_style',
					'value' => vcex_border_style_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Width', 'total' ),
					'param_name' => 'border_width',
					'value' => vcex_border_width_choices(),
					'group' => esc_html__( 'Style', 'total' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Border Color', 'total' ),
					'param_name' => 'border_color',
					'group' => esc_html__( 'Style', 'total' ),
				),
				// Typography
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Text Align', 'total-theme-core' ),
					'param_name' => 'text_align',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_responsive_sizes',
					'target' => 'font-size',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'font_family',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'heading' => esc_html__( 'Tag', 'total-theme-core' ),
					'param_name' => 'tag',
					'type' => 'vcex_select_buttons',
					'choices' => 'html_tag',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'color',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'param_name' => 'font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'heading' => esc_html__( 'Font Style', 'total-theme-core' ),
					'param_name' => 'font_style',
					'type' => 'vcex_select_buttons',
					'std' => '',
					'choices' => array(
						'' => esc_html__( 'Normal', 'total-theme-core' ),
						'italic' => esc_html__( 'Italic', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				// Animated Text
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Loop', 'total-theme-core' ),
					'param_name' => 'loop',
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Cursor', 'total-theme-core' ),
					'param_name' => 'type_cursor',
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
					'admin_label' => true,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Speed', 'total-theme-core' ),
					'param_name' => 'speed',
					'description' => esc_html__( 'Enter a value in milliseconds.', 'total-theme-core' ),
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Back Delay', 'total-theme-core' ),
					'param_name' => 'back_delay',
					'std' => '500',
					'description' => esc_html__( 'Enter a value in milliseconds.', 'total-theme-core' ),
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Back Speed', 'total-theme-core' ),
					'param_name' => 'back_speed',
					'description' => esc_html__( 'Enter a value in milliseconds.', 'total-theme-core' ),
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Start Delay', 'total-theme-core' ),
					'param_name' => 'start_delay',
					'description' => esc_html__( 'Enter a value in milliseconds.', 'total-theme-core' ),
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
				),
				array(
					'type'  => 'textfield',
					'heading' => esc_html__( 'Fixed Width', 'total-theme-core' ),
					'param_name' => 'animated_span_width',
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
					'description' => esc_html__( 'Enter a custom width to keep the animated container fixed. Useful when adding custom background or static text after the animated text.', 'total-theme-core' ),
				),
				array(
					'type'  => 'vcex_text_alignments',
					'heading' => esc_html__( 'Text Align', 'total-theme-core' ),
					'param_name' => 'animated_text_align',
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
					'dependency' => array( 'element' => 'animated_span_width', 'not_empty' => true )
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'animated_font_family',
					'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'param_name' => 'animated_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'std' => '',
					'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Line Height', 'total-theme-core' ),
					'param_name' => 'animated_line_height',
					'description' => vcex_shortcode_param_description( 'line_height' ),
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
				),
				array(
					'heading' => esc_html__( 'Font Style', 'total-theme-core' ),
					'param_name' => 'animated_font_style',
					'type' => 'vcex_select_buttons',
					'std' => '',
					'choices' => array(
						'' => esc_html__( 'Normal', 'total-theme-core' ),
						'italic' => esc_html__( 'Italic', 'total-theme-core' ),
					),
					'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Text Decoration', 'total-theme-core' ),
					'param_name' => 'animated_text_decoration',
					'choices' => 'text_decoration',
					'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'animated_color',
					'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background Color', 'total' ),
					'param_name' => 'animated_background_color',
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'animated_padding',
					'value' => vcex_padding_choices(),
					'group' => esc_html__( 'Animated Text', 'total-theme-core' ),
				),
				// Static Text
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Enable', 'total-theme-core' ),
					'param_name' => 'static_text',
					'group' => esc_html__( 'Static Text', 'total-theme-core' ),
					'std' => 'false',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Before', 'total-theme-core' ),
					'param_name' => 'static_before',
					'group' => esc_html__( 'Static Text', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'text' ),
					'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'After', 'total-theme-core' ),
					'param_name' => 'static_after',
					'group' => esc_html__( 'Static Text', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'text' ),
					'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'Outer CSS', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'Inner CSS', 'total-theme-core' ),
					'param_name' => 'animated_css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_animated_text' );

		}

	}

}
new VCEX_Animated_Text_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_vcex_animated_text' ) ) {
	class WPBakeryShortCode_vcex_animated_text extends WPBakeryShortCode {}
}