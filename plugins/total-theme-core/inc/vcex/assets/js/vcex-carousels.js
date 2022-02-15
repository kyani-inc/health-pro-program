if ( 'function' !== typeof window.vcexCarousels ) {
	window.vcexCarousels = function( context ) {

		if ( 'undefined' === typeof jQuery || 'function' !== typeof jQuery.fn.wpexOwlCarousel ) {
			return;
		}

		if ( ! context || ! context.childNodes ) {
			context = document;
		}

		var l10n = vcex_carousels_params;
		var isRTL = document.body.classList.contains( 'rtl' );
		var allCarousels = context.querySelectorAll( '.wpex-carousel' );

		var renderCarousel = function( element ) {

			var settings = JSON.parse( element.dataset.wpexCarousel );

			if ( ! settings ) {
				console.log( 'Total Notice: The Carousel template in your child theme needs updating to include wpex-carousel data attribute.' );
				return;
			}

			var defaults = {
				animateIn: false,
				animateOut: false,
				lazyLoad: false,
				autoplayHoverPause: true,
				rtl: isRTL,
				navText: [ '<span class="ticon ticon-chevron-left" aria-hidden="true"></span><span class="screen-reader-text">' + l10n.i18n.PREV + '</span>', '<span class="ticon ticon-chevron-right" aria-hidden="true"></span><span class="screen-reader-text">' + l10n.i18n.NEXT + '</span>' ],
				responsive: {
					0: {
						items: settings.itemsMobilePortrait
					},
					480: {
						items: settings.itemsMobileLandscape
					},
					768: {
						items: settings.itemsTablet
					},
					960: {
						items: settings.items
					}
				},
			};

			jQuery( element ).wpexOwlCarousel( jQuery.extend( true, {}, defaults, settings ) );

		};

		allCarousels.forEach( function( element ) {
			if ( 'function' === typeof imagesLoaded ) {
				imagesLoaded( element, function() {
					renderCarousel( element );
				} );
			} else {
				renderCarousel( element );
			}
		} );

	};

}

if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
	setTimeout( vcexCarousels, 0 );
} else {
	document.addEventListener( 'DOMContentLoaded', vcexCarousels, false );
}