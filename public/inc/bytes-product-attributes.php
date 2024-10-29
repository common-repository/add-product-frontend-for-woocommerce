<?php
/* Add attributes form in frontend product form */
add_action( 'wp_ajax_bytes_add_product_attributes', 'apffw_add_product_attributes' );
add_action( 'wp_ajax_nopriv_bytes_add_product_attributes', 'apffw_add_product_attributes' );
/* *** Add new attribute *** */
add_action( 'wp_ajax_bytes_product_add_new_attribute', 'apffw_product_add_new_attribute' );
add_action( 'wp_ajax_nopriv_bytes_product_add_new_attribute', 'apffw_product_add_new_attribute' );
/* *** Save attribute *** */
add_action( 'wp_ajax_bytes_product_save_attributes', 'apffw_product_save_attributes' );
add_action( 'wp_ajax_nopriv_bytes_product_save_attributes', 'apffw_product_save_attributes' );

if(!function_exists('apffw_add_product_attributes')){
    function apffw_add_product_attributes(){
        ob_start();
        $i = absint( $_POST['i'] );
        $metabox_class = array();
        $attribute     = new WC_Product_Attribute();
        $attribute->set_id( wc_attribute_taxonomy_id_by_name( sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) ) ) );
        $attribute->set_name( sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) ) );
        $attribute->set_visible( apply_filters( 'woocommerce_attribute_default_visibility', 1 ) );
        $attribute->set_variation( apply_filters( 'woocommerce_attribute_default_is_variation', 0 ) );
        if ( $attribute->is_taxonomy() ) {
            $metabox_class[] = 'taxonomy';
            $metabox_class[] = $attribute->get_name();
        } ?>
        <div data-taxonomy="<?php echo esc_attr( $attribute->get_taxonomy() ); ?>" class="woocommerce_attribute wc-metabox postbox closed <?php echo esc_attr( implode( ' ', $metabox_class ) ); ?>" rel="<?php echo esc_attr( $attribute->get_position() ); ?>">
            <h3>
                <a href="" class="remove_row delete"><?php esc_html_e( 'Remove', 'bytes_product_frontend' ); ?></a>
                <div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'bytes_product_frontend' ); ?>"></div>
                <div class="tips sort" data-tip="<?php esc_attr_e( 'Drag and drop to set admin attribute order', 'bytes_product_frontend' ); ?>"></div>            
                <strong class="attribute_name"><?php echo wc_attribute_label( $attribute->get_name() ); ?></strong>
            </h3>
            <div class="woocommerce_attribute_data wc-metabox-content hidden">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="attribute_name">
                                <label><?php esc_html_e( 'Name', 'bytes_product_frontend' ); ?>:</label>
                                <?php if ( $attribute->is_taxonomy() ) : ?>
                                    <strong><?php echo wc_attribute_label( $attribute->get_name() ); ?></strong>
                                    <input type="hidden" name="attribute_names[<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $attribute->get_name() ); ?>" />
                                <?php else : ?>
                                    <input type="text" class="attribute_name" name="attribute_names[<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $attribute->get_name() ); ?>" />
                                <?php endif; ?>
                                <input type="hidden" name="attribute_position[<?php echo esc_attr( $i ); ?>]" class="attribute_position" value="<?php echo esc_attr( $attribute->get_position() ); ?>" />
                            </td>
                            <td rowspan="3">
                                <label><?php esc_html_e( 'Value(s)', 'bytes_product_frontend' ); ?>:</label>
                                <?php
                                if ( $attribute->is_taxonomy() && $attribute_taxonomy = $attribute->get_taxonomy_object() ) {
                                    $attribute_types = wc_get_attribute_types();
                                    if ( ! array_key_exists( $attribute_taxonomy->attribute_type, $attribute_types ) ) {
                                        $attribute_taxonomy->attribute_type = 'select';
                                    }
                                    if ( 'select' === $attribute_taxonomy->attribute_type ) {
                                        ?>
                                        <select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'bytes_product_frontend' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
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
                                        <button class="button plus select_all_attributes bt-button-default"><?php esc_html_e( 'Select all', 'bytes_product_frontend' ); ?></button>
                                        <button class="button minus select_no_attributes bt-button-default"><?php esc_html_e( 'Select none', 'bytes_product_frontend' ); ?></button>
                                        <button class="button fr plus add_new_attribute bt-button-default"><?php esc_html_e( 'Add new', 'bytes_product_frontend' ); ?></button>
                                        <?php
                                    }
                                    do_action( 'woocommerce_product_option_terms', $attribute_taxonomy, $i, $attribute );
                                } else {
                                    ?>
                                    <textarea name="attribute_values[<?php echo esc_attr( $i ); ?>]" cols="5" rows="5" placeholder="<?php printf(
                                        /* translators: %s: APFFW_SIGN */
                                        esc_attr__( 'Enter some text, or some attributes by "%s" separating values.', 'bytes_product_frontend' ), APFFW_SIGN ); ?>"><?php echo esc_textarea( wc_implode_text_attributes( $attribute->get_options() ) ); ?></textarea>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label><input type="checkbox" class="checkbox" <?php checked( $attribute->get_visible(), true ); ?> name="attribute_visibility[<?php echo esc_attr( $i ); ?>]" value="1" /> <?php esc_html_e( 'Visible on the product page', 'bytes_product_frontend' ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="enable_variation show_if_variable">
                                    <label><input type="checkbox" class="checkbox" <?php checked( $attribute->get_variation(), true ); ?> name="attribute_variation[<?php echo esc_attr( $i ); ?>]" value="1" /> <?php esc_html_e( 'Used for variations', 'bytes_product_frontend' ); ?></label>
                                </div>
                            </td>
                        </tr>
                        <?php do_action( 'woocommerce_after_product_attribute_settings', $attribute, $i ); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
        wp_die();
    }
}

/* *** add a new attribute via ajax function *** */
if(!function_exists('apffw_product_add_new_attribute')){
    function apffw_product_add_new_attribute(){
        if(isset( $_POST['taxonomy'], $_POST['term'])){
            $taxonomy = esc_attr( wp_unslash( $_POST['taxonomy'] ) ); // phpcs:ignore
            $term = wc_clean(wp_unslash($_POST['term']));
            if(taxonomy_exists($taxonomy)){
                $result = wp_insert_term($term, $taxonomy);
                if(is_wp_error($result)){
                    wp_send_json(
                        array(
                            'error' => $result->get_error_message(),
                        )
                    );
                } 
                else{
                    $term = get_term_by('id', $result['term_id'], $taxonomy);
                    wp_send_json(
                        array(
                            'term_id' => $term->term_id,
                            'name'    => $term->name,
                            'slug'    => $term->slug,
                        )
                    );
                }
            }
        }
        wp_die(-1);
    }
}

/* *** save attributes via ajax *** */
if(!function_exists('apffw_product_save_attributes')){
    function apffw_product_save_attributes(){
        if(!isset($_POST['data'], $_POST['post_id'])){
            wp_die(-1);
        }
        $response = array();
        try{
            parse_str( wp_unslash( $_POST['data'] ), $data ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            
            $attributes   = WC_Meta_Box_Product_Data::prepare_attributes( $data );
            $product_id   = absint( wp_unslash( $_POST['post_id'] ) );
            $product_type = ! empty( $_POST['product_type'] ) ? wc_clean( wp_unslash( $_POST['product_type'] ) ) : 'simple';
            $classname    = WC_Product_Factory::get_product_classname( $product_id, $product_type );
            $product      = new $classname( $product_id );
            $product->set_attributes( $attributes );
            ob_start();
            $attributes = $product->get_attributes();
            $i          = -1;
            if ( ! empty( $data['attribute_names'] ) ) {
                foreach ( $data['attribute_names'] as $attribute_name ) {
                    $attribute = isset( $attributes[ sanitize_title( $attribute_name ) ] ) ? $attributes[ sanitize_title( $attribute_name ) ] : false;
                    if ( ! $attribute ) {
                        continue;
                    }
                    $i++;
                    $metabox_class = array();
                    if ( $attribute->is_taxonomy() ) {
                        $metabox_class[] = 'taxonomy';
                        $metabox_class[] = $attribute->get_name();
                    }
                    ?>
                    <div data-taxonomy="<?php echo esc_attr( $attribute->get_taxonomy() ); ?>" class="woocommerce_attribute wc-metabox postbox closed <?php echo esc_attr( implode( ' ', $metabox_class ) ); ?>" rel="<?php echo esc_attr( $attribute->get_position() ); ?>">
                        <h3>
                            <a href="#" class="remove_row delete"><?php esc_html_e( 'Remove', 'bytes_product_frontend' ); ?></a>
                            <div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'bytes_product_frontend' ); ?>"></div>
                            <div class="tips sort" data-tip="<?php esc_attr_e( 'Drag and drop to set admin attribute order', 'bytes_product_frontend' ); ?>"></div>
                            <strong class="attribute_name"><?php echo wc_attribute_label( $attribute->get_name() ); ?></strong>
                        </h3>
                        <div class="woocommerce_attribute_data wc-metabox-content hidden">
                            <table cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td class="attribute_name">
                                            <label><?php esc_html_e( 'Name', 'bytes_product_frontend' ); ?>:</label>
                                            <?php if ( $attribute->is_taxonomy() ) : ?>
                                                <strong><?php echo wc_attribute_label( $attribute->get_name() ); ?></strong>
                                                <input type="hidden" name="attribute_names[<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $attribute->get_name() ); ?>" />
                                            <?php else : ?>
                                                <input type="text" class="attribute_name" name="attribute_names[<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $attribute->get_name() ); ?>" />
                                            <?php endif; ?>
                                            <input type="hidden" name="attribute_position[<?php echo esc_attr( $i ); ?>]" class="attribute_position" value="<?php echo esc_attr( $attribute->get_position() ); ?>" />
                                        </td>
                                        <td rowspan="3">
                                            <label><?php esc_html_e( 'Value(s)', 'bytes_product_frontend' ); ?>:</label>
                                            <?php
                                            if ( $attribute->is_taxonomy() && $attribute_taxonomy = $attribute->get_taxonomy_object() ) {
                                                $attribute_types = wc_get_attribute_types();
                                                if ( ! array_key_exists( $attribute_taxonomy->attribute_type, $attribute_types ) ) {
                                                    $attribute_taxonomy->attribute_type = 'select';
                                                }
                                                if ( 'select' === $attribute_taxonomy->attribute_type ) {
                                                    ?>
                                                    <select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'bytes_product_frontend' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
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
                                                    <button class="button plus select_all_attributes bt-button-default"><?php esc_html_e( 'Select all', 'bytes_product_frontend' ); ?></button>
                                                    <button class="button minus select_no_attributes bt-button-default"><?php esc_html_e( 'Select none', 'bytes_product_frontend' ); ?></button>
                                                    <button class="button fr plus add_new_attribute bt-button-default"><?php esc_html_e( 'Add new', 'bytes_product_frontend' ); ?></button>
                                                    <?php
                                                }
                                                do_action( 'woocommerce_product_option_terms', $attribute_taxonomy, $i, $attribute );
                                            } else {
                                                ?>
                                                <textarea name="attribute_values[<?php echo esc_attr( $i ); ?>]" cols="5" rows="5" placeholder="<?php printf(
                                                    /* translators: %s: APFFW_SIGN */
                                                    esc_attr__( 'Enter some text, or some attributes by "%s" separating values.', 'bytes_product_frontend' ), APFFW_SIGN ); ?>"><?php echo esc_textarea( wc_implode_text_attributes( $attribute->get_options() ) ); ?></textarea>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label><input type="checkbox" class="checkbox" <?php checked( $attribute->get_visible(), true ); ?> name="attribute_visibility[<?php echo esc_attr( $i ); ?>]" value="1" /> <?php esc_html_e( 'Visible on the product page', 'bytes_product_frontend' ); ?></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="enable_variation show_if_variable">
                                                <label><input type="checkbox" class="checkbox" <?php checked( $attribute->get_variation(), true ); ?> name="attribute_variation[<?php echo esc_attr( $i ); ?>]" value="1" /> <?php esc_html_e( 'Used for variations', 'bytes_product_frontend' ); ?></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php do_action( 'woocommerce_after_product_attribute_settings', $attribute, $i ); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                }
            }
            $response['html'] = ob_get_clean();
        } catch ( Exception $e ) {
            wp_send_json_error( array( 'error' => $e->getMessage() ) );
        }
        /* wp_send_json_success must be outside the try block not to break phpunit tests */
        wp_send_json_success( $response );
    }
}
?>