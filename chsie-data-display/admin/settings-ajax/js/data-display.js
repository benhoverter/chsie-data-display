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

                var $this = $( this );

                // Select the html you need on event here.

                var confirmed = confirm( "Are you sure you want to do that?" );

                if ( confirmed === true ) {

                    //console.log( "Click confirmed." );

                    // Select html to pass to the AJAX callback here.
                    var selection = $( "#cdd-data-select option" ).filter( ":selected" ).val();
                    console.log( "Selection is ", selection );

                    // This is the Ajax call:
                    getSelectedData( selection );

                    //console.log( "selection is " + selection + "." );

                }
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
                        //ajax_nonce: cdd_settings_ajax_data.ajax_nonce,
                        data_selection: selection
                },

                beforeSend: function() {

                    // Get the current height of #current-user-registration-info.
                    var regHeight = $( '#outer-frame-id' ).height();

                    // Set the CSS height to that value to preserve element position.
                    $( '#outer-frame-id' ).height( regHeight );

                    // Pretty fade out.
                    $( '#frame-id' ).fadeOut( 'fast' );

                },

                success: function( html, status, jqXHR ) {

                    //console.log( "AJAX returned HTML of: " + html );
                    //console.log( "AJAX returned a status of: " + status + "." );
                    //console.log( "AJAX returned a jqXHR object of: " + jqXHR + "." );

                    $( '#frame-id' ).html( html ); // Use the AJAX return value as the HTML content
                    $( '#frame-id' ).fadeIn( 'fast' ); // Pretty fade in.

                    // Set CSS height of #current-user-registration-info back to auto.
                    $( '#outer-frame-id' ).css( 'height', 'auto' );

                    // Standard for WP API, and just handy:
                    $( document.body ).trigger( 'post-load' );

                }
            }); // END OF: $.ajax().

        } // END OF: getSelectedData().




    }); // END OF: $( document ).ready( function() {

})(jQuery);
