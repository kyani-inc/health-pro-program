<?php
namespace TotalThemeCore\Meta;

defined( 'ABSPATH' ) || exit;

/**
 * Class for easily adding term meta settings.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */
final class Term_Settings {

	/**
	 * Our single Term_Settings instance.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Term_Settings.
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

		// Register meta options
		// Not needed since it only is used for sanitization which we do ourselves
		//add_action( 'init', __CLASS__ . '::register_meta' );

		// Admin init
		add_action( 'admin_init', __CLASS__ . '::meta_form_fields', 40 );

	}

	/**
	 * Array of meta options.
	 */
	public static function get_options() {

		$options = array(
			// Card style
			'wpex_entry_card_style' => array(
				'label'     => esc_html__( 'Entry Card Style', 'total-theme-core' ),
				'type'      => 'select',
				'choices'   => 'wpex_choices_card_styles',
				'args'      => array(
					'type'              => 'string',
					'single'            => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
			// Redirect
			'wpex_redirect' => array(
				'label'     => esc_html__( 'Redirect', 'total-theme-core' ),
				'type'      => 'wp_dropdown_pages',
				'args'      => array(
					'type'              => 'integer',
					'single'            => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
			// Sidebar select
			'wpex_sidebar' => array(
				'label'    => esc_html__( 'Sidebar', 'total-theme-core' ),
				'type'     => 'select',
				'choices'  => 'wpex_choices_widget_areas',
				'args'     => array(
					'type'              => 'string',
					'single'            => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		);

		/**
		 * Filters the term meta options array.
		 *
		 * @param array $options.
		 */
		$options = apply_filters( 'wpex_term_meta_options', $options );

		return $options;
	}

	/**
	 * Add meta form fields.
	 */
	public static function meta_form_fields() {

		// Get taxonomies.
		$taxonomies = (array) apply_filters( 'wpex_term_meta_taxonomies', get_taxonomies( array(
			'public' => true,
		) ) );

		// Return if no taxes defined.
		if ( ! $taxonomies ) {
			return;
		}

		// Loop through taxonomies.
		foreach ( $taxonomies as $taxonomy ) {

			// Add fields to add new term page.
			add_action( $taxonomy . '_add_form_fields', __CLASS__ . '::add_form_fields' );

			// Add fields to edit term page.
			add_action( $taxonomy . '_edit_form_fields', __CLASS__ . '::edit_form_fields' );

			// Save fields.
			add_action( 'created_' . $taxonomy, __CLASS__ . '::save_forms', 10, 3 );
			add_action( 'edited_' . $taxonomy, __CLASS__ . '::save_forms', 10, 3 );

			// Show fields in admin columns.
			add_filter( 'manage_edit-' . $taxonomy . '_columns', __CLASS__ . '::admin_columns' );
			add_filter( 'manage_' . $taxonomy . '_custom_column', __CLASS__ . '::admin_column', 10, 3 );

		}

	}

	/**
	 * Register meta options.
	 */
	public static function register_meta() {
		foreach( self::get_options() as $key => $val ) {
			$args = $val['args'] ?? array();
			register_meta( 'term', $key, $args );
		}
	}

	/**
	 * Adds new category fields.
	 */
	public static function add_form_fields( $taxonomy ) {
		$has_fields = false;

		// Get term options.
		$meta_options = self::get_options();

		// Make sure options aren't empty/disabled.
		if ( ! empty( $meta_options ) && is_array( $meta_options ) ) {

			// Loop through options.
			foreach ( $meta_options as $key => $val ) {

				if ( empty( $val['show_on_create'] ) ) {
					continue;
				}

				if ( false === $has_fields ) {
					$has_fields = true;
				}

				$label = $val['label'] ?? '';

				if ( ! self::maybe_add_option_to_taxonomy( $val, $taxonomy ) ) {
					continue;
				}

				?>

				<div class="form-field">
					<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
					<?php self::meta_form_field( $key, $val, '' ); ?>
				</div>

			<?php }

			// Add security nonce only if fields are to be added.
			if ( $has_fields ) {
				wp_nonce_field( 'wpex_term_meta_nonce', 'wpex_term_meta_nonce' );
			}

		}

	}

	/**
	 * Adds new category fields.
	 */
	public static function edit_form_fields( $term ) {

		// Security nonce.
		wp_nonce_field( 'wpex_term_meta_nonce', 'wpex_term_meta_nonce' );

		// Get term options.
		$meta_options = self::get_options();

		// Make sure options aren't empty/disabled.
		if ( ! empty( $meta_options ) && is_array( $meta_options ) ) {

			// Loop through options.
			foreach ( $meta_options as $key => $val ) {

				$label = $val['label'] ?? '';

				if ( ! self::maybe_add_option_to_taxonomy( $val, $term->taxonomy ) ) {
					continue;
				}

				?>

				<tr class="form-field">
					<th scope="row" valign="top"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
					<td><?php self::meta_form_field( $key, $val, $term ); ?></td>
				</tr>

			<?php }

		}

	}

	/**
	 * Saves meta fields.
	 */
	public static function save_forms( $term_id ) {

		// Make sure everything is secure.
		if ( empty( $_POST['wpex_term_meta_nonce'] )
			|| ! wp_verify_nonce( $_POST['wpex_term_meta_nonce'], 'wpex_term_meta_nonce' )
		) {
			return;
		}

		// Get options.
		$meta_options = self::get_options();

		// Make sure options aren't empty/disabled.
		if ( ! empty( $meta_options ) && is_array( $meta_options ) ) {

			// Loop through options.
			foreach ( $meta_options as $key => $val ) {

				// Skip any option that isn't in $__POST - this way we aren't deleting meta that could be temporarily hidden.
				if ( ! array_key_exists( $key, $_POST ) ) {
					continue;
				}

				// Check option value.
				$value = $_POST[$key];

				// Save setting.
				if ( $value ) {
					if ( isset( $val['args']['sanitize_callback'] ) && is_callable( $val['args']['sanitize_callback'] ) ) {
						$safe_value = call_user_func( $val['args']['sanitize_callback'], $value );
					} else {
						$safe_value = sanitize_text_field( $value );
					}
					update_term_meta( $term_id, $key, $safe_value );
				}

				// Delete setting.
				else {
					delete_term_meta( $term_id, $key );
				}

			}

		}

	}

	/**
	 * Add new admin columns for specific fields.
	 */
	public static function admin_columns( $columns ) {
		$meta_options = self::get_options();
		if ( ! empty( $meta_options ) && is_array( $meta_options ) && array_key_exists( 'taxonomy', $_GET ) ) {
			foreach ( $meta_options as $key => $option ) {
				if ( ! empty( $option['has_admin_col'] )
					&& self::maybe_add_option_to_taxonomy( $option, $_GET['taxonomy'] )
				) {
					$columns[$key] = esc_html( $option['label'] );
				}
			}
		}
		return $columns;
	}

	/**
	 * Display certain field vals in admin columns.
	 */
	public static function admin_column( $columns, $column, $term_id ) {

		$meta_options = self::get_options();

		if ( ! empty( $meta_options[$column] ) && ! empty( $meta_options[$column]['has_admin_col'] ) ) {

			$meta = get_term_meta( $term_id, $column, true );

			if ( $meta ) {
				$field_type = $meta_options[$column]['type'];

				switch ( $field_type ) {
					case 'color':
						$columns .= '<span style="background:' . esc_attr( $meta ) . ';width:15px;height:15px;display:inline-block;border-radius:999px;"></span>';
						break;
					default:
						$columns .= esc_html( $meta );
						break;
				}

			} else {
				$columns .= '&#8212;';
			}

		}

		return $columns;

	}

	/**
	 * Outputs the form field.
	 */
	public static function meta_form_field( $key, $val, $term = '' ) {

		$type = $val['type'] ?? 'text';
		$term_id = ( ! empty( $term ) && is_object( $term ) ) ? $term->term_id : '';
		$value = get_term_meta( $term_id, $key, true );

		// Text.
		switch ( $type ) {

			case 'select':

				$choices = ! empty( $val['choices'] ) ? $val['choices'] : false;

				if ( $choices ) {

					if ( is_string( $choices ) && function_exists( $choices ) ) {
						$choices = call_user_func( $choices );
					}

					?>

					<select name="<?php echo esc_attr( $key ); ?>">
						<?php foreach ( $choices as $key => $val ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value, $key ) ?>><?php echo esc_html( $val ); ?></option>
						<?php endforeach; ?>
					</select>

				<?php
				}
				break;

			case 'color':

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );

				wp_enqueue_script(
					'wpex-wp-color-picker-init',
					TTC_PLUGIN_DIR_URL . 'assets/js/wpColorPicker-init.min.js',
					array( 'jquery', 'wp-color-picker' ),
					true
				);

				?>

					<input type="text" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>" class="wpex-color-field">

				<?php
				break;

			case 'wp_dropdown_pages':

				$args = array(
					'name' => $key,
					'selected' => $value,
					'show_option_none' => esc_html__( 'None', 'total-theme-core' )
				);

				wp_dropdown_pages( $args );

				break;

			case 'text':
			default: ?>

				<input type="text" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>"></td>

			<?php
			break;

		} // end switch type.

	}

	/**
	 * Checks if a specific option should be added to the taxonomy.
	 */
	public static function maybe_add_option_to_taxonomy( $option, $taxonomy ) {
		if ( isset( $option['taxonomies'] ) && is_array( $option['taxonomies'] ) ) {
			if ( ! in_array( $taxonomy, $option['taxonomies'] ) ) {
				return false;
			}
		}
		return true;
	}

}