<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( current_user_can( 'upload_files' ) ) {
    wp_enqueue_media();
}
?>
<!-- Product image -->
<div class="bt-box-container bt-product-box-container">
    <div class="bt-header">
        <h2><?php _e( 'Product image', 'bytes_product_frontend' ); ?></h2>
    </div>
    <div class="bt-content bt-content-image" style="padding: 12px;">
        <input value="" name="bt_product_image" type="hidden"/>
        <img src="" class="bt-product-image bt-hide-section" width="213" height="200">
        <p class="bt-image-product-descr bt-hide-section"><i><?php _e( 'Click the image to edit or update', 'bytes_product_frontend' ); ?></i></p>
    <?php
        if ( current_user_can( 'upload_files' ) ) {
            echo '<a href="#" class="bt-upload-image-button">'. __( 'Set product image', 'bytes_product_frontend' ).'</a>';
            echo '<a href="#" class="bt-remove-image-button bt-hide-section">'. __( 'Remove product image', 'bytes_product_frontend' ).'</a>';
        }
    ?>
    </div>
</div>