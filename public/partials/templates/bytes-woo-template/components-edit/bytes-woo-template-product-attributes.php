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
        <?php
        /* Product attributes - taxonomies and custom, ordered, with visibility and variation attributes set */
        $attributes = $product_object->get_attributes();
        $i          = -1;
        foreach ( $attributes as $attribute ) {
            $i++;
            $metabox_class = array();

            if ( $attribute->is_taxonomy() ) {
                $metabox_class[] = 'taxonomy';
                $metabox_class[] = $attribute->get_name();
            } ?>
            <div data-taxonomy="<?php echo esc_attr( $attribute->get_taxonomy() ); ?>" class="woocommerce_attribute wc-metabox postbox closed <?php echo esc_attr( implode( ' ', $metabox_class ) ); ?>" rel="<?php echo esc_attr( $attribute->get_position() ); ?>">
                <h3>
                    <a href="#" class="remove_row delete"><?php _e( 'Remove', 'bytes_product_frontend' ); ?></a>
                    <div class="handlediv" title="<?php _e( 'Click to toggle', 'bytes_product_frontend' ); ?>"></div>
                    <div class="tips sort" data-tip="<?php _e( 'Drag and drop to set admin attribute order', 'bytes_product_frontend' ); ?>"></div>            
                    <strong class="attribute_name"><?php echo wc_attribute_label( $attribute->get_name() ); ?></strong>
                </h3>
                <div class="woocommerce_attribute_data wc-metabox-content hidden">
                    <table cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="attribute_name">
                                    <label><?php _e( 'Name', 'bytes_product_frontend' ); ?>:</label>

                                    <?php if ( $attribute->is_taxonomy() ) : ?>
                                        <strong><?php echo wc_attribute_label( $attribute->get_name() ); ?></strong>
                                        <input type="hidden" name="attribute_names[<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $attribute->get_name() ); ?>" />
                                    <?php else : ?>
                                        <input type="text" class="attribute_name" name="attribute_names[<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $attribute->get_name() ); ?>" />
                                    <?php endif; ?>

                                    <input type="hidden" name="attribute_position[<?php echo esc_attr( $i ); ?>]" class="attribute_position" value="<?php echo esc_attr( $attribute->get_position() ); ?>" />
                                </td>
                                <td rowspan="3">
                                    <label><?php _e( 'Value(s)', 'bytes_product_frontend' ); ?>:</label>
                                    <?php
                                    if ( $attribute->is_taxonomy() && $attribute_taxonomy = $attribute->get_taxonomy_object() ) {
                                        $attribute_types = wc_get_attribute_types();
                                        if ( ! array_key_exists( $attribute_taxonomy->attribute_type, $attribute_types ) ) {
                                            $attribute_taxonomy->attribute_type = 'select';
                                        }
                                        if ( 'select' === $attribute_taxonomy->attribute_type ) {
                                            ?>
                                            <select multiple="multiple" data-placeholder="<?php _e( 'Select terms', 'bytes_product_frontend' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
                                                <?php
                                                $args      = array(
                                                    'orderby'    => ! empty( $attribute_taxonomy->attribute_orderby ) ? $attribute_taxonomy->attribute_orderby : 'name',
                                                    'hide_empty' => 0,
                                                );
                                                $all_terms = get_terms( $attribute->get_taxonomy(), apply_filters( 'woocommerce_product_attribute_terms', $args ) );
                                                if ( $all_terms ) {
                                                    foreach ( $all_terms as $term ) {
                                                        $options = $attribute->get_options();
                                                        $options = ! empty( $options ) ? $options : array();
                                                        echo '<option value="' . esc_attr( $term->term_id ) . '"' . wc_selected( $term->term_id, $options ) . '>' . esc_html( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <button class="button plus select_all_attributes bt-button-default"><?php _e( 'Select all', 'bytes_product_frontend' ); ?></button>
                                            <button class="button minus select_no_attributes bt-button-default"><?php _e( 'Select none', 'bytes_product_frontend' ); ?></button>
                                            <button class="button fr plus add_new_attribute bt-button-default"><?php _e( 'Add new', 'bytes_product_frontend' ); ?></button>
                                            <?php
                                        }
                                        do_action( 'woocommerce_product_option_terms', $attribute_taxonomy, $i, $attribute );
                                    } else {
                                        ?>
                                        <textarea name="attribute_values[<?php echo esc_attr( $i ); ?>]" cols="5" rows="5" placeholder="<?php printf( esc_attr__( 'Enter some text, or some attributes by "%s" separating values.', 'bytes_product_frontend' ), '' ); ?>"><?php echo esc_textarea( wc_implode_text_attributes( $attribute->get_options() ) ); ?></textarea>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label><input type="checkbox" class="checkbox" <?php checked( $attribute->get_visible(), true ); ?> name="attribute_visibility[<?php echo esc_attr( $i ); ?>]" value="1" /> <?php _e( 'Visible on the product page', 'bytes_product_frontend' ); ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="enable_variation show_if_variable">
                                        <label><input type="checkbox" class="checkbox" <?php checked( $attribute->get_variation(), true ); ?> name="attribute_variation[<?php echo esc_attr( $i ); ?>]" value="1" /> <?php _e( 'Used for variations', 'bytes_product_frontend' ); ?></label>
                                    </div>
                                </td>
                            </tr>
                            <?php do_action( 'woocommerce_after_product_attribute_settings', $attribute, $i ); ?>
                        </tbody>
                    </table>
                </div>
            </div>            
        <?php }
        ?>
    </div>
    <div class="toolbar">
        <span class="expand-close">
            <a href="#" class="expand_all"><?php _e( 'Expand', 'bytes_product_frontend' ); ?></a> / <a href="#" class="close_all"><?php _e( 'Close', 'bytes_product_frontend' ); ?></a>
        </span>
        <button type="button" class="button save_attributes button-primary"><?php _e( 'Save attributes', 'bytes_product_frontend' ); ?></button>
    </div>
    <?php do_action( 'woocommerce_product_options_attributes' ); ?>
</div>