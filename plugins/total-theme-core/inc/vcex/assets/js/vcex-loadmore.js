( function( $ ) {

	'use strict';

	if ( 'function' !== typeof window.vcexLoadMore ) {
		window.vcexLoadMore = function( $context ) {

			$( '.vcex-loadmore' ).each( function() {

				var $buttonWrap = $( this );
				var $button = $buttonWrap.find( '.vcex-loadmore-button' );

				if ( ! $button.length ) {
					return;
				}

				var $grid = $buttonWrap.parent().find( '> .wpex-row, > .entries, > .vcex-recent-news, .vcex-image-grid, .wpex-post-cards-list' );
				var loading = false;
				var ajaxUrl = vcex_loadmore_params.ajax_url;
				var loadMoreData = $button.data();
				var page = loadMoreData.page + 1;
				var maxPages = loadMoreData.maxPages;
				var $textSpan = $button.find( '.vcex-txt' );
				var text = loadMoreData.text;
				var loadingText = loadMoreData.loadingText;
				var failedText = loadMoreData.failedText;

				$buttonWrap.css( 'min-height', $buttonWrap.outerHeight() ); // prevent jump when showing loader icon

				$button.on( 'click', function( e ) {

					var shortcodeParams = loadMoreData.shortcodeParams; // this gets updated on each refresh

					shortcodeParams.paged = page; // update paged value

					if ( ! loading ) {

						loading = true;

						$button.parent().addClass( 'vcex-loading' );
						$textSpan.text( loadingText );

						var data = {
							action: 'vcex_loadmore_ajax_render',
							nonce: loadMoreData.nonce,
							shortcodeTag: loadMoreData.shortcodeTag,
							shortcodeParams: shortcodeParams
						};

						$.post( ajaxUrl, data, function( res ) {

							var $newElements = '';

							if ( res.success ) {

								page = page + 1;

								if ( $grid.parent().hasClass( 'vcex-post-type-archive' ) ) {
									$newElements = $( res.data ).find( '> .wpex-row > .col, > .wpex-row > .blog-entry, #blog-entries > .blog-entry' );
								} else {
									$newElements = $( res.data ).find( '> .wpex-row > .vcex-grid-item, > .vcex-recent-news > .vcex-recent-news-entry-wrap, .vcex-image-grid-entry, .wpex-post-cards-entry' );
								}

								if ( $newElements.length ) {

									$newElements.css( 'opacity', 0 ); // hide until images are loaded

									$newElements.each( function() {
										var $this = $( this );
										if ( $this.hasClass( 'sticky' ) ) {
											$this.addClass( 'vcex-duplicate' );
										}
									} );

									$grid.append( $newElements ).imagesLoaded( function() {

										if ( 'object' === typeof wpex && 'function' === typeof wpex.equalHeights ) {
											wpex.equalHeights();
										}

										if ( 'function' === typeof Isotope && ( $grid.hasClass( 'vcex-isotope-grid' ) || $grid.hasClass( 'vcex-navbar-filter-grid' ) || $grid.hasClass( 'wpex-masonry-grid' ) ) ) {
											var isotope = Isotope.data( $grid[0] );
											if ( isotope ) {
												isotope.appended( $newElements );
												isotope.layout();
											} else {
												$newElements.css( 'opacity', 1 );
											}
										} else {
											$newElements.css( 'opacity', 1 );
										}

										if ( $grid.hasClass( 'justified-gallery' ) && 'undefined' !== typeof $.fn.justifiedGallery ) {
											$grid.justifiedGallery( 'norewind' );
										}

										if ( 'object' === typeof wpex ) {

											if ( 'function' === typeof wpex.overlaysMobileSupport ) {
												wpex.overlaysMobileSupport();
											}

											if ( 'function' === typeof wpex.hoverStyles ) {
												wpex.hoverStyles();
											}

										}

										$( '.wpb_animate_when_almost_visible', $grid ).addClass( 'wpb_start_animation animated' );

										if ( 'function' === typeof window.wpexSliderPro ) {
											window.wpexSliderPro( $newElements );
										}

										if ( 'undefined' !== typeof $.fn.mediaelementplayer ) {
											$newElements.find( 'audio, video' ).mediaelementplayer();
										}

										$grid.trigger( 'vcexLoadMoreFinished', [$newElements] ); // Use this trigger if you need to run other js functions after items are loaded

										// Update loadMoreData with new data (used for clearing floats, etc).
										var newData  = $( res.data ).find( '.vcex-loadmore-button' ).data();
										loadMoreData = newData ? newData : loadMoreData;

										$button.parent().removeClass( 'vcex-loading' );
										$textSpan.text( text );

										// Set correct focus.
										var $firstLink = $newElements.first().find( 'a' );

										if ( $firstLink.length ) {
											$firstLink.eq(0).focus();
										}

										// Hide button.
										if ( ( page - 1 ) == maxPages ) {
											$buttonWrap.hide();
										}

										// Set loading to false.
										loading = false;

									} ); // End images loaded.

								} // End $newElements check.

								else {

									console.log( res );

								}

							} // End success.

							else {

								$button.text( failedText );

								console.log( res );

							}

						} ).fail( function( xhr, textGridster, e ) {

							console.log( xhr.responseText );

						} );

					} // end loading check

					return false;

				} ); // end click event

			} );

		};
	}

	$( window ).on( 'load', function() {
		vcexLoadMore();
	} );

} ) ( jQuery );