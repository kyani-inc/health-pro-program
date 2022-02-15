<?php
namespace TotalTheme\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * Templatera Integration.
 *
 * @package TotalTheme
 * @subpackage Integration
 * @version 5.3
 */
final class Templatera {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Templatera.
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

		// Remove admin notices.
		add_action( 'init', __CLASS__ . '::remove_notices' ); // @todo can we switch to admin_init?

		// Admin dashboard columns.
		add_filter( 'manage_templatera_posts_columns', __CLASS__ . '::define_columns' );
		add_action( 'manage_templatera_posts_custom_column', __CLASS__ . '::columns_display', 10, 2 );

		// Re-register shortcode to fix issues on archives - @todo remove when no longer needed.
		add_action( 'wp_loaded', __CLASS__ . '::register_shortcode', 50 );
	}

	/**
	 * Remove notices.
	 */
	public static function remove_notices() {
		remove_action( 'admin_notices', 'templatera_notice' );
	}

	/**
	 * Define new admin dashboard columns.
	 */
	public static function define_columns( $columns ) {
		$columns['wpex_templatera_shortcode'] = esc_html__( 'Shortcode', 'total' );
		$columns['wpex_templatera_id'] = esc_html__( 'ID', 'total' );
    	return $columns;
	}

	/**
	 * Display new admin dashboard columns.
	 */
	public static function columns_display( $column, $post_id ) {
		switch ( $column ) {
			case 'wpex_templatera_shortcode' :
				echo '<input type="text" onClick="this.select();" value=\'[templatera id="' . esc_attr( absint( $post_id ) ) . '"]\' readonly>';
			break;
			case 'wpex_templatera_id' :
				echo esc_html( absint( $post_id ) );
			break;
		}
	}

	/**
	 * Register templatera shortcode to fix issues on archives with default registration.
	 */
	public static function register_shortcode() {
		add_shortcode( 'templatera', __CLASS__ . '::add_shortcode' );
	}

	/**
	 * New templatera shortcode output.
	 */
	public static function add_shortcode( $atts, $content = '' ) {

		if ( ! class_exists( '\WPBMap' ) || ! function_exists( 'visual_composer' ) ) {
			return;
		}

		$id = '';
		$el_class = '';
		$output = '';
		extract( shortcode_atts( array(
			'el_class' => '',
			'id' => '',
		), $atts ) );
		if ( empty( $id ) ) {
			return $output;
		}
		$my_query = new \WP_Query( array(
			'post_type' => 'templatera',
			'p' => (int) $id,
		) );
		\WPBMap::addAllMappedShortcodes();
		global $post;
		$backup = $post;
		while ( $my_query->have_posts() ) {
			$my_query->the_post();
			$post_id = get_the_ID();
			if ( $post_id === (int) $id ) {
				$output .= '<div class="templatera_shortcode' . ( $el_class ? ' ' . $el_class : '' ) . '">';
				ob_start();
				visual_composer()->addPageCustomCss( $post_id );
				visual_composer()->addShortcodesCustomCss( $post_id );
				$content = get_the_content();
				// @codingStandardsIgnoreLine
				print $content;
				$output .= ob_get_clean();
				$output .= '</div>';
				$output = do_shortcode( $output );
			}
			wp_reset_postdata();
		}
		// @codingStandardsIgnoreLine
		$post = $backup;
		return $output;

	}

}