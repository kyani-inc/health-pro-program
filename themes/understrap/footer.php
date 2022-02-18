<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$container = get_theme_mod('understrap_container_type');
?>

<?php get_template_part('sidebar-templates/sidebar', 'footerfull'); ?>

<div class="wrapper" id="wrapper-footer">

	<div class="<?php echo esc_attr($container); ?>">

		<div class="row">

			<div class="col-md-12">


				<footer class="site-footer" id="colophon">


					<div class="site-info">

						<div class="wrapper" id="wrapper-footer-full">

							<div class="container" id="footer-full-content">

								<div>
									<div class="row footer-logo">
										<?php dynamic_sidebar('footerlogo') ?>
									</div>

									<div class="row footer-additional-text">
										<?php dynamic_sidebar('footertext') ?>
									</div>
								</div>
							</div>

						</div><!-- #wrapper-footer-full -->

					</div><!-- .site-info -->

				</footer><!-- #colophon -->

			</div>
			<!--col end -->

		</div><!-- row end -->

	</div><!-- container end -->

</div><!-- wrapper end -->


</div><!-- #page we need this extra closing tag here -->

</body>

</html>

<?php wp_footer();
global $rep;
if ($rep->rep_found()) {
	$link = new ShopLink($rep->get_rep_id());
}
?>
<script>
	let shop = document.getElementsByClassName("nav-shoplink")[0].firstElementChild
	shop.href = "<?php echo esc_url_raw( $link->get_all_products_link() ) ?>";
</script>;
<?php get_template_part('sidebar-templates/sidebar', 'footerfull'); ?>


