/**
 * JS for: public/lms-evals-ajax/LMS-Evals-Ajax.php
 *
 * @link       https://github.com/benhoverter/chsie-data-display
 * @since      1.0.0
 *
 * @package    chsie-data-display
 * @subpackage chsie-data-display/public/lms-evals-ajax/js
 */
( function($) {

    'use strict';

    $( function() {

        console.log( "LMS-Evals-Ajax's lms-evals-ajax.js loaded." );

        // Do the initial formatting:
        doCourseEval(); // TODO: Set as conditional for courses.


        // ***** FUNCTION DEFINITIONS ***** //
        function doCourseEval(){

            const courseComplete = ( $( '.course_progress_blue' ).attr( 'style' ) === 'width: 100%;' );
            console.log("courseComplete is ", courseComplete);

            const evaluated = cdd_public_lms_evals_ajax_data.evaluated;
            //console.log( "evaluated returned " + evaluated );

            if( courseComplete && evaluated !== 'true' ){

                const $form = $( '.frm_forms' );

                if( $form.length ) {

                    const $button = $form.find( '.frm_submit > button' );
                    const $legend = $form.find( 'fieldset > legend:first' );
                    //console.log( $legend );
                    //console.log( "legend text is ", $legend.text() ); // works.

                    const $evalSettings = $( '#eval-settings' );
                    const cta = $evalSettings.attr( 'data-ctatype' ) === 'custom_cta' ? $evalSettings.attr( 'data-ctatext' ) : '';
                    //console.log( "cta is", cta );

                    let formTitle = '';
                    const titletype = $evalSettings.attr( 'data-titletype' );

                    if( titletype !== 'no_title' && titletype !== 'not_found' ){

                        formTitle = ( $evalSettings.attr( 'data-titletype' ) === 'form_title' ) ?  $legend.text() : $evalSettings.attr( 'data-titletext' );

                    }

                    $form.addClass( 'no-uw-bar' );

                    $button.addClass( 'chsie-button-gold' );

                    if ( cta.length ) {
                        $legend.after( '<p class="form-cta">' + cta + '</p>' );
                    }
                    if( formTitle.length ){
                        $legend.after( '<h2>' + formTitle + '</h2>' );
                    }

                    $form.slideDown();
                }


            }
        } /// END OF doCourseEval()


        // ----- Post-Submission AJAX ----- //
        // On load, check for the Formidable success message
        //  and the 'evaluated' state from PHP.
        const needsEvalUpdate = ( $( '.frm_message' ).length > 0 );
        //console.log( "needsEvalUpdate returns " , needsEvalUpdate );

        if( needsEvalUpdate && cdd_public_lms_evals_ajax_data.evaluated !== 'true' ){

            const raw_id = $( '.frm_forms' ).attr( 'id' );
            const idEnd = raw_id.indexOf( '_container' );
            const form_id = raw_id.substring( 9, idEnd );
            //console.log( 'form_id is ' + form_id );

            doEvalUpdate( form_id );
        }


        function doEvalUpdate( form_id ) {

            $.ajax({
                method: 'POST',
                url: cdd_public_lms_evals_ajax_data.ajax_url, // Grab the url from the PHP ajax data object.
                data:
                {
                        action: 'lms_eval_update',  // Same as in wp_ajax_{lms_eval_update}.
                        ajax_nonce: cdd_public_lms_evals_ajax_data.ajax_nonce,
                        user_id: cdd_public_lms_evals_ajax_data.user_id,
                        post_id: cdd_public_lms_evals_ajax_data.post_id,
                        form_id: form_id
                },

                success: function( html, status, jqXHR ) {

                    //console.log( "AJAX returned HTML of: " + html );
                    //console.log( "AJAX returned a status of: " + status + "." );
                    //console.log( "AJAX returned a jqXHR object of: " + jqXHR + "." );

                    $( '.frm_message > p' ).after( html ); // Use the AJAX return value as the HTML content

                    $( '.frm_message > p' ).css({
                        'height': 'auto'
                    });

                    $( '.frm_message').css({
                        'opacity': '1'
                    }); // Pretty fade in.

                    // Standard for WP API, and just handy:
                    $( document.body ).trigger( 'post-load' );

                },

                error: function(jqXHR, status, error) {

                    //console.log( "jqXHR was: " , jqXHR );
                    //console.log( "Status returned was: " , status ); // error
                    //console.log( "Error thrown was: " , error );     // bad request
                }

            }); // END OF: $.ajax().
        } // END OF: doEvalUpdate().

        // ----- END OF Post-Module Evaluation AJAX. ----- //

    }); // END OF: $( document ).ready( function() {

})(jQuery);
