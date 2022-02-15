<?php
/* Adds Shop Now link to both desktop and mobile */

function navShopLinkShortcode()
{
	// ENQUEUE files
	wp_enqueue_style('navShopLinkStyle', plugins_url() . '/kyani-custom-plugin/includes/navShopLink/assets/style.css');

	// GET current country code
	$current_site_id = get_current_blog_id();
	$current_site_country_code = str_replace("/", "", get_blog_details($current_site_id)->path);
	if ($current_site_country_code === "") {
		$current_site_country_code = "us";
	}

	// GET store links for country
	$store_links = json_decode(file_get_contents(plugins_url() . '/kyani-custom-plugin/assets/data/links/' . $current_site_country_code . '.json'));

	// GET translations for current country
	$locale_translations = '';
	$translations = json_decode(file_get_contents(plugins_url() . '/kyani-custom-plugin/assets/data/translations/' . $current_site_country_code . '.json'));

	// GET current locale for country
	global $TRP_LANGUAGE;
	$current_locale = $TRP_LANGUAGE;

	// SET locale translations for current locale
	foreach ($translations->locales as $locale) {
		if ($locale->locale === $current_locale) {
			$locale_translations = $locale->translations;
		}
	}

	// GET rep Global
	global $rep;

	// IF rep found, set to rep ID ELSE set to default rep for that country
		if ($rep->rep_found()) {
			$rep_id = $rep->get_rep_id();
		} else {
			$rep_id = $store_links->default_rep;
		}

	// SET product link to shopLink for rep
	$product_link = new ShopLink($rep_id);

	// RETURN link for navigation menus
	return '<li class="nav-item"><a class="navShopLink nav-link" href="' . $product_link->get_all_products_link() . '">' . $locale_translations->shop_now . '</a></li>';
}

add_shortcode('navShopLink', 'navShopLinkShortcode');
