<?php
/**
 * Helper functions for custom WPBakery modules.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return asset URL.
 *
 * @since 1.2.8
 */
function vcex_wpbakery_asset_url( $part = '' ) {
	return TTC_PLUGIN_DIR_URL . 'inc/wpbakery/assets/' . $part;
}