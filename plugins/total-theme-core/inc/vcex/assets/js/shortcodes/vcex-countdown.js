if ( 'function' !== typeof window.vcexCountDown ) {
	window.vcexCountDown = function( context ) {

		if ( 'undefined' === typeof jQuery || 'function' !== typeof jQuery.fn.countdown ) {
			return;
		}

		if ( ! context || ! context.childNodes ) {
			context = document;
		}

		context.querySelectorAll( '.vcex-countdown' ).forEach( function( element ) {
			var endDate = element.dataset.countdown;
			var days = element.dataset.days;
			var hours = element.dataset.hours;
			var minutes = element.dataset.minutes;
			var seconds = element.dataset.seconds;
			var timezone = element.dataset.timezone;

			if ( timezone && 'function' === typeof moment.tz ) {
				endDate = moment.tz( endDate, timezone ).toDate();
			}

			if ( ! endDate ) {
				return;
			}

			jQuery( element ).countdown( endDate, function( event ) {
				jQuery( this ).html( event.strftime( '<div class="wpex-days"><span>%-D</span> <small>' + days + '</small></div> <div class="wpex-hours"><span>%-H</span> <small>' + hours + '</small></div class="wpex-months"> <div class="wpex-minutes"><span>%-M</span> <small>' + minutes + '</small></div> <div class="wpex-seconds"><span>%-S</span> <small>' + seconds + '</small></div>' ) );
			} );

		} );

	};

}

if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
	setTimeout( vcexCountDown, 0 );
} else {
	document.addEventListener( 'DOMContentLoaded', vcexCountDown, false );
}