<?php
namespace TotalThemeCore\WPBakery\Views\Backend_Editor;

defined( 'ABSPATH' ) || exit;

/**
 * Custom view for displaying images in the WPBakery backend editor.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */
final class Image {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Image.
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
		add_action( 'wp_ajax_vcex_wpbakery_backend_view_image', array( $this, 'generate' ) );
	}

	/**
	 * AJAX callback.
	 */
	public function generate() {

		if ( ! function_exists( 'vc_user_access' ) ) {
			die();
		}

		vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie();

		$content = vc_post_param( 'content' );
		$post_id = (int) vc_post_param( 'post_id' );
		$source  = vc_post_param( 'image_source', 'media_library' );
		$img     = '';

		switch ( $source ) {

			case 'media_library':
			case 'featured':

				if ( 'featured' === $source ) {
					if ( $post_id && has_post_thumbnail( $post_id ) && 'templatera' != get_post_type( $post_id ) ) {
						$img_id = get_post_thumbnail_id( $post_id );
					} else {
						$img_id = 0;
					}
				} else {
					$img_id = preg_replace( '/[^\d]/', '', intval( $content ) );
				}

				if ( $img_id ) {
					$img = wp_get_attachment_image_url( $img_id, 'thumbnail' );
				}

				break;

			case 'author_avatar';

				$img = get_avatar_url( get_post_field( 'post_author', $post_id ), array( 'size' => get_option( 'thumbnail_size_w' ) ) );

				break;

			case 'user_avatar';

				$img = get_avatar_url( wp_get_current_user(), array( 'size' => get_option( 'thumbnail_size_w' ) ) );

				break;

			case 'external';

				if ( $content ) {
					$img = $content;
				}

				break;

			case 'custom_field';

				if ( $content ) {
					$img = get_post_meta( $post_id, $content, true );
					if ( is_numeric( $img ) ) {
						$img = wp_get_attachment_image_url( $img, 'thumbnail' );
					}
				}

				break;

		}

		if ( $img ) {
			echo esc_url( $img );
		}

		die();
	}

}