( function( $ ) {

	'use strict';

	if ( 'function' !== typeof window.wpexWCLightboxGallery ) {
		window.wpexWCLightboxGallery = function () {

			if ( 'function' !== typeof $.fn.fancybox ) {
				return;
			}

			$( document ).on( 'click', '.woocommerce-product-gallery__image', function () {

				event.preventDefault(); // important

				var $gallery = $( this ).parents( '.woocommerce-product-gallery__wrapper' );
				var $items = $( '.woocommerce-product-gallery__image > a', $gallery );

				$items.each( function( index, item ) {
					$( item ).addClass( 'wpex-woo-lightbox-item' ).attr( 'data-index', index );
				} );

				var images = [];
				var activeIndex = $( this ).find( '.wpex-woo-lightbox-item' ).data( 'index' );

				$items.each( function() {

					var $this = $( this );
					var opts = {};
					var title = '';
					var href = $this.attr( 'href' ) || '';
					var src = href;
					var img = $this.find( 'img' );

					if ( img.length ) {

						if ( wpex_wc_lightbox_params.showTitle ) {
							title = img.attr( 'data-caption' ) || img.attr( 'title' ) || '';
						}

						if ( title.length ) {
							opts.caption = '<div class="fancybox-caption__title">' + title + '</div>';
						}

						opts.thumb = src;

						images.push( {
							src: src,
							opts: opts
						} );

					}

				} );

				if ( images.length > 1 ) {
					$.fancybox.open( images, wpex_fancybox_params, parseInt( activeIndex ) );
				} else {
					$.fancybox.open( images, wpex_fancybox_params );
				}

			} );

		 };
	 }

	$( document ).ready( function() {
		wpexWCLightboxGallery();
	} );

} ) ( jQuery );