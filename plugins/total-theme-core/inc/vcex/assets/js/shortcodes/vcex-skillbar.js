if ( 'function' !== typeof window.vcexSkillbar ) {
	window.vcexSkillbar = function( context, event ) {

		if ( ! context || ! context.childNodes ) {
			context = document;
		}

		var isScrolling = false;

		if ( event && ( 'scroll' === event.type || 'resize' === event.type ) ) {
			isScrolling = true;
		}

		var inView = function( element ) {
			var elementRect = element.getBoundingClientRect();
			if ( elementRect.top < window.innerHeight && elementRect.bottom >= 0 ) {
				return true; // returns true when element is partially visible.
			}
		};

		context.querySelectorAll( '.vcex-skillbar[data-percent]' ).forEach( function( element ) {
			var initialized = element.dataset.vcexSkillbarInit;
			var animateOnScroll = element.dataset.animateOnScroll;
			if ( ! animateOnScroll && isScrolling && 'true' === initialized ) {
				return;
			}

			var bar = element.querySelector( '.vcex-skillbar-bar' );

			// Hide/Show on scroll.
			if ( animateOnScroll && initialized && isScrolling ) {
				if ( inView( element ) ) {
					if ( ! bar.style.width ) {
						bar.style.width = element.dataset.percent;
					}
				} else {
					if ( bar.style.width ) {
						bar.style.removeProperty( 'width' );
					}
				}
				return;
			}

			// Show for the first time.
			if ( ! initialized && inView( element ) ) {
				bar.style.width = element.dataset.percent;
				element.setAttribute( 'data-vcex-skillbar-init', 'true' );
			}

		} );

	};
}

if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
	setTimeout( vcexSkillbar, 0 );
} else {
	document.addEventListener( 'DOMContentLoaded', vcexSkillbar, false );
}

window.addEventListener( 'scroll', function( event ) {
	vcexSkillbar( document, event );
}, { passive: true } );

window.addEventListener( 'resize', function( event ) {
	vcexSkillbar( document, event );
} );