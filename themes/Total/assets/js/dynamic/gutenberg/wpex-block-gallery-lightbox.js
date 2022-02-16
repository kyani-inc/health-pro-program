if ( 'function' !== typeof window.wpexBlockGalleryLightbox ) {
	window.wpexBlockGalleryLightbox = function() {

		document.querySelectorAll( '.wp-block-gallery' ).forEach( function( gallery ) {

			var hasLightbox = false;

			gallery.querySelectorAll( 'figure > a' ).forEach( function( link ) {

				if ( link.classList.contains( 'fancybox' ) || link.classList.contains( 'lightbox' ) ) {
					return;
				}

				var image = link.querySelector( 'img' );

				if ( ! image ) {
					return;
				}

				var fullImage = image.dataset.fullUrl;

				if ( ! fullImage ) {
					return;
				}


				var href = link.getAttribute( 'href' );

				if ( href === fullImage ) {
					link.classList.add( 'wpex-lightbox-group-item' );
				}

				if ( ! hasLightbox ) {
					hasLightbox = true;
				}

			} );

			if ( hasLightbox ) {
				gallery.classList.add( 'wpex-lightbox-group' );
			}

		} );

	};
}

if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
	setTimeout( wpexBlockGalleryLightbox, 0 );
} else {
	document.addEventListener( 'DOMContentLoaded', wpexBlockGalleryLightbox, false );
}