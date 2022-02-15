<?php
namespace TotalThemeCore\WPBakery\Templatera;

defined( 'ABSPATH' ) || exit;

/**
 * Enable Frontend editing for Templatera templates.
 *
 * Important: Templatera runs on init hook priority 8.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
class Enable_Frontend {

	/**
	 * Get things started
	 */
	public function __construct() {

		// Inserts the front-end editor button for templatera.
		add_action( 'admin_print_footer_scripts', array( $this, 'add_editor_button' ), PHP_INT_MAX );

		// We only have to register templatera when dealing with the front-end editor.
		if ( $this->is_frontend() ) {
			add_action( 'init', array( $this, 'register_post_type' ), 0 );
			add_filter( 'register_post_type_args', array( $this, 'filter_post_type_args' ), 10, 2 );
			add_action( 'init', array( $this, 'enable_editor' ), 8 ); // note: must use same priority as templatera.
		}

	}

	/**
	 * Check if currently in frontend mode.
	 */
	private function is_frontend() {
		if ( function_exists( 'vc_is_inline' ) ) {
			return vc_is_inline();
		}
		return false;
	}

	/**
	 * Adds the front-end editor button.
	 */
	public function add_editor_button() {
		if ( ! function_exists( 'vc_frontend_editor' ) || ! function_exists( 'templatera_init' ) ) {
			return;
		}
		global $pagenow;
		$template_edit = 'post.php' == $pagenow && isset( $_GET['post'] ) && 'templatera' === get_post_type( $_GET['post'] );
		if ( ! $template_edit ) {
			return;
		}
		$front_end_url = vc_frontend_editor()->getInlineUrl(); ?>
		<script>
			( function ( $ ) {
				if ( typeof vc !== 'undefined' ) {
					vc.events.on( 'vc:access:backend:ready', function ( access ) {
						var vcSwitch = $( '.composer-inner-switch' );
						if ( vcSwitch.length ) {
							vcSwitch.append( '<a class="wpb_switch-to-front-composer" href="<?php echo esc_url( $front_end_url ); ?>">' + window.i18nLocale.main_button_title_frontend_editor + '</a>' );
						}
					} );
				}
			} ) ( window.jQuery );
		</script>
	<?php }

	/**
	 * Register Templatera Post Type.
	 */
	public function register_post_type() {
		register_post_type( 'templatera' );
	}

	/**
	 * Enable front-end editor.
	 */
	public function enable_editor() {
		if ( 'templatera' === $this->get_current_post_type() && $this->user_permissions_check() ) {
			add_filter( 'vc_role_access_with_frontend_editor_get_state', '__return_true' );
		}
	}

	/**
	 * Templatera post type args.
	 */
	public function filter_post_type_args( $args, $post_type ) {
		if ( 'templatera' === $post_type ) {
			//$args['supports'] = array( 'title', 'editor', 'revisions' );
			$args['public']             = true;
			$args['publicly_queryable'] = true;
			$args['map_meta_cap']       = true;
		}
		return $args;
	}

	/**
	 * Get the post type currently being edited.
	 */
	private function get_current_post_type() {
		$post_type = '';
		if ( function_exists( 'vc_get_param' ) ) {
			if ( vc_get_param( 'post_type' ) ) {
				$post_type = vc_get_param( 'post_type' );
			} elseif ( vc_get_param( 'post' ) ) {
				$post_type = get_post_type( (int) vc_get_param( 'post' ) );
			}
		}
		if ( ! $post_type ) {
			$post_type = get_post_type();
		}
		return $post_type;
	}

	/**
	 * Security check to make sure user can edit posts/pages.
	 */
	private function user_permissions_check() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			die( 'You don\'t have permission to edit this template.' );
		}
		return true;
	}

}