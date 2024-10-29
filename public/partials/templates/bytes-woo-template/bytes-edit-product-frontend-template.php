<?php
get_header();
if(is_user_logged_in()){
    update_option('bytes_plugin_template', 'bytes-product-frontend-template');

    global $current_user; // get the current user
    $role = $current_user->roles[0]; // display the current user's role
    
    $frontend_product_user_role = get_option('frontend_product_user_role');
    $current_user_product_access = isset($frontend_product_user_role[$role]) ? wc_clean(wp_unslash($frontend_product_user_role[$role])) : "";

    if($current_user_product_access == 1){
        $product_id = isset( $_GET['id'] ) ? wc_clean( wp_unslash( $_GET['id'] ) ) : false;
        $product_object = wc_get_product($product_id); // get product details
        
        /* Get the product tag */
        $terms = get_the_terms($product_id, 'product_tag');
        $product_tags = array();
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
            foreach ( $terms as $term ) {
                $product_tags[] = $term->slug;
            }
        }
        $edit_product_tags = implode(",", $product_tags);

        /* Get the product gallery images */
        $product_gallery_ids = $product_object->get_gallery_image_ids();
        $product_gallery_images_ids = implode(',', $product_gallery_ids);

        // Get product tax status and class
        $product_tax_status = $product_object->get_tax_status();
        $product_tax_class = $product_object->get_tax_class();

        // get product inventory information
        $manage_stock = $product_object->get_manage_stock();
        $product_manage_stock = ($manage_stock == 1) ? "checked" : ""; // manage stock
        $product_stock_status = $product_object->get_stock_status(); // get stock status
        $product_sold_individually = (($product_object->get_sold_individually()) == 1) ? "checked" : ""; // sold individually 
        $product_backorders = $product_object->get_backorders(); // get backorders

        // get product advanced information
        $product_reviews_allowed = ($product_object->get_reviews_allowed() == 1) ? "checked" : "";

        $virtual_product = ($product_object->is_virtual()) ? 'checked' : ""; // get vitual product
        $downloadable_product = ($product_object->is_downloadable()) ? 'checked' : ""; // get downloadable product
        $product_download_limit = $product_object->get_download_limit() ? $product_object->get_download_limit() : "";
        $product_download_limit = ($product_download_limit == -1) ? "" : $product_download_limit;    
        $product_download_expiry = $product_object->get_download_expiry() ? $product_object->get_download_expiry() : "";
        $product_download_expiry = ($product_download_expiry == -1) ? "" : $product_download_expiry;    

        // external/affiliate product info
        if($product_object->get_type() == 'external'){
            $external_product_url = $product_object->get_product_url() ? $product_object->get_product_url() : "";
            $external_button_text = $product_object->get_button_text() ? $product_object->get_button_text() : "";
        }
        else{
            $external_product_url = "";
            $external_button_text = "";
        }

        // grouped product ids
        $grouped_product_ids = $product_object->is_type('grouped') ? $product_object->get_children() : array();

        // get additional field option
        $frontend_product_additional_fields = get_option('frontend_product_additional_fields');

?>
    <div class="bt-plugin-context" id="woocommerce-product-data">
        <div class="bt-plugin-context-inner">
            <form method="post" id="bt-edit-product-form" onkeydown="return event.key != 'Enter';">
                <div class="bt-main-col">
                    <input type="hidden" id="product_id" value="<?php echo $product_id; ?>" name="product_id">
                    <?php
                        require_once( plugin_dir_path( __FILE__ ).'components-edit/bytes-woo-template-title.php'); // Title
                        if(isset($frontend_product_additional_fields['description']) && $frontend_product_additional_fields['description'] == 1){
                            require_once( plugin_dir_path( __FILE__ ).'components-edit/bytes-woo-template-description.php'); // Description
                        }
                        require_once( plugin_dir_path( __FILE__ ).'components-edit/bytes-woo-template-product-options.php'); // WC Product options
                        if(isset($frontend_product_additional_fields['short_description']) && $frontend_product_additional_fields['short_description'] == 1){
                            require_once( plugin_dir_path( __FILE__ ).'components-edit/bytes-woo-template-excerpt.php'); // Short description / excerpt
                        }
                    ?>
                </div>
                <div class="bt-side-col">
                    <?php
                        if(isset($frontend_product_additional_fields['categories']) && $frontend_product_additional_fields['categories'] == 1){
                            require_once( plugin_dir_path( __FILE__ ).'components-edit/bytes-woo-template-categories.php'); // Categories
                        }
                        if(isset($frontend_product_additional_fields['tags']) && $frontend_product_additional_fields['tags'] == 1){
                            require_once( plugin_dir_path( __FILE__ ).'components-edit/bytes-woo-template-tags.php'); // Tags
                        }
                        if(isset($frontend_product_additional_fields['image']) && $frontend_product_additional_fields['image'] == 1){
                            require_once( plugin_dir_path( __FILE__ ).'components-edit/bytes-woo-template-product-image.php'); // Product image
                        }    
                        else{
                             if(current_user_can('upload_files')){ wp_enqueue_media(); }
                        }
                        if(isset($frontend_product_additional_fields['gallery']) && $frontend_product_additional_fields['gallery'] == 1){
                            require_once( plugin_dir_path( __FILE__ ).'components-edit/bytes-woo-template-product-gallery.php'); // Product gallery
                        }
                        else{
                             if(current_user_can('upload_files')){ wp_enqueue_media(); }
                        }
                    ?>
                </div>
                <div style="width: 100%;float: left;">
                    <button id="bt-save-product" class="bt-button-default bt-button-primary" type="submit"><?php _e( 'Update', 'bytes_product_frontend' ); ?></button>
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
                            <p><?php _e( 'Your product has been updated correctly!', 'bytes_product_frontend' ); ?></p>
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
                        <button  class="bt-button-default bt-button-primary"> <?php _e( 'Close', 'bytes_product_frontend' ); ?> </button>
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
                <p><?php _e( 'You are not allow to update product!', 'bytes_product_frontend' ); ?></p>
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