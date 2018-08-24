<?php

/**
* Define the internationalization functionality
*
* Loads and defines the internationalization files for this plugin
* so that it is ready for translation.
*
* @link       https://github.com/benhoverter/chsie-data-display
* @since      1.0.0
*
* @package    chsie-data-display
* @subpackage chsie-data-display/includes
*/

/**
* Define the internationalization functionality.
*
* Loads and defines the internationalization files for this plugin
* so that it is ready for translation.
*
* @since      1.0.0
* @package    chsie-data-display
* @subpackage chsie-data-display/includes
* @author     Ben Hoverter <ben.hoverter@gmail.com>
*/
class CDD_i18n {


    /**
    * Load the plugin text domain for translation.
    *
    * @since    1.0.0
    */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'chsie-data-display',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }


}
