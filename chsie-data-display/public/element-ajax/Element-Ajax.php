<?php

/**
* Does one thing well.
*
* @link       https://github.com/benhoverter/chsie-data-display
* @since      1.0.0
*
* @package    chsie-data-display
* @subpackage chsie-data-display/public/element-ajax
*/

/**
* Does one thing well.
*
* Here's the description of how it does it.
*
* @package    chsie-data-display
* @subpackage chsie-data-display/public/element-ajax
* @author     Ben Hoverter <ben.hoverter@gmail.com>
*/
class CDD_Public_Element_Ajax {

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
    * @var      associative array    $ajax_data    The data for public AJAX functions.
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
    * Get all data to be passed to the frontend.
    * Localized in "../Public.php".
    *
    * @return   array     $this->ajax_data     The associative array of data to pass.
    * @since    1.0.0
    */
    public function get_ajax_data() {

        // Needed on the frontend. No touching!
        $this->ajax_data[ 'ajax_url' ] = admin_url( 'admin-ajax.php' );

        // Gets checked in element_ajax_callback().
        $this->ajax_data[ 'element_ajax_nonce' ] = wp_create_nonce( 'plugin_abbr_element_ajax_nonce' );

        // Add key => value pairs here.

        return $this->ajax_data;

    }


    // ***** POST-CALL METHODS ***** //

    /**
    * AJAX callback function to bind to wp_ajax_{action_name} hook.
    *
    * @since    1.0.0
    */
    public function element_ajax_callback() {

        check_ajax_referer( 'plugin_abbr_element_ajax_nonce', 'element_ajax_nonce' ); // Dies if false.

        // Call the handler function.
        echo $this->handler_function();

        // Needed to return AJAX:
        wp_die();

    }


    /**
    * Handler function called by element_ajax_callback().
    *
    * @since    1.0.0
    */
    private function handler_function() {

    }


}
