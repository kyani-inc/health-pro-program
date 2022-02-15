<?php
/**
 * Image Before/After Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Image_Before_After_Shortcode' ) ) {

	class VCEX_Image_Before_After_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_image_ba', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Image_Before_After::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_image_ba', $atts );
			include( vcex_get_shortcode_template( 'vcex_image_before_after' ) );
			do_action( 'vcex_shortcode_after', 'vcex_image_ba', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				// Images
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Source', 'total-theme-core' ),
					'param_name' => 'source',
					'group' => esc_html__( 'Images', 'total-theme-core' ),
					'std' => 'media_library',
					'value' => array(
						esc_html__( 'Media Library', 'total-theme-core' ) => 'media_library',
						esc_html__( 'Custom Field', 'total-theme-core' ) => 'custom_field',
					),
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Before', 'total-theme-core' ),
					'param_name' => 'before_img',
					'group' => esc_html__( 'Images', 'total-theme-core' ),
					'dependency' => array( 'element' => 'source', 'value' => 'media_library' ),
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'After', 'total-theme-core' ),
					'param_name' => 'after_img',
					'group' => esc_html__( 'Images', 'total-theme-core' ),
					'dependency' => array( 'element' => 'source', 'value' => 'media_library' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Before Image Custom Field Name', 'total-theme-core' ),
					'param_name' => 'before_img_custom_field',
					'group' => esc_html__( 'Images', 'total-theme-core' ),
					'dependency' => array( 'element' => 'source', 'value' => 'custom_field' ),
					'description' => esc_html__( 'Your custom field should return an attachment ID.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'After Image Custom Field Name', 'total-theme-core' ),
					'param_name' => 'after_img_custom_field',
					'group' => esc_html__( 'Images', 'total-theme-core' ),
					'dependency' => array( 'element' => 'source', 'value' => 'custom_field' ),
					'description' => esc_html__( 'Your custom field should return an attachment ID.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_image_sizes',
					'heading' => esc_html__( 'Image Size', 'total-theme-core' ),
					'param_name' => 'img_size',
					'std' => 'wpex_custom',
					'group' => esc_html__( 'Images', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_image_crop_locations',
					'heading' => esc_html__( 'Image Crop Location', 'total-theme-core' ),
					'param_name' => 'img_crop',
					'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					'group' => esc_html__( 'Images', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image Crop Width', 'total-theme-core' ),
					'param_name' => 'img_width',
					'group' => esc_html__( 'Images', 'total-theme-core' ),
					'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image Crop Height', 'total-theme-core' ),
					'param_name' => 'img_height',
					'description' => esc_html__( 'Leave empty to disable vertical cropping and keep image proportions.', 'total-theme-core' ),
					'group' => esc_html__( 'Images', 'total-theme-core' ),
					'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Default Offset Percentage', 'total-theme-core' ),
					'std' => '50%',
					'param_name' => 'default_offset_pct',
				),
				// General
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'el_class' ),
					'param_name' => 'el_class',
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
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Orientation', 'total-theme-core' ),
					'param_name' => 'orientation',
					'std' => 'horizontal',
					'choices' => array(
						'horizontal' => esc_html__( 'Horizontal', 'total-theme-core' ),
						'vertical' => esc_html__( 'Vertical', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'width',
					'group' => esc_html__( 'Images', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Align', 'total-theme-core' ),
					'param_name' => 'align',
					'group' => esc_html__( 'Images', 'total-theme-core' ),
					'dependency' => array( 'element' => 'width', 'not_empty' => true ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				// Overlay
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Overlay on Hover', 'total-theme-core' ),
					'std' => 'true',
					'param_name' => 'overlay',
					'group' => esc_html__( 'Overlay', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Before Label', 'total-theme-core' ),
					'param_name' => 'before_label',
					'dependency' => array( 'element' => 'overlay', 'value' => 'true' ),
					'group' => esc_html__( 'Overlay', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'After Label', 'total-theme-core' ),
					'param_name' => 'after_label',
					'dependency' => array( 'element' => 'overlay', 'value' => 'true' ),
					'group' => esc_html__( 'Overlay', 'total-theme-core' ),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_image_ba' );

		}

		/**
		 * Register scripts.
		 */
		public static function enqueue_scripts() {

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'imagesloaded' );

			wp_enqueue_script(
				'jquery-move',
				vcex_asset_url( 'js/lib/jquery.event.move.min.js' ),
				array( 'jquery' ),
				'2.0',
				true
			);

			wp_enqueue_script(
				'twentytwenty',
				vcex_asset_url( 'js/lib/jquery.twentytwenty.min.js' ),
				array( 'jquery', 'jquery-move' ),
				'1.0',
				true
			);

			wp_enqueue_script(
				'vcex-image-before-after',
				vcex_asset_url( 'js/shortcodes/vcex-image-before-after.min.js' ),
				array( 'jquery', 'jquery-move', 'twentytwenty' ),
				TTC_VERSION,
				true
			);

		}

	}

}
new VCEX_Image_Before_After_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Image_Ba' ) ) {
	class WPBakeryShortCode_Vcex_Image_Ba extends WPBakeryShortCode {}
}