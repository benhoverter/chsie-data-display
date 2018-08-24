<?php

/**
* This class stores the plugin SQL queries. It is not instanced.
*
* NOTE: IMPORTANT! THIS FILE SHOULD BE INCLUDED IN YOUR .gitignore FILE!
*       Do not expose your SQL queries on your repository!
*
* @link       https://github.com/benhoverter/chsie-data-display
* @since      1.0.0
*
* @package    chsie-data-display
* @subpackage chsie-data-display/config
*/

/**
* This class stores the plugin SQL queries. It is not instanced.
* *
* @since      1.0.0
* @package    chsie-data-display
* @subpackage chsie-data-display/config
* @author     Ben Hoverter <ben.hoverter@gmail.com>
*/
class CDD_Queries {

    /**
    * This method retrieves the plugin SQL queries. It is called directly.
    *
    * @since    1.0.0
    */
    public static function get_queries() {
        return self::$queries;
    }


    /**
    * The array used to store all queries the plugin can run.
    * Values are examples only.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $queries    The array used to store all queries the plugin can run.
    *
    */
    public static $queries = array(
        // Queries go here.
    );

}
