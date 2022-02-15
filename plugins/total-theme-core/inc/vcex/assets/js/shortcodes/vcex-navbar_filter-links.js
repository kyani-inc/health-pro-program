if ( 'function' !== typeof window.vcexNavbarFilterLinks ) {
	window.vcexNavbarFilterLinks = function() {

		if ( 'function' !== typeof Isotope ) {
			return;
		}

		var renderGrid = function( grid, settings ) {
			if ( ! grid.classList.contains( 'vcex-navbar-filter-grid' ) ) {
				grid.classList.add( 'vcex-navbar-filter-grid' );
				isotopeInit( grid, settings );
			}
			// Add isotope only, the filter grid already exists - @todo is this needed?
			else {
				isotopeInit( grid, {} );
			}
		};

		var initialize = function( grid, settings ) {
			if ( 'function' === typeof imagesLoaded ) {
				imagesLoaded( grid, function( instance ) {
					renderGrid( grid, settings );
				} );
			} else {
				renderGrid( grid, settings );
			}
		};

		var filterGrid = function( grid, filter ) {
			var iso = Isotope.data( grid );
			iso.arrange( {
				filter: filter
			} );
		};

		function isotopeInit( grid, settings ) {
			if ( 'undefined' === typeof jQuery ) {
				var iso = new Isotope( grid, settings );
			} else {
				jQuery( grid ).isotope( settings );
			}
		}

		// Loops through filter navs to initialize masonry and set default active items.
		document.querySelectorAll( '.vcex-filter-nav' ).forEach( function( nav ) {
			var settings,
				grid = document.querySelector( '#' + nav.dataset.filterGrid );

			if ( ! grid ) {
				return;
			}

			if ( ! grid.classList.contains( 'wpex-row' ) ) {
				grid = grid.querySelector( '.wpex-row' );
			}

			if ( ! grid ) {
				return;
			}

			// Remove isotope class since we are adding our own masonry.
			grid.classList.remove( 'vcex-isotope-grid' );

			// Get settings from data attributes.
			var activeItems = nav.dataset.filter;
			var customDuration = nav.dataset.transitionDuration;
			var customLayout = nav.dataset.layoutMode;

			// Define masonry settings.
			if ( 'object' === typeof wpex_isotope_params ) {
				settings = Object.assign( {}, wpex_isotope_params ); // create new object to keep wpex_isotope_params intact.
			} else {
				settings = {
					transformsEnabled: true,
					transitionDuration: '0.4s',
					layoutMode: 'masonry',
				};
			}

			if ( document.body.classList.contains( 'rtl' ) ) {
				settings.isOriginLeft = false;
			}

			if ( 'undefined' !== typeof customDuration ) {
				settings.transitionDuration = parseFloat( customDuration ) + 's';
			}

			if ( 'undefined' !== typeof customLayout ) {
				settings.layoutMode = customLayout;
			}

			if ( activeItems && nav.querySelector( '[data-filter="' + activeItems + '"]' ) ) {
				settings.filter = activeItems;
			}

			settings.itemSelector = '.col'; // because the vcex-isotope-entry can't be added.

			if ( grid.closest( '[data-vc-stretch-content]' ) ) {
				setTimeout( function() {
					initialize( grid, settings );
				}, 10 );
			} else {
				initialize( grid, settings );
			}

		} );

		// Filters grid when clicking on filter links.
		document.addEventListener( 'click', function( event ) {
			var filterLink = event.target.closest( '.vcex-navbar-link' );
			if ( ! filterLink ) {
				return;
			}

			var nav = filterLink.closest( '.vcex-filter-nav' );

			if ( ! nav ) {
				return;
			}

			var grid = document.querySelector( '#' + nav.dataset.filterGrid );

			if ( ! grid ) {
				return;
			}

			if ( ! grid.classList.contains( 'wpex-row' ) ) {
				grid = grid.querySelector( '.wpex-row' );
			}

			if ( ! grid ) {
				return;
			}

			var filter = filterLink.dataset.filter || '*';

			nav.querySelectorAll( '.vcex-navbar-link' ).forEach( function( element ) {
				element.classList.remove( 'active' );
			} );

			filterLink.classList.add( 'active' );

			filterGrid( grid, filter );

			event.preventDefault();
			event.stopPropagation();
		} );

	};

}

if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
	setTimeout( vcexNavbarFilterLinks, 0 );
} else {
	document.addEventListener( 'DOMContentLoaded', vcexNavbarFilterLinks, false );
}