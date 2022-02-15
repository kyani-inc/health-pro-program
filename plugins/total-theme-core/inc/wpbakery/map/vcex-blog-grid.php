<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Blog_Grid_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_blog_grid shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Blog_Grid {

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
		vc_lean_map( 'vcex_blog_grid', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {

			// Get autocomplete suggestion.
			add_filter( 'vc_autocomplete_vcex_blog_grid_include_categories_callback', 'vcex_suggest_categories', 10, 1 );
			add_filter( 'vc_autocomplete_vcex_blog_grid_exclude_categories_callback', 'vcex_suggest_categories', 10, 1 );
			add_filter( 'vc_autocomplete_vcex_blog_grid_filter_active_category_callback', 'vcex_suggest_categories', 10, 1 );
			add_filter( 'vc_autocomplete_vcex_blog_grid_author_in_callback', 'vcex_suggest_users', 10, 1 );

			// Render autocomplete suggestions.
			add_filter( 'vc_autocomplete_vcex_blog_grid_include_categories_render', 'vcex_render_categories', 10, 1 );
			add_filter( 'vc_autocomplete_vcex_blog_grid_exclude_categories_render', 'vcex_render_categories', 10, 1 );
			add_filter( 'vc_autocomplete_vcex_blog_grid_filter_active_category_render', 'vcex_render_categories', 10, 1 );
			add_filter( 'vc_autocomplete_vcex_blog_grid_author_in_render', 'vcex_render_users', 10, 1 );

		}

		if ( 'vc_edit_form' === $vc_action ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_blog_grid', 'vcex_parse_deprecated_grid_entry_content_css' );
		}

	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Blog Grid', 'total-theme-core' ),
			'description' => esc_html__( 'Recent blog posts grid', 'total-theme-core' ),
			'base'        => 'vcex_blog_grid',
			'icon'        => 'vcex_element-icon vcex_element-icon--blog-grid',
			'category'    => vcex_shortcodes_branding(),
			'params'      => VCEX_Blog_Grid_Shortcode::get_params(),
		);
	}

}