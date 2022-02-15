<?php
/**
 * WooCommerce Loop Carousel.
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 1.3.2
 */

if ( ! class_exists( 'VCEX_WooCommerce_Loop_Carousel' ) ) {

	class VCEX_WooCommerce_Loop_Carousel {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_woocommerce_loop_carousel', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_WooCommerce_Loop_Carousel::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_woocommerce_loop_carousel', $atts );
			include( vcex_get_shortcode_template( 'vcex_woocommerce_loop_carousel' ) );
			do_action( 'vcex_shortcode_after', 'vcex_woocommerce_loop_carousel', $atts );
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
				// Query
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Advanced Query', 'total-theme-core' ),
					'param_name' => 'custom_query',
					'group' => esc_html__( 'Query', 'total-theme-core' ),
					'description' => esc_html__( 'Enable to build a custom query using your own parameters.', 'total-theme-core' ),
				),
				array(
					'type' => 'textarea_safe',
					'heading' => esc_html__( 'Query Parameter String or Callback Function Name', 'total-theme-core' ),
					'param_name' => 'custom_query_args',
					'description' => esc_html__( 'Build a query according to the WordPress Codex in string format. Example: posts_per_page=-1&post_type=portfolio&post_status=publish&orderby=title or enter a custom callback function name that will return an array of query arguments.', 'total-theme-core' ),
					'group' => esc_html__( 'Query', 'total-theme-core' ),
					'dependency' => array( 'element' => 'custom_query', 'value' => array( 'true' ) ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Post Count', 'total-theme-core' ),
					'param_name' => 'count',
					'value' => '8',
					'group' => esc_html__( 'Query', 'total-theme-core' ),
					'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Display', 'total-theme-core' ),
					'param_name' => 'query_products_by',
					'group' => esc_html__( 'Query', 'total-theme-core' ),
					'value' => array(
						esc_html__( 'All Products', 'total-theme-core' ) => '',
						esc_html__( 'Featured Products', 'total-theme-core' ) => 'featured',
						esc_html__( 'On Sale Products', 'total-theme-core' ) => 'on_sale',
					),
					'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => '',
					'vcex' => array( 'on' => true, 'off' => '' ),
					'heading' => esc_html__( 'Exclude Out of Stock Products', 'total-theme-core' ),
					'param_name' => 'exclude_products_out_of_stock',
					'group' => esc_html__( 'Query', 'total-theme-core' ),
					'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Include Categories', 'total-theme-core' ),
					'param_name' => 'include_categories',
					'param_holder_class' => 'vc_not-for-custom',
					'admin_label' => true,
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'groups' => true,
						'unique_values' => true,
						'display_inline' => true,
						'delay' => 0,
						'auto_focus' => true,
					),
					'group' => esc_html__( 'Query', 'total-theme-core' ),
					'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Exclude Categories', 'total-theme-core' ),
					'param_name' => 'exclude_categories',
					'param_holder_class' => 'vc_not-for-custom',
					'admin_label' => true,
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'groups' => true,
						'unique_values' => true,
						'display_inline' => true,
						'delay' => 0,
						'auto_focus' => true,
					),
					'group' => esc_html__( 'Query', 'total-theme-core' ),
					'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order', 'total-theme-core' ),
					'param_name' => 'order',
					'group' => esc_html__( 'Query', 'total-theme-core' ),
					'value' => array(
						esc_html__( 'Default', 'total-theme-core' ) => '',
						esc_html__( 'DESC', 'total-theme-core' ) => 'DESC',
						esc_html__( 'ASC', 'total-theme-core' ) => 'ASC',
					),
					'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
				),
				array(
					'type' => 'vcex_orderby',
					'heading' => esc_html__( 'Order By', 'total-theme-core' ),
					'param_name' => 'orderby',
					'group' => esc_html__( 'Query', 'total-theme-core' ),
					'post_type' => 'woo_product',
					'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Orderby: Meta Key', 'total-theme-core' ),
					'param_name' => 'orderby_meta_key',
					'group' => esc_html__( 'Query', 'total-theme-core' ),
					'dependency' => array(
						'element' => 'orderby',
						'value' => array( 'meta_value_num', 'meta_value' ),
					),
				),
				array( 'type' => 'hidden', 'param_name' => 'entry_output' ),
			);

			$params = array_merge( $params, vcex_vc_map_carousel_settings() );

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_woocommerce_loop_carousel' );

		}

	}

}
new VCEX_WooCommerce_Loop_Carousel;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_woocommerce_Loop_Carousel' ) ) {
	class WPBakeryShortCode_Vcex_woocommerce_Loop_Carousel extends WPBakeryShortCode {}
}