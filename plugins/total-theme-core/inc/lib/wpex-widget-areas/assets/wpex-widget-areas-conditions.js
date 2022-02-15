( function( $110n ) {

	'use strict';

	// Tabs.
	document.addEventListener( 'click', function( event ) {

		var tabLink = event.target.closest( '.wpex-wa-conditions-tab-link' ),
			tab,
			allTabLinks,
			allTabs;

		if ( ! tabLink ) {
			return;
		}

		event.preventDefault();

		allTabLinks = document.querySelectorAll( '.wpex-wa-conditions-tab-link' );
		allTabs = document.querySelectorAll( '.wpex-wa-conditions-panel__section' );
		tab = tabLink.getAttribute( 'aria-controls' );
		tab = document.getElementById( tab );

		for( let i = 0; i < allTabLinks.length; i++ ) {
			allTabLinks[i].parentNode.classList.remove( 'wpex-wa-conditions-panel__tab--active' );
			allTabLinks[i].setAttribute( 'aria-selected', 'false' );
		}

		tabLink.parentNode.classList.add( 'wpex-wa-conditions-panel__tab--active' );
		tabLink.setAttribute( 'aria-selected', 'true' );

		for( let i = 0; i < allTabs.length; i++ ) {
			allTabs[i].classList.remove( 'wpex-wa-conditions-panel__section--active' );
		}

		tab.classList.add( 'wpex-wa-conditions-panel__section--active' );

	} );

	// Accordions.
	document.addEventListener( 'click', function( event ) {

		var toggle = event.target.closest( '.wpex-wa-conditions-accordion__toggle' ), accordionTitle, accordionContent, expanded;

		if ( ! toggle ) {
			return;
		}

		event.preventDefault();

		accordionTitle = toggle.closest( '.wpex-wa-conditions-accordion__title' );
		accordionContent = accordionTitle.nextSibling;
		expanded = toggle.getAttribute( 'aria-expanded' );

		if ( 'true' === expanded ) {
			toggle.setAttribute( 'aria-expanded', 'false' );
			accordionContent.classList.remove( 'wpex-wa-conditions-accordion--active' );
		} else {
			toggle.setAttribute( 'aria-expanded', 'true' );
			accordionContent.classList.add( 'wpex-wa-conditions-accordion--active' );
		}

	} );

	// Clear button.
	document.addEventListener( 'click', function( event ) {

		if ( ! event.target.closest( '.wpex-wa-conditions-clear' ) ) {
			return;
		}

		if ( confirm( $110n.confirm ) ) {

			var allCheckboxes = document.querySelectorAll( '.wpex-wa-conditions-panel input[type="checkbox"]' );

			for( let i = 0; i < allCheckboxes.length; i++ ) {
				allCheckboxes[i].checked = false;
			}

		}

		event.preventDefault();

	} );

} ) ( wpexWidgetAreasConditionsL10n );