<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!-- WC Product options -->
<div class="bt-box-container bt-product-box-container">
    <!-- Header -->
    <div class="bt-header">
        <h2> <?php _e( 'Product Data', 'bytes_product_frontend' ); ?> — </h2>
        <span>
            <select id="bt-product-type" name="bt-product-type">
                <option value="simple" selected="selected"><?php _e( 'Simple product', 'bytes_product_frontend' ); ?></option>
                <option value="grouped"><?php _e( 'Grouped product', 'bytes_product_frontend' ); ?></option>
                <option value="external"><?php _e( 'External/Affiliate product', 'bytes_product_frontend' ); ?></option>
            </select>
            <div id="virtual_downloadable_product">
                <label for="bt-virtual" class="bt-checkbox-label bt-virtual-feature bt-tooltip">
                    <span class="bt-tooltiptext"><?php _e( 'Virtual products are intangible and are not shipped.', 'bytes_product_frontend' ); ?></span>
                    <?php _e( 'Virtual', 'bytes_product_frontend' ); ?>
                    <input type="checkbox" name="bt-virtual" id="bt-virtual">
                </label>
                <label for="bt-downloadable" class="bt-checkbox-label bt-virtual-feature bt-tooltip">
                    <span class="bt-tooltiptext"><?php _e( 'Downloadable products give access to a file upon purchase.', 'bytes_product_frontend' ); ?></span>
                    <?php _e( 'Downloadable', 'bytes_product_frontend' ); ?>
                    <input type="checkbox" name="bt-downloadable" id="bt-downloadable">
                </label>
            </div>                
        </span>
    </div>
    <!-- Content -->
    <div class="bt-content">
        <!-- Tabs -->
        <ul class="bt-wc-tabs">
            <li class="bt-tab-option bt-general-options active hide_if_grouped">
                <a href="#bt-general-product-data"><span><?php _e( 'General', 'bytes_product_frontend' ); ?></span></a>
            </li>
            <?php if((isset($frontend_product_additional_fields['sku']) && $frontend_product_additional_fields['sku'] == 1) || (isset($frontend_product_additional_fields['manage_stock']) && $frontend_product_additional_fields['manage_stock'] == 1) || (isset($frontend_product_additional_fields['stock_status']) && $frontend_product_additional_fields['stock_status'] == 1) || (isset($frontend_product_additional_fields['sold_individually']) && $frontend_product_additional_fields['sold_individually'] == 1)){ ?>
            <li class="bt-tab-option bt-inventory-options show_if_simple show_if_variable show_if_grouped show_if_external">
                <a href="#bt-inventory-product-data"><span><?php _e( 'Inventory', 'bytes_product_frontend' ); ?></span></a>
            </li>
            <?php } ?>
            <li class="bt-tab-option bt-shipping-options hide_if_virtual hide_if_grouped hide_if_external show_simple_product">
                <a href="#bt-shipping-product-data"><span><?php _e( 'Shipping', 'bytes_product_frontend' ); ?></span></a>
            </li>
            <?php if(isset($frontend_product_additional_fields['linked_products']) && $frontend_product_additional_fields['linked_products'] == 1){ ?>
            <li class="bt-tab-option bt-linked-prod-options">
                <a href="#bt-linked-products-data"><span><?php _e( 'Linked Products', 'bytes_product_frontend' ); ?></span></a>
            </li>
            <?php }
            if(isset($frontend_product_additional_fields['attributes']) && $frontend_product_additional_fields['attributes'] == 1){ ?>
            <li class="bt-tab-option bt-attributes-options">
                <a href="#bt-attributes-product-data"><span><?php _e( 'Attributes', 'bytes_product_frontend' ); ?></span></a>
            </li>
            <?php }
            if((isset($frontend_product_additional_fields['purchase_note']) && $frontend_product_additional_fields['purchase_note'] == 1) || (isset($frontend_product_additional_fields['menu_order']) && $frontend_product_additional_fields['menu_order'] == 1) || (isset($frontend_product_additional_fields['enable_reviews']) && $frontend_product_additional_fields['enable_reviews'] == 1)){ ?>
            <li class="bt-tab-option bt-advanced-options">
                <a href="#bt-advanced-product-data"><span><?php _e( 'Advanced', 'bytes_product_frontend' ); ?></span></a>
            </li>
            <?php } ?>
        </ul>
        <!-- General options -->
        <div id="bt-general-product-data" class="bt-option-panel">
            <div class="bt-option-group show_if_external" style="display: none;">
                <p class="form-field _product_url_field ">
                    <label for="_product_url"><?php _e( 'Product URL', 'bytes_product_frontend' ); ?></label>
                    <input type="text" class="short bt-input bt-input-inner" name="_product_url" id="_product_url" placeholder="https://">
                    <span class="description"><?php _e( 'Enter the external URL to the product.', 'bytes_product_frontend' ); ?></span>
                </p>
                <p class="form-field _button_text_field ">
                    <label for="_button_text"><?php _e( 'Button text', 'bytes_product_frontend' ); ?></label>
                    <input type="text" class="short bt-input bt-input-inner" name="_button_text" id="_button_text" placeholder="Buy product">
                    <span class="description"><?php _e( 'This text will be shown on the button linking to the external product.', 'bytes_product_frontend' ); ?></span>
                </p>
            </div>
            <div class="bt-option-group show_if_simple show_if_external">
                <?php if(isset($frontend_product_additional_fields['regular_price']) && $frontend_product_additional_fields['regular_price'] == 1){ ?>
                <p>
                    <label for="bt-regular-price"><?php _e( 'Regular price', 'bytes_product_frontend' ); echo ' ('.esc_html(get_woocommerce_currency_symbol(), 'bytes_product_frontend').') ';?></label> 
                    <input type="text" class="bt-input bt-input-inner" name="regular_price" id="bt-regular-price" placeholder="">
                </p>
                <?php } ?>
                <?php if(isset($frontend_product_additional_fields['sale_price']) && $frontend_product_additional_fields['sale_price'] == 1){ ?>
                <p>
                    <label for="bt-sale-price"><?php _e( 'Sale price', 'bytes_product_frontend' ); echo ' ('.esc_html(get_woocommerce_currency_symbol(), 'bytes_product_frontend').') ';?></label> 
                    <input type="text" class="bt-input bt-input-inner" name="sale_price" id="bt-sale-price" placeholder="">
                </p>
                <?php } ?>
            </div> 
            <div id="downloadable_product" class="options_group panel woocommerce_options_panel show_if_downloadable" style="display: none;">
                <div class="form-field downloadable_files options_group">
                    <label>Downloadable files</label>
                    <table class="">
                        <thead>
                            <tr>
                                <th class="sort">&nbsp;</th>
                                <th><?php _e( 'Name', 'bytes_product_frontend' ); ?> <?php echo wc_help_tip( __( 'This is the name of the download shown to the customer.', 'bytes_product_frontend' ) ); ?></th>
                                <th colspan="2"><?php _e( 'File URL', 'bytes_product_frontend' ); ?> <?php echo wc_help_tip( __( 'This is the URL or absolute path to the file which customers will get access to. URLs entered here should already be encoded.', 'bytes_product_frontend' ) ); ?></th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>      
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5">
                                    <a href="#" class="button insert" data-row="
                                    <?php
                                        $key  = '';
                                        $file = array(
                                            'file' => '',
                                            'name' => '',
                                        );
                                        ob_start(); ?>
                                        <tr>
                                            <td class="sort"></td>
                                            <td class="file_name">
                                                <input type="text" class="input_text bt-input bt-input-inner" placeholder="<?php _e( 'File name', 'bytes_product_frontend' ); ?>" name="_wc_file_names[]" value="<?php echo esc_attr( $file['name'] ); ?>" />
                                                <input type="hidden" name="_wc_file_hashes[]" value="<?php echo esc_attr( $key ); ?>" />
                                            </td>
                                            <td class="file_url"><input type="text" class="input_text bt-input bt-input-inner" placeholder="<?php _e( 'http://', 'bytes_product_frontend' ); ?>" name="_wc_file_urls[]" value="<?php echo esc_attr( $file['file'] ); ?>" /></td>
                                            <td class="file_url_choose" width="0%"><a href="#" class="button upload_file_button" data-choose="<?php _e( 'Choose file', 'bytes_product_frontend' ); ?>" data-update="<?php _e( 'Insert file URL', 'bytes_product_frontend' ); ?>"><?php _e( 'Choose file', 'bytes_product_frontend' ); ?></a></td>
                                            <td width="1%"><a href="#" class="delete"><?php _e( 'Delete', 'bytes_product_frontend' ); ?></a></td>
                                        </tr>
                                    <?php   echo esc_attr(ob_get_clean());
                                        ?>
                                    "><?php _e( 'Add File', 'bytes_product_frontend' ); ?></a>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <p class="form-field _download_limit_field">
                    <label for="_download_limit"><?php _e( 'Download limit', 'bytes_product_frontend' ); ?></label>
                    <input type="number" class="short bt-input bt-input-inner" style="" name="_download_limit" id="_download_limit" value="" placeholder="Unlimited" step="1" min="0">
                    <span class="description"><?php _e( 'Leave blank for unlimited re-downloads.', 'bytes_product_frontend' ); ?></span>
                </p>

                <p class="form-field _download_expiry_field">
                    <label for="_download_expiry"><?php _e( 'Download expiry', 'bytes_product_frontend' ); ?></label>
                    <input type="number" class="short bt-input bt-input-inner" style="" name="_download_expiry" id="_download_expiry" value="" placeholder="Never" step="1" min="0">
                    <span class="description"><?php _e( 'Enter the number of days before a download link expires, or leave blank.', 'bytes_product_frontend' ); ?></span>
                </p>
            </div>
            <!-- product tax -->
            <?php if(wc_tax_enabled()): ?>
            <div class="bt-option-group show_if_simple show_if_external show_if_variable">
                <?php
                    $tax_classes = wc_get_product_tax_class_options();
                    $tax_status_tooltip = __('Define whether or not the entire product is taxable, or just the cost of shipping it.', 'bytes_product_frontend');
                    $tax_classes_tooltip = __('Choose a tax class for this product. Tax classes are used to apply different tax rates specific to certain types of product.', 'bytes_product_frontend');
                ?>
                <p>
                    <label for="bt-tax-status"><?php _e( 'Tax status', 'bytes_product_frontend' );?></label>
                    <select id="bt-tax-status" name="tax_status">
                        <option value="taxable" selected="selected"><?php _e( 'Taxable', 'bytes_product_frontend' ); ?></option>
                        <option value="shipping"><?php _e( 'Shipping only', 'bytes_product_frontend' ); ?></option>
                        <option value="none"><?php _e( 'None', 'bytes_product_frontend' ); ?></option>		
                    </select>
                    <?php
                        apffw_print_tooltip($tax_status_tooltip);
                    ?>
                </p>
                <p>
                    <label for="bt-tax-class"><?php _e( 'Tax class', 'bytes_product_frontend' );?></label>
                    <select id="bt-tax-class" name="tax_class">
                        <?php
                            foreach($tax_classes as $key => $value){
                                echo '<option value="'. esc_attr( $key ).'">'. esc_html( $value, 'bytes_product_frontend' ) .'</option>';
                            }
                        ?>	
                    </select>
                    <?php
                        apffw_print_tooltip($tax_classes_tooltip);
                    ?>
                </p>
            </div>
            <?php endif; ?>
        </div>
        <!-- Inventory options -->
        <div id="bt-inventory-product-data" class="bt-option-panel bt-hide-section">
            <div class="bt-option-group">
                <?php
                    $sku_tooltip = __('SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'bytes_product_frontend');
                    $stock_status_tooltip = __('Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend.', 'bytes_product_frontend');
                if(isset($frontend_product_additional_fields['sku']) && $frontend_product_additional_fields['sku'] == 1){ ?>
                <p>
                    <label for="bt-sku"><?php _e( 'SKU', 'bytes_product_frontend' );?></label> 
                    <input type="text" class="bt-input bt-input-inner" name="sku" id="bt-sku" placeholder="">
                    <?php
                        apffw_print_tooltip($sku_tooltip);
                    ?>
                </p>
                <?php }
                if(isset($frontend_product_additional_fields['manage_stock']) && $frontend_product_additional_fields['manage_stock'] == 1){ ?>
                <p class="show_if_simple show_if_variable">
                    <label for="bt-manage-stock"><?php _e( 'Manage stock?', 'bytes_product_frontend' );?></label> 
                    <input type="checkbox" class="bt-input" name="manage_stock" id="bt-manage-stock">
                </p>
                <?php } ?>
                <div class="bt-manage-stock-section bt-hide-section">
                    <?php
                        $stock = __('Stock quantity. If this is a variable product this value will be used to control stock for all variations, unless you define stock at variation level.', 'bytes_product_frontend');
                        $backorders = __('If managing stock, this controls whether or not back-orders are allowed. If enabled, stock quantity can go below 0.', 'bytes_product_frontend');
                        $low_stock_amount = __('When product stock reaches this amount you will be notified by email.', 'bytes_product_frontend');
                    ?>
                    <p>
                        <label for="bt-manage-stock"><?php _e( 'Stock quantity', 'bytes_product_frontend' ); ?></label> 
                        <input type="number" class="bt-input bt-input-inner" name="bt_stock" id="bt-stock">
                        <?php
                            apffw_print_tooltip($stock);
                        ?>
                    </p>
                    <p>
                        <label for="bt-manage-stock"><?php _e( 'Allow back-orders?', 'bytes_product_frontend' ); ?></label> 
                        <select class="bt-input bt-input-inner" name="bt_backorders" id="bt-backorders">
                            <option value="no"><?php _e( 'Do not allowed', 'bytes_product_frontend' ); ?></option>
                            <option value="notify"><?php _e( 'Allow, but notify customer', 'bytes_product_frontend' ); ?></option>
                            <option value="yes"><?php _e( 'Allow', 'bytes_product_frontend' ); ?></option>
                        </select>
                        <?php
                            apffw_print_tooltip($backorders);
                        ?>
                    </p>
                    <p>
                        <label for="bt-manage-stock"><?php _e( 'Low stock threshold', 'bytes_product_frontend' );?></label> 
                        <input type="number" class="bt-input bt-input-inner" name="bt_low_stock_amount" id="bt-low-stock-amount">
                        <?php
                            apffw_print_tooltip($low_stock_amount);
                        ?>
                    </p>        
                </div>
                <?php if(isset($frontend_product_additional_fields['stock_status']) && $frontend_product_additional_fields['stock_status'] == 1){ ?>
                <p class="bt-stock-status-option hide_if_variable hide_if_external hide_if_grouped show_simple_product">
                    <?php
                        $stock_status_options = wc_get_product_stock_status_options();
                    ?>
                    <label for="bt-stock-status"><?php _e( 'Stock status', 'bytes_product_frontend' ); ?></label>
                    <select id="bt-stock-status" name="stock_status">
                        <?php
                            foreach ($stock_status_options as $key => $value) {
                                echo '<option value="'. esc_attr( $key ).'">'. esc_html( $value, 'bytes_product_frontend' ) .'</option>';
                            }
                        ?>
                    </select>
                    <?php
                        apffw_print_tooltip($stock_status_tooltip);
                    ?>
                </p>
                <?php } ?>
            </div>
            <?php if(isset($frontend_product_additional_fields['sold_individually']) && $frontend_product_additional_fields['sold_individually'] == 1){ ?>
            <div class="bt-option-group show_if_simple show_if_variable">
                <p>
                    <label for="bt-sold-individually"><?php _e( 'Sold individually', 'bytes_product_frontend' );?></label> 
                    <input type="checkbox" class="bt-input" name="sold_individually" id="bt-sold-individually">
                    <span><i><?php _e( 'Enable this to only allow one of this item to be bought in a single order', 'bytes_product_frontend' );?></i></span>
                </p>
            </div>
            <?php } ?>
        </div>
        <!-- Shipping options -->
        <div id="bt-shipping-product-data" class="bt-option-panel bt-hide-section">
            <div class="bt-option-group">
                <?php 
                    $weight_unit = get_option('woocommerce_weight_unit');
                    $dimension_unit = get_option('woocommerce_dimension_unit');
                    $weight_tootltip = __('Weight in decimal form.', 'bytes_product_frontend');
                    $dimension_tootltip = __('LxWxH in decimal form.', 'bytes_product_frontend');
                    if(isset($frontend_product_additional_fields['weight']) && $frontend_product_additional_fields['weight'] == 1){ ?>
                    <p>
                        <label for="bt-weight"><?php _e( 'Weight', 'bytes_product_frontend' ); echo ' ('.esc_html($weight_unit, 'bytes_product_frontend').')';?></label> 
                        <input type="text" class="bt-input bt-input-inner" name="weight" id="bt-weight" placeholder="0">
                        <?php
                            apffw_print_tooltip($weight_tootltip);
                        ?>
                    </p>
                    <?php } 
                    if(isset($frontend_product_additional_fields['dimensions']) && $frontend_product_additional_fields['dimensions'] == 1){ ?>
                    <p class="bt-dimensions-field">
                        <label for="bt-product-length"><?php _e( 'Dimensions', 'bytes_product_frontend' ); echo ' ('.esc_html($dimension_unit, 'bytes_product_frontend').')';?></label>
                        <span>
                            <input type="text" class="bt-input bt-input-inner" name="length" id="bt-product-length" placeholder="Length" style="">
                            <input type="text" class="bt-input bt-input-inner" name="width" placeholder="Width">
                            <input type="text" class="bt-input bt-input-inner" name="height" placeholder="Height">
                        </span>
                        <?php
                            apffw_print_tooltip($dimension_tootltip);
                        ?>
                    </p>
                    <?php } ?>
            </div>
            <div class="bt-option-group">
                <p>
                    <?php
                        $shipping_classes = get_terms( array('taxonomy' => 'product_shipping_class', 'hide_empty' => false ) );
                        $shipping_classes_tootltip = __('Shipping classes are used by certain shipping methods to group similar products.', 'bytes_product_frontend');
                    ?>
                    <label for="bt-shipping-class"><?php _e( 'Product shipping class', 'bytes_product_frontend' ); ?></label>
                    <select id="bt-shipping-class" name="shipping_class">
                        <option value="-1" selected="selected"><?php echo esc_html("No shipping class", 'bytes_product_frontend'); ?></option>
                        <?php
                            foreach ($shipping_classes as $shipping_class) {
                                echo '<option value="'. esc_attr( $shipping_class->term_id ) .'">'. esc_html( $shipping_class->name, 'bytes_product_frontend' ) .'</option>';
                            }
                        ?>
                    </select>
                    <?php
                        apffw_print_tooltip($shipping_classes_tootltip);
                    ?>
                </p>
            </div>
        </div>
        <!-- Linked Products -->
        <div id="bt-linked-products-data" class="bt-option-panel bt-hide-section">
            <div class="bt-option-group">
                <?php
                        $linked_products =  wc_get_products( array( 'status' => 'publish', 'limit' => -1 ) );
                        $groupsell_tootltip = __('This lets you choose which products are part of this group.', 'bytes_product_frontend');
                        $upsell_tootltip = __('Upsells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'bytes_product_frontend');
                        $crossell_tootltip = __('Cross-sells are products which you promote in the cart, based on the current product.', 'bytes_product_frontend');
                ?>
                <p class="form-field show_if_grouped">
                    <label for="grouped_products"><?php esc_html_e( 'Grouped products', 'bytes_product_frontend' ); ?></label>
                    <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="grouped_products" name="grouped_products[]" data-sortable="true" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;' ); ?>">
                        <?php
                        foreach ( $linked_products as $product ) {
                            $product = wc_get_product($product->get_id());
                            if ( is_object( $product ) ) {
                                echo '<option value="' . esc_attr( $product->get_id() ) . '">' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option>';
                            }
                        }
                        ?>
                    </select> 
                    <?php apffw_print_tooltip($groupsell_tootltip); ?> 
                </p>
                <p class="form-field">
                    <label for="upsell_ids"><?php esc_html_e( 'Upsells', 'bytes_product_frontend' ); ?></label>
                    <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="upsell_ids" name="upsell_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;' ); ?>">
                        <?php
                        foreach ( $linked_products as $product ) {
                            $product = wc_get_product( $product->get_id() );
                            if ( is_object( $product ) ) {
                                echo '<option value="' . esc_attr( $product->get_id() ) . '">' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option>';
                            }
                        }
                        ?>
                    </select> 
                    <?php apffw_print_tooltip($upsell_tootltip); ?>
                </p>
                <p class="form-field hide_if_grouped hide_if_external">
                    <label for="crosssell_ids"><?php esc_html_e( 'Cross-sells', 'bytes_product_frontend' ); ?></label>
                    <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="crosssell_ids" name="crosssell_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;' ); ?>">
                        <?php
                        foreach ( $linked_products as $product ) {
                            $product = wc_get_product( $product->get_id() );
                            if ( is_object( $product ) ) {
                                echo '<option value="' . esc_attr( $product->get_id() ) . '">' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option>';
                            }
                        }
                        ?>
                    </select> 
                     <?php apffw_print_tooltip($crossell_tootltip); ?>
                </p>    
            </div>
        </div>
        <!-- Attributes -->
        <div id="bt-attributes-product-data" class="bt-option-panel bt-hide-section">
            <div class="bt-loader bt-attribute-loader"></div>
            <?php require_once( plugin_dir_path( __FILE__ ).'bytes-woo-template-product-attributes.php'); ?>
        </div>  
        <!-- Advanced -->
        <div id="bt-advanced-product-data" class="bt-option-panel bt-hide-section">
            <?php if(isset($frontend_product_additional_fields['purchase_note']) && $frontend_product_additional_fields['purchase_note'] == 1){ ?>
            <div class="bt-option-group hide_if_external hide_if_grouped">
                <?php
                    $purchase_note_tootltip = __('Enter an optional note to send the customer after purchase.', 'bytes_product_frontend');
                ?>
                <p>
                    <label for="bt-purchase-note"><?php _e( 'Purchase note', 'bytes_product_frontend' );?></label> 
                    <textarea class="bt-input bt-input-inner" name="purchase_note" id="bt-purchase-note" placeholder="" rows="2" cols="20"></textarea>
                    <?php
                        apffw_print_tooltip($purchase_note_tootltip);
                    ?>        
                </p>      
            </div>
            <?php }
            if(isset($frontend_product_additional_fields['menu_order']) && $frontend_product_additional_fields['menu_order'] == 1){ ?>
            <div class="bt-option-group">
                <?php
                    $menu_order_tootltip = __('Custom ordering position.', 'bytes_product_frontend');
                ?>
                <p>
                    <label for="bt-menu-order"><?php _e( 'Menu order', 'bytes_product_frontend' );?></label> 
                    <input type="number" class="bt-input bt-input-inner" name="menu_order" id="bt-menu-order" value="0" step="1" placeholder="">
                    <?php
                        apffw_print_tooltip($menu_order_tootltip);
                    ?>   
                </p>      
            </div>
            <?php }
            if(isset($frontend_product_additional_fields['enable_reviews']) && $frontend_product_additional_fields['enable_reviews'] == 1){ ?>
            <div class="bt-option-group">
                <p>
                    <label for="bt-reviews-allowed"><?php _e( 'Reviews allowed', 'bytes_product_frontend' );?></label> 
                    <input type="checkbox" class="bt-input" name="reviews_allowed" id="bt-reviews-allowed" checked="checked">     
                </p>      
            </div>
            <?php } ?>
        </div>
    </div>
</div>