( function() {
	'use strict';

	// Add image.
	document.addEventListener( 'click', function( event ) {
		var button = event.target.closest( '#wpex-add-term-thumbnail' );
		if ( ! button || 'undefined' === typeof wp ) {
			return;
		}
		event.preventDefault();

		var preview_img = document.querySelector( '#wpex-term-thumbnail-preview img' );

		var image = wp.media( {
			library: {
				type: 'image'
			},
			multiple: false
		} ).on( 'select', function( e ) {
			var selected = image.state().get( 'selection' ).first();
			var imageID = selected.toJSON().id;
			var imageURL = selected.toJSON().url;

			var thumbRemoveBtn = document.querySelector( '#wpex-term-thumbnail-remove' );

			if ( thumbRemoveBtn ) {
				thumbRemoveBtn.style.display = '';
			}

			if ( preview_img ) {
				preview_img.src = imageURL;
			} else {
				var previewContainer = document.querySelector( '#wpex-term-thumbnail-preview' );
				if ( previewContainer ) {
					var imgSize = previewContainer.dataset.imageSize || '40';
					var img = document.createElement( 'img' );
					img.src = imageURL;
					img.setAttribute( 'height', imgSize );
					img.setAttribute( 'width', imgSize );
					img.style.marginTop = '10px';
					previewContainer.appendChild( img );
				}
			}

			var input = document.querySelector( '#wpex_term_thumbnail' );
			if ( input ) {
				input.value = imageID;
			}

		} ).open();

	} );

	// Remove image.
	document.addEventListener( 'click', function( event ) {
		var button = event.target.closest( '#wpex-term-thumbnail-remove' );
		if ( ! button ) {
			return;
		}
		event.preventDefault();
		document.querySelector( '#wpex_term_thumbnail' ).value = '';
		document.querySelector( '#wpex-term-thumbnail-preview' ).removeChild( document.querySelector( '#wpex-term-thumbnail-preview img' ) );
		button.style.display = 'none';
	} );

})();