jQuery(document).ready(function($){
  // delete product from admin
  jQuery(".delete_product").on('click', function(e){
    let delete_product = confirm("Are you sure want to delete this product ?");
    if(delete_product){
      let product_id = jQuery(this).attr('data-id');
      jQuery.ajax({
          method: "POST",
          dataType: "json",
          url: save_admin_product.ajax_url,
          data: {
            'action': 'bytes_admin_delete_product',
            'product_id': product_id
          },
          success: function(response){
              if(response.status == true && response.message == "The product has been removed"){
                alert("The product has been removed.");
                window.location.reload();
              }
              else if(response.status == false && response.message == "There is an error to remove product"){
                alert("There is an error to remove product");
              }     
          },
      });
    }
  });

  jQuery(document).on('change','.apffw_user_roles', function(){
        var role = jQuery(this).val();
        console.log(role);

        var nonce = "";
        
         jQuery.ajax({
            method: "POST",
            dataType: "json",
            url: save_admin_product.ajax_url,
            data: {action: "apffw_get_users_by_role", role : role, nonce: nonce},
            success: function(response){
                if(response.status){
                  jQuery(document).find(".apffw_user_lists").html( response.data );
                }
            }
        });
    });


});