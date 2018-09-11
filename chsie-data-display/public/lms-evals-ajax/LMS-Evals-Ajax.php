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
    * @var      string    $plugin_name    The ID of this plugin.
    */
    private $plugin_name;

    /**
    * The version of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $version    The current version of this plugin.
    */
    private $version;

    /**
    * The data object for public AJAX functions.
    *
    * @since    1.0.0
    * @access   private
    * @var      array    $ajax_data    The data for public AJAX functions.
    */
    private $ajax_data;

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
    * @param      string    $plugin_name       The name of the plugin.
    * @param      string    $version           The version of this plugin.
    */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }


    // ***** PRE-CALL METHODS ***** //

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

    /**
    * Render a view.
    * Different hooks will require separate render_{} methods.
    *
    * @since    1.0.0
    */
    public function render_view() {

        /**
        * The view displaying ________.
        */
        include( plugin_dir_path( __FILE__ ) . 'views/view-name.php' ) ;

    }


    /**
    * Set data to be passed to the frontend.
    *
    * @since    1.0.0
    */
    public function set_module_ajax_data() {

        // Frontend data for data table:
        wp_localize_script(

            $this->plugin_title . '-admin-js',

            'cdd_module_ajax_data',

            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'module_ajax_data_nonce' => wp_create_nonce( 'cdd_module_ajax_nonce' )
            )

        );

        // Add'l calls to wp_localize_script() for add'l data sets go here:

    }


    // ***** POST-CALL METHODS ***** //

    /**
    * AJAX callback function to bind to wp_ajax_{action_name} hook.
    *
    * @since    1.0.0
    */
    public function module_ajax_callback() {

        if( ! current_user_can( 'read_private_pages' ) ) {
            echo "<p>Sorry, it doesn't look like you have permission to do that.</p>";
        }

        check_ajax_referer( 'cdd_module_ajax_nonce', 'ajax_nonce' ); // Dies if false.

        // Call the handler function.
        echo $this->handler_function();

        // Needed to return AJAX:
        wp_die();

    }


    /**
    * Handler function called by module_ajax_callback().
    *
    * @since    1.0.0
    */
    private function handler_function() {

    }


}
