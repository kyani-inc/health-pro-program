<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

/**
 * Display format icons over featured images.
 *
 * @package TotalTheme
 * @subpackage Classes
 * @version 5.3.1
 *
 * @todo change to use wpex_hook_entry_media_after for better consistency
 */
class Thumbnail_Format_Icons {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Thumbnail_Format_Icons.
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
		add_filter( 'wpex_get_entry_media_after', array( $this, 'icon_html' ) );
	}

	/**
	 * Check if the thumbnail format icon html is enabled.
	 *
	 * @since 4.5.4
	 */
	public function enabled() {
		$check = ( 'post' === get_post_type() );
		$check = apply_filters( 'wpex_thumbnails_have_format_icons', $check ); // @todo deprecate

		/**
		 * Filters whether a the thumbnail format icon should display or not.
		 *
		 * @param bool $check
		 */
		$check = (bool) apply_filters( 'wpex_has_post_thumbnail_format_icon', $check );

		return $check;
	}

	/**
	 * Return correct icon class.
	 *
	 * @since 4.5.4
	 */
	public function icon_class( $format = '' ) {

		switch ( $format ) {
			case 'video':
				$icon_class = 'ticon ticon-play';
				break;
			case 'audio':
				$icon_class = 'ticon ticon-music';
				break;
			case 'gallery':
				$icon_class = 'ticon ticon-file-photo-o';
				break;
			case 'quote':
				$icon_class = 'ticon ticon-quote-left';
				break;
			default:
				$icon_class = 'ticon ticon-file-text-o';
		}

		/**
		 * Filters the post format icon classname
		 *
		 * @param string $icon_class
		 * @todo rename filter?
		 */
		$icon_class = apply_filters( 'wpex_get_thumbnail_format_icon_class', $icon_class, $format );

		return $icon_class;
	}

	/**
	 * Get thumbnail format icon.
	 *
	 * @since 4.5.4
	 */
	public function icon_html( $media_after = '' ) {

		if ( ! $this->enabled() ) {
			return $media_after;
		}

		$post_format = get_post_format();

		$icon_class = $this->icon_class( $post_format );

		if ( ! $icon_class ) {
			return $media_after;
		}

		$icon_html = '<span class="' . esc_attr( $icon_class ) . '"></span>';

		/**
		 * Filters the thumbnail post format icon html.
		 *
		 * @param string $icon_html
		 * @param string $post_format
		 *
		 * @todo rename filter?
		 */
		$icon_html = apply_filters( 'wpex_get_thumbnail_format_icon_html', $icon_html, $post_format );

		if ( $icon_html ) {

			$class = array(
				'wpex-thumbnail-format-icon',
				'wpex-block',
				'wpex-right-0',
				'wpex-bottom-0',
				'wpex-mr-15',
				'wpex-mb-15',
				'wpex-absolute',
				'wpex-text-white',
				'wpex-text-center',
				'wpex-leading-none',
				'wpex-opacity-0',
				'wpex-onload-opacity-100',
			);

			/**
			 * Filters the post thumbnail format icon class.
			 *
			 * @param string $class
			 * @param string $post_format
			 */
			$class = apply_filters( 'wpex_post_thumbnail_format_icon_class', $class, $post_format );

			return  $media_after . '<i class="' . esc_attr( implode( ' ', $class ) ) . '" aria-hidden="true">' . $icon_html . '</i>';

		}

	}

}