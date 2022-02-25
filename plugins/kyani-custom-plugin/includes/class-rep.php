<?php

class Rep
{
	private $rep_name, $image_url, $email, $description, $found, $rep_id, $join_url, $company_flag, $company_name;

	function __construct($repDID) {
		if ($repDID != "") {
			$url = "https://api.kyani.net/rep/site?find=" . $repDID;
			$request = wp_remote_get($url);

			if (is_wp_error($request)) {
				$this->found = false;
				return;
			}
			$body = wp_remote_retrieve_body($request);
			$data = json_decode($body);
			if (!empty($data) && $data->found) {
				$this->rep_id = $data->id;
				$this->rep_name = $data->name;
				$this->found = $data->found;
				$this->image_url = $data->image;
				$this->email = $data->email;
				$this->description = $data->text;
				$this->company_flag = $data->company_name_display_flag;
				$this->company_name = $data->company;

				if ($data->image == "") {
					$this->image_url = "https://assets.kyani.net/www.kyani.com/assets/img/dist/default-profile.png";
				}

				$this->set_rep_join_link($data->id);
			}
		} else {
			$this->found = false;
		}
	}

	function get_rep_name() {
		if($this->company_flag === 1){
			return $this->company_name;
		}
		return $this->rep_name;
	}

	function rep_found() {
		return $this->found;
	}

	function get_rep_image() {
		return $this->image_url;
	}

	function get_rep_email() {
		return $this->email;
	}

	function get_rep_id() {
		return $this->rep_id;
	}

	function get_rep_description() {
		return $this->description;
	}

	function get_rep_join_link() {
		return $this->join_url;
	}

	private function set_rep_join_link($repID) {
		// get current country code
		$current_site_id = get_current_blog_id();
		$current_site_country_code = str_replace("/", "", get_blog_details($current_site_id)->path);

		if ($current_site_country_code === "") {
			$current_site_country_code = "us";
		}

		// get json object with join attributes
		$links = json_decode(file_get_contents(dirname(__DIR__) . '/assets/data/links/' . $current_site_country_code . '.json'));

		// get current locale
		global $TRP_LANGUAGE;
		$current_locale = $TRP_LANGUAGE;

		// get join_locale
		foreach ($links->locales as $locale) {
			if ($locale->locale === $current_locale) {
				$this->join_url = "https://join.kyani.com/settings?country=" . $links->country . "&language=" . $locale->language_code . '&sponsor=' . $repID;
			}
		}
	}
}

function set_rep() {
	$repID = "";
	if (isset($_SERVER['HTTP_X_KYANI_REP'])) {
		$repID = explode(';', $_SERVER['HTTP_X_KYANI_REP'])[0];
	}

	global $rep;
	$rep = new Rep($repID);
}

add_action('init', 'set_rep');
