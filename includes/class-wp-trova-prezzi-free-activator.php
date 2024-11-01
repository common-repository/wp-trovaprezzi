<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.wemiura.com
 * @since      1.0.0
 *
 * @package    Wp_trova_prezzi_free
 * @subpackage Wp_trova_prezzi_free/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_trova_prezzi_free
 * @subpackage Wp_trova_prezzi_free/includes
 * @author     WeMiura <info@wemiura.com>
 */
class Wp_trova_prezzi_free_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{

		if(!get_option('wp_trovaprezzi_category_tp_settings')) { add_option('wp_trovaprezzi_category_tp_settings','product_cat'); }
		if(!get_option('wp_trovaprezzi_brand_settings')) { add_option('wp_trovaprezzi_brand_settings',array(
			'choice' => '',
			'value' => ''
		)); }
		if(!get_option('wp_trovaprezzi_code_settings')) { add_option('wp_trovaprezzi_code_settings',array(
			'choice' => '_sku',
			'value' => ''
		)); }
		if(!get_option('wp_trovaprezzi_description_settings')) { add_option('wp_trovaprezzi_description_settings','short'); }
		if(!get_option('wp_trovaprezzi_eancode_settings')) { add_option('wp_trovaprezzi_eancode_settings',array(
			'choice' => '_sku',
			'value' => ''
		)); }
		if(!get_option('wp_trovaprezzi_part_number_settings')) { add_option('wp_trovaprezzi_part_number_settings',array(
			'choice' => '_sku',
			'value' => ''
		)); }
		if(!get_option('wp_trovaprezzi_shipping_cost_settings')) { add_option('wp_trovaprezzi_shipping_cost_settings','flat_rate'); }
		if(!get_option('wp_trovaprezzi_custom_shipping_cost_settings')) { add_option('wp_trovaprezzi_custom_shipping_cost_settings',array( 'value' => 0, 'filter' => -1 )); }

		if (!(is_plugin_active('woocommerce/woocommerce.php'))) {
			wp_die('Questo Plugin richiede che sia installato ed attivato Woocommerce <a href="https://wordpress.org/plugins/woocommerce/" target="_blank" />https://wordpress.org/plugins/woocommerce/</a>');
		}


	}
}
