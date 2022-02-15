<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Image_Banner_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_image_banner shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Image_Banner {

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
		vc_lean_map( 'vcex_image_banner', array( $this, 'map' ) );

		if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_image_banner', array( $this, 'edit_form_fields' ) );
		}
	}

	/**
	 * Update deprecated options.
	 */
	public function edit_form_fields( $atts ) {

		if ( ! empty( $atts['border_radius'] ) ) {
			$atts['border_radius'] = vcex_sanitize_border_radius( $atts['border_radius'] );
		}

		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'             => esc_html__( 'Image Banner', 'total-theme-core' ),
			'description'      => esc_html__( 'Image Banner with overlay text and animation', 'total-theme-core' ),
			'base'             => 'vcex_image_banner',
			'icon'             => 'vcex_element-icon vcex_element-icon--image-banner',
			'category'         => vcex_shortcodes_branding(),
			'params'           => VCEX_Image_Banner_Shortcode::get_params(),
			'admin_enqueue_js' => vcex_wpbakery_asset_url( 'js/backend-editor/vcex-image-view.min.js' ),
			'js_view'          => 'vcexBackendViewImage',
		);
	}

}