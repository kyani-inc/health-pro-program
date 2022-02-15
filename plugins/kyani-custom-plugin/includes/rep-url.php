<?php function grabRepID( $atts ){
	$parsedUrl = wp_parse_url(get_permalink());
	if (explode('.', $parsedUrl['host'])[0] === 'nitrohealthpro') {
		return;
	} else {
	return explode('.', $parsedUrl['host'])[0];
	}
}
add_shortcode( 'grabRepID', 'grabRepID' );
