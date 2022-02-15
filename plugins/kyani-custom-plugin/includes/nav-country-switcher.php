<?php
/**
 * Custom navigation country switcher for Kyani
 */

add_filter('wp_nav_menu_items', 'add_country_selector_to_menu', 10, 2);
function add_country_selector_to_menu($items, $args)
{
	$sites = get_sites(['public' => 1]);
	$current_site_id = get_current_blog_id();
	$new_nav_item = "";
	$items_array = array();

	// get the current site country code
	$current_site_country_code = str_replace("/", "", get_blog_details($current_site_id)->path);
	// since the main site is for the US but contains no country code assign it to $current_site_country_code
	if ($current_site_country_code == "") {
		$current_site_country_code = "us";
	}

	if ($args->theme_location == "primary") {
		$new_nav_item .= '<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">';
		$new_nav_item .= '<img src="' . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $current_site_country_code . '.svg" width="24">' . '</a>';
		$new_nav_item .= '<ul class="dropdown-menu dropdown-menu-right" id="countries" aria-labelledby="navbarDropdown">';

		$new_nav_item .= buildDropdownItems($sites);
		$new_nav_item .= '</ul></li>';

		// if there are menu items in the $items array it will add the country switcher as the second menu item
			if ($items) {
				while (false !== ($items_pos = strpos($items, '<li', 3))) {
					$items_array[] = substr($items, 0, $items_pos);
					$items = substr($items, $items_pos);

				}
				$items_array[] = $items;
				array_splice($items_array, 0, 0, $new_nav_item);

				$items = implode('', $items_array);
				return $items;
			}
	}
	return $items . $new_nav_item;

}

add_filter('wp_nav_menu_items', 'add_country_selector_to_mobile', 10, 2);
function add_country_selector_to_mobile($items, $args) {
	$sites = get_sites(['public' => 1]);
	$current_site_id = get_current_blog_id();
	$new_nav_item = "";
	$items_array = array();

	// get the current site country code
	$current_site_country_code = str_replace("/", "", get_blog_details($current_site_id)->path);
	// since the main site is for the US but contains no country code assign it to $current_site_country_code
	if ($current_site_country_code == "") {
		$current_site_country_code = "us";
	}

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

	if ($args->theme_location == "mobile") {
		$new_nav_item .= "<li class='nav-item dropdown mobile-only mt-auto'><a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown'>";
		$new_nav_item .= $locale_translations->country ."</a>";
		$new_nav_item .= "<ul class='dropdown-menu' id='countries' aria-labelledby='navbarDropdown'>";

		$new_nav_item .= buildDropDownItems($sites);
		$new_nav_item .= "</ul>";
	}
	// if there are menu items in the $items array it will add the country switcher as the second menu item
	if ($items) {
		while (false !== ($items_pos = strpos($items, '<li', 3))) {
			$items_array[] = substr($items, 0, $items_pos);
			$items = substr($items, $items_pos);

		}
		$items_array[] = $items;
		array_splice($items_array, 1, 0, $new_nav_item);

		$items = implode('', $items_array);
		return $items;
	}
	return $new_nav_item . $items;
}

// function builds the dropdown menu with all the countries
function buildDropdownItems($sites) {
	$country_websites = json_decode(file_get_contents(dirname(__DIR__) . '/assets/data/sites.json'));
	$rep = "";
	if (isset($_SERVER['HTTP_X_KYANI_REP'])) {
		$rep = explode(';', $_SERVER['HTTP_X_KYANI_REP'])[0];
	}
	$dropdownItems = "";

	foreach ($country_websites->regions as $region) {
		$dropdownItems .= '<li class="dropdown-header">' . $region->name . '</li>';

		foreach ($region->countries as $country) {
			foreach ($sites as $subsite) {
				$subsite->object_id = get_object_vars($subsite)['blog_id'];
				$subsite->url = get_blog_details($subsite->object_id)->siteurl;
				$subsite->displayname = get_blog_details($subsite->object_id)->blogname;
				$subsite->path = str_replace("/", "", get_blog_details($subsite->object_id)->path);
			}
			if ($country->code == 'cn'){
				$dropdownItems .= "<a class='dropdown-item' href='" . $country->url . "'><img class='nav-country-flag' src='" . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $country->code . ".svg' width='24'>" . $country->display_name . "</a>";
			} else {
				$dropdownItems .= "<a class='dropdown-item' href='//". ($rep != "" ? $rep . '.' : "") .$_SERVER['HTTP_HOST'] . $country->url . "'><img class='nav-country-flag' src='" . plugins_url() . '/kyani-custom-plugin/assets/images/flags/' . $country->code . ".svg' width='24'>" . $country->display_name . "</a>";
			}
		}
		$dropdownItems .= '<li class="dropdown-divider"></li>';
	}
	return $dropdownItems;
}
