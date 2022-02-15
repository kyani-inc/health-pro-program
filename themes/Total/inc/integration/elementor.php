<?php
namespace TotalTheme\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * Elementor Configuration Class
 *
 * @package TotalTheme
 * @subpackage Integration
 * @version 5.2
 */
final class Elementor {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Elementor.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}

		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function __construct() {
		add_action( 'elementor/theme/register_locations', array( $this, 'register_locations' ) );
	}

	/**
	 * Registers Elementor locations.
	 *
	 * @access public
	 * @since 4.9.5
	 */
	public function register_locations( $elementor_theme_manager ) {

		/**
		 * Filters whether the theme should register all core elementor locations via
		 * $elementor_theme_manager->register_all_core_location().
		 *
		 * @param bool $check
		 */
		$register_core_locations = apply_filters( 'total_register_elementor_locations', true );

		if ( $register_core_locations ) {
			$elementor_theme_manager->register_all_core_location();
		}

		$elementor_theme_manager->register_location( 'togglebar', array(
			'label'           => esc_html__( 'Togglebar', 'total' ),
			'multiple'        => true,
			'edit_in_content' => false,
		) );

		$elementor_theme_manager->register_location( 'topbar', array(
			'label'           => esc_html__( 'Top Bar', 'total' ),
			'multiple'        => true,
			'edit_in_content' => false,
		) );

		$elementor_theme_manager->register_location( 'page_header', array(
			'label'           => esc_html__( 'Page Header', 'total' ),
			'multiple'        => true,
			'edit_in_content' => false,
		) );

		$elementor_theme_manager->register_location( 'footer_callout', array(
			'label'           => esc_html__( 'Footer Callout', 'total' ),
			'multiple'        => true,
			'edit_in_content' => false,
		) );

		$elementor_theme_manager->register_location( 'footer_bottom', array(
			'label'           => esc_html__( 'Footer Bottom', 'total' ),
			'multiple'        => true,
			'edit_in_content' => false,
		) );

	}

}