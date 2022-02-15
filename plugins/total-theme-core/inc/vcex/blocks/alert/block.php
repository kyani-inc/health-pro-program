<?php
namespace TotalThemeCore\Vcex\Blocks\Alert;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Alert Block.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Block {

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
			static::$instance = new Block;
			static::$instance->actions();
		}

		return static::$instance;
	}

	/**
	 * Hook into WP actions.
	 */
	public function actions() {
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'block_scripts' ) );
	}

	/**
	 * Register a new block.
	 */
	public function register_block() {

		if ( ! function_exists( 'register_block_type' ) || ! shortcode_exists( 'vcex_alert' ) ) {
			// Block editor is not available.
			return;
		}

		register_block_type( 'vcex/alert', array(
			'editor_script'   => array( 'vcex-alert-block' ),
			'render_callback' => __CLASS__ . '::callback',
			'attributes'      => $this->get_block_attributes(),
		) );

	}

	/**
	 * Register the block scripts.
	 */
	public function block_scripts() {

		if ( ! shortcode_exists( 'vcex_alert' ) ) {
			return;
		}

		wp_register_script(
			'vcex-alert-block',
			vcex_get_block_editor_script_src( 'alert' ),
			array( 'wp-blocks', 'wp-element', 'wp-editor' ),
			TTC_VERSION,
		);

	}

	/**
	 * Return block attributes.
	 */
	public function get_block_attributes() {
		return array(
			'type' => array(
				'type' => 'string',
				'default' => '',
			),
			'heading' => array(
				'type' => 'string',
				'default' => '',
			),
			'content' => array(
				'type' => 'string',
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce laoreet vestibulum elit eget fringilla.',
			),
		);
	}

	/**
	 * Callback for block display.
	 */
	public static function callback( $atts, $content = '' ) {
		if ( empty( $content ) && isset( $atts['content'] ) ) {
			$content = $atts['content'];
		}
		return vcex_do_shortcode_function( 'vcex_alert', $atts, $content );
	}

}