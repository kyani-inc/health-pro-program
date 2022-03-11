<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;
$price = WC()->cart->total;
$currency = get_woocommerce_currency_symbol();
?>
<div>

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>
	<table class="shop_table woocommerce-checkout-review-order-table p-3">
		<tbody>
		<thead>
		<th><?php esc_html_e( 'Order Summary', 'woocommerce' ); ?></th>
		</thead>
		<tr>
			<td class="product-name"><?php esc_html_e( 'Products', 'woocommerce' ); ?></td>
				<td class="product-total"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></td>
			<?php if (wc_discount_total() !== NULL) { ?>
				<td class="product-total"><?php esc_html_e( 'Discount', 'woocommerce' ); ?></td>
			<?php } ?>
			<td class="product-total"><?php esc_html_e( 'Tax', 'woocommerce' ); ?></td>
			<td class="product-total"><strong><?php esc_html_e( 'Total price', 'woocommerce' ); ?></strong></td>
		</tr>
		<tr>
			<td><?php echo WC()->cart->cart_contents_count; ?></td>
				<td><?php echo wc_original_total_price(); ?></td>
			<?php if (wc_discount_total() !== NULL) { ?>
				<td><?php echo wc_discount_total(); ?></td>
			<?php } ?>
			<td><?php echo WC()->cart->get_taxes(); ?></td>
			<td><strong><?php echo WC()->cart->get_cart_total() ?></strong></td>
		</tr>
		<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>
		</tbody>
	</table>

	<div class="wc-proceed-to-checkout">

		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
