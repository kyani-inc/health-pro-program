<?php
namespace TotalThemeCore;

defined( 'ABSPATH' ) || exit;

/**
 * Register custom widgets.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */
final class Register_Widgets {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Register_Widgets.
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
		add_action( 'widgets_init', array( $this, 'init' ) );
		add_action( 'admin_print_scripts-widgets.php', array( $this, 'widget_scripts' ) );
	}

	/**
	 * Register custom widgets.
	 */
	public function init() {

		$widgets_list = $this->get_widgets_list();

		if ( empty( $widgets_list ) || ! is_array( $widgets_list ) ) {
			return;
		}

		foreach ( $widgets_list as $custom_widget ) {
			$file = TTC_PLUGIN_DIR_PATH . 'inc/widgets/' . $custom_widget . '.php';
			if ( file_exists( $file ) ) {
				require_once $file;
			}
		}

	}

	/**
	 * Return custom widgets list.
	 */
	public function get_widgets_list() {

		$widgets_list = array(
			'about'              => 'widget-about',
			'advertisement'      => 'widget-advertisement',
			'newsletter'         => 'widget-newsletter',
			'simple-newsletter'  => 'widget-simple-newsletter',
			'info'               => 'widget-business-info',
			'social-fontawesome' => 'widget-social-profiles',
			'social',
			'simple-menu'        => 'widget-simple-menu',
			'modern-menu'        => 'widget-modern-menu',
			'facebook-page'      => 'widget-facebook',
			'google-map'         => 'widget-google-map',
			'video'              => 'widget-video',
			'posts-thumbnails'   => 'widget-recent-posts-thumb',
			'posts-grid'         => 'widget-recent-posts-thumb-grid',
			'posts-icons'        => 'widget-recent-posts-icons',
			'users-grid'         => 'widget-users-grid',
			'taxonomy-terms'     => 'widget-taxonomy-terms',
			'comments-avatar'    => 'widget-recent-comments-avatar',
		);

		if ( function_exists( 'templatera_init' ) ) {
			$widgets_list['templatera'] = 'widget-templatera';
		}

		if ( class_exists( 'bbPress' ) ) {
			$widgets_list['bbpress-forum-info'] = 'widget-bbPress-forum-info';
			$widgets_list['bbpress-topic-info'] = 'widget-bbPress-topic-info';
		}

		return apply_filters( 'wpex_custom_widgets', $widgets_list );

	}

	/**
	 * Custom Widgets scripts
	 *
	 * @since  1.0
	 * @access public
	 */
	public function widget_scripts() {

		wp_enqueue_style(
			'wpex-custom-widgets-admin',
			TTC_PLUGIN_DIR_URL . 'assets/css/custom-widgets-admin.css',
			array(),
			'1.0'
		);

		wp_enqueue_script(
			'wpex-custom-widgets-admin',
			TTC_PLUGIN_DIR_URL . 'assets/js/custom-widgets-admin.min.js',
			array( 'jquery' ),
			'1.0',
			true
		);

		wp_localize_script( 'wpex-custom-widgets-admin', 'wpexCustomWidgets', array(
			'confirm' => esc_html__( 'Do you really want to delete this item?', 'total-theme-core' ),
		) );

	}

}