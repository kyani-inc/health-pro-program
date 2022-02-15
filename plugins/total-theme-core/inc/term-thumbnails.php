<?php
namespace TotalThemeCore;

defined( 'ABSPATH' ) || exit;

/**
 * Adds thumbnail options to taxonomies.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */
final class Term_Thumbnails {

	/**
	 * Our single Term_Thumbnails instance.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Term_Thumbnails.
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
		if ( is_admin( 'admin' ) ) {
			self::admin_hooks();
		}

		if ( self::is_frontend() ) {
			self::frontend_hooks();
		}
	}

	/**
	 * Admin hooks.
	 */
	public static function admin_hooks() {
		add_action( 'admin_init', __CLASS__ . '::admin_init' );
	}

	/**
	 * Frontend hooks.
	 */
	public static function frontend_hooks() {
		if ( apply_filters( 'wpex_enable_term_page_header_image', true ) ) {
			add_filter( 'wpex_page_header_style', __CLASS__ . '::page_header_style' );
			add_filter( 'wpex_page_header_background_image', __CLASS__ . '::page_header_bg' );
		}
	}

	/**
	 * Get things started in the backend to add/save the settings.
	 */
	public static function admin_init() {
		$taxonomies = apply_filters( 'wpex_thumbnail_taxonomies', get_taxonomies( array(
			'public' => true,
		) ) );

		// Return if no taxonomies are defined
		if ( ! $taxonomies ) {
			return;
		}

		// Loop through taxonomies
		foreach ( $taxonomies as $taxonomy ) {

			// Add forms.
			add_action( $taxonomy . '_add_form_fields', __CLASS__ . '::add_form_fields', 10 );
			add_action( $taxonomy . '_edit_form_fields', __CLASS__ . '::edit_form_fields', 10, 2 );

			// Add columns.
			if ( 'product_cat' !== $taxonomy ) {
				add_filter( 'manage_edit-' . $taxonomy . '_columns', __CLASS__ . '::admin_columns' );
				add_filter( 'manage_' . $taxonomy . '_custom_column', __CLASS__ . '::admin_column', 10, 3 );
			}

			// Save forms.
			add_action( 'created_' . $taxonomy, __CLASS__ . '::save_forms' );
			add_action( 'edit_' . $taxonomy, __CLASS__ . '::save_forms' );

		}
	}

	/**
	 * Add Thumbnail field to add form fields.
	 */
	public static function add_form_fields( $taxonomy ) {
		if ( 'product_cat' === $taxonomy ) {
			return; // options not needed for WooCommerce.
		}

		wp_nonce_field( 'wpex_term_thumbnail_meta_nonce', 'wpex_term_thumbnail_meta_nonce' );

		self::enqueue_admin_scripts();
		?>

		<div class="form-field">

			<label for="term-thumbnail"><?php esc_html_e( 'Image', 'total-theme-core' ); ?></label>

			<div>

				<input type="hidden" id="wpex_term_thumbnail" name="wpex_term_thumbnail">

				<button id="wpex-add-term-thumbnail" class="button-secondary"><?php esc_attr_e( 'Select', 'total' ); ?></button>

				<button id="wpex-term-thumbnail-remove" class="button-secondary" style="display:none;"><?php esc_html_e( 'Remove', 'total' ); ?></button>

				<div id="wpex-term-thumbnail-preview" data-image-size="80"></div>

			</div>

			<div class="clear"></div>

		</div>

	<?php
	}

	/**
	 * Add Thumbnail field to edit form fields.
	 */
	public static function edit_form_fields( $term, $taxonomy ) {

		wp_nonce_field( 'wpex_term_thumbnail_meta_nonce', 'wpex_term_thumbnail_meta_nonce' );

		// Get current taxonomy.
		$term_id = $term->term_id;

		// Get page header setting.
		$page_header_bg = self::get_term_meta( $term_id, 'page_header_bg', true );

		?>

		<tr class="form-field">

			<th scope="row" valign="top"><label><?php esc_html_e( 'Page Header Image', 'total-theme-core' ); ?></label></th>

			<td>
				<select id="wpex_term_page_header_image" name="wpex_term_page_header_image" class="postform">
					<option value="" <?php selected( $page_header_bg, '', true ); ?>><?php esc_html_e( 'Default', 'total-theme-core' ); ?></option>
					<option value="false" <?php selected( $page_header_bg, 'false', true ); ?>><?php esc_html_e( 'No', 'total-theme-core' ); ?></option>
					<option value="true" <?php selected( $page_header_bg, 'true', true ); ?>><?php esc_html_e( 'Yes', 'total-theme-core' ); ?></option>
				</select>
			</td>

		</tr>

		<?php
		// Options not needed for Woo.
		if ( 'product_cat' !== $taxonomy ) :

			// Get thumbnail.
			$thumbnail_id  = self::get_term_thumbnail_id( $term_id, false );

			if ( $thumbnail_id ) {
				$thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail', false );
				$thumbnail_url = $thumbnail_src[0] ?? '';
			}

			self::enqueue_admin_scripts();

			?>

			<tr class="form-field">

				<th scope="row" valign="top">
					<label for="term-thumbnail"><?php esc_html_e( 'Image', 'total-theme-core' ); ?></label>
				</th>

				<td>

					<input type="hidden" id="wpex_term_thumbnail" name="wpex_term_thumbnail" value="<?php echo esc_attr( $thumbnail_id ); ?>">

					<button id="wpex-add-term-thumbnail" class="button-secondary"><?php esc_attr_e( 'Select', 'total' ); ?></button>

					<button id="wpex-term-thumbnail-remove" class="button-secondary"<?php if ( ! $thumbnail_id ) echo ' style="display:none;"'; ?>><?php esc_html_e( 'Remove', 'total' ); ?></button>

					<div id="wpex-term-thumbnail-preview" data-image-size="80">
						<?php if ( ! empty( $thumbnail_url ) ) { ?>
							<img class="wpex-term-thumbnail-img" src="<?php echo esc_url( $thumbnail_url ); ?>" width="80" height="80" style="margin-top:10px;">
						<?php } ?>
					</div>

				</td>

			</tr>

		<?php endif; ?>

		<?php

	}

	/**
	 * Enqueue Admin scripts for uploading/selecting thumbnails.
	 */
	protected static function enqueue_admin_scripts() {
		wp_enqueue_media();

		wp_enqueue_script(
			'wpex-term-thumbnails',
			TTC_PLUGIN_DIR_URL . 'assets/js/term-thumbnails.min.js',
			array( 'jquery' ),
			TTC_VERSION,
			true
		);
	}

	/**
	 * Saves term data in database.
	 */
	protected static function add_term_data( $term_id, $meta_key, $meta_value, $prev_value = '' ) {
		update_term_meta( $term_id, $meta_key, sanitize_text_field( $meta_value ), $prev_value );
	}

	/**
	 * Delete term data from database.
	 */
	protected static function remove_term_data( $term_id, $key ) {
		if ( empty( $term_id ) || empty( $key ) ) {
			return;
		}

		delete_term_meta( $term_id, $key );
	}

	/**
	 * Delete term data from database.
	 */
	protected static function remove_deprecated_term_data( $term_id, $key ) {
		if ( empty( $term_id ) || empty( $key ) ) {
			return;
		}

		// Get deprecated data.
		$term_data = get_option( 'wpex_term_data' );

		// Add to options.
		if ( isset( $term_data[$term_id][$key] ) ) {
			unset( $term_data[$term_id][$key] );
		}

		update_option( 'wpex_term_data', $term_data );
	}

	/**
	 * Update thumbnail value.
	 */
	protected static function update_thumbnail( $term_id, $thumbnail_id ) {

		// Sanitize thumbnail id.
		$safe_thumbnail_id = absint( $thumbnail_id );

		// Add thumbnail.
		if ( ! empty( $safe_thumbnail_id ) ) {
			self::add_term_data( $term_id, 'thumbnail_id', $safe_thumbnail_id );
		}

		// Delete thumbnail.
		else {
			self::remove_term_data( $term_id, 'thumbnail_id' );
		}

		// Remove old data.
		self::remove_deprecated_term_data( $term_id, 'thumbnail' );

	}

	/**
	 * Update page header image option.
	 */
	protected static function update_page_header_img( $term_id, $display ) {

		// Turn into string since we can't check if false is empty.
		if ( is_bool( $display ) ) {
			$display = $display ? 'true' : 'false';
		}

		// Add option.
		if ( isset( $display ) && '' != $display ) {
			self::add_term_data( $term_id, 'page_header_bg', $display );
		}

		// Remove option.
		else {
			self::remove_term_data( $term_id, 'page_header_bg' );
		}

		// Remove old data.
		self::remove_deprecated_term_data( $term_id, 'page_header_bg' );

	}

	/**
	 * Save Forms.
	 */
	public static function save_forms( $term_id ) {
		if ( ! isset( $_POST['wpex_term_thumbnail_meta_nonce'] )
			|| ! wp_verify_nonce( $_POST['wpex_term_thumbnail_meta_nonce'], 'wpex_term_thumbnail_meta_nonce' )
		) {
			return;
		}

		if ( array_key_exists( 'wpex_term_thumbnail', $_POST ) ) {
			self::update_thumbnail( $term_id, $_POST['wpex_term_thumbnail'] );
		}

		if ( array_key_exists( 'wpex_term_page_header_image', $_POST ) ) {
			self::update_page_header_img( $term_id, $_POST['wpex_term_page_header_image'] );
		}
	}

	/**
	 * Thumbnail column added to category admin.
	 */
	public static function admin_columns( $columns ) {
		$columns['wpex-term-thumbnail-col'] = esc_attr__( 'Image', 'total-theme-core' );
		return $columns;
	}

	/**
	 * Thumbnail column value added to category admin.
	 */
	public static function admin_column( $columns, $column, $id ) {

		if ( 'wpex-term-thumbnail-col' === $column ) {

			$thumbnail_id = self::get_term_thumbnail_id( $id, false );

			if ( $thumbnail_id ) {
				$thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );
			}

			if ( ! empty( $thumbnail ) ) {
				$columns .= '<img loading="lazy" src="' . esc_url( $thumbnail[0] ) . '" class="wp-post-image" height="40" width="40">';
			} else {
				$columns .= '&#8212;';
			}

		}

		return $columns;
	}

	/**
	 * Get term meta with fallback
	 */
	protected static function get_term_meta( $term_id = null, $key = '', $single = true ) {
		if ( ! $term_id ) {
			$term_id = get_queried_object()->term_id;
		}

		$value = '';

		if ( $term_id ) {

			$value = get_term_meta( $term_id, $key, $single );

			if ( isset( $value ) ) {
				return $value;
			}

			$term_data = get_option( 'wpex_term_data' );
			$term_data = $term_data[ $term_id ] ?? '';

			if ( $term_data && ! empty( $term_data[ $key ] ) ) {
				return $term_data[ $key ];
			}

		}

		return $value;
	}

	/**
	 * Check if the term page header should have a background image.
	 */
	public static function page_header_style( $style ) {
		if ( self::is_tax_archive() && wpex_term_page_header_image_enabled() && self::get_term_thumbnail_id() ) {
			$style = 'background-image';
		}
		return $style;
	}

	/**
	 * Sets correct page header background.
	 */
	public static function page_header_bg( $image ) {
		if ( self::is_tax_archive() && wpex_term_page_header_image_enabled() ) {
			$term_thumbnail = self::get_term_thumbnail_id();
			if ( $term_thumbnail ) {
				$image_url = wp_get_attachment_image_url( $term_thumbnail, 'full' );
				if ( $image_url ) {
					$image = $image_url;
				}
			}
		}
		return $image;
	}

	/**
	 * Retrieve term thumbnail for admin panel.
	 *
	 * @access public
	 */
	public static function get_term_thumbnail_id( $term_id = null, $apply_filters = true ) {
		$thumbnail_id = '';

		if ( ! $term_id ) {
			$term_id = get_queried_object_id();
		}

		if ( $term_id ) {

			$thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );

			// Check old options.
			if ( empty( $thumbnail_id ) ) {

				$term_data = get_option( 'wpex_term_data' );
				$term_data = $term_data[ $term_id ] ?? '';

				if ( $term_data && ! empty( $term_data[ 'thumbnail' ] ) ) {
					return $term_data[ 'thumbnail' ];
				}

			}

		}

		/**
		 * Filters the term thumbnail id.
		 *
		 * @param int $thumbnail_id
		 * @param int $term_id
		 */
		if ( $apply_filters ) {
			$thumbnail_id = apply_filters( 'wpex_get_term_thumbnail_id', $thumbnail_id );
		}

		return $thumbnail_id;
	}

	/**
	 * Check if on a tax archive.
	 */
	protected static function is_tax_archive() {
		if ( ! is_search() && ( is_tax() || is_category() || is_tag() ) ) {
			return true;
		}
	}

	/**
	 * Check current request.
	 */
	protected static function is_frontend() {
		return ( ! is_admin() || wp_doing_ajax() );
	}

}