( function() {

	if ( 'function' !== typeof window.vcexAnimatedText ) {
		window.vcexAnimatedText = function( context ) {

			if ( 'function' !== typeof Typed ) {
				return;
			}

			if ( ! context || ! context.childNodes ) {
				context = document;
			}

			var inView = function( element ) {
				var elementRect = element.getBoundingClientRect();
				if ( (elementRect.top >= 0) && (elementRect.bottom <= window.innerHeight) ) {
					return true; // returns true when element is fully visible.
				}
			};

			var texts = context.querySelectorAll( '.vcex-typed-text:not([data-vcex-typed-text-init="true"])' );

			if ( ! texts.length ) {
				window.removeEventListener( 'scroll', vcexAnimatedText );
				window.removeEventListener( 'resize', vcexAnimatedText );
			}

			texts.forEach( function( element ) {
				var strings = element.dataset.strings, settings, typed;
				if ( ! element.dataset.strings ) {
					return;
				}

				strings = JSON.parse( strings );
				settings = JSON.parse( element.dataset.settings );

				if ( settings.typeSpeed ) {
					settings.typeSpeed  = parseInt( settings.typeSpeed );
				}

				if ( settings.backDelay ) {
					settings.backDelay  = parseInt( settings.backDelay );
				}

				if ( settings.backSpeed ) {
					settings.backSpeed  = parseInt( settings.backSpeed );
				}

				if ( settings.startDelay ) {
					settings.startDelay = parseInt( settings.startDelay );
				}

				settings.strings = strings;

				if ( inView( element ) ) {
					typed = new Typed( element, settings );
					element.setAttribute( 'data-vcex-typed-text-init', 'true' );
				}

			} );

		};
	}

	if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
		setTimeout( vcexAnimatedText, 0 );
	} else {
		document.addEventListener( 'DOMContentLoaded', vcexAnimatedText, false );
	}

	window.addEventListener( 'scroll', vcexAnimatedText, { passive: true } );
	window.addEventListener( 'resize', vcexAnimatedText );

})();