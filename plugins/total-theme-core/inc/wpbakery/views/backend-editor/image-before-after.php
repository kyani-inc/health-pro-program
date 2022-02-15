<?php
namespace TotalThemeCore\WPBakery\Views\Backend_Editor;

defined( 'ABSPATH' ) || exit;

/**
 * Custom view for displaying images in the WPBakery backend editor.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */
final class Image_Before_After {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Image_Before_After.
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
		add_action( 'wp_ajax_vcex_wpbakery_backend_view_image_before_after', array( $this, 'generate' ) );
	}

	/**
	 * AJAX request.
	 */
	public function generate() {
		if ( ! function_exists( 'vc_user_access' ) ) {
			die();
		}

		vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie();

		$return  = array();
		$post_id = (int) vc_post_param( 'post_id' );
		$source  = vc_post_param( 'source' );

		switch ( $source ) {
			case 'featured':
				$before_image = get_post_thumbnail_id( $post_id );
				$after_image = wpex_get_secondary_thumbnail( $post_id );
				break;
			case 'custom_field':
				$before_image = get_post_meta( $post_id, vc_post_param( 'beforeImageCf' ), true );
				$after_image = get_post_meta( $post_id, vc_post_param( 'afterImageCf' ), true );
				break;
			case 'media_library';
			default:
				$before_image = vc_post_param( 'beforeImage' );
				$after_image = vc_post_param( 'afterImage' );
				break;
		}

		if ( $before_image ) {
			$before_image = esc_url( wp_get_attachment_image_url( $before_image, 'thumbnail' ) );
			if ( $before_image ) {
				$return['beforeImage'] = $before_image;
			}
		}

		if ( $after_image ) {
			$after_image = esc_url( wp_get_attachment_image_url( $after_image, 'thumbnail' ) );
			if ( $after_image ) {
				$return['afterImage'] = $after_image;
			}
		}

		echo json_encode( $return );

		die();

	}

}