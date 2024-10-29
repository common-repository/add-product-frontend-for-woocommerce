<?php
/**
 * The admin-specific functionality of the plugin.
 */

if(!class_exists('APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Admin')){
	class APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Admin {
		private $plugin_name;
		private $version;

		public function __construct( $plugin_name, $version ) {
			$this->plugin_name = $plugin_name;
			$this->version = $version;
		}

		/**
		 * Register the stylesheets for the admin area.
		 */
		public function apffw_enqueue_styles() {
			$current_bytes_page = isset( $_GET['page'] ) ? wc_clean( wp_unslash( $_GET['page'] ) ) : false;
			if($current_bytes_page == "bytes-product-frontend-info" || $current_bytes_page == "bytes-product-frontend-setting" || $current_bytes_page == "bytes-product-list"){
				wp_enqueue_style( $this->plugin_name.'_bootstrap.min.css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' ); // load bootstrap css
				wp_enqueue_style( $this->plugin_name.'_custom.admin.css', plugin_dir_url( __FILE__ ) . 'css/custom-admin.css', array(), $this->version, 'all' ); // load custom css
			}
		}

		/**
		 * Register the JavaScript for the admin area.
		 */
		public function apffw_enqueue_scripts() {
			$current_bytes_page = isset( $_GET['page'] ) ? wc_clean( wp_unslash( $_GET['page'] ) ) : false;
			if($current_bytes_page == "bytes-product-frontend-info" || $current_bytes_page == "bytes-product-frontend-setting" || $current_bytes_page == "bytes-product-list"){			
				wp_enqueue_script( 'bytes-add-product-frontend-for-woocommerce-admin.js', plugin_dir_url( __FILE__ ) . 'js/bytes-add-product-frontend-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );
				wp_localize_script( 'bytes-add-product-frontend-for-woocommerce-admin.js', 'save_admin_product', array( 
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				));
				wp_enqueue_script( $this->plugin_name.'_bootstrap.min.js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array('jquery'), $this->version, true ); // load bootstrap JS
			}
		}

		/* *** load plugin setting link *** */
		public function apffw_settings_link_bytes_add_product_frontend_for_woocommerce($setting_links){
			$setting_links[] = '<a href="' .
			    esc_url(admin_url('admin.php?page=bytes-product-frontend-info')) .
			    '">' . __('Settings', 'bytes_product_frontend') . '</a>';
			return $setting_links; 
		}

		/* *** load admin menu page *** */
		public function apffw_custom_menu_page_bytes_add_product_frontend_for_woocommerce(){
			//The icon in Base64 format
			$icon_base64 = 'PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCAzMiA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xOS43NzIzIDMuNjE2NDZMMTYuMTc3MSAwTDEyLjUzODcgMy42NTk4N0wxNC40MTA2IDUuNTQyODVMOS41IDYuODY2NDRMMTAuODMxNyAxMS44NjU5TDEzLjM4ODMgMTEuMTc2OEwxMi4wNzI2IDE2LjExNkwxNy4wNDI3IDE3LjQ1NTZMMTcuNzI3OSAxNC44ODM0TDIxLjMyMjQgMTguNDk5MkwyNC45NjA3IDE0LjgzOTNMMjMuMDg5NSAxMi45NTdMMjguMDAwMSAxMS42MzM0TDI2LjY2ODQgNi42MzM5NEwyNC4xMTE4IDcuMzIzMDNMMjUuNDI3NSAyLjM4Mzg0TDIwLjQ1NzQgMS4wNDQyM0wxOS43NzIzIDMuNjE2NDZaTTEzLjUwODIgMTEuMTQ0NUwxOC4zNzQ0IDEyLjQ1NjJMMTcuNzYgMTQuNzYyOUwyMS4zMjI0IDExLjE3OTRMMjMuMDAyNSAxMi44Njk1TDIxLjY5ODQgNy45NzM1NEwyMy45OTIgNy4zNTUzM0wxOS4xMjU3IDYuMDQzNzFMMTkuNzQwNyAzLjczNTA1TDE2LjE3NzEgNy4zMTk3NEwxNC40OTc2IDUuNjMwMzVMMTUuODAxOCAxMC41MjYzTDEzLjUwODIgMTEuMTQ0NVoiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik0xMC4zNzUgMjguNjg3N0gyNy4zMTI3QzI3LjczMjEgMjguNjg3NyAyOC4xIDI4LjQxMDMgMjguMjEzNSAyOC4wMDc1TDMxLjk2MzggMTQuODgyM0MzMi4wNDQzIDE0LjU5OTQgMzEuOTg5MiAxNC4yOTU0IDMxLjgxMTcgMTQuMDYwM0MzMS42MzM5IDEzLjgyNTkgMzEuMzU3NiAxMy42ODc1IDMxLjA2MjcgMTMuNjg3NUg4LjIxMDY3TDcuNTQwNTEgMTAuNjcxOUM3LjQ0NTI5IDEwLjI0MjQgNy4wNjQ0MyA5LjkzNzUgNi42MjQ5OCA5LjkzNzVIMC45Mzc0OTdDMC40MTk0MzIgOS45Mzc1IDAgMTAuMzU2OSAwIDEwLjg3NUMwIDExLjM5MzMgMC40MTk0MzIgMTEuODEyNSAwLjkzNzQ5NyAxMS44MTI1SDUuODcyNTRMOS4yNTgwMyAyNy4wNDY4QzguMjYxOTQgMjcuNDc5OSA3LjU2MjQ4IDI4LjQ3MTQgNy41NjI0OCAyOS42MjUyQzcuNTYyNDggMzEuMTc2IDguODI0MTkgMzIuNDM3NyAxMC4zNzUgMzIuNDM3N0gyNy4zMTI3QzI3LjgzMSAzMi40Mzc3IDI4LjI1MDIgMzIuMDE4NSAyOC4yNTAyIDMxLjUwMDJDMjguMjUwMiAzMC45ODIxIDI3LjgzMSAzMC41NjI3IDI3LjMxMjcgMzAuNTYyN0gxMC4zNzVDOS44NTg2MSAzMC41NjI3IDkuNDM3NDcgMzAuMTQyNSA5LjQzNzQ3IDI5LjYyNTJDOS40Mzc0NyAyOS4xMDc5IDkuODU4NjEgMjguNjg3NyAxMC4zNzUgMjguNjg3N1YyOC42ODc3WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTkuNDM3NSAzNS4yNUM5LjQzNzUgMzYuODAxIDEwLjY5OTIgMzguMDYyNSAxMi4yNTAyIDM4LjA2MjVDMTMuODAxIDM4LjA2MjUgMTUuMDYyNyAzNi44MDEgMTUuMDYyNyAzNS4yNUMxNS4wNjI3IDMzLjY5OTIgMTMuODAxIDMyLjQzNzUgMTIuMjUwMiAzMi40Mzc1QzEwLjY5OTIgMzIuNDM3NSA5LjQzNzUgMzMuNjk5MiA5LjQzNzUgMzUuMjVaIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNMjIuNjI1IDM1LjI1QzIyLjYyNSAzNi44MDEgMjMuODg2NyAzOC4wNjI1IDI1LjQzNzUgMzguMDYyNUMyNi45ODg1IDM4LjA2MjUgMjguMjUgMzYuODAxIDI4LjI1IDM1LjI1QzI4LjI1IDMzLjY5OTIgMjYuOTg4NSAzMi40Mzc1IDI1LjQzNzUgMzIuNDM3NUMyMy44ODY3IDMyLjQzNzUgMjIuNjI1IDMzLjY5OTIgMjIuNjI1IDM1LjI1WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg==';
			//The icon in the data URI scheme
			$icon_data_uri = 'data:image/svg+xml;base64,'.$icon_base64;
			add_menu_page('Add products Frontend',
			    'Add products Frontend',
			    'manage_options',
			    'bytes-product-frontend-info',
			    array($this, 'apffw_info_bytes_add_product_frontend_for_woocommerce'),
			    $icon_data_uri,
			    59
			);
			
			
			add_submenu_page('bytes-product-frontend-info', 'Product List', 'Product List', 'manage_options', 'bytes-product-list', array($this, 'apffw_bytes_product_list_for_woocommerce'), null);
			add_submenu_page('bytes-product-frontend-info', 'Settings', 'Settings', 'manage_options', 'bytes-product-frontend-setting', array($this, 'apffw_setting_bytes_add_product_frontend_for_woocommerce'), null);
			add_submenu_page('bytes-product-frontend-info', 'About plugin', 'About plugin', 'manage_options', 'bytes-product-frontend-info');
		}

		public function apffw_info_bytes_add_product_frontend_for_woocommerce(){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/plugin-info-bytes-add-product-frontend.php';
		}

		public function apffw_setting_bytes_add_product_frontend_for_woocommerce(){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/plugin-setting-bytes-add-product-frontend.php';
		}

		public function apffw_bytes_product_list_for_woocommerce(){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/plugin-bytes-product-list.php';
		}

		/*
	     * Ajax file include
	     */ 
		public function apffw_frontend_product_management_admin_ajax(){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/inc/ajax.php';
		}

		/*
	     * Display option
	     */ 
		public function apffw_frontend_product_display_options(){
			/* section name, display name, callback to print description of section, page to which section is attached */
	        add_settings_section("header_section", "", array($this, "apffw_display_header_options_content"), "plugin-options");

	        /* setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
	           last field section is optional */
	        add_settings_field("frontend_product_additional_fields", "Product Fields", array($this, "apffw_display_additional_field_bytes_product_frontend_form"), "plugin-options", "header_section");
	        add_settings_field("frontend_product_status", "Product Status", array($this, "apffw_display_product_status_field_bytes_product_frontend_form"), "plugin-options", "header_section");
	        add_settings_field("frontend_product_user_role", "Select user role which can create or edit product", array($this, "apffw_display_user_role_field_bytes_product_frontend_form"), "plugin-options", "header_section");

	        /* section name, form element name, callback for sanitization */
	        register_setting("header_section", "frontend_product_additional_fields");
	        register_setting("header_section", "frontend_product_status");
	        register_setting("header_section", "frontend_product_user_role");
		}

		/* *** add author support in admin *** */
		public function apffw_add_author_support(){
			add_post_type_support('product', 'author');
		}

		/* *** set permalink *** */
		public function apffw_set_permalink_structure(){
			if(get_option('bytes_permalink_update') == '1'){
				global $wp_rewrite;
				$wp_rewrite->set_permalink_structure('/%postname%/');
				$wp_rewrite->flush_rules();
				update_option('bytes_permalink_update', '2');
			}
		}

		public function apffw_display_header_options_content(){ 
			echo "<div class='postbox-header'><h2 class='hndle ui-sortable-handle'><span>Fields Options</span></h2></div>"; 
			echo '<div class="inside apffw-woo"><div class="main">';
		}
		public function apffw_display_additional_field_bytes_product_frontend_form(){
		
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/setting/plugin-setting-additional-fields.php';
		}

		public function apffw_display_product_status_field_bytes_product_frontend_form(){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/setting/plugin-setting-product-status.php';	
		}

		public function apffw_display_user_role_field_bytes_product_frontend_form(){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/setting/plugin-setting-user-role.php';
			echo '</div></div>';
		}

		/* *** add columns to Product List *** */
		public function apffw_add_product_columns($columns){
		   return array_merge($columns, array( 
		   		'product_status' => __('Product Status', 'bytes_product_frontend'),
		   ));
		}
		/* *** add checkbox to Post List *** */
		function apffw_add_custom_product_columns($column, $post_id){
		  switch($column){
		    case 'product_status':
		      $product_status = get_post_meta($post_id, 'product_status', true); 
		      $msg = ($product_status == 1) ? "Frontend product" : "-";
		      esc_html_e($msg, 'bytes_product_frontend');
		      break;
		  }
		}
	}
}