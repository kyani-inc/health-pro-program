<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Pricing_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_pricing shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Pricing {

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
		vc_lean_map( 'vcex_pricing', array( $this, 'map' ) );

		if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_pricing', array( $this, 'edit_form_fields' ) );
		}
	}

	/**
	 * Alter module fields on edit.
	 */
	public function edit_form_fields( $atts ) {

		if ( ! empty( $atts['button_url'] )
			&& false === strpos( $atts['button_url'], 'url:' )
			&& '|' != $atts['button_url']
			&& '||' != $atts['button_url']
			&& '|||' != $atts['button_url']
		) {
			$url = 'url:' . rawurlencode( $atts['button_url'] ) . '|';
			$atts['button_url'] = $url;
		}

		$atts = vcex_parse_icon_param( $atts, 'button_icon_left' );
		$atts = vcex_parse_icon_param( $atts, 'button_icon_right' );

		return $atts;

	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Pricing Table', 'total-theme-core' ),
			'description' => esc_html__( 'Insert a pricing column', 'total-theme-core' ),
			'base'        => 'vcex_pricing',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--pricing-table',
			'params'      => VCEX_Pricing_Shortcode::get_params(),
		);
	}

}