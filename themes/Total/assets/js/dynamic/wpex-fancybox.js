( function( $, global_settings ) {
	'use strict';

	/**
	 * Parses data attribute and returns object.
	 */
	var parseObjectLiteralData = function( data ) {
		var properties = data.split( ',' );
		var obj = {};
		$.each( properties, function(index, item) {
			var tup = item.split(':');
			obj[tup[0]] = tup[1];
		} );
		return obj;
	};

	/**
	 * Initializes wpex fancybox functions.
	 */
	if ( 'function' !== typeof window.wpexFancybox ) {
		window.wpexFancybox = function() {
			wpexFancyboxSingle();
			wpexFancyboxGallery();
			wpexFancyboxInlineGallery();
			wpexFancyboxCarousels();
		};
	}

	/**
	 * Single lightbox.
	 */
	if ( 'function' !== typeof window.wpexFancyboxSingle ) {
		window.wpexFancyboxSingle = function( $context ) {

			$context = $context || $( 'body' );

			$context.on( 'click', '.wpex-lightbox, .wpex-lightbox-video, .wpb_single_image.video-lightbox a, .wpex-lightbox-autodetect, .wpex-lightbox-autodetect a', function( event ) {

				event.preventDefault();

				var $this = $( this );

				if ( ! $this.is( 'a' ) ) {
					$this = $this.find( 'a' );
				}

				if ( $this.hasClass( 'wpex-lightbox-group-item' ) ) {
					return;
				}

				var customSettings = {};
				var opts           = $this.data() || {};
				var src            = $this.attr( 'href' ) || $this.data( 'src' ) || '';
				var type           = $this.data( 'type' ) || '';
				var caption        = $this.data( 'caption' ) || '';
				var show_title     = $this.attr( 'data-show_title' ) || true;
				var oldOpts        = $this.data( 'options' ) && parseObjectLiteralData( $this.data( 'options' ) ) || '';

				if ( ! opts.parsedOpts ) {

					if ( oldOpts ) {

						if ( $this.data( 'type' ) && 'iframe' === $this.data( 'type' ) ) {
							if ( oldOpts.width && oldOpts.height ) {
								opts.width  = oldOpts.width;
								opts.height = oldOpts.height;
							}
						}

						if ( oldOpts.iframeType && 'video' === oldOpts.iframeType ) {
							type = '';
						}

					}

					if ( 'iframe' === type && opts.width && opts.height ) {
						opts.iframe = {
							css: {
								'width': opts.width,
								'height': opts.height
							}
						};
					}

					if ( 'false' !== show_title ) {
						var title = $this.data( 'title' ) || '';
						if ( title.length ) {
							var titleClass = 'fancybox-caption__title';
							if ( caption.length ) {
								titleClass = titleClass + ' fancybox-caption__title-margin';
							}
							caption = '<div class="' + titleClass + '">' + title + '</div>' + caption;
						}
					}

					if ( caption.length ) {
						opts.caption = caption;
					}

					opts.parsedOpts = true; // prevent duplicating caption since we are storing new caption in data.

				}

				if ( $this.hasClass( 'wpex-lightbox-iframe' ) ) {
					type = 'iframe'; // for use with random modules.
				}

				if ( $this.hasClass( 'wpex-lightbox-inline' ) ) {
					type = 'inline'; // for use with random modules.
				}

				if ( 'inline' === type ) {
					opts.afterLoad = function( instance, current ) {
						$( document ).trigger( 'wpex-modal-loaded' );
					};
				}

				if ( $this.hasClass( 'rev-btn' ) ) {
					type = '';
					opts = {}; // fixes rev slider issues.
				}

				$.fancybox.open( [ {
					src: src,
					opts: opts,
					type: type
				} ], $.extend( {}, global_settings, customSettings ) );

			} );

		};
	}

	/**
	 * Gallery lightbox.
	 */
	if ( 'function' !== typeof window.wpexFancyboxGallery ) {
		window.wpexFancyboxGallery = function( $context ) {

			$context = $context || $( document );

			// Prevent conflicts (can't be a group item and a single lightbox item.
			document.querySelectorAll( 'a.wpex-lightbox-group-item' ).forEach( function( element ) {
				element.classList.remove( 'wpex-lightbox' );
			} );

			// Open lightbox when clicking on group items.
			$context.on( 'click', 'a.wpex-lightbox-group-item', function( event ) {

				event.preventDefault();

				$( '.wpex-lightbox-group-item' ).removeAttr( 'data-lb-index' ); // Remove all lb-indexes to prevent issues with filterable grids or hidden items.

				var $this = $( this );
				var $group = $this.closest( '.wpex-lightbox-group' );
				var $groupItems = $group.find( 'a.wpex-lightbox-group-item:visible' );
				var customSettings = {};
				var items = [];
				var activeIndex = 0;

				$groupItems.each( function( index ) {

					var $item = $( this );
					var opts = $item.data() || {};
					var src = $item.attr( 'href' ) || $item.data( 'src' ) || '';
					var title = '';
					var show_title = $item.attr( 'data-show_title' ) || true;
					var caption = $item.data( 'caption' ) || '';
					var oldOpts = $item.data( 'options' ) && parseObjectLiteralData( '({' + $item.data( 'options' ) + '})' ) || '';

					if ( ! opts.parsedOpts ) {

						opts.thumb = $item.data( 'thumb' ) || src;

						if ( oldOpts ) {
							opts.thumb = oldOpts.thumbnail || opts.thumb;
							if ( oldOpts.iframeType && 'video' === oldOpts.iframeType ) {
								opts.type = '';
							}
						}

						if ( 'false' !== show_title ) {
							title = $item.data( 'title' ) || $item.attr( 'title' ) || '';
							if ( title.length ) {
								var titleClass = 'fancybox-caption__title';
								if ( caption.length ) {
									titleClass = titleClass + ' fancybox-caption__title-margin';
								}
								caption = '<div class="' + titleClass + '">' + title + '</div>' + caption;
							}
						}

						if ( caption.length ) {
							opts.caption = caption;
						}

						opts.parsedOpts = true;

					}

					if ( src ) {

						$item.attr( 'data-lb-index', index );

						if ( $this[0] == $item[0] ) {
							activeIndex = index;
						}

						items.push( {
							src: src,
							opts: opts
						} );

					}

				} );

				$.fancybox.open( items, $.extend( {}, global_settings, customSettings ), activeIndex );

			} );

		};
	}

	/**
	 * Inline Gallery lightbox.
	 */
	if ( 'function' !== typeof window.wpexFancyboxInlineGallery ) {
		window.wpexFancyboxInlineGallery = function( $context ) {
			$context = $context || $( document );

			$context.on( 'click', '.wpex-lightbox-gallery', function( event ) {

				event.preventDefault();

				var $this = $( this );
				var gallery = $this.data( 'gallery' ) || '';
				var items = [];

				if ( gallery.length && 'object' === typeof gallery ) {

					$.each( gallery, function( index, val ) {
						var opts = {};
						var title = val.title || '';
						var caption = val.caption || '';
						if ( title.length ) {
							var titleClass = 'fancybox-caption__title';
							if ( caption.length ) {
								titleClass = titleClass + ' fancybox-caption__title-margin';
							}
							caption = '<div class="' + titleClass + '">' + title + '</div>' + caption;
						}
						if ( caption.length ) {
							opts.caption = caption;
						}
						opts.thumb = val.thumb || val.src;
						items.push( {
							src: val.src,
							opts: opts
						} );
					} );

					$.fancybox.open( items, global_settings );

				}

			} );
		};
	}

	/**
	 * Lightbox for carousels.
	 */
	if ( 'function' !== typeof window.wpexFancyboxCarousels ) {
		window.wpexFancyboxCarousels = function( $context ) {
			$context = $context || $( document );

			$context.on( 'click', '.wpex-carousel-lightbox-item', function( event ) {

				event.preventDefault();

				var $this = $( this );
				var $parent = $this.parents( '.wpex-carousel' );
				var $owlItems = $parent.find( '.owl-item' );
				var items = [];
				var customSettings = {
					loop : true // carousels should always loop so it's not strange when clicking an item after scrolling.
				};

				$owlItems.each( function() {

					if ( ! $( this ).hasClass( 'cloned' ) ) {

						var $item = $( this ).find( '.wpex-carousel-lightbox-item' );

						if ( $item.length ) {

							var opts = {};
							var src = $item.attr( 'href' ) || $item.data( 'src' ) || '';
							var title = $item.data( 'title' ) || $item.attr( 'title' ) || '';
							var caption = $item.data( 'caption' ) || '';
							var show_title = $item.attr( 'data-show_title' ) || true;

							if ( 'false' !== show_title && title.length ) {
								var titleClass = 'fancybox-caption__title';
								if ( caption.length ) {
									titleClass = titleClass + ' fancybox-caption__title-margin';
								}
								caption = '<div class="' + titleClass + '">' + title + '</div>' + caption;
							}

							if ( caption.length ) {
								opts.caption = caption;
							}

							opts.thumb = $item.data( 'thumb' ) || src;

							items.push( {
								src  : src,
								opts : opts
							} );

						}

					}

				} );

				if ( items.length && 'object' === typeof items ) {
					var activeIndex = $this.data( 'count' ) - 1 || 0;
					$.fancybox.open( items, $.extend( {}, global_settings, customSettings ), activeIndex );
				}

			} );
		};
	}

	if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
		setTimeout( wpexFancybox, 0 );
	} else {
		document.addEventListener( 'DOMContentLoaded', wpexFancybox, false );
	}

} )( jQuery, wpex_fancybox_params );