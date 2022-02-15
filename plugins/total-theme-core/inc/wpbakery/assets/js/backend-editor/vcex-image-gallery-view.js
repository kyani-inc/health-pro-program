( function() {

	'use strict';

	if ( 'object' !== typeof vc || 'function' !== typeof vc.shortcode_view ) {
		return false;
	}

	window.vcexBackendViewImageGallery = vc.shortcode_view.extend( {
		changeShortcodeParams: function( model ) {

			window.vcexBackendViewImageGallery.__super__.changeShortcodeParams.call( this, model );

			var self, imageIds, postGallery, customField, xhr, data, grid;

			self = this;
			imageIds = model.getParam( 'image_ids' );
			postGallery = model.getParam( 'post_gallery' );
			customField = model.getParam( 'custom_field_gallery' );

			xhr = new XMLHttpRequest();

			data = ''
				+ 'action=vcex_wpbakery_backend_view_image_gallery'
				+ '&imageIds=' + imageIds
				+ '&postGallery=' + postGallery
				+ '&customField=' + customField
				+ '&post_id=' + vc_post_id // used for security checks
				+ '&_vcnonce=' + window.vcAdminNonce;

			xhr.onload = function() {

				if ( 4 == xhr.readyState && 200 == xhr.status ) {

					grid = self.$el[0].querySelector( '.vcex-backend-view-images' );

					if ( grid ) {
						grid.parentNode.removeChild( grid );
					}

					var response = JSON.parse( this.responseText );

					if ( ! response.length ) {
						return; // nothing to add
					}

					var target = self.$el[0].getElementsByClassName( 'wpb_element_wrapper' )[0];

					// Add vcex-backend-view-ba element.
					var parentdiv = document.createElement( 'div' );
					parentdiv.className = 'vcex-backend-view-images';
					target.appendChild( parentdiv );

					// Loop through response to add images.
					for (var i = 0; i < response.length; i++) {
						var img = document.createElement( 'img' );
						img.src = response[i];
						parentdiv.appendChild( img );
					}

				} else {
					console.log( this.responseText );
				}

			};

			xhr.open( 'POST', window.ajaxurl, true );
			xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
			xhr.send( data );

		}
	} );

})();