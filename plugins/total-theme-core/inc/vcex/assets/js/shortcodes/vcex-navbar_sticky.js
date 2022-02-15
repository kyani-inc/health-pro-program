( function( $ ) {

	'use strict';

	if ( 'function' !== typeof window.vcexStickyNavbar ) {
		window.vcexStickyNavbar = function( $context ) {

			var $nav = $( '.vcex-navbar-sticky' ),
				$window = $( window );

			if ( ! $nav.length ) {
				return;
			}

			$nav.each( function() {
				var $this = $( this );
				var $isSticky = false;
				var $stickyEndPoint = $this.data( 'sticky-endpoint' ) ? $( $this.data( 'sticky-endpoint' ) ) : '';

				// Add sticky wrap.
				var $stickyWrap = $( '<div class="vcex-navbar-sticky-wrapper not-sticky"></div>' );
				$this.wrapAll( $stickyWrap );
				$stickyWrap = $this.parent( '.vcex-navbar-sticky-wrapper' );

				// Check sticky offSet based on other sticky elements.
				function getStickyOffset() {

					var offset = 0;
					var items = '';

					if ( $this.data( 'vcex-sticky-offset-items' ) ) {
						items = $this.data( 'vcex-sticky-offset-items' );
					} else {
						items = '#top-bar-wrap-sticky-wrapper.wpex-can-sticky #top-bar-wrap,#site-header-sticky-wrapper.wpex-can-sticky #site-header,#site-navigation-sticky-wrapper.wpex-can-sticky,#wpex-mobile-menu-fixed-top,#wpadminbar,.wpex-sticky-el-offset';
					}

					if ( ! items ) {
						return;
					}

					items = items.split( ',' );

					$.each( items, function( index, value ) {
						var $this = $( value );
						if ( $this.is( ':visible' ) ) {
							offset = parseInt( offset ) + parseInt( $this.outerHeight() );
						}
					} );

					return offset;

				}

				// Set sticky.
				function setSticky( $offset ) {

					// Return if hidden.
					if ( ! $this.is( ':visible' ) ) {
						destroySticky(); // make sure to destroy if hidden
						return;
					}

					// Already sticky or hidden.
					if ( $isSticky ) {
						$this.css( {
							'top' : getStickyOffset() // recalculate for shrink sticky elements
						} );
						return;
					}

					// Set placeholder.
					$stickyWrap
						.css( 'height', $this.outerHeight() )
						.removeClass( 'not-sticky' )
						.addClass( 'is-sticky' );

					// Position Fixed nav.
					$this.css( {
						'top': $offset,
						'width': $stickyWrap.width()
					} );

					// Update sticky var.
					$isSticky = true;

				}

				// Un-Shrink header function.
				function destroySticky() {

					// Not sticky
					if ( ! $isSticky ) {
						return;
					}

					// Remove sticky wrap height and toggle sticky class.
					$stickyWrap
						.css( 'height', '' )
						.removeClass( 'is-sticky' )
						.addClass( 'not-sticky' );

					// Remove navbar width.
					$this.css( {
						'width' : '',
						'top'   : ''
					} );

					// Update shrunk var.
					$isSticky = false;

				}

				// On scroll function.
				function stickyCheck() {

					var windowTop = $( window ).scrollTop(),
						stickyOffset = getStickyOffset(),
						stickyWrapTop = $stickyWrap.offset().top,
						setStickyPos = stickyWrapTop - stickyOffset;

					if ( windowTop > setStickyPos && 0 !== windowTop ) {
						setSticky( stickyOffset );
						if ( $stickyEndPoint.length && $stickyEndPoint.is( ':visible' ) ) {
							if ( windowTop > ( $stickyEndPoint.offset().top - stickyOffset - $this.outerHeight() ) ) {
								$stickyWrap.addClass( 'sticky-hidden' );
							} else {
								$stickyWrap.removeClass( 'sticky-hidden' );
							}
						}
					} else {
						destroySticky();
					}

				}

				// On resize function.
				function onResize() {

					// Should it be sticky?
					stickyCheck();

					// Sticky fixes.
					if ( $isSticky ) {

						// Destroy if hidden.
						if ( ! $this.is( ':visible' ) ) {
							destroySticky();
						}

						// Set correct height on wrapper.
						$stickyWrap.css( 'height', $this.outerHeight() );

						// Set correct width and offset value on sticky element.
						$this.css( {
							'top': getStickyOffset(),
							'width': $stickyWrap.width()
						} );

					}

					// Should it become sticky?
					else {
						stickyCheck();
					}

				}

				// Fire on init.
				stickyCheck();

				// Fire onscroll event.
				window.addEventListener( 'scroll', stickyCheck, { passive: true } );

				// Fire onResize.
				window.addEventListener( 'resize', onResize );

				// Fire resize on flip.
				$window.on( 'orientationchange', function( e ) {
					destroySticky();
					stickyCheck();
				} );

			} ); // End each

		};
	}

	$( window ).on( 'load', function() {
		vcexStickyNavbar();
	} );

	// Fix potential width issues with sticky elements inside WPBakery stretched rows in Firefox/Safari.
	// This is because sometimes window.load loads after document.ready
	$( document ).on( 'vc_js', function() {
		document.querySelectorAll( '[data-vc-full-width-init] .vcex-navbar-sticky' ).forEach( function( element ) {
			var stickyWrapper = element.closest( '.vcex-navbar-sticky-wrapper' );
			if ( stickyWrapper ) {
				element.style.width = stickyWrapper.getBoundingClientRect().width + 'px';
			}
		} );
	} );

} ) ( jQuery );