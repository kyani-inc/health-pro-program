<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Post_Type_Grid_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_post_type_grid shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Post_Type_Grid {

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

		vc_lean_map( 'vcex_post_type_grid', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {

			// Tax query
			add_filter( 'vc_autocomplete_vcex_post_type_grid_tax_query_taxonomy_callback', 'vcex_suggest_taxonomies' );
			add_filter( 'vc_autocomplete_vcex_post_type_grid_tax_query_taxonomy_render', 'vcex_render_taxonomies' );

			// Categories taxonomy
			add_filter( 'vc_autocomplete_vcex_post_type_grid_categories_taxonomy_callback', 'vcex_suggest_taxonomies' );
			add_filter( 'vc_autocomplete_vcex_post_type_grid_categories_taxonomy_render', 'vcex_render_taxonomies' );

			// Filter taxonomy
			add_filter( 'vc_autocomplete_vcex_post_type_grid_filter_taxonomy_callback', 'vcex_suggest_taxonomies' );
			add_filter( 'vc_autocomplete_vcex_post_type_grid_filter_taxonomy_render', 'vcex_render_taxonomies' );

			// Terms query
			add_filter( 'vc_autocomplete_vcex_post_type_grid_tax_query_terms_callback', 'vcex_suggest_terms' );
			add_filter( 'vc_autocomplete_vcex_post_type_grid_tax_query_terms_render', 'vcex_render_terms' );

			// Author query
			add_filter( 'vc_autocomplete_vcex_post_type_grid_author_in_callback', 'vcex_suggest_users' );
			add_filter( 'vc_autocomplete_vcex_post_type_grid_author_in_render', 'vcex_render_users' );

			// Posts In query
			add_filter( 'vc_autocomplete_vcex_post_type_grid_posts_in_callback', 'vc_include_field_search' );
			add_filter( 'vc_autocomplete_vcex_post_type_grid_posts_in_render', 'vc_include_field_render' );

		}

		if ( 'vc_edit_form' === $vc_action ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_post_type_grid', 'vcex_parse_deprecated_grid_entry_content_css' );
		}

	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Post Types Grid', 'total-theme-core' ),
			'description' => esc_html__( 'Posts grid', 'total-theme-core' ),
			'base'        => 'vcex_post_type_grid',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--post-type-grid',
			'params'      => VCEX_Post_Type_Grid_Shortcode::get_params(),
		);
	}

}