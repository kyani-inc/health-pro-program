<?php
namespace TotalThemeCore\WPBakery\Map;
use \WPEX_Post_Cards_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the WPEX_Post_Cards shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class WPEX_Post_Cards {

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
		vc_lean_map( 'wpex_post_cards', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {

			// Tax query.
			add_filter( 'vc_autocomplete_wpex_post_cards_tax_query_taxonomy_callback', 'vcex_suggest_taxonomies' );
			add_filter( 'vc_autocomplete_wpex_post_cards_tax_query_taxonomy_render', 'vcex_render_taxonomies' );

			// Categories taxonomy.
			add_filter( 'vc_autocomplete_wpex_post_cards_categories_taxonomy_callback', 'vcex_suggest_taxonomies' );
			add_filter( 'vc_autocomplete_wpex_post_cards_categories_taxonomy_render', 'vcex_render_taxonomies' );

			// Terms query.
			add_filter( 'vc_autocomplete_wpex_post_cards_tax_query_terms_callback', 'vcex_suggest_terms' );
			add_filter( 'vc_autocomplete_wpex_post_cards_tax_query_terms_render', 'vcex_render_terms' );

			// Author query.
			add_filter( 'vc_autocomplete_wpex_post_cards_author_in_callback', 'vcex_suggest_users' );
			add_filter( 'vc_autocomplete_wpex_post_cards_author_in_render', 'vcex_render_users' );

			// Posts In query.
			add_filter( 'vc_autocomplete_wpex_post_cards_posts_in_callback', 'vc_include_field_search' );
			add_filter( 'vc_autocomplete_wpex_post_cards_posts_in_render', 'vc_include_field_render' );

		}
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Post Cards', 'total-theme-core' ),
			'description' => esc_html__( 'Post based card list, grid or carousel.', 'total-theme-core' ),
			'base'        => 'wpex_post_cards',
			'icon'        => 'vcex_element-icon vcex_element-icon--post-cards',
			'category'    => vcex_shortcodes_branding(),
			'params'      => WPEX_Post_Cards_Shortcode::get_params(),
		);

	}

}