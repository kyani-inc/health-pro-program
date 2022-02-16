<?php
namespace TotalTheme\Widgets;

defined( 'ABSPATH' ) || exit;

/**
 * The Wiget Block Editor Tweaks.
 *
 * @package TotalTheme
 * @subpackage Widgets
 * @version 5.3
 */
final class Block_Editor {

	/**
	 * Class instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of this class.
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

		if ( ! get_theme_mod( 'widget_block_editor_enable', true ) ) {
			self::disable_block_editor();
		}

	}

	/**
	 * Disables the block editor.
	 */
	public static function disable_block_editor() {

		// Disables the block editor from managing widgets in the Gutenberg plugin.
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );

		// Disables the block editor from managing widgets.
		add_filter( 'use_widgets_block_editor', '__return_false' );

	}

}