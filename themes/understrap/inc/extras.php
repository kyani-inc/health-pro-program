<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_filter( 'body_class', 'understrap_body_classes' );

if ( ! function_exists( 'understrap_body_classes' ) ) {
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	function understrap_body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		return $classes;
	}
}

// Removes tag class from the body_class array to avoid Bootstrap markup styling issues.
add_filter( 'body_class', 'understrap_adjust_body_class' );

if ( ! function_exists( 'understrap_adjust_body_class' ) ) {
	/**
	 * Setup body classes.
	 *
	 * @param string $classes CSS classes.
	 *
	 * @return mixed
	 */
	function understrap_adjust_body_class( $classes ) {

		foreach ( $classes as $key => $value ) {
			if ( 'tag' == $value ) {
				unset( $classes[ $key ] );
			}
		}

		return $classes;

	}
}

// Filter custom logo with correct classes.
add_filter( 'get_custom_logo', 'understrap_change_logo_class' );

if ( ! function_exists( 'understrap_change_logo_class' ) ) {
	/**
	 * Replaces logo CSS class.
	 *
	 * @param string $html Markup.
	 *
	 * @return mixed
	 */
	function understrap_change_logo_class( $html ) {

		$html = str_replace( 'class="custom-logo"', 'class="img-fluid"', $html );
		$html = str_replace( 'class="custom-logo-link"', 'class="navbar-brand custom-logo-link"', $html );
		$html = str_replace( 'alt=""', 'title="Home" alt="logo"', $html );

		return $html;
	}
}

/**
 * Display navigation to next/previous post when applicable.
 */

if ( ! function_exists( 'understrap_post_nav' ) ) {
	function understrap_post_nav() {
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}
		?>
		<nav class="container navigation post-navigation">
			<h2 class="sr-only"><?php esc_html_e( 'Post navigation', 'understrap' ); ?></h2>
			<div class="row nav-links justify-content-between">
				<?php
				if ( get_previous_post_link() ) {
					previous_post_link( '<span class="nav-previous">%link</span>', _x( '<i class="fa fa-angle-left"></i>&nbsp;%title', 'Previous post link', 'understrap' ) );
				}
				if ( get_next_post_link() ) {
					next_post_link( '<span class="nav-next">%link</span>', _x( '%title&nbsp;<i class="fa fa-angle-right"></i>', 'Next post link', 'understrap' ) );
				}
				?>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
}

if ( ! function_exists( 'understrap_pingback' ) ) {
	/**
	 * Add a pingback url auto-discovery header for single posts of any post type.
	 */
	function understrap_pingback() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="' . esc_url( get_bloginfo( 'pingback_url' ) ) . '">' . "\n";
		}
	}
}
add_action( 'wp_head', 'understrap_pingback' );

if ( ! function_exists( 'understrap_mobile_web_app_meta' ) ) {
	/**
	 * Add mobile-web-app meta.
	 */
	function understrap_mobile_web_app_meta() {
		echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
		echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
		echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr( get_bloginfo( 'name' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'understrap_mobile_web_app_meta' );

if ( ! function_exists( 'understrap_default_body_attributes' ) ) {
	/**
	 * Adds schema markup to the body element.
	 *
	 * @param array $atts An associative array of attributes.
	 * @return array
	 */
	function understrap_default_body_attributes( $atts ) {
		$atts['itemscope'] = '';
		$atts['itemtype']  = 'http://schema.org/WebSite';
		return $atts;
	}
}
add_filter( 'understrap_body_attributes', 'understrap_default_body_attributes' );

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
	<p class="form-row form-row-wide">
		<input id="reg_role" name="role" class="input">
		<script>	if (document.cookie.indexOf('user') > -1 ) {
				document.getElementById("reg_role").value = "um_health-pro";

			}</script>
	</p>
	<?php
}
add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );

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
	$price = $product->get_price();
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
