<?php
defined( 'ABSPATH' ) || exit;

/**
 * Post Terms Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */
if ( ! class_exists( 'VCEX_Post_Terms_Shortcode' ) ) {

	class VCEX_Post_Terms_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_post_terms', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Post_Terms::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_post_terms', $atts );
			include( vcex_get_shortcode_template( 'vcex_post_terms' ) );
			do_action( 'vcex_shortcode_after', 'vcex_post_terms', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Style', 'total-theme-core' ),
					'param_name' => 'style',
					'std' => 'buttons',
					'choices' => array(
						'buttons' => esc_html__( 'Buttons', 'total-theme-core' ),
						'inline'  => esc_html__( 'Inline List', 'total-theme-core' ),
						'ul'      => esc_html__( 'UL List', 'total-theme-core' ),
						'ol'      => esc_html__( 'OL List', 'total-theme-core' ),
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Max Width', 'total-theme-core' ),
					'param_name' => 'max_width',
					'description' => vcex_shortcode_param_description( 'width' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Aligment', 'total-theme-core' ),
					'param_name' => 'align',
					'dependency' => array( 'element' => 'max_width', 'not_empty' => true ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Spacing', 'total-theme-core' ),
					'param_name' => 'spacing',
					'dependency' => array( 'element' => 'style', 'value' => 'buttons' ),
					'value' => vcex_margin_choices(),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Before Text', 'total-theme-core' ),
					'param_name' => 'before_text',
					'dependency' => array( 'element' => 'style', 'value' => 'inline' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Spacer', 'total-theme-core' ),
					'param_name' => 'spacer',
					'dependency' => array( 'element' => 'style', 'value' => 'inline' ),
					'description' => esc_html__( 'Enter a custom spacer to insert between items such as a comma.', 'total-theme-core' ),
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Taxonomy', 'total-theme-core' ),
					'param_name' => 'taxonomy',
					'admin_label' => true,
					'std' => '',
					'settings' => array(
						'multiple' => false,
						'min_length' => 1,
						'groups' => false,
						'unique_values' => true,
						'display_inline' => true,
						'delay' => 0,
						'auto_focus' => true,
					),
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Child Of', 'total-theme-core' ),
					'param_name' => 'child_of',
					'settings' => array(
						'multiple' => false,
						'min_length' => 1,
						'groups' => true,
						'display_inline' => true,
						'delay' => 0,
						'auto_focus' => true,
					),
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Exclude terms', 'total-theme-core' ),
					'param_name' => 'exclude_terms',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'groups' => true,
						'display_inline' => true,
						'delay' => 0,
						'auto_focus' => true,
					),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Display First or Primary Term Only', 'total-theme-core' ),
					'param_name' => 'first_term_only',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order', 'total-theme-core' ),
					'param_name' => 'order',
					'value' => array(
						esc_html__( 'ASC', 'total-theme-core' ) => 'ASC',
						esc_html__( 'DESC', 'total-theme-core' ) => 'DESC',
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order By', 'total-theme-core' ),
					'param_name' => 'orderby',
					'value' => array(
						esc_html__( 'Name', 'total-theme-core' ) => 'name',
						esc_html__( 'Slug', 'total-theme-core' ) => 'slug',
						esc_html__( 'Term Group', 'total-theme-core' ) => 'term_group',
						esc_html__( 'Term ID', 'total-theme-core' ) => 'term_id',
						'ID' => 'id',
						esc_html__( 'Description', 'total-theme-core' ) => 'description',
					),
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
					'description' => sprintf( esc_html__( 'Optional element ID (Note: make sure it is unique and valid according to %sw3c specification%s).', 'total-theme-core' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank" rel="noopener noreferrer">', '</a>' ),
				),
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'param_name' => 'classes',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-theme-core' ),
				),
				vcex_vc_map_add_css_animation(),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Duration', 'total' ),
					'param_name' => 'animation_duration',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Delay', 'total' ),
					'param_name' => 'animation_delay',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total' ),
				),
				// Link
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Link to Archive?', 'total-theme-core' ),
					'param_name' => 'archive_link',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Link Target', 'total-theme-core' ),
					'param_name' => 'archive_link_target',
					'std' => 'self',
					'choices' => 'link_target',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'dependency' => array( 'element' => 'archive_link', 'value' => 'true' )
				),
				// Design
				array(
					'type' => 'vcex_button_styles',
					'heading' => esc_html__( 'Button Style', 'total-theme-core' ),
					'param_name' => 'button_style',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'style', 'value' => 'buttons' ),
				),
				array(
					'type' => 'vcex_button_colors',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'button_color_style',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'style', 'value' => 'buttons' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Alignment', 'total-theme-core' ),
					'param_name' => 'button_align',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'style', 'value' => 'buttons' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Size', 'total-theme-core' ),
					'param_name' => 'button_size',
					'std' => '',
					'value' => array(
						esc_html__( 'Default', 'total-theme-core' ) => '',
						esc_html__( 'Small', 'total-theme-core' ) => 'small',
						esc_html__( 'Medium', 'total-theme-core' ) => 'medium',
						esc_html__( 'Large', 'total-theme-core' ) => 'large',
					),
					'dependency' => array( 'element' => 'style', 'value' => 'buttons' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background', 'total-theme-core' ),
					'param_name' => 'button_background',
					'dependency' => array( 'element' => 'style', 'value' => 'buttons' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'extra_choices' => array(
						esc_html__( 'Term Color', 'total-theme-core' ) => 'term_color',
					),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background: Hover', 'total-theme-core' ),
					'param_name' => 'button_hover_background',
					'dependency' => array( 'element' => 'style', 'value' => 'buttons' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'extra_choices' => array(
						esc_html__( 'Term Color', 'total-theme-core' ) => 'term_color',
					),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'button_color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'extra_choices' => array(
						esc_html__( 'Term Color', 'total-theme-core' ) => 'term_color',
					),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color: Hover', 'total-theme-core' ),
					'param_name' => 'button_hover_color',
					'dependency' => array( 'element' => 'style', 'value' => 'buttons' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'extra_choices' => array(
						esc_html__( 'Term Color', 'total-theme-core' ) => 'term_color',
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border Radius', 'total-theme-core' ),
					'param_name' => 'button_border_radius',
					'dependency' => array( 'element' => 'style', 'value' => 'buttons' ),
					'description' => vcex_shortcode_param_description( 'border_radius' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'button_padding',
					'description' => vcex_shortcode_param_description( 'padding' ),
					'dependency' => array( 'element' => 'style', 'value' => 'buttons' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Margin', 'total-theme-core' ),
					'param_name' => 'button_margin',
					'description' => vcex_shortcode_param_description( 'margin' ),
					'dependency' => array( 'element' => 'style', 'value' => 'buttons' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				// Typography
				array(
					'type' => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'button_font_family',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'button_font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Letter Spacing', 'total-theme-core' ),
					'param_name' => 'button_letter_spacing',
					'description' => vcex_shortcode_param_description( 'letter_spacing' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_transforms',
					'heading' => esc_html__( 'Text Transform', 'total-theme-core' ),
					'param_name' => 'button_text_transform',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'button_font_weight',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				// Renamed/deprecated atts
				array( 'type' => 'hidden', 'param_name' => 'target' ),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_post_terms' );

		}

	}

}
new VCEX_Post_Terms_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Post_Terms' ) ) {
	class WPBakeryShortCode_Vcex_Post_Terms extends WPBakeryShortCode {}
}