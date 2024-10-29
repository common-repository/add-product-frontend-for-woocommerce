<?php
/**
 * Fired when the plugin is uninstalled.
 */

/* If uninstall not called from WordPress, then exit */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/* delete WooCommerce install and activate notice */
delete_option('woocommerce_install_and_activate_admin_notices'); 
delete_option('deferred_admin_notices');

/* delete plugin setting for additional fields, status and user role */
delete_option('frontend_product_additional_fields');
delete_option('frontend_product_status'); 
delete_option('frontend_product_user_role'); 

/* delete add products frontend page */
$bytes_page_id = get_option('bytes_plugin_page_id');
if($bytes_page_id){
    wp_delete_post($bytes_page_id, true);
}
delete_option("bytes_plugin_page_id");
delete_option("bytes_plugin_template");
delete_option("bytes_permalink_update"); // delete permalink setting