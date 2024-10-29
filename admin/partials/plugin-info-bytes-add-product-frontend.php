<?php
    $bytes_product_frontend_info = get_admin_url()."admin.php?page=bytes-product-frontend-info";
    $bytes_product_frontend_setting = get_admin_url()."admin.php?page=bytes-product-frontend-setting";  
    $bytes_product_list = get_admin_url()."admin.php?page=bytes-product-list";  
?>
<div class="wrap custom-user-wrap">
    <hr class="wp-header-end">
    <div class="entry-edit">
        <div class="container postbox-container">            
            <div class="row mt-4">
                 <div class="col-md-9">
                     <div class="row align-items-center h-100">
                        <div class="col">
                            <h2><?php esc_html_e('Add Product Frontend for WooCommerce'.APFFW_ADD_PRODUCT_FRONTEND_FOR_WOOCOMMERCE_VERSION, 'bytes_product_frontend'); ?></h2>
                            <p class="lead mt-3"><?php esc_html_e('Thank you for choosing Add Product Frontend for WooCommerce! We are committed to providing you with the best experience possible for creating products directly from the frontend for your WooCommerce store.', 'bytes_product_frontend'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 bg-white d-flex">
                    <img src="<?php echo APFFW_PLUGIN_DIR_URL.'admin/images/bytes-logo.svg'; ?>" alt="Bytes Add Product Frontend" class="img-fluid" />
                </div>
            </div> 
            <div class="row bg-white p-5 mt-2">
                <div class="col-md-12">
                    <h3><?php esc_html_e('All ready to go!', 'bytes_product_frontend'); ?></h3>
                    <p class="lead">Add this page (<b>Add Products Frontend</b>) inside Menus and start to use the plugin!</p>
                    <p class="lead"><a href="<?php echo esc_url(get_admin_url()); ?>nav-menus.php"><?php esc_html_e('Goto Menus', 'bytes_product_frontend'); ?></a></p>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
</div>