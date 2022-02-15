<?php
/**
 * Icon Box Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Icon_Box_Shortcode' ) ) {

	class VCEX_Icon_Box_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_icon_box', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Icon_Box::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_icon_box', $atts );
			include( vcex_get_shortcode_template( 'vcex_icon_box' ) );
			do_action( 'vcex_shortcode_after', 'vcex_icon_box', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {
			$params = array(
				// Main
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Heading', 'total-theme-core' ),
					'param_name' => 'heading',
					'std' => 'Sample Heading',
					'description' => vcex_shortcode_param_description( 'text' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Badge', 'total-theme-core' ),
					'param_name' => 'heading_badge',
					'description' => vcex_shortcode_param_description( 'text' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Badge Background Color', 'total' ),
					'param_name' => 'heading_badge_background_color',
					'dependency' => array( 'element' => 'heading_badge', 'not_empty' => true ),
				),
				array(
					'type' => 'textarea_html',
					'holder' => 'div',
					'heading' => esc_html__( 'Content', 'total-theme-core' ),
					'param_name' => 'content',
					'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
				),
				// General
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
					'value' => vcex_icon_box_styles(),
					'std' => 'one',
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
					'type' => 'dropdown',
					'heading' => esc_html__( 'Stack at Breakpoint', 'total' ),
					'param_name' => 'stack_bk',
					'value' => vcex_breakpoint_choices(),
					'group' => esc_html__( 'Style', 'total' ),
					'dependency' => array( 'element' => 'style', 'value' => array( 'one', 'seven' ) ),
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
					'std' => 'center',
					'exclude_choices' => array( '', 'default' ),
					'dependency' => array( 'element' => 'width', 'not_empty' => true ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Icon Spacing', 'total-theme-core' ),
					'param_name' => 'icon_spacing',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Vertical Align Center', 'total-theme-core' ),
					'param_name' => 'align_center',
					'dependency' => array( 'element' => 'style', 'value' => array( 'one', 'seven' ) ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Text Align', 'total-theme-core' ),
					'param_name' => 'alignment',
					'std' => 'center',
					'exclude_choices' => array( '', 'default' ),
					'dependency' => array( 'element' => 'style', 'value' => array( 'two', 'eight' ) ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background Color', 'total' ),
					'param_name' => 'background_color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background: Hover', 'total-theme-core' ),
					'param_name' => 'hover_background',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'White Text On Hover', 'total-theme-core' ),
					'param_name' => 'hover_white_text',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Width', 'total' ),
					'param_name' => 'border_width',
					'value' => vcex_border_width_choices(),
					'dependency' => array( 'element' => 'style', 'value' => array( 'four' ) ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Border Color', 'total' ),
					'param_name' => 'border_color',
					'dependency' => array( 'element' => 'style', 'value' => array( 'four' ) ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'wpex_padding',
					'value' => vcex_padding_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Border Radius', 'total-theme-core' ),
					'param_name' => 'border_radius',
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
				),
				array(
					'type' => 'vcex_hover_animations',
					'heading' => esc_html__( 'Hover Animation', 'total-theme-core'),
					'param_name' => 'hover_animation',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				// Content
				array(
					'type' => 'vcex_responsive_sizes',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Content', 'total-theme-core' ),
					'target' => 'font-size',
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'group' => esc_html__( 'Content', 'total-theme-core' ),
					'param_name' => 'font_weight',
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'font_color',
					'group' => esc_html__( 'Content', 'total-theme-core' ),
				),
				// Heading
				array(
					'type' => 'vcex_responsive_sizes',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'heading_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
					'target' => 'font-size',
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'heading_font_family',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'heading_color',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'heading' => esc_html__( 'HTML Tag', 'total-theme-core' ),
					'param_name' => 'heading_type',
					'type' => 'vcex_select_buttons',
					'choices' => 'html_tag',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
					'description' => esc_html__( 'Default', 'total-theme-core' ) . ': h2',
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'heading_weight',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_transforms',
					'heading' => esc_html__( 'Text Transform', 'total-theme-core' ),
					'param_name' => 'heading_transform',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Letter Spacing', 'total-theme-core' ),
					'param_name' => 'heading_letter_spacing',
					'description' => vcex_shortcode_param_description( 'letter_spacing' ),
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Line Height', 'total-theme-core' ),
					'param_name' => 'heading_line_height',
					'description' => vcex_shortcode_param_description( 'line_height' ),
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'heading_bottom_margin',
					'description' => vcex_shortcode_param_description( 'margin' ),
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				// Icons
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
					'value' => 'ticon ticon-star-o',
					'settings' => array( 'emptyIcon' => true, 'type' => 'ticons', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'is_empty' => true ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_fontawesome',
					'value' => 'fas fa-info-circle',
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
					'settings' => array( 'emptyIcon' => true, 'type' => 'typicons', 'iconsPerPage' => 100 ),
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
					'param_name' => 'icon_material',
					'settings' => array( 'emptyIcon' => true, 'type' => 'material', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'material' ),
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
					'heading' => esc_html__( 'Icon Alternative Classes', 'total-theme-core' ),
					'param_name' => 'icon_alternative_classes',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Icon Alternative Character', 'total-theme-core' ),
					'param_name' => 'icon_alternative_character',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'text' ),
					'dependency' => array( 'element' => 'icon_alternative_classes', 'is_empty' => true ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'icon_font_weight',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
					'dependency' => array( 'element' => 'icon_alternative_character', 'not_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Size', 'total-theme-core' ),
					'param_name' => 'icon_size',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
					'description' => esc_html__( 'Default', 'total-theme-core' ) . ': 28px',
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'icon_color',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background', 'total-theme-core' ),
					'param_name' => 'icon_background',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Shadow', 'total' ),
					'param_name' => 'icon_shadow',
					'value' => vcex_shadow_choices(),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
					'description' => esc_html__( 'For optimal display add a white background to your icon or give the icon a custom width/height.', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Width', 'total' ),
					'param_name' => 'icon_border_width',
					'value' => vcex_border_width_choices(),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border Radius', 'total-theme-core' ),
					'param_name' => 'icon_border_radius',
					'description' => vcex_shortcode_param_description( 'border_radius' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'icon_width',
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Height', 'total-theme-core' ),
					'param_name' => 'icon_height',
					'description' => vcex_shortcode_param_description( 'height' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'icon_bottom_margin',
					'dependency' => array( 'element' => 'style', 'value' => array( 'two', 'three', 'four', 'five', 'six' ) ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'margin' ),
				),
				// Image
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Source', 'total-theme-core' ),
					'param_name' => 'image_source',
					'std' => 'media_library',
					'value' => array(
						esc_html__( 'Media Library', 'total-theme-core' ) => 'media_library',
						esc_html__( 'External', 'total-theme-core' ) => 'external',
					),
					'group' => esc_html__( 'Image', 'total-theme-core' ),
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Image', 'total-theme-core' ),
					'param_name' => 'image',
					'dependency' => array( 'element' => 'image_source', 'value' => 'media_library' ),
					'group' => esc_html__( 'Image', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'External Image URL', 'total-theme-core' ),
					'param_name' => 'external_image',
					'dependency' => array( 'element' => 'image_source', 'value' => 'external' ),
					'group' => esc_html__( 'Image', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'image_bottom_margin',
					'group' => esc_html__( 'Image', 'total-theme-core' ),
					'dependency' => array( 'element' => 'style', 'value' => array( 'two' ) ),
					'description' => esc_html__( 'Default', 'total-theme-core' ) . ': 20px',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Radius', 'total-theme-core' ),
					'param_name' => 'image_border_radius',
					'value' => vcex_border_radius_choices(),
					'group' => esc_html__( 'Image', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'image_width',
					'group' => esc_html__( 'Image', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Height', 'total-theme-core' ),
					'param_name' => 'image_height',
					'group' => esc_html__( 'Image', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Resize Image', 'total-theme-core' ),
					'param_name' => 'resize_image',
					'group' => esc_html__( 'Image', 'total-theme-core' ),
					'description' => esc_html__( 'Enable to run the image through the resizing script, disable to simply resize via CSS.', 'total-theme-core' ),
					'dependency' => array( 'element' => 'image_source', 'value' => 'media_library' ),
				),
				// Link
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Apply Link To Entire Element?', 'total-theme-core' ),
					'param_name' => 'url_wrap',
					'std' => 'false',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'dependency' => array( 'element' => 'style', 'value' => array( 'one', 'two', 'three', 'seven', 'eight' ) ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'On click action', 'total-theme-core' ),
					'param_name' => 'onclick',
					'value' => array(
						esc_html__( 'None', 'total-theme-core' ) => '',
						esc_html__( 'Go to custom link', 'total-theme-core' ) => 'custom_link',
						esc_html__( 'Go to internal page', 'total-theme-core' ) => 'internal_link',
						esc_html__( 'Scroll to section', 'total-theme-core' ) => 'local_scroll',
						esc_html__( 'Toggle Element', 'total-theme-core' ) => 'toggle_element',
						esc_html__( 'Go to custom field value', 'total-theme-core' ) => 'custom_field',
						esc_html__( 'Go to callback function value', 'total-theme-core' ) => 'callback_function',
						esc_html__( 'Open inline content or iframe popup', 'total-theme-core' ) => 'popup',
						esc_html__( 'Open image lightbox', 'total-theme-core' ) => 'lightbox_image',
						esc_html__( 'Open image gallery lightbox', 'total-theme-core' ) => 'lightbox_gallery',
						esc_html__( 'Open video lightbox', 'total-theme-core' ) => 'lightbox_video',
						esc_html__( 'Open post image gallery lightbox', 'total-theme-core' ) => 'lightbox_post_gallery',
						esc_html__( 'Open post video lightbox', 'total-theme-core' ) => 'lightbox_post_video',
					),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Link', 'total-theme-core' ),
					'param_name' => 'onclick_url',
					'description' => vcex_shortcode_param_description( 'text' ),
					'dependency' => array(
						'element' => 'onclick',
						'value' => array(
							'custom_link',
							'local_scroll',
							'popup',
							'lightbox_image',
							'lightbox_video',
							'toggle_element'
						),
					),
					'description' => esc_html__( 'Enter your custom link url, lightbox url or local/toggle element ID (including a # at the front).', 'total-theme-core' ),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'vc_link',
					'heading' => esc_html__( 'Internal Link', 'total-theme-core' ),
					'param_name' => 'onclick_internal_link',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'description' => esc_html__( 'This setting is used only if you want to link to an internal page to make it easier to find and select it. Any extra settings in the popup (title, target, nofollow) are ignored.', 'total-theme-core' ),
					'dependency' => array( 'element' => 'onclick', 'value' => 'internal_link' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Field ID', 'total-theme-core' ),
					'param_name' => 'onclick_custom_field',
					'dependency' => array( 'element' => 'onclick', 'value' => 'custom_field' ),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Callback Function', 'total-theme-core' ),
					'param_name' => 'onclick_callback_function',
					'dependency' => array( 'element' => 'onclick', 'value' => 'callback_function' ),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Lightbox Image', 'total-theme-core' ),
					'param_name' => 'onclick_lightbox_image',
					'dependency' => array( 'element' => 'onclick', 'value' => 'lightbox_image' ),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'attach_images',
					'heading' => esc_html__( 'Lightbox Gallery', 'total-theme-core' ),
					'param_name' => 'onclick_lightbox_gallery',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'dependency' => array( 'element' => 'onclick', 'value' => 'lightbox_gallery' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title Attribute', 'total-theme-core' ),
					'param_name' => 'onclick_title',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'dependency' => array( 'element' => 'onclick', 'not_empty' => true ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Target', 'total-theme-core' ),
					'param_name' => 'onclick_target',
					'std' => 'self',
					'choices' => array(
						'self'   => esc_html__( 'Self', 'total-theme-core' ),
						'_blank' => esc_html__( 'Blank', 'total-theme-core' ),
					),
					'dependency' => array(
						'element' => 'onclick',
						'value' => array( 'custom_link', 'internal_link', 'custom_field', 'callback_function' ),
					),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Rel Attribute', 'total-theme-core' ),
					'param_name' => 'onclick_rel',
					'std' => '',
					'choices' => array(
						'' => esc_html__( 'None', 'total-theme-core' ),
						'nofollow' => esc_html__( 'Nofollow', 'total-theme-core' ),
						'sponsored' => esc_html__( 'Sponsored', 'total-theme-core' ),
					),
					'dependency' => array(
						'element' => 'onclick',
						'value' => array( 'custom_link', 'internal_link', 'custom_field', 'callback_function' ),
					),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Lightbox Dimensions (optional)', 'total-theme-core' ),
					'param_name' => 'onclick_lightbox_dims',
					'description' => esc_html__( 'Enter a custom width and height for your lightbox pop-up window. Use format widthxheight. Example: 1920x1080.', 'total-theme-core' ),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'dependency' => array( 'element' => 'onclick', 'value' => array( 'lightbox_video', 'popup' ) ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Lightbox Title', 'total-theme-core' ),
					'param_name' => 'onclick_lightbox_title',
					'dependency' => array(
						'element' => 'onclick',
						'value' => array( 'lightbox_image', 'lightbox_video', 'popup' )
					),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'textarea',
					'heading' => esc_html__( 'Lightbox Caption', 'total-theme-core' ),
					'param_name' => 'onclick_lightbox_caption',
					'dependency' => array(
						'element' => 'onclick',
						'value' => array( 'lightbox_image', 'lightbox_video', 'popup' )
					),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'exploded_textarea',
					'heading' => esc_html__( 'Custom Data Attributes', 'total-theme-core' ),
					'param_name' => 'onclick_data_attributes',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'dependency' => array(
						'element' => 'onclick',
						'value' => array(
							'custom_link',
							'custom_field',
							'callback_function',
							'popup',
						),
					),
					'description' => esc_html__( 'Enter your custom data attributes in the format of data|value. Hit enter after each set of data attributes.', 'total-theme-core' ),
				),
				// Design
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
				// Deprecated fields.
				array( 'type' => 'hidden', 'param_name' => 'url' ), // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'url_target' ), // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'url_rel' ), // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'icon_color_accent' ), // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'icon_background_accent' ), // @since v5.1
			);

			/**
			 * Filters the vcex_icon box shortcode params.
			 *
			 * @param array $params
			 */
			$params = (array) apply_filters( 'vcex_shortcode_params', $params, 'vcex_icon_box' );

			return $params;
		}

		/**
		 * Parses deprecated params.
		 */
		public static function parse_deprecated_attributes( $atts = '' ) {
			if ( empty( $atts ) || ! is_array( $atts ) ) {
				return $atts;
			}

			// Move items to new onclick param.
			if ( empty( $atts['onclick'] ) ) {

				if ( ! empty( $atts['url'] ) ) {
					if ( empty( $atts['onclick_url'] ) ) {
						$atts['onclick_url'] = $atts['url'];
					}
					$atts['onclick'] = 'custom_link';
					unset( $atts['url'] );
				}

				if ( ! empty( $atts['url_target'] ) ) {
					if ( 'local' === $atts['url_target' ] ) {
						if ( empty( $atts['onclick'] ) ) {
							$atts['onclick'] = 'local_scroll';
						}
						$atts['onclick_target'] = 'self';
					} else {
						$atts['onclick_target'] = $atts['url_target'];
					}
					unset( $atts['url_target'] );
				}

				if ( ! empty( $atts['url_rel'] ) ) {
					$atts['onclick_rel'] = $atts['url_rel'];
					unset( $atts['url_rel'] );
				}

			}

			// Deprecate old use accent color settings.
			if ( isset( $atts['icon_color_accent'] ) && 'true' === $atts['icon_color_accent'] ) {
				if ( empty( $atts['icon_color'] ) ) {
					$atts['icon_color'] = 'accent';
				}
				unset( $atts['icon_color_accent'] );
			}

			if ( isset( $atts['icon_background_accent'] ) && 'true' === $atts['icon_background_accent'] ) {
				if ( empty( $atts['icon_background'] ) ) {
					$atts['icon_background'] = 'accent';
				}
				if ( empty( $atts['icon_color'] ) ) {
					$atts['icon_color'] = '#fff';
				}
				unset( $atts['icon_background_accent'] );
			}

			return $atts;
		}

	}

}
new VCEX_Icon_Box_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Icon_Box' ) ) {
	class WPBakeryShortCode_Vcex_Icon_Box extends WPBakeryShortCode {
		protected function outputTitle( $title ) {
			$icon = $this->settings( 'icon' );
			return '<h4 class="wpb_element_title"><i class="vc_general vc_element-icon' . ( ! empty( $icon ) ? ' ' . $icon : '' ) . '" aria-hidden="true"></i><span class="vcex-heading-text">' . esc_html__( 'Icon Box', 'total-theme-core' ) . '<span></span></span></h4>';
		}
	}
}