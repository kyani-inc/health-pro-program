<?php
namespace TotalThemeCore\WPBakery\Views\Backend_Editor;

defined( 'ABSPATH' ) || exit;

/**
 * Custom view for displaying shortcode image gallery in the WPBakery backend editor.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */
final class Image_Gallery {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Image_Gallery.
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
		add_action( 'wp_ajax_vcex_wpbakery_backend_view_image_gallery', array( $this, 'generate' ) );
	}

	/**
	 * AJAX callback.
	 */
	public function generate() {

		if ( ! function_exists( 'vc_user_access' ) ) {
			die();
		}

		vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie();

		$post_id = (int) vc_post_param( 'post_id' );
		$image_ids = vc_post_param( 'imageIds' );
		$post_gallery = vc_post_param( 'postGallery' );
		$custom_field = vc_post_param( 'customField' );
		$return = array();

		if ( $image_ids ) {
			$images = $image_ids;
		}

		if ( $custom_field && $post_id ) {
			$custom_images = get_post_meta( $post_id, $custom_field, true );
			if ( $custom_images ) {
				$images = $custom_images;
			}
		}

		if ( 'true' === $post_gallery && $post_id && function_exists( 'wpex_get_gallery_ids' ) ) {
			$gallery_images = wpex_get_gallery_ids( $post_id );
			if ( $gallery_images ) {
				$images = $gallery_images;
			}
		}

		if ( $images ) {

			if ( is_string( $images ) ) {
				$images = explode( ',', $images );
			}

			if ( $images && is_array( $images ) ) {
				$i = 0;
				foreach( $images as $image_id ) {
					if ( $i++ > 100 ) {
						break; // don't show too many images.
					}
					$return[] = esc_url( wp_get_attachment_image_url( $image_id, 'thumbnail' ) );
				}
			}

		}

		echo json_encode( $return );

		die();

	}

}