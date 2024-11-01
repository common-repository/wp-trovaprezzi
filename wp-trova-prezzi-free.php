<?php

/**
 *
 * @link              http://www.wemiura.com
 * @since             1.0.0
 * @package           Wp_trova_prezzi_free
 *
 * @wordpress-plugin
 * Plugin Name:       Wp TrovaPrezzi Free ( Trova Prezzi )
 * Plugin URI:        http://www.wemiura.com
 * Description:       Wp TrovaPrezzi WordPress / WooCommerce Plugin is a WordPress plugin that allows in a simple way, fast way and totally customizable, integration with TrovaPrezzi, creating a CSV or XML feeds. ( Trova Prezzi )
 * Version:           1.1
 * Author:            WeMiura
 * Author URI:        http://www.wemiura.com
 * License:           GPL-2.0+
 * Text Domain:       wp-trova-prezzi-free
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-trova-prezzi-free-activator.php
 */
function activate_wp_trova_prezzi_free() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-trova-prezzi-free-activator.php';
	Wp_trova_prezzi_free_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-trova-prezzi-free-deactivator.php
 */
function deactivate_wp_trova_prezzi_free() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-trova-prezzi-free-deactivator.php';
	Wp_trova_prezzi_free_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_trova_prezzi_free' );
register_deactivation_hook( __FILE__, 'deactivate_wp_trova_prezzi_free' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-trova-prezzi-free.php';
require plugin_dir_path( __FILE__ ) . 'includes/wp_feed.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_trova_prezzi_free() {

	$plugin = new Wp_trova_prezzi_free();
	$plugin->run();

}
run_wp_trova_prezzi_free();


add_action('admin_head','set_wp_trovaprezzi_wp_list_table_style');
function set_wp_trovaprezzi_wp_list_table_style(){
?>
<style type="text/css">
.set_wp_trovaprezzi_wp_list_table { cursor: pointer; }
</style>
<?php
}
