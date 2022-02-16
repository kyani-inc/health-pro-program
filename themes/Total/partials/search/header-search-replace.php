<?php
/**
 * Site header search replace.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

?>

<div id="searchform-header-replace" class="header-searchform-wrap wpex-clr" data-placeholder="<?php echo esc_attr( wpex_get_header_menu_search_form_placeholder() ); ?>" data-disable-autocomplete="true">
	<?php echo wpex_get_header_menu_search_form(); ?>
	<button id="searchform-header-replace-close" class="wpex-user-select-none">
        <span class="searchform-header-replace-close__icon" aria-hidden="true">&times;</span>
        <span class="screen-reader-text"><?php esc_html_e( 'Close search', 'total' ); ?></span>
    </button>
</div>