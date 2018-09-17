<?php

/**
* Pushes eval form settings to the frontend, handles display logic,
* and does the AJAX form submission.
*
* @link       https://github.com/benhoverter/chsie-data-display
* @since      1.0.0
*
* @package    chsie-data-display
* @subpackage chsie-data-display/public/lms-evals-ajax
*/

/**
* Pushes eval form settings to the frontend, handles display logic,
* and does the AJAX form submission.*
*
* @package    chsie-data-display
* @subpackage chsie-data-display/public/lms-evals-ajax
* @author     Ben Hoverter <ben.hoverter@gmail.com>
*/
class CDD_Public_LMS_Evals_Ajax {

    /**
    * The ID of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $plugin_title    The ID of this plugin.
    */
    private $plugin_title;

    /**
    * The version of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $version    The current version of this plugin.
    */
    private $version;

    /**
    * The data object for LMS Eval AJAX functions.
    *
    * @since    1.0.0
    * @access   private
    * @var      array    $ajax_eval_data    The data for LMS Eval AJAX functions.
    */
    private $ajax_eval_data;

    /**
    * The nonce for the AJAX call.  Must be available to public_ajax_callback().
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $ajax_nonce    The nonce for the AJAX call.
    */
    private $ajax_nonce;

    /**
    * The current post ID.  Needed for AJAX (otherwise unavailable).
    *
    * @since    1.0.0
    * @access   public
    * @var      object    $post_id    The current post ID.
    */
    public $post_id;


    /**
    * Initialize the class and set its properties.
    *
    * @since    1.0.0
    * @param      string    $plugin_title       The name of the plugin.
    * @param      string    $version           The version of this plugin.
    */
    public function __construct( $plugin_title, $version ) {

        $this->plugin_title = $plugin_title;
        $this->version = $version;

    }


    // ***** PRE-CALL METHODS ***** //

    /**
	 * Retrieves the AJAX data for the public side of the plugin.
     * Calls wp_localize_script() to pass data to AJAX JS.
	 *
	 * @since    1.0.0
	 */
	public function set_lms_evals_ajax_data() {

        global $wpdb;

        $user_id = get_current_user_id();

        $post_id = get_the_ID();

        $evaluated = 'false';

        $user_forms = get_user_meta( $user_id, 'eval_forms', true );

        if( !empty( $user_forms ) ) {

            //
            $form_ids = $wpdb->get_col(
                $wpdb->prepare(
                    "
                        SELECT  form_id
                        FROM    wp_frm_items
                        WHERE   post_id = %d
                        and     user_id = %d
                    ",
                    $post_id,
                    $user_id
                )
            );

            if ( ! empty( $form_ids ) ) {

                foreach( $user_forms as $form ){

                    foreach( $form_ids as $form_id ) {

                        $form_id = (int) $form_id;

                        if( $form === $form_id ) {
                            $evaluated = 'true';
                            break;
                        }
                    }
                }
            }

        }

        // Stick the data in an array for wp_localize_script():

        // Necessary. No touching.
        $this->ajax_eval_data[ 'ajax_url' ] = admin_url( 'admin-ajax.php' );

        // Gets checked in ajax_eval_update().
        //$this->ajax_eval_data[ 'ajax_nonce' ] = wp_create_nonce( 'cdt_public_ajax_nonce' );

        $this->ajax_eval_data[ 'user_id' ] = $user_id;

        $this->ajax_eval_data[ 'post_id' ] = $post_id;

        $this->ajax_eval_data[ 'form_id' ] = $form_id;

        $this->ajax_eval_data[ 'evaluated' ] = $evaluated;

        $this->ajax_eval_data[ 'user_forms' ] = $user_forms;

        // Frontend data for lms-evals-ajax.js:
        wp_localize_script(

            $this->plugin_title . '-public-js',

            'cdd_public_lms_evals_ajax_data',

            $this->ajax_eval_data

        );

    }


    /**
	 * Echoes the custom settings for the LMS Evaluations.
     *
     * Options are set in CHSIE Data Display > LMS Evaluations.
	 *
	 * @since    1.0.0
	 */
	public function echo_eval_settings() {

        /*
        if( !is_single() || ( get_post_type() !== 'sfwd-courses' ) ) {

            echo '<p>' . get_post_type() . '</p>';

        } else {
        */
            $lms_evals_array = get_option( 'chsie_data_display_lms_evals_section' );
            $title_radio = $lms_evals_array['title_radio'];
            $title_text = isset( $lms_evals_array['title_text'] ) ? $lms_evals_array['title_text'] : '' ;

            $title_data = 'data-titletype="' . $title_radio . '"';

            if( $title_radio === 'custom_title' ) {

                $title_data .= 'data-titletext="' . $title_text . '"';

            }

            $cta_radio = $lms_evals_array[ 'cta_radio' ];
            $cta_text = isset( $lms_evals_array[ 'cta_text' ] ) ? $lms_evals_array[ 'cta_text' ] : '' ;

            $cta_data = $cta_radio !== 'no_cta' ? 'data-ctatype="' . $cta_radio . '" data-ctatext="' . $cta_text . '"' : '';


            echo '<div id="eval-settings" ' . $title_data . ' ' . $cta_data . '></div>';

        // }

    }


    // ***** POST-CALL METHODS ***** //

    /**
     * AJAX callback function to bind to wp_ajax_lms_eval_update hook.
     *
     * @since    1.0.0
     */
    public function lms_eval_update() {

        //check_ajax_referer( 'cdt_public_ajax_nonce', 'ajax_nonce' ); // Dies if false.

        if(  isset( $_POST['user_id'] ) && isset( $_POST['form_id'] ) && isset( $_POST['post_id'] )  ) {

            $user_id = (int) $_POST['user_id'];
            $form_id = (int) $_POST['form_id'];
            $post_id = (int) $_POST['post_id'];

            $this->do_eval_update( $user_id, $form_id, $post_id );

        } else {

            echo "<p>Sorry, there was an error.  Your evaluation wasn't saved.</p>";

        }

        // Needed to return AJAX:
        wp_die();

    }


    /**
     * Handler function called by ajax_eval_update().
     *
     * @since    1.0.0
     */
    public function do_eval_update( $user_id, $form_id, $post_id ) {

        global $wpdb;

        // Update usermeta with $form_id to show they've done that survey:
        $eval_forms = get_user_meta( $user_id, 'eval_forms', true ); // Empty string if DNE.

        // To manage the "empty string" issue:
        if( $eval_forms === "" ) {
            $eval_forms = array();
        }

        $eval_forms[] = $form_id;

        $eval_update = update_user_meta( $user_id, 'eval_forms', $eval_forms );
        //print_r( $eval_update ); // Returns (int) meta_id on new record, true on update, false on no update.


        /**
        * Update the post_id associated with this form, user, and frm_item.
        * If the form shortcode has moved to a new post, or the post ID has changed,
        * the database will reflect that for users who submitted on the new post ID.
        */
        $post_id_update = $wpdb->update(

            'wp_frm_items',

            array( // UPDATE...
                'post_id' => $post_id
            ),

            array( // WHERE...
                'form_id' => $form_id,
                'user_id' => $user_id
            ),

            array(
                '%d'
            ),

            array(
                '%d'
            )
        );

        //echo "<p>Eval forms is " . print_r($eval_forms) . "</p>";
        //echo "<p>Eval update returned " . $eval_update . "</p>";
        //echo "<p>post_id update returned " . $post_id_update . "</p>";

        echo "<p>You shouldn't see this evaluation again.</p>";

    }


}
