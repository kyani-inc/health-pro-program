<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Toggle_Group_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_toggle_group shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */
final class Vcex_Toggle_Group {

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
		vc_lean_map( 'vcex_toggle_group', array( $this, 'map' ) );
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name' => esc_html__( 'Toggle (FAQ) Group', 'total-theme-core' ),
			'description' => esc_html__( 'Create an accordion using toggle elements.', 'total-theme-core' ),
			'base' => 'vcex_toggle_group',
			'category' => vcex_shortcodes_branding(),
			'icon' => 'vcex_element-icon vcex_element-icon--toggle',
			'params' => VCEX_Toggle_Group_Shortcode::get_params(),
			'allowed_container_element' => false,
			'is_container' => true,
			'content_element' => true,
			'js_view' => 'VcColumnView',
			'as_parent' => array( 'only' => 'vcex_toggle' ),
		);
	}

}