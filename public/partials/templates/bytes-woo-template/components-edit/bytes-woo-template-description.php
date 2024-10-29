<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="bt-box-container">
    <?php
        $content   = $product_object->get_description();
        $editor_id = 'bt_description_editor';
        wp_editor($content, $editor_id, array('media_buttons' => false));
    ?>
</div>