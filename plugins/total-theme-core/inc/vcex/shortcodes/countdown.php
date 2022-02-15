<?php
/**
 * Countdown Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Countdown_Shortcode' ) ) {

	class VCEX_Countdown_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_countdown', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Countdown::instance();
			}

		}

		/**
		 * Enqueue scripts.
		 */
		public function enqueue_scripts( $atts ) {

			wp_enqueue_script(
				'countdown',
				vcex_asset_url( 'js/lib/countdown.min.js' ),
				array( 'jquery' ),
				'2.1.0',
				true
			);

			wp_enqueue_script(
				'vcex-countdown',
				vcex_asset_url( 'js/shortcodes/vcex-countdown.min.js' ),
				array( 'jquery', 'countdown' ),
				TTC_VERSION,
				true
			);

			if ( vcex_vc_is_inline() || ! empty( $atts['timezone'] ) ) {

				wp_enqueue_script(
					'moment-with-locales',
					vcex_asset_url( 'js/lib/moment-with-locales.min.js' ),
					array( 'jquery' ),
					'2.10.0',
					true
				);

				wp_enqueue_script(
					'moment-timezone-with-data',
					vcex_asset_url( 'js/lib/moment-timezone-with-data.min.js' ),
					array( 'jquery' ),
					'2.10.0',
					true
				);

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_countdown', $atts );
			include( vcex_get_shortcode_template( 'vcex_countdown' ) );
			do_action( 'vcex_shortcode_after', 'vcex_countdown', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				// General
				array(
					'type' => 'vcex_timezones',
					'heading' => esc_html__( 'Time Zone', 'total' ),
					'param_name' => 'timezone',
					'description' => esc_html__( 'If a time zone is not selected the time zone will be based on the visitors computer time.', 'total' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'End Month', 'total' ),
					'param_name' => 'end_month',
					'value' => array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' ),
					'admin_label' => true,
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'End Day', 'total' ),
					'param_name' => 'end_day',
					'value' => array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31' ),
					'admin_label' => true,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'End Year', 'total' ),
					'param_name' => 'end_year',
					'admin_label' => true,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'End Time', 'total' ),
					'param_name' => 'end_time',
					'description' => esc_html__( 'Enter your custom end time in military format. Example if your event starts at 1:30pm enter 13:30', 'total' ),
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
					'description' => vcex_shortcode_param_description( 'el_class' ),
					'param_name' => 'el_class',
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
					'group' => esc_html__( 'Style', 'total' ),
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
					'heading' => esc_html__( 'Shadow', 'total' ),
					'param_name' => 'shadow',
					'value' => vcex_shadow_choices(),
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
					'heading' => esc_html__( 'Text Align', 'total' ),
					'param_name' => 'text_align',
					'group' => esc_html__( 'Typography', 'total' ),
				),
				array(
					'type' => 'vcex_responsive_sizes',
					'target' => 'font-size',
					'heading' => esc_html__( 'Font Size', 'total' ),
					'param_name' => 'font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Typography', 'total' ),
				),
				array(
					'type' => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total' ),
					'param_name' => 'font_family',
					'group' => esc_html__( 'Typography', 'total' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total' ),
					'param_name' => 'font_weight',
					'group' => esc_html__( 'Typography', 'total' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total' ),
					'param_name' => 'color',
					'group' => esc_html__( 'Typography', 'total' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Italic', 'total' ),
					'param_name' => 'italic',
					'group' => esc_html__( 'Typography', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Line Height', 'total' ),
					'param_name' => 'line_height',
					'description' => vcex_shortcode_param_description( 'line_height' ),
					'group' => esc_html__( 'Typography', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Letter Spacing', 'total' ),
					'param_name' => 'letter_spacing',
					'description' => vcex_shortcode_param_description( 'letter_spacing' ),
					'group' => esc_html__( 'Typography', 'total' ),
				),
				// Translations
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Days', 'total' ),
					'param_name' => 'days',
					'group' =>  esc_html__( 'Strings', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Hours', 'total' ),
					'param_name' => 'hours',
					'group' =>  esc_html__( 'Strings', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Minutes', 'total' ),
					'param_name' => 'minutes',
					'group' =>  esc_html__( 'Strings', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Seconds', 'total' ),
					'param_name' => 'seconds',
					'group' =>  esc_html__( 'Strings', 'total' ),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_countdown' );

		}

	}

}
new VCEX_Countdown_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Countdown' ) ) {
	class WPBakeryShortCode_Vcex_Countdown extends WPBakeryShortCode {}
}