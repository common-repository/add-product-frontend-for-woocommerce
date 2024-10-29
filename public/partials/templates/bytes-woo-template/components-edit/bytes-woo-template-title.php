<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="bt-box-container" style="box-shadow: unset;">
    <input type="text" class="bt-input" name="name" id="bt-sale-price" placeholder="<?php _e('Product name', 'bytes_product_frontend'); ?>" value="<?php echo esc_attr($product_object->get_title()); ?>" required>
</div>