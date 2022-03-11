<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}
?>
<table class="woocommerce-cart-form__contents cart-products-table">
<tr class="d-block w-100 woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order ) ); ?>">
	<td class="cart-product-thumbnail-section align-top">
		<span class="product-thumbnail">
				<?php
				$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $product->get_image(), $cart_item, $cart_item_key );

				if ( ! $product_permalink ) {
					echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</span>
	</td>

	<td class="align-top cart-product-info-section" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
		<h2 class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
			<?php
			if ( ! $product_permalink ) {
				echo "<strong>" . $item->get_quantity() . "x </strong>" . wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $product->get_name(), $item, $order ) . '&nbsp;' );
			}

			?>
		</h2>
		<span class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
			<div class="unit-price">Unit Price: $<?php $product_price = $item->get_total( $item );
			$product_quantity = $item->get_quantity( $item);
				echo $unit_price = $product_price / $product_quantity; ?></div>
			Price:
				<?php echo $order->get_formatted_line_subtotal( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
	</td>


</tr>
</table>
