<?php
namespace TotalTheme\Integration\WooCommerce;

defined( 'ABSPATH' ) || exit;

/**
 * Perform all main WooCommerce configurations for this theme.
 *
 * @package TotalTheme
 * @subpackage Integration/WooCommerce
 * @version 5.3
 */
final class WooCommerce_Advanced {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of WooCommerce_Advanced.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->define_constants();
			static::$instance->include_functions();
			static::$instance->initiate_classes();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Define constants.
	 *
	 * @since 5.0
	 */
	public function define_constants() {
		define( 'WPEX_WOO_CONFIG_DIR', WPEX_INC_DIR . 'integration/woocommerce/' );
	}

	/**
	 * Include functions.
	 *
	 * @since 5.0
	 */
	public function include_functions() {
		require_once WPEX_WOO_CONFIG_DIR . 'actions.php';
		require_once WPEX_WOO_CONFIG_DIR . 'function-overrides.php';
		require_once WPEX_WOO_CONFIG_DIR . 'menu-cart.php';
	}

	/**
	 * Initiate classes.
	 *
	 * @since 5.0
	 */
	public function initiate_classes() {

		Accent_Colors::instance();

		if ( apply_filters( 'wpex_woocommerce_maybe_modify_product_entry', true ) ) {
			Product_Entry::instance();
		}

		if ( apply_filters( 'wpex_woocommerce_maybe_modify_product_gallery', true ) ) {
			Product_Gallery::instance();
		}

		if ( ! get_theme_mod( 'woo_dynamic_image_resizing', false ) ) {
			Thumbnails::instance();
		}

	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 5.0
	 */
	public function init_hooks() {

		// Add theme support.
		add_action( 'after_setup_theme', array( $this, 'theme_support' ) );

		// Add Customizer settings.
		add_filter( 'wpex_customizer_panels', array( $this, 'customizer_settings' ) );

		// These filters/actions must run on init.
		add_action( 'init', array( $this, 'init' ) );

		// Disable WooCommerce main page title.
		add_filter( 'woocommerce_show_page_title', '__return_false' );

		// Remove Woo Styles.
		// Can be disabled by the filter or by disabling Advanced Integration via the Total theme panel.
		if ( $this->maybe_remove_styles() ) {
			add_action( 'woocommerce_enqueue_styles', array( $this, 'disable_styles' ), PHP_INT_MAX );
		}

		// Load customs scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'add_custom_scripts' ) );

		// Alter the sale tag.
		add_filter( 'woocommerce_sale_flash', array( $this, 'woocommerce_sale_flash' ), 10, 3 );

		// Alter shop posts per page.
		add_filter( 'loop_shop_per_page', array( $this, 'loop_shop_per_page' ), 20 );

		// Alter shop columns.
		add_filter( 'loop_shop_columns', array( $this, 'loop_shop_columns' ) );

		// Tweak Woo pagination args.
		add_filter( 'woocommerce_pagination_args', array( $this, 'pagination_args' ) );

		// Alter shop page redirect.
		add_filter( 'woocommerce_continue_shopping_redirect', array( $this, 'continue_shopping_redirect' ) );

		// Alter product tag cloud widget args
		add_filter( 'woocommerce_product_tag_cloud_widget_args', array( $this, 'tag_cloud_widget_args' ) );

		// Add new typography settings.
		add_filter( 'wpex_typography_settings', array( $this, 'typography_settings' ), 60 );

		// Alter the comment form args.
		add_filter( 'woocommerce_product_review_comment_form_args', array( $this, 'comment_form_args' ) );

		// Alter orders per-page on account page.
		add_filter( 'woocommerce_my_account_my_orders_query', array( $this, 'woocommerce_my_account_my_orders_query' ) );

	} // End init_hooks

	/*-------------------------------------------------------------------------------*/
	/* - Start Class Functions
	/*-------------------------------------------------------------------------------*/

	/**
	 * Add theme support.
	 */
	public function theme_support() {
		add_theme_support( 'woocommerce' );
	}

	/**
	 * Adds Customizer settings for WooCommerce.
	 *
	 * @since 4.0
	 */
	public function customizer_settings( $panels ) {
		$branding = ( $branding = wpex_get_theme_branding() ) ? ' (' . $branding . ')' : '';
		$panels['woocommerce'] = array(
			'title'    => esc_html__( 'WooCommerce', 'total' ) . $branding,
			'settings' => WPEX_WOO_CONFIG_DIR . 'customize/advanced-settings.php'
		);
		return $panels;
	}

	/**
	 * Run actions on init.
	 *
	 * @since 2.0.0
	 */
	public function init() {

		// Remove single meta.
		if ( ! get_theme_mod( 'woo_product_meta', true ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		}

		// Remove result count if disabled.
		if ( ! get_theme_mod( 'woo_shop_result_count', true ) ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		}

		// Remove orderby if disabled.
		if ( ! get_theme_mod( 'woo_shop_sort', true ) ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		}

		// Move tabs.
		// Add after meta which is set to 40.
		if ( 'right' === get_theme_mod( 'woo_product_tabs_position' ) ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 41 );
		}

	}

	/**
	 * Add Custom scripts.
	 *
	 * @since 2.0.0
	 */
	public function add_custom_scripts() {

		if ( $this->maybe_remove_styles() ) {

			wp_enqueue_style(
				'wpex-woocommerce',
				wpex_asset_url( 'css/wpex-woocommerce.css' ),
				array(),
				WPEX_THEME_VERSION
			);

			wp_style_add_data( 'wpex-woocommerce', 'rtl', 'replace' );

		}

		if ( apply_filters( 'wpex_custom_woo_scripts', true ) ) {

			wp_enqueue_script(
				'wpex-wc-functions',
				wpex_asset_url( 'js/dynamic/woocommerce/wpex-wc-functions.min.js' ),
				array( 'jquery' ),
				WPEX_THEME_VERSION,
				true
			);

			$script_data = array(
				'quantityButtons' => 'div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)',
			);

			if ( $this->has_added_to_cart_notice() ) {
				$notice = esc_html__( 'was added to your shopping cart.', 'total' );
				$script_data['addedToCartNotice'] = apply_filters( 'wpex_woocommerce_added_to_cart_notice', $notice );
			}

			wp_localize_script(
				'wpex-wc-functions',
				'wpex_wc_params',
				$script_data
			);

		}

	}

	/**
	 * Change onsale text.
	 *
	 * @since 2.0.0
	 */
	public function woocommerce_sale_flash( $text, $post, $_product ) {
		$text = wpex_get_translated_theme_mod( 'woo_sale_flash_text' );
		if ( ! $text ) {
			$text = esc_html__( 'Sale', 'total' );
		}
		return '<span class="onsale">' . esc_html( $text ) . '</span>';
	}

	/**
	 * Returns correct posts per page for the shop.
	 *
	 * @since 3.0.0
	 */
	public function loop_shop_per_page() {
		$posts_per_page = get_theme_mod( 'woo_shop_posts_per_page' );
		if ( ! $posts_per_page ) {
			$posts_per_page = '12';
		}
		return $posts_per_page;
	}

	/**
	 * Change products per row for the main shop.
	 *
	 * @since 2.0.0
	 */
	public function loop_shop_columns() {
		$columns = wpex_get_array_first_value( get_theme_mod( 'woocommerce_shop_columns', '4' ) );
		if ( ! $columns ) {
			$columns = '4'; // always needs a fallback.
		}
		return $columns;
	}

	/**
	 * Tweaks pagination arguments.
	 *
	 * @since 2.0.0
	 */
	public function pagination_args( $args ) {
		$arrow_style = ( $arrow_style = get_theme_mod( 'pagination_arrow' ) ) ? $arrow_style : 'angle';
		$args['prev_text'] = '<i class="ticon ticon-' . sanitize_html_class( $arrow_style ) . '-left" aria-hidden="true"></i>';
		$args['next_text'] = '<i class="ticon ticon-' . sanitize_html_class( $arrow_style ) . '-right" aria-hidden="true"></i>';
		return $args;
	}

	/**
	 * Alter continue shoping URL.
	 *
	 * @since 2.0.0
	 */
	public function continue_shopping_redirect( $return_to ) {
		$shop_id = wc_get_page_id( 'shop' );
		if ( $shop_id ) {
			$shop_id = wpex_parse_obj_id( $shop_id, 'page' );
			$return_to = get_permalink( $shop_id );
		}
		return $return_to;
	}

	/**
	 * Alter product tag cloud widget args.
	 *
	 * @since 4.2
	 */
	public function tag_cloud_widget_args( $args ) {
		$args['largest']  = '1';
		$args['smallest'] = '1';
		$args['unit']     = 'em';
		return $args;
	}

	/**
	 * Add typography options for the WooCommerce product title.
	 *
	 * @since 3.0.0
	 */
	public function typography_settings( $settings ) {
		$settings['woo_entry_title'] = array(
			'label' => esc_html__( 'WooCommerce Entry Title', 'total' ),
			'target' => '.woocommerce ul.products li.product .woocommerce-loop-product__title,.woocommerce ul.products li.product .woocommerce-loop-category__title',
			'margin' => true,
		);
		$settings['woo_product_title'] = array(
			'label' => esc_html__( 'WooCommerce Product Title', 'total' ),
			'target' => '.woocommerce div.product .product_title',
			'margin' => true,
		);
		$settings['woo_upsells_related_title'] = array(
			'label' => esc_html__( 'WooCommerce Section Heading', 'total' ),
			'target' => '.up-sells > h2, .related.products > h2, .woocommerce-tabs .panel > h2',
			'margin' => true,
		);
		return $settings;
	}

	/**
	 * Tweak comment form args.
	 *
	 * @since 4.0
	 */
	public function comment_form_args( $args ) {
		$args['title_reply'] = esc_html__( 'Leave a customer review', 'total' );
		return $args;
	}

	/**
	 * Alter orders per-page on account page.
	 *
	 * @since 4.0
	 */
	public function woocommerce_my_account_my_orders_query( $args ) {
		$args['limit'] = 20;
		return $args;
	}

	/**
	 * Check if we should remove the core WooCommerce styles.
	 *
	 * @since 5.0
	 */
	public function maybe_remove_styles() {
		return (bool) apply_filters( 'wpex_custom_woo_stylesheets', true ); // @todo rename filter
	}

	/**
	 * Check if the added to cart notice is enabled.
	 *
	 * @since 5.0
	 */
	public function has_added_to_cart_notice() {
		if ( get_theme_mod( 'woo_show_entry_add_to_cart', true ) && get_theme_mod( 'woo_added_to_cart_notice', true ) ) {
			return true;
		}
	}

	/**
	 * Disable WooCommerce Styles.
	 *
	 * @since 5.1.1
	 */
	public function disable_styles( $styles ) {
		if ( is_array( $styles ) ) {
			unset( $styles['woocommerce-general'] );
			unset( $styles['woocommerce-layout'] );
			unset( $styles['woocommerce-smallscreen'] );
		}
		return $styles;
	}

}