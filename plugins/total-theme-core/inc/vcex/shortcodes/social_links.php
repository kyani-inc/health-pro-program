<?php
/**
 * Social Links Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Social_Links_Shortcode' ) ) {

	class VCEX_Social_Links_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_social_links', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Social_Links::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_social_links', $atts );
			include( vcex_get_shortcode_template( 'vcex_social_links' ) );
			do_action( 'vcex_shortcode_after', 'vcex_social_links', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {
			$social_link_select = array();

			// Get array of social links to loop through.
			$social_links = vcex_social_links_profiles();

			if ( ! empty( $social_links ) ) {
				foreach ( $social_links as $key => $val ) {
					$social_link_select[$val['label']] = $key;
				}
			}

			$params = array(
				// Social Links
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Post Author Links', 'total-theme-core' ),
					'param_name' => 'author_links',
					'description' => esc_html__( 'Enable to display the social links for the current post author.', 'total-theme-core' ),
					'group' => esc_html__( 'Profiles', 'total-theme-core' ),
				),
				array(
					'type' => 'param_group',
					'param_name' => 'social_links',
					'group' => esc_html__( 'Profiles', 'total-theme-core' ),
					'value' => urlencode( json_encode( array( ) ) ),
					'dependency' => array( 'element' => 'author_links', 'value' => 'false' ),
					'params' => array(
						array(
							'type' => 'dropdown',
							'heading' => esc_html__( 'Site', 'total-theme-core' ),
							'param_name' => 'site',
							'admin_label' => true,
							'value' => $social_link_select,
						),
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Link', 'total-theme-core' ),
							'param_name' => 'link',
						),
					),
				),
				// General
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin', // can't name it margin_bottom due to WPBakery parsing issue
					'value' => vcex_margin_choices(),
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_hover_animations',
					'heading' => esc_html__( 'Hover Animation', 'total-theme-core'),
					'param_name' => 'hover_animation',
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Link Target', 'total-theme-core'),
					'param_name' => 'link_target',
					'std' => 'self',
					'choices' => 'link_target',
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Show Labels', 'total-theme-core' ),
					'param_name' => 'show_label',
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
					'type' => 'vcex_social_button_styles',
					'heading' => esc_html__( 'Style', 'total-theme-core'),
					'param_name' => 'style',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Spacing', 'total-theme-core' ),
					'param_name' => 'spacing',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'value' => vcex_margin_choices(),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Expand Items', 'total-theme-core' ),
					'param_name' => 'expand',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Align', 'total-theme-core' ),
					'param_name' => 'align',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'expand', 'value' => 'false' ),
				),
				array(
					'type' => 'vcex_responsive_sizes',
					'target' => 'font-size',
					'heading' => esc_html__( 'Custom Icon Size', 'total-theme-core' ),
					'param_name' => 'size',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Radius', 'total' ),
					'param_name' => 'border_radius',
					'value' => vcex_border_radius_choices(),
					'group' => esc_html__( 'Style', 'total' ),
					'dependency' => array( 'element' => 'style', 'value' => 'none' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Vertical Padding', 'total-theme-core' ),
					'param_name' => 'padding_y',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'height', 'is_empty' => true ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Horizontal Padding', 'total-theme-core' ),
					'param_name' => 'padding_x',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'width', 'is_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'width',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'width' ),
					'dependency' => array( 'element' => 'expand', 'value' => 'false' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Height', 'total-theme-core' ),
					'param_name' => 'height',
					'description' => vcex_shortcode_param_description( 'height' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color: Hover', 'total-theme-core' ),
					'param_name' => 'hover_color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background', 'total-theme-core' ),
					'param_name' => 'bg',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background: Hover', 'total-theme-core' ),
					'param_name' => 'hover_bg',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Border Color', 'total-theme-core' ),
					'param_name' => 'border_color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array(
						'element' => 'style',
						'value' => array(
							'minimal',
							'minimal-rounded',
							'minimal-round',
							'bordered',
							'bordered-rounded',
							'bordered-round',
						)
					),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'Custom CSS applied to each social link.', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
				// Deprecated
				array( 'type' => 'hidden', 'param_name' => 'line_height' ), // @since 1.2.8
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_social_links' );

		}

	}

}
new VCEX_Social_Links_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Social_Links' ) ) {
	class WPBakeryShortCode_Vcex_Social_Links extends WPBakeryShortCode {}
}