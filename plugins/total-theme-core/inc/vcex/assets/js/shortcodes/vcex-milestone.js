if ( 'function' !== typeof window.vcexMilestone ) {
	window.vcexMilestone = function( context ) {

		if ( 'function' !== typeof CountUp ) {
			return;
		}

		if ( ! context || ! context.childNodes ) {
			context = document;
		}

		var inView = function( element ) {
			var elementRect = element.getBoundingClientRect();
			if ( elementRect.top < window.innerHeight && elementRect.bottom >= 0 ) {
				return true; // returns true when element is partially visible.
			}
		};

		context.querySelectorAll( '.vcex-milestone-time.vcex-countup' ).forEach( function( element ) {
			var numAnim = null;
			var options = JSON.parse( element.dataset.options );
			var initialized = element.dataset.vcexCountupInit;
			var animateOnScroll = options.animateOnScroll;
			var appeared = false;

			if ( ! animateOnScroll && 'true' === initialized ) {
				return; // for vc front-end.
			}

			// Start new counter.
			if ( ! numAnim ) {
				numAnim = new CountUp( element, options.startVal, options.endVal, options.decimals, options.duration, {
					useEasing: true,
					useGrouping: true,
					separator: options.separator,
					decimal: options.decimal,
					prefix: '',
					suffix: ''
				} );
			}

			// Animate on doc ready.
			if ( 'true' !== initialized && inView( element ) ) {
				appeared = true;
				numAnim.start();
				initialized = 'true';
				element.setAttribute( 'data-vcex-countup-init', initialized );
				if ( ! animateOnScroll ) {
					return; // no need to add scroll or resize events.
				}
			}

			var showHide = function( event ) {
				if ( inView( element ) ) {
					if ( ! appeared ) {
						appeared = true;
						if ( 'true' === initialized && animateOnScroll ) {
							numAnim.reset();
							numAnim.start();
						} else {
							numAnim.start();
							initialized = 'true';
							element.setAttribute( 'data-vcex-countup-init', initialized );
						}
						if ( ! animateOnScroll ) {
							window.removeEventListener( 'scroll', showHide );
							window.removeEventListener( 'scroll', showHide );
						}
					}
				} else {
					appeared = false;
				}
			};

			window.addEventListener( 'scroll', showHide, { passive: true } );
			window.addEventListener( 'resize', showHide );

		} );

	};

}

if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
	setTimeout( vcexMilestone, 0 );
} else {
	document.addEventListener( 'DOMContentLoaded', vcexMilestone, false );
}