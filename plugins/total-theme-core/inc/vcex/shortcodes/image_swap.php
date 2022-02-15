<?php
/**
 * Image Swap Shortcode
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Image_Swap_Shortcode' ) ) {

	class VCEX_Image_Swap_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_image_swap', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Image_Swap::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_image_swap', $atts );
			include( vcex_get_shortcode_template( 'vcex_image_swap' ) );
			do_action( 'vcex_shortcode_after', 'vcex_image_swap', $atts );
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
						esc_html__( 'Featured and Secondary image', 'total-theme-core' ) => 'featured',
					),
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Primary Image', 'total-theme-core' ),
					'param_name' => 'primary_image',
					'group' => esc_html__( 'Images', 'total-theme-core' ),
					'dependency' => array( 'element' => 'source', 'value' => 'media_library' ),
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Secondary Image', 'total-theme-core' ),
					'param_name' => 'secondary_image',
					'group' => esc_html__( 'Images', 'total-theme-core' ),
					'dependency' => array( 'element' => 'source', 'value' => 'media_library' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Primary Image Custom Field Name', 'total-theme-core' ),
					'param_name' => 'primary_image_custom_field',
					'group' => esc_html__( 'Images', 'total-theme-core' ),
					'dependency' => array( 'element' => 'source', 'value' => 'custom_field' ),
					'description' => esc_html__( 'Your custom field should return an attachment ID.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Secondary Image Custom Field Name', 'total-theme-core' ),
					'param_name' => 'secondary_image_custom_field',
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
				// General
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin', // can't name it margin_bottom due to WPBakery parsing issue
					'value' => vcex_margin_choices(),
					'admin_label' => true,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border Radius', 'total-theme-core' ),
					'param_name' => 'border_radius',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Container Width', 'total-theme-core' ),
					'param_name' => 'container_width',
					'description' => esc_html__( 'By default the images are stretched to 100% to fit the parent container. Enter a custom width (px or %) to restrict the width of your images.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Align', 'total-theme-core' ),
					'param_name' => 'align',
					'dependency' => array( 'element' => 'container_width', 'not_empty' => true ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Hover Swap Speed', 'total-theme-core' ),
					'param_name' => 'hover_speed',
					'value' => array(
						esc_html__( 'Default', 'total-theme-core' ) => '',
						'75ms' => '75',
						'100ms' => '100',
						'150ms' => '150',
						'200ms' => '200',
						'300ms' => '300',
						'500ms' => '500',
						'700ms' => '700',
						'1000ms' => '1000',
					),
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
				// Link
				array(
					'type' => 'vc_link',
					'heading' => esc_html__( 'Link', 'total-theme-core' ),
					'param_name' => 'link',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				// Design Options
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
				// Hidden
				array( 'type' => 'hidden', 'param_name' => 'link_title' ),
				array( 'type' => 'hidden', 'param_name' => 'link_target' ),
				array( 'type' => 'hidden', 'param_name' => 'dynamic_images' ),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_image_swap' );

		}

		/**
		 * Parses deprecated attributes.
		 */
		public static function parse_deprecated_attributes( $atts = '' ) {

			if ( empty( $atts ) || ! is_array( $atts ) ) {
				return $atts;
			}

			if ( isset( $atts['dynamic_images'] )
				&& ( 'true' == $atts['dynamic_images'] || 'yes' === $atts['dynamic_images'] )
			) {
				$atts['source'] = 'featured';
				unset( $atts['dynamic_images'] );
			}

			return $atts;

		}


	}

}
new VCEX_Image_Swap_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Image_Swap' ) ) {
	class WPBakeryShortCode_Vcex_Image_Swap extends WPBakeryShortCode {}
}