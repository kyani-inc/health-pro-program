<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Testimonials_Slider_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_testimonials_slider shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Testimonials_Slider {

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
		vc_lean_map( 'vcex_testimonials_slider', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {

			// Get autocomplete suggestion
			add_filter(
				'vc_autocomplete_vcex_testimonials_slider_include_categories_callback',
				'vcex_suggest_testimonials_categories'
			);
			add_filter(
				'vc_autocomplete_vcex_testimonials_slider_exclude_categories_callback',
				'vcex_suggest_testimonials_categories'
			);

			// Render autocomplete suggestions
			add_filter(
				'vc_autocomplete_vcex_testimonials_slider_include_categories_render',
				'vcex_render_testimonials_categories'
			);
			add_filter(
				'vc_autocomplete_vcex_testimonials_slider_exclude_categories_render',
				'vcex_render_testimonials_categories'
			);

		}

		if ( 'vc_edit_form' === $vc_action ) {

			add_filter( 'vc_edit_form_fields_attributes_vcex_testimonials_slider', array( $this, 'edit_form_fields' ) );

		}

	}

	/**
	 * Parse old shortcode attributes.
	 */
	public function edit_form_fields( $atts ) {
		if ( ! empty( $atts['animation'] ) && 'fade' === $atts['animation'] ) {
			$atts['animation'] = 'fade_slides';
		}
		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Testimonials Slider', 'total-theme-core' ),
			'description' => esc_html__( 'Recent testimonials slider', 'total-theme-core' ),
			'base'        => 'vcex_testimonials_slider',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--testimonial',
			'params'      => VCEX_Testimonials_Slider_Shortcode::get_params()
		);
	}

}