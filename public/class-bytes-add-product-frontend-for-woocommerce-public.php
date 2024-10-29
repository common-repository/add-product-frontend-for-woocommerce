<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 */
if(!class_exists('APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Public')){
	class APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Public {
		private $plugin_name;
		private $version;

		public function __construct( $plugin_name, $version ) {
			$this->plugin_name = $plugin_name;
			$this->version = $version;
		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 */
		public function apffw_enqueue_styles() {
			/* *** add product frontend css *** */
			wp_enqueue_style( 'bytes-woo-template.css', plugin_dir_url( __FILE__ ) . 'css/bytes-woo-template.css', array(), $this->version, 'all' );
			/* *** add selectize css file *** */
			wp_enqueue_style( 'selectize.css', plugin_dir_url( __FILE__ ) . 'css/selectize.css', array(), $this->version, 'all' );
			/* *** add select2 css file *** */
			wp_enqueue_style( 'select2.css', plugin_dir_url( __FILE__ ) . 'css/select2.css', array(), $this->version, 'all' );
		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 */
		public function apffw_enqueue_scripts() {
			/* *** add product frontend js *** */
			wp_enqueue_script( 'bytes-woo-template.js', plugin_dir_url( __FILE__ ) . 'js/bytes-woo-template.js', array( 'jquery' ), $this->version, false );
			/* *** add selectize.min.js *** */
			wp_enqueue_script( 'selectize.min.js', plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array( 'jquery' ), $this->version, false );
			/* *** add Product attribute js *** */
			wp_enqueue_script( 'product-attributes.js', plugin_dir_url( __FILE__ ) . 'js/product-attributes.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( 'product-attributes.js', 'productattributes', array( 
				'ajax_url' => admin_url( 'admin-ajax.php' ), 
				'security' => wp_create_nonce( 'bytes-security' ),
				'post_id' => isset( $post->ID ) ? $post->ID : '',
			));
			/* *** add save product js *** */
			wp_enqueue_script( 'bytes-save-product.js', plugin_dir_url( __FILE__ ) . 'js/bytes-save-product.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( 'bytes-save-product.js', 'savesimpleproduct', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce( 'bytes-security' )));
			/* *** add wc-enhanced-select.js *** */
			wp_enqueue_script( 'wc-enhanced-select.js', plugin_dir_url( __FILE__ ) . 'js/wc-enhanced-select.js', array('jquery', 'selectWoo'), $this->version, false );
			wp_localize_script(
				'wc-enhanced-select.js',
				'wc_enhanced_select_params',
				array(
					'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', 'bytes_product_frontend' ),
					'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', 'bytes_product_frontend' ),
					'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', 'bytes_product_frontend' ),
					'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', 'bytes_product_frontend' ),
					'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', 'bytes_product_frontend' ),
					'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', 'bytes_product_frontend' ),
					'i18n_selection_too_long_1' => _x( 'You can only select 1 item', 'enhanced select', 'bytes_product_frontend' ),
					'i18n_selection_too_long_n' => _x( 'You can only select %qty% items', 'enhanced select', 'bytes_product_frontend' ),
					'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'bytes_product_frontend' ),
					'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'bytes_product_frontend' ),
					'ajax_url'                  => admin_url( 'admin-ajax.php' ),
					'search_products_nonce'     => wp_create_nonce( 'search-products' ),
					'search_customers_nonce'    => wp_create_nonce( 'search-customers' ),
					'search_categories_nonce'   => wp_create_nonce( 'search-categories' ),
					'search_pages_nonce'        => wp_create_nonce( 'search-pages' ),
				)
			);
			/* *** add selectWoo js *** */
			wp_enqueue_script( 'selectWoo.full.min.js', plugin_dir_url( __FILE__ ) . 'js/selectWoo/selectWoo.full.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'selectWoo.min.js', plugin_dir_url( __FILE__ ) . 'js/selectWoo/selectWoo.min.js', array( 'jquery' ), $this->version, false );
			/* *** add select2 js *** */
			wp_enqueue_script( 'select2.full.min.js', plugin_dir_url( __FILE__ ) . 'js/select2/select2.full.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'select2.min.js', plugin_dir_url( __FILE__ ) . 'js/select2/select2.min.js', array( 'jquery' ), $this->version, false );
			/* *** add meta-box js *** */
			wp_enqueue_script( 'meta-boxes.js', plugin_dir_url( __FILE__ ) . 'js/meta-boxes.js', array( 'jquery' ), $this->version, false );
			/* *** add tiptip js *** */
			wp_enqueue_script( 'jquery.tipTip.min.js', plugin_dir_url( __FILE__ ) . 'js/jquery-tiptip/jquery.tipTip.min.js', array( 'jquery' ), $this->version, false );
		}

		public function apffw_attach_main_page_template($page_template){
			$selected_plugin_page_id = get_option('bytes_plugin_page_id');
			$selected_plugin_page = get_page($selected_plugin_page_id);
			if(is_page($selected_plugin_page->post_name)){
			    $plugin_template_option = get_option( 'bytes_plugin_template' );
			    $page_template = APFFW_PLUGIN_DIR_PATH.'/public/partials/templates/bytes-woo-template/bytes-product-frontend-template.php';
			}
			return $page_template;
		}

		public function apffw_account_menu_items($menu_links){
			$menu_links = array_slice($menu_links, 0, 5, true)
			+ array('product-list' => 'Product List')
			+ array_slice($menu_links, 5, NULL, true);
			return $menu_links;
		}

		public function apffw_add_endpoint(){
			add_rewrite_endpoint('product-list', EP_PAGES);
			add_rewrite_endpoint('edit-product-form', EP_PAGES);
		}

		public function apffw_show_user_product_list(){
			require_once plugin_dir_path( dirname( __FILE__ ) ).'public/partials/bytes-myaccount-show-product-list.php';
		}
		public function apffw_show_product_list_pagination(){
			require_once plugin_dir_path( dirname( __FILE__ ) ).'public/partials/bytes-myaccount-show-product-list-pagination.php';
		}

		public function apffw_edit_user_product(){
			require_once plugin_dir_path( dirname( __FILE__ ) ).'public/partials/templates/bytes-woo-template/bytes-edit-product-frontend-template.php';
		}

		public function apffw_allow_customer_uploads(){
			if(is_user_logged_in()){
				global $current_user; // get the current user
				$role = $current_user->roles[0]; // display the current user's role		
				$upload_file_access = $role.'_upload';
				$upload_file_access = get_role($role);
				$upload_file_access->add_cap('upload_files');
			}
		}

		/* *** send email on product publish/update *** */
		public function apffw_publish_or_update_product($post){
		    $author_id = get_post_field('post_author', $post);
		    $author_email = sanitize_email(get_the_author_meta('user_email', $author_id));
		    if(!current_user_can('administrator')) return;
		    if(get_post_type($post) == 'product' && (sanitize_email(get_bloginfo('admin_email')) !== $author_email)){
		        /* Send mail on product publish/update */
		        $product_slug = get_post_field('post_name', $post);
		        $site_title = get_bloginfo('name');
		        $subject = "Publish/Update product";
		        $table_header = "Publish/Update product";
		        $product_title = get_the_title($post);
		        $product_price = !empty(get_post_meta($post, '_regular_price', true)) ? ('<span>'.get_woocommerce_currency_symbol().'</span>'.get_post_meta($post, '_regular_price', true)) : 'N/A';
		        $product_edit_link = '<a href="'.get_site_url().'/product/'.$product_slug.'" style="font-weight:normal;text-decoration:underline;color:#7f54b3" target="_blank">View Product</a>';
		        $headers = array('Content-Type: text/html; charset=UTF-8');
		        $product_message = 'Your product is publish/update on site <a href="'.get_site_url().'">'.get_site_url().'</a>';
		        require_once plugin_dir_path( dirname( __FILE__ ) ).'public/partials/emails/admin-new-product.php';
		        wp_mail($author_email, $subject, $body, $headers);
		    }
		}
	}
}