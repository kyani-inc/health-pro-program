if ( 'function' !== typeof window.vcexIsotopeGrids ) {
	window.vcexIsotopeGrids = function( context ) {

		if ( 'function' !== typeof Isotope ) {
			return;
		}

		if ( ! context || ! context.childNodes ) {
			context = document;
		}

		var renderGrid = function( element ) {

			var settings,
				prevElement,
				filter,
				customDuration,
				customLayout,
				activeItems;

			prevElement = element.previousElementSibling;
			customDuration = element.dataset.transitionDuration;
			customLayout = element.dataset.layoutMode;

			if ( prevElement && prevElement.classList.contains( 'vcex-filter-links' ) ) {
				filter = prevElement;
			}

			if ( 'object' === typeof wpex_isotope_params ) {
				settings = Object.assign( {}, wpex_isotope_params ); // create new object to keep wpex_isotope_params intact.
			} else {
				settings = {
					transformsEnabled: true,
					transitionDuration : '0.4s',
					layoutMode: 'masonry',
				};
			}

			if ( document.body.classList.contains( 'rtl' ) ) {
				settings.isOriginLeft = false;
			}

			if ( customDuration ) {
				settings.transitionDuration = parseFloat( customDuration ) + 's';
			}

			if ( customLayout ) {
				settings.layoutMode = customLayout;
			}

			settings.itemSelector = '.vcex-isotope-entry';

			if ( filter ) {

				activeItems = element.dataset.filter;

				// If there aren't any posts for the active category then don't set active category.
				if ( activeItems && ! filter.querySelectorAll( '[data-filter="' + activeItems + '"]' ).length ) {
					activeItems = '';
				}

				if ( activeItems ) {
					settings.filter = activeItems;
				}

			}

			var iso = new Isotope( element, settings );

		}; // end renderGrid

		var initialize = function( element ) {
			if ( 'function' === typeof imagesLoaded ) {
				imagesLoaded( element, function() {
					renderGrid( element );
				} );
			} else {
				renderGrid( element );
			}
		};

		context.querySelectorAll( '.vcex-isotope-grid' ).forEach( function( element ) {
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

if ( 'function' !== typeof window.vcexIsotopeGridFilter ) {
	window.vcexIsotopeGridFilter = function() {

		if ( 'function' !== typeof Isotope ) {
			return;
		}

		document.addEventListener( 'click', function( event ) {
			var target, filter, button, grid;

			target = event.target;
			filter = target.closest( '.vcex-filter-links' );

			if ( ! filter ) {
				return;
			}

			// @todo we can update to instead use event.target.closest( '.vcex-filter-links a' );
			if ( 'a' === target.tagName.toLowerCase() ) {
				button = target;
			} else {
				button = target.closest( 'a' );
			}

			// Make sure we are clicking on an actual filter link button.
			if ( ! button || ! button.hasAttribute( 'data-filter' ) ) {
				return;
			}

			grid = filter.nextElementSibling;

			if ( ! grid || ! grid.classList.contains( 'vcex-isotope-grid' ) ) {
				return;
			}

			var iso = Isotope.data( grid );

			if ( iso ) {
				iso.arrange( {
					filter: target.dataset.filter
				} );
			}

			filter.querySelectorAll( 'li' ).forEach( function( element ) {
				element.classList.remove( 'active' );
			} );

			target.closest( 'li' ).classList.add( 'active' );

			event.preventDefault();
			event.stopPropagation();

		} );

	};
}

( function() {

	var init = function() {
		vcexIsotopeGrids();
		vcexIsotopeGridFilter();
		//window.addEventListener( 'orientationchange', vcexIsotopeGrids ); // deprecated in 1.3.1
	};

	if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
		setTimeout( init, 0 );
	} else {
		document.addEventListener( 'DOMContentLoaded', init, false );
	}

})();