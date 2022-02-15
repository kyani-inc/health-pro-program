( function() {

	'use strict';

	if ( 'object' !== typeof vc || 'function' !== typeof vc.shortcode_view ) {
		return false;
	}

	window.vcexHeadingView = vc.shortcode_view.extend( {
		changeShortcodeParams: function( model ) {
			window.vcexHeadingView.__super__.changeShortcodeParams.call( this, model );
			var text, source, target, inverted_value;
			text = model.getParam( 'text' );
			source = model.getParam( 'source' );
			target = this.$el[0].querySelector( '.vcex-heading-text > span' );
			if ( text && _.isString( text ) && ! text.match(/^#E\-8_/) ) {
				switch( source ) {
					case 'custom':
						target.textContent = ': ' + text;
						break;
					default:
						inverted_value = _.invert( this.params.source.value );
						target.textContent = ': ' + inverted_value[source];
				}
			} else {
				target.textContent = '';
			}
		}
	} );

})();