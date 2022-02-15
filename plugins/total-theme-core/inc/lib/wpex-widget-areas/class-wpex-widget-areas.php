<?php
/**
 * WPEX Widget Areas
 *
 * @version 1.0
 * @copyright WPExplorer.com - All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPEX_Widget_Areas' ) ) {

	final class WPEX_Widget_Areas {

		/**
		 * Post type used to store the custom widget areas.
		 */
		public static $post_type = 'wpex_widget_area';

		/**
		 * Instance.
		 *
		 * @access private
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Create or retrieve the instance of WPEX_Widget_Areas.
		 */
		public static function instance() {
			if ( is_null( static::$instance ) ) {
				static::$instance = new self();
			}
			return static::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			self::global_hooks();
			if ( is_admin() ) {
				self::admin_hooks();
			}
		}

		/**
		 * Global hooks.
		 *
		 * @since 1.0
		 */
		public static function global_hooks() {

			// Register the "wpex_widget_areas" post_type.
			add_action( 'init', __CLASS__ . '::register_post_type' );

			// Create wpex_widget_areas posts from deprecated theme_mod.
			if ( get_theme_mod( 'widget_areas' ) ) {
				add_action( 'admin_notices', __CLASS__ . '::migration_notice' );
				add_action( 'wp_ajax_wpex_widget_areas_migrate', __CLASS__ . '::migrate_widget_areas_ajax' );
			}

			// Register widget areas with WP.
			add_action( 'init', __CLASS__ . '::register_widget_areas', 1000 ); // use high priority so they display last.

			// Add metaboxes.
			add_action( 'admin_init', __CLASS__ . '::register_metaboxes' );

			// Replace widget areas.
			add_action( 'get_header', __CLASS__ . '::init_replace_widget_areas' );

			// Require conditionals class.
			require_once plugin_dir_path( __FILE__ ) . 'class-wpex-widget-areas-conditions.php';
			new WPEX_Widget_Areas_Conditions;

		}

		/**
		 * Admin hooks.
		 *
		 * @since 1.0
		 */
		public static function admin_hooks() {

			add_action( 'admin_head', __CLASS__ . '::remove_admin_column_filter' );

			add_filter( 'manage_' . self::$post_type . '_posts_columns', __CLASS__ . '::set_admin_columns' );

			add_action( 'manage_' . self::$post_type . '_posts_custom_column', __CLASS__ . '::show_admin_columns', 10, 2 );

			if ( class_exists( 'Vc_Manager' ) ) {
				add_filter( 'vc_is_valid_post_type_be', __CLASS__ . '::disable_wpbakery', 10, 2 );
				add_filter( 'vc_show_button_fe', __CLASS__ . '::remove_wpbakery_button_fe', 10, 3 );
			}

		}

		/**
		 * Register wpex_widget_areas type.
		 *
		 * @since 1.0
		 */
		public static function register_post_type() {
			register_post_type( self::$post_type, array(
				'labels' => array(
					'name' => esc_html__( 'Widget Areas', 'total-theme-core' ),
					'singular_name' => esc_html__( 'Widget Area', 'total-theme-core' ),
					'add_new' => esc_html__( 'Add Widget Area' , 'total-theme-core' ),
					'add_new_item' => esc_html__( 'Add Widget Area' , 'total-theme-core' ),
					'edit_item' => esc_html__( 'Edit Widget Area' , 'total-theme-core' ),
					'new_item' => esc_html__( 'Widget Area' , 'total-theme-core' ),
					'view_item' => esc_html__( 'View Widget Area', 'total-theme-core' ),
					'search_items' => esc_html__( 'Search Widget Areas', 'total-theme-core' ),
					'not_found' => esc_html__( 'No Widget Areas found', 'total-theme-core' ),
					'not_found_in_trash' => esc_html__( 'No Widget Areas found in Trash', 'total-theme-core' ),
				),
				'public' => false,
				'query_var' => true,
				'_builtin' => false,
				'show_ui' => true,
				'show_in_nav_menus' => false,
				'show_in_admin_bar' => false,
				'capability_type' => 'page',
				'hierarchical' => false,
				'menu_position' => null,
				'rewrite' => false,
				'supports' => array( 'title' ),
				'show_in_menu' => 'themes.php',
			) );
		}

		/**
		 * Remove the admin columns sort filter.
		 *
		 * @since 1.0
		 */
		public static function remove_admin_column_filter() {
			if ( self::$post_type === get_current_screen()->post_type ) {
				add_filter( 'months_dropdown_results', '__return_empty_array' );
			}
		}

		/**
		 * Set admin columns.
		 *
		 * @since 1.0
		 */
		public static function set_admin_columns( $columns ) {
			$columns['id'] = esc_html__( 'ID', 'total-theme-core' );
			$columns['area_to_replace'] = esc_html__( 'Area To Replace', 'total-theme-core' );
			$columns['conditions'] = esc_html__( 'Condition(s)', 'total-theme-core' );
			unset( $columns['date'] );
			return $columns;
		}

		/**
		 * Show admin columns.
		 *
		 * @since 1.0
		 */
		public static function show_admin_columns( $column, $post_id ) {
			$registered_sidebars = self::get_registered_sidebars();
			switch( $column ) {
				case 'id' :
					echo esc_html( self::get_widget_area_id( $post_id ) );
				break;
				case 'area_to_replace' :
					$area_to_replace = get_post_meta( $post_id, '_wpex_widget_area_to_replace', true );
					if ( $area_to_replace && array_key_exists( $area_to_replace, $registered_sidebars ) ) {
						echo esc_html( $registered_sidebars[$area_to_replace] );
					} else {
						echo '&#8212;';
					}
				break;
				case 'conditions' :
					$conditions = get_post_meta( $post_id, '_wpex_widget_area_conditions', true );
					if ( is_array( $conditions ) ) {
						WPEX_Widget_Areas_Conditions::selected_conditions_display( $conditions );
					} else {
						echo '&#8212;';
					}
				break;
			}
		}

		/**
		 * Disable wpbakery builder from post type.
		 *
		 * @since 1.0
		 */
		public static function disable_wpbakery( $check, $type ) {
			if ( self::$post_type === $type ) {
				return false;
			}
			return $check;
		}

		/**
		 * Removes the edit with wpbakery button from the admin screen.
		 *
		 * @since 1.0
		 */
		public static function remove_wpbakery_button_fe( $result, $post_id, $type ) {
			if ( self::$post_type === $type ) {
				return false;
			}
			return $result;
		}

		/**
		 * Get widget area ID.
		 *
		 * @since 1.0
		 */
		public static function get_widget_area_id( $post_id ) {
			$post = get_post( $post_id );
			if ( $post && isset( $post->post_name ) ) {
				return $post->post_name;
			}
		}

		/**
		 * Get widget area to replace.
		 *
		 * @since 1.0
		 */
		public static function get_widget_area_to_replace( $post_id ) {
			return get_post_meta( $post_id, '_wpex_widget_area_to_replace', true );
		}

		/**
		 * Get widget area conditions
		 *
		 * @since 1.0
		 */
		public static function get_widget_area_conditions( $post_id ) {
			return get_post_meta( $post_id, '_wpex_widget_area_conditions', true );
		}

		/**
		 * Return widget area posts.
		 *
		 * @since 1.0
		 */
		public static function get_widget_area_posts() {
			return get_posts( array(
				'orderby'          => 'date',
				'order'            => 'ASC',
				'numberposts' 	   => apply_filters( 'wpex_widget_areas_upper_limit', 200 ),
				'post_type' 	   => self::$post_type,
				'post_status'      => 'publish',
				'suppress_filters' => false, // allows for caching.
			) );
		}

		/**
		 * Return widget areas.
		 *
		 * @since 1.0
		 */
		public static function get_widget_areas() {

			$widget_areas = array();

			// Get deprecated mod widget areas.
			$deprecated_mod = get_theme_mod( 'widget_areas' );

			if ( $deprecated_mod && is_array( $deprecated_mod ) ) {
				foreach( $deprecated_mod as $widget_area_name ) {
					$widget_areas[] = array(
						'id'   => sanitize_key( $widget_area_name ),
						'name' => $widget_area_name,
					);
				}
			}

			// Get custom widget area posts.
			$custom_widget_areas = self::get_widget_area_posts();

			if ( ! is_wp_error( $custom_widget_areas ) && count( $custom_widget_areas ) > 0 ) {
				foreach ( $custom_widget_areas as $widget_area ) {
					$id = self::get_widget_area_id( $widget_area );
					if ( $id ) {
						$widget_areas[] = array(
							'id'              => $id,
							'name'            => $widget_area->post_title,
							'area_to_replace' => self::get_widget_area_to_replace( $widget_area->ID ),
							'conditions'      => self::get_widget_area_conditions( $widget_area->ID ),
						);
					}
				}
			}

			return $widget_areas;

		}

		/**
		 * Register the custom widget areas.
		 *
		 * @since 1.0
		 */
		public static function register_widget_areas() {

			$widget_areas = self::get_widget_areas();

			if ( ! is_array( $widget_areas ) ) {
				return;
			}

			foreach ( $widget_areas as $widget_area ) {
				self::register_widget_area( $widget_area );
			}

		}

		/**
		 * Register a single custom widget area.
		 *
		 * @since 1.0
		 */
		public static function register_widget_area( $args ) {
			self::register_sidebar( $args );
		}

		/**
		 * Register a custom sidebar widget area.
		 *
		 * @since 1.0
		 */
		public static function register_sidebar( $args ) {
			if ( empty( $args['id'] ) || empty( $args['name'] ) ) {
				return;
			}
			unset( $args['area_to_replace'] );
			unset( $args['conditions'] );
			$function = function_exists( 'wpex_register_sidebar' ) ? 'wpex_register_sidebar' : 'register_sidebar';
			$function( array(
				'id'   => $args['id'],
				'name' => $args['name'],
			) );
		}

		/**
		 * Migrate old widget areas from theme mods to posts via ajax.
		 *
		 * @since 1.0
		 */
		public static function migrate_widget_areas_ajax() {

			check_ajax_referer( 'wpex_migrate_widget_areas_nonce', 'nonce' );

			@set_time_limit(0);

			$converted_widget_areas = array();

			$deprecated_mod = get_theme_mod( 'widget_areas' );

			if ( ! $deprecated_mod || ! is_array( $deprecated_mod ) ) {
				die();
			}

			// Save widget area backup just incase.
			add_option( 'widget_areas_backup', $deprecated_mod, '', false );

			// Get widget area posts.
			$widget_areas = get_posts( array(
				'orderby'          => 'date',
				'order'            => 'ASC',
				'numberposts' 	   => 50,
				'post_type' 	   => self::$post_type,
				'post_status'      => 'publish',
				'suppress_filters' => true,
			) );

			// Insert widget areas based on old theme_mod.
			foreach( $deprecated_mod as $widget_area ) {

				$widget_area_slug = sanitize_key( $widget_area );

				if ( $widget_areas && array_key_exists( $widget_area_slug, $widget_areas ) ) {
					continue; // post already exists.
				}

				$post_id = wp_insert_post( array(
					'post_type'   => self::$post_type,
					'post_title'  => $widget_area,
					'post_status' => 'publish',
					'post_name'   => $widget_area_slug,
				) );

				// If post was created successfully, let's add the '_slug' post meta and remove it from theme_mod.
				if ( $post_id ) {
					$post = get_post( $post_id );
					if ( $post ) {
						$key = array_search( $post->post_title, $deprecated_mod, true );
						if ( false !== $key ) {
							unset( $deprecated_mod[$key] );
							set_theme_mod( 'widget_areas', $deprecated_mod );
						}
						$converted_widget_areas[] = $widget_area;
					}
				}

			}

			if ( $converted_widget_areas ) {
				echo json_encode( $converted_widget_areas );
			}

			die();

		}

		/**
		 * Display migration notice.
		 *
		 * @since 1.0
		 */
		public static function migration_notice() {

			$current_screen = get_current_screen();

			if ( empty( $current_screen->id ) || 'edit-wpex_widget_area' !== $current_screen->id ) {
				return;
			}

			wp_enqueue_script(
				'wpex-widget-areas-migrate',
				plugin_dir_url( __FILE__ ) . 'assets/wpex-widget-areas-migrate.js',
				array(),
				'1.0',
				true
			);

			?>

			<div id="wpex-migrate-widget-areas-notice" class="notice notice-warning">
				<p style="font-size:16px;"><?php esc_html_e( 'Please click the button below to migrate your old widget areas to the new system.', 'total' ); ?></p>
				<p><a href="#" class="button button-primary" data-wpex-migrate-widget-areas data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpex_migrate_widget_areas_nonce' ) ); ?>"><?php esc_html_e( 'Migrate Widget Areas', 'total' ); ?></a></strong></p>
				<p class="wpex-migrate-widget-areas-loader hidden"><svg height="20px" width="20px" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg"><circle cx="18" cy="18" r="18" fill="#a2a2a2" fill-opacity=".5"/><circle cx="18" cy="8" r="4" fill="#fff"><animateTransform attributeName="transform" dur="1100ms" from="0 18 18" repeatCount="indefinite" to="360 18 18" type="rotate"/></circle></svg></p>
			</div>

		<?php }

		/**
		 * Register metaboxes.
		 *
		 * @since 1.0
		 */
		public static function register_metaboxes() {

			if ( ! class_exists( 'WPEX_Meta_Factory' ) ) {
				return;
			}

			$area_to_replace_choices = array(
				'' => esc_html__( 'None', 'total-theme-core' )
			);

			$registered_sidebars = self::get_registered_sidebars();

			if ( is_array( $registered_sidebars ) && count( $registered_sidebars ) > 0 ) {
				$area_to_replace_choices = $area_to_replace_choices + $registered_sidebars ;
			}

			$settings = array(
				'id'       => 'wpex_widget_areas_area_to_replace',
				'title'    => esc_html__( 'Widget Area to Replace', 'total-theme-core' ),
				'screen'   => array( self::$post_type ),
				'context'  => 'normal',
				'priority' => 'default',
				'fields'   => array(
					array(
						'id' => '_wpex_widget_area_to_replace',
						'type' => 'select',
						'choices' => $area_to_replace_choices,
					),
				),
			);

			new WPEX_Meta_Factory( $settings );

		}

		/**
		 * Get array of registered sidebars.
		 *
		 * @since 1.0
		 */
		public static function get_registered_sidebars() {
			global $wp_registered_sidebars;

			$registered_sidebars = array();
			$custom_sidebars = array();

			$custom_widget_areas = self::get_widget_area_posts();

			if ( ! is_wp_error( $custom_widget_areas ) && count( $custom_widget_areas ) > 0 ) {
				foreach ( $custom_widget_areas as $widget_area ) {
					$id = self::get_widget_area_id( $widget_area->ID );
					if ( $id ) {
						$custom_sidebars[] = $id;
					}
				}
			}

			if ( is_array( $wp_registered_sidebars ) && ( count( $wp_registered_sidebars ) > 0 ) ) {
				foreach ( $wp_registered_sidebars as $k => $v ) {
					if ( ! in_array( $v['id'], $custom_sidebars ) ) {
						$registered_sidebars[$v['id']] = $v['name'];
					}
				}
			}

			return $registered_sidebars;
		}

		/**
		 * init_replace_widget_areas function.
		 *
		 * @since 1.0
		 */
		public static function init_replace_widget_areas() {
			add_filter( 'sidebars_widgets', __CLASS__ . '::replace_widget_areas' );
		}

		/**
		 * Used to replace widget areas with custom ones.
		 *
		 * @since 1.0
		 */
		public static function replace_widget_areas( $sidebars_widgets ) {

			if ( is_admin() ) {
				return $sidebars_widgets;
			}

			$custom_widget_areas = self::get_widget_areas();

			if ( $custom_widget_areas ) {
				foreach( $custom_widget_areas as $custom_widget_area ) {
					if ( empty( $custom_widget_area['area_to_replace'] ) ) {
						continue;
					}
					$area_to_replace = $custom_widget_area['area_to_replace'];
					if ( isset( $sidebars_widgets[$custom_widget_area['id']] )
						&& array_key_exists( $area_to_replace, $sidebars_widgets )
						&& self::maybe_replace_widget_area( $custom_widget_area )
					) {
						$widgets = $sidebars_widgets[$custom_widget_area['id']];
						// Important, only override if we have widgets, otherwise the is_active_sidebar
						// check will fail and so the widget area may fallback to the incorrect sidebar area.
						if ( $widgets ) {
							unset( $sidebars_widgets[$area_to_replace] );
							$sidebars_widgets[$area_to_replace] = $widgets;
						}
					}
				}

			}

			return $sidebars_widgets;

		}

		/**
		 * Returns true if we should replace the current widget area based on the widget conditionals.
		 *
		 * @since 1.0
		 */
		public static function maybe_replace_widget_area( $widget_area ) {
			if ( empty( $widget_area['conditions'] ) ) {
				return true;
			}
			return WPEX_Widget_Areas_Conditions::frontend_check( $widget_area['conditions'] );
		}

	}

	WPEX_Widget_Areas::instance();

}