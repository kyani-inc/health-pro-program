( function() {

	'use strict';

	var runVcexFunctions = function() {

		if ( 'function' === typeof window.vcexCarousels ) {
			vcexCarousels();
		}

		if ( 'function' === typeof window.vcexResponsiveCSS ) {
			vcexResponsiveCSS();
		}

		if ( 'function' === typeof window.vcexResponsiveText ) {
			vcexResponsiveText();
		}

		if ( 'function' === typeof window.vcexStickyNavbar ) {
			vcexStickyNavbar();
		}

		if ( 'function' === typeof window.vcexNavbarMobileSelect ) {
			vcexNavbarMobileSelect();
		}

		if ( 'function' === typeof window.vcexIsotopeGrids ) {
			vcexIsotopeGrids();
		}

		if ( 'function' === typeof window.vcexNavbarFilterLinks ) {
			vcexNavbarFilterLinks();
		}

		if ( 'function' === typeof window.vcexBeforeAfter ) {
			vcexBeforeAfter();
		}

		if ( 'function' === typeof window.vcexJustifiedGallery ) {
			vcexJustifiedGallery();
		}

		if ( 'function' === typeof window.vcexAnimatedText ) {
			vcexAnimatedText();
		}

		if ( 'function' === typeof window.vcexMilestone ) {
			vcexMilestone();
		}

		if ( 'function' === typeof window.vcexSkillbar ) {
			vcexSkillbar();
		}

		if ( 'function' === typeof window.vcexCountDown ) {
			vcexCountDown();
		}

	};

	jQuery( window ).on( 'vc_reload', runVcexFunctions );

})();