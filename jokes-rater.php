<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              frawebdev.com
 * @since             1.0.0
 * @package           Jokes_Rater
 *
 * @wordpress-plugin
 * Plugin Name:       Jokes Rater
 * Plugin URI:        https://frawebdev.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            FraWebDev
 * Author URI:        frawebdev.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jokes-rater
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'JOKES_RATER_VERSION', '1.0.0' );

/**
 * Directory path of the plugin
 */
define( 'JR_PLUGIN_PATH', plugin_dir_path(__FILE__) );

/**
 * url of the plugin
 */
define( 'JR_PLUGIN_URL', plugin_dir_url(__FILE__) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jokes-rater-activator.php
 */
function activate_jokes_rater() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jokes-rater-activator.php';
	Jokes_Rater_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jokes-rater-deactivator.php
 */
function deactivate_jokes_rater() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jokes-rater-deactivator.php';
	Jokes_Rater_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_jokes_rater' );
register_deactivation_hook( __FILE__, 'deactivate_jokes_rater' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-jokes-rater.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_jokes_rater() {

	$plugin = new Jokes_Rater();
	$plugin->run();

}
run_jokes_rater();
