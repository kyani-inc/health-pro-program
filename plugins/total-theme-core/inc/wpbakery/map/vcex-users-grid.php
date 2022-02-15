<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Users_Grid_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_users_grid shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Users_Grid {

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
		vc_lean_map( 'vcex_users_grid', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {
			add_filter( 'vc_autocomplete_vcex_users_grid_role__in_callback', 'vcex_suggest_user_roles' );
			add_filter( 'vc_autocomplete_vcex_users_grid_role__in_render', 'vcex_render_user_roles' );
		}

		if ( 'vc_edit_form' === $vc_action ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_users_grid', array( $this, 'edit_fields' ) );
		}

	}

	/**
	 * Edit form fields.
	 */
	public function edit_fields( $atts ) {

		if ( isset( $atts['link_to_author_page'] ) ) {
			if ( 'false' == $atts['link_to_author_page'] ) {
				$atts['onclick'] = 'disable';
				unset( $atts['link_to_author_page'] );
			}
		}

		return $atts;

	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Users Grid', 'total-theme-core' ),
			'description' => esc_html__( 'Displays a grid of users', 'total-theme-core' ),
			'base'        => 'vcex_users_grid',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--users-grid',
			'params'      => VCEX_Users_Grid_Shortcode::get_params(),
		);
	}

}