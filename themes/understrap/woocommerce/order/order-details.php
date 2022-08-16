<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.6.0
 */

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $order ) {
	return;
}

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>
<section class="woocommerce-order-details d-flex">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>
	<div class="w-50 p-3 checkout-half">
	<table class="shop_table woocommerce-checkout-review-order-table woocommerce-table woocommerce-table--order-details shop_table order_details">
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
			<?php if (WC()->cart->get_cart_shipping_total() != "$0") { ?>
				<td class="product-total"><?php esc_html_e('Shipping', 'woocommerce'); ?></td>
			<?php } ?>
			<td class="product-total"><?php esc_html_e( 'Taxes', 'woocommerce' ); ?></td>
			<td class="product-total"><strong><?php esc_html_e( 'Total price', 'woocommerce' ); ?></strong></td>
		</tr>
		<tr>
			<td><?php echo $order->get_item_count();; ?></td>
				<td>$<?php echo $order->get_subtotal(); ?></td>
			<?php if (wc_discount_total() !== NULL) { ?>
				<td>-<?php echo wc_discount_total(); ?></td>
			<?php } ?>
			<?php if (WC()->cart->get_cart_shipping_total() != "$0") { ?>
				<td><?php echo $order->get_shipping_to_display(); ?></td>
			<?php } ?>
			<td>$<?php echo $order->get_total_tax(); ?></td>
			<td><strong><?php echo $order->get_formatted_order_total(); ?></strong></td>
		</tr>
		<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>
		</tbody>
	</table>
</div>
<div class="w-50 p-3 checkout-half">
	<?php
	do_action( 'woocommerce_order_details_before_order_table_items', $order );

	foreach ( $order_items as $item_id => $item ) {
		$product = $item->get_product();

		wc_get_template(
				'order/order-details-item.php',
				array(
						'order'              => $order,
						'item_id'            => $item_id,
						'item'               => $item,
						'show_purchase_note' => $show_purchase_note,
						'purchase_note'      => $product ? $product->get_purchase_note() : '',
						'product'            => $product,
				)
		);
	}

	do_action( 'woocommerce_order_details_after_order_table_items', $order );
	?>
</div>
</section>

<?php
/**
 * Action hook fired after the order details.
 *
 * @since 4.4.0
 * @param WC_Order $order Order data.
 */
do_action( 'woocommerce_after_order_details', $order );

if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}
