<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Post_Terms_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_post_terms shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Post_Terms {

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

		vc_lean_map( 'vcex_post_terms', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {
			add_filter( 'vc_autocomplete_vcex_post_terms_taxonomy_callback', 'vcex_suggest_taxonomies' );
			add_filter( 'vc_autocomplete_vcex_post_terms_taxonomy_render', 'vcex_render_taxonomies' );
			add_filter( 'vc_autocomplete_vcex_post_terms_exclude_terms_callback', 'vcex_suggest_terms' );
			add_filter( 'vc_autocomplete_vcex_post_terms_exclude_terms_render', 'vcex_render_terms' );
			add_filter( 'vc_autocomplete_vcex_post_terms_child_of_callback', 'vcex_suggest_terms' );
			add_filter( 'vc_autocomplete_vcex_post_terms_child_of_render', 'vcex_render_terms' );
		}

		if ( 'vc_edit_form' === $vc_action ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_post_terms', array( $this, 'edit_fields' ), 10 );
		}

	}

	/**
	 * Edit form fields.
	 */
	public function edit_fields( $atts ) {

		if ( empty( $atts['archive_link_target'] ) && ! empty( $atts['target'] ) ) {
			$atts['archive_link_target'] = $atts['target'];
			unset( $atts['target'] );
		}

		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Post Terms', 'total-theme-core' ),
			'description' => esc_html__( 'Display your post terms', 'total-theme-core' ),
			'base'        => 'vcex_post_terms',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--post-terms',
			'params'      => VCEX_Post_Terms_Shortcode::get_params(),
		);
	}

}