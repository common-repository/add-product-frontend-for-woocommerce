jQuery(document).ready(function($){
    // save product
    $('#bt-product-form').on('submit', function (e){
        e.preventDefault();
        $('.bt-modal').show();
        $('.bt-modal .bt-loading-section').show();
        var formdata = new FormData($('#bt-product-form')[0]); // get form data
        formdata.append("action", "bytes_save_product"); // add action
        formdata.append("security", savesimpleproduct.security); // add security
        $.ajax({
            method: "POST",
            dataType: "json",
            url: savesimpleproduct.ajax_url,
            data: formdata,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.success){
                   $('.bt-modal .bt-loading-section').hide();
                   $('.bt-modal .bt-modal-content .bt-modal-result').show();
                }
                else{
                   $('.bt-modal .bt-loading-section').hide();
                   $('.bt-modal .bt-modal-content .bt-modal-result-error .bt-modal-main-section p').html(response.data.description);
                   $('.bt-modal .bt-modal-content .bt-modal-result-error').show();
                }
            }
        });
    });

    // edit simple product
    $('#bt-edit-product-form').on('submit', function (e){
    e.preventDefault();
    $('.bt-modal').show();
    $('.bt-modal .bt-loading-section').show();
    var formdata = new FormData($('#bt-edit-product-form')[0]); // get form data
    formdata.append("action", "bytes_edit_product"); // add action
    formdata.append("security", savesimpleproduct.security); // add security
    $.ajax({
        method: "POST",
        dataType: "json",
        url: savesimpleproduct.ajax_url,
        data: formdata,
        processData: false,
        contentType: false,
        success: function(response){
            if(response.success){
               $('.bt-modal .bt-loading-section').hide();
               $('.bt-modal .bt-modal-content .bt-modal-result').show();
            }
            else{
               $('.bt-modal .bt-loading-section').hide();
               $('.bt-modal .bt-modal-content .bt-modal-result-error .bt-modal-main-section p').html(response.data.description);
               $('.bt-modal .bt-modal-content .bt-modal-result-error').show();
            }
        }
    });
    });

    $('.bt-modal .bt-modal-result button').on('click', function(e){
        $('html,body').scrollTop(0);
        location.reload();
    });

    $('.bt-modal .bt-modal-result-error button').on('click', function(e){
        $('html,body').scrollTop(0);
        $('.bt-modal .bt-modal-content .bt-modal-result-error').hide();
        $('.bt-modal').hide();
    });

    // delete product
    $(".delete_product").on('click', function(e){
        let delete_product = confirm("Are you sure want to delete this product ?");
        if(delete_product){
            let product_id = $(this).attr('data-id');
            $.ajax({
                method: "POST",
                dataType: "json",
                url: savesimpleproduct.ajax_url,
                data: {
                    'action': 'bytes_delete_product',
                    'product_id': product_id
                },
                success: function(response){
                    if(response.status == true && response.message == "The product has been removed"){
                        alert("The product has been removed.");
                        $('html,body').scrollTop(0);
                        window.location.reload();
                    }
                    else if(response.status == false && response.message == "There is an error to remove product"){
                        alert("There is an error to remove product");
                    }     
                },
            });
        }
    });
});