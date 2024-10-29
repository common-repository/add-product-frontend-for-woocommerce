<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!-- Short description / excerpt -->
<div class="bt-box-container bt-product-box-container">
    <div class="bt-header">
        <h2> <?php _e( 'Short product description', 'bytes_product_frontend' ); ?></h2>
    </div>
    <div class="bt-content" style="padding: 12px;">
        <?php
            $content   = $product_object->get_short_description();
            $editor_id = 'bt_excerpt_editor';
            wp_editor($content, $editor_id, array('textarea_rows' => 7, 'media_buttons' => false));
        ?>
    </div>
</div>