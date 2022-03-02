<?php

/*
 * Generates the shop links for each country
 */

class ShopLink
{
	private $rep, $default_rep, $join_locale, $country;
	private $nitro_nutrition;
	private $sunrise, $nitro, $nitroxtreme, $sunset, $hl5, $fit20, $core140, $on, $origin, $restore;
	private $shop_type, $country_code, $enabled, $redirectURL;

	function __construct($repID)
	{
		// get current site country code
		$current_site_id = get_current_blog_id();
		$current_site_country_code = str_replace("/", "", get_blog_details($current_site_id)->path);

		if ($current_site_country_code === "") {
			$current_site_country_code = "us";
		}
		// get json object for specific country
		$store_links = json_decode(file_get_contents(dirname(__DIR__) . '/assets/data/links/' . $current_site_country_code . '.json'));

		// get sites current locale code
		global $TRP_LANGUAGE;
		$current_locale = $TRP_LANGUAGE;

		// select correct locale information
		foreach ($store_links->locales as $locale) {
			if ($locale->locale === $current_locale) {
				$this->join_locale = $locale->join_locale;
			}
		}

		// Creates redirectURL if shop is not enabled
		$enabled = TRUE;
		$redirectURL = '';
		$str = file_get_contents('https://s3.amazonaws.com/data.kyani.net/countries/shop.json');
		$json = json_decode($str, true); // decode the JSON into an associative array

		foreach ($json as $country) {
			if ($country['code'] === $current_site_country_code) {
				if ($country['enabled'] != '11') {
					$redirectURL = $country['redirectURL'];
					$enabled = FALSE;
				};
			};

		}

		$this->country_code = $current_site_country_code;
		$this->enabled = $enabled;
		$this->redirectURL = $redirectURL;

		// add correct values to variables
		$this->default_rep = $store_links->default_rep;
		$this->country = $store_links->country;

		// add category codes
		$this->nitro_nutrition = $store_links->nitro_nutrition;

		// add product codes
		$this->sunrise = $store_links->sunrise;
		$this->nitro = $store_links->nitro;
		$this->nitroxtreme = $store_links->nitroxtreme;
		$this->sunset = $store_links->sunset;
		$this->hl5 = $store_links->hl5;
		$this->fit20 = $store_links->fit20;
		$this->core140 = $store_links->core140;
		$this->on = $store_links->on;
		$this->origin = $store_links->origin;
		$this->restore = $store_links->restore;

		if ($current_site_country_code === "th") {
			$this->shop_type = "kyani";
		}

		if ($repID === "") {
			$this->rep = $this->default_rep;
		} else {
			$this->rep = $repID;
		}
	}

	function get_all_products_link()
	{
		if ($this->enabled === FALSE) {
			return $this->redirectURL;
		} else {
			if ($this->country_code === 'tr' && $this->rep === '') {
				return "";
			} else {
				if ($this->shop_type === "kyani") {
					if ($this->country === "THAILAND") {
						return "https://shop.kyani.net/" . $this->join_locale . "/products/?sponsor=" . $this->rep . "&country=" . $this->country_code . "&locale=" . $this->join_locale;
					}
				} else {
					return 'https://store.kyani.com/#/shop/from/' . $this->rep . '?MarketShow=571&country=' . $this->country . '&locale=' . $this->join_locale . '&newsession=1';
				}
			}
		}
	}

	function get_nitro_nutrition_link()
	{
		if ($this->shop_type === "kyani") {
			if ($this->country === "THAILAND") {
				return $this->category_link_generator($this->nitro_nutrition, "kyani");
			}
		} else {
			return $this->category_link_generator($this->nitro_nutrition, "bdt");
		}
	}

	function get_sunrise_link()
	{
		if ($this->shop_type === "kyani") {
			if ($this->country === "HONG KONG" || $this->country === "MACAU" || $this->country === "THAILAND") {
				return $this->product_link_generator($this->sunrise, "kyani");
			}
		} else {
			return $this->product_link_generator($this->sunrise, "bdt");
		}
	}

	function get_nitro_link()
	{
		if ($this->shop_type === "kyani") {
			if ($this->country === "HONG KONG" || $this->country === "MACAU" || $this->country === "THAILAND") {
				return $this->product_link_generator($this->nitro, "kyani");
			}
		} else {
			return $this->product_link_generator($this->nitro, "bdt");
		}
	}

	function get_nitro_xtreme_link()
	{
		if ($this->shop_type === "kyani") {
			if ($this->country === "HONG KONG" || $this->country === "MACAU" || $this->country === "THAILAND") {
				return $this->product_link_generator($this->nitroxtreme, "kyani");
			}
		} else {
			return $this->product_link_generator($this->nitroxtreme, "bdt");
		}
	}

	function get_sunset_link()
	{
		if ($this->shop_type === "kyani") {
			if ($this->country === "HONG KONG" || $this->country === "MACAU" || $this->country === "THAILAND") {
				return $this->product_link_generator($this->sunset, "kyani");
			}

		} else {
			return $this->product_link_generator($this->sunset, "bdt");
		}
	}

	function get_hl5_link()
	{
		if ($this->shop_type === "kyani") {
			if ($this->country === "THAILAND") {
				return $this->product_link_generator($this->hl5, "kyani");
			}
		}
		return $this->product_link_generator($this->hl5, "bdt");
	}

	function get_fit20_link()
	{
		if ($this->shop_type === "kyani") {
			if ($this->country === "HONG KONG" || $this->country === "MACAU" || $this->country === "THAILAND") {
				return $this->product_link_generator($this->fit20, "kyani");
			}
		} else {
			return $this->product_link_generator($this->fit20, "bdt");
		}
	}

	function get_core140_link()
	{
		if ($this->shop_type === "kyani") {
			if ($this->country === "HONG KONG" || $this->country === "MACAU" || $this->country === "THAILAND") {
				return $this->product_link_generator($this->core140, "kyani");
			}
		} else {
			return $this->product_link_generator($this->core140, "bdt");
		}
	}

	function get_on_link()
	{
		if ($this->shop_type === "kyani") {
			if ($this->country === "HONG KONG" || $this->country === "MACAU" || $this->country === "THAILAND") {
				return $this->product_link_generator($this->on, "kyani");
			}
		} else {
			return $this->product_link_generator($this->on, "bdt");
		}
	}

	function get_origin_link()
	{
		return $this->product_link_generator($this->origin, "bdt");
	}

	function get_restore_link()
	{
		return $this->product_link_generator($this->restore, "bdt");
	}

	private function product_link_generator($product_code, $shop_type)
	{
		if ($this->enabled === FALSE) {
			return $this->redirectURL;
		} else {
			if ($this->country_code === 'tr' && $this->rep === '') {
				return "";
			} else {
				if ($shop_type === "bdt") {
					return 'https://store.kyani.com/#/shop/detail/' . $product_code . '/from/' . $this->rep . '?country=' . $this->country . '&MarketShow=571&locale=' . $this->join_locale . '&newsession=1';
				}
				if ($shop_type === "kyani") {
					return 'https://shop.kyani.net/' . $this->join_locale . '/products/' . $product_code . '?sponsor=' . $this->rep . "&country=" . $this->country_code . '&locale=' . $this->join_locale;
				}
				return "";
			}
		}
	}

	private function category_link_generator($category_code, $shop_type)
	{
		if ($this->enabled === FALSE) {
			return $this->redirectURL;
		} else {
			if ($this->country_code === 'tr' && $this->rep === '') {
				return "";
			} else {
				if ($shop_type === "bdt") {
					return 'https://store.kyani.com/#/shop/from/' . $this->rep . '?categoryID=' . $category_code . '&MarketShow=571&country=' . $this->country . '&locale=' . $this->join_locale . '&newsession=1';
				}
				if ($shop_type === "kyani") {
					return "https://shop.kyani.net/" . $this->join_locale . "/products/category/" . $category_code . "?sponsor=" . $this->rep . "&country=" . $this->country_code . "&locale=" . $this->join_locale;
				}
				return "";
			}
		}
	}
}

function my_acf_add_local_field_groups()
{
	if (function_exists('acf_add_local_field_group')) {
		$repID = "";

		if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
			$rep = explode('.', $_SERVER['HTTP_X_FORWARDED_HOST'])[0];
		}
		global $rep;
		if ($rep) {
			if ($rep->rep_found()) {
				$repID = $rep->get_rep_id();
			}
		}

		// instantiate ShopLink Class
		$link = new ShopLink($repID);

		// create advanced custom fields
		acf_add_local_field_group(array(
			'key' => 'replicated-store',
			'title' => 'Replicated Store Links',
			'fields' => array(
				array(
					'key' => 'field_1',
					'label' => 'All Products',
					'name' => 'all_products',
					'type' => 'url',
					'default_value' => $link->get_all_products_link()
				),
				array(
					'key' => 'field_2',
					'label' => 'Nitro Nutrition',
					'name' => 'nitro_nutrition',
					'type' => 'url',
					'default_value' => $link->get_nitro_nutrition_link()
				),
				array(
					'key' => 'field_3',
					'label' => 'Sunrise',
					'name' => 'sunrise',
					'type' => 'url',
					'default_value' => $link->get_sunrise_link()
				),
				array(
					'key' => 'field_4',
					'label' => 'Nitro FX',
					'name' => 'nitro',
					'type' => 'url',
					'default_value' => $link->get_nitro_link()
				),
				array(
					'key' => 'field_5',
					'label' => 'Nitro Xtreme',
					'name' => 'nitro_xtreme',
					'type' => 'url',
					'default_value' => $link->get_nitro_xtreme_link()
				),
				array(
					'key' => 'field_6',
					'label' => 'Sunset',
					'name' => 'sunset',
					'type' => 'url',
					'default_value' => $link->get_sunset_link()
				),
				array(
					'key' => 'field_7',
					'label' => 'HL5',
					'name' => 'hl5',
					'type' => 'url',
					'default_value' => $link->get_hl5_link()
				),
				array(
					'key' => 'field_8',
					'label' => 'FIT20',
					'name' => 'fit20',
					'type' => 'url',
					'default_value' => $link->get_fit20_link()
				),
				array(
					'key' => 'field_9',
					'label' => 'CORE140',
					'name' => 'core140',
					'type' => 'url',
					'default_value' => $link->get_core140_link()
				),
				array(
					'key' => 'field_10',
					'label' => 'ON',
					'name' => 'on',
					'type' => 'url',
					'default_value' => $link->get_on_link()
				),
				array(
					'key' => 'field_11',
					'label' => 'Origin',
					'name' => 'origin',
					'type' => 'url',
					'default_value' => $link->get_origin_link()
				),
				array(
					'key' => 'field_12',
					'label' => 'Restore',
					'name' => 'restore',
					'type' => 'url',
					'default_value' => $link->get_restore_link()
				)
			)
		));
	}
}

add_action('acf/init', 'my_acf_add_local_field_groups');
