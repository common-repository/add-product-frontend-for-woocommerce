<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div id="product_attributes" class="panel wc-metaboxes-wrapper hidden">
    <div class="toolbar toolbar-top">
        <span class="expand-close">
            <a href="#" class="expand_all"><?php _e( 'Expand', 'bytes_product_frontend' ); ?></a> / <a href="#" class="close_all"><?php _e( 'Close', 'bytes_product_frontend' ); ?></a>
        </span>
        <select name="attribute_taxonomy" class="attribute_taxonomy">
            <option value=""><?php _e( 'Custom product attribute', 'bytes_product_frontend' ); ?></option>
            <?php
            global $wc_product_attributes;
            /* array of defined attribute taxonomies */
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            if ( ! empty( $attribute_taxonomies ) ) {
                foreach ( $attribute_taxonomies as $tax ) {
                    $attribute_taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
                    $label                   = $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name;
                    echo '<option value="' . esc_attr( $attribute_taxonomy_name ) . '">' . esc_html( $label ) . '</option>';
                }
            }
            ?>
        </select>
        <button type="button" class="button add_attribute"><?php _e( 'Add', 'bytes_product_frontend' ); ?></button>
    </div>
    <div class="product_attributes wc-metaboxes">
    </div>
    <div class="toolbar">
        <span class="expand-close">
            <a href="#" class="expand_all"><?php _e( 'Expand', 'bytes_product_frontend' ); ?></a> / <a href="#" class="close_all"><?php _e( 'Close', 'bytes_product_frontend' ); ?></a>
        </span>
        <button type="button" class="button save_attributes button-primary"><?php _e( 'Save attributes', 'bytes_product_frontend' ); ?></button>
    </div>
    <?php do_action( 'woocommerce_product_options_attributes' ); ?>
</div>