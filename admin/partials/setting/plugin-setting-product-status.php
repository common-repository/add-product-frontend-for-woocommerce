<?php 
	$draft = get_option('frontend_product_status') == 'draft' ? 'selected' : '';
	$publish = get_option('frontend_product_status') == 'publish' ? 'selected' : '';
?>
<select name="frontend_product_status">
    <option value="<?php echo esc_attr('draft'); ?>" <?php echo esc_attr($draft); ?>><?php _e( 'Draft', 'bytes_product_frontend' ); ?></option>
    <option value="<?php echo esc_attr('publish'); ?>" <?php echo esc_attr($publish); ?>><?php _e( 'Publish', 'bytes_product_frontend' ); ?></option>
</select>
<br />