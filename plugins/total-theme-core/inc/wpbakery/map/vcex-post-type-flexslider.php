<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Post_Type_Flexslider_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_post_type_flexslider shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Post_Type_Flexslider {

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
		vc_lean_map( 'vcex_post_type_flexslider', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {

			// Get autocomplete suggestion
			add_filter(
				'vc_autocomplete_vcex_post_type_flexslider_tax_query_taxonomy_callback',
				'vcex_suggest_taxonomies'
			);
			add_filter(
				'vc_autocomplete_vcex_post_type_flexslider_tax_query_terms_callback',
				'vcex_suggest_terms'
			);
			add_filter(
				'vc_autocomplete_vcex_post_type_flexslider_author_in_callback',
				'vcex_suggest_users'
			);

			// Render autocomplete suggestions
			add_filter(
				'vc_autocomplete_vcex_post_type_flexslider_tax_query_taxonomy_render',
				'vcex_render_taxonomies'
			);
			add_filter(
				'vc_autocomplete_vcex_post_type_flexslider_tax_query_terms_render',
				'vcex_render_terms'
			);
			add_filter(
				'vc_autocomplete_vcex_post_type_flexslider_author_in_render',
				'vcex_render_users'
			);

		}
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Post Types Slider', 'total-theme-core' ),
			'description' => esc_html__( 'Posts slider', 'total-theme-core' ),
			'base'        => 'vcex_post_type_flexslider',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--post-type-slider',
			'params'      => VCEX_Post_Type_Flexslider_Shortcode::get_params(),
		);
	}

}