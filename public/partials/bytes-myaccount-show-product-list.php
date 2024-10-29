<style type="text/css">
	table {
		border-collapse: collapse;
		width: 100%;
	}
	th, td {
		border: 1px solid #000;
		text-align: center;
		padding: 10px;
	}
</style>
<?php
	/* show list of product which is added by customer (author) */
	$author = (current_user_can('administrator')) ? '' : get_current_user_id();
	$status = (current_user_can('administrator')) ? 'any' : 'any';
	$customer_access = true;
	$paged = !empty($_GET['list_page']) ? absint($_GET['list_page']) : 1;
	$posts_per_page = 10;

	global $wp;
	$current_url = home_url(add_query_arg(array(), $wp->request)); // get current page url
	if(!empty(isset($_GET['orderby']))){
		$orderby = sanitize_text_field(wp_unslash($_GET['orderby']));
	}

	if(!empty(isset($_GET['order']))){
		$order = strtolower(sanitize_text_field(wp_unslash($_GET['order'])));
	}

	if(!empty(isset($_GET['search_by_product_title']))){
		$search_by_product_title = sanitize_text_field(wp_unslash($_GET['search_by_product_title']));
	}

	if(!empty(isset($_GET['orderby']))){
		if($orderby == "id" || $orderby == "title"){
			$args = array(
				'post_type' => 'product',
			    'post_status' => $status,
			    'posts_per_page' => $posts_per_page,
			    'paged' => $paged,
			    'author' => $author,
			    'orderby'=> $orderby, 
				'order' => $order,
			);
		}
		else if($orderby == "price"){
			$args = array(
				'post_type' => 'product',
			    'post_status' => $status,
			    'posts_per_page' => $posts_per_page,
			    'paged' => $paged,
			    'author' => $author,
				'orderby'   => 'meta_value_num',
	    		'meta_key'  => '_price',
	    		'order' => $order,
			);
		}
	}
	else if(!empty(isset($_GET['search_by_product_title']))){
		$args = array(
		    'post_type' => 'product',
		    'post_status' => $status,
		    'posts_per_page' => $posts_per_page,
		    's' => $search_by_product_title,
		    'paged' => $paged,
		    'author' => $author,
		);
	}
	else{
		$args = array(
		    'post_type' => 'product',
		    'post_status' => $status,
		    'posts_per_page' => $posts_per_page,
		    'paged' => $paged,
		    'author' => $author,
		);
	}
	if(!empty(isset($_GET['order']))){
		if($order == 'asc'){
			$order = 'desc';
		}
		else{
			$order = 'asc';
		}
	}
	else{
		$order = 'asc';
	}
	$loop = new WP_Query($args);
	if($loop->have_posts()): ?>
	<div style="overflow-x:auto;">
		<div class="product-list-container">
			<form class="product-list-form-container" method="get" action="">
				<input type="text" name="search_by_product_title" placeholder="Search by product name..." autocomplete="off">
				<button type="submit"><?php esc_html_e('Search', 'bytes_product_frontend'); ?></button>
			</form>
		</div>
		<table class="bt_table">
			<thead>
			<tr>
				<th><a href="<?php echo esc_url($current_url); ?>/?orderby=id&order=<?php echo esc_attr($order); ?>"><?php esc_html_e('Sr. No.', 'bytes_product_frontend'); ?></a></th>
				<th><a href="<?php echo esc_url($current_url); ?>/?orderby=title&order=<?php echo esc_attr($order); ?>"><?php esc_html_e('Product Name', 'bytes_product_frontend'); ?></a></th>
				<th><a href="<?php echo esc_url($current_url); ?>/?orderby=price&order=<?php echo esc_attr($order); ?>"><?php esc_html_e('Price', 'bytes_product_frontend'); ?></a></th>
				<?php if(current_user_can('administrator')): ?>
					<th><?php esc_html_e('Author', 'bytes_product_frontend'); ?></th> 
				<?php endif; ?>
				<th><?php esc_html_e('Status', 'bytes_product_frontend'); ?></th>
				<th><?php esc_html_e('Product Link', 'bytes_product_frontend'); ?></th>
				<th><?php esc_html_e('Action', 'bytes_product_frontend'); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php 
			$i = (($paged*$posts_per_page) - $posts_per_page) + 1;
			while($loop->have_posts()) : $loop->the_post(); 
				/* get $product object using product ID */  
				$product = wc_get_product(get_the_id()); ?>
				<tr>
					<td><?php esc_html_e(absint($i++), 'bytes_product_frontend'); ?></td>
					<td><?php esc_html_e(sanitize_title($product->get_name()), 'bytes_product_frontend'); ?></td>
					<td><?php echo wp_kses_post($product->get_price_html()); ?></td>
					<?php if(current_user_can('administrator')): ?>
						<td><?php esc_html_e(get_the_author(), 'bytes_product_frontend'); ?></td>
					<?php endif; ?>
					<td><?php esc_html_e(get_post_status(), 'bytes_product_frontend'); ?></td>
					<td>
						<?php if(get_post_status() == 'publish'): ?>
						<a href="<?php echo esc_url(get_permalink(get_the_id())); ?>"><?php esc_html_e('View', 'bytes_product_frontend'); ?></a>
						<?php endif; ?>
					</td>
					<?php if(current_user_can('administrator')): ?>
						<td><a href="<?php echo esc_url(get_permalink(wc_get_page_id('myaccount'))); ?>edit-product-form/?id=<?php echo absint(get_the_id()); ?>" data-id="<?php echo absint(get_the_id()); ?>" class="edit_product"><?php esc_html_e('Edit', 'bytes_product_frontend'); ?></a>
					| <a href="javascript:void(0)" data-id="<?php echo absint(get_the_id()); ?>" class="delete_product"><?php esc_html_e('Delete', 'bytes_product_frontend'); ?></a></td>
					<?php else: ?>
						<td><a href="<?php echo esc_url(get_permalink(wc_get_page_id('myaccount'))); ?>edit-product-form/?id=<?php echo absint(get_the_id()); ?>" data-id="<?php echo absint(get_the_id()); ?>" class="edit_product"><?php esc_html_e('Edit', 'bytes_product_frontend'); ?></a></td>
					<?php endif; ?>
				</tr>
			<?php endwhile; ?>
			</tbody>
		</table>
	</div>
	<?php
	else:
		esc_html_e('No products found', 'bytes_product_frontend');
    endif;    
	wp_reset_postdata();

/* *** pagination *** */
echo product_list_pagination($loop);