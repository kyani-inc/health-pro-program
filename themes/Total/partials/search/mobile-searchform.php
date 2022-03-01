<?php
/**
 * Mobile menu searchform.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.3.1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Filters the mobile menu searchform placeholder text.
 *
 * @param string $text
 */
$placeholder = apply_filters( 'wpex_mobile_searchform_placeholder', esc_html__( 'Search', 'total' ) );

/**
 * Filters the mobile menu searchform action attribute.
 *
 * @param string $action
 * @param todo add new wpex_mobile_searchform_action_attribute filter.
 */
$action = apply_filters( 'wpex_search_action', esc_url( home_url( '/' ) ), 'mobile' );

?>

<div id="mobile-menu-search" class="wpex-hidden wpex-clr">
	<form method="get" action="<?php echo esc_attr( $action ); ?>" class="mobile-menu-searchform">
		<label>
			<span class="screen-reader-text"><?php echo esc_html( $placeholder ); ?></span>
			<input type="search" name="s" autocomplete="off" placeholder="<?php echo esc_attr( $placeholder ); ?>">
			<?php if ( defined( 'ICL_LANGUAGE_CODE' ) ) { ?>
				<input type="hidden" name="lang" value="<?php echo( ICL_LANGUAGE_CODE ); ?>">
			<?php } ?>
			<?php if ( WPEX_WOOCOMMERCE_ACTIVE && get_theme_mod( 'woo_header_product_searchform', false ) ) { ?>
				<input type="hidden" name="post_type" value="product">
			<?php } ?>
		</label>
		<button type="submit" class="searchform-submit"><?php wpex_theme_icon_html( 'search' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Submit', 'total' ); ?></span></button>
	</form>
</div>