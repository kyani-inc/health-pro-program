<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Recent_News_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_recent_news shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Recent_News {

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
		vc_lean_map( 'vcex_recent_news', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		// Auto complete filters
		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {

			// Include categories.
			add_filter( 'vc_autocomplete_vcex_recent_news_include_categories_callback', 'vcex_suggest_categories' );
			add_filter( 'vc_autocomplete_vcex_recent_news_include_categories_render', 'vcex_render_categories' );

			// Exclude categories.
			add_filter( 'vc_autocomplete_vcex_recent_news_exclude_categories_callback', 'vcex_suggest_categories' );
			add_filter( 'vc_autocomplete_vcex_recent_news_exclude_categories_render', 'vcex_render_categories' );

			// Author.
			add_filter( 'vc_autocomplete_vcex_recent_news_author_in_callback', 'vcex_suggest_users' );
			add_filter( 'vc_autocomplete_vcex_recent_news_author_in_render', 'vcex_render_users' );

			// Categories taxonomy select.
			add_filter( 'vc_autocomplete_vcex_recent_news_categories_taxonomy_callback', 'vcex_suggest_taxonomies' );
			add_filter( 'vc_autocomplete_vcex_recent_news_categories_taxonomy_render', 'vcex_render_taxonomies' );

		}

		if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_recent_news', array( $this, 'edit_fields' ), 10 );
		}

	}

	/**
	 * Edit form fields.
	 */
	public function edit_fields( $atts ) {

		if ( empty( $atts['divider_color'] ) && ! empty( $atts['entry_bottom_border_color'] ) ) {
			$atts['divider_color'] = $atts['entry_bottom_border_color'];
			unset( $atts['entry_bottom_border_color'] );
		}

		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Recent News', 'total-theme-core' ),
			'description' => esc_html__( 'Posts with calendar style date', 'total-theme-core' ),
			'base'        => 'vcex_recent_news',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--recent-news',
			'params'      => VCEX_Recent_News_Shortcode::get_params(),
		);
	}

}