document.addEventListener( 'click', function( event ) {

	var button = event.target.closest( '[data-wpex-migrate-widget-areas]' ), xhr, data, loader;

	if ( ! button ) {
		return;
	}

	event.preventDefault();

	loader = document.getElementsByClassName( 'wpex-migrate-widget-areas-loader' )[0];

	xhr = new XMLHttpRequest();

	data = ''
		+ 'action=wpex_widget_areas_migrate'
		+ '&nonce=' + button.dataset.nonce;

	xhr.onload = function() {

		if ( 4 == xhr.readyState && 200 == xhr.status ) {

			var result = this.responseText;

			if ( ! result ) {
				loader.classList.add( 'hidden' );
				button.classList.remove( 'hidden' );
				alert( 'Something wen\'t wrong, please try again. If you still have issues please contact the theme developer.' );
				return;
			}

			result = JSON.parse( this.responseText );

			if ( ! result.length ) {
				return;
			}

			loader.classList.add( 'hidden' );

			var newLine = "\r\n";
			var message = 'The following widget areas were imported:' + newLine;

			for (var i =0; i < result.length; i++) {
				message += newLine;
				message += result[i];
			}

			document.getElementById( 'wpex-migrate-widget-areas-notice' ).classList.add( 'hidden' );
			alert( message );
			window.location.reload();

		} else {
			console.log( this.responseText );
		}
	};

	button.classList.add( 'hidden' );
	loader.classList.remove( 'hidden' );

	xhr.open( 'POST', window.ajaxurl, true );
	xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
	xhr.send( data );

} );