<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 */
if(!class_exists('APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Deactivator')){
	class APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Deactivator {
		public static function apffw_deactivate() {
			/* delete WooCommerce install and activate notice */
	        delete_option('woocommerce_install_and_activate_admin_notices'); 
	        delete_option('deferred_admin_notices');
	        delete_option("bytes_permalink_update"); // delete permalink setting
		}
	}
}
