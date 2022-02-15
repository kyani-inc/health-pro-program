<?php
/**
 * Grid Container Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Grid_Container_Shortcode' ) ) {

	class VCEX_Grid_Container_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {

			add_shortcode( 'vcex_grid_container', __CLASS__ . '::output' );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Grid_Container::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public static function output( $atts, $content = null ) {
			if ( ! vcex_maybe_display_shortcode( 'vcex_grid_container', $atts ) ) {
				return;
			}

			ob_start();
				do_action( 'vcex_shortcode_before', 'vcex_grid_container', $atts );
				include( vcex_get_shortcode_template( 'vcex_grid_container' ) );
				do_action( 'vcex_shortcode_after', 'vcex_grid_container', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$column_options = array();

			$alt_col_options = array(
				esc_html__( 'Inherit', 'total-theme-core' ) => '',
			);

			$columns_count = 12;

			for( $i = 1; $i <= 12; $i++) {
				$column_options[$i] = $i;
				$alt_col_options[$i] = $i;
			}

			$params = array(
				array(
					'type' => 'vcex_notice',
					'param_name' => 'editor_notice',
					'text' => esc_html__( 'Because of how the frontend editor works, there could be some design inconsistencies when using this element, so it\'s best used via the backend.', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Align Items', 'total-theme-core' ),
					'param_name' => 'align_items',
					'value' => vcex_align_items_choices(),
					'description' => esc_html__( 'Set the align-items CSS property.', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Justify Items', 'total-theme-core' ),
					'param_name' => 'justify_items',
					'value' => vcex_align_items_choices(),
					'description' => esc_html__( 'Set the justify-items CSS property.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Gap', 'total-theme-core' ),
					'param_name' => 'gap',
					'description' => esc_html__( 'Spacing between elements. Default is 20px.', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns', 'total-theme-core' ),
					'param_name' => 'columns',
					'value' => $column_options,
					'std' => '1',
					'description' => esc_html__( 'This element uses a mobile-first design aproach, so the number of columns you select will be used on all devices. To display more columns or less columns on larger devices you can use the settings below. If you wish to stack your elements on devices smaller than 640px, select 1 for this option then use the settings below to select the columns you want displayed for larger screens.', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns xl', 'total-theme-core' ),
					'param_name' => 'columns_xl',
					'std' => '',
					'edit_field_class' => 'vc_col-sm-3 vc_column',
					'value' => $alt_col_options,
					'description' => esc_html__( 'For screens 1280px and greater.', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns lg', 'total-theme-core' ),
					'param_name' => 'columns_lg',
					'std' => '3',
					'edit_field_class' => 'vc_col-sm-3 vc_column',
					'value' => $alt_col_options,
					'description' => esc_html__( 'For screens 1024px and greater.', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns md', 'total-theme-core' ),
					'param_name' => 'columns_md',
					'std' => '',
					'edit_field_class' => 'vc_col-sm-3 vc_column',
					'value' => $alt_col_options,
					'description' => esc_html__( 'For screens 768px and greater.', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns sm', 'total-theme-core' ),
					'param_name' => 'columns_sm',
					'std' => '',
					'edit_field_class' => 'vc_col-sm-3 vc_column',
					'value' => $alt_col_options,
					'description' => esc_html__( 'For screens 640px and greater.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'param_name' => 'el_class',
					'description' => vcex_shortcode_param_description( 'el_class' ),
				),
				// Design
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'width',
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Shadow', 'total' ),
					'param_name' => 'shadow',
					'value' => vcex_shadow_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_grid_container' );

		}

	}

}
new VCEX_Grid_Container_Shortcode;

if ( class_exists( 'WPBakeryShortCodesContainer' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Grid_Container' ) ) {
	class WPBakeryShortCode_Vcex_Grid_Container extends WPBakeryShortCodesContainer {}
}