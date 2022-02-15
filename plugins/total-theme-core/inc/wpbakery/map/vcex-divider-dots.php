<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Divider_Dots_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_divider_dots shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Divider_Dots {

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
		vc_lean_map( 'vcex_divider_dots', array( $this, 'map' ) );

		if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_divider_dots', array( $this, 'edit_form_fields' ) );
		}

	}

	/**
	 * Edit form fields.
	 */
	public function edit_form_fields( $atts ) {
		$atts = VCEX_Divider_Dots_Shortcode::parse_deprecated_attributes( $atts );
		return $atts;

	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Divider Dots', 'total-theme-core' ),
			'description' => esc_html__( 'Dot Separator', 'total-theme-core' ),
			'base'        => 'vcex_divider_dots',
			'icon'        => 'vcex_element-icon vcex_element-icon--divider-dots',
			'category'    => vcex_shortcodes_branding(),
			'params'      => VCEX_Divider_Dots_Shortcode::get_params(),
		);
	}

}