<?php
/* *** save product *** */
add_action('wp_ajax_bytes_save_product', 'apffw_save_product');
add_action('wp_ajax_nopriv_bytes_save_product', 'apffw_save_product');
/* *** edit product *** */
add_action('wp_ajax_bytes_edit_product', 'apffw_edit_product');
add_action('wp_ajax_nopriv_bytes_edit_product', 'apffw_edit_product');

if(!function_exists('apffw_prepare_downloads')){
    function apffw_prepare_downloads($file_names, $file_urls, $file_hashes){
        $downloads = array();
        if ( ! empty( $file_urls ) ) {
            $file_url_size = count( $file_urls );
            for ( $i = 0; $i < $file_url_size; $i ++ ) {
                if ( ! empty( $file_urls[ $i ] ) ) {
                    $downloads[] = array(
                        'name'        => wc_clean( $file_names[ $i ] ),
                        'url'        => wp_unslash( trim( $file_urls[ $i ] ) ),
                        'id' => wc_clean( $file_hashes[ $i ] ),
                    );
                }
            }
        }
        return $downloads;
    }
}

if(!function_exists('apffw_save_downloadable_files')){
    function apffw_save_downloadable_files($product, $downloads, $deprecated = 0){
        $files = array();
        foreach ( $downloads as $key => $file ) {
            if ( isset( $file['url'] ) ) {
                $file['file'] = $file['url'];
            }
            if ( empty( $file['file'] ) ) {
                continue;
            }
            $download = new WC_Product_Download();
            $download->set_id( ! empty( $file['id'] ) ? $file['id'] : wp_generate_uuid4() );
            $download->set_name( $file['name'] ? $file['name'] : wc_get_filename_from_url( $file['file'] ) );
            $download->set_file( apply_filters( 'woocommerce_file_download_path', $file['file'], $product, $key ) );
            $files[]  = $download;
        }
        return $files;
    }
}

/* *** prepare attributes for save *** */
if(!function_exists('apffw_prepare_attributes')){
    function apffw_prepare_attributes( $data = false ) {
        $attributes = array();
        if ( isset( $data['attribute_names'], $data['attribute_values'] ) ) {
            $attribute_names         = $data['attribute_names'];
            $attribute_values        = $data['attribute_values'];
            $attribute_visibility    = isset( $data['attribute_visibility'] ) ? $data['attribute_visibility'] : array();
            $attribute_variation     = isset( $data['attribute_variation'] ) ? $data['attribute_variation'] : array();
            $attribute_position      = $data['attribute_position'];
            $attribute_names_max_key = max( array_keys( $attribute_names ) );
            for ( $i = 0; $i <= $attribute_names_max_key; $i++ ) {
                if ( empty( $attribute_names[ $i ] ) || ! isset( $attribute_values[ $i ] ) ) {
                    continue;
                }
                $attribute_id   = 0;
                $attribute_name = wc_clean( esc_html( $attribute_names[ $i ] ) );
                if ( 'pa_' === substr( $attribute_name, 0, 3 ) ) {
                    $attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
                }
                $options = isset( $attribute_values[ $i ] ) ? $attribute_values[ $i ] : '';
                if ( is_array( $options ) ) {
                    /* Term ids sent as array */
                    $options = wp_parse_id_list( $options );
                } else {
                    /* Terms or text sent in textarea */
                    $options = 0 < $attribute_id ? wc_sanitize_textarea( esc_html( wc_sanitize_term_text_based( $options ) ) ) : wc_sanitize_textarea( esc_html( $options ) );
                    $options = wc_get_text_attributes( $options );
                }
                if ( empty( $options ) ) {
                    continue;
                }
                $attribute = new WC_Product_Attribute();
                $attribute->set_id( $attribute_id );
                $attribute->set_name( $attribute_name );
                $attribute->set_options( $options );
                $attribute->set_position( $attribute_position[ $i ] );
                $attribute->set_visible( isset( $attribute_visibility[ $i ] ) );
                $attribute->set_variation( isset( $attribute_variation[ $i ] ) );
                /* phpcs:disable WooCommerce.Commenting.CommentHooks.MissingHookComment */
                $attributes[] = apply_filters( 'woocommerce_admin_meta_boxes_prepare_attribute', $attribute, $data, $i );
                /* phpcs: enable */
            }
        }
        return $attributes;
    }
}

if(!function_exists('apffw_save_product')){
    function apffw_save_product() {
        check_ajax_referer('bytes-security', 'security');
        $is_virtual = isset( $_POST['is_virtual'] ) ? wc_clean( wp_unslash( $_POST['is_virtual'] ) ) : null;
        $is_on_sale = isset( $_POST['is_on_sale'] ) ? wc_clean( wp_unslash( $_POST['is_on_sale'] ) ) : null;
        if(!empty($_POST['bt-product-type']) && $_POST['bt-product-type'] == 'simple'){
            $product = new WC_Product_Simple();
        }
        elseif(!empty($_POST['bt-product-type']) && $_POST['bt-product-type'] == 'grouped'){
            $product = new WC_Product_Grouped();
        }
        elseif(!empty($_POST['bt-product-type']) && $_POST['bt-product-type'] == 'external'){
            $product = new WC_Product_External();  
        }
        elseif(!empty($_POST['bt-product-type']) && $_POST['bt-product-type'] == 'variable'){
            $product = new WC_Product_Variable();
        }
        if(trim(wc_clean(wp_unslash($_POST['name']))) == '') {
            $error = new WP_Error( '001', 'Product name is required and cannot be blank.' );       
            wp_send_json_error( $error );
            die();
        }
        $product->set_props( array (
            'type'               => isset( $_POST['bt-product-type'] ) ? wc_clean(wp_unslash($_POST['bt-product-type'])) : null, 
            'name'               => isset( $_POST['name'] ) ? wc_clean(wp_unslash($_POST['name'])) : null,
            'featured'           => isset( $_POST['featured'] ) ? wc_clean(wp_unslash($_POST['featured'])) : null,
            'catalog_visibility' => isset( $_POST['catalog_visibility'] ) ? wc_clean( wp_unslash( $_POST['catalog_visibility'] ) ) : null,
            'description'        => isset( $_POST['bt_description_editor'] ) ? wp_kses_post( wp_unslash( $_POST['bt_description_editor'] ) ) : null,
            'short_description'  => isset( $_POST['bt_excerpt_editor'] ) ? wp_kses_post( wp_unslash( $_POST['bt_excerpt_editor'] ) ) : null,
            'sku'                => isset( $_POST['sku'] ) ? wc_clean( wp_unslash( $_POST['sku'] ) ) : null,
            'regular_price'      => isset( $_POST['regular_price'] ) ? wc_clean( wp_unslash( $_POST['regular_price'] ) ) : null,
            'sale_price'         => isset( $_POST['sale_price'] ) ? wc_clean( wp_unslash( $_POST['sale_price'] ) ) : null,
            'date_on_sale_from'  => '',
            'date_on_sale_to'    => '',
            'total_sales'        => 0,
            'tax_status'         => isset( $_POST['tax_status'] ) ? wc_clean( wp_unslash( $_POST['tax_status'] ) ) : null,
            'tax_class'          => isset( $_POST['tax_class'] ) ? sanitize_title( wp_unslash( $_POST['tax_class'] ) ) : null,
            'manage_stock'       => (!empty($_POST['manage_stock']) && $_POST['manage_stock'] == 'on') ? true : false,
            'stock_quantity'     => isset($_POST['bt_stock']) ? wc_clean( wp_unslash( $_POST['bt_stock'] ) ) : null,
            'stock_status'       => isset( $_POST['stock_status'] ) ? wc_clean( wp_unslash( $_POST['stock_status'] ) ) : null,
            'backorders'         => isset( $_POST['bt_backorders'] ) ? wc_clean( wp_unslash( $_POST['bt_backorders'] ) ) : null,
            'low_stock_amount'   => isset( $_POST['bt_low_stock_amount'] ) ? wc_clean(wp_unslash($_POST['bt_low_stock_amount'])) : null,
            'sold_individually'  => (!empty($_POST['sold_individually']) && $_POST['sold_individually'] == 'on') ? true : false,
            'weight'             => $is_virtual ? '' : (isset($_POST['weight']) ? wc_clean(wp_unslash($_POST['weight'])) : null),
            'length'             => $is_virtual ? '' : (isset($_POST['length']) ? wc_clean(wp_unslash($_POST['length'])) : null),
            'width'              => $is_virtual ? '' : (isset($_POST['width']) ? wc_clean(wp_unslash($_POST['width'])) : null),
            'height'             => $is_virtual ? '' : (isset($_POST['height']) ? wc_clean(wp_unslash($_POST['height'])) : null),
            'upsell_ids'         => isset( $_POST['upsell_ids'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['upsell_ids'] ) ) : array(),
            'cross_sell_ids'     => isset( $_POST['crosssell_ids'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['crosssell_ids'] ) ) : array(),
            'parent_id'          => isset ( $_POST['parent_id'] ) ? absint( $_POST['parent_id'] ) : "",
            'reviews_allowed'    => (!empty($_POST['reviews_allowed']) && $_POST['reviews_allowed'] == 'on') ? true : false,
            'purchase_note'      => isset( $_POST['purchase_note'] ) ? wp_kses_post( wp_unslash( $_POST['purchase_note'] ) ) : '',
            'menu_order'         => !empty( $_POST['menu_order'] ) ? wc_clean( wp_unslash( $_POST['menu_order'] ) ) : false,
            'virtual'            => (!empty($_POST['bt-virtual']) && $_POST['bt-virtual'] == 'on') ? true : false,
            'downloadable'       => (!empty($_POST['bt-downloadable']) && $_POST['bt-downloadable'] == 'on') ? true : false,
            // Those are sanitized inside apffw prepare_downloads.
            'downloads'=>apffw_save_downloadable_files($product, apffw_prepare_downloads(
                isset( $_POST['_wc_file_names'] ) ? wp_unslash( $_POST['_wc_file_names'] ) : array(), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                isset( $_POST['_wc_file_urls'] ) ? wp_unslash( $_POST['_wc_file_urls'] ) : array(), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                isset( $_POST['_wc_file_hashes'] ) ? wp_unslash( $_POST['_wc_file_hashes'] ) : array() // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            )),
            'download_limit' => isset( $_POST['_download_limit'] ) && '' !== $_POST['_download_limit'] ? absint( wp_unslash( $_POST['_download_limit'] ) ) : '',
            'download_expiry' => isset( $_POST['_download_expiry'] ) && '' !== $_POST['_download_expiry'] ? absint( wp_unslash( $_POST['_download_expiry'] ) ) : '',
            'category_ids'       => isset( $_POST['category_ids'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['category_ids'] ) ) : array(),
            'tag_ids'            => isset( $_POST['tag_ids'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['tag_ids'] ) ) : array(),
            'shipping_class_id'  => isset( $_POST['shipping_class'] ) ? absint( wp_unslash( $_POST['shipping_class'] ) ) : null,
            'image_id'           => isset($_POST['bt_product_image']) ? wc_clean(wp_unslash($_POST['bt_product_image'])) : null,
            'gallery_image_ids'  => isset($_POST['bt_product_gallery_ids']) ? wc_clean(wp_unslash($_POST['bt_product_gallery_ids'])) : null,
            'product_url'        => isset( $_POST['_product_url'] ) ? esc_url_raw( wp_unslash( $_POST['_product_url'] ) ) : '',
            'button_text'        => isset( $_POST['_button_text'] ) ? wc_clean( wp_unslash( $_POST['_button_text'] ) ) : '',
            'grouped_products' => (!empty($_POST['bt-product-type']) && $_POST['bt-product-type'] == 'grouped') ? ($_POST['grouped_products'] ? $product->set_children($_POST['grouped_products']) : "") : "",
            'attributes' => apffw_prepare_attributes($_POST),
        ));
        /* Categories */
        if(isset($_POST['bt_product_cat'])){
            $bt_product_cat = isset($_POST['bt_product_cat']) ? wc_clean(wp_unslash($_POST['bt_product_cat'])) : null;
            $product->set_category_ids($bt_product_cat);
        }
        /* Tags */
        if(isset($_POST['bt_tags'])) {
            $bt_tags = isset($_POST['bt_tags']) ? wc_clean(wp_unslash($_POST['bt_tags'])) : null;
            $exploded_tags = explode(",", $bt_tags);
            $all_tags = array();
            foreach ($exploded_tags as $tag) {
                $res = wp_insert_term(
                    $tag,
                    'product_tag'
                );
                if ( is_wp_error( $res ) ) {
                    if($res->errors['term_exists']) {
                        array_push($all_tags, $res->error_data['term_exists']);
                    }
                }
                else {
                    array_push($all_tags, $res['term_id']);
                }
            }
            if(!empty($all_tags)) {
                $product->set_tag_ids($all_tags);
            }
        }
        /* Get the user selected default status and update it to product */
        $user_selected_prod_status = get_option('frontend_product_status');
        $product->set_status($user_selected_prod_status);
        $pid = $product->save();
        update_post_meta($pid, 'product_status', 1); // Update product status
        if(is_wp_error($pid)){
            $response_array['status'] = 'Not added';
            $response_array['description'] = 'Something went wrong during the saving of this product.';    
            wp_send_json_error( $response_array );
            die();
        }
        $response_array['status'] = 'Added';
        $response_array['prod_name'] = $product->get_title();
        $response_array['prod_id'] = $pid;
        $response_array['permalink'] = get_permalink($product->get_id());
        /* Send mail on success */
        $current_user = wp_get_current_user();
        $current_user_email = sanitize_email($current_user->user_email);
        $to = sanitize_email(get_bloginfo('admin_email'));
        if($current_user_email !== $to){
            $site_title = get_bloginfo('name');
            $subject = "[{$site_title}]: New product #{$pid}";
            $table_header = "New product #{$pid}";
            $product_title = get_the_title($pid);
            $product_price = !empty(get_post_meta($pid, '_regular_price', true)) ? ('<span>'.get_woocommerce_currency_symbol().'</span>'.get_post_meta($pid, '_regular_price', true)) : 'N/A';
            $product_edit_link = '<a href="'.admin_url().'post.php?post='.$pid.'&action=edit" style="font-weight:normal;text-decoration:underline;color:#7f54b3" target="_blank">Product #'.$pid.'</a>';
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $product_message = 'You’ve received the following product from <a href="mailto:'.$current_user_email.'">'.$current_user_email.'</a>:';
            require_once plugin_dir_path( dirname( __FILE__ ) ).'partials/emails/admin-new-product.php';
            wp_mail($to, $subject, $body, $headers);
        }
        /* Send JSON response */
        wp_send_json_success($response_array);
    	die();
    }
}

if(!function_exists('apffw_edit_product')){
    function apffw_edit_product(){
        check_ajax_referer('bytes-security', 'security');
        $is_virtual = isset( $_POST['is_virtual'] ) ? wc_clean( wp_unslash( $_POST['is_virtual'] ) ) : null;
        $is_on_sale = isset( $_POST['is_on_sale'] ) ? wc_clean( wp_unslash( $_POST['is_on_sale'] ) ) : null;
        $product_id = absint( $_POST['product_id'] ); // get product id
        if(!empty($_POST['bt-product-type']) && $_POST['bt-product-type'] == 'simple'){
            $product = new WC_Product_Simple($product_id);
        }
        elseif(!empty($_POST['bt-product-type']) && $_POST['bt-product-type'] == 'grouped'){
            $product = new WC_Product_Grouped($product_id);
        }
        elseif(!empty($_POST['bt-product-type']) && $_POST['bt-product-type'] == 'external'){
            $product = new WC_Product_External($product_id);  
        }
        elseif(!empty($_POST['bt-product-type']) && $_POST['bt-product-type'] == 'variable'){
            $product = new WC_Product_Variable($product_id);
        }
        if(trim(wc_clean(wp_unslash($_POST['name']))) == ''){
            $error = new WP_Error( '001', 'Product name is required and cannot be blank.' );       
            wp_send_json_error( $error );
            die();
        }
        $product->set_props( array (
            'type'               => isset( $_POST['bt-product-type'] ) ? wc_clean(wp_unslash($_POST['bt-product-type'])) : null,
            'name'               => isset( $_POST['name'] ) ? wc_clean(wp_unslash($_POST['name'])) : null,
            'featured'           => isset( $_POST['featured'] ) ? wc_clean(wp_unslash($_POST['featured'])) : null,
            'catalog_visibility' => isset( $_POST['catalog_visibility'] ) ? wc_clean( wp_unslash( $_POST['catalog_visibility'] ) ) : null,
            'description'        => isset( $_POST['bt_description_editor'] ) ? wp_kses_post( wp_unslash( $_POST['bt_description_editor'] ) ) : null,
            'short_description'  => isset( $_POST['bt_excerpt_editor'] ) ? wp_kses_post( wp_unslash( $_POST['bt_excerpt_editor'] ) ) : null,
            'sku'                => isset( $_POST['sku'] ) ? wc_clean( wp_unslash( $_POST['sku'] ) ) : null,
            'regular_price'      => isset( $_POST['regular_price'] ) ? wc_clean( wp_unslash( $_POST['regular_price'] ) ) : null,
            'sale_price'         => isset( $_POST['sale_price'] ) ? wc_clean( wp_unslash( $_POST['sale_price'] ) ) : null,
            'date_on_sale_from'  => '',
            'date_on_sale_to'    => '',
            'total_sales'        => 0,
            'tax_status'         => isset( $_POST['tax_status'] ) ? wc_clean( wp_unslash( $_POST['tax_status'] ) ) : null,
            'tax_class'          => isset( $_POST['tax_class'] ) ? sanitize_title( wp_unslash( $_POST['tax_class'] ) ) : null,
            'manage_stock'       => (!empty($_POST['manage_stock']) && $_POST['manage_stock'] == 'on') ? true : false,
            'stock_quantity'     => isset($_POST['bt_stock']) ? wc_clean( wp_unslash( $_POST['bt_stock'] ) ) : null,
            'stock_status'       => isset( $_POST['stock_status'] ) ? wc_clean( wp_unslash( $_POST['stock_status'] ) ) : null,
            'backorders'         => isset( $_POST['bt_backorders'] ) ? wc_clean( wp_unslash( $_POST['bt_backorders'] ) ) : null,
            'low_stock_amount'   => isset( $_POST['bt_low_stock_amount'] ) ? wc_clean(wp_unslash($_POST['bt_low_stock_amount'])) : null,
            'sold_individually'  => (!empty($_POST['sold_individually']) && $_POST['sold_individually'] == 'on') ? true : false,
            'weight'             => $is_virtual ? '' : (isset($_POST['weight']) ? wc_clean(wp_unslash($_POST['weight'])) : null),
            'length'             => $is_virtual ? '' : (isset($_POST['length']) ? wc_clean(wp_unslash($_POST['length'])) : null),
            'width'              => $is_virtual ? '' : (isset($_POST['width']) ? wc_clean(wp_unslash($_POST['width'])) : null),
            'height'             => $is_virtual ? '' : (isset($_POST['height']) ? wc_clean(wp_unslash($_POST['height'])) : null),
            'upsell_ids'         => isset( $_POST['upsell_ids'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['upsell_ids'] ) ) : array(),
            'cross_sell_ids'     => isset( $_POST['crosssell_ids'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['crosssell_ids'] ) ) : array(),
            'parent_id'          => isset ( $_POST['parent_id'] ) ? absint( $_POST['parent_id'] ) : "",
            'reviews_allowed'    => (!empty($_POST['reviews_allowed']) && $_POST['reviews_allowed'] == 'on') ? true : false,
            'purchase_note'      => isset( $_POST['purchase_note'] ) ? wp_kses_post( wp_unslash( $_POST['purchase_note'] ) ) : '',
            'menu_order'         => !empty( $_POST['menu_order'] ) ? wc_clean( wp_unslash( $_POST['menu_order'] ) ) : false,
            'virtual'            => (!empty($_POST['bt-virtual']) && $_POST['bt-virtual']== 'on') ? true : false,
            'downloadable'       => (!empty($_POST['bt-downloadable']) && $_POST['bt-downloadable'] == 'on') ? true : false,
            // Those are sanitized inside apffw prepare_downloads.
            'downloads'=>apffw_save_downloadable_files($product, apffw_prepare_downloads(
                isset( $_POST['_wc_file_names'] ) ? wp_unslash( $_POST['_wc_file_names'] ) : array(), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                isset( $_POST['_wc_file_urls'] ) ? wp_unslash( $_POST['_wc_file_urls'] ) : array(), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                isset( $_POST['_wc_file_hashes'] ) ? wp_unslash( $_POST['_wc_file_hashes'] ) : array() // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            )),
            'download_limit' => isset( $_POST['_download_limit'] ) && '' !== $_POST['_download_limit'] ? absint( wp_unslash( $_POST['_download_limit'] ) ) : '',
            'download_expiry' => isset( $_POST['_download_expiry'] ) && '' !== $_POST['_download_expiry'] ? absint( wp_unslash( $_POST['_download_expiry'] ) ) : '',
            'category_ids'       => isset( $_POST['category_ids'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['category_ids'] ) ) : array(),
            'tag_ids'            => isset( $_POST['tag_ids'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['tag_ids'] ) ) : array(),
            'shipping_class_id'  => isset( $_POST['shipping_class'] ) ? absint( wp_unslash( $_POST['shipping_class'] ) ) : null,
            'image_id'           => isset($_POST['bt_product_image']) ? wc_clean(wp_unslash($_POST['bt_product_image'])) : null,
            'gallery_image_ids'  => isset($_POST['bt_product_gallery_ids']) ? wc_clean(wp_unslash($_POST['bt_product_gallery_ids'])) : null,
            'product_url'        => isset( $_POST['_product_url'] ) ? esc_url_raw( wp_unslash( $_POST['_product_url'] ) ) : '',
            'button_text'        => isset( $_POST['_button_text'] ) ? wc_clean( wp_unslash( $_POST['_button_text'] ) ) : '',
            'grouped_products' => (!empty($_POST['bt-product-type']) && $_POST['bt-product-type'] == 'grouped') ? ($_POST['grouped_products'] ? $product->set_children($_POST['grouped_products']) : "") : "",
            'attributes' => apffw_prepare_attributes($_POST),
        ));
        /* Categories */
        if(isset($_POST['bt_product_cat'])){
            $bt_product_cat = isset($_POST['bt_product_cat']) ? wc_clean(wp_unslash($_POST['bt_product_cat'])) : null;
            $product->set_category_ids($bt_product_cat);
        }
        /* Tags */
        if(isset($_POST['bt_tags'])) {
            $bt_tags = isset($_POST['bt_tags']) ? wc_clean(wp_unslash($_POST['bt_tags'])) : null;
            $exploded_tags = explode(",", $bt_tags);
            $all_tags = array();
            foreach ($exploded_tags as $tag) {
                $res = wp_insert_term(
                    $tag,
                    'product_tag'
                );
                if ( is_wp_error( $res ) ) {
                    if($res->errors['term_exists']) {
                        array_push($all_tags, $res->error_data['term_exists']);
                    }
                }
                else {
                    array_push($all_tags, $res['term_id']);
                }
            }
            if(!empty($all_tags)) {
                $product->set_tag_ids($all_tags);
            }
        }
        /* Get the user selected default status and update it to product */
        $user_selected_prod_status = $product->get_status();
        $product->set_status( $user_selected_prod_status );
        $pid = $product->save();
        if(is_wp_error($pid)){
            $response_array['status'] = 'Not updated';
            $response_array['description'] = 'Something went wrong during the saving of this product.';    
            wp_send_json_error( $response_array );
            die();
        }
        $response_array['status'] = 'Updated';
        $response_array['prod_name'] = $product->get_title();
        $response_array['prod_id'] = $pid;
        $response_array['permalink'] = get_permalink( $product->get_id() );
        /* Send mail on success */
        $current_user = wp_get_current_user();
        $current_user_email = sanitize_email($current_user->user_email);
        $to = sanitize_email(get_bloginfo('admin_email'));
        if($current_user_email !== $to){ 
            $site_title = get_bloginfo('name');
            $subject = "[{$site_title}]: Edit product #{$pid}";
            $table_header = "Edit product #{$pid}";
            $product_title = get_the_title($pid);
            $product_price = !empty(get_post_meta($pid, '_regular_price', true)) ? ('<span>'.get_woocommerce_currency_symbol().'</span>'.get_post_meta($pid, '_regular_price', true)) : 'N/A';
            $product_edit_link = '<a href="'.admin_url().'post.php?post='.$pid.'&action=edit" style="font-weight:normal;text-decoration:underline;color:#7f54b3" target="_blank">Product #'.$pid.'</a>';
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $product_message = 'You’ve received the following product from <a href="mailto:'.$current_user_email.'">'.$current_user_email.'</a>:';
            require_once plugin_dir_path( dirname( __FILE__ ) ).'partials/emails/admin-new-product.php';
            wp_mail($to, $subject, $body, $headers);
        }        
        /* Send JSON response */ 
        wp_send_json_success($response_array);
        die();
    }
}
?>