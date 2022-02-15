( function( $, obj ) {

	var init = function() {
		if ( 'undefined' !== typeof jQuery && 'undefined' !== typeof jQuery.fn.wpColorPicker ) {
			jQuery( '.wpex-color-field' ).wpColorPicker();
		}
	}

	if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
		setTimeout( init, 0 );
	} else {
		document.addEventListener( 'DOMContentLoaded', init, false );
	}

})();