<?php
namespace TotalThemeCore\Meta;

defined( 'ABSPATH' ) || exit;

/**
 * Creates a gallery metabox for WordPress.
 *
 * Credits: http://wordpress.org/plugins/easy-image-gallery/
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */
final class Gallery_Metabox {

	/**
	 * Array of post types to add the gallery to.
	 */
	private $post_types;

	/**
	 * Our single Term_Settings instance.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Gallery_Metabox.
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
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * Admin Actions.
	 */
	public function admin_init() {

		// Post types to add the metabox to
		$this->post_types = array( 'post', 'page' );
		$this->post_types = apply_filters( 'wpex_gallery_metabox_post_types', array_combine( $this->post_types, $this->post_types ) );

		// return if no post types
		if ( ! $this->post_types ) {
			return;
		}

		// Add metabox to corresponding post types
		foreach( $this->post_types as $key => $val ) {
			add_action( 'add_meta_boxes_' . $val, array( $this, 'add_meta' ), 20 );
		}

		// Save metabox
		add_action( 'save_post', array( $this, 'save_meta' ) );

	}

	/**
	 * Adds the gallery metabox.
	 */
	public function add_meta( $post ) {

		add_meta_box(
			'wpex-gallery-metabox-ttc',
			esc_html__( 'Image Gallery', 'total-theme-core' ),
			array( $this, 'display_metabox' ),
			$post->post_type,
			'side',
			'default'
		);

	}

	/**
	 * Render the gallery metabox.
	 */
	public function display_metabox() {
		global $post;

		$this->load_scripts();

		?>
		<div id="wpex_gallery_images_container">
			<ul class="wpex_gallery_images">
				<?php
				$image_gallery = get_post_meta( $post->ID, '_easy_image_gallery', true );
				$attachments = array_filter( explode( ',', $image_gallery ) );
				if ( $attachments ) {
					foreach ( $attachments as $attachment_id ) {
						if ( wp_attachment_is_image ( $attachment_id  ) ) {
							echo '<li class="image" data-attachment_id="' . absint( $attachment_id ) . '"><div class="attachment-preview"><div class="thumbnail">
										' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '</div>
										<a href="#" class="wpex-gmb-remove">' . esc_html__( 'Remove image', 'total-theme-core' ) . '</a>
									</div></li>';
						}
					}
				} ?>
			</ul>
			<input type="hidden" id="wpex_image_gallery_field" name="wpex_image_gallery" value="<?php echo esc_attr( $image_gallery ); ?>">
			<?php wp_nonce_field( 'wpex_gallery_metabox_nonce', 'wpex_gallery_metabox_nonce' ); ?>
		</div>

		<p class="add_wpex_gallery_images hide-if-no-js">
			<a href="#" class="button-primary"><?php esc_html_e( 'Add/Edit Images', 'total-theme-core' ); ?></a>
		</p>

		<p>
			<label for="easy_image_gallery_link_images">
				<input type="checkbox" id="easy_image_gallery_link_images" value="on" name="easy_image_gallery_link_images"<?php echo checked( get_post_meta( get_the_ID(), '_easy_image_gallery_link_images', true ), 'on', false ); ?>> <?php esc_html_e( 'Single post lightbox?', 'total-theme-core' )?>
			</label>
		</p>

	<?php
	}

	/**
	 * Render the gallery metabox.
	 */
	public function save_meta( $post_id ) {

		// Check nonce.
		if ( ! isset( $_POST[ 'wpex_gallery_metabox_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'wpex_gallery_metabox_nonce' ], 'wpex_gallery_metabox_nonce' ) ) {
			return;
		}

		// Check auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Update post meta.
		if ( ! empty( $_POST[ 'wpex_image_gallery' ] ) ) {
			$attachment_ids = sanitize_text_field( $_POST['wpex_image_gallery'] );
			$attachment_ids = explode( ',', $attachment_ids );
			$attachment_ids = array_filter( $attachment_ids );
			$attachment_ids =  implode( ',', $attachment_ids );
			update_post_meta( $post_id, '_easy_image_gallery', wp_strip_all_tags( $attachment_ids ) );
		}

		// Delete gallery, but make sure the gallery is actually enabled, we don't want to potentially delete items if the form
		// isn't even on the page.
		elseif( isset( $_POST[ 'wpex_image_gallery' ] ) ) {
			delete_post_meta( $post_id, '_easy_image_gallery' );
		}

		if ( isset( $_POST[ 'easy_image_gallery_link_images' ] ) ) {
			update_post_meta( $post_id, '_easy_image_gallery_link_images', wp_strip_all_tags( $_POST[ 'easy_image_gallery_link_images' ] ) );
		} else {
			update_post_meta( $post_id, '_easy_image_gallery_link_images', 'off' );
		}

		do_action( 'wpex_save_gallery_metabox', $post_id );

	}

	/**
	 * Load needed scripts.
	 */
	public function load_scripts() {

		wp_enqueue_style(
			'wpex-gallery-metabox',
			TTC_PLUGIN_DIR_URL . 'assets/css/gallery-metabox.css',
			false,
			TTC_VERSION
		);

		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_enqueue_script(
			'wpex-gallery-metabox',
			TTC_PLUGIN_DIR_URL . 'assets/js/gallery-metabox.min.js',
			array( 'jquery', 'jquery-ui-sortable' ),
			'1.0',
			true
		);

		wp_localize_script( 'wpex-gallery-metabox', 'wpexGalleryMetabox', array(
			'title'  => esc_html__( 'Add Images to Gallery', 'total-theme-core' ),
			'button' => esc_html__( 'Add to gallery', 'total-theme-core' ),
			'remove' => esc_html__( 'Remove image', 'total-theme-core' ),
		) );

	}

}