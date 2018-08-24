<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              https://github.com/benhoverter/chsie-data-display
* @since             1.0.0
* @package           chsie-data-display
*
* @wordpress-plugin
* Plugin Name:       CHSIE Data Display
* Plugin URI:        https://github.com/benhoverter/chsie-data-display
* Description:       Provides CHSIE administrators with data display and export capabilities.
* Version:           1.0.0
* Author:            Ben Hoverter
* Author URI:        http://github.com/benhoverter
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       chsie-data-display
* Domain Path:       /languages
*/

// If this file is called directly, abort.

if ( !defined( 'WPINC' ) ) {
    die;
}


/**
* Current plugin version.
* Start at version 1.0.0 and use SemVer - https://semver.org
* Rename this for your plugin and update it as you release new versions.
*/
define( 'CHSIE_DATA_DISPLAY_VERSION', '1.0.0' );

/**
* The code that runs during plugin activation.
* This action is documented in includes/Activator.php
*/

function activate_chsie_data_display() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/Activator.php';
    CDD_Activator::activate();
}


/**
* The code that runs during plugin deactivation.
* This action is documented in includes/Deactivator.php
*/

function deactivate_chsie_data_display() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/Deactivator.php';
    CDD_Deactivator::deactivate();
}


register_activation_hook( __FILE__, 'activate_chsie_data_display' );
register_deactivation_hook( __FILE__, 'deactivate_chsie_data_display' );


/**
* The core plugin class that is used to define internationalization,
* admin-specific hooks, and public-facing site hooks.
*/
require plugin_dir_path( __FILE__ ) . 'includes/Main.php';

/**
* Begins execution of the plugin.
*
* Since everything within the plugin is registered via hooks,
* then kicking off the plugin from this point in the file does
* not affect the page life cycle.
*
* @since    1.0.0
*/

function run_chsie_data_display() {

    $plugin = new CHSIE_Data_Display();
    $plugin->run();

}

run_chsie_data_display();
