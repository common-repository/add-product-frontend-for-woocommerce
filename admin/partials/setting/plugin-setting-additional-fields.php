<?php 
	/* id and name of form element should be same as the setting name */
	$frontend_product_additional_fields = get_option('frontend_product_additional_fields'); ?>
    <label for="frontend_product_additional_fields[description]">
    	<input name="frontend_product_additional_fields[description]" id="frontend_product_additional_fields[description]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['description']) ? checked('1', $frontend_product_additional_fields['description']) : ""; ?> /><?php _e( 'Description', 'bytes_product_frontend' ); ?>
    </label>
    <br />
    <br />
  	
  	<label for="frontend_product_additional_fields[short_description]">
    	<input name="frontend_product_additional_fields[short_description]" id="frontend_product_additional_fields[short_description]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['short_description']) ? checked('1', $frontend_product_additional_fields['short_description']) : ""; ?> /><?php _e( 'Short Description', 'bytes_product_frontend' ); ?>
    </label>
		<br />
		<br />

		<label for="frontend_product_additional_fields[image]">
    	<input name="frontend_product_additional_fields[image]" id="frontend_product_additional_fields[image]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['image']) ? checked('1', $frontend_product_additional_fields['image']) : ""; ?> /><?php _e( 'Product image', 'bytes_product_frontend' ); ?>
    </label>	
 	<br />
 	<br />

 	<label for="frontend_product_additional_fields[gallery]">
 		<input name="frontend_product_additional_fields[gallery]" id="frontend_product_additional_fields[gallery]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['gallery']) ? checked('1', $frontend_product_additional_fields['gallery']) : ""; ?> /><?php _e( 'Product gallery', 'bytes_product_frontend' ); ?>
	</label>
	<br />
	<br />

	<label for="frontend_product_additional_fields[categories]">
    	<input name="frontend_product_additional_fields[categories]" id="frontend_product_additional_fields[categories]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['categories']) ? checked('1', $frontend_product_additional_fields['categories']) : ""; ?> /><?php _e( 'Categories', 'bytes_product_frontend' ); ?>
  	</label>
  	<br />
  	<br />

  	<label for="frontend_product_additional_fields[tags]">
    	<input name="frontend_product_additional_fields[tags]" id="frontend_product_additional_fields[tags]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['tags']) ? checked('1', $frontend_product_additional_fields['tags']) : ""; ?> /><?php _e( 'Tags', 'bytes_product_frontend' ); ?>
  	</label>
  	<br />
  	<br />

  	<label for="frontend_product_additional_fields[regular_price]">
    	<input name="frontend_product_additional_fields[regular_price]" id="frontend_product_additional_fields[regular_price]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['regular_price']) ? checked('1', $frontend_product_additional_fields['regular_price']) : ""; ?> /><?php _e( 'Regular Price', 'bytes_product_frontend' ); ?>
	</label>
	<br />
	<br />

	<label for="frontend_product_additional_fields[sale_price]">
    	<input name="frontend_product_additional_fields[sale_price]" id="frontend_product_additional_fields[sale_price]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['sale_price']) ? checked('1', $frontend_product_additional_fields['sale_price']) : ""; ?> /><?php _e( 'Sale Price', 'bytes_product_frontend' ); ?>
  	</label>
  	<br />
  	<br />

  	<label for="frontend_product_additional_fields[sku]">
	  		<input name="frontend_product_additional_fields[sku]" id="frontend_product_additional_fields[sku]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['sku']) ? checked('1', $frontend_product_additional_fields['sku']) : ""; ?> /><?php _e( 'SKU', 'bytes_product_frontend' ); ?>
		</label>
		<br />
		<br />

		<label for="frontend_product_additional_fields[manage_stock]">
		<input name="frontend_product_additional_fields[manage_stock]" id="frontend_product_additional_fields[manage_stock]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['manage_stock']) ? checked('1', $frontend_product_additional_fields['manage_stock']) : ""; ?> /><?php _e( 'Manage stock', 'bytes_product_frontend' ); ?>
	</label>
	<br />
	<br />

	<label for="frontend_product_additional_fields[stock_status]">
		<input name="frontend_product_additional_fields[stock_status]" id="frontend_product_additional_fields[stock_status]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['stock_status']) ? checked('1', $frontend_product_additional_fields['stock_status']) : ""; ?> /><?php _e( 'Stock status', 'bytes_product_frontend' ); ?>
	</label>
	<br />
	<br />

	<label for="frontend_product_additional_fields[sold_individually]">
		<input name="frontend_product_additional_fields[sold_individually]" id="frontend_product_additional_fields[sold_individually]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['sold_individually']) ? checked('1', $frontend_product_additional_fields['sold_individually']) : ""; ?> /><?php _e( 'Sold individually', 'bytes_product_frontend' ); ?>
	</label>
	<br />
	<br />

	<label for="frontend_product_additional_fields[weight]">
		<input name="frontend_product_additional_fields[weight]" id="frontend_product_additional_fields[weight]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['weight']) ? checked( '1', $frontend_product_additional_fields['weight']) : ""; ?> /><?php _e( 'Weight', 'bytes_product_frontend' ); ?>
	</label>
	<br />
	<br />

	<label for="frontend_product_additional_fields[dimensions]">
		<input name="frontend_product_additional_fields[dimensions]" id="frontend_product_additional_fields[dimensions]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['dimensions']) ? checked('1', $frontend_product_additional_fields['dimensions']) : ""; ?> /><?php _e( 'Dimensions', 'bytes_product_frontend' ); ?>
	</label>
	<br />
	<br />

	<label for="frontend_product_additional_fields[linked_products]">
		<input name="frontend_product_additional_fields[linked_products]" id="frontend_product_additional_fields[linked_products]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['linked_products']) ? checked('1', $frontend_product_additional_fields['linked_products']) : ""; ?> /><?php _e( 'Linked Products', 'bytes_product_frontend' ); ?>
	</label>
	<br />
	<br />

	<label for="frontend_product_additional_fields[attributes]">
		<input name="frontend_product_additional_fields[attributes]" id="frontend_product_additional_fields[attributes]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['attributes']) ? checked('1', $frontend_product_additional_fields['attributes']) : ""; ?> /><?php _e( 'Attributes', 'bytes_product_frontend' ); ?>
	</label>
	<br />
	<br />

	<label for="frontend_product_additional_fields[purchase_note]">
		<input name="frontend_product_additional_fields[purchase_note]" id="frontend_product_additional_fields[purchase_note]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['purchase_note']) ? checked('1', $frontend_product_additional_fields['purchase_note']) : ""; ?> /><?php _e( 'Purchase note', 'bytes_product_frontend' ); ?>
	</label>
	<br />
	<br />

	<label for="frontend_product_additional_fields[menu_order]">
		<input name="frontend_product_additional_fields[menu_order]" id="frontend_product_additional_fields[menu_order]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['menu_order']) ? checked('1', $frontend_product_additional_fields['menu_order']) : ""; ?> /><?php _e( 'Menu order', 'bytes_product_frontend' ); ?>
	</label>
	<br />
	<br />

	<label for="frontend_product_additional_fields[enable_reviews]">
		<input name="frontend_product_additional_fields[enable_reviews]" id="frontend_product_additional_fields[enable_reviews]" type="checkbox" value="1" <?php isset($frontend_product_additional_fields['enable_reviews']) ? checked('1', $frontend_product_additional_fields['enable_reviews']) : ""; ?> /><?php _e( 'Enable reviews', 'bytes_product_frontend' ); ?>
	</label>
	<br />
	<br />