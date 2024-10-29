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
        <input id="bt-gallery-ids" value="<?php echo esc_attr($product_gallery_images_ids); ?>" name="bt_product_gallery_ids" type="hidden" />

        <div id="product_images_container">
			<ul class="bt-gallery-imgs">
                <?php 
                if(!empty($product_object->get_gallery_image_ids())){
                    foreach($product_object->get_gallery_image_ids() as $attachment_id){
                        echo '<li class="bt-image-gallery" data-attachment_id="'.esc_attr($attachment_id).'" style="background-image: url(\''.esc_url(wp_get_attachment_url($attachment_id)).'\'); background-repeat: no-repeat; background-size: cover;"><a onclick="btRemoveGalleryImg(this, '.$attachment_id.');" class="bt-remove-img bt-hide-section"></a></li>';
                    }
                }
                ?>
            </ul>
        </div>
        <?php
        if ( current_user_can( 'upload_files' ) ) {
            echo '<a href="#" class="bt-upload-gallery-button">'. __( 'Add product gallery images', 'bytes_product_frontend' ).'</a>';
        }
        ?>							
    </div>
</div>