<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Portfolio_Carousel_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_portfolio_carousel shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Portfolio_Carousel {

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
		vc_lean_map( 'vcex_portfolio_carousel', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {

			// Get autocomplete suggestion.
			add_filter(
				'vc_autocomplete_vcex_portfolio_carousel_include_categories_callback',
				'vcex_suggest_portfolio_categories'
			);

			add_filter(
				'vc_autocomplete_vcex_portfolio_carousel_exclude_categories_callback',
				'vcex_suggest_portfolio_categories'
			);

			// Render autocomplete suggestions.
			add_filter(
				'vc_autocomplete_vcex_portfolio_carousel_include_categories_render',
				'vcex_render_portfolio_categories'
			);

			add_filter(
				'vc_autocomplete_vcex_portfolio_carousel_exclude_categories_render',
				'vcex_render_portfolio_categories'
			);

		}

		if ( 'vc_edit_form' === $vc_action ) {

			add_filter(
				'vc_edit_form_fields_attributes_vcex_portfolio_carousel',
				'vcex_parse_deprecated_grid_entry_content_css'
			);

		}

	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Portfolio Carousel', 'total-theme-core' ),
			'description' => esc_html__( 'Recent portfolio posts carousel', 'total-theme-core' ),
			'base'        => 'vcex_portfolio_carousel',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--portfolio',
			'params'      => VCEX_Portfolio_Carousel_Shortcode::get_params(),
		);
	}

}