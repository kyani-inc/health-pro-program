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
</div><!-- #page we need this extra closing tag here -->

</body>

</html>
