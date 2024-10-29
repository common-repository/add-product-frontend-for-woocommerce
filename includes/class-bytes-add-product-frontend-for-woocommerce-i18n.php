<?php
/**
 * Define the internationalization functionality
 */
if(!class_exists('APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_i18n')){
	class APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_i18n {
		/* Load the plugin text domain for translation */
		public function apffw_load_plugin_textdomain() {
			load_plugin_textdomain(
				'bytes_product_frontend',
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);
		}
	}
}