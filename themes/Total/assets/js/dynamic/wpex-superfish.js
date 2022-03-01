( function( $, settings ) {
	'use strict';

	$( '#site-navigation ul.sf-menu' ).superfish( {
		delay        : settings.delay,
		speed        : settings.speed,
		speedOut     : settings.speedOut,
		cssArrows    : false,
		disableHI    : false,
		animation    : {
			opacity: 'show'
		},
		animationOut : {
			opacity: 'hide'
		}
	} );

} )( jQuery, wpex_superfish_params );