<?php

/**
* The public-facing functionality of the plugin.
*
* @link       https://github.com/benhoverter/chsie-data-display
* @since      1.0.0
*
* @package    chsie-data-display
* @subpackage chsie-data-display/public
*/

/**
* The public-facing functionality of the plugin.
*
* Defines the plugin name and version,
* enqueues the public-facing stylesheet and JavaScript,
* and pipes in the public-facing functions.
*
* @package    chsie-data-display
* @subpackage chsie-data-display/public
* @author     Ben Hoverter <ben.hoverter@gmail.com>
*/
class CDD_Public_Assets {

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
    * Initialize the class and set its properties.
    *
    * @since    1.0.0
    * @param      string    $plugin_title       The name of the plugin.
    * @param      string    $version    The version of this plugin.
    */
    public function __construct( $plugin_title, $version ) {

        $this->plugin_title = $plugin_title;
        $this->version = $version;

    }


    /**
    * Register the combined stylesheet for the public-facing side of the site.
    *
    * @since    1.0.0
    */
    public function enqueue_styles() {

        /**
        * An instance of this class is passed to the run() function
        * defined in CDD_Loader, which then creates the relationship
        * between the defined hooks and the functions defined in this
        * class.
        *
        * This architecture assumes you are transpiling all child directory
        * css/scss/less files into a single public.css file in the /public directory.
        */

        // Variable to hold the URL path for enqueueing.
        $public_css_dir_url = plugin_dir_url( __DIR__ ) . 'assets/public/public.min.css';

        // Variable to hold the server path for filemtime() and versioning.
        $public_css_dir_path = plugin_dir_path( __DIR__ ) . 'assets/public/public.min.css';

        // Register the style using an automatic and unique version based on modification time.
        wp_register_style( $this->plugin_title . '-public-css', $public_css_dir_url, array(),  filemtime( $public_css_dir_path ), 'all' );

        // Enqueue the style.
        wp_enqueue_style( $this->plugin_title . '-public-css' );
        //wp_enqueue_style( 'thickbox' );

    }

    /**
    * Register the concat/minified JavaScript for the public-facing side of the site.
    *
    * @since    1.0.0
    */
    public function enqueue_scripts() {

        /**
        * An instance of this class is passed to the run() function
        * defined in CDD_Loader, which then creates the relationship
        * between the defined hooks and the functions defined in this
        * class.
        *
        * This architecture assumes that you are transpiling all child directory
        * JavaScript files into a single public.min.js file in the /public directory.
        */

        // Variable to hold the URL path for enqueueing.
        $public_js_dir_url = plugin_dir_url( __DIR__ ) . 'assets/public/public.min.js';

        // Variable to hold the server path for filemtime() and versioning.
        $public_js_dir_path = plugin_dir_path( __DIR__ ) . 'assets/public/public.min.js';

        // Register the script using an automatic and unique version based on modification time.
        wp_register_script( $this->plugin_title . '-public-js', $public_js_dir_url, array( 'jquery' ),  filemtime( $public_js_dir_path ), true );

        // Enqueue the scripts.
        wp_enqueue_script( $this->plugin_title . '-public-js' );

    }



}
