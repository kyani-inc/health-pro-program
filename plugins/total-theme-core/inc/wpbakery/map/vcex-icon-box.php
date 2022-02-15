<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Icon_Box_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_icon_box shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Icon_Box {

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
		vc_lean_map( 'vcex_icon_box', array( $this, 'map' ) );

		if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_icon_box', array( $this, 'edit_fields' ), 10 );
		}

	}

	/**
	 * Edit form fields.
	 */
	public function edit_fields( $atts ) {
		$atts = vcex_parse_icon_param( $atts );
		$atts = VCEX_Icon_Box_Shortcode::parse_deprecated_attributes( $atts );
		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'             => esc_html__( 'Icon Box (Blurb)', 'total-theme-core' ),
			'base'             => 'vcex_icon_box',
			'category'         => vcex_shortcodes_branding(),
			'icon'             => 'vcex_element-icon vcex_element-icon--icon-box',
			'description'      => esc_html__( 'Content box with icon', 'total-theme-core' ),
			'admin_enqueue_js' => vcex_wpbakery_asset_url( 'js/backend-editor/vcex-icon-box-view.min.js' ),
			'js_view'          => 'vcexIconBoxVcBackendView',
			'params'           => VCEX_Icon_Box_Shortcode::get_params(),
		);
	}

}