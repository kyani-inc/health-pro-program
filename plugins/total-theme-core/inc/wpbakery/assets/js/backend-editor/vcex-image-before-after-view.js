( function() {

	'use strict';

	if ( 'object' !== typeof vc || 'function' !== typeof vc.shortcode_view ) {
		return false;
	}

	window.vcexBackendViewImageBeforeAfter = vc.shortcode_view.extend( {
		changeShortcodeParams: function( model ) {
			window.vcexBackendViewImageBeforeAfter.__super__.changeShortcodeParams.call( this, model );

			var self = this, grid, xhr, data, beforeImage;

			xhr = new XMLHttpRequest();

			data = ''
				+ 'action=vcex_wpbakery_backend_view_image_before_after'
				+ '&source=' + model.getParam( 'source' )
				+ '&beforeImage=' +  ( model.getParam( 'before_img' ) || model.getParam( 'primary_image' ) )
				+ '&afterImage=' + ( model.getParam( 'after_img' ) || model.getParam( 'secondary_image' ) )
				+ '&beforeImageCf=' + ( model.getParam( 'before_img_custom_field' ) || model.getParam( 'primary_image_custom_field' ) )
				+ '&afterImageCf=' + ( model.getParam( 'after_img_custom_field' ) || model.getParam( 'secondary_image_custom_field' ) )
				+ '&post_id=' + vc_post_id // used for security checks
				+ '&_vcnonce=' + window.vcAdminNonce;

			xhr.onload = function() {

				if ( 4 == xhr.readyState && 200 == xhr.status ) {

					grid = self.$el[0].querySelector( '.vcex-backend-view-ba' );

					if ( grid ) {
						grid.parentNode.removeChild( grid );
					}

					var response = JSON.parse( this.responseText );
					var target = self.$el[0].getElementsByClassName( 'wpb_element_wrapper' )[0];

					// Add vcex-backend-view-ba element.
					var parentdiv = document.createElement( 'div' );
					parentdiv.className = 'vcex-backend-view-ba';
					target.appendChild( parentdiv );

					// Loop through response to add images.
					for (var key in response) {
						if ( ! response[key] ) {
							continue;
						}
						var div = document.createElement( 'div' );
						parentdiv.appendChild( div );
						var img = document.createElement( 'img' );
						img.setAttribute( 'src', response[key] );
						div.appendChild( img );
					}

				} else {
					console.log(this.responseText);
				}

			};

			xhr.open( 'POST', window.ajaxurl, true );
			xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
			xhr.send( data );

		}
	} );

})();