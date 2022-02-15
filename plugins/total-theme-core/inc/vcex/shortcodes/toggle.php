<?php
/**
 * Toggle Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Toggle_Shortcode' ) ) {

	class VCEX_Toggle_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {

			add_action( 'wp_enqueue_scripts', __CLASS__ . '::register_scripts' );

			add_shortcode( 'vcex_toggle', __CLASS__ . '::output' );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Toggle::instance();
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
				'vcex-toggle',
				vcex_asset_url( 'js/shortcodes/vcex-toggle' . $js_extension ),
				array(),
				TTC_VERSION,
				true
			);

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public static function output( $atts, $content = null ) {

			// Load necessary shortcode scripts.
			wp_enqueue_script( 'vcex-toggle' );

			// Display shortcode.
			ob_start();
				do_action( 'vcex_shortcode_before', 'vcex_toggle', $atts );
				include( vcex_get_shortcode_template( 'vcex_toggle' ) );
				do_action( 'vcex_shortcode_after', 'vcex_toggle', $atts );
			return ob_get_clean();

		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Heading', 'total' ),
					'value' => 'Lorem ipsum dolor sit amet?',
					'param_name' => 'heading',
					'description' => vcex_shortcode_param_description( 'text' ),
				),
				array(
					'type' => 'textarea_html',
					'heading' => esc_html__( 'Content', 'total-theme-core' ),
					'param_name' => 'content',
					'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eros urna, aliquet et porttitor in, congue ut risus. Nunc placerat faucibus ligula a mattis.',
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Default State', 'total' ),
					'param_name' => 'state',
					'std' => 'closed',
					'choices' => array(
						'closed' => esc_html__( 'Closed', 'total-theme-core' ),
						'open' => esc_html__( 'Open', 'total-theme-core' ),
					),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Animate', 'total' ),
					'param_name' => 'animate',
					'std' => 'true',
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'FAQ Markup', 'total' ),
					'param_name' => 'faq_microdata',
					'std' => 'false',
					'description' => esc_html__( 'Enable to include FAQ microdata markup for use with FAQ schema page types.', 'total' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Animation Speed', 'total-theme-core' ),
					'param_name' => 'animation_speed',
					'value' => array(
						esc_html__( 'Default', 'total-theme-core' ) => '',
						'75ms' => '75',
						'100ms' => '100',
						'150ms' => '150',
						'200ms' => '200',
						'300ms' => '300',
						'400ms' => '400',
						'500ms' => '500',
						'600ms' => '600',
						'700ms' => '700',
						'1000ms' => '1000',
					),
					'dependency' => array( 'element' => 'animate', 'value' => 'true' ),
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
				// Icon
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Icon Type', 'total' ),
					'param_name' => 'icon_type',
					'std' => 'plus',
					'choices' => array(
						'plus' => esc_html__( 'Plus', 'total-theme-core' ),
						'angle' => esc_html__( 'Angle', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Icon Position', 'total' ),
					'param_name' => 'icon_position',
					'std' => 'left',
					'choices' => array(
						'left' => esc_html__( 'Left', 'total-theme-core' ),
						'right' => esc_html__( 'Right', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Icon Spacing', 'total-theme-core' ),
					'param_name' => 'icon_spacing',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Icon Size', 'total-theme-core' ),
					'param_name' => 'icon_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'icon_color',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				// Heading
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Heading Tag', 'total' ),
					'param_name' => 'heading_tag',
					'std' => 'div',
					'choices' => array(
						'div' => 'div',
						'h2' => 'h2',
						'h3' => 'h3',
						'h4' => 'h4',
						'h5' => 'h5',
						'h6' => 'h6',
					),
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
					'description' => esc_html__( 'Used for SEO reasons only, not styling.', 'total-theme-core' ),
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'heading_font_family',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_responsive_sizes',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'heading_font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'target' => 'font-size',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'heading_font_weight',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'heading_color',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color: Hover', 'total-theme-core' ),
					'param_name' => 'heading_color_hover',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				// Content
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Content ID', 'total-theme-core' ),
					'param_name' => 'content_id',
					'description' => vcex_shortcode_param_description( 'unique_id' ),
					'group' => esc_html__( 'Content', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_responsive_sizes',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'content_font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'target' => 'font-size',
					'group' => esc_html__( 'Content', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'content_color',
					'group' => esc_html__( 'Content', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_toggle' );

		}

	}

}
new VCEX_Toggle_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Toggle' ) ) {
	class WPBakeryShortCode_Vcex_Toggle extends WPBakeryShortCode {}
}