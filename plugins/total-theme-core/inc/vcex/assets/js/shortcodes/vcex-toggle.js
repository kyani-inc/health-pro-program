if ( 'function' !== typeof window.vcexToggle ) {
	window.vcexToggle = function( context ) {

		if ( ! context || ! context.childNodes ) {
			context = document;
		}

		var isAnimating = false;

		document.addEventListener( 'click', function( event ) {
			var trigger = event.target.closest( '.vcex-toggle__trigger' );
			if ( ! trigger ) {
				return;
			}
			event.preventDefault();

			if ( isAnimating ) {
				return; // prevent click spam.
			}

			var toggle = trigger.closest( '.vcex-toggle' );
			var isOpen = toggle.classList.contains( 'vcex-toggle--active' );
			var content = toggle.querySelector( '.vcex-toggle__content' );
			var isAnimated = toggle.dataset.animate;
			var duration = toggle.dataset.duration;
			var accordion = toggle.dataset.accordion;

			if ( 'false' === isAnimated ) {
				isAnimated = false;
			}

			// Prevent more click spam.
			if ( ! content || content.classList.contains( 'wpex-transitioning' ) ) {
				return;
			}

			var parentContainer = toggle.closest( '.vcex-toggle-group' );

			if ( parentContainer || ( accordion && 'false' !== accordion ) ) {

				if ( ! parentContainer ) {
					parentContainer = toggle.closest( '.vc_column_container' );
				}

				if ( parentContainer ) {
					parentContainer.querySelectorAll( '.vcex-toggle--active' ).forEach( function( activeToggle ) {
						var activeToggleContent = activeToggle.querySelector( '.vcex-toggle__content' );
						if ( activeToggle.dataset.animate && 'false' !== activeToggle.dataset.animate && 'undefined' !== typeof wpex && 'function' === typeof wpex.slideUp ) {
							activeToggleContent.style.display = 'block';
							isAnimating = true;
							wpex.slideUp( activeToggleContent, activeToggle.dataset.duration, function() {
								isAnimating = false;
							} );
						}
						activeToggle.classList.remove( 'vcex-toggle--active' );
						activeToggle.querySelector( '.vcex-toggle__trigger' ).setAttribute( 'aria-expanded', 'false' );
					} );
				}

			}

			if ( isOpen ) {
				if ( isAnimated && 'undefined' !== typeof wpex && 'function' === typeof wpex.slideUp ) {
					content.style.display = 'block'; // fixes animations.
					isAnimating = true;
					wpex.slideUp( content, duration, function() {
						isAnimating = false;
					} );
				}
				toggle.classList.remove( 'vcex-toggle--active' );
				trigger.setAttribute( 'aria-expanded', 'false' );
			} else {
				if ( isAnimated && 'undefined' !== typeof wpex && 'function' === typeof wpex.slideDown ) {
					isAnimating = true;
					wpex.slideDown( content, duration, function() {
						isAnimating = false;
					} );
				}
				toggle.classList.add( 'vcex-toggle--active' );
				trigger.setAttribute( 'aria-expanded', 'true' );
			}

		} );

	};
}

if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
	setTimeout( vcexToggle, 0 );
} else {
	document.addEventListener( 'DOMContentLoaded', vcexToggle, false );
}