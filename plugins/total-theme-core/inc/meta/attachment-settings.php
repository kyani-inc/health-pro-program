<?php
namespace TotalThemeCore\Meta;

defined( 'ABSPATH' ) || exit;

/**
 * Adds new fields for the media items.
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 5.2
 */
final class Attachment_Settings {

	/**
	 * Our single Attachment_Settings instance.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Attachment_Settings.
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
		add_filter( 'attachment_fields_to_edit', array( $this, 'edit_fields' ), null, 2 );
		add_filter( 'attachment_fields_to_save', array( $this, 'save_fields' ), null , 2 );
	}

	/**
	 * Adds new edit attachment fields
	 *
	 * @since 2.0.0
	 */
	public function edit_fields( $form_fields, $post ) {
		$form_fields['wpex_video_url'] = array(
			'label'	=> esc_html__( 'Video URL', 'total' ),
			'input'	=> 'text',
			'value'	=> get_post_meta( $post->ID, '_video_url', true ),
		);
	   return $form_fields;
	}

	/**
	 * Save new attachment fields
	 *
	 * @since 2.0.0
	 */
	public function save_fields( $post, $attachment ) {
		if ( isset( $attachment['wpex_video_url'] ) ) {
			update_post_meta( $post['ID'], '_video_url', wp_strip_all_tags( $attachment['wpex_video_url'] ) );
		}
		return $post;
	}

}