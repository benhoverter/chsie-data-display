<?php

/**
* Does one thing well.
*
* @link       https://github.com/benhoverter/chsie-data-display
* @since      1.0.0
*
* @package    chsie-data-display
* @subpackage chsie-data-display/admin/module-ajax
*/

/**
* Does one thing well.
*
* Here's the description of how it does it.
*
* @package    chsie-data-display
* @subpackage chsie-data-display/admin/module-ajax
* @author     Ben Hoverter <ben.hoverter@gmail.com>
*/
class CDD_Admin_Settings_Ajax {

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
    * The data array for admin AJAX functions.
    *
    * @since    1.0.0
    * @access   public
    * @var      associative array    $ajax_data    The data for admin AJAX functions.
    */
    public $ajax_data;

    /**
    * The nonce for the AJAX call.  Must be available to event_mats_ajax_save().
    *
    * @since    1.0.0
    * @access   public
    * @var      string    $ajax_nonce    The nonce for the AJAX call.
    */
    public $ajax_nonce;

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
    * @param      string    $plugin_name       The name of this plugin.
    * @param      string    $version    The version of this plugin.
    */
    public function __construct( $plugin_name, $version/* , $conn, $query_master_list */ ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }


    // ***** PRE-CALL METHODS ***** //

    /**
    * Get all data to be passed to the frontend.
    * Localized in "../Admin.php".
    *
    * @return   array     $this->ajax_data     The associative array of data to pass.
    * @since    1.0.0
    */
    public function get_ajax_data() {

        // Needed on the frontend. No touching!
        $this->ajax_data[ 'ajax_url' ] = admin_url( 'admin-ajax.php' );

        // Gets checked in module_ajax_callback().
        //$this->ajax_data[ 'module_ajax_nonce' ] = wp_create_nonce( 'cdd_module_ajax_nonce' );

        // Add key => value pairs here.

        return $this->ajax_data;

    }


    // ***** CALL METHODS ***** //

    /**
    * AJAX callback function to bind to wp_ajax_{action_name} hook.
    * Wrapper for do_data_table().
    *
    * @since    1.0.0
    */
    public function cdd_ajax_data_table() {

        //check_ajax_referer( 'cdd_module_ajax_nonce', 'module_ajax_nonce' ); // Dies if false.

        // Call the handler function.
        //echo $this->do_data_table();
        echo '<p>Success!</p>';

        include( plugin_dir_path( __FILE__ ) . 'views/data-display.php' );

        // Needed to return AJAX:
        wp_die();

    }


    /**
    * Handler function called by ajax_datatables().
    *
    * @since    1.0.0
    */
    public function do_data_table() {

        ob_start();

        // Pass in the query from the user select after comparing it to the master list.
        $selected_query = $this->get_selection();

        if( $selected_query === NULL ) {

            ?>
            <p>Please select a data set to display.</p>
            <?php

        } else {

            // Parse the selected query array.
            $query_array = $this->parse_selection( $selected_query );

            // Create the views.
            echo $this->create_sql_views( $this->conn, $query_array ); // Returns messages.

            // Display the data.
            echo $this->display_data( $this->conn, $query_array ); // Returns the table node.

            // Verify the views have been dropped.  echo to debug.
            echo $this->drop_sql_views( $this->conn, $query_array ); // Returns "Views dropped" message.

        }

        return ob_get_clean();

    }




    // *********** PROCESSING FUNCTIONS CALLED IN do_data_table() *********** //
    /**
    * This grabs the query string from POST and compares it to options
    * in the $query_master_list.
    * It returns the $selected_query for parse_selection().
    *
    * @since    1.0.0
    */
    public function get_selection() {

        if ( !isset( $_POST['data_selection'] ) ) {
            return NULL;
        }

        $ajax_query_str = $_POST['data_selection'];

        //echo $ajax_query_str;

        foreach( $this->query_master_list as $query ) {

            if( $query['href_value'] === $ajax_query_str ) {

                $selected_query = $query['content'];
                break;

            } else {

                $selected_query = NULL;

            }
        }

        return $selected_query;
    }


    /**
    * This takes apart one of the inner arrays that holds the SQL queries
    * and table names for data retrieval.
    *
    * @since    1.0.0
    */
    public function parse_selection( $selected_query ) {

        if ( $selected_query === NULL ) {

            $query_array = array();

        } elseif ( $selected_query['inner'] !== 0 && ! empty( $selected_query['inner'] ) ) {

            $query_array = array(
                'title' => $selected_query['outer']['table_name'],
                'inner_view' => $selected_query['inner']['query_str'],
                'outer_view' => $selected_query['outer']['query_str'],
                'main_query' => "SELECT * FROM {$selected_query['outer']['table_name']}",
                'drop_query' => "DROP VIEW IF EXISTS {$selected_query['inner']['table_name']}, {$selected_query['outer']['table_name']}"
            );

        } else {

            $query_array = array(
                'title' => $selected_query['outer']['table_name'],
                'inner_view' => 0,
                'outer_view' => $selected_query['outer']['query_str'],
                'main_query' => "SELECT * FROM {$selected_query['outer']['table_name']}",
                'drop_query' => "DROP VIEW IF EXISTS {$selected_query['outer']['table_name']}"
            );

        }

        return $query_array;

    }


    /**
    * Creates the views and generates success/error messages.
    *
    * @since    1.0.0
    */
    public function create_sql_views( $conn, $query_array ) {

        $view_status = '';

        if ( !empty( $query_array ) ) {  // If the query array has content...

            if ( $query_array['inner_view'] !== 0 ) {  // Test if the inner view exists.  If so, run it.

                if( $view_creator = $conn->query( $query_array['inner_view'] ) ) { // Verify success.

                    //$view_status .= "<p>Inner view created successfully!</p>";

                } else {

                    $view_status .= "<p style='color: crimson;'>Failed to create inner view.</p>";  // Error.
                }

            } else if ( $query_array['inner_view'] === 0 ) {  // If the inner view doesn't exist in a proper query...

                //$view_status .= "<p>No inner view to create.</p>";

            }

            // Regardless, run the outer view.
            if( $view_creator = $conn->query( $query_array['outer_view'] ) ) {  // Verify success.

                //$view_status .= "<p>Outer view created successfully!</p>";

            } else {

                $view_status .= "<p style='color: crimson;'>Failed to create outer view.</p>";  // Error.
            }
        }

        return $view_status;
    }


    /**
    * Runs the MySQL query and generates an HTML table of the results.
    *
    * @since    1.0.0
    */
    public function display_data( $conn, $query_array ) {

        $display_data = "";

        if ( !empty( $query_array ) ) {  // If the query array has content...

            $main_query_result = $conn->query( $query_array['main_query'] );

            // Generate the HTML for the table headers and rows of data
            if( $main_query_result->num_rows > 0) {

                $display_data = '<table class="display" data-title="' . $query_array['title'] . '"><thead><tr>';

                // Output the column names as a table header row.
                while( $header_name = $main_query_result->fetch_field() ) { // Gets header field name, one per loop.

                    $col_names[] = $header_name->name;  // Pushes the column name onto the end of an array.

                    $display_header_name = '<th>' . $header_name->name . '</th>';

                    $display_data .= $display_header_name; // Concatenate the new table header to the table.

                }

                $display_data .= '</tr></thead><tbody>';  // Finish the header row and start the body.

                // Output the data in the table.
                while( $row = $main_query_result->fetch_assoc() ) {  // Gets one row of data with each loop.

                    $display_row = '<tr>';  // Start the row HTML.

                    for ( $i = 0; $i < ( $n = count( $col_names ) ); $i++ ) {

                        $display_row .= '<td>' . $row[ $col_names[$i] ] . '</td>';  // Get the row entry with the key corresponding to the proper column name and concatenate it to the rest of the row.
                    }

                    $display_row .= '</tr>';  // Finish the row HTML.

                    $display_data .= $display_row; // Concatenate the new row to the old HTML.
                }

                $display_data .= '</tbody></table>' ; // Closes the HTML tag.

            } else { // Error when query returns no rows.

                $display_data .= '<p style="color: crimson;">Hmm. It looks like that query returned no data.</p>';
            }
        }

        return $display_data;

    }


    /**
    * Drops the views from the database.
    *
    * @since    1.0.0
    */
    public function drop_sql_views( $conn, $query_array ) {

        ob_start();

        if ( !empty( $query_array ) ) {
            if ( $conn->query( $query_array['drop_query'] ) ) {  // Drops the views.

                ?>
                <p id="cdd-drop-views">Views successfully dropped.</p>
                <?php

            } else {

                ?>
                <p id="cdd-drop-views" style="color: crimson;">ERROR: Views not dropped!</p>
                <?php

            }
        }

        return ob_get_clean();

    }
}