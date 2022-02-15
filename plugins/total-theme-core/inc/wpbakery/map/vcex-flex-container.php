<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Flex_Container_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_flex_container shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Flex_Container {

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
		vc_lean_map( 'vcex_flex_container', array( $this, 'map' ) );
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name' => esc_html__( 'Flexible Container', 'total-theme-core' ),
			'description'  => esc_html__( 'Place certain elements in a flexible container.', 'total-theme-core' ),
			'base' => 'vcex_flex_container',
			'category' => vcex_shortcodes_branding(),
			'icon' => 'vcex_element-icon vcex_element-icon--flex-container',
			'params' => VCEX_Flex_Container_Shortcode::get_params(),
			'allowed_container_element' => false,
			'is_container' => true,
			'content_element' => true,
			'js_view' => 'VcColumnView',
			'as_parent' => array( 'only' => $this->allowed_child_elements() ),
		);
	}

	/**
	 * Return list of allowed child elements for the flex container.
	 */
	private function allowed_child_elements() {
		$allowed_elements = 'vcex_heading,vcex_icon_box,vcex_milestone,vcex_bullets,vcex_button,vcex_list_item,vcex_teaser,vc_column_text,vcex_image,vcex_pricing,vcex_custom_field,vcex_navbar,vcex_post_terms,vcex_post_meta,vcex_page_title,vcex_image_banner,vcex_social_links,vcex_newsletter_form,vcex_icon';
		return (string) apply_filters( 'vcex_flex_container_allowed_elements', $allowed_elements );
	}

}