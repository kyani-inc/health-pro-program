<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_WooCommerce_Loop_Carousel as Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_woocommerce_loop_carousel shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_WooCommerce_Loop_Carousel {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the class instance.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {
		add_action( 'vc_after_mapping', array( $this, 'vc_after_mapping' ) );
	}

	/**
	 * Run functions on vc_after_mapping hook.
	 */
	public function vc_after_mapping() {
		vc_lean_map( 'vcex_woocommerce_loop_carousel', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {

			// Get autocomplete suggestion
			add_filter(
				'vc_autocomplete_vcex_woocommerce_loop_carousel_include_categories_callback',
				'vcex_suggest_product_categories'
			);
			add_filter(
				'vc_autocomplete_vcex_woocommerce_loop_carousel_exclude_categories_callback',
				'vcex_suggest_product_categories'
			);

			// Render autocomplete suggestions
			add_filter(
				'vc_autocomplete_vcex_woocommerce_loop_carousel_include_categories_render',
				'vcex_render_product_categories'
			);
			add_filter(
				'vc_autocomplete_vcex_woocommerce_loop_carousel_exclude_categories_render',
				'vcex_render_product_categories'
			);

		}

	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		$category = array( 'WooCommerce' );
		$branding = vcex_shortcodes_branding();
		if ( $branding ) {
			$category[] = $branding;
		}
		return array(
			'name'        => esc_html__( 'Woo Products Carousel', 'total-theme-core' ),
			'description' => esc_html__( 'WooCommerce products carousel', 'total-theme-core' ),
			'base'        => 'vcex_woocommerce_loop_carousel',
			'category'    => $category,
			'icon'        => 'vcex_element-icon vcex_element-icon--woocommerce',
			'params'      => Shortcode::get_params(),
		);
	}

}