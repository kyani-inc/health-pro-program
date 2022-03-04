<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */
defined( 'ABSPATH' ) || exit;
?>
<table class="shop_table woocommerce-checkout-review-order-table p-3">
	<tbody>
	<thead>
	<th><?php esc_html_e( 'Order Summary', 'woocommerce' ); ?></th>
	</thead>
	<tr>
		<td class="product-name"><?php esc_html_e( 'Products', 'woocommerce' ); ?></td>
		<?php if (wc_discount_total() !== NULL) { ?>
		<td class="product-total"><?php esc_html_e( 'Original total price', 'woocommerce' ); ?></td>
		<td class="product-total"><?php esc_html_e( 'Discount', 'woocommerce' ); ?></td>
		<?php } ?>
		<td class="product-total"><strong><?php esc_html_e( 'Total price', 'woocommerce' ); ?></strong></td>
	</tr>
	<tr>
		<td><?php echo WC()->cart->cart_contents_count; ?></td>
		<?php if (wc_discount_total() !== NULL) { ?>
		<td><?php echo wc_original_total_price(); ?></td>
		<td><?php echo wc_discount_total(); ?></td>
		<?php } ?>
		<td><strong><?php echo WC()->cart->get_cart_total() ?></strong></td>
	</tr>
		<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>
	</tbody>
</table>
