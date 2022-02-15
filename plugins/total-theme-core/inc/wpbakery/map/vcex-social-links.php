<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Social_Links_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_social_links shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Social_Links {

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
		vc_lean_map( 'vcex_social_links', array( $this, 'map' ) );

		if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_social_links', array( $this, 'edit_form_fields' ) );
		}
	}

	/**
	 * Parse attributes on edit.
	 */
	public function edit_form_fields( $atts ) {

		$social_profiles = vcex_social_links_profiles();

		if ( ! empty( $social_profiles ) )  {

			// Loop through old options and move to new ones + delete old settings?
			if ( empty( $atts['social_links'] ) ) {
				$social_links = array();
				foreach ( $social_profiles  as $key => $val ) {
					if ( ! empty( $atts[$key] ) ) {
						$social_links[] = array(
							'site' => $key,
							'link' => $atts[$key],
						);
					}
					unset( $atts[$key] );
				}
				if ( $social_links ) {
					$atts['social_links'] = urlencode( json_encode( $social_links ) );
				}
			}

		}

		// Return attributes.
		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Social Links', 'total-theme-core' ),
			'description' => esc_html__( 'Display social links using icon fonts', 'total-theme-core' ),
			'base'        => 'vcex_social_links',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--social-links',
			'params'      => VCEX_Social_Links_Shortcode::get_params(),
		);
	}

}