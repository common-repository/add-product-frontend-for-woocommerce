<?php
/* Template Name: Bytes Add Product Frontend template*/
get_header();
if(is_user_logged_in()){
    update_option('bytes_plugin_template', 'bytes-product-frontend-template');

    global $current_user; // get the current user
    $role = $current_user->roles[0]; // display the current user's role
    
    $frontend_product_user_role = get_option('frontend_product_user_role');
    $current_user_product_access = isset($frontend_product_user_role[$role]) ? wc_clean(wp_unslash($frontend_product_user_role[$role])) : "";

    if($current_user_product_access == 1){
        // get additional field option
        $frontend_product_additional_fields = get_option('frontend_product_additional_fields');
?>
    <div style="text-align: center;">
        <h1><?php _e( get_the_title(), 'bytes_product_frontend' ); ?></h1>
    </div>
    <div class="bt-plugin-context" id="woocommerce-product-data">
        <div class="bt-plugin-context-inner">
            <form method="post" id="bt-product-form" onkeydown="return event.key != 'Enter';">
                <div class="bt-main-col">
                    <?php
                        require_once( plugin_dir_path( __FILE__ ).'components/bytes-woo-template-title.php'); // Title
                        if(isset($frontend_product_additional_fields['description']) && $frontend_product_additional_fields['description'] == 1){
                            require_once( plugin_dir_path( __FILE__ ).'components/bytes-woo-template-description.php'); // Description
                        }

                        require_once( plugin_dir_path( __FILE__ ).'components/bytes-woo-template-product-options.php'); // WC Product options
                        if(isset($frontend_product_additional_fields['short_description']) && $frontend_product_additional_fields['short_description'] == 1){
                            require_once( plugin_dir_path( __FILE__ ).'components/bytes-woo-template-excerpt.php'); // Short description / excerpt
                        }
                    ?>
                </div>
                <div class="bt-side-col">
                    <?php
                        if(isset($frontend_product_additional_fields['categories']) && $frontend_product_additional_fields['categories'] == 1){
                            require_once( plugin_dir_path( __FILE__ ).'components/bytes-woo-template-categories.php'); // Categories
                        }
                        if(isset($frontend_product_additional_fields['tags']) && $frontend_product_additional_fields['tags'] == 1){
                            require_once( plugin_dir_path( __FILE__ ).'components/bytes-woo-template-tags.php'); // Tags
                        }    
                        if(isset($frontend_product_additional_fields['image']) && $frontend_product_additional_fields['image'] == 1){
                            require_once( plugin_dir_path( __FILE__ ).'components/bytes-woo-template-product-image.php'); // Product image
                        }
                        else{
                             if(current_user_can('upload_files')){ wp_enqueue_media(); }
                        }
                        if(isset($frontend_product_additional_fields['gallery']) && $frontend_product_additional_fields['gallery'] == 1){
                            require_once( plugin_dir_path( __FILE__ ).'components/bytes-woo-template-product-gallery.php'); // Product gallery
                        }
                        else{
                             if(current_user_can('upload_files')){ wp_enqueue_media(); }
                        }
                    ?>
                </div>
                <div style="width: 100%;float: left;">
                    <button id="bt-save-product" class="bt-button-default bt-button-primary" type="submit"><?php _e( 'Save', 'bytes_product_frontend' ); ?></button>
                </div>
            </form>
            <!-- The Modal -->
            <div class="bt-modal">
                <!-- Modal content -->
                <div class="bt-modal-content">
                    <div class="bt-loading-section">
                        <div class="bt-loader"></div>
                    </div>
                    
                    <!-- Success -->
                    <div class="bt-modal-result bt-hide-section">
                        <div class="bt-modal-header">
                            <h2><?php _e( 'Success', 'bytes_product_frontend' ); ?></h2>
                        </div>
                        <div class="bt-modal-main-section">
                            <p><?php _e( 'Your product has been added correctly!', 'bytes_product_frontend' ); ?></p>
                        </div>
                        <button  class="bt-button-default bt-button-primary"> <?php _e( 'OK', 'bytes_product_frontend' ); ?> </button>
                    </div>

                    <!-- Error -->
                    <div class="bt-modal-result-error bt-hide-section">
                        <div class="bt-modal-header">
                            <h2><?php _e( 'Error!', 'bytes_product_frontend' ); ?></h2>
                        </div>
                        <div class="bt-modal-main-section">
                            <p></p>
                        </div>
                        <button  class="bt-button-default bt-button-primary"><?php _e( 'Close', 'bytes_product_frontend' ); ?></button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

<?php
    }
    else{ ?>
        <div class="bt-plugin-context">
            <div class="bt-warning-message">
                <p><?php _e( 'You are not allow to add product!', 'bytes_product_frontend' ); ?></p>
            </div>
        </div>
    <?php }
}
else {
?>
    <div class="bt-plugin-context">
        <div class="bt-warning-message">
            <p><?php _e( 'You  must be logged in to view this content!', 'bytes_product_frontend' ); ?></p>
        </div>
    </div>
<?php
   
}
get_footer();
?>