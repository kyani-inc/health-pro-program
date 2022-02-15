<?php
/* Adds Replicated Display to both desktop and mobile  */

function replicated_display_shortcode()
{
	// SETS variables
	$replicated_display = '';

	// ENQUEUE files
	wp_enqueue_style('replicatedDisplayStyle', plugins_url() . '/kyani-custom-plugin/includes/replicatedDisplay/assets/style.css');
	wp_enqueue_script('replicatedDisplayScript', plugins_url() . '/kyani-custom-plugin/includes/replicatedDisplay/assets/script.js');

	// GET current country code
	$current_site_id = get_current_blog_id();
	$current_site_country_code = str_replace("/", "", get_blog_details($current_site_id)->path);
	if ($current_site_country_code === "") {
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

	// GET rep global
	global $rep;

	// IF rep and rep found create menu item
	if ($rep) {
		if ($rep->rep_found()) {
			$replicated_display = '<li class="nav-item"><a class="repDropdown nav-link" onclick="toggleRepDisplay()">' . $rep->get_rep_name() . '</a></li>';

			$replicated_display .= '<div id="repDisplay" style="display: none;" class="col-12"><div class="col-7 row d-flex justify-content-center p-5">
								  <div class="col-2 col-md-5 col-lg-2 col-xl-2 p-3 text-right"><img src="' . $rep->get_rep_image() . '" class="repImage"></div>
								  <div class="col-10 col-md-7 col-lg-10 col-xl-10 p-3">
																<h2 class="repName">' . $rep->get_rep_name() . '</h2>
																<p class="repInfo">' . $locale_translations->ibp . '<br>
																ID: ' . $rep->get_rep_id() . '<br>
																<a href="mailto:' . $rep->get_rep_email() . '">' . $rep->get_rep_email() . '</a></p>
																<h3 class="repBioView" onclick="toggleBio()"><span id="viewBio">' . $locale_translations->view_bio . '</span><span id="hideBio" style="display: none;">' . $locale_translations->hide_bio . '</span></h3><div id="repBio" style="display: none;">';
			if(!empty($rep->get_rep_description())) {
				$replicated_display .= '<h4>' . $locale_translations->about_me . '</h4>' . $rep->get_rep_description() . '<hr>';
			}
			if ($locale_translations->disclaimer) {
				$replicated_display .=  '<p class="repDisclaimer">' . $locale_translations->disclaimer . '</p>';
			};
			$replicated_display .= '</div><button class="repJoin"><a href="' . $rep->get_rep_join_link() . '" target="_blank">' . $locale_translations->join_team . '</a></button>';
			$replicated_display .= '</div></div></div>';
		}
	}
	return $replicated_display;
}

add_shortcode('replicatedDisplay', 'replicated_display_shortcode');



function replicated_display_mobile_shortcode()
{
	// SETS variables
	$replicated_display = '';

	// ENQUEUE files
	wp_enqueue_style('replicatedDisplayStyle', plugins_url() . '/kyani-custom-plugin/includes/replicatedDisplay/assets/style.css');
	wp_enqueue_script('replicatedDisplayScript', plugins_url() . '/kyani-custom-plugin/includes/replicatedDisplay/assets/script.js');

	// GET current country code
	$current_site_id = get_current_blog_id();
	$current_site_country_code = str_replace("/", "", get_blog_details($current_site_id)->path);
	if ($current_site_country_code === "") {
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

	// GET rep global
	global $rep;

	// IF rep and rep found create menu item
	if ($rep) {
		if ($rep->rep_found()) {
			$replicated_display .= '<li class="nav-item dropdown mobile-only"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">' . $rep->get_rep_name() . '</a>';
			$replicated_display .= '<ul class="dropdown-menu p-5 col-12" id="countries repDisplay" aria-labelledby="navbarDropdown">';
			$replicated_display .= '<div class="col-10"><p class="repInfo">' . $locale_translations->ibp . '<br>
							  		ID: ' . $rep->get_rep_id() . '<br>
							  		<a href="mailto:' . $rep->get_rep_email() . '">' . $rep->get_rep_email() . '</a></p>
							  		<button class="repJoin"><a href="' . $rep->get_rep_join_link() . '" target="_blank">' . $locale_translations->join_team . '</a></button></div>';
			$replicated_display .= "</ul>";
			return $replicated_display;
		}
	}
}

add_shortcode('replicatedDisplayMobile', 'replicated_display_mobile_shortcode');
