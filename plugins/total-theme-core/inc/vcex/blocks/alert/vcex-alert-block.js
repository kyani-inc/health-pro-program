( function( blocks, element, serverSideRender, blockEditor, components ) {

	const el = element.createElement;
	const PlainText = blockEditor.PlainText;
	const BlockControls = blockEditor.BlockControls;
	const { Fragment } = element;
	const { RichText, InspectorControls } = blockEditor;
	const { TextControl, SelectControl, TextareaControl, Panel, PanelBody, PanelRow } = components;
	const { __ } = window.wp.i18n;

	blocks.registerBlockType( 'vcex/alert', {
		title: __( 'Alert', 'total-theme-core' ),
		icon: 'megaphone',
		category: 'total',
		keywords: [
			__( 'alert', 'total-theme-core' )
		],
		attributes: {
			type: {
				type: 'string'
			},
			heading: {
				type: 'string'
			},
			content: {
				type: 'string',
				default: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce laoreet vestibulum elit eget fringilla.',
			},
		},
		edit: function( props ) {

			return (
				el( Fragment, {},

					el( InspectorControls, {},

						/* General Settings */
						el( PanelBody, { title: __( 'General', 'total-theme-core' ), initialOpen: true },

							el( PanelRow, {},
								el( SelectControl,
									{
										label: __( 'Type', 'total-theme-core' ),
										value: props.attributes.type,
										options : [
											{ label: __( 'Default', 'total-theme-core' ), value: '' },
											{ label: __( 'Info', 'total-theme-core' ), value: 'info' },
											{ label: __( 'Success', 'total-theme-core' ), value: 'success' },
											{ label: __( 'Warning', 'total-theme-core' ), value: 'warning' },
											{ label: __( 'Error', 'total-theme-core' ), value: 'error' },
										],
										onChange: ( value ) => {
											props.setAttributes( { type: value } );
										}
									}
								)
							),

							el( PanelRow, {},
								el( TextControl,
									{
										label: __( 'Heading', 'total-theme-core' ),
										value: props.attributes.heading,
										onChange: ( value ) => {
											props.setAttributes( { heading: value } );
										}
									}
								)
							),

							el( PanelRow, {},
								el( TextareaControl,
									{
										label: __( 'Message', 'total-theme-core' ),
										value: props.attributes.content,
										onChange: ( value ) => {
											props.setAttributes( { content: value } );
										}
									}
								)
							),

						),

					),

					/** Render block **/
					el( serverSideRender, {
						block: 'vcex/alert',
						attributes: props.attributes,
					} ),

				)

			);

		},

		save: function( props ) {
			return null;
		},

	} );

} )( window.wp.blocks, window.wp.element, window.wp.serverSideRender, window.wp.blockEditor, window.wp.components );