<?php
/* *** delete product from admin ***/
add_action('wp_ajax_bytes_admin_delete_product', 'apffw_admin_delete_product');
add_action('wp_ajax_nopriv_bytes_admin_delete_product', 'apffw_admin_delete_product');

if(!function_exists('apffw_admin_delete_product')){
    function apffw_admin_delete_product(){
        if(!empty($_POST['action']) && $_POST['action'] == 'bytes_admin_delete_product'){
            $id = intval($_POST['product_id']); // get product id
            $product = wc_get_product($id);
            $force = false;
            if(empty($product)){
                echo json_encode(array(
                    'status' => false,
                    'message' => "There is an error to remove product"
                ));
                exit;   
            }
            /* If forcing, then delete permanently */
            if($force){
                if($product->is_type('grouped')){
                    foreach($product->get_children() as $child_id){
                        $child = wc_get_product($child_id);
                        $child->set_parent_id(0);
                        $child->save();
                    }
                }
                $product->delete(true);
                $result = $product->get_id() > 0 ? false : true;
                if($result){
                    echo json_encode(array(
                        'status' => true,
                        'message' => "The product has been removed",
                    ));
                    exit;
                }
            }
            else{
                $product->delete();
                $result = 'trash' === $product->get_status();
                if($result){
                    echo json_encode(array(
                        'status' => true,
                        'message' => "The product has been removed",
                    ));
                    exit;
                }
            }
            if(!$result){
                echo json_encode(array(
                    'status' => false,
                    'message' => "This product cannot be deleted"
                ));
                exit;  
            }
        }    
    }
}
?>