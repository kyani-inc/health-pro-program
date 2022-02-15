<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Image_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_image shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Image {

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
		vc_lean_map( 'vcex_image', array( $this, 'map' ) );

		if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_image', array( $this, 'edit_fields' ), 10 );
		}

	}

	/**
	 * Edit form fields.
	 */
	public function edit_fields( $atts ) {
		$atts = VCEX_Image_Shortcode::parse_deprecated_attributes( $atts );
		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'             => esc_html__( 'Image', 'total-theme-core' ),
			'description'      => esc_html__( 'Single Image', 'total-theme-core' ),
			'base'             => 'vcex_image',
			'category'         => vcex_shortcodes_branding(),
			'icon'             => 'vcex_element-icon vcex_element-icon--image',
			'admin_enqueue_js' => vcex_wpbakery_asset_url( 'js/backend-editor/vcex-image-view.min.js' ),
			'js_view'          => 'vcexBackendViewImage',
			'params'           => VCEX_Image_Shortcode::get_params(),
		);
	}

}