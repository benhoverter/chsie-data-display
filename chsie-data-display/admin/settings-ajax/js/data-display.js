/**
 * JS for: admin/settings-ajax/views/data-display.php.
 *
 * @link       https://github.com/benhoverter/chsie-data-display
 * @since      1.0.0
 *
 * @package    plugin-name
 * @subpackage plugin-name/admin/module-ajax/js
 */

( function($) {

    'use strict';

    $( document ).ready( function() {
        console.log( "Settings-Ajax's data-display.js loaded." );

        // Do the thing.

        // Bind the event handler to the delete button:
        bindHandler();

        // Rebind all handlers on Ajax finish:
        $( document.body ).on( 'post-load', function() {
            bindHandler();
        } );


        // Handler-binder for a button:
        function bindHandler() {
            $( '#cdd-data-select-button' ).off( "click" ).click( function( event ) {

                event.preventDefault();

                // Select html to pass to the AJAX callback here.
                var selection = $( "#cdd-data-select option" ).filter( ":selected" ).val();
                console.log( "Selection is ", selection );

                // This is the Ajax call:
                getSelectedData( selection );

            });
        } // END OF: bindHandler().


        // Define an ajax function:
        function getSelectedData( selection ) {

            $.ajax({
                method: 'POST',
                url: ajaxurl, // Should be available as a global on the admin side.
                data:
                {
                        action: 'data_select',  // Same as in wp_ajax_{data_select}().
                        ajax_nonce: cdd_settings_ajax_data.settings_ajax_data_nonce,
                        data_selection: selection
                },

                beforeSend: function() {

                    console.log( "beforeSend triggered." );

                    // Get the current height of #current-user-registration-info.
                    var regHeight = $( '#cdd-data-table' ).height();

                    // Set the CSS height to that value to preserve element position.
                    $( '#cdd-data-table' ).height( regHeight );

                    // Pretty fades.
                    $( '#cdd-download-csv' ).fadeOut( 'fast' );
                    $( '#cdd-data-table-mask' ).fadeIn( 'fast' );
                    $( '#cdd-button-div .spinner' ).removeClass( 'faded' );

                },

                success: function( html, status, jqXHR ) {

                    console.log( "AJAX returned HTML of: " + html );
                    console.log( "AJAX returned a status of: " + status );
                    console.log( "AJAX returned a jqXHR object of: " , jqXHR );

                    // Use the AJAX return value as the HTML content:
                    $( '#cdd-data-table' ).html( html );

                    // Initialize and format the DataTables jQuery extension:
                    $( "#cdd-data-table > table" ).DataTable({

                        "lengthMenu" : [ [10 , 50 , 100 , -1 ] , [ 10 , 50 , 100 , "All" ] ]

                    });

                    // Set CSS height of #cdd-data-table back to auto.
                    $( '#cdd-data-table' ).css( 'height', 'auto' );

                    // Fade back in.
                    $( '#cdd-button-div .spinner' ).addClass( 'faded' );
                    $( '#cdd-data-table-mask' ).fadeOut( 'fast' );
                    if ( $( '#cdd-data-table table' ).length > 0 ) {
                        $( '#cdd-download-csv' ).fadeIn( 'fast' );
                    }

                    // Standard for WP API, and just handy:
                    $( document.body ).trigger( 'post-load' );

                },

                error: function(jqXHR, status, error) {

                    $( '#cdd-data-table' ).html( "<p>Sorry, unable to display data.</p>" ).fadeIn( 'fast' );

                    console.log( "jqXHR was: " , jqXHR );
                    console.log( "Status returned was: " , status ); // error
                    console.log( "Error thrown was: " , error );     // bad request
                }

            }); // END OF: $.ajax().

        } // END OF: getSelectedData().




    }); // END OF: $( document ).ready( function() {

})(jQuery);
