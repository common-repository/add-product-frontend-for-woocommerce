<?php
    $bytes_product_list = get_admin_url()."admin.php?page=bytes-product-list"; 

    $ListTable = new APFFW_prod_listing();
    $ListTable->prepare_items(); 
?>
<div class="wrap custom-user-wrap">
    <hr class="wp-header-end"> 
    <h1 class="wp-heading-inline"><?php esc_html_e('Add Product Frontend', 'bytes_product_frontend'); ?></h1>
    <div id="tabs" style="padding-bottom: 25px;">
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo esc_url( $bytes_product_list ); ?>" id="switch_tabs_general_section" class="nav-tab  nav-tab-active"><?php esc_html_e('Product List', 'bytes_product_frontend'); ?></a>
        </h2>
    </div>
    
    <div class="entry-edit">
        <div class="postbox">
            <div class="inside">
                <div class="main">
                     <form method="get" class="posts-filter" action="http://192.168.10.136/filenow/wp-admin/admin.php">
                        <input type="hidden" name="page" value="bytes-product-list">
                        <?php //$ListTable->get_views(); ?>
                        <?php $ListTable->search_box(__( 'Search', 'bytes_product_frontend' ), 'search'); ?>
                        <?php $ListTable->display(); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php


if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class APFFW_prod_listing extends WP_List_Table
{
    public $post_per_page = 10;

    public function __construct() {
        parent::__construct(
            array(
                'singular' => 'product',
                'plural'   => 'products',
                'ajax'     => false
            )
        );
    }

    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items() {
        global $wpdb;
        $this->process_bulk_action();
        
        $columns     = $this->get_columns();
        $hidden      = $this->get_hidden_columns();
        $sortable    = $this->get_sortable_columns();
        $data        = $this->table_data();

       
        $currentPage = $this->get_pagenum();

        $search = empty( $_REQUEST['s'] ) ? false :  esc_sql( $_REQUEST['s'] );

        $prtltArg = array(
                'post_type' => 'product',
                'post_status' => array('publish', 'draft'),
                'posts_per_page' => -1,
            );

         $prtltArg['meta_query'][] = array(
                        'key' => 'product_status',
                            'value' => 1,
                            'compare' => '==',
                        );

        if( $search ){
            $prtltArg['s'] = $search;
        }

        
        $productCnt =  get_posts( $prtltArg );
        $total_items = count( $productCnt ) ? count( $productCnt ) : 0;

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $this->post_per_page,
            'total_pages' => ceil( $total_items / $this->post_per_page ) // use ceil to round up
        ) );

        $this->_column_headers = array($columns, $hidden ,$sortable);
        $this->items = $data;
    }
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {   
        $columns = array(
            'cb'    => '<input type="checkbox" />',
            'thumbnail'  => __( 'Image', 'bytes_product_frontend' ),
            'name'  => __( 'Product Name ', 'bytes_product_frontend' ),
            'sku'   => __( 'SKU ', 'bytes_product_frontend' ),
            'price' => __( 'Price ', 'bytes_product_frontend' ),
            'author'=> __( 'Author ', 'bytes_product_frontend' ),
            'status'=> __( 'Status ', 'bytes_product_frontend' ),
        );
        return $columns;
    }
    /**
     * Define check box for bulk action (each row)
     * @param  $item
     * @return checkbox
     */
    public function column_cb($item){
        return sprintf(
             '<input type="checkbox" name="%1$s[]" value="%2$s" />',
             $this->_args['singular'],
             $item['cb']
        );
    }
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns() {
        return  array('form_id');
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        $sortableFields = array(
                'name' => array( 'name', true ),
                'price' => array( 'price', true ),
            );
        return $sortableFields;
    }
    /**
     * Define bulk action
     * @return Array
     */
    public function get_bulk_actions() {
        return array(
            'delete' => __( 'Move to Trash', 'bytes_product_frontend' )
        );

    }
    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = array();
        global $wpdb;
        
        $paged   = $this->get_pagenum();
        $search  = empty( $_REQUEST[ 's' ] ) ? false :  esc_sql( $_REQUEST[ 's' ] );

        $orderby = isset( $_GET[ 'orderby' ] ) ? esc_sql( $_GET[ 'orderby' ] ) : "";
        $order   = isset( $_GET[ 'order' ] ) ? esc_sql( $_GET[ 'order' ] ) : "";
        $author = "";
        
        $args = array(
                'post_type'         => 'product',
                'post_status'       => array( 'publish', 'draft' ),
                'posts_per_page'    => $this->post_per_page,
                'paged'             => $paged,
        );

        $args['meta_query'][] = array(
                        'key' => 'product_status',
                            'value' => 1,
                            'compare' => '==',
                        );
        

        $usr_role   = isset( $_GET[ 'apffw_user_roles' ] ) ? esc_sql( $_GET[ 'apffw_user_roles' ] ) : "";
        $usr_id   = isset( $_GET[ 'apffw_user_id' ] ) && $_GET[ 'apffw_user_id' ] != -1 ? esc_sql( $_GET[ 'apffw_user_id' ] ) : "";

         if( !$usr_id && $usr_role ){
            $users = get_users( array(
                'role'   => $usr_role, // Specify the role you want to query
                'fields' => 'ID'      // Retrieve only user IDs
            ) );
            $args[ 'author__in' ] = $users; 
        } elseif($usr_role){
            $users = ($usr_id > 0) ? $usr_id : "";
            $args[ 'author' ] = $users; 
        } else{
            $users = ($usr_id > 0) ? $usr_id : "";
            $args[ 'author' ] = $users; 
        }

        // Search
        if( $search ) { $args[ 's' ] = $search; }
        
        // Sorting
        if( ! empty( $orderby ) && ! empty( $order ) ) {
            if( $orderby ==  'price'){
                $args[ 'orderby' ]  = 'meta_value_num';
                $args[ 'meta_key' ]  = '_price';
                $args[ 'order' ]  = $order;
            } else{
                 $args[ 'order' ]  = $order;
                $args[ 'orderby' ]  = $orderby;
            }
        }

        $the_query = new WP_Query( $args );
        
        while ( $the_query->have_posts() ) : $the_query->the_post();
        
            $prodId  = get_the_id();
            $product = wc_get_product( $prodId );
            
            $title   = esc_html__( sanitize_title( $product->get_name() ), 'bytes_product_frontend');
            
            $link        = "<a href='%s' data-id='%s' class='prod-links row-title'>%s</a>";
            $placeholder = wc_placeholder_img_src();
            $image       = wp_get_attachment_image_src( get_post_thumbnail_id( $prodId ) );

            $prodImg     = "<img height='50' width='50' src='%s' data-id='".esc_attr( $prodId )."'>"; 
            
            $data_value[ 'cb' ]           = $prodId;
            $data_value[ 'thumbnail' ]    = sprintf( $prodImg, !empty( $image[0]) ? $image[0] : $placeholder );
            $data_value[ 'name' ]         = sprintf( $link, get_edit_post_link() , absint(get_the_id()), $title);
            $data_value[ 'author' ]       = get_the_author();
            $data_value[ 'price' ]        = wp_kses_post( $product->get_price_html() );
            $data_value[ 'status' ]       = get_post_status();
            $data_value[ 'sku' ]          = $product->get_sku() ? $product->get_sku() : "–";

            $data[] = $data_value;
        endwhile;
        return $data;
    }

    /**
     * Define bulk action
     *
     */
    public function process_bulk_action(){
        global $wpdb;
        $action = $this->current_action();

        if ( !empty( $action ) ) {
            $nonce        = isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : '';
            $nonce_action = 'bulk-' . $this->_args['plural'];
            if ( !wp_verify_nonce( $nonce, $nonce_action ) ){
                wp_die( 'Nonce failed to validate' );
            }
        }

        if( 'delete' === $action ) {
            foreach ( $_GET[ 'product' ] as $prdId ):
                wp_trash_post( $prdId );
            endforeach;
        }
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        return isset( $item[ $column_name ] ) ? $item[ $column_name ]: '';
    }

    /**
     * Display the bulk actions dropdown.
     *
     * @since 3.1.0
     * @access protected
     *
     * @param string $which The location of the bulk actions: 'top' or 'bottom'.
     *                      This is designated as optional for backward compatibility.
     */
    protected function bulk_actions( $which = '' ) {
        if ( is_null( $this->_actions ) ) {
            $this->_actions = $this->get_bulk_actions();
                
            $this->_actions = apply_filters( "bulk_actions-{$this->screen->id}", $this->_actions );
            $two = '';
        } else {
            $two = '2';
        }

        if ( empty( $this->_actions ) )  return;

        echo '<label for="bulk-action-selector-' . esc_attr( $which ) . '" class="screen-reader-text">' . __( 'Select bulk action', 'bytes_product_frontend' ) . '</label>';
        echo '<select name="action' . $two . '" id="bulk-action-selector-' . esc_attr( $which ) . "\">\n";
        echo '<option value="-1">' . __( 'Bulk Actions', 'bytes_product_frontend' ) . "</option>\n";

        foreach ( $this->_actions as $name => $title ) {
            $class = 'edit' === $name ? ' class="hide-if-no-js"' : '';

            echo "\t" . '<option value="' . $name . '"' . $class . '>' . $title . "</option>\n";
        }

        echo "</select>\n";
            
       

        submit_button( __( 'Apply', 'bytes_product_frontend' ), 'action', '', false, array( 'id' => "doaction$two" ) );
        echo "\n";
        $nonce = wp_create_nonce( 'dnonce' );
    }

      public function extra_tablenav( $which ) {

        if ( 'top' === $which ) {  global $wp_roles;

         $all_roles = $wp_roles->roles;
         echo '<label for="bulk-action-selector-' . esc_attr( $which ) . '" class="screen-reader-text">' . __( 'Select bulk action', 'bytes_product_frontend' ) . '</label>';
        echo '<select name="apffw_user_roles" class="apffw_user_roles" id="bulk-action-selector-' . esc_attr( $which ) . "\">\n";
        echo '<option value="">' . __( 'User Role', 'bytes_product_frontend' ) . "</option>\n";

        foreach ( $all_roles as $name => $title ) {
            $class = 'edit' === $name ? ' class="hide-if-no-js"' : '';
            $selected = (isset($_GET['apffw_user_roles']) && $_GET['apffw_user_roles'] == $name) ? ' selected="selected"' : "";

            echo "\t" . '<option value="' . $name . '"' . $class . $selected . ' >' . $name . "</option>\n";
        }

        echo "</select>\n";

        echo '<label for="bulk-action-selector-' . esc_attr( $which ) . '" class="screen-reader-text">' . __( 'Select bulk action', 'bytes_product_frontend' ) . '</label>';
        
        echo '<select id="bulk-action-selector-tesd" name="apffw_user_id" class="apffw_user_lists' . "\">\n";
        echo '<option value="">' . __( 'User', 'bytes_product_frontend' ) . "</option>\n";

        if( isset( $_REQUEST['apffw_user_roles'] ) ){
            $users =    get_users( array( 'role__in' => array( sanitize_text_field( $_REQUEST['apffw_user_roles'] ) ) ) );
            foreach( $users as $user ){
                $selected = ( isset( $_REQUEST['apffw_user_id'] ) && $_REQUEST['apffw_user_id'] == $user->ID ) ? ' selected="selected"' : "";
                echo "\t" . '<option value="' . $user->ID . '"' . $class . $selected . ' >' . $user->data->user_login . "</option>\n";
                
            }
        }
        echo "</select>\n";
        
        submit_button( __( 'Apply', 'bytes_product_frontend' ), 'action', '', false, array( 'id' => "doaction" ) );
        }
    }

    public function column_row_actions( $item ) {
        $actions = array(
            'edit'   => sprintf( '<a href="#">Edit</a>', $item['name'] ),
            'delete' => sprintf( '<a href="#">Delete</a>', $item['name'] )
        );
        return $this->row_actions( $actions );
    }

    public function column_name( $item ) {
        $actions = array(
            'edit'   => sprintf( '<a href="%s">Edit</a>', get_edit_post_link($item['cb']), $item['name'] ),
            'delete' => sprintf( '<a href="%s">Delete</a>', get_delete_post_link($item['cb']), $item['name'] )
        );
        return $item['name'] . $this->row_actions( $actions );
    }
}