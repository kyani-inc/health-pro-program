<?php
/**
 * Header Search Overlay.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

?>

<div id="wpex-searchform-overlay" class="header-searchform-wrap wpex-fs-overlay" data-placeholder="<?php echo esc_attr( wpex_get_header_menu_search_form_placeholder() ); ?>" data-disable-autocomplete="true">
	<button class="wpex-close">
		<span class="wpex-close__icon" aria-hidden="true">&times;</span>
		<span class="screen-reader-text"><?php esc_html_e( 'Close search', 'total' ); ?></span>
	</button>
	<div class="wpex-inner wpex-scale">
		<?php wpex_hook_header_search_overlay_top(); ?>
		<div class="wpex-title"><?php esc_html_e( 'Search', 'total' ); ?></div>
		<?php echo wpex_get_header_menu_search_form(); ?>
		<?php wpex_hook_header_search_overlay_bottom(); ?>
	</div>
</div>