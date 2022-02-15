/**
 * Update Customizer settings live.
 *
 * @version 5.3.1
 */
( function( api, $ ) {

	'use strict';

	if ( ! wp || ! wp.customize ) {
		console.log( 'wp or wp.customize objects not found.' );
		return;
	}

	// Declare variables.
	var siteheader = $( '#site-header' );

	/******** General *********/

		// Pagination alignment class.
		api( 'pagination_align', function( setting ) {
			setting.bind( function( newval ) {
				document.querySelectorAll( '.wpex-pagination, .woocommerce-pagination' ).forEach( function( pagination ) {
					pagination.classList.remove( 'textcenter', 'textleft', 'textright' );
					pagination.classList.add( 'text' + newval );
				} );
			} );
		} );

		// Pagination arrow.
		api( 'pagination_arrow', function( setting ) {
			setting.bind( function( newval ) {
				var next,prev;
				if ( document.body.classList.contains( 'rtl' ) ) {
					next = document.querySelector( 'ul.page-numbers .next .ticon' );
					prev = document.querySelector( 'ul.page-numbers .prev .ticon' );
				} else {
					prev = document.querySelector( 'ul.page-numbers .next .ticon' );
					next = document.querySelector( 'ul.page-numbers .prev .ticon' );
				}
				if ( next ) {
					next.className = 'ticon ticon-' + newval + '-left';
				}
				if ( prev ) {
					prev.className = 'ticon ticon-' + newval + '-right';
				}
			} );
		} );

		// Theme heading alignment.
		api( 'theme_heading_align', function( setting ) {
			setting.bind( function( newval ) {
				document.querySelectorAll( '.theme-heading' ).forEach( function( heading ) {
					heading.classList.remove( 'wpex-text-left', 'wpex-text-center', 'wpex-text-right' );
					heading.classList.add( 'wpex-text-' + newval );
				} );
			} );
		} );

	/******** Layouts *********/

	api( 'boxed_dropdshadow', function( setting ) {
		setting.bind( function( newval ) {
			if ( newval ) {
				document.body.classList.add( 'wrap-boxshadow' );
			} else {
				document.body.classList.remove( 'wrap-boxshadow' );
			}
		} );
	} );

	api( 'header_flex_items', function( setting ) {
		setting.bind( function( newval ) {
			var header = document.querySelector( '#site-header' );
			if ( ! header ) {
				return;
			}
			if ( newval ) {
				header.classList.add( 'wpex-header-two-flex-v' );
			} else {
				header.classList.remove( 'wpex-header-two-flex-v' );
			}
		} );
	} );

	api( 'site_frame_border', function( setting ) {
		setting.bind( function( newval ) {
			if ( newval ) {
				document.body.classList.add( 'has-frame-border' );
			} else {
				document.body.classList.remove( 'has-frame-border' );
			}
		} );
	} );

	/******** TOPBAR *********/

		api( 'top_bar_fullwidth', function( setting ) {
			setting.bind( function( newval ) {
				var topBarWrap = document.querySelector( '#top-bar-wrap' );
				var topBar = document.querySelector( '#top-bar' );
				if ( ! topBarWrap || ! topBar ) {
					return;
				}
				if ( newval ) {
					topBarWrap.classList.add( 'wpex-full-width', 'wpex-px-30' );
					topBar.classList.remove( 'container' );
				} else {
					topBarWrap.classList.remove( 'wpex-full-width', 'wpex-px-30' );
					topBar.classList.add( 'container' );
				}
			} );
		} );

		api( 'top_bar_bottom_border', function( setting ) {
			setting.bind( function( newval ) {
				var topBarWrap = document.querySelector( '#top-bar-wrap' );
				if ( ! topBarWrap ) {
					return;
				}
				if ( newval ) {
					topBarWrap.classList.add( 'wpex-border-b', 'wpex-border-main', 'wpex-border-solid' );
				} else {
				   topBarWrap.classList.remove( 'wpex-border-b', 'wpex-border-main', 'wpex-border-solid' );
				}
			} );
		} );

	/******** HEADER *********/

		// Full-width header
		api( 'full_width_header', function( setting ) {
			setting.bind( function( newval ) {
				var header = document.querySelector( '#site-header' );
				if ( ! header ) {
					return;
				}
				if ( newval ) {
					header.classList.add( 'wpex-full-width' );
				} else {
					header.classList.remove( 'wpex-full-width' );
				}
			} );
		} );

		// Header Vertical Style - Fixed or not fixed
		api( 'vertical_header_style', function( setting ) {
			setting.bind( function( newval ) {
				if ( newval ) {
					document.body.classList.add( 'wpex-fixed-vertical-header' );
				} else {
					document.body.classList.remove( 'wpex-fixed-vertical-header' );
				}
			} );
		} );

		// Header borders
		api( 'header_menu_disable_borders', function( setting ) {
			setting.bind( function( newval ) {
				var nav = document.querySelector( '.navbar-style-two, .navbar-style-six' );
				if ( ! nav ) {
					return;
				}
				if ( newval ) {
					nav.classList.add( 'no-borders' );
				} else {
					nav.classList.remove( 'no-borders' );
				}
			} );
		} );

		// Header Center
		api( 'header_menu_center', function( setting ) {
			setting.bind( function( newval ) {
				var nav = document.querySelector( '.navbar-style-two' );
				if ( ! nav ) {
					return;
				}
				if ( newval ) {
					nav.classList.add( 'center-items' );
				} else {
					nav.classList.remove( 'center-items' );
				}
			} );
		} );

		// Header menu stretch
		api( 'header_menu_stretch_items', function( setting ) {
			setting.bind( function( newval ) {
				document.querySelectorAll( '.navbar-style-two, .navbar-style-three, .navbar-style-four, .navbar-style-five' ).forEach( function( nav ) {
					if ( newval ) {
						nav.classList.add( 'wpex-stretch-items' );
					} else {
						nav.classList.remove( 'wpex-stretch-items' );
					}
				} );
			} );
		} );

	/******** NAVBAR *********/

		api( 'menu_dropdown_style', function( setting ) {
			setting.bind( function( newval ) {
				var headerClasses = siteheader.attr( 'class' ).split( ' ' );
				for(var i = 0; i < headerClasses.length; i++) {
					if ( headerClasses[i].indexOf( 'wpex-dropdown-style-' ) != -1 ) {
						siteheader.removeClass(headerClasses[i]);
					}
				}
				siteheader.addClass( 'wpex-dropdown-style-' + newval );
			} );
		} );

		api( 'menu_dropdown_dropshadow', function( setting ) {
			setting.bind( function( newval ) {
				var headerClasses = siteheader.attr( 'class' ).split( ' ' );
				for(var i = 0; i < headerClasses.length; i++) {
					if(headerClasses[i].indexOf( 'wpex-dropdowns-shadow-' ) != -1) {
						siteheader.removeClass(headerClasses[i]);
					}
				}
				siteheader.addClass( 'wpex-dropdowns-shadow-' + newval );
			} );
		} );

	/******** Mobile Menu *********/

		api( 'full_screen_mobile_menu_style', function( setting ) {
			setting.bind( function( newval ) {
				var nav = document.querySelector( '.full-screen-overlay-nav' );
				if ( nav ) {
					nav.classList.remove( 'white', 'black' );
					nav.classList.add( newval );
				}
			} );
		} );

	/******** Sidebar *********/

		api( 'has_widget_icons', function( setting ) {
			setting.bind( function( newval ) {
				if ( newval ) {
					document.body.classList.add( 'sidebar-widget-icons' );
				} else {
					document.body.classList.remove( 'sidebar-widget-icons' );
				}
			} );
		} );

	/******** Sidebar *********/

		api( 'sidebar_headings', function( setting ) {
			setting.bind( function( newval ) {
				var headings = $( '.sidebar-box .widget-title' );
				headings.each( function() {
					$( this ).replaceWith( '<' + newval + ' class="widget-title">' + this.innerHTML + '</' + newval + '>' );
				} );
			} );
		} );

	/******** Blog *********/

		api( 'blog_single_header_custom_text', function( setting ) {
			var title = document.querySelector( 'body.single-post .page-header-title' );
			if ( ! title ) {
				return;
			}
			var ogTitle = setting.get();
			setting.bind( function( newval ) {
				if ( newval ) {
					title.innerHTML = newval;
				} else {
					title.innerHTML = ogTitle;
				}
			} );
		} );

		api( 'blog_related_title', function( setting ) {
			var heading = document.querySelector( '.related-posts-title span.text' );
			if ( ! heading ) {
				return;
			}
			var ogheading = setting.get();
			setting.bind( function( newval ) {
				if ( newval ) {
					heading.innerHTML = newval;
				} else {
					heading.innerHTML = ogheading;
				}
			} );
		} );

	/******** Portfolio *********/

		api( 'portfolio_related_title', function( setting ) {
			var heading = document.querySelector( '.related-portfolio-posts-heading span.text' );
			if ( ! heading ) {
				return;
			}
			var ogheading = setting.get();
			setting.bind( function( newval ) {
				if ( newval ) {
					heading.innerHTML = newval;
				} else {
					heading.innerHTML = ogheading;
				}
			} );
		} );

	/******** Staff *********/

		api( 'staff_related_title', function( setting ) {
			var heading = document.querySelector( '.related-staff-posts-heading span.text' );
			if ( ! heading ) {
				return;
			}
			var ogheading = setting.get();
			setting.bind( function( newval ) {
				if ( newval ) {
					heading.innerHTML = newval;
				} else {
					heading.innerHTML = ogheading;
				}
			} );
		} );

	/******** Footer Headings *********/

		api( 'footer_headings', function( setting ) {
			setting.bind( function( newval ) {
				var headings = $( '.footer-widget .widget-title' );
				var widgetClass = headings.attr( 'class' );
				headings.each( function() {
					$( this ).replaceWith( '<' + newval + ' class="' + widgetClass + '">' + this.innerHTML + '</' + newval + '>' );
				} );
			} );
		} );

	/******** Footer Gap *********/

		api( 'footer_widgets_gap', function( setting ) {
			var widgets = $( '#footer-widgets' );
			setting.bind( function( newval ) {
				var classes = widgets.attr( 'class' ).split( ' ' );
				if ( classes ) {
					$.each(classes, function(i, c) {
						if ( 0 == c.indexOf( 'gap-' ) ) {
							widgets.removeClass(c);
						}
					} );
				}
				if ( newval ) {
					widgets.addClass( 'gap-' + newval );
				}
			} );
		} );

	/******** Accent Color *********/
	function wpexGenerateAccentColorCSS() {

		if ( 'undefined' === typeof wpex_accent_targets ) {
			return;
		}

		api( 'accent_color', function( setting ) {

			setting.bind( function( newval ) {

				if ( newval ) {

					var style = '<style id="wpex-accent-css">';

					if ( wpex_accent_targets.texts ) {
						style += wpex_accent_targets.texts.join( ',' ) + '{color:' + newval + ';}';
					}

					if ( wpex_accent_targets.backgrounds ) {
						style += wpex_accent_targets.backgrounds.join( ',' ) + '{background-color:' + newval + ';}';
					}

					/*if ( wpex_accent_targets.backgroundsHover ) {
						style += wpex_accent_targets.backgroundsHover.join( ',' ) + '{background-color:' + newval + ';}';
					}*/

					if ( wpex_accent_targets.borders ) {
						_.each( wpex_accent_targets.borders, function( val, key ) {
							if ( _.isArray( val ) ) {
								_.each( val, function( borderLocation ) {
									style += key + '{border-' + borderLocation + '-color:' + newval + ';}';
								} );
							} else {
								style += val + '{border-color:' + newval + ';}';
							}
						} );
					}

					style += '</style>';

					if ( $( '#wpex-accent-css' ).length !== 0 ) {
						$( '#wpex-accent-css' ).replaceWith( style );
					} else {
						$( style ).appendTo( $( 'head' ) );
					}

				} else if ( $( '#wpex-accent-css' ).length !== 0 ) {
					$( '#wpex-accent-css' ).remove();
				}

			} );

		} );

		api( 'accent_color_hover', function( setting ) {

			 setting.bind( function( newval ) {

				if ( newval ) {

					var style = '<style id="wpex-accent-hover-css">';

					if ( wpex_accent_targets.backgroundsHover ) {
						style += wpex_accent_targets.backgroundsHover.join( ',' ) + '{background-color:' + newval + ';}';
					}

					if ( wpex_accent_targets.textsHover ) {
						style += wpex_accent_targets.textsHover.join( ',' ) + '{color:' + newval + ';}';
					}

					style += '</style>';

					if ( $( '#wpex-accent-hover-css' ).length !== 0 ) {
						$( '#wpex-accent-hover-css' ).replaceWith( style );
					} else {
						$( style ).appendTo( $( 'head' ) );
					}

				} else if ( $( '#wpex-accent-hover-css' ).length !== 0 ) {
					$( '#wpex-accent-hover-css' ).remove();
				}

			} );

		} );

	}

	wpexGenerateAccentColorCSS();

	/**
	 * Live design options.
	 */
	var wpexinCSS = {

		/**
		 * Get and loop through inline css options.
		 */
		init: function() {

			if ( typeof wpexCustomizer === 'undefined' ) {
				return;
			}

			var stylingOptions = wpexCustomizer.stylingOptions;

			//console.log( stylingOptions );

			_.each( stylingOptions, function( settings, id ) {

				wpexinCSS.setStyle( settings, id );

			} );

		},

		/**
		 * Set styles.
		 */
		setStyle: function( settings, id ) {

			var styleId = 'wpex-customizer-' + id;
			var target = settings.target;
			var property = settings.alter;
			var sanitize = settings.sanitize || '';
			var important = settings.important ? '!important' : '';
			var media_query = settings.media_query || '';

			api( id, function( value ) {

				value.bind( function( newval ) {

					if ( 'display' === property && 'checkbox' === sanitize ) {
						newval = newval ? '' : 'none';
					}

					// Remove style.
					if ( '' === newval || 'undefined' === typeof newval ) {
						$( '#' + styleId ).remove();
					}

					// Build style.
					else {

						var style = '<style id="' + styleId + '">';

							if ( media_query ) {
								style += '@media only screen and ' + media_query + '{';
							}

							// Sanitize val.
							if ( sanitize && newval ) {

								switch( sanitize ) {
									case 'px':
										if ( newval.indexOf( 'px' ) == -1
											&& newval.indexOf( 'em' ) == -1
											&& newval.indexOf( '%' ) == -1
										) {

											newval = parseInt( newval ); // set to integer
											newval = newval + 'px'; // Add px

										}
									break;
									case 'page_header_min_height':
										if ( newval.indexOf( 'px' ) == -1
											&& newval.indexOf( 'em' ) == -1
											&& newval.indexOf( '%' ) == -1
											&& newval.indexOf( 'vh' ) == -1
											&& newval.indexOf( 'vw' ) == -1
										) {
											newval = parseInt( newval ); // set to integer
											newval = newval + 'px'; // Add px
										}
									break;
									case 'font-size':
										if ( newval.indexOf( 'px' ) == -1
											&& newval.indexOf( 'em' ) == -1
										) {
											newval = newval + 'px';
										}
									break;
									default:
									// nothing.
								}

							} // End sanitize.

							// Target single item.
							if ( 'string' === typeof property ) {


								// Add style.
								if ( Object.prototype.toString.call( target ) === '[object Array]' ) {
									$.each( target, function( index, value ) {
										style += value + '{' + property + ':' + newval + important + ';}';
									} );
								} else {
									style += target + '{' + property + ':' + newval + important + ';}';
								}

							}

							// Target multiple items.
							else {

								if ( '[object Array]' === Object.prototype.toString.call( target ) ) {
									$.each( target, function( index, value ) {
										var eachTarget = value;
										$.each( property, function( index, value ) {
											style += eachTarget + '{' + value + ':' + newval + important + ';}';
										} );
									} );
								} else {
									$.each( property, function( index, value ) {
										style += target + '{' + value + ':' + newval + important + ';}';
									} );
								}

							}

						if ( media_query ) {
							style += '}';
						}

						style += '</style>';

						// Update previewer.
						if ( 0 !== $( '#' + styleId ).length ) {
							$( '#' + styleId ).replaceWith( style );
						} else {
							$( style ).appendTo( $( 'head' ) );
						}

					}

				} );

			} );

		}

	};

	wpexinCSS.init();

} ( wp.customize, jQuery ) );