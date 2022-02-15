<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Bullets_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_bullets shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Bullets {

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
		vc_lean_map( 'vcex_bullets', array( $this, 'map' ) );

		if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_bullets', array( $this, 'edit_fields' ), 10 );
		}

	}

	/**
	 * Edit form fields.
	 */
	public function edit_fields( $atts ) {
		$atts = vcex_parse_icon_param( $atts );
		$atts = VCEX_Bullets_Shortcode::parse_deprecated_attributes( $atts );
		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'List (bullets)', 'total-theme-core' ),
			'description' => esc_html__( 'Bulleted list with icons', 'total-theme-core' ),
			'base'        => 'vcex_bullets',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--bullets',
			'params'      => VCEX_Bullets_Shortcode::get_params(),
		);
	}

}