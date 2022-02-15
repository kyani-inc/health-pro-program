<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Terms_Carousel_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_terms_carousel shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Terms_Carousel {

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
		vc_lean_map( 'vcex_terms_carousel', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {

			add_filter( 'vc_autocomplete_vcex_terms_carousel_exclude_terms_callback', 'vcex_suggest_terms' );
			add_filter( 'vc_autocomplete_vcex_terms_carousel_exclude_terms_render', 'vcex_render_terms' );

			add_filter( 'vc_autocomplete_vcex_terms_carousel_taxonomy_callback', 'vcex_suggest_taxonomies' );
			add_filter( 'vc_autocomplete_vcex_terms_carousel_taxonomy_render', 'vcex_render_taxonomies' );

			add_filter( 'vc_autocomplete_vcex_terms_carousel_child_of_callback', 'vcex_suggest_terms' );
			add_filter( 'vc_autocomplete_vcex_terms_carousel_child_of_render', 'vcex_render_terms' );

		}

		if ( 'vc_edit_form' === $vc_action ) {

			add_filter( 'vc_edit_form_fields_attributes_vcex_terms_carousel', array( $this, 'edit_form_fields' ) );

		}
	}

	/**
	 * Edit form fields.
	 */
	public function edit_form_fields( $atts ) {
		$atts = VCEX_Terms_Carousel_Shortcode::parse_deprecated_attributes( $atts );
		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Categories/Terms Carousel', 'total-theme-core' ),
			'description' => esc_html__( 'Carousel of taxonomy terms', 'total-theme-core' ),
			'base'        => 'vcex_terms_carousel',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--terms-carousel',
			'params'      => VCEX_Terms_Carousel_Shortcode::get_params(),
		);
	}

}