<?php

/**
* The WP admin settings functionality of the plugin.
*
* @since      1.0.0
*
* @package    chsie-data-display
* @subpackage chsie-data-display/admin
*/

/**
* The WP admin settings functionality of the plugin.
*
* Defines the plugin slug, as well as the options page, sections and fields.
*
* @package    chsie-data-display
* @subpackage chsie-data-display/admin
* @author     Ben Hoverter <ben.hoverter@gmail.com> (modifier)
* @author     Tareq Hasan, WeDevs Settings API creator
*/
class CDD_Admin_Settings {

    /**
    * The ID of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $plugin_title    The ID of this plugin.
    */
    private $plugin_title;

    /**
    * The snake_case slug of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $plugin_slug    The snake_case slug of this plugin.
    */
    private $plugin_slug;

    /**
    * The version of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $version    The current version of this plugin.
    */
    private $version;

    /**
    * The object instance of the WeDevs Settings API class.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $settings_api    The object instance of the WeDevs Settings API class.
    */
    private $settings_api;

    /**
    *  The instance of the mysqli connection.
    *
    * @since    1.0.0
    * @access   private
    * @var      mysqli     $conn    The instance of the mysqli connection.
    */
    private $conn;

    /**
    * The associative array of all MySQL queries to run.
    *
    * @since    1.0.0
    * @access   private
    * @var      array    $queries    The associative array of all MySQL queries to run.
    */
    private $queries;


    /**
    * Initialize the class and set its properties.
    *
    * @since    1.0.0
    * @param      string    $plugin_title       The name of this plugin.
    * @param      string    $version    The version of this plugin.
    */
    public function __construct( $plugin_title, $version, $conn, $queries ) {

        $this->plugin_title = $plugin_title;
        $this->plugin_slug = $this->get_plugin_slug( $plugin_title );
        $this->version = $version;

        $this->conn = $conn;
        $this->queries = $queries;

        require_once plugin_dir_path( __FILE__ ) . 'settings-api/Settings-API.php';
        $this->settings_api = new WeDevs_Settings_API;

    }


    /**
    * Generate the snake_case slug from the $plugin_title.
    *
    * @since    Custom addition for WeDevs Settings API.
    * @author   Ben Hoverter
    */
    private function get_plugin_slug( $plugin_title ) {

        $plugin_slug = str_replace( array( ' ', '-' ), '_', strtolower( $plugin_title ) );

        return $plugin_slug;
    }


    /**
    * Set and Initialize the sections and fields defined in this class
    * by passing them to the API Class.
    *
    * @since    Custom addition for WeDevs Settings API.
    *
    */
    public function admin_init() {
        /**
        * An instance of this class should be passed as the second parameter
        * of the run() function defined in CDD_Loader
        * as all of the hooks are defined in that particular class.
        *
        * The CDD_Loader will then create the relationship
        * between the defined hooks and the functions defined in this
        * class.
        */

        // Pass this class' settings into the API Class.
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        // Initialize the settings in the API Class.
        $this->settings_api->admin_init();
    }


    /**
    * Add a Settings menu item  containing all Settings API pages.
    *
    * NOTE: Call this method in define_settings_hooks() if you want the plugin's
    *       settings to show as a Settings menu item, NOT a top-level menu item.
    *
    * Additional pages can be generated with new calls to add_options_page().
    * $plugin_title and $plugin_slug are followed by customizable text.
    *
    * @since    Custom addition for WeDevs Settings API.
    *
    */
    public function admin_options() {
        /**
        * An instance of this class should be passed as the second parameter
        * of the run() function defined in CDD_Loader
        * as all of the hooks are defined in that particular class.
        *
        * The CDD_Loader will then create the relationship
        * between the defined hooks and the functions defined in this
        * class.
        */
        add_options_page( $this->plugin_title . ' Settings', $this->plugin_title, 'manage_options', $this->plugin_slug . '_settings', array($this, 'plugin_page') );
    }


    /**
    * Add a top-level menu item containing all Settings API pages.
    *
    * NOTE: Call this method in define_settings_hooks() if you want the plugin's
    *       settings to show as a top-level menu item, NOT a Settings menu item.
    *
    * Additional menu items can be generated with new calls to add_menu_page().
    * $plugin_title and $plugin_slug are followed by customizable text.
    *
    * @author   Ben Hoverter (modifier)
    */
    public function admin_menu() {
        /**
        * An instance of this class should be passed as the second parameter
        * of the run() function defined in CDD_Loader
        * as all of the hooks are defined in that particular class.
        *
        * The CDD_Loader will then create the relationship
        * between the defined hooks and the functions defined in this
        * class.
        */
        add_menu_page( $this->plugin_title . ' Settings', $this->plugin_title, 'manage_options', $this->plugin_slug . '_settings', array($this, 'plugin_page') );
    }


    /**
    * Defines the settings page sections in an associative array.
    *
    * Modify values and number of elements for your needs.
    * Altering 'id' values in get_settings_sections() requires
    * matching alterations in get_settings_fields().
    *
    * NOTE: This array can take FOUR possible keys!
    *
    *           'id' and 'title' are required.
    *
    *           However, you can add either a CUSTOM DESCRIPTION or
    *           a CUSTOM CALLBACK FUNCTION. The description takes precedence,
    *           and WeDevs_Settings_API::admin_init() cannot display both.
    *
    *           You add a description with a 'desc' key whose value is a string
    *           (HTML is OK, and escaped).
    *
    *           You add a description with a 'callback' key whose value is an array
    *           holding the object instance containing the function and a string
    *           with the name of the callback function.
    *           (This is array callable syntax:
    *               http://php.net/manual/en/language.types.callable.php ).
    *           ************* This callback should echo its output *************
    *
    * @since    Custom addition for WeDevs Settings API.
    *
    */
    private function get_settings_sections() {
        $sections = array(

            array(
                'id'    => $this->plugin_slug . '_data_section',
                'title' => __( 'Data Display', 'textdomain' ),
                'callback' => array(
                    $this,
                    'do_data_section'
                )
            ),

            array(
                'id'    => $this->plugin_slug . '_lms_eval_section',
                'title' => __( 'LMS Evaluations', 'textdomain' ),
                'callback' => array(
                    $this,
                    'do_lms_evals_section'
                )
            ),

        );
        return $sections;
    }

    // ********************* SECTION CALLBACK FUNCTIONS ********************* //
    /**
    * Calls the view for the Data Display section.
    *
    * @since    1.0.0.
    */
    public function do_data_section() {
        include( plugin_dir_path( __FILE__ ) . 'views/data-section.php' ) ;
    }


    /**
    * Calls the view for the Data Display section.
    *
    * @since    1.0.0.
    */
    public function do_lms_evals_section() {
        include( plugin_dir_path( __FILE__ ) . 'views/lms-evals-documentation.php' ) ;
    }


    // *********************** VIEW-CALLED FUNCTIONS *********************** //
    /**
    * Generates the html for the Settings page dropdown <select>.
    * Called in data-section.php.
    *
    * @since    1.0.0.
    * @return   An html string.
    */
    private function do_dropdown_select( $queries ) {

        ob_start();

        ?>
        <select id='cdt-data-select'>
            <option value='default'>Select a data set</option>
            <?php

            foreach( $queries as $query ) {

                ?>
                <option value= <?php echo $query['href_value'] ?> > <?php echo $query['title'] ?> </option>
                <?php

            }

            ?>
        </select>
        <?php

        echo ob_get_clean();

    }


    /**
    * Checks the status of the MySQLi connection and throws errors.
    * Called in data-section.php.
    *
    * @since    1.0.0
    */
    public function check_conn_status( $conn ) {

        // Check connection.
        if ($conn->connect_error) {

            $conn_status = '<p style="color: crimson;">Connection failed: ' . $conn->connect_error . ' .</p>';
            die("Sorry, connection problem: " . $conn->connect_error);

        } else {

            $conn_status = '<p><em>Connection to database established.</em></p>';

        }

        return $conn_status;
    }


    // ******************* CREATE SETTINGS API FIELDS HERE ******************* //
    /**
    * Defines and returns all the settings fields.
    * Modify values and number of elements for your needs.
    *
    * @since   Custom addition for WeDevs Settings API.
    * @return  Array of settings fields.
    */
    private function get_settings_fields() {
        $settings_fields = array(
            $this->plugin_slug . '_lms_eval_section' => array( // The fields for the LMS Evaluations tab.
                array(
                    'name'              => 'text_val',
                    'label'             => __( 'Text Input', 'textdomain' ),
                    'desc'              => __( 'Text input description', 'textdomain' ),
                    'placeholder'       => __( 'Text Input placeholder', 'textdomain' ),
                    'type'              => 'text',
                    'default'           => 'Title',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'number_input',
                    'label'             => __( 'Number Input', 'textdomain' ),
                    'desc'              => __( 'Number field with validation callback `floatval`', 'textdomain' ),
                    'placeholder'       => __( '1.99', 'textdomain' ),
                    'min'               => 0,
                    'max'               => 100,
                    'step'              => '0.01',
                    'type'              => 'number',
                    'default'           => 'Title',
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'        => 'textarea',
                    'label'       => __( 'Textarea Input', 'textdomain' ),
                    'desc'        => __( 'Textarea description', 'textdomain' ),
                    'placeholder' => __( 'Textarea placeholder', 'textdomain' ),
                    'type'        => 'textarea'
                ),
                array(
                    'name'        => 'html',
                    'desc'        => __( 'HTML area description. You can use any <strong>bold</strong> or other HTML elements.', 'textdomain' ),
                    'type'        => 'html'
                ),
                array(
                    'name'  => 'checkbox',
                    'label' => __( 'Checkbox', 'textdomain' ),
                    'desc'  => __( 'Checkbox Label', 'textdomain' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'    => 'radio',
                    'label'   => __( 'Radio Button', 'textdomain' ),
                    'desc'    => __( 'A radio button', 'textdomain' ),
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'selectbox',
                    'label'   => __( 'A Dropdown', 'textdomain' ),
                    'desc'    => __( 'Dropdown description', 'textdomain' ),
                    'type'    => 'select',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'textdomain' ),
                    'desc'    => __( 'Password description', 'textdomain' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'file',
                    'label'   => __( 'File', 'textdomain' ),
                    'desc'    => __( 'File description', 'textdomain' ),
                    'type'    => 'file',
                    'default' => '',
                    'options' => array(
                        'button_label' => 'Choose Image'
                    )
                )
            )
        );

        return $settings_fields;
    }


    // *********************** SETTINGS API PAGE MAKERS *********************** //
    //                              ( NO TOUCHING. )
    /**
    * Callback function to generate HTML elements for Settings Page.
    * Required for add_options_page().
    *
    * Must be duplicated and made unique for add'l Options pages.
    *
    * @since    Custom addition for WeDevs Settings API.
    *
    */
    public function plugin_page() {
        echo '<div class="wrap">';
        echo '<h1>' . $this->plugin_title . ' Settings</h1>';

        settings_errors();

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }


    /**
    * Retrieves all pages on the site for use in the Settings API.
    * WP's get_pages() takes parameters to filter pages retrieved.
    *
    * @since    Custom addition for WeDevs Settings API.
    * @return   An array of page names indexed by ID.
    */
    private function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
