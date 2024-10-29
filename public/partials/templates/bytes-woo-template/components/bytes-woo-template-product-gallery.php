<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( current_user_can( 'upload_files' ) ) {
    wp_enqueue_media();
}
?>
<!-- Product gallery -->
<div class="bt-box-container bt-product-box-container">
    <div class="bt-header">
        <h2><?php _e( 'Product gallery', 'bytes_product_frontend' ); ?></h2>
    </div>
    <div class="bt-content bt-content-gallery" style="padding: 12px;">
        <input id="bt-gallery-ids" value="" name="bt_product_gallery_ids" type="hidden" />

        <div id="product_images_container">
			<ul class="bt-gallery-imgs">
            </ul>
        </div>
        <?php
        if ( current_user_can( 'upload_files' ) ) {
            echo '<a href="#" class="bt-upload-gallery-button">'. __( 'Add product gallery images', 'bytes_product_frontend' ).'</a>';
        }
        ?>							
    </div>
</div>