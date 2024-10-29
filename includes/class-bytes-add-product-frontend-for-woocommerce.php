<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 */
if(!class_exists('APFFW_Bytes_Add_Product_Frontend_For_Woocommerce')){
	class APFFW_Bytes_Add_Product_Frontend_For_Woocommerce {
		protected $loader;
		protected $plugin_name;
		protected $version;

		public function __construct() {
			if ( defined( 'APFFW_ADD_PRODUCT_FRONTEND_FOR_WOOCOMMERCE_VERSION' ) ) {
				$this->version = APFFW_ADD_PRODUCT_FRONTEND_FOR_WOOCOMMERCE_VERSION;
			}
			else {
				$this->version = '1.0.0';
			}
			$this->plugin_name = 'bytes-add-product-frontend-for-woocommerce';
			$this->apffw_load_dependencies();
			$this->apffw_set_locale();
			$this->apffw_define_admin_hooks();
			$this->apffw_define_public_hooks();
		}

		/**
		 * Load the required dependencies for this plugin.
		 */
		private function apffw_load_dependencies() {
			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bytes-add-product-frontend-for-woocommerce-loader.php';
			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bytes-add-product-frontend-for-woocommerce-i18n.php';
			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bytes-add-product-frontend-for-woocommerce-admin.php';
			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bytes-add-product-frontend-for-woocommerce-public.php';
			/* *** load function for product frontend tooltip *** */
			require_once plugin_dir_path( dirname( __FILE__ ) ).'public/inc/bytes-print-tooltip.php';
			/* *** load function for product attributes *** */
			require_once plugin_dir_path( dirname( __FILE__ ) ).'public/inc/bytes-product-attributes.php';
			/* *** load function for product save *** */
			require_once plugin_dir_path( dirname( __FILE__ ) ).'public/inc/bytes-save-product.php';
			/* *** load function for delete product *** */
			require_once plugin_dir_path( dirname( __FILE__ ) ).'public/inc/bytes-delete-product.php';
			$this->loader = new APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Loader();
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 */
		private function apffw_set_locale() {
			$plugin_i18n = new APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_i18n();
			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'apffw_load_plugin_textdomain' );
		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 */
		private function apffw_define_admin_hooks() {
			$plugin_admin = new APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );
			
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'apffw_enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'apffw_enqueue_scripts' );
			$this->loader->add_filter("plugin_action_links_".APFFW_BASENAME, $plugin_admin, "apffw_settings_link_bytes_add_product_frontend_for_woocommerce", 10, 1); // add plugin setting link
			$this->loader->add_action('admin_menu', $plugin_admin, 'apffw_custom_menu_page_bytes_add_product_frontend_for_woocommerce'); // admin menu page
			$this->loader->add_action('admin_init',$plugin_admin, 'apffw_frontend_product_management_admin_ajax');
			$this->loader->add_action('admin_init',$plugin_admin, 'apffw_frontend_product_display_options');
			$this->loader->add_action('admin_init',$plugin_admin, 'apffw_add_author_support');
			$this->loader->add_action('admin_init',$plugin_admin, 'apffw_set_permalink_structure');
			$this->loader->add_filter("manage_product_posts_columns", $plugin_admin, "apffw_add_product_columns", 10, 1); // add product columns
			$this->loader->add_action('manage_product_posts_custom_column',$plugin_admin, 'apffw_add_custom_product_columns', 10, 2); // get data of product_status columns
		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 */
		private function apffw_define_public_hooks() {
			$plugin_public = new APFFW_Bytes_Add_Product_Frontend_For_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'apffw_enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'apffw_enqueue_scripts' );
			$this->loader->add_filter('page_template', $plugin_public, 'apffw_attach_main_page_template', 10, 1); // add page template
			$this->loader->add_filter('woocommerce_account_menu_items', $plugin_public, 'apffw_account_menu_items', 10, 1); // add account menu items
			$this->loader->add_action( 'wp_loaded', $plugin_public, 'apffw_add_endpoint' ); // register permalink endpoint
			$this->loader->add_action( 'woocommerce_account_product-list_endpoint', $plugin_public, 'apffw_show_user_product_list' ); // show product list
			$this->loader->add_action( 'wp_loaded', $plugin_public, 'apffw_show_product_list_pagination' ); // show product list pagination
			$this->loader->add_action( 'woocommerce_account_edit-product-form_endpoint', $plugin_public, 'apffw_edit_user_product' ); // show edit product form
			$this->loader->add_action('wp_loaded',$plugin_public, 'apffw_allow_customer_uploads'); // allow user to Upload Media
			$this->loader->add_action('save_post',$plugin_public, 'apffw_publish_or_update_product'); // notify user when publish or update product
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}
	}
}