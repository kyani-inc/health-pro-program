<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Contact_Form_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_contact_form shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Contact_Form {

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
		}

		return static::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'vc_after_mapping', array( $this, 'vc_after_mapping' ) );
	}

	/**
	 * Run functions on vc_after_mapping hook.
	 */
	public function vc_after_mapping() {
		vc_lean_map( 'vcex_contact_form', array( $this, 'map' ) );
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Contact Form', 'total-theme-core' ),
			'description' => esc_html__( 'Simple contact form.', 'total-theme-core' ),
			'base'        => 'vcex_contact_form',
			'icon'        => 'vcex_element-icon vcex_element-icon--contact',
			'category'    => vcex_shortcodes_branding(),
			'params'      => VCEX_Contact_Form_Shortcode::get_params(),
		);
	}

}