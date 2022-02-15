<?php
/**
 * Post Media Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Post_Media_Shortcode' ) ) {

	class VCEX_Post_Media_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_post_media', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Post_Media::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_post_media', $atts );
			include( vcex_get_shortcode_template( 'vcex_post_media' ) );
			do_action( 'vcex_shortcode_after', 'vcex_post_media', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'width',
					'description' => vcex_shortcode_param_description( 'width' ),
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
					'admin_label' => true,
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-theme-core' ),
					'param_name' => 'classes',
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
				// Media
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Allowed Media Types', 'total-theme-core' ),
					'param_name' => 'supported_media',
					'std' => 'thumbnail,video,audio,gallery',
					'value' => array(
						esc_html__( 'Thumbnail', 'js_composer' ) => 'thumbnail',
						esc_html__( 'Video', 'js_composer' ) => 'video',
						esc_html__( 'Audio', 'js_composer' ) => 'audio',
						esc_html__( 'Gallery', 'js_composer' ) => 'gallery',
					),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Lightbox', 'total-theme-core' ),
					'param_name' => 'lightbox',
					'std' => 'false',
					'description' => esc_html__( 'Enable lightbox for the Thumbnail or Gallery.', 'total' ),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_image_sizes',
					'heading' => esc_html__( 'Image Size', 'total-theme-core' ),
					'param_name' => 'img_size',
					'std' => 'wpex_custom',
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_image_crop_locations',
					'heading' => esc_html__( 'Image Crop Location', 'total-theme-core' ),
					'param_name' => 'img_crop',
					'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image Crop Width', 'total-theme-core' ),
					'param_name' => 'img_width',
					'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					'description' => esc_html__( 'Enter a width in pixels.', 'total-theme-core' ),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image Crop Height', 'total-theme-core' ),
					'param_name' => 'img_height',
					'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					'description' => esc_html__( 'Leave empty to disable vertical cropping and keep image proportions.', 'total-theme-core' ),
					'group' => esc_html__( 'Media', 'total-theme-core' ),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_post_media' );

		}

	}

}
new VCEX_Post_Media_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Post_Media' ) ) {
	class WPBakeryShortCode_Vcex_Post_Media extends WPBakeryShortCode {}
}