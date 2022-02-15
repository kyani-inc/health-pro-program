// @todo remove, was deprecated in 5.2.
( function( $ ) {

    'use strict';

    $( document ).ready( function() {
        vcexResponsiveCSS();
    } );

    /* Responsive CSS
    ---------------------------------------------------------- */
    if ( 'function' !== typeof window.vcexResponsiveCSS ) {
        window.vcexResponsiveCSS = function ( $context ) {
            var headCSS  = '';
            var mediaObj = {};
            var bkPoints = {};

            $( '.wpex-vc-rcss' ).remove(); // Prevent duplicates when editing the VC.

            // Get breakpoints.
            bkPoints.d = '';

            if ( 'undefined' !== typeof wpex_theme_params ) {
                bkPoints = $.extend( bkPoints, wpex_theme_params.responsiveDataBreakpoints );
            } else {
                bkPoints = {
                    'tl':'1024px',
                    'tp':'959px',
                    'pl':'767px',
                    'pp':'479px'
                };
            }

            // Loop through breakpoints to create mediaObj
            $.each( bkPoints, function( key ) {
                mediaObj[key] = ''; // Create empty array of media breakpoints
            } );

            // loop through all modules and add CSS to mediaObj.
            $( '[data-wpex-rcss]' ).each( function( index, value ) {

                var $this       = $( this );
                var uniqueClass = 'wpex-rcss-' + index;
                var data        = $this.data( 'wpex-rcss' );

                $this.addClass( uniqueClass );

                $.each( data, function( key, val ) {

                    var thisVal = val;
                    var target  = key;

                    $.each( bkPoints, function( key ) {

                        if ( thisVal[key] ) {

                            mediaObj[key] += '.' + uniqueClass + '{' + target + ':' + thisVal[key] + '!important;}';

                        }

                    } );

                } );

            } );

            $.each( mediaObj, function( key, val ) {

                if ( 'd' == key ) {
                    headCSS += val;
                } else {
                    if ( val ) {
                        headCSS += '@media(max-width:' + bkPoints[key] + '){' + val + '}';
                    }
                }

            } );

            if ( headCSS ) {

                headCSS = '<style class="wpex-vc-rcss">' + headCSS + '</style>';

                $( 'head' ).append( headCSS );

            }

        };

    }

} ) ( jQuery );