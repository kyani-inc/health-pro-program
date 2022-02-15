if ( 'function' !== typeof window.vcexResponsiveText ) {
	window.vcexResponsiveText = function() {
		document.querySelectorAll( '.wpex-responsive-txt' ).forEach( function( element ) {
			var width = element.offsetWidth;
			var minFont = element.dataset.minFontSize || 13;
			var maxFont = element.dataset.maxFontSize || 40;
			var ratio = element.dataset.responsiveTextRatio || 10;
			var fontBase = width / ratio;
			var fontSize = ( fontBase > maxFont ? maxFont : fontBase < minFont ? minFont : fontBase ) + 'px';
			element.style.fontSize = fontSize;
		} );
	};
}

if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
	setTimeout( vcexResponsiveText, 0 );
} else {
	document.addEventListener( 'DOMContentLoaded', vcexResponsiveText, false );
}

window.addEventListener( 'resize', vcexResponsiveText );