<?php
/**
 * Cart overlay.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

?>

<div id="wpex-cart-overlay" class="wpex-fs-overlay">
	<button class="wpex-close">
		<span class="wpex-close__icon" aria-hidden="true">&times;</span>
		<span class="screen-reader-text"><?php esc_html_e( 'Close cart', 'total' ); ?></span>
	</button>
	<div class="wpex-inner wpex-scale">
		<?php the_widget(
			'WC_Widget_Cart',
			array(),
			array(
				'before_title' => '<span class="widgettitle screen-reader-text">',
				'after_title' => '</span>'
			)
		); ?>
	</div>
</div>