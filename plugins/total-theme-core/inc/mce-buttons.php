<?php
namespace TotalThemeCore;

defined( 'ABSPATH' ) || exit;

final class Mce_Buttons {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Mce_Buttons.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}

		return static::$instance;
	}

	/**
	 * Get things started.
	 */
	public function __construct() {
		add_action( 'admin_head', array( $this, 'filter_buttons' ) );
		add_action( 'admin_footer', array( $this, 'json' ) );
	}

	/**
	 * Register admin filters for adding the editor shortcodes button.
	 */
	public function filter_buttons() {

		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( user_can_richedit() ) {
			add_filter( 'mce_external_plugins', array( $this, 'register_script' ) );
			add_filter( 'mce_buttons', array( $this, 'register_button' ) );
		}

	}

	/**
	 * Register the new editor shortcodes button script.
	 */
	public function register_script( $plugin_array ) {
		$plugin_array['wpex_shortcodes_mce_button'] = TTC_PLUGIN_DIR_URL . 'assets/js/shortcodes-tinymce.min.js';
		return $plugin_array;
	}

	/**
	 * Register the editor shortcodes button so it's avaible in the tinymce.
	 */
	public function register_button( $buttons ) {
		array_push( $buttons, 'wpex_shortcodes_mce_button' );
		return $buttons;
	}

	/**
	 * JSON used to add items to the shortcodes editor button.
	 */
	public function json() {

		$data = array();

		$data['btnLabel']   = esc_html__( 'Shortcodes', 'total-theme-core' );
		$data['shortcodes'] = array(
			'br' => array(
				'text' => esc_html__( 'Line Break', 'total-theme-core' ),
				'insert' => '[br]',
			),
			'ticon' => array(
				'text' => esc_html__( 'Icon', 'total-theme-core' ),
				'insert' => '[ticon icon="bolt" color="000" size="16px" margin_right="" margin_left="" margin_top="" margin_bottom="" link=""]',
			),
			'current_year' => array(
				'text' => esc_html__( 'Current Year', 'total-theme-core' ),
				'insert' => '[current_year]',
			),
			'searchform' => array(
				'text' => esc_html__( 'WP Searchform', 'total-theme-core' ),
				'insert' => '[searchform]',
			),
		);

		$add_vc_shortcodes = get_theme_mod( 'extend_visual_composer', true );

		/**
		 * Filters whether the theme should add shortcodes to the tinymce.
		 *
		 * @param bool $add_vc_shortcodes
		 */
		$check = apply_filters( 'vcex_wpex_shortcodes_tinymce', $add_vc_shortcodes );

		if ( $check ) {

			$data['shortcodes']['vcex_button'] = array(
				'text' => esc_html__( 'Button', 'total-theme-core' ),
				'insert' => '[vcex_button url="#" title="Visit Site" style="flat" align="left" color="black" size="small" target="self" rel="none"]Button Text[/vcex_button]',
			);

			$data['shortcodes']['vcex_divider'] = array(
				'text' => esc_html__( 'Divider', 'total-theme-core' ),
				'insert' => '[vcex_divider color="#dddddd" width="100%" height="1px" margin_top="20" margin_bottom="20"]',
			);

			$data['shortcodes']['vcex_divider_dots'] = array(
				'text' => esc_html__( 'Divider Dots', 'total-theme-core' ),
				'insert' => '[vcex_divider_dots color="#dd3333" margin_top="10" margin_bottom="10"]',
			);

			$data['shortcodes']['vcex_spacing'] = array(
				'text' => esc_html__( 'Spacing', 'total-theme-core' ),
				'insert' => '[vcex_spacing size="30px"]',
			);

		}

		/**
		 * Filters the custom shortcodes tinymce array.
		 *
		 * @param array $data
		 */
		$data = apply_filters( 'wpex_shortcodes_tinymce_json', $data );

		?>

			<!-- Total TinyMCE Shortcodes -->
			<script>var wpexTinymce = <?php echo wp_json_encode( $data ); ?> ;</script>
			<!-- Total TinyMCE Shortcodes -->

		<?php

	}

}