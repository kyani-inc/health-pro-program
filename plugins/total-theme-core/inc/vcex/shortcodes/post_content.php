<?php
/**
 * Post Content Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Post_Content_Shortcode' ) ) {

	class VCEX_Post_Content_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_post_content', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Post_Content::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_post_content', $atts );
			include( vcex_get_shortcode_template( 'vcex_post_content' ) );
			do_action( 'vcex_shortcode_after', 'vcex_post_content', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				array(
					'type' => 'vcex_notice',
					'param_name' => 'vcex_notice__general',
					'text' => esc_html__( 'The Post Content module should be used only when creating a custom template via templatera that will override the default output of a post/page.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Enable Sidebar', 'total-theme-core' ),
					'param_name' => 'sidebar',
					'std' => 'false',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Sidebar Position', 'total-theme-core' ),
					'param_name' => 'sidebar_position',
					'value' => array(
						esc_html__( 'Right', 'total-theme-core' ) => 'right',
						esc_html__( 'Left', 'total-theme-core' ) => 'left',
					),
					'dependency' => array( 'element' => 'sidebar', 'value' => 'true' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'width',
					'description' => vcex_shortcode_param_description( 'width' ),
				),
				array(
					'type' => 'vcex_sorter',
					'heading' => esc_html__( 'Blocks', 'total-theme-core' ),
					'param_name' => 'blocks',
					'std' => 'the_content',
					'admin_label' => true,
					'choices' => apply_filters( 'vcex_post_content_blocks', array(
						'the_content'    => esc_html__( 'The Content', 'total-theme-core' ),
						'featured_media' => esc_html__( 'Featured Media', 'total-theme-core' ),
						'title'          => esc_html__( 'Title', 'total-theme-core' ),
						'meta'           => esc_html__( 'Meta', 'total-theme-core' ),
						'series'         => esc_html__( 'Series', 'total-theme-core' ),
						'social_share'   => esc_html__( 'Social Share', 'total-theme-core' ),
						'author_bio'     => esc_html__( 'Author Bio', 'total-theme-core' ),
						'related'        => esc_html__( 'Related Posts', 'total-theme-core' ),
						'comments'       => esc_html__( 'Comments', 'total-theme-core' ),
					) ),
					'description' => esc_html__( 'By default only "The Content" block is enabled but you can click on the toggle icon to enable any block. Drag and drop the items for custom sorting. The purpose for allowing you to enable other blocks is so you can have them next to the sidebar when enabled in this module. You can also add custom blocks via your child theme or code snippets plugin using the "vcex_post_content_blocks" filter.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-theme-core' ),
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
				// Typography
				array(
					'type' => 'vcex_notice',
					'param_name' => 'vcex_notice__typo',
					'text' => esc_html__( 'The following settings are only applied to the main content, it won\'t affect other enabled blocks such as the title, meta, etc. You can always insert those blocks seperately for greater control.', 'total-theme-core' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'font_family',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
					'description' => esc_html__( 'Applies to the content block only.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_responsive_sizes',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'font_size',
					'target' => 'font-size',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
					'description' => esc_html__( 'Applies to the content block only.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'color',
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
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_post_content' );

		}

	}

}
new VCEX_Post_Content_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Post_Content' ) ) {
	class WPBakeryShortCode_Vcex_Post_Content extends WPBakeryShortCode {}
}