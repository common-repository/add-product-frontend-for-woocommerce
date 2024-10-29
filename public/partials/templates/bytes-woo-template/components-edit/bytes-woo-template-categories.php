<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!-- Product categories -->
<div class="bt-box-container bt-product-box-container bt-category-container">
    <div class="bt-header" style="border-bottom: 1px solid #ccd0d4;">
        <h2><?php _e( 'Product categories', 'bytes_product_frontend' ); ?></h2>
    </div>
    <div class="bt-content" style="padding: 12px;">
        <?php
              /* 1 for yes, 0 for no */
              $taxonomy     = 'product_cat';
              $orderby      = 'name';  
              $show_count   = 0;      
              $pad_counts   = 0;
              $hierarchical = 1;
              $title        = '';  
              $empty        = 0;
              $args = array(
                     'taxonomy'     => $taxonomy,
                     'orderby'      => $orderby,
                     'show_count'   => $show_count,
                     'pad_counts'   => $pad_counts,
                     'hierarchical' => $hierarchical,
                     'title_li'     => $title,
                     'hide_empty'   => $empty
              );
             $all_categories = get_categories( $args );
             foreach ($all_categories as $cat) {
                if($cat->category_parent == 0) {
                    $category_id = $cat->term_id;
                    $cat_checked = in_array($category_id, $product_object->get_category_ids()) ? "checked" : "";
                    echo '<label class="bt-cat-list"><input value="'. esc_attr($category_id).'" type="checkbox" name="bt_product_cat[]" '.esc_attr($cat_checked).'>'.esc_html($cat->name, 'bytes_product_frontend').'</label><br />';
                    $args2 = array(
                            'taxonomy'     => $taxonomy,
                            'child_of'     => 0,
                            'parent'       => $category_id,
                            'orderby'      => $orderby,
                            'show_count'   => $show_count,
                            'pad_counts'   => $pad_counts,
                            'hierarchical' => $hierarchical,
                            'title_li'     => $title,
                            'hide_empty'   => $empty
                    );
                    $sub_cats = get_categories( $args2 );
                    if($sub_cats) {
                        foreach($sub_cats as $sub_category) {
                            $sub_cats_checked = in_array($sub_category->term_id, $product_object->get_category_ids()) ? "checked" : "";
                            echo '<label class="bt-sub-cat-list"><input value="'. esc_attr($sub_category->term_id).'" type="checkbox" name="bt_product_cat[]" '.esc_attr($sub_cats_checked).'>'.esc_html($sub_category->name, 'bytes_product_frontend').'</label><br />';
                            $args3 = array(
                                'taxonomy'     => $taxonomy,
                                'child_of'     => 0,
                                'parent'       => $sub_category->term_id,
                                'orderby'      => $orderby,
                                'show_count'   => $show_count,
                                'pad_counts'   => $pad_counts,
                                'hierarchical' => $hierarchical,
                                'title_li'     => $title,
                                'hide_empty'   => $empty
                            );
                            $sub_sub_cats = get_categories( $args3 );
                            if($sub_sub_cats) {
                                foreach($sub_sub_cats as $sub_sub_category) {
                                    $sub_category_checked = in_array($sub_sub_category->term_id, $product_object->get_category_ids()) ? "checked" : "";
                                    echo '<label class="bt-sub-sub-cat-list"><input value="'. esc_attr($sub_sub_category->term_id).'" type="checkbox" name="bt_product_cat[]" '.esc_attr($sub_category_checked).'>'.esc_html($sub_sub_category->name, 'bytes_product_frontend').'</label><br />';
                                }
                            }
                        }
                    }
                }       
            }
        ?>
    </div>
</div>