<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       frawebdev.com
 * @since      1.0.0
 *
 * @package    Jokes_Rater
 * @subpackage Jokes_Rater/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Jokes_Rater
 * @subpackage Jokes_Rater/includes
 * @author     FraWebDev <fra@frawebdev.com>
 */
class Jokes_Rater_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'jokes-rater',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
