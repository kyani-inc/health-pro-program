<?php
/*
 * Handles all the logic to render replicated sites for Kyani Business Partners
 */

function replicated_sites_shortcode()
{
	$current_site_id = get_current_blog_id();
	$current_site_country_code = str_replace("/", "", get_blog_details($current_site_id)->path);

	if ($current_site_country_code === "") {
		$current_site_country_code = "us";
	}

	$replicated_sites = '';
	$translated = '';

	$translated = json_decode(file_get_contents(plugins_url() . '/kyani-custom-plugin/assets/data/translations/replicated-sites/us.json'));

	global $rep;

	if ($rep) {
		if ($rep->rep_found()) {
			// html and php for card
			$replicated_sites = '<div class="container-fluid aligncenter">
									<div class="justify-content-center row">
										<div class="replicated-card col-10 col-sm-8 col-md-6 col-lg-6 col-xl-4 row px-0" >
															<div class="col-12 col-lg-5 col-xl-5 d-flex align-items-center justify-content-center">
																<img src="' . $rep->get_rep_image() . '" class="replicated-image">
															</div>
															<div class="col-12 col-lg-7 col-xl-7 replicated-rep">
																<h3 class="replicated-name">' . $rep->get_rep_name() . '</h3>
																<div class="replicated-links">
																	<span><a data-toggle="modal" data-target="#replicatedModal">View Bio</a></span>
			                                      					<span><a href=" ' . $rep->get_rep_join_link() . '" target="_blank">Join Team</a></span>
																</div>
																<div class="replicated-rep-info hidden">
												<div>Independent Business Partner</div>
												<div>ID: ' . $rep->get_rep_id() . '</div>
												<div class="email-text-link"><a href="mailto:' . $rep->get_rep_email() . '">' . $rep->get_rep_email() . '</a></div>
															</div>
															  	<a class="replicated-view-profile"><span class="replicated-profile-text-show">View Profile</span><span class="replicated-profile-text-hide">Hide Profile</span><div class="arrow">&raquo;</div></a>
														</div>
													</div></div>
												';


			// html and php for modal
			$replicated_sites .= '<div class="modal fade" id="replicatedModal" tabindex="-1" role="dialog">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-body">
												<button type="button" class="close modal-close d-flex justify-content-center align-items-center" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
												<div class="row">
													<img src="' . $rep->get_rep_image() . '" class="rounded-circle replicated-image">
													<div class="modal-rep-info">
														<h3 class="replicated-name">' . $rep->get_rep_name() . '</h3>
														<div class="ibp-name">' . $translated->ibp . '</div>
													</div>
												</div>
												<div class="row">
													<div class="modal-description">
														<h4 class="about-me">About Me</h4>
														' . $rep->get_rep_description() . '
													</div>
												</div>';
			if ($translated->disclaimer) {
				$replicated_sites .=  '			<div class="row">
													<div class="modal-footer">
														<p class="disclaimer" style="font-size: 12px">DISCLAIMER: Results aren\'t typical or guaranteed and require hard work and skill. By referring customers, Business Partners can earn meaningful supplemental income based on actual product sales. Most people join only to purchase amazing products for personal use and earn little or no income. To see what\'s possible visit income.kyani.com.</br></br>DISCLAIMER: These statements have not been evaluated by the FDA or any government agency. Kyäni products are not intended to diagnose, treat, cure, or prevent any disease or medical condition.</p>
													</div>
												</div>';
			};
			$replicated_sites .= '
											</div>
										</div>
									</div>
								</div>';
		}
	}
	return $replicated_sites;
}

add_shortcode('replicatedsites', 'replicated_sites_shortcode');
