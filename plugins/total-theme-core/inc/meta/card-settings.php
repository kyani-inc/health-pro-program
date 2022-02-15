<?php
namespace TotalThemeCore\Meta;

defined( 'ABSPATH' ) || exit;

/**
 * Register meta options for theme cards.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
class Card_Settings {

	/**
	 * Our single Card_Settings instance.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Card_Settings.
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
		add_action( 'admin_init', array( $this, 'init' ) ); // lower priority so it's not at the very top
	}

	/**
	 * Initialize.
	 */
	public function init() {

		if ( ! $this->is_enabled() || ! class_exists( '\WPEX_Meta_Factory' ) ) {
			return;
		}

		new \WPEX_Meta_Factory( $this->card_metabox() );

	}

	/**
	 * Check if enabled.
	 */
	public function is_enabled() {
		return (bool) apply_filters( 'wpex_has_card_metabox', true );
	}

	/**
	 * Card metabox settings.
	 */
	public function card_metabox() {

		$post_types = apply_filters( 'wpex_card_metabox_post_types', array(
			'post'         => 'post',
			'portfolio'    => 'portfolio',
			'staff'        => 'staff',
			'testimonials' => 'testimonials',
		) );

		$fields = array(
			array(
				'name' => esc_html__( 'Link Target', 'total-theme-core' ),
				'id'   => 'wpex_card_link_target',
				'type' => 'select',
				'choices' => array(
					'' => esc_html__( 'Default', 'total-theme-core' ),
					'_blank' => esc_html__( 'New Tab', 'total-theme-core' ),
				),
			),
			array(
				'name' => esc_html__( 'Link URL', 'total-theme-core' ),
				'id'   => 'wpex_card_url',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'Thumbnail', 'total-theme-core' ),
				'id'   => 'wpex_card_thumbnail',
				'type' => 'upload',
				'return' => 'id',
				'desc' => esc_html__( 'Select a custom thumbnail to override the featured image.', 'total-theme-core' ),
			),
			array(
				'name' => esc_html__( 'Font Icon', 'total-theme-core' ),
				'id'   => 'wpex_card_icon',
				'type' => 'icon_select',
				'choices' => $this->choices_icons(),
				'desc' => esc_html__( 'Enter your custom Font Icon classname or click the button to select from the available theme icons.', 'total-theme-core' ),
			),
		);

		$fields = (array) apply_filters( 'wpex_card_metabox_fields', $fields );

		if ( ! empty( $fields ) ) {

			return array(
				'id'       => 'card',
				'title'    => esc_html__( 'Card Settings', 'total-theme-core' ),
				'screen'   => $post_types,
				'context'  => 'normal',
				'priority' => 'default',
				'fields'   => $fields
			);

		}

	}

	/**
	 * Icon choices.
	 */
	public function choices_icons() {

		$icons_list = array();

		if ( function_exists( 'wpex_ticons_list' ) ) {

			$ticons = wpex_ticons_list();

			if ( $ticons && is_array( $ticons ) ) {

				foreach( $ticons as $ticon ) {
					if ( 'none' == $ticon || '' == $ticon ) {
						$icons_list[] = esc_html__( 'Default', 'total' );
					} else {
						$icons_list['ticon ticon-' . trim( $ticon )] = $ticon;
					}
				}

			}

		}

		return (array) apply_filters( 'wpex_card_meta_choices_icons', $icons_list );

	}

}