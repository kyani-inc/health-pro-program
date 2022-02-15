<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Staff_Social;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the staff_social shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Staff_Social {

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
		vc_lean_map( 'staff_social', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {
			add_filter( 'vc_autocomplete_staff_social_post_id_callback', 'vcex_suggest_staff_members' );
			add_filter( 'vc_autocomplete_staff_social_post_id_render', 'vcex_render_staff_members' );
		}
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Staff Social Links', 'total-theme-core' ),
			'description' => esc_html__( 'Single staff social links', 'total-theme-core' ),
			'base'        => 'staff_social',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--staff-social',
			'params'      => array(
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Staff Member ID', 'total-theme-core' ),
					'param_name' => 'post_id',
					'admin_label' => true,
					'param_holder_class' => 'vc_not-for-custom',
					'description' => esc_html__( 'Select a staff member to display their social links. By default it will diplay the current staff member links.', 'total-theme-core'),
					'settings' => array(
						'multiple' => false,
						'min_length' => 1,
						'groups' => false,
						'unique_values' => true,
						'display_inline' => true,
						'delay' => 0,
						'auto_focus' => true,
					),
				),
				array(
					'type' => 'vcex_social_button_styles',
					'heading' => esc_html__( 'Style', 'total-theme-core' ),
					'param_name' => 'style',
					'std' => get_theme_mod( 'staff_social_default_style', 'minimal-round' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Link Target', 'total-theme-core' ),
					'param_name' => 'link_target',
					'value' => array(
						esc_html__( 'Blank', 'total-theme-core' ) => 'blank',
						esc_html__( 'Self', 'total-theme-core') => 'self',
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Icon Size', 'total-theme-core' ),
					'param_name' => 'font_size',
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Icon Margin', 'total-theme-core' ),
					'param_name' => 'icon_margin',
				),
				vcex_vc_map_add_css_animation(),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Duration', 'total'),
					'param_name' => 'animation_duration',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total'),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Delay', 'total'),
					'param_name' => 'animation_delay',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total'),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
			),
		);

	}

}