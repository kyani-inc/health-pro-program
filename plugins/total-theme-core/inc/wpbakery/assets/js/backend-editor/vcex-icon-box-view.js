( function() {

	'use strict';

	if ( 'object' !== typeof vc || 'function' !== typeof vc.shortcode_view ) {
		return false;
	}

	window.vcexIconBoxVcBackendView = vc.shortcode_view.extend( {
		changeShortcodeParams: function( model ) {
			window.vcexIconBoxVcBackendView.__super__.changeShortcodeParams.call( this, model );
			var heading, target;
			target = this.$el[0].querySelector( '.vcex-heading-text > span' );
			if ( target ) {
				heading = model.getParam( 'heading' );
				if ( heading && _.isString( heading ) && ! heading.match(/^#E\-8_/) ) {
					target.textContent = ': ' + heading;
				} else {
					target.textContent = '';
				}
			}
		}
	} );

})();