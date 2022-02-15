<?php
/**
 * Footer Layout
 *
 * @package TotalTheme
 * @subpackage Partials
 * @version 5.0.8
 */

defined( 'ABSPATH' ) || exit;

?>

<?php wpex_hook_footer_before(); ?>

<?php if ( ! wpex_theme_do_location( 'footer' ) ) : ?>

	<?php if ( wpex_footer_has_widgets() ) : ?>

	    <footer id="footer" class="<?php echo esc_attr( wpex_footer_class() ); ?>"<?php wpex_schema_markup( 'footer' ); ?>>

	        <?php wpex_hook_footer_top(); ?>

	        <div id="footer-inner" class="site-footer-inner container wpex-pt-40 wpex-clr"><?php

	        	wpex_hook_footer_inner(); // widgets are added via this hook

	        ?></div>

	        <?php wpex_hook_footer_bottom(); ?>

	    </footer>

	<?php endif; ?>

<?php endif; ?>

<?php wpex_hook_footer_after(); ?>