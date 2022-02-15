<?php
defined( 'ABSPATH' ) || exit;

/**
 * Skillbar Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */
if ( ! class_exists( 'VCEX_Skillbar_Shortcode' ) ) {

	class VCEX_Skillbar_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', __CLASS__ . '::register_scripts' );
			add_shortcode( 'vcex_skillbar', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Skillbar::instance();
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
				'vcex-skillbar',
				vcex_asset_url( 'js/shortcodes/vcex-skillbar' . $js_extension ),
				array(),
				TTC_VERSION,
				true
			);

		}

		/**
		 * Shortcode scripts.
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'vcex-skillbar' );
		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_skillbar', $atts );
			include( vcex_get_shortcode_template( 'vcex_skillbar' ) );
			do_action( 'vcex_shortcode_after', 'vcex_skillbar', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				// General
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Text', 'total-theme-core' ),
					'param_name' => 'title',
					'admin_label' => true,
					'value' => 'Web Design',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Percentage Source', 'total-theme-core' ),
					'param_name' => 'source',
					'value' => array(
						esc_html__( 'Custom Text', 'total-theme-core' ) => 'custom',
						esc_html__( 'Custom Field', 'total-theme-core' ) => 'custom_field',
						esc_html__( 'Callback Function', 'total-theme-core' ) => 'callback_function',
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Field ID', 'total-theme-core' ),
					'param_name' => 'custom_field',
					'dependency' => array( 'element' => 'source', 'value' => 'custom_field' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Callback Function', 'total-theme-core' ),
					'param_name' => 'callback_function',
					'dependency' => array( 'element' => 'source', 'value' => 'callback_function' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Percentage', 'total-theme-core' ),
					'param_name' => 'percentage',
					'value' => 70,
					'dependency' => array( 'element' => 'source', 'value' => 'custom' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Animate', 'total-theme-core' ),
					'param_name' => 'animate_percent',
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Re-trigger animation on scroll', 'total-theme-core' ),
					'param_name' => 'animate_percent_onscroll',
					'dependency' => array( 'element' => 'animate_percent', 'value' => 'true' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Display Percentage', 'total-theme-core' ),
					'param_name' => 'show_percent',
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
					'param_name' => 'classes',
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
					'heading' => esc_html__( 'Style', 'total-theme-core' ),
					'param_name' => 'style',
					'value' => array(
						esc_html__( 'Default', 'total-theme-core' ) => '',
						esc_html__( 'Title Above', 'total-theme-core' ) => 'alt-1',
					),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin', // can't name it margin_bottom due to WPBakery parsing issue
					'value' => vcex_margin_choices(),
					'admin_label' => true,
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
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Label Font Size', 'total-theme-core' ),
					'param_name' => 'font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Label Color', 'total-theme-core' ),
					'param_name' => 'label_color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Percentage Font Size', 'total-theme-core' ),
					'param_name' => 'percentage_font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Percentage Color', 'total-theme-core' ),
					'param_name' => 'percentage_color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Container Background', 'total-theme-core' ),
					'param_name' => 'background',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Container Inset Shadow', 'total-theme-core' ),
					'param_name' => 'box_shadow',
					'dependency' => array( 'element' => 'style', 'is_empty' => true ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Container Height', 'total-theme-core' ),
					'param_name' => 'container_height',
					'description' => vcex_shortcode_param_description( 'height' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Container Left Padding', 'total-theme-core' ),
					'param_name' => 'container_padding_left',
					'description' => vcex_shortcode_param_description( 'padding' ),
					'dependency' => array( 'element' => 'style', 'is_empty' => true ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				// Icon
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Display Icon', 'total-theme-core' ),
					'param_name' => 'show_icon',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Icon library', 'total-theme-core' ),
					'param_name' => 'icon_type',
					'value' => array(
						esc_html__( 'Theme Icons', 'total-theme-core' ) => '',
						esc_html__( 'Font Awesome', 'total-theme-core' ) => 'fontawesome',
						esc_html__( 'Open Iconic', 'total-theme-core' ) => 'openiconic',
						esc_html__( 'Typicons', 'total-theme-core' ) => 'typicons',
						esc_html__( 'Entypo', 'total-theme-core' ) => 'entypo',
						esc_html__( 'Linecons', 'total-theme-core' ) => 'linecons',
						esc_html__( 'Pixel', 'total-theme-core' ) => 'pixelicons',
					),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
					'dependency' => array( 'element' => 'show_icon', 'value' => 'true' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon',
					'settings' => array( 'emptyIcon' => true, 'type' => 'ticons', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'is_empty' => true ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_fontawesome',
					'settings' => array( 'emptyIcon' => true, 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_openiconic',
					'settings' => array(
						'emptyIcon' => true,
						'iconsPerPage' => 100,
						'type' => 'openiconic',
					),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_typicons',
					'settings' => array(
						'emptyIcon' => true,
						'iconsPerPage' => 100,
						'type' => 'typicons',
					),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_entypo',
					'settings' => array(
						'emptyIcon' => false,
						'type' => 'entypo',
						'iconsPerPage' => 300,
					),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_linecons',
					'settings' => array(
						'emptyIcon' => true,
						'iconsPerPage' => 100,
						'type' => 'linecons',
					),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_pixelicons',
					'settings' => array(
						'emptyIcon' => true,
						'iconsPerPage' => 100,
						'type' => 'pixelicons',
						'source' => vcex_pixel_icons(),
					),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Side Margin', 'total-theme-core' ),
					'param_name' => 'icon_margin',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
					'dependency' => array( 'element' => 'show_icon', 'value' => 'true' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_skillbar' );

		}

	}

}
new VCEX_Skillbar_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Skillbar' ) ) {
	class WPBakeryShortCode_Vcex_Skillbar extends WPBakeryShortCode {}
}