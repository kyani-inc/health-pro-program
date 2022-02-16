<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

/**
 * Under Construction Addon.
 *
 * @package TotalTheme
 * @version 5.3.1
 */
final class Under_Construction {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Under_Construction.
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

		if ( wpex_is_request( 'admin' ) ) {
			add_action( 'admin_menu', array( $this, 'add_submenu_page' ), 5 );
			add_action( 'admin_init', array( $this, 'register_page_options' ) );
		}

		if ( wpex_is_request( 'frontend' ) && get_theme_mod( 'under_construction', false ) ) {
			add_action( 'template_redirect', array( $this, 'redirect' ) );
		}

	}

	/**
	 * Add sub menu page for the custom CSS input.
	 */
	public function add_submenu_page() {

		add_submenu_page(
			WPEX_THEME_PANEL_SLUG,
			esc_html__( 'Under Construction', 'total' ),
			esc_html__( 'Under Construction', 'total' ),
			'manage_options',
			WPEX_THEME_PANEL_SLUG . '-under-construction',
			array( $this, 'create_admin_page' )
		);

	}

	/**
	 * Function that will register admin page options.
	 */
	public function register_page_options() {

		// Register settings.
		register_setting( 'wpex_under_construction', 'under_construction', array(
			'show_in_rest'      => false,
			'sanitize_callback' => array( $this, 'sanitize_settings' ),
		) );

		// Add main section to our options page.
		add_settings_section( 'wpex_under_construction_main', false, array( $this, 'section_main_callback' ), 'wpex-under-construction-admin' );

		// Enable field.
		add_settings_field(
			'under_construction',
			esc_html__( 'Enable Under Constuction', 'total' ),
			array( $this, 'enable_field_callback' ),
			'wpex-under-construction-admin',
			'wpex_under_construction_main',
			array(
				'label_for' => 'wpex-under-construction-enable',
			)
		);

		// Under construction page select field.
		add_settings_field(
			'under_construction_page_id',
			esc_html__( 'Under Construction Page', 'total' ),
			array( $this, 'content_id_field_callback' ),
			'wpex-under-construction-admin',
			'wpex_under_construction_main',
			array(
				'label_for' => 'wpex-under-construction-page-select',
			)
		);

		// Exclude pages field.
		add_settings_field(
			'under_construction_exclude_pages',
			esc_html__( 'Exclude Pages From Redirection', 'total' ),
			array( $this, 'under_construction_exclude_pages_callback' ),
			'wpex-under-construction-admin',
			'wpex_under_construction_main',
			array(
				'label_for' => 'wpex-under-construction-exclude-pages-select',
			)
		);

		// Logged in roles.
		add_settings_field(
			'under_construction_access_roles',
			esc_html__( 'Site Access Roles', 'total' ),
			array( $this, 'under_construction_access_roles_callback' ),
			'wpex-under-construction-admin',
			'wpex_under_construction_main',
			array(
				'label_for' => 'wpex-under-construction__access-roles-field',
			)
		);

	}

	/**
	 * Sanitization callback.
	 */
	public function sanitize_settings( $settings ) {

		if ( isset ( $settings['enable'] ) ) {
			set_theme_mod( 'under_construction', 1 );
		} else {
			remove_theme_mod( 'under_construction' );
		}

		if ( isset( $settings['content_id'] ) ) {
			set_theme_mod( 'under_construction_page_id', $settings['content_id'] );
		}

		if ( isset( $settings['exclude_pages'] ) && is_array( $settings['exclude_pages'] ) ) {
			$exclude_pages_sanitized = array_map( 'absint', $settings['exclude_pages'] );
			set_theme_mod( 'under_construction_exclude_pages', $exclude_pages_sanitized );
		} else {
			remove_theme_mod( 'under_construction_exclude_pages' );
		}

		if ( isset( $settings['access_roles'] ) && is_array( $settings['access_roles'] ) ) {
			$access_roles_sanitized = array_map( 'esc_html', $settings['access_roles'] );
			set_theme_mod( 'under_construction_access_roles', $access_roles_sanitized );
		} else {
			remove_theme_mod( 'under_construction_access_roles' );
		}

		return '';
	}

	/**
	 * Main Settings section callback.
	 */
	public function section_main_callback( $options ) {
		// Leave blank
	}

	/**
	 * Enable under construction field callback.
	 */
	public function enable_field_callback() {
		$val = get_theme_mod( 'under_construction', false );
		echo '<input type="checkbox" name="under_construction[enable]" value="' . esc_attr( $val ) . '" ' . checked( $val, true, false ) . ' id="wpex-under-construction-enable"> ';
	}

	/**
	 * Under construction page select field callback.
	 */
	public function content_id_field_callback() {

		wp_enqueue_script(
			'wpex-chosen',
			wpex_asset_url( 'lib/chosen/chosen.jquery.min.js' ),
			array( 'jquery' ),
			'1.4.1'
		);

		wp_enqueue_style(
			'wpex-chosen',
			wpex_asset_url( 'lib/chosen/chosen.min.css' ),
			false,
			'1.4.1'
		);

		// Get construction page id.
		$page_id = get_theme_mod( 'under_construction_page_id' ); ?>

		<select name="under_construction[content_id]" id="wpex-under-construction-page-select" class="wpex-chosen">

			<option value=""><?php esc_html_e( 'None', 'total' ); ?></option>

			<?php
			$pages = get_pages( array(
				'exclude' => get_option( 'page_on_front' ),
			) );
			if ( $pages ) {
				foreach ( $pages as $page ) {
					echo '<option value="' . absint( $page->ID ) . '"' . selected( $page_id, $page->ID, false ) . '>' . esc_attr( $page->post_title ) . '</option>';
				}
			} ?>

		</select>

		<p class="description"><?php esc_html_e( 'Select your custom page for your under construction display. Every page and post will redirect to your selected page for non-logged in users.', 'total' ) ?></p>

		<?php
		// Display edit and preview buttons.
		if ( $page_id ) { ?>

			<p style="margin:20px 0 0;">

			<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $page_id . '&action=edit' ) ); ?>" class="button" target="_blank" rel="noopener noreferrer">
                <?php esc_html_e( 'Backend Edit', 'total' ); ?>
            </a>

            <?php if ( WPEX_VC_ACTIVE ) { ?>
                <a href="<?php echo esc_url( admin_url( 'post.php?vc_action=vc_inline&post_id=' . $page_id . '&post_type=page' ) ); ?>" class="button" target="_blank" rel="noopener noreferrer">
                    <?php esc_html_e( 'Frontend Edit', 'total' ); ?>
                </a>
            <?php } ?>

            <a href="<?php the_permalink( $page_id ); ?>" class="button" target="_blank" rel="noopener noreferrer">
                <?php esc_html_e( 'Preview', 'total' ); ?>
            </a>

		<?php } ?>

	<?php }

	/**
	 * Exclude pages field callback.
	 */
	public function under_construction_exclude_pages_callback() {
		$exclude_pages = (array) get_theme_mod( 'under_construction_exclude_pages', array() );
		$pages = get_pages( array(
			'exclude' => get_option( 'page_on_front' ),
		) );
		if ( ! $pages ) {
			return;
		} ?>
		<select data-placeholder="<?php esc_html_e( 'Click to select&hellip;', 'total' ); ?>" multiple name="under_construction[exclude_pages][]" id="wpex-under-construction-exclude-pages-select" class="wpex-chosen-multiselect" style="min-width:270px;">
			<option value=""><?php esc_html_e( 'None', 'total' ); ?></option>
			<?php
			foreach ( $pages as $page ) {
				echo '<option value="' . absint( $page->ID ) . '"' . selected( in_array( $page->ID, $exclude_pages ), true, false ) . '>' . esc_attr( $page->post_title ) . '</option>';
			} ?>
		</select>
	<?php }

	/**
	 * Access roles callback.
	 */
	public function under_construction_access_roles_callback() {
		$access_roles = (array) get_theme_mod( 'under_construction_access_roles', array() );
		$get_roles = get_editable_roles();
		if ( ! is_array( $get_roles ) || empty( $get_roles ) ) {
			return;
		}
		?>
		<select data-placeholder="<?php esc_html_e( 'Click to select&hellip;', 'total' ); ?>" multiple name="under_construction[access_roles][]" id="wpex-under-construction-exclude-pages-select" class="wpex-chosen-multiselect" style="min-width:270px;">
			<option value=""><?php esc_html_e( 'None', 'total' ); ?></option>
			<?php foreach ( $get_roles as $role_name => $role_info ) {
				echo '<option value="' . esc_attr( $role_name ) . '"' . selected( in_array( $role_name, $access_roles ), true, false ) . '>' . esc_attr( $role_info['name'] ) . '</option>';
			} ?>
		</select>
		<p class="description"><?php esc_html_e( 'Limit access to the site to logged in users with specific roles.', 'total' ) ?></p>
	<?php }

	/**
	 * Settings page output.
	 */
	public function create_admin_page() {

		wp_enqueue_style( 'wpex-chosen' );
		wp_enqueue_script( 'wpex-chosen' );

		wp_enqueue_style( 'wpex-admin-pages' );
		wp_enqueue_script( 'wpex-admin-pages' );

		?>

		<div class="wrap">

			<h1><?php esc_html_e( 'Under Construction', 'total' ); ?></h1>

			<p><?php esc_html_e( 'Redirect all non-logged in traffic to a specific page. Useful when building the site or making changes and do not want to make it public yet.', 'total' ); ?></p>

			<hr>

			<form method="post" action="options.php">
				<?php settings_fields( 'wpex_under_construction' ); ?>
				<?php do_settings_sections( 'wpex-under-construction-admin' ); ?>
				<?php submit_button(); ?>
			</form>

		</div>

	<?php }

	/**
	 * Redirect all pages to the under cronstruction page if user is not logged in.
	 *
	 * @since 1.6.0
	 */
	public function redirect() {
		$redirect = false;

		// Get under construction page ID.
		$page_id = absint( wpex_parse_obj_id( get_theme_mod( 'under_construction_page_id' ), 'page' ) );

		// Return if ID not defined.
		if ( ! $page_id ) {
			return;
		}

		// Return if under construction is the same as posts page because it creates an endless loop.
		if ( $page_id === absint( get_option( 'page_for_posts' ) ) ) {
			return;
		}

		// Check excluded pages.
		if ( $exclude_pages = get_theme_mod( 'under_construction_exclude_pages', null ) ) {
			if ( is_array( $exclude_pages ) && in_array( wpex_get_current_post_id(), $exclude_pages ) ) {
				return;
			}
		}

		// Check access roles if user is logged in.
		if ( is_user_logged_in() ) {

			$access_roles = (array) get_theme_mod( 'under_construction_access_roles', array() );

			if ( $access_roles ) {
				$user = wp_get_current_user();
				if ( $user && ! empty( $user->roles ) && ! array_intersect( $access_roles, $user->roles ) ) {
				   $redirect = true;
				}
			}

		// If user is not logged in redirect them.
		} else {
			$redirect = true;
		}

		// Apply filters.
		$redirect = apply_filters( 'wpex_has_under_construction_redirect', $redirect );

		// Get permalink if redirect is enabled.
		if ( $redirect ) {
			$permalink = get_permalink( $page_id );
			if ( $permalink && ! is_page( $page_id ) ) {
				$redirect = true;
			} else {
				$redirect = false;
			}
		}

		// Redirect.
		if ( $redirect && ! empty( $permalink ) ) {
			wp_safe_redirect( esc_url( $permalink ), 307, 'Under Construction Redirect' );
			exit();
		}

	}

}