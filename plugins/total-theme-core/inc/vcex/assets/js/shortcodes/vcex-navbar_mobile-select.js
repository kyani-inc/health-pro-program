if ( 'function' !== typeof window.vcexNavbarMobileSelect ) {
	window.vcexNavbarMobileSelect = function() {
		document.querySelectorAll( '.vcex-navbar-mobile-select select' ).forEach( function( element ) {
			var nav = element.closest( '.vcex-navbar' );
			var icon = document.createElement( 'span' );
			icon.className = 'ticon ticon-angle-down';
			icon.setAttribute( 'aria-hidden', 'true' );
			element.closest( '.vcex-navbar-mobile-select' ).appendChild( icon );
			element.addEventListener( 'change', function( event ) {
				if ( event.target.value ) {
					var targetLink = nav.querySelector( '.vcex-navbar-inner a[href="' + event.target.value + '"]' );
					if ( targetLink ) {
						targetLink.click();
					}
				}
			} );
		} );
	};
}

if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
	setTimeout( vcexNavbarMobileSelect, 0 );
} else {
	document.addEventListener( 'DOMContentLoaded', vcexNavbarMobileSelect, false );
}