<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 */
if(!class_exists('APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Activator')){
    class APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Activator {
    	public static function apffw_activate(){
            /* Check WooCommerce plugin is active or not */
            if(current_user_can('activate_plugins') && !is_plugin_active('woocommerce/woocommerce.php')){
                /* *** Deactivate the plugin *** */
                deactivate_plugins(APFFW_BASENAME);
                $notices = get_option('woocommerce_install_and_activate_admin_notices', array());
                $notices[] = 'Please install and activate WooCommerce plugin <a href="' . esc_url('https://wordpress.org/plugins/woocommerce' ) . '" target="_blank">WooCommerce</a>';
                update_option('woocommerce_install_and_activate_admin_notices', $notices);
            }
            else{
                /* *** Admin setting *** */
                $frontend_product_additional_fields = array(
                    'description' => 1,
                    'short_description' => 1,
                    'image' => 1,
                    'gallery' => 1,
                    'categories' => 1,
                    'tags' => 1,
                    'regular_price' => 1,
                    'sale_price' => 1,
                    'sku' => 1,
                    'manage_stock' => 1,
                    'stock_status' => 1,
                    'sold_individually' => 1,
                    'weight' => 1,
                    'dimensions' => 1,
                    'linked_products' => 1,
                    'attributes' => 1,
                    'purchase_note' => 1,
                    'menu_order' => 1,
                    'enable_reviews' => 1,
                );
                $frontend_product_user_role = array(
                    'administrator' => 1,
                    'editor' => 0,
                    'author' => 0,
                    'contributor' => 0,
                    'subscriber' => 0,
                    'customer' => 1,
                    'shop_manager' => 1
                );
                /* *** Create page on plugin activation *** */
                $bytes_page = array(
                    'post_title'    => wp_strip_all_tags('Add Products Frontend'),
                    'post_name' => 'add-products-frontend',
                    'post_content'  => '',
                    'post_status'   => 'publish',
                    'post_author'   => 1,
                    'post_type'     => 'page',
                );
                /* On plugin activation, set the default template */
                if(!empty(get_option('bytes_plugin_template'))){}
                else{
                    update_option('frontend_product_additional_fields', $frontend_product_additional_fields);
                    update_option('frontend_product_status', 'draft');
                    update_option('frontend_product_user_role', $frontend_product_user_role);
                    $bytes_page_id = wp_insert_post($bytes_page);
                    add_option('bytes_plugin_page_id', $bytes_page_id);
                    add_option('bytes_plugin_template', 'bytes-product-frontend-template');
                    add_option('bytes_permalink_update', '1'); // add permalink setting option
                }
            }
    	}
    }
}