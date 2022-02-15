( function() {

	'use strict';

	if ( 'object' !== typeof vc || 'function' !== typeof vc.shortcode_view ) {
		return false;
	}

	window.vcexBackendViewImage = vc.shortcode_view.extend( {
		changeShortcodeParams: function( model ) {
			window.vcexBackendViewImage.__super__.changeShortcodeParams.call( this, model );

			var self = this, imageSource, imageData, thumbnail, xhr, data;

			imageSource = _.isString( model.getParam( 'source' ) ) ? model.getParam( 'source' ) : '';

			if ( ! imageSource && _.isString( model.getParam( 'image_source' ) ) ) {
				imageSource = model.getParam( 'image_source' );
			}

			if ( imageSource ) {

				switch( imageSource ) {
					case 'external':
						imageData = model.getParam( 'external_image' );
					break;
					case 'custom_field':
						imageData = model.getParam( 'custom_field_name' );
						if ( ! imageData ) {
							imageData = model.getParam( 'image_custom_field' );
						}
					break;
					default:
						imageData = model.getParam( 'image_id' );
						if ( ! imageData ) {
							imageData = model.getParam( 'image' );
						}
				}

				xhr = new XMLHttpRequest();

				data = ''
					+ 'action=vcex_wpbakery_backend_view_image'
					+ '&content=' + imageData
					+ '&size=thumbnail'
					+ '&image_source=' + imageSource
					+ '&post_id=' + vc_post_id // used for security checks
					+ '&_vcnonce=' + window.vcAdminNonce;

				xhr.onload = function() {

					if ( 4 == xhr.readyState && 200 == xhr.status ) {

						thumbnail = self.$el[0].querySelector( '.vcex_wpb_image_holder' );

						if ( thumbnail ) {
							thumbnail.parentNode.removeChild( thumbnail );
						}

						if ( ! this.responseText ) {
							return;
						}

						var target = self.$el[0].getElementsByClassName( 'wpb_element_wrapper' )[0];

						var holder = document.createElement( 'p' );
						holder.className = 'vcex_wpb_image_holder';
						target.appendChild( holder );

						var img = document.createElement( 'img' );
						img.src = this.responseText;
						holder.appendChild( img );

					} else {
						console.log( this.responseText );
					}

				};

				xhr.open( 'POST', window.ajaxurl, true );
				xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
				xhr.send( data );

			}

		}
	} );

})();