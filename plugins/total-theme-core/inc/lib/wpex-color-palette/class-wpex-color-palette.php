<?php
/**
 * WPEX Color Palette.
 *
 * @version 1.1
 * @copyright WPExplorer.com - All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPEX_Color_Palette' ) ) {

	final class WPEX_Color_Palette {

		/**
		 * Post type used to store the color palette.
		 */
		public $post_type = 'wpex_color_palette';

		/**
		 * Check if we have registered colors or not.
		 */
		public static $get_registered_colors = false;

		/**
		 * Holds array of registered colors.
		 */
		public static $registered_colors = array();

		/**
		 * Instance.
		 *
		 * @access private
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Create or retrieve the instance of WPEX_Color_Palette.
		 */
		public static function instance() {
			if ( is_null( static::$instance ) ) {
				static::$instance = new self();
				static::$instance->init_hooks();
				static::$instance->gutenberg_support();
			}

			return static::$instance;
		}

		/**
		 * Hook into actions and filters.
		 */
		public function init_hooks() {

			$this->global_hooks();

			if ( is_admin() ) {
				$this->admin_hooks();
			}

		}

		/**
		 * Global hooks.
		 */
		public function global_hooks() {
			add_action( 'init', array( $this, 'register_type' ) );
			add_action( 'init', array( $this, 'get_registered_colors' ) );
			add_filter( 'wpex_head_css', array( $this, 'head_css' ) );
		}

		/**
		 * Admin hooks.
		 */
		public function admin_hooks() {

			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_head', array( $this, 'remove_admin_column_filter' ) );
			add_filter( 'manage_' . $this->post_type . '_posts_columns', array( $this, 'set_admin_columns' ) );
			add_action( 'manage_' . $this->post_type . '_posts_custom_column', array( $this, 'show_admin_columns' ), 10, 2 );

			add_action( 'admin_head-post.php', array( $this, 'add_back_button' ) );

			add_action( 'admin_init', array( $this, 'register_metaboxes' ) );

			add_action( 'save_post', array( $this, 'set_color_slug' ), 10, 3 );

			if ( class_exists( 'Vc_Manager' ) ) {
				add_filter( 'vc_is_valid_post_type_be', array( $this, 'disable_wpbakery' ), 10, 2 );
				add_filter( 'vc_show_button_fe', array( $this, 'remove_wpbakery_button_fe' ), 10, 3 );
			}

		}

		/**
		 * Add gutenberg support if enabled.
		 */
		public function gutenberg_support() {

			if ( class_exists( 'Classic_Editor' ) ) {
				return;
			}

			if ( class_exists( 'Vc_Manager' ) && get_option( 'wpb_js_gutenberg_disable' ) ) {
				return;
			}

			$check = self::has_registered_colors();

			$has_support = apply_filters( 'wpex_color_palette_gutenberg_support', $check );

			if ( ! $has_support ) {
				return;
			}

			static::$instance->add_theme_support();

		}

		/**
		 * Register wpex_color_palette type.
		 */
		public function register_type() {

			register_post_type( $this->post_type, array(
				'labels' => array(
					'name'               => esc_html__( 'Color Palette', 'total-theme-core' ),
					'singular_name'      => esc_html__( 'Color', 'total-theme-core' ),
					'add_new'            => esc_html__( 'Add Color' , 'total-theme-core' ),
					'add_new_item'       => esc_html__( 'Add Color' , 'total-theme-core' ),
					'edit_item'          => esc_html__( 'Edit Color' , 'total-theme-core' ),
					'new_item'           => esc_html__( 'Color' , 'total-theme-core' ),
					'view_item'          => esc_html__( 'View Color', 'total-theme-core' ),
					'search_items'       => esc_html__( 'Search Colors', 'total-theme-core' ),
					'not_found'          => esc_html__( 'No Colors found', 'total-theme-core' ),
					'not_found_in_trash' => esc_html__( 'No Colors found in Trash', 'total-theme-core' ),
				),
				'public'          => false,
				'show_ui'         => true,
				'_builtin'        => false,
				'capability_type' => 'page',
				'hierarchical'    => false,
				'rewrite'         => false,
				'supports'        => array( 'title' ),
				'show_in_menu'    => 'edit.php?post_type=wpex_color_palette',
			) );

		}

		/**
		 * Register new admin menu.
		 */
		public function admin_menu() {

			$parent_slug = defined( 'WPEX_THEME_PANEL_SLUG' ) ? WPEX_THEME_PANEL_SLUG : 'themes.php';

			add_submenu_page(
				$parent_slug,
				esc_html__( 'Color Palette', 'total-theme-core' ),
				esc_html__( 'Color Palette', 'total-theme-core' ),
				'edit_theme_options',
				'edit.php?post_type=' . $this->post_type
			);

		}

		/**
		 * Remove the admin columns sort filter.
		 */
		public function remove_admin_column_filter() {

			$screen = get_current_screen();

			if ( $this->post_type == $screen->post_type ) {
				add_filter( 'months_dropdown_results', '__return_empty_array' );
			}

		}

		/**
		 * Set admin columns.
		 */
		public function set_admin_columns( $columns ) {

			$columns['color']       = esc_html__( 'Color', 'total-theme-core' );
			$columns['description'] = esc_html__( 'Description', 'total-theme-core' );
			$columns['slug']        = esc_html__( 'Slug', 'total-theme-core' );

			unset( $columns['date'] );

			return $columns;

		}

		/**
		 * Show admin columns.
		 */
		public function show_admin_columns( $column, $post_id ) {

			switch( $column ) {

				case 'description' :

					$description = wp_strip_all_tags( get_post_meta( $post_id, 'description', true ) );

					if ( $description ) {
						echo '<i>' . wp_kses_post( $description ) . '</i>';
					}

				break;

				case 'color' :

					$color = wp_strip_all_tags( get_post_meta( $post_id, 'color', true ) );

					if ( $color ) {
						echo '<div style="background:' . esc_attr( $color ) . ';height:30px;width:50px;border-radius:4px;box-shadow:inset 0 0 0 1px rgba(0,0,0,.2);border: 1px solid transparent;"><div>';
					}

				break;

				case 'slug' :

					$slug = wp_strip_all_tags( get_post_meta( $post_id, 'slug', true ) );

					if ( $slug ) {
						echo '<code>' . esc_html( $slug ) . '</code>';
					}

				break;


			}

		}

		/**
		 * Add a back button to the Color Palette admin page.
		 */
		public function add_back_button() {

			global $current_screen;

			if ( $this->post_type !== $current_screen->post_type ) {
				return;
			}

			wp_enqueue_script( 'jQuery' );

			?>

		    <script>
			    jQuery( function() {
					jQuery( 'body.post-type-wpex_color_palette .wrap h1' ).append( '<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wpex_color_palette' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Color Palette', 'total-theme-core' ); ?></a>' );
				} );
		    </script>

		    <?php
		}

		/**
		 * Register metaboxes.
		 */
		public function register_metaboxes() {
			if ( class_exists( 'WPEX_Meta_Factory' ) ) {
				new WPEX_Meta_Factory( $this->meta_box() );
			}
		}

		/**
		 * Save slug.
		 */
		public function set_color_slug( $post_id, $post, $update ) {

			if ( $this->post_type !== $post->post_type ) {
				return;
			}

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

			$slug = get_post_meta( $post_id, 'slug', true );

			if ( empty( $slug ) ) {

				$slug = 'palette-' . trim( absint( $post_id ) );

				update_post_meta( $post_id, 'slug', $slug );

			}

		}

		/**
		 * General Metabox.
		 */
		public function meta_box() {

			return array(
				'id'       => 'general',
				'title'    => esc_html__( 'Settings', 'total-theme-core' ),
				'screen'   => array( $this->post_type ),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => array(
					array(
						'name'  => esc_html__( 'Color', 'total-theme-core' ),
						'id'    => 'color',
						'type'  => 'color',
						'alpha' => true,
					),
					array(
						'name' => esc_html__( 'Description', 'total-theme-core' ),
						'id'   => 'description',
						'type' => 'textarea',
					),
				)
			);

		}

		/**
		 * Conditional check to see if we have registered colors.
		 */
		public static function has_registered_colors() {

			$palette = self::get_registered_colors();

			if ( ! empty( $palette ) ) {
				return true;
			}

		}

		/**
		 * Return all registered colors.
		 */
		public static function get_registered_colors() {

			if ( self::$get_registered_colors ) {
				return self::$registered_colors;
			}

			$colors = get_posts( array(
				'numberposts' 	   => 50,
				'post_type' 	   => 'wpex_color_palette',
				'post_status'      => 'publish',
				'suppress_filters' => false, // allow translation plugins to translate colors.
				'fields'           => 'ids',
			) );

			if ( $colors ) {

				foreach ( $colors as $post_id ) {

					$name  = get_the_title( $post_id );
					$color = get_post_meta( $post_id, 'color', true );
					$slug  = get_post_meta( $post_id, 'slug', true );

					if ( $name && $color && $slug ) {

						self::$registered_colors[] = array(
							'name'  => $name,
							'color' => $color,
							'slug'  => $slug,
						);

					}

				}

			}

			self::$get_registered_colors = true; // prevent extra get_posts checks.

			return self::$registered_colors;

		}

		/**
		 * Return color palette colors list as an array.
		 */
		public static function get_colors_list() {

			$colors = array();

			if ( function_exists( 'wpex_get_accent_color' ) ) {
				$colors['accent'] = array(
					'name'  => esc_html( 'Accent', 'total-theme-core' ),
					'color' => wpex_get_accent_color(),
				);
			}

			$registered_colors = self::get_registered_colors();

			if ( ! empty( $registered_colors ) && is_array( $registered_colors ) ) {

				foreach ( $registered_colors as $color ) {
					$colors[$color['slug']] = $color;
				}

			}

			return (array) apply_filters( 'wpex_color_palette', $colors );

		}

		/**
		 * Disable wpbakery builder from post type.
		 */
		public function disable_wpbakery( $check, $type ) {
			if ( $this->post_type == $type ) {
				return false;
			}
			return $check;
		}

		/**
		 * Removes the edit with wpbakery button from the admin screen.
		 */
		public function remove_wpbakery_button_fe( $result, $post_id, $type ) {
			if ( $this->post_type == $type ) {
				return false;
			}
			return $result;
		}

		/**
		 * Register Color Palette for use with Gutenberg.
		 */
		public function add_theme_support() {

			$colors = self::get_colors_list();

			if ( empty( $colors ) || ! is_array( $colors ) ) {
				return;
			}

			$editor_palette = array();

			foreach ( $colors as $slug => $color ) {

				$editor_palette[] = array(
					'name'  => $color['name'],
					'slug'  => $slug,
					'color' => $color['color'],
				);

			}

			add_theme_support( 'editor-color-palette', $editor_palette );

		}

		/**
		 * Generate and output CSS on the front-end for each color.
		 */
		public function head_css( $css ) {

			$palette_css = '';

			$colors = self::get_colors_list();

			if ( $colors && is_array( $colors ) ) {

				foreach ( $colors as $slug => $color ) {

					// Background color
					$palette_css .= '.has-' . sanitize_html_class( $slug ) . '-background-color{background-color:' . esc_attr( $color['color'] ) . '}';

					// Text color
					$palette_css .= '.has-' . sanitize_html_class( $slug ) . '-color{color:' . esc_attr( $color['color'] ) . '}';

				}

			}

			$palette_css = apply_filters( 'wpex_color_palette_head_css', $palette_css );

			if ( $palette_css ) {
				$css .= $palette_css;
			}

			return $css;

		}

	}

	WPEX_Color_Palette::instance();

}