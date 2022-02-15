( function( $ ) {
	'use strict';

	$( document ).ready( function() {

		// Social Sorter.
		function socialSorter() {
			$( '#widgets-right .wpex-social-widget-services-list, .customize-control .wpex-social-widget-services-list' ).each( function() {
				var id = $( this ).attr( 'id' ),
					$el = $( '#' + id );
				$el.sortable( {
					placeholder: "placeholder",
					opacity: 0.6,
					update: function( event, ui ) {
						if ( wp.customize !== undefined ) {
							$el.find( 'input.ui-sortable-handle' ).trigger( 'change' );
						} else {
							$el.find( 'input.wpex-social-widget-services-hidden-field' ).trigger( 'change' );
						}
					}
				} );
			} );
		}
		socialSorter();

		// Customizer support.
		$( document ).on( 'widget-updated', socialSorter );
		$( document ).on( 'widget-added', socialSorter );

		// Repeater field
		function repeaterField() {

			// Add items.
			$( 'body' ).on( 'click', '.wpex-widget-settings-form .wpex-rpf-add', function( event ) {
				event.preventDefault();

				var widgetForm = $( this ).closest( '.wpex-widget-settings-form' ),
					cloneEl    = widgetForm.find( '.wpex-rpf-clone' ),
					cloneHTML  = $( '<li>' + cloneEl.html() + '</li>' );

				widgetForm.find( '.wpex-repeater-field' ).append( cloneHTML );

				cloneHTML.find( 'p' ).eq(0).find( 'input' ).eq(0).focus();

			} );

			// Delete items.
			$( 'body' ).on( 'click', '.wpex-widget-settings-form .wpex-rpf-remove', function( event ) {
				event.preventDefault();
				if ( confirm( wpexCustomWidgets.confirm ) ) {
					$( this ).parent().find( 'input[type="text"]' ).trigger( 'change' );
					$( this ).closest( 'li' ).remove();
				}
			} );

		} repeaterField();

		// Repeater Field sort.
		function sortRepeatableFields() {
			$( '#widgets-right .wpex-widget-settings-form .wpex-repeater-field, .customize-control .wpex-widget-settings-form .wpex-repeater-field' ).each( function() {
				var id = $( this ).attr( 'id' ),
					$el = $( '#' + id );
				$el.sortable( {
					revert : false,
					delay : 100,
					cursor : 'move',
					placeholder: 'wpex-rpf-placeholder',
					opacity: 0.8,
					start: function( e, ui ) {
						ui.placeholder.height( ui.item.height() );
					},
					update: function( event, ui ) {
						$el.find( 'input' ).eq(0).trigger( 'change' );
					}
				} );
			} );
		}

		sortRepeatableFields();

		// Re-run sorting as needed.
		$( document ).on( 'widget-updated', sortRepeatableFields );
		$( document ).on( 'widget-added', sortRepeatableFields );

		// Media button.
		function uploadMediaField() {

			var _custom_media = true,
			_orig_send_attachment = wp.media.editor.send.attachment;


			$( document ).on( 'click', '.wpex-widget-settings-form .wpex-upload-button', function() {

				window.wpActiveEditor = null; // fixes console error.

				var send_attachment_bkp	= wp.media.editor.send.attachment,
					button = $( this ),
					id = button.prev();
					_custom_media = true;

				wp.media.editor.send.attachment = function( props, attachment ) {
					if ( _custom_media ) {
						id.val( attachment.id ).trigger( 'change' );
					} else {
						return _orig_send_attachment.apply( this, [props, attachment] );
					};
				}

				wp.media.editor.open();

				return false;

			} );

			$( '.add_media' ).on( 'click', function() {
				_custom_media = false;
			} );

		} uploadMediaField();

		// Customizer support.
		$( document ).on( 'widget-updated', uploadMediaField );
		$( document ).on( 'widget-added', uploadMediaField );

	} );

} ) ( jQuery );