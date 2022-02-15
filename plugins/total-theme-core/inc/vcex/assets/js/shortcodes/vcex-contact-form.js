if ( 'function' !== typeof window.vcexContactForm ) {
	window.vcexContactForm = function() {

		document.querySelectorAll( '.vcex-contact-form form' ).forEach( function( element ) {
			element.addEventListener( 'submit', function( event ) {
				var form = event.target.closest( '.vcex-contact-form' );
				var button = form.querySelector( 'button.vcex-contact-form__submit' );
				var spinner = form.querySelector( '.vcex-contact-form__spinner' );
				var name = form.querySelector( 'input[name="vcex_cf_name"]' );
				var email = form.querySelector( 'input[name="vcex_cf_email"]' );
				var message = form.querySelector( 'textarea[name="vcex_cf_message"]' );
				var privacy_check = form.querySelector( '.vcex-contact-form__privacy .vcex-contact-form__checkbox' );
				var notice = form.querySelector( '.vcex-contact-form__notice' );
				var ajaxurl = form.dataset.ajaxurl;
				var nonce = form.dataset.nonce;
				var subject = form.dataset.subject;
				var recaptcha = form.dataset.recaptcha;
				var recaptchaResponse = '';

				// Get labels to pass to the email as these can be custom on each form.
				var label_name = form.querySelector( '.vcex-contact-form__name .vcex-contact-form__label' );
					label_name = label_name ? label_name.firstChild.data : '';
				var label_email = form.querySelector( '.vcex-contact-form__email .vcex-contact-form__label' );
					label_email = label_email ? label_email.firstChild.data : '';
				var label_message = form.querySelector( '.vcex-contact-form__message .vcex-contact-form__label' );
					label_message = label_message ? label_message.firstChild.data : '';

				var ajax = function() {
					notice.classList.add( 'wpex-hidden' );
					spinner.classList.remove( 'wpex-hidden' );
					button.disabled = true;

					var xhr = new XMLHttpRequest();

					var data = ''
						+ 'action=vcex_contact_form_action'
						+ '&name=' + name.value
						+ '&subject=' + subject
						+ '&label_name=' + label_name
						+ '&label_email=' + label_email
						+ '&label_message=' + label_message
						+ '&email=' + email.value
						+ '&message=' + message.value
						+ '&recaptcha=' + recaptchaResponse
						+ '&nonce=' + nonce;

					xhr.onload = function() {

						spinner.classList.add( 'wpex-hidden' );
						button.disabled = false;

						var status = JSON.parse( this.responseText );

						if ( 4 == xhr.readyState && 200 == xhr.status ) {

							switch ( status ) {
								case 'success':
									name.value = '';
									email.value = '';;
									message.value = '';;
									notice.classList.remove( 'wpex-hidden', 'wpex-alert-error' );
									notice.classList.add( 'wpex-alert-success' );
									notice.innerHTML = form.dataset.noticeSuccess;
									break;
								default:
									notice.classList.remove( 'wpex-hidden', 'wpex-alert-success' );
									notice.classList.add( 'wpex-alert-error' );
									notice.innerHTML = form.dataset.noticeError;
									console.log( this.responseText );
							}

						} else {
							console.log( this.responseText );
						}

					};

					xhr.open( 'POST', ajaxurl, true );
					xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
					xhr.send( data );

				};

				if ( privacy_check && ! privacy_check.checked ) {
					event.preventDefault();
					return;
				}

				if ( recaptcha && 'object' === typeof grecaptcha ) {
					grecaptcha.ready(function () {
						grecaptcha.execute( recaptcha, {
							action: 'vcex_contact_form'
						} ).then( function( token ) {
							recaptchaResponse = token;
							ajax();
						} );
					} );
				} else {
					ajax();
				}

				event.preventDefault();

			} );
		} );

	};
}

vcexContactForm();