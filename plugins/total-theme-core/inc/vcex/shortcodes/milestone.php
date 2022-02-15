<?php
/**
 * Milestone Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 *
 * @todo add alignment option.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Milestone_Shortcode' ) ) {

	class VCEX_Milestone_Shortcode {

		/**
		 * Define shortcode name.
		 */
		public $shortcode = 'vcex_milestone';

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', __CLASS__ . '::register_scripts' );
			add_shortcode( 'vcex_milestone', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Milestone::instance();
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
				'countUp',
				vcex_asset_url( 'js/lib/countUp' . $js_extension ),
				array(),
				'1.9.3',
				true
			);

			wp_register_script(
				'vcex-milestone',
				vcex_asset_url( 'js/shortcodes/vcex-milestone' . $js_extension ),
				array( 'countUp' ),
				TTC_VERSION,
				true
			);

		}

		/**
		 * Enqueue scripts.
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'vcex-milestone' );
		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_milestone', $atts );
			include( vcex_get_shortcode_template( 'vcex_milestone' ) );
			do_action( 'vcex_shortcode_after', 'vcex_milestone', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {
			$params = array(
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
				// Style
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Style', 'total-theme-core' ),
					'param_name' => 'style',
					'std' => 'plain',
					'choices' => array(
						'plain'    => esc_html__( 'Plain', 'total-theme-core' ),
						'bordered' => esc_html__( 'Bordered', 'total-theme-core' ),
						'boxed'    => esc_html__( 'Boxed', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Text Align', 'total-theme-core' ),
					'param_name' => 'text_align',
					'std' => 'center',
					'exclude_choices' => array( '', 'default' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_hover_animations',
					'heading' => esc_html__( 'Hover Animation', 'total-theme-core'),
					'param_name' => 'hover_animation',
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
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Aligment', 'total-theme-core' ),
					'param_name' => 'float',
					'std' => 'left', // was always left by default.
					'exclude_choices' => array( '', 'default' ),
					'dependency' => array( 'element' => 'width', 'not_empty' => true ),
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
					'heading' => esc_html__( 'Shadow', 'total' ),
					'param_name' => 'shadow',
					'value' => vcex_shadow_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Shadow: Hover', 'total' ),
					'param_name' => 'shadow_hover',
					'value' => vcex_shadow_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'hover_animation', 'is_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border Radius', 'total-theme-core' ),
					'param_name' => 'border_radius',
					'description' => vcex_shortcode_param_description( 'border_radius' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
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
				// Number
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => esc_html__( 'Number', 'total-theme-core' ),
					'param_name' => 'number',
					'std' => '45',
					'description' => esc_html__( 'Enter a PHP function name if you would like to return a dynamic number based on a custom function', 'total-theme-core' ),
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Animated', 'total-theme-core' ),
					'group' => esc_html__( 'Number', 'total-theme-core' ),
					'param_name' => 'animated',
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Re-trigger animation on scroll', 'total-theme-core' ),
					'group' => esc_html__( 'Number', 'total-theme-core' ),
					'param_name' => 'animate_onscroll',
					'dependency' => array( 'element' => 'animated', 'value' => 'true' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Start Value', 'total-theme-core' ),
					'param_name' => 'startval',
					'value' => '0',
					'description' => esc_html__( 'Enter the number which to start counting from, if the number is greater then the value set under the number tab then the counter will count down instead of up.','total-theme-core'),
					'dependency' => array( 'element' => 'animated', 'value' => 'true' ),
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Speed', 'total-theme-core' ),
					'param_name' => 'speed',
					'value' => '2500',
					'description' => esc_html__( 'The number of milliseconds it should take to finish counting.','total-theme-core'),
					'dependency' => array( 'element' => 'animated', 'value' => 'true' ),
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'std' => ',',
					'heading' => esc_html__( 'Thousand Seperator', 'total-theme-core' ),
					'param_name' => 'separator',
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Decimal Places', 'total-theme-core' ),
					'param_name' => 'decimals',
					'value' => '0',
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'std' => '.',
					'heading' => esc_html__( 'Decimal Seperator', 'total-theme-core' ),
					'param_name' => 'decimal_separator',
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Before', 'total-theme-core' ),
					'param_name' => 'before',
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'After', 'total-theme-core' ),
					'param_name' => 'after',
					'default' => '%',
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'number_font_family',
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'number_color',
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'number_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'number_weight',
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'number_bottom_margin',
					'description' => vcex_shortcode_param_description( 'margin' ),
					'group' => esc_html__( 'Number', 'total-theme-core' ),
				),
				// caption
				array(
					'type' => 'textfield',
					'class' => 'vcex-animated-counter-caption',
					'heading' => esc_html__( 'Caption', 'total-theme-core' ),
					'param_name' => 'caption',
					'value' => 'Awards Won',
					'admin_label' => true,
					'group' => esc_html__( 'Caption', 'total-theme-core' ),
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'caption_font_family',
					'group' => esc_html__( 'Caption', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__(  'Color', 'total-theme-core' ),
					'param_name' => 'caption_color',
					'group' => esc_html__( 'Caption', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'caption_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Caption', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'caption_font', // @todo rename setting to font_weight
					'group' => esc_html__( 'Caption', 'total-theme-core' ),
				),
				// Icons
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Enable Icon', 'total-theme-core' ),
					'param_name' => 'enable_icon',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Icon library', 'total-theme-core' ),
					'param_name' => 'icon_type',
					'description' => esc_html__( 'Select icon library.', 'total-theme-core' ),
					'value' => array(
						esc_html__( 'Theme Icons', 'total-theme-core' ) => '',
						esc_html__( 'Font Awesome', 'total-theme-core' ) => 'fontawesome',
						esc_html__( 'Open Iconic', 'total-theme-core' ) => 'openiconic',
						esc_html__( 'Typicons', 'total-theme-core' ) => 'typicons',
						esc_html__( 'Entypo', 'total-theme-core' ) => 'entypo',
						esc_html__( 'Linecons', 'total-theme-core' ) => 'linecons',
						esc_html__( 'Pixel', 'total-theme-core' ) => 'pixelicons',
					),
					'dependency' => array( 'element' => 'enable_icon', 'value' => 'true' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
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
					'settings' => array( 'emptyIcon' => true, 'type' => 'fontawesome', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_openiconic',
					'settings' => array( 'emptyIcon' => true, 'type' => 'openiconic', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_typicons',
					'settings' => array( 'emptyIcon' => true, 'type' => 'typicons', 'iconsPerPage' => 100, ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_entypo',
					'settings' => array( 'emptyIcon' => true, 'type' => 'entypo', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_linecons',
					'settings' => array( 'emptyIcon' => true, 'type' => 'linecons', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_pixelicons',
					'settings' => array( 'emptyIcon' => true, 'type' => 'pixelicons', 'source' => vcex_pixel_icons() ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Icon Font Alternative Classes', 'total-theme-core' ),
					'param_name' => 'icon_alternative_classes',
					'dependency' => array( 'element' => 'enable_icon', 'value' => 'true' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Icon Position', 'total-theme-core' ),
					'param_name' => 'icon_position',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
					'std' => 'inline', // this is required for vcex_select_buttons
					'choices' => array(
						'inline' => esc_html__( 'Inline', 'total-theme-core' ),
						'top' => esc_html__( 'Top', 'total-theme-core' ),
						'left' => esc_html__( 'Left', 'total-theme-core' ),
						'right' => esc_html__( 'Right', 'total-theme-core' ),
					),
					'dependency' => array( 'element' => 'enable_icon', 'value' => 'true' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Spacing', 'total-theme-core' ),
					'param_name' => 'icon_spacing',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__(  'Color', 'total-theme-core' ),
					'param_name' => 'icon_color',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
					'dependency' => array( 'element' => 'enable_icon', 'value' => 'true' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Size', 'total-theme-core' ),
					'param_name' => 'icon_size',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
					'dependency' => array( 'element' => 'enable_icon', 'value' => 'true' ),
					'description' => esc_html__( 'Default', 'total-theme-core' ) . ': 54px',
				),
				// Link
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Apply Link To Entire Element?', 'total-theme-core' ),
					'param_name' => 'url_wrap',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'URL', 'total-theme-core' ),
					'param_name' => 'url',
					'description' => vcex_shortcode_param_description( 'text' ),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Target', 'total-theme-core' ),
					'param_name' => 'url_target',
					'std' => 'self',
					'choices' => array(
						'self' => esc_html__( 'Self', 'total-theme-core' ),
						'blank' => esc_html__( 'Blank', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Rel', 'total-theme-core' ),
					'param_name' => 'url_rel',
					'std' => '',
					'choices' => array(
						'' => esc_html__( 'None', 'total-theme-core' ),
						'nofollow' => esc_html__( 'Nofollow', 'total-theme-core' ),
						'sponsored' => esc_html__( 'Sponsored', 'total-theme-core' ),
					),

					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_milestone' );

		}

	}

}
new VCEX_Milestone_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Milestone' ) ) {
	class WPBakeryShortCode_Vcex_Milestone extends WPBakeryShortCode {}
}