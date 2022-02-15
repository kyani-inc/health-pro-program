<?php
/**
 * Teaser Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Teaser_Shortcode' ) ) {

	class VCEX_Teaser_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_teaser', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Teaser::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_teaser', $atts );
			include( vcex_get_shortcode_template( 'vcex_teaser' ) );
			do_action( 'vcex_shortcode_after', 'vcex_teaser', $atts );
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
					'heading' => esc_html__( 'Heading', 'total-theme-core' ),
					'param_name' => 'heading',
					'value' => 'Sample Heading',
				),
				array(
					'type' => 'textarea_html',
					'holder' => 'div',
					'heading' => esc_html__( 'Content', 'total-theme-core' ),
					'param_name' => 'content',
					'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed faucibus feugiat convallis. Integer nec eros et risus condimentum tristique vel vitae arcu.',
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
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-theme-core' ),
					'param_name' => 'classes',
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total-theme-core' ),
					'param_name' => 'visibility',
				),
				array(
					'type' => 'vcex_hover_animations',
					'heading' => esc_html__( 'Hover Animation', 'total-theme-core'),
					'param_name' => 'hover_animation',
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
						esc_html__( 'Plain', 'total-theme-core' )   => 'one',
						esc_html__( 'Boxed Rounded', 'total-theme-core' ) => 'two',
						esc_html__( 'Boxed Square', 'total-theme-core' ) => 'three',
						esc_html__( 'Outline', 'total-theme-core' ) => 'four',
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
					'type' => 'dropdown',
					'heading' => esc_html__( 'Shadow', 'total' ),
					'param_name' => 'shadow',
					'value' => vcex_shadow_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Text Align', 'total-theme-core' ),
					'param_name' => 'text_align',
					'std' => '',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'padding',
					'dependency' => array( 'element' => 'style', 'value' => array( 'two', 'three' ) ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background Color', 'total-theme-core' ),
					'param_name' => 'background',
					'dependency' => array( 'element' => 'style', 'value' => array( 'two', 'three' ) ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Border Color', 'total-theme-core' ),
					'param_name' => 'border_color',
					'dependency' => array( 'element' => 'style', 'value' => array( 'four' ) ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border Radius', 'total-theme-core' ),
					'param_name' => 'border_radius',
					'dependency' => array( 'element' => 'style', 'value' => array( 'two', 'three', 'four' ) ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				// Heading
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'heading_color',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Tag', 'total-theme-core' ),
					'param_name' => 'heading_type',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
					'std' => 'h2',
					'choices' => 'html_tag',
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'heading_font_family',
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
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
					'type' => 'vcex_responsive_sizes',
					'target' => 'font-size',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'heading_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Margin', 'total-theme-core' ),
					'param_name' => 'heading_margin',
					'description' => vcex_shortcode_param_description( 'margin' ),
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Letter Spacing', 'total-theme-core' ),
					'param_name' => 'heading_letter_spacing',
					'description' => vcex_shortcode_param_description( 'letter_spacing' ),
					'group' => esc_html__( 'Heading', 'total-theme-core' ),
				),
				// Content
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Top Spacing', 'total-theme-core' ),
					'param_name' => 'content_top_margin',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Content', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background', 'total-theme-core' ),
					'param_name' => 'content_background',
					'group' => esc_html__( 'Content', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'content_color',
					'group' => esc_html__( 'Content', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Margin', 'total-theme-core' ),
					'param_name' => 'content_margin',
					'description' => vcex_shortcode_param_description( 'margin' ),
					'group' => esc_html__( 'Content', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'content_padding',
					'description' => vcex_shortcode_param_description( 'padding' ),
					'group' => esc_html__( 'Content', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_responsive_sizes',
					'target' => 'font-size',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'content_font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Content', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'content_font_weight',
					'group' => esc_html__( 'Content', 'total-theme-core' ),
				),
				// Media
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Source', 'total-theme-core' ),
					'param_name' => 'image_source',
					'std' => 'media_library',
					'value' => array(
						esc_html__( 'Media Library', 'total-theme-core' ) => 'media_library',
						esc_html__( 'External', 'total-theme-core' ) => 'external',
					),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Image', 'total-theme-core' ),
					'param_name' => 'image',
					'dependency' => array( 'element' => 'image_source', 'value' => 'media_library' ),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'External Image URL', 'total-theme-core' ),
					'param_name' => 'external_image',
					'dependency' => array( 'element' => 'image_source', 'value' => 'external' ),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image Alt', 'total-theme-core' ),
					'param_name' => 'image_alt',
					'group' => esc_html__( 'Media', 'total-theme-core' ),
					'dependency' => array( 'element' => 'image', 'not_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Video link', 'total-theme-core' ),
					'param_name' => 'video',
					'description' => esc_html__( 'Enter in a video URL that is compatible with WordPress\'s built-in oEmbed feature.', 'total-theme-core' ),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Image Style', 'total-theme-core' ),
					'param_name' => 'img_style',
					'std' => '',
					'choices' => array(
						'' => esc_html__( 'Auto', 'total-theme-core' ),
						'stretch' => esc_html__( 'Stretch', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Align', 'total-theme-core' ),
					'param_name' => 'img_align',
					'std' => '',
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Radius', 'total-theme-core' ),
					'param_name' => 'img_border_radius',
					'value' => vcex_border_radius_choices(),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'img_bottom_margin',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_image_sizes',
					'heading' => esc_html__( 'Image Size', 'total-theme-core' ),
					'param_name' => 'img_size',
					'std' => 'wpex_custom',
					'group' => esc_html__( 'Media', 'total-theme-core' ),
					'dependency' => array( 'element' => 'image_source', 'value' => 'media_library' ),
				),
				array(
					'type' => 'vcex_image_crop_locations',
					'heading' => esc_html__( 'Image Crop Location', 'total-theme-core' ),
					'param_name' => 'img_crop',
					'group' => esc_html__( 'Media', 'total-theme-core' ),
					'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					'dependency' => array( 'element' => 'image_source', 'value' => 'media_library' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image Crop Width', 'total-theme-core' ),
					'param_name' => 'img_width',
					'group' => esc_html__( 'Media', 'total-theme-core' ),
					'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image Crop Height', 'total-theme-core' ),
					'param_name' => 'img_height',
					'description' => esc_html__( 'Leave empty to disable vertical cropping and keep image proportions.', 'total-theme-core' ),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
					'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
				),
				array(
					'type' => 'vcex_image_filters',
					'heading' => esc_html__( 'Image Filter', 'total-theme-core' ),
					'param_name' => 'img_filter',
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_image_hovers',
					'heading' => esc_html__( 'CSS3 Image Hover', 'total-theme-core' ),
					'param_name' => 'img_hover_style',
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				// Link
				array(
					'type' => 'vc_link',
					'heading' => esc_html__( 'URL', 'total-theme-core' ),
					'param_name' => 'url',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Local Scroll', 'total-theme-core' ),
					'param_name' => 'url_local_scroll',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'std' => 'false',
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_teaser' );

		}

	}

}
new VCEX_Teaser_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Teaser' ) ) {
	class WPBakeryShortCode_Vcex_Teaser extends WPBakeryShortCode {}
}