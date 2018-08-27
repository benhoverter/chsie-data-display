<?php

/**
* The file that defines the core plugin class
*
* A class definition that includes attributes and functions used across both the
* public-facing side of the site and the admin area.
*
* @link       https://github.com/benhoverter/chsie-data-display
* @since      1.0.0
*
* @package    chsie-data-display
* @subpackage chsie-data-display/includes
*/

/**
* The core plugin class.
*
* This is used to define internationalization, admin-specific hooks, and
* public-facing site hooks.
*
* Also maintains the unique identifier of this plugin as well as the current
* version of the plugin.
*
* @since      1.0.0
* @package    chsie-data-display
* @subpackage chsie-data-display/includes
* @author     Ben Hoverter <ben.hoverter@gmail.com>
*/
class CHSIE_Data_Display {

    /**
    * The loader that's responsible for maintaining and registering all hooks that power
    * the plugin.
    *
    * @since    1.0.0
    * @access   protected
    * @var      CDD_Loader    $loader    Maintains and registers all hooks for the plugin.
    */
    protected $loader;

    /**
    * The unique identifier of this plugin.
    *
    * @since    1.0.0
    * @access   protected
    * @var      string    $plugin_name    The string used to uniquely identify this plugin.
    */
    protected $plugin_name;

    /**
    * The current version of the plugin.
    *
    * @since    1.0.0
    * @access   protected
    * @var      string    $version    The current version of the plugin.
    */
    protected $version;

    /**
    * The mysqli database connection object instance.
    *
    * @since    1.0.0
    * @access   protected
    * @var      string    $conn    The mysqli database connection object instance.
    */
    //public $conn;

    /**
    * The array of SQL queries that the plugin can run.
    *
    * @since    1.0.0
    * @access   protected
    * @var      string    $queries    The array of SQL queries that the plugin can run.
    */
    //public $queries;


    /**
    * Define the core functionality of the plugin.
    *
    * Set the plugin name and the plugin version that can be used throughout the plugin.
    * Load the dependencies, define the locale, and set the hooks for the admin area and
    * the public-facing side of the site.
    *
    * @since    1.0.0
    */
    public function __construct() {
        if ( defined( 'CHSIE_DATA_DISPLAY_VERSION' ) ) {
            $this->version = CHSIE_DATA_DISPLAY_VERSION;
        } else {
            $this->version = '1.0.0';
        }

        $this->plugin_name = 'CHSIE Data Display';

        $this->load_dependencies();
        $this->set_locale();

        $this->define_admin_hooks();  // Creates an instance of the admin class and hooks its methods in.
        $this->define_public_hooks(); // Creates an instance of the public class and hooks its methods in.
        //$this->define_settings_hooks(); // Creates an instance of the admin settings class and hooks its methods in.

    }

    /**
    * Load the required dependencies for this plugin.
    *
    * Include the following files that make up the plugin:
    *
    * - Loader.php. Orchestrates the hooks of the plugin.
    * - I18n.php. Defines internationalization functionality.
    * - Admin.php. Defines all hooks for the admin area.
    * - Settings.php.  Defines the WeDevs Settings API integration.
    * - Public.php. Defines all hooks for the public side of the site.
    *
    * Create an instance of the loader which will be used to register the hooks
    * with WordPress.
    *
    * @since    1.0.0
    * @access   private
    */
    private function load_dependencies() {

        /**
        * The class responsible for orchestrating the actions and filters of the
        * core plugin.
        */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Loader.php';

        /**
        * The class responsible for defining internationalization functionality
        * of the plugin.
        */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/I18n.php';

        /**
        * The class responsible for defining all actions that occur in the admin area.
        */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/Admin.php';

        /**
        * The class responsible for defining all admin settings and menu options.
        */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/Settings.php';

        /**
        * The class responsible for defining all actions that occur in the public-facing
        * side of the site.
        */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/Public.php';

        /**
        * The file responsible for defining the SQL queries run by the plugin.
        */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'config/Queries.php';

        $this->loader = new CDD_Loader();

    }

    /**
    * Define the locale for this plugin for internationalization.
    *
    * Uses the CDD_i18n class in order to set the domain and to register the hook
    * with WordPress.
    *
    * @since    1.0.0
    * @access   private
    */
    private function set_locale() {

        $plugin_i18n = new CDD_i18n();

        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

    }


    /**
    * Register all of the hooks related to the WeDevs Settings API functionality
    * of the plugin.
    *
    * @since    1.0.0
    * @access   private
    */
    private function define_settings_hooks() {

        $plugin_settings = new CDD_Settings( $this->get_plugin_name(), $this->get_version() );

        // No need to enqueue scripts/styles here -- they are enqueued in the WeDevs_Settings_API class.

        // Standard functions that call dev-defined sections and menus in the Settings class:
        $this->loader->add_action( 'admin_menu', $plugin_settings, 'admin_menu' );
        $this->loader->add_action( 'admin_init', $plugin_settings, 'admin_init' );

    }


    /**
    * Register all of the hooks related to the admin area functionality
    * of the plugin.
    *
    * @since    1.0.0
    * @access   private
    */
    private function define_admin_hooks() {

        $plugin_admin = new CDD_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        // Standard admin element hooks go here:
        //$this->loader->add_action( 'add_meta_boxes{_post_type}', $plugin_admin->element, 'render_metabox' );
        //$this->loader->add_action( 'save_post{_post_type}', $plugin_admin->element, 'save_metabox' );
        //$this->loader->add_action( 'hook_name', $plugin_admin->element_ajax, 'render_view' );

        // AJAX admin element hooks go here:
        //$this->loader->add_action( 'wp_ajax_{action_name}', $plugin_admin->element_ajax, 'element_ajax_callback' );

    }


    /**
    * Register all of the hooks related to the public-facing functionality
    * of the plugin.
    *
    * @since    1.0.0
    * @access   private
    */
    private function define_public_hooks() {

        $plugin_public = new CDD_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        // Standard public element hooks go here:
        //$this->loader->add_action( 'hook_name', $plugin_public->element, 'render_view' );

        // AJAX public element hooks go here:
        //$this->loader->add_action( 'wp_ajax_{action_name}', $plugin_public->element_ajax, 'element_ajax_callback' );

        // Shortcode hooks go here:
        //add_shortcode( 'shortcode-name', array( $plugin_public, 'shortcode_function' ) );

    }


    /**
    * Run the loader to execute all of the hooks with WordPress.
    *
    * @since    1.0.0
    */
    public function run() {
        $this->loader->run();
    }


    /**
    * The name of the plugin used to uniquely identify it within the context of
    * WordPress and to define internationalization functionality.
    *
    * @since     1.0.0
    * @return    string    The name of the plugin.
    */
    public function get_plugin_name() {
        return $this->plugin_name;
    }


    /**
    * The reference to the class that orchestrates the hooks with the plugin.
    *
    * @since     1.0.0
    * @return    CDD_Loader    Orchestrates the hooks of the plugin.
    */
    public function get_loader() {
        return $this->loader;
    }


    /**
    * Retrieve the version number of the plugin.
    *
    * @since     1.0.0
    * @return    string    The version number of the plugin.
    */
    public function get_version() {
        return $this->version;
    }


    /**
    * Get the config options for the DB and initialize a mysqli object instance.
    *
    * @since     1.0.0
    * @return    object    The mysqli database connection object instance.
    */
    public function set_db_connection() {

        $config = new CDD_Config();

        $this->conn = new mysqli(
            $config->host,
            $config->user,
            $config->password,
            $config->db_name
        );

        return $this->conn;
    }


    /**
    * Retrieve the master list of queries to run.
    *
    * @since     1.0.0
    * @return    array    The multidimensional array of queries.
    */
    public function get_queries() {

        $this->queries = CDD_Queries::get_queries();

        return $this->queries;
    }


}
