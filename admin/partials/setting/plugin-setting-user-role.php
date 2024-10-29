<?php
   /* id and name of form element should be same as the setting name */
   $frontend_product_user_role = get_option('frontend_product_user_role'); ?>
 
   <label for="frontend_product_user_role[administrator]">
      <input name="frontend_product_user_role[administrator]" id="frontend_product_user_role[administrator]" type="checkbox" value="1" <?php isset($frontend_product_user_role['administrator']) ? checked('1', $frontend_product_user_role['administrator']) : ""; ?> /><?php _e( 'Administrator', 'bytes_product_frontend' ); ?>
   </label>
   <br />
   <br />

   <label for="frontend_product_user_role[editor]">
      <input name="frontend_product_user_role[editor]" id="frontend_product_user_role[editor]" type="checkbox" value="1" <?php isset($frontend_product_user_role['editor']) ? checked('1', $frontend_product_user_role['editor']) : ""; ?> /><?php _e( 'Editor', 'bytes_product_frontend' ); ?>
   </label>
   <br />
   <br />
   
   <label for="frontend_product_user_role[author]">
      <input name="frontend_product_user_role[author]" id="frontend_product_user_role[author]" type="checkbox" value="1" <?php isset($frontend_product_user_role['author']) ? checked('1', $frontend_product_user_role['author']) : ""; ?> /><?php _e( 'Author', 'bytes_product_frontend' ); ?>
   </label>
   <br />
   <br />
   
   <label for="frontend_product_user_role[contributor]">
      <input name="frontend_product_user_role[contributor]" id="frontend_product_user_role[contributor]" type="checkbox" value="1" <?php isset($frontend_product_user_role['contributor']) ? checked('1', $frontend_product_user_role['contributor']) : ""; ?> /><?php _e( 'Contributor', 'bytes_product_frontend' ); ?>
   </label> 
   <br />
   <br />
   
   <label for="frontend_product_user_role[subscriber]">
      <input name="frontend_product_user_role[subscriber]" id="frontend_product_user_role[subscriber]" type="checkbox" value="1" <?php isset($frontend_product_user_role['subscriber']) ? checked('1', $frontend_product_user_role['subscriber']) : ""; ?> /><?php _e( 'Subscriber', 'bytes_product_frontend' ); ?>
   </label>
   <br />
   <br />
   
   <label for="frontend_product_user_role[customer]">
      <input name="frontend_product_user_role[customer]" id="frontend_product_user_role[customer]" type="checkbox" value="1" <?php isset($frontend_product_user_role['customer']) ? checked('1', $frontend_product_user_role['customer']) : ""; ?> /><?php _e( 'Customer', 'bytes_product_frontend' ); ?>
   </label>
   <br />
   <br />
   
   <label for="frontend_product_user_role[shop_manager]">
      <input name="frontend_product_user_role[shop_manager]" id="frontend_product_user_role[shop_manager]" type="checkbox" value="1" <?php isset($frontend_product_user_role['shop_manager']) ? checked('1', $frontend_product_user_role['shop_manager']) : ""; ?> /><?php _e( 'Shop Manager', 'bytes_product_frontend' ); ?>
   </label> 
   <br />
   <br />