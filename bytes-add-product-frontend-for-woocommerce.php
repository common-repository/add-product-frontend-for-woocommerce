<?php
/**
 * @package Bytes_Add_Product_Frontend_For_Woocommerce
 * @author Bytes Technolab
 *
 * @wordpress-plugin
 * Plugin Name:       Add Product Frontend for WooCommerce
 * Plugin URI:        https://plugins.demo1.bytestechnolab.com
 * Description:       Allow users to add products from a frontend page, edit and delete it.Users can see list of products in my account page.Admin will needs to configure mail by using WP Mail SMTP plugin or any other plugin in order to get notified when user add new product to site.  
 * Version:           1.0.4
 * Author:            Bytes Technolab
 * Author URI:        https://www.bytestechnolab.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bytes_product_frontend
 * Domain Path:       /languages
 */

/* If this file is called directly, abort */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* define constant for attributes seprator */
if ( ! defined( 'APFFW_SIGN' ) ) {
    define('APFFW_SIGN', '|');
}

define( 'APFFW_ADD_PRODUCT_FRONTEND_FOR_WOOCOMMERCE_VERSION', '1.0.4' );

/* The code that runs during plugin activation */
if(!function_exists('apffw_activate_bytes_add_product_frontend_for_woocommerce')){
    function apffw_activate_bytes_add_product_frontend_for_woocommerce() {
    	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bytes-add-product-frontend-for-woocommerce-activator.php';
    	APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Activator::apffw_activate();
    }
}

/* The code that runs during plugin deactivation */
if(!function_exists('apffw_deactivate_bytes_add_product_frontend_for_woocommerce')){
    function apffw_deactivate_bytes_add_product_frontend_for_woocommerce() {
    	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bytes-add-product-frontend-for-woocommerce-deactivator.php';
    	APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Deactivator::apffw_deactivate();
    }
}

register_activation_hook( __FILE__, 'apffw_activate_bytes_add_product_frontend_for_woocommerce' );
register_deactivation_hook( __FILE__, 'apffw_deactivate_bytes_add_product_frontend_for_woocommerce' );

/* define plugin dir path */
define('APFFW_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ));

/* define plugin dir url */
define('APFFW_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ));

/* define plugin base file name */
define('APFFW_BASENAME', plugin_basename(__FILE__));

/* Check if plugin is active or not in admin */
if(!function_exists('is_plugin_active_for_network')){
    include_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

/* Check WooCommerce plugin is active or not */
if(!function_exists('apffw_load_validation_bytes_add_product_frontend_for_woocommerce')){
    function apffw_load_validation_bytes_add_product_frontend_for_woocommerce(){
        if(current_user_can('activate_plugins') && !is_plugin_active('woocommerce/woocommerce.php')){
            /* Deactivate the plugin */
            deactivate_plugins(APFFW_BASENAME);
            $notices = get_option('', array());
            $notices[] = 'Please install and activate WooCommerce plugin <a href="' . esc_url('https://wordpress.org/plugins/woocommerce' ) . '" target="_blank">WooCommerce</a>';
            update_option('woocommerce_install_and_activate_admin_notices', $notices);
        }
        else{
            apffw_run_bytes_add_product_frontend_for_woocommerce(); // run code after plugin is activated
        }
        /* Check woocommerce plugin is not activated */
        if(!is_plugin_active('woocommerce/woocommerce.php')){
            $notices = get_option('deferred_admin_notices', array());
            $notices[] = 'Please activate WooCommerce plugin <a href="' . esc_url('https://wordpress.org/plugins/woocommerce') . '" target="_blank">WooCommerce</a> <b>to use Add Product Frontend for WooCommerce plugin.</b>';
            update_option('deferred_admin_notices', $notices);
        }
    }
}
add_action('init', 'apffw_load_validation_bytes_add_product_frontend_for_woocommerce');

/* *** load admin notices *** */
if(!function_exists('apffw_admin_notices_bytes_add_product_frontend_for_woocommerce')){
    function apffw_admin_notices_bytes_add_product_frontend_for_woocommerce(){
        /* if woocommerce plugin is not activated or installed */
        if($notices = get_option('woocommerce_install_and_activate_admin_notices')){
            foreach($notices as $notice){ ?>
                <div class='updated notice error is-dismissible'><p><?php _e($notice, 'bytes_product_frontend'); ?></p></div>
            <?php break;
            }
            delete_option('woocommerce_install_and_activate_admin_notices');
            deactivate_plugins(APFFW_BASENAME);
            if(isset($_GET['activate'])){
                unset($_GET['activate']);
            }
        }
        /* if woocommerce plugin is installed and not activated */
        if($notices = get_option('deferred_admin_notices')){
            foreach($notices as $notice){ ?>
                <div class='updated notice error is-dismissible'><p><?php _e($notice, 'bytes_product_frontend'); ?></p></div>
            <?php break;
            }
            delete_option('deferred_admin_notices');
        }
    }
}
add_action('admin_notices', 'apffw_admin_notices_bytes_add_product_frontend_for_woocommerce');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bytes-add-product-frontend-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 */
if(!function_exists('apffw_run_bytes_add_product_frontend_for_woocommerce')){
    function apffw_run_bytes_add_product_frontend_for_woocommerce(){
    	$plugin = new APFFW_Bytes_Add_Product_Frontend_For_Woocommerce();
    	$plugin->run();
    }
}

// deactivate feedback form
require_once plugin_dir_path( __FILE__ ) . 'lib/plugin-deactivation/deactivate-feedback-form.php';
add_filter('codecabin_deactivate_feedback_form_plugins', function($plugins){
    $plugins[] = (object)array(
        'slug'      => 'add-product-frontend-for-woocommerce',
        'version'   => '7.0.6'
    );
    return $plugins;
});

/* *** deactivate feedback form ajax action ***/
if(!function_exists('apffw_deactivate_plugin')){
    function apffw_deactivate_plugin(){
        if(!empty($_POST['action']) && $_POST['action'] == 'bytes_deactivate_feedback_form'){
            $current_user = wp_get_current_user();
            $current_user_email = sanitize_email($current_user->user_email); // get current user email
            $current_site_url = get_site_url(); // get current site's url
            $site_title = get_bloginfo('name'); // get site's title
            
            $to = sanitize_email("info@bytestechnolab.com"); // developer email
            $subject = "[{$site_title}]: Deactivate Plugin"; 
            $plugin_header = "Add Product Frontend"; // plugin name
            $message = 'Your plugin is deactivated by: <a href="mailto:'.$current_user_email.'">'.$current_user_email.'</a>:';
            $reason = (!empty($_POST['reason'])) ? wc_clean(wp_unslash($_POST['reason'])) : "-"; 
            $comments = (!empty($_POST['comments'])) ? wc_clean(wp_unslash($_POST['comments'])) : "-";  
            $headers = array('Content-Type: text/html; charset=UTF-8');
            require_once plugin_dir_path( __FILE__ ) . 'lib/plugin-deactivation/email/admin-deactivate-plugin.php'; // email template
            wp_mail($to, $subject, $body, $headers);
            echo json_encode(array(
                'status' => true,
                'message' => "Email has been send successfully"
            ));
        }
        exit;
    }
}
add_action('wp_ajax_bytes_deactivate_feedback_form', 'apffw_deactivate_plugin');
add_action('wp_ajax_nopriv_bytes_deactivate_feedback_form', 'apffw_deactivate_plugin');


/* *** deactivate feedback form ajax action ***/
if(!function_exists('apffw_get_users_by_role')){
    function apffw_get_users_by_role(){

        if( isset( $_POST['role'] ) ){
            $users =    get_users( array( 'role__in' => array( sanitize_text_field( $_POST['role'] ) ) ) );
            $allUsr = array();
            ob_start();
            echo '<option value="-1">' . __( 'User', 'bytes_product_frontend' ) . "</option>\n";
            if( is_array( $users ) && count( $users ) > 0) {
                foreach($users as $user ){
                    echo "<option value='".$user->ID."'>".$user->data->user_login."</option>";
                }
            }
            $options = ob_get_clean();
            
            echo json_encode(array(
                'status' => true,
                'data' =>  $options
            ));
            exit;
        }
    }
}
add_action('wp_ajax_apffw_get_users_by_role', 'apffw_get_users_by_role');
add_action('wp_ajax_nopriv_apffw_get_users_by_role', 'apffw_get_users_by_role');



