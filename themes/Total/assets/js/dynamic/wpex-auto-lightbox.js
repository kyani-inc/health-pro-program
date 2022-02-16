( function( $, l10n ) {
	'use strict';

	if ( 'function' !== typeof window.wpexAutoLightbox ) {
		window.wpexAutoLightbox = function() {

			var isImage = function( src ) {
				if ( ! src ) {
					return false;
				}
				var ext = src.split(/[#?]/)[0].split('.').pop().trim();
				var imageExtensions = ['bmp', 'gif', 'jpeg', 'jpg', 'png', 'tiff', 'tif', 'jfif', 'jpe'];

				if ( ext ) {
					return imageExtensions.includes( ext );
				}
			}

			document.querySelectorAll( l10n.targets ).forEach( function( image ) {
				var link = image.closest( 'a' );
				if ( ! link || link.classList.contains( 'wpex-lightbox' ) || link.classList.contains( 'wpex-lightbox-gallery' ) || link.classList.contains( 'wpex-lightbox-group-item' ) ) {
					return;
				}
				var href = link.getAttribute( 'href' );
				if ( isImage( href ) && ! link.closest( '.woocommerce-product-gallery' ) ) {
					link.classList.add( 'wpex-lightbox' );
				}
			} );

		};
	}

	if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
		setTimeout( wpexAutoLightbox, 0 );
	} else {
		document.addEventListener( 'DOMContentLoaded', wpexAutoLightbox, false );
	}

} )( jQuery, wpex_autolightbox_params );