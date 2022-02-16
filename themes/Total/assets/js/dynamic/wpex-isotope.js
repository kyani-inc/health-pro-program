if ( 'function' !== typeof window.wpexIsotope ) {
	window.wpexIsotope = function() {

		var masonryGrids = document.querySelectorAll( '.wpex-masonry-grid' );

		var renderGrid = function( element ) {

			// Set settings for each item.
			var settings = {};
			if ( 'object' === typeof wpex_isotope_params ) {
				settings = Object.assign( {}, wpex_isotope_params ); // create new object of wpex_isotope_params
				settings.itemSelector = '.wpex-masonry-col'; // set correct itemSelector
			}

			// Get custom settings.
			if ( element.dataset.transitionDuration ) {
				settings.transitionDuration = parseFloat( element.dataset.transitionDuration ) + 's';
			}

			if ( element.dataset.layoutMode ) {
				settings.layoutMode = element.dataset.layoutMode;
			}

			// Initiate isotope.
			var iso = new Isotope( element, settings );

		};

		var initialize = function( element ) {
			if ( 'function' === typeof imagesLoaded ) {
				imagesLoaded( element, function() {
					renderGrid( element );
				} );
			} else {
				renderGrid( element );
			}
		};

		masonryGrids.forEach( function( element ) {
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
	setTimeout( wpexIsotope, 0 );
} else {
	document.addEventListener( 'DOMContentLoaded', wpexIsotope, false );
}