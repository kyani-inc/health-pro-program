<?php
namespace TotalTheme\Integration\WooCommerce\Customize;

defined( 'ABSPATH' ) || exit;

/**
 * Vanilla WooCommerce Customizer Settings.
 *
 * @package TotalTheme
 * @subpackage Integration/WooCommerce
 * @version 5.2
 */
final class Vanilla_Settings {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Vanilla_Settings.
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
		add_action( 'customize_register' , array( $this , 'customizer_settings' ) );
	}

	/**
	 * Customizer Settings.
	 */
	public function customizer_settings( $wp_customize ) {

		$choices_layouts = wpex_get_post_layouts();

		// Add Theme Section to WooCommerce tab.
		$wp_customize->add_section(
			'wpex_woocommerce_vanilla',
			array(
				'title' => __( 'Theme Settings', 'total' ),
				'theme_supports' => array( 'woocommerce' ),
				'panel' => 'woocommerce',
			)
		);

		// Shop Layout.
		$wp_customize->add_setting( 'woo_shop_layout' , array(
			'default'           => 'full-width',
			'transport'         => 'refresh',
			'sanitize_callback' => 'wpex_sanitize_customizer_select',
		) );

		$wp_customize->add_control( 'woo_shop_layout', array(
			'label'    => esc_html__( 'Shop Layout', 'total' ),
			'section'  => 'wpex_woocommerce_vanilla',
			'settings' => 'woo_shop_layout',
			'type'     => 'select',
			'choices'  => $choices_layouts,
		) );

		// Shop Layout.
		$wp_customize->add_setting( 'woo_product_layout' , array(
			'default'           => 'full-width',
			'transport'         => 'refresh',
			'sanitize_callback' => 'wpex_sanitize_customizer_select',
		) );

		$wp_customize->add_control( 'woo_product_layout', array(
			'label'    => esc_html__( 'Single Product Layout', 'total' ),
			'section'  => 'wpex_woocommerce_vanilla',
			'settings' => 'woo_product_layout',
			'type'     => 'select',
			'choices'  => $choices_layouts,
		) );

		// Next/Previous.
		$wp_customize->add_setting( 'woo_next_prev' , array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( 'woo_next_prev', array(
			'label'    => esc_html__( 'Display Next & Previous Links?', 'total' ),
			'section'  => 'wpex_woocommerce_vanilla',
			'settings' => 'woo_next_prev',
			'type'     => 'checkbox',
		) );

	}

}