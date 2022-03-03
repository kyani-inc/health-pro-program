<?php
//code for cart addon
add_shortcode('woo_cart_but', 'woo_cart_but');
/**
 * Create Shortcode for WooCommerce Cart Menu Item
 */
function woo_cart_but()
{
	ob_start();
	$cart_count = WC()
		->cart->cart_contents_count; // Set variable for cart item count
	$cart_url = wc_get_cart_url(); // Set Cart URL

	?>
	<a class="menu-item cart-contents" href="<?php echo $cart_url; ?>" title="My Basket">
		<?php
		if ($cart_count > 0)
		{
			?>
			<span class="cart-contents-count"><?php echo $cart_count; ?></span>
			<?php
		}
		?>
	</a>
	<?php
	return ob_get_clean();
}

//Add a filter to get the cart count
add_filter('woocommerce_add_to_cart_fragments', 'woo_cart_but_count');
/**
 * Add AJAX Shortcode when cart contents update
 */
function woo_cart_but_count($fragments)
{
	ob_start();
	$cart_count = WC()
		->cart->cart_contents_count;
	$cart_url = wc_get_cart_url();
	?>
	<a class="cart-contents menu-item" href="<?php echo $cart_url; ?>" title="<?php _e('View your shopping cart'); ?>">
		<?php
		if ($cart_count > 0)
		{
			?>
			<span class="cart-contents-count"><?php echo $cart_count; ?></span>
			<?php
		}
		?></a>
	<?php
	$fragments['a.cart-contents'] = ob_get_clean();
	return $fragments;
}

function wooc_extra_register_fields() {
	global $wp_roles; ?>
	<input class="invisible" id="reg_role" name="role" class="input" value="customer">
	<script>	if (document.cookie.indexOf('user') > -1 ) {
			document.getElementById("reg_role").value = "um_health-pro";

		}</script>
	<?php
}
add_action( 'woocommerce_register_form_end', 'wooc_extra_register_fields' );

//saving role
add_action( 'woocommerce_created_customer', 'update_user_role' );
function update_user_role( $user_id ) {
	$user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $_POST['role'] ) );
}

// Minimum CSS to remove +/- default buttons on input field type number
add_action( 'wp_head' , 'custom_quantity_fields_css' );
function custom_quantity_fields_css(){
	?>
	<style>
		.quantity input::-webkit-outer-spin-button,
		.quantity input::-webkit-inner-spin-button {
			display: none;
			margin: 0;
		}
		.quantity input.qty {
			appearance: textfield;
			-webkit-appearance: none;
			-moz-appearance: textfield;
		}
	</style>
	<?php
}


add_action( 'wp_footer' , 'custom_quantity_fields_script' );
function custom_quantity_fields_script(){
	?>
	<script type='text/javascript'>
		jQuery( function( $ ) {
			if ( ! String.prototype.getDecimals ) {
				String.prototype.getDecimals = function() {
					var num = this,
						match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
					if ( ! match ) {
						return 0;
					}
					return Math.max( 0, ( match[1] ? match[1].length : 0 ) - ( match[2] ? +match[2] : 0 ) );
				}
			}
			// Quantity "plus" and "minus" buttons
			$( document.body ).on( 'click', '.plus, .minus', function() {
				var $qty        = $( this ).closest( '.quantity' ).find( '.qty'),
					currentVal  = parseFloat( $qty.val() ),
					max         = parseFloat( $qty.attr( 'max' ) ),
					min         = parseFloat( $qty.attr( 'min' ) ),
					step        = $qty.attr( 'step' );

				// Format values
				if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
				if ( max === '' || max === 'NaN' ) max = '';
				if ( min === '' || min === 'NaN' ) min = 0;
				if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

				// Change the value
				if ( $( this ).is( '.plus' ) ) {
					if ( max && ( currentVal >= max ) ) {
						$qty.val( max );
					} else {
						$qty.val( ( currentVal + parseFloat( step )).toFixed( step.getDecimals() ) );
					}
				} else {
					if ( min && ( currentVal <= min ) ) {
						$qty.val( min );
					} else if ( currentVal > 0 ) {
						$qty.val( ( currentVal - parseFloat( step )).toFixed( step.getDecimals() ) );
					}
				}

				// Trigger change event
				$qty.trigger( 'change' );
			});
		});
	</script>
	<?php
}


/**
 * @snippet       Calculate Subtotal Based on Quantity - WooCommerce Single Product
 * @author        Codeithub
 */

add_action( 'woocommerce_after_add_to_cart_button', 'Codeithub_product_price_recalculate' );

function codeithub_product_price_recalculate() {
	global $product;
	$price = $product->get_price(); WC()->cart->cart_contents_total;
	$currency = get_woocommerce_currency_symbol();
	wc_enqueue_js( "
      $('[name=quantity]').on('input change', function() {
         var qty = $(this).val();
         var price = '" . esc_js( $price ) . "';
         var price_string = (price*qty).toFixed(2);
         $('#subtot > span').html('(" . esc_js( $currency ) . "'+price_string + ')');
      }).change();
   " );
}

add_filter( 'woocommerce_checkout_fields', 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
	if( current_user_can('customer') ){
		unset($fields['billing']['billing_company']);
		unset($fields['billing']['billing_dob']);
		unset($fields['billing']['billing_tax']);
		unset($fields['billing']['billing_site']);
		unset($fields['billing']['billing_health_pro_agreement_heading']);
		unset($fields['billing']['billing_health_pro_agreement']);
	}
	return $fields;
}

add_action( 'wp_footer', 'cart_update_qty_script' );
function cart_update_qty_script() {
	if (is_cart()) :
		?>
		<script>
			jQuery( 'div.woocommerce' ).on( 'change', '.qty', function () {
				setTimeout(function() {
					location.reload();
				}, 2500);
			} );
			jQuery( '.product-remove a' ).on( 'click', function () {
				setTimeout(function() {
					location.reload();
				}, 2500);
			} );
		</script>
	<?php
	endif;
}
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
	<h3><?php _e("Extra profile information", "blank"); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="address"><?php _e("Address"); ?></label></th>
			<td>
				<input type="text" name="address" id="address" value="<?php echo esc_attr( get_the_author_meta( 'address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e("Please enter your address."); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="city"><?php _e("City"); ?></label></th>
			<td>
				<input type="text" name="city" id="city" value="<?php echo esc_attr( get_the_author_meta( 'city', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e("Please enter your city."); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="province"><?php _e("Province"); ?></label></th>
			<td>
				<input type="text" name="province" id="province" value="<?php echo esc_attr( get_the_author_meta( 'province', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e("Please enter your province."); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="postalcode"><?php _e("Postal Code"); ?></label></th>
			<td>
				<input type="text" name="postalcode" id="postalcode" value="<?php echo esc_attr( get_the_author_meta( 'postalcode', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e("Please enter your postal code."); ?></span>
			</td>
		</tr>
	</table>
<?php }

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

	update_user_meta( $user_id, 'address', $_POST['address'] );
	update_user_meta( $user_id, 'city', $_POST['city'] );
	update_user_meta( $user_id, 'province', $_POST['province'] );
	update_user_meta( $user_id, 'postalcode', $_POST['postalcode'] );
}

remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'woocommerce_after_order_notes', 'woocommerce_checkout_payment', 20 );

function wc_discount_total() {
	global $woocommerce;
	$discount_total = 0;

	foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values) {

		$_product = $values['data'];

		if ( $_product->is_on_sale() ) {
			$regular_price = $_product->get_regular_price();
			$sale_price = $_product->get_sale_price();
			$discount = ($regular_price - $sale_price) * $values['quantity'];
			$discount_total += $discount;
		}
	}
	if ( $discount_total > 0 ) {
		return
				wc_price( $discount_total + $woocommerce->cart->discount_cart );
	} else {
		return NULL;
	}
}

function wc_original_total_price() {
	global $woocommerce;
	$discount_total = 0;

	foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values) {

		$_product = $values['data'];

		if ( $_product->is_on_sale() ) {
			$regular_price = $_product->get_regular_price();
			$sale_price = $_product->get_sale_price();
			$discount = ($regular_price - $sale_price) * $values['quantity'];
			$discount_total += $discount;
		}
	}

	$original = $discount_total + WC()->cart->get_cart_contents_total();
	return wc_price($original);
}
