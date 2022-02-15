<?php
/**
 * Author Bio Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Author_Bio_Shortcode' ) ) {

	class VCEX_Author_Bio_Shortcode {

		/**
		 * Define shortcode name.
		 */
		public $shortcode = 'vcex_author_bio';

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_author_bio', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Author_Bio::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_author_bio', $atts );
			include( vcex_get_shortcode_template( 'vcex_author_bio' ) );
			do_action( 'vcex_shortcode_after', 'vcex_author_bio', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$alt_styles = array( 'alt-1', 'alt-2', 'alt-3', 'alt-4' );

			$params = array(
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
				// Style.
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Style', 'total-theme-core' ),
					'param_name' => 'style',
					'std' => 'default',
					'admin_label' => true,
					'value' => array(
						esc_html__( 'Theme Default', 'total-theme-core' ) => 'default',
						esc_html__( 'Alt 1', 'total-theme-core' )         => 'alt-1',
						esc_html__( 'Alt 2', 'total-theme-core' )         => 'alt-2',
						esc_html__( 'Alt 3', 'total-theme-core' )         => 'alt-3',
						esc_html__( 'Alt 4', 'total-theme-core' )         => 'alt-4',
					),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin', // can't name it margin_bottom due to WPBakery parsing issue.
					'value' => vcex_margin_choices(),
					'admin_label' => true,
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Max Width', 'total-theme-core' ),
					'param_name' => 'max_width',
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Aligment', 'total-theme-core' ),
					'param_name' => 'align',
					'dependency' => array( 'element' => 'max_width', 'not_empty' => true ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background Color', 'total' ),
					'param_name' => 'background_color',
					'dependency' => array( 'element' => 'style', 'value' => $alt_styles ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'padding_all',
					'value' => vcex_padding_choices(),
					'dependency' => array( 'element' => 'style', 'value' => $alt_styles ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Style', 'total-theme-core' ),
					'param_name' => 'border_style',
					'dependency' => array( 'element' => 'style', 'value' => $alt_styles ),
					'value' => vcex_border_style_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Width', 'total-theme-core' ),
					'param_name' => 'border_width',
					'dependency' => array( 'element' => 'style', 'value' => $alt_styles ),
					'value' => vcex_border_width_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Border Color', 'total-theme-core' ),
					'param_name' => 'border_color',
					'dependency' => array( 'element' => 'style', 'value' => $alt_styles ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Radius', 'total' ),
					'param_name' => 'border_radius',
					'value' => vcex_border_radius_choices(),
					'dependency' => array( 'element' => 'style', 'value' => $alt_styles ),
					'group' => esc_html__( 'Style', 'total' ),
				),
				// Avatar.
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Avatar Spacing', 'total-theme-core' ),
					'param_name' => 'avatar_spacing',
					'value' => vcex_margin_choices(),
					'dependency' => array( 'element' => 'style', 'value' => $alt_styles ),
					'group' => esc_html__( 'Avatar', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Avatar Size', 'total'),
					'param_name' => 'avatar_size',
					'dependency' => array( 'element' => 'style', 'value' => $alt_styles ),
					'group' => esc_html__( 'Avatar', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Radius', 'total' ),
					'param_name' => 'avatar_border_radius',
					'value' => vcex_border_radius_choices(),
					'dependency' => array( 'element' => 'style', 'value' => $alt_styles ),
					'group' => esc_html__( 'Avatar', 'total' ),
				),
				// Author Link.
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'On click action', 'total' ),
					'param_name' => 'author_onclick',
					'dependency' => array( 'element' => 'style', 'value' => $alt_styles ),
					'std' => 'author_archive',
					'value' => array(
						esc_html__( 'Open author archive', 'total-theme-core' ) => 'author_archive',
						esc_html__( 'Open author website', 'total-theme-core' ) => 'author_website',
						esc_html__( 'Do nothing', 'total-theme-core' )          => 'null',
					),
					'group' => esc_html__( 'Author Link', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title Attribute', 'total' ),
					'param_name' => 'author_onclick_title',
					'dependency' => array(
						'element' => 'author_archive',
						'value' => array( 'author_archive', 'author_website' )
					),
					'group' => esc_html__( 'Author Link', 'total' ),
				),
				// CSS.
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total' ),
					'param_name' => 'css',
					'dependency' => array( 'element' => 'style', 'value' => $alt_styles ),
					'group' => esc_html__( 'CSS', 'total' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_author_bio' );

		}

	}

}
new VCEX_Author_Bio_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_vcex_author_bio' ) ) {
	class WPBakeryShortCode_vcex_author_bio extends WPBakeryShortCode {}
}