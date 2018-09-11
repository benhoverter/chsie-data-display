/**
 * JS for: admin/module/views/view-name.php.
 *
 * @link       https://github.com/benhoverter/chsie-data-display
 * @since      1.0.0
 *
 * @package    chsie-data-display
 * @subpackage chsie-data-display/admin/module/js
 */

 (function( $ ) {
     'use strict';

    $(document).ready( function() {
        console.log( "Settings lms-evals-section.js loaded." );

        doLmsEvalsDOM();

        watchRadios();

        listenToInputs();


        // ***** FUNCTION DEFINITIONS ***** //
        function doLmsEvalsDOM() {
            const tbody = $( "#chsie_data_display_lms_evals_section .form-table tbody" );
            const pSubmit = $( "#chsie_data_display_lms_evals_section p.submit" ).remove();

            tbody.before( "<h3>Custom Evaluation Settings</h3>" );

            tbody.after( pSubmit );

        }

        function watchRadios() {

            const radios = $( "#chsie_data_display_lms_evals_section .form-table input[type='radio']" );

            radios.each( function() {
                showCustomText( $( this ) );
                //console.log( "checkedRadios includes", $( this ) );
            } );

            radios.click( function() {
                showCustomText( $( this ) );
                //console.log( "Just clicked", $( this ) );
            } );


            function showCustomText( radio ) {

                let customText = radio.parents( "fieldset" ).find( "input.custom-text" );

                if( radio.is( "input[id^='custom_']" ) && radio.is( ":checked" ) ) {
                    customText.fadeIn( "fast" );
                } else {
                    customText.fadeOut( "fast" );
                }
            }

        }


        function listenToInputs() {

            const textFields = $( "#chsie_data_display_lms_evals_section .form-table input[type='text']" );

            textFields.off( 'keyup' );
            let timer = 0;

            textFields.on( 'keydown', function( e ) {

                if( e.key === 'Enter' ) {

                    e.preventDefault();

                    sanitizeInputs( $( this ) );

                    $( "#chsie_data_display_lms_evals_section form" ).submit();

                    console.log("Enter pressed on ", $(this) );

                } else if( e.key === 'Tab' ) {

                    sanitizeInputs( $( this ) );
                    console.log("Tab pressed on ", $(this) );

                }

            } );

            textFields.on( 'keyup', function( e ) {

                timer = window.setTimeout( () => {

                    sanitizeInputs( $( this ) );

                    //console.log( "Timer ran on ", $(this) );

                }, 250 );

            } );


            function sanitizeInputs( input ) {

                let text = input.val();

                // Use DOMPurify:
                let safeText = DOMPurify.sanitize( text );

                input.val( safeText );

            }

        }


    } ); // END: document.ready

})( jQuery );
