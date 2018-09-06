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
        console.log( "Settings data-section.js loaded." );

        // ***** FUNCTION DEFINITIONS ***** //
        function getFileDate() {
            // Get the date in a file-suffix format.
            const cdd_Date = new Date();
            const cdd_day = cdd_Date.getUTCDate();
            const cdd_month = cdd_Date.getMonth() + 1;
            const cdd_year = cdd_Date.getFullYear();
            const cdd_hour = cdd_Date.getHours();
            const cdd_min = cdd_Date.getMinutes();

            console.log( "The file's Date is " + cdd_Date );

            const cdd_FileDate = cdd_month + "-" + cdd_day + "-" + cdd_year + "_" + cdd_hour + "-" + cdd_min ;

            return cdd_FileDate;
        } // End getFileDate().


        function cdd_exportToCSV( filename ) {
            const csv = [];
            const $rows = $('#cdd-data-table tr');

            $rows.each( function() {
                let row = [];
                let $cols = $( this ).children('th, td');

                $cols.each( function() {
                    row.push( $( this ).text() );
                } );

                csv.push(row.join(","));

            } );

            // Download the file, concatenating the csv[] elements with newlines.
            // Sends the CSV data as a string, along with the chosen filename.
            cdd_downloadCSV( csv.join( "\n" ) , filename );

            console.log( "cdd_exportToCSV finished.");

        } // End cdd_exportToCSV().


        function cdd_downloadCSV( csv, filename ) {

            // Create the blob that will be the file, and fill it with the csv string from cdd_exportToCSV().
            const csvFile = new Blob( [csv] , { type: "text/csv"});

            $("#cdd-button-div").append("<a id='cdd-hidden-button'>Hidden!</a>");
            const csvButton = $('#cdd-hidden-button');
            csvButton.attr( "download", filename );  // Sets the button's download attribute to the filename from cdd_export.

            const fileURL = window.URL.createObjectURL( csvFile );
            csvButton.attr( "href", fileURL );  // Set's the button's href target to the file's location.

            $('#cdd-hidden-button')[0].click(); // Calls native DOM click, not JQ click. IMPORTANT.

            window.URL.revokeObjectURL( fileURL ); // Takes out the trash.
            csvButton.detach();  // Removes the button.

        } // End of cdd_downloadCSV().


        // ***** SELECT, BIND HANDLERS, AND CALL ***** //

        // Grab content of an HTML <select> dropdown and return its selected value.
        if( $( '#chsie_data_display_data_section' ).length ) {
            //console.log( "chsie_data_display_data_section detected." );

            $( document.body ).on( 'post-load', function() {
                //console.log( "post-load detected." );

                // CSV EXPORT: Trigger these functions only if the table has been rendered.
                if( $( "#cdd-data-table table" ).length ) {
                    console.log( "#cdd-data-table table detected." );

                    // Set the current Option as the displayed one, then make it the filename prefix.
                    const cdd_tableTitle = $( "#cdd-data-table table" ).attr( "data-title" );
                    const cdd_filename = cdd_tableTitle + "_" + getFileDate() + ".csv";
                    const cdd_checkOption = $("#cdd-data-select option");

                    console.log( "cdd_filename is " + cdd_filename );

                    cdd_checkOption.each( function() {

                        if ( $( this ).val() === cdd_tableTitle ) {

                            $( this ).attr( "selected" , "true" );
                            console.log( $( this ) + "set as selected." );

                        }
                    });

                    // When the Download button is clicked, build the CSV from the current table configuration.
                    $( '#cdd-download-csv' ).off( 'click' );

                    $( '#cdd-download-csv' ).click( function() {

                        console.log( "download button clicked." );

                        cdd_exportToCSV( cdd_filename );

                    });

                } // End if( $(#cdd-data-table table).length ).

            }); // END OF: $( document.body ).on( 'post-load', function()

        } // End if( $( '#chsie_data_display_data_section' ).length )

    } ); // END: document.ready

})( jQuery );
