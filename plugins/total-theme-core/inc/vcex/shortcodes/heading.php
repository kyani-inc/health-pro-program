<?php
/**
 * Heading Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Heading_Shortcode' ) ) {

	class VCEX_Heading_Shortcode {

		/**
		 * Define shortcode name.
		 */
		public $shortcode = 'vcex_heading';

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_heading', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Heading::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_heading', $atts );
			include( vcex_get_shortcode_template( 'vcex_heading' ) );
			do_action( 'vcex_shortcode_after', 'vcex_heading', $atts );
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
					'heading' => esc_html__( 'Text Source', 'total-theme-core' ),
					'param_name' => 'source',
					'value' => array(
						esc_html__( 'Custom Text', 'total-theme-core' ) => 'custom',
						esc_html__( 'Post or Page Title', 'total-theme-core' ) => 'post_title',
						esc_html__( 'Post Publish Date', 'total-theme-core' ) => 'post_date',
						esc_html__( 'Post Modified Date', 'total-theme-core' ) => 'post_modified_date',
						esc_html__( 'Post Author', 'total-theme-core' ) => 'post_author',
						esc_html__( 'Current User', 'total-theme-core' ) => 'current_user',
						esc_html__( 'Custom Field', 'total-theme-core' ) => 'custom_field',
						esc_html__( 'Callback Function', 'total-theme-core' ) => 'callback_function',
					),
					'admin_label' => true,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Field ID', 'total-theme-core' ),
					'param_name' => 'custom_field',
					'dependency' => array( 'element' => 'source', 'value' => 'custom_field' ),
					'admin_label' => true,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Callback Function', 'total-theme-core' ),
					'param_name' => 'callback_function',
					'dependency' => array( 'element' => 'source', 'value' => 'callback_function' ),
					'admin_label' => true,
				),
				array(
					'type' => 'textarea_safe',
					'heading' => esc_html__( 'Text', 'total-theme-core' ),
					'param_name' => 'text',
					'value' => esc_html__( 'Heading', 'total-theme-core' ),
					'vcex_rows' => 2,
					'description' => vcex_shortcode_param_description( 'text_html' ),
					'dependency' => array( 'element' => 'source', 'value' => 'custom' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Badge', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'text' ),
					'param_name' => 'badge',
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Badge Background Color', 'total' ),
					'param_name' => 'badge_background_color',
					'dependency' => array( 'element' => 'badge', 'not_empty' => true ),
				),
				array(
					'heading' => esc_html__( 'HTML Tag', 'total-theme-core' ),
					'param_name' => 'tag',
					'type' => 'vcex_select_buttons',
					'choices' => 'html_tag',
					'description' => esc_html__( 'The heading module has its own styling no matter what HTML tag you\'ve selected. However, you can enable your customizer Typography heading styles to apply to the Heading module via a setting under Appearance > Customize > WPBakery Builder.', 'total-theme-core' ) . '<br>' .  esc_html__( 'You can also define your default HTML tag in the Customizer.', 'total-theme-core' ),
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
					'heading' => esc_html__( 'Style', 'total-theme-core' ),
					'param_name' => 'style',
					'std' => 'plain',
					'value' => array(
						esc_html__( 'Plain', 'total-theme-core' ) => 'plain',
						esc_html__( 'Bottom Border', 'total-theme-core' ) => 'bottom-border',
						esc_html__( 'Bottom Border w/ Color', 'total-theme-core' ) => 'bottom-border-w-color',
						esc_html__( 'Side Border', 'total-theme-core' ) => 'side-border',
						esc_html__( 'Graphical', 'total-theme-core' ) => 'graphical',
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
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'width',
					'description' => esc_html__( 'Enter a custom width instead of using breaks to slim down your content width.', 'total-theme-core' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Shadow', 'total' ),
					'param_name' => 'shadow',
					'value' => vcex_shadow_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'style', 'value' => 'plain' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Module Align', 'total-theme-core' ),
					'param_name' => 'align',
					'std' => '',
					'dependency' => array( 'element' => 'width', 'not_empty' => true ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Float', 'total-theme-core' ),
					'param_name' => 'float',
					'dependency' => array( 'element' => 'align', 'value' => array( 'left', 'right' ) ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Add Design to Inner Span', 'total-theme-core' ),
					'param_name' => 'add_css_to_inner',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'description' => esc_html__( 'Enable to add the background, padding, border, etc only around your text and icons and not the whole heading container.', 'total-theme-core' ),
					'dependency' => array( 'element' => 'style', 'value' => array( 'plain', 'side-border', 'bottom-border' ) ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background Color', 'total' ),
					'param_name' => 'background_color',
					'dependency' => array( 'element' => 'style', 'value' => 'plain' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background: Hover', 'total-theme-core' ),
					'param_name' => 'background_hover',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'style', 'value' => 'plain' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'padding_all',
					'value' => vcex_padding_choices(),
					'dependency' => array( 'element' => 'style', 'value' => 'plain' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Side Margin', 'total-theme-core' ),
					'param_name' => 'border_side_margin',
					'value' => vcex_margin_choices(),
					'dependency' => array( 'element' => 'style', 'value' => 'side-border' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Accent Border Color', 'total-theme-core' ),
					'param_name' => 'inner_bottom_border_color',
					'dependency' => array( 'element' => 'style', 'value' => 'bottom-border-w-color' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Border Color', 'total-theme-core' ),
					'param_name' => 'inner_bottom_border_color_main',
					'dependency' => array( 'element' => 'style', 'value' => 'bottom-border-w-color' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Style', 'total-theme-core' ),
					'param_name' => 'border_style',
					'dependency' => array( 'element' => 'style', 'value' => array( 'bottom-border', 'side-border' ) ),
					'value' => vcex_border_style_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Width', 'total-theme-core' ),
					'param_name' => 'border_width',
					'dependency' => array(
						'element' => 'style',
						'value' => array(
							'bottom-border',
							'side-border',
							//'bottom-border-w-color',
						)
					),
					'value' => vcex_border_width_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Border Color', 'total-theme-core' ),
					'param_name' => 'border_color',
					'dependency' => array( 'element' => 'style', 'value' => array( 'bottom-border', 'side-border' ) ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Radius', 'total' ),
					'param_name' => 'border_radius',
					'value' => vcex_border_radius_choices(),
					'group' => esc_html__( 'Style', 'total' ),
					'dependency' => array( 'element' => 'style', 'value' => 'plain' ),
				),
				// Typography
				array(
					'type' => 'vcex_notice',
					'param_name' => 'typo_notice',
					'text' => esc_html__( 'You can set custom styles for your this specific heading module below but you can also go to Appearance > Customize > Typography to set global styles for all your heading modules.', 'total-theme-core' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Text Align', 'total-theme-core' ),
					'param_name' => 'text_align',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_responsive_sizes',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
					'target' => 'font-size',
				),
				array(
					'type' => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'font_family',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'font_weight',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_transforms',
					'heading' => esc_html__( 'Text Transform', 'total-theme-core' ),
					'param_name' => 'text_transform',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'color',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color: Hover', 'total-theme-core' ),
					'param_name' => 'color_hover',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Italic', 'total-theme-core' ),
					'param_name' => 'italic',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Line Height', 'total-theme-core' ),
					'param_name' => 'line_height',
					'description' => vcex_shortcode_param_description( 'line_height' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Letter Spacing', 'total-theme-core' ),
					'param_name' => 'letter_spacing',
					'description' => vcex_shortcode_param_description( 'letter_spacing' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Auto Responsive Font Size', 'total-theme-core' ),
					'param_name' => 'responsive_text',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Minimum Font Size', 'total-theme-core' ),
					'param_name' => 'min_font_size',
					'dependency' => array( 'element' => 'responsive_text', 'value' => 'true' ),
					'description' => vcex_shortcode_param_description( 'px' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				// Link
				array(
					'type' => 'vc_link',
					'heading' => esc_html__( 'URL', 'total-theme-core' ),
					'param_name' => 'link',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Link: Local Scroll', 'total-theme-core' ),
					'param_name' => 'link_local_scroll',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				// Icon
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
						esc_html__( 'Material', 'total-theme-core' ) => 'material',
						esc_html__( 'Pixel', 'total-theme-core' ) => 'pixelicons',
					),
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
					'value' => '',
					'settings' => array( 'emptyIcon' => true, 'iconsPerPage' => 100, 'type' => 'fontawesome' ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_openiconic',
					'settings' => array(
						'emptyIcon' => true,
						'type' => 'openiconic',
						'iconsPerPage' => 100,
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
						'type' => 'typicons',
						'iconsPerPage' => 100,
					),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_entypo',
					'settings' => array(
						'emptyIcon' => true,
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
						'type' => 'linecons',
						'iconsPerPage' => 100,
					),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_material',
					'settings' => array(
						'emptyIcon' => true,
						'type' => 'material',
						'iconsPerPage' => 100,
					),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'material' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_pixelicons',
					'settings' => array(
						'emptyIcon' => true,
						'type' => 'pixelicons',
						'source' => vcex_pixel_icons(),
					),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Position', 'total-theme-core' ),
					'param_name' => 'icon_position',
					'std' => 'left',
					'choices' => array(
						'left' => esc_html__( 'Left', 'total-theme-core' ),
						'right' => esc_html__( 'Right', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'icon_color',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Side Margin', 'total-theme-core' ),
					'param_name' => 'icon_side_margin',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
				// Hidden/Deprecated fields
				array( 'type' => 'hidden', 'param_name' => 'text_accent', 'std' => '' ),
				array( 'type' => 'hidden', 'param_name' => 'hover_white_text', 'std' => '' ),
				array( 'type' => 'hidden', 'param_name' => 'hover_text_accent', 'std' => '' ),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_heading' );

		}

		/**
		 * Parses deprecated params.
		 */
		public static function parse_deprecated_attributes( $atts = '' ) {

			if ( empty( $atts ) || ! is_array( $atts ) ) {
				return $atts;
			}

			if ( ! empty( $atts['text_accent'] ) ) {
				if ( 'true' == $atts['text_accent'] ) {
					$atts['color'] = 'accent';
				}
				$atts['text_accent'] = '';
			}

			if ( ! empty( $atts['hover_white_text'] ) && 'true' == $atts['hover_white_text'] ) {
				$atts['color_hover'] = '#ffffff';
			} elseif ( ! empty( $atts['hover_text_accent'] ) && 'true' == $atts['hover_text_accent'] ) {
				$atts['color_hover'] = 'accent';
			}

			unset( $atts['hover_white_text'] );
			unset( $atts['hover_text_accent'] );

			return $atts;
		}

	}

}
new VCEX_Heading_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Heading' ) ) {
	class WPBakeryShortCode_Vcex_Heading extends WPBakeryShortCode {
		protected function outputTitle( $title ) {
			$icon = $this->settings( 'icon' );
			return '<h4 class="wpb_element_title"><i class="vc_general vc_element-icon' . ( ! empty( $icon ) ? ' ' . $icon : '' ) . '" aria-hidden="true"></i><span class="vcex-heading-text">' . esc_html__( 'Heading', 'total-theme-core' ) . '<span></span></span></h4>';
		}
	}
}