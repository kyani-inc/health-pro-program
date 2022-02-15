( function( $ ) {
	'use strict';
	$( document ).ready( function() {
		var settings = wpexAnimsition;
		settings.inDuration = parseInt( settings.inDuration );
		settings.outDuration = parseInt( settings.outDuration );
		$( '.animsition' ).animsition( settings );

		// Make sure rows are rendered properly after animation is complete.
		// Note: for some reason animsition.inStart won't work in Firefox, it never gets triggered.
		if ( settings.inClass === 'fade-in-left' || settings.inClass === 'fade-in-right' || settings.inClass === 'flip-in-x' || settings.inClass === 'flip-in-y' || settings.inClass === 'zoom-in' ) {
			$( '.animsition' ).on( 'animsition.inEnd', function() {
				if ( 'undefined' !== typeof window.vc_rowBehaviour ) {
					vc_rowBehaviour();
				}
			} );
		}
	} );
} ) ( jQuery );