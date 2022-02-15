<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Post_Content_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_post_content shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Post_Content {

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
		vc_lean_map( 'vcex_post_content', array( $this, 'map' ) );

		if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_post_content', array( $this, 'edit_form_fields' ) );
		}

	}

	/**
	 * Update fields on edit.
	 */
	public function edit_form_fields( $atts ) {

		if ( empty( $atts['blocks'] ) ) {

			$blocks = array();

			$settings_to_check = array(
				'post_series',
				'the_content',
				'social_share',
				'author_bio',
				'related',
				'comments',
			);

			foreach( $settings_to_check as $setting ) {

				if ( 'the_content' == $setting ) {
					$blocks[] = $setting;
				} elseif ( isset( $atts[$setting] ) && 'true' == $atts[$setting] ) {
					$blocks[] = $setting;
				}

			}

			if ( $blocks ) {
				$atts['blocks'] = implode( ',', $blocks );
			}

		}

		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Post Content', 'total-theme-core' ),
			'description' => esc_html__( 'Display your post content', 'total-theme-core' ),
			'base'        => 'vcex_post_content',
			'icon'        => 'vcex_element-icon vcex_element-icon--post-content',
			'category'    => vcex_shortcodes_branding(),
			'params'      => VCEX_Post_Content_Shortcode::get_params(),
		);
	}

}