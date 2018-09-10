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


    } ); // END: document.ready

})( jQuery );
