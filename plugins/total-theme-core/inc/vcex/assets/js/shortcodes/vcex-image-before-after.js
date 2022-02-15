if ( 'function' !== typeof window.vcexBeforeAfter ) {
	window.vcexBeforeAfter = function( context ) {

		if ( 'undefined' === typeof jQuery || 'function' !== typeof jQuery.fn.twentytwenty ) {
			return;
		}

		if ( ! context || ! context.childNodes ) {
			context = document;
		}

		var renderBa = function( element ) {
			jQuery( element ).twentytwenty( JSON.parse( element.dataset.options ) );
			element.setAttribute( 'data-vcex-image-ba-init', 'true' );
		};

		context.querySelectorAll( '.vcex-image-ba:not([data-vcex-image-ba-init="true"]' ).forEach( function( element ) {
			if ( 'function' === typeof imagesLoaded ) {
				imagesLoaded( element, function() {
					renderBa( element );
				} );
			} else {
				renderBa( element );
			}
		} );

	};
}

if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
	setTimeout( vcexBeforeAfter, 0 );
} else {
	document.addEventListener( 'DOMContentLoaded', vcexBeforeAfter, false );
}