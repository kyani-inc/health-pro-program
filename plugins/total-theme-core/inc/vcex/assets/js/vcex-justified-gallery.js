if ( 'function' !== typeof window.vcexJustifiedGallery ) {
	window.vcexJustifiedGallery = function( context ) {
		if ( 'undefined' === typeof jQuery || 'function' !== typeof jQuery.fn.justifiedGallery ) {
			return;
		}
		if ( ! context || ! context.childNodes ) {
			context = document;
		}
		var initialize = function( element ) {
			jQuery( element ).justifiedGallery( JSON.parse( element.dataset.justifiedGallery ) );
		};
		context.querySelectorAll( '.vcex-justified-gallery' ).forEach( function( element ) {
			if ( element.closest( '[data-vc-stretch-content]' ) ) {
				setTimeout( function() {
					initialize( element );
				}, 10 );
			} else {
				initialize( element );
			}
		} );
	};
}

if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
	setTimeout( vcexJustifiedGallery, 0 );
} else {
	document.addEventListener( 'DOMContentLoaded', vcexJustifiedGallery, false );
}