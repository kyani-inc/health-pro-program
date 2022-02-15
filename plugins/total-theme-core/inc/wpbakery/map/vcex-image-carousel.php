<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Image_Carousel as Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_image_carousel shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Image_Carousel {

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
		vc_lean_map( 'vcex_image_carousel', array( $this, 'map' ) );

		if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_image_carousel', 'vcex_parse_deprecated_grid_entry_content_css' );
			add_filter( 'vc_edit_form_fields_attributes_vcex_image_carousel', array( $this, 'edit_form_fields' ) );
		}
	}

	/**
	 * Update fields on edit.
	 */
	public function edit_form_fields( $atts ) {
		$atts = Shortcode::parse_deprecated_attributes( $atts );
		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'             => esc_html__( 'Image Carousel', 'total-theme-core' ),
			'description'      => esc_html__( 'Image based jQuery carousel', 'total-theme-core' ),
			'base'             => 'vcex_image_carousel',
			'admin_enqueue_js' => vcex_wpbakery_asset_url( 'js/backend-editor/vcex-image-gallery-view.min.js' ),
			'js_view'          => 'vcexBackendViewImageGallery',
			'category'         => vcex_shortcodes_branding(),
			'icon'             => 'vcex_element-icon vcex_element-icon--image-carousel',
			'params'           => Shortcode::get_params(),
		);
	}

}