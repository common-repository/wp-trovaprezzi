<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.wemiura.com
 * @since      1.0.0
 *
 * @package    Wp_trova_prezzi_free
 * @subpackage Wp_trova_prezzi_free/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_trova_prezzi_free
 * @subpackage Wp_trova_prezzi_free/includes
 * @author     WeMiura <info@wemiura.com>
 */
class Wp_trova_prezzi_free_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-trova-prezzi-free',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
