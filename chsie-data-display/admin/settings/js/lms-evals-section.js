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

        function doLmsEvalsDOM() {
            const $tbody = $( "#chsie_data_display_lms_evals_section .form-table tbody" );
            const $submit = $( "#chsie_data_display_lms_evals_section p.submit" ).remove();

            $tbody.before( "<h3>Custom Evaluation Settings</h3>" );

            $tbody.after( $submit );

        }

        doLmsEvalsDOM();

    } ); // END: document.ready

})( jQuery );
