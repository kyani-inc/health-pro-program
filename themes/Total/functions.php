<?php
/**
 * Total WordPress Theme.
 *
 * Theme URI     : https://total.wpexplorer.com/
 * Documentation : https://wpexplorer-themes.com/total/docs/
 * License       : https://themeforest.net/licenses/terms/regular
 * Subscribe     : https://total.wpexplorer.com/newsletter/
 *
 * @author  WPExplorer
 * @package TotalTheme
 * @version 5.3.1
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Define theme constants.
 */

// TotalTheme version.
define( 'TOTAL_THEME_ACTIVE', true );
define( 'WPEX_THEME_VERSION', '5.3.1' );

// Supported Bundled plugin versions.
define( 'WPEX_VC_SUPPORTED_VERSION', '6.8.0' );
define( 'WPEX_THEME_CORE_PLUGIN_SUPPORTED_VERSION', '1.3.2' );

// Theme Branding.
define( 'WPEX_THEME_BRANDING', get_theme_mod( 'theme_branding', 'Total' ) );

// Theme changelog URL.
define( 'WPEX_THEME_CHANGELOG_URL', 'https://wpexplorer-themes.com/total/changelog/' );

// Theme directory location and URL.
define( 'WPEX_THEME_DIR', get_template_directory() );
define( 'WPEX_THEME_URI', get_template_directory_uri() );

// Theme Panel slug and hook prefix.
define( 'WPEX_THEME_PANEL_SLUG', 'wpex-panel' );
define( 'WPEX_ADMIN_PANEL_HOOK_PREFIX', 'theme-panel_page_' . WPEX_THEME_PANEL_SLUG );

// Includes folder.
define( 'WPEX_INC_DIR', trailingslashit( WPEX_THEME_DIR ) . 'inc/' );

// Check if js minify is enabled.
define( 'WPEX_MINIFY_JS', get_theme_mod( 'minify_js_enable', true ) );

// Theme stylesheet and main javascript handles.
define( 'WPEX_THEME_STYLE_HANDLE', 'wpex-style' );
define( 'WPEX_THEME_JS_HANDLE', 'wpex-core' ); //@todo rename to wpex-js?

// Check if certain plugins are enabled.
define( 'WPEX_PTU_ACTIVE', class_exists( 'Post_Types_Unlimited' ) );
define( 'WPEX_VC_ACTIVE', class_exists( 'Vc_Manager' ) );
define( 'WPEX_TEMPLATERA_ACTIVE', class_exists( 'VcTemplateManager' ) );
define( 'WPEX_WOOCOMMERCE_ACTIVE', class_exists( 'WooCommerce' ) );
define( 'WPEX_WPML_ACTIVE', class_exists( 'SitePress' ) );
define( 'WPEX_ELEMENTOR_ACTIVE', did_action( 'elementor/loaded' ) );
define( 'WPEX_BBPRESS_ACTIVE', class_exists( 'bbPress' ) );

// Theme Core post type checks.
define( 'WPEX_PORTFOLIO_IS_ACTIVE', get_theme_mod( 'portfolio_enable', true ) );
define( 'WPEX_STAFF_IS_ACTIVE', get_theme_mod( 'staff_enable', true ) );
define( 'WPEX_TESTIMONIALS_IS_ACTIVE', get_theme_mod( 'testimonials_enable', true ) );

/**
 * Register autoloader.
 */
require_once trailingslashit( WPEX_THEME_DIR ) . 'inc/autoloader.php';

/**
 * All the magic happens here.
 */
TotalTheme\Initialize::instance();

// Add this to your theme's functions.php
function kia_add_script_to_footer(){
	if( ! is_admin() ) { ?>
		<script>
			jQuery(document).ready(function($){
				$('.quantity').on('click', '.plus', function(e) {
					$input = $(this).prev('input.qty');
					var val = parseInt($input.val());
					var step = $input.attr('step');
					step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
					$input.val( val + step ).change();
				});
				$('.quantity').on('click', '.minus',
					function(e) {
						$input = $(this).next('input.qty');
						var val = parseInt($input.val());
						var step = $input.attr('step');
						step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
						if (val > 0) {
							$input.val( val - step ).change();
						}
					});
			});
		</script>
	<?php }
}

/**
 * Remove the Billing VAT field from the checkout form only for Wholesalers.
 */
function custom_override_checkout_fields( $fields ) {

	if ( ! current_user_can( 'um_health-pro' ) && isset( $fields['billing']['billing_dob'] ) ) {
		unset( $fields['billing']['billing_dob'] );
	}

	if ( ! current_user_can( 'um_health-pro' ) && isset( $fields['billing']['billing_company'] ) ) {
		unset( $fields['billing']['billing_company'] );
	}

	if ( ! current_user_can( 'um_health-pro' ) && isset( $fields['billing']['billing_tax'] ) ) {
		unset( $fields['billing']['billing_tax'] );
	}

	if ( ! current_user_can( 'um_health-pro' ) && isset( $fields['billing']['billing_site'] ) ) {
		unset( $fields['billing']['billing_site'] );
	}

	if ( ! current_user_can( 'um_health-pro' ) && isset( $fields['billing']['billing_health_pro_agreement_heading'] ) ) {
		unset( $fields['billing']['billing_health_pro_agreement_heading'] );
	}

	if ( ! current_user_can( 'um_health-pro' ) && isset( $fields['billing']['billing_health_pro_agreement'] ) ) {
		unset( $fields['billing']['billing_health_pro_agreement'] );
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
