<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$already_existent_tags = get_terms( 'product_tag', array(
                            'hide_empty' => false,
                        ) );
?>
<!-- Product tags -->
<div class="bt-box-container bt-product-box-container">
    <div class="bt-header">
        <h2><?php _e( 'Product tags', 'bytes_product_frontend' ); ?></h2>
    </div>
    <div class="bt-content bt-content-tags" style="padding: 12px;">
        <input type="text" id="bt-input-values" list="bt-tags" class="bt-input" name="tags" placeholder="" style="width: auto;">
        <datalist id="bt-tags">
        <?php
            foreach ($already_existent_tags as $tag) {
                echo '<option value="'.esc_attr($tag->name).'">';
            }
        ?>
        </datalist>
        <button id="bt-add-tag" class="bt-button-default" type="button"><?php _e( 'Add', 'bytes_product_frontend' ); ?></button>
        <p><i><?php _e('Separate tags with commas', 'bytes_product_frontend'); ?></i></p>
        
        <ul class="bt-tagchecklist" role="list">

        </ul>
        <input type="hidden" id="bt-input-values-hidden" name="bt_tags">
    </div>
</div>