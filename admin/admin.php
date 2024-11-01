<?php
//--------------------------------------------
//Admin pages
class withinweb_wwkc_keycodes_Admin {

    private $plugin_name;		//The name of this plugin.    
    private $version;			//The version of this plugin. 

	//--------------------------------------------
    //Comstructor
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();
    }
	//--------------------------------------------
    //Register the stylesheets for the Dashboard.
    public function enqueue_styles() {
      	//  An instance of this class should be passed to the run() function
      	//  defined in withinweb_wwkc_keycodes_Admin_Loader as all of the hooks are defined
      	//  in that particular class.
      	// 
      	//  The withinweb_wwkc_keycodes_Admin_Loader will then create the relationship
      	//  between the defined hooks and the functions defined in this
      	//  class.
        $screen = get_current_screen();
        if ((isset($screen->id) && ($screen->id == 'paypal_ipn' || $screen->id == 'settings_page_paypal-ipn-for-wordpress-option')) && (isset($screen->base) && ($screen->base == 'post' || $screen->id == 'settings_page_paypal-ipn-for-wordpress-option' ))) {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/withinweb_wwkc_keycodes_admin.css', array(), $this->version, 'all');
        }
    }
	//--------------------------------------------
    public function admin_enqueue_scripts() {       
        global $post_type;
        if ($post_type == "paypal_ipn") {
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/withinweb_wwkc_keycodes_admin.js', array('jquery'), $this->version, true);
        }
    }
	//--------------------------------------------
    private function load_dependencies() {
		
		if ( is_admin() )		//Check if admin user
		{
		//IPN post types.
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/posttypes.php';
		}
		
		if ( is_admin() )		//Check if admin user
		{
        //admin set up
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/admindisplay.php';
		}
		
		if ( is_admin() )		//Check if admin user
		{		
		//display Html element
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/htmloutput.php';
		}
		
		if ( is_admin() )		//Check if admin user
		{
        //general settings tab
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/settings.php';
		}

		if ( is_admin() )		//Check if admin user
		{
        //create item page
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/createitem.php';
		}
		
		if ( is_admin() )		//Check if admin user
		{
        //create list item page
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/listitems.php';
		}		

		if ( is_admin() )		//Check if admin user
		{
       	//Sales
       	require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/sales.php';		
		}

		if ( is_admin() )		//Check if admin user
		{
       	//Sales details
       	require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/salesdetails.php';
		}		
		
		if ( is_admin() )		//Check if admin user
		{
       	//Local Test
       	require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/localtest.php';		
		}
		
		if ( is_admin() )		//Check if admin user
		{
       	//button code
       	require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/buttoncode.php';
		}

		if ( is_admin() )		//Check if admin user
		{
       	//edititem code
       	require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/edititem.php';
		}

		if ( is_admin() )		//Check if admin user
		{
       	//delete item
       	require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/deleteitem.php';
		}
		
		if ( is_admin() )		//Check if admin user
		{
       	//about page
       	require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/about.php';
		}
		
		if ( is_admin() )		//Check if admin user
		{
       	//premium plugin page
       	require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/premium.php';
		}		
		
    }
	//--------------------------------------------
    //modify wordpress search query
    public function withinweb_wwkc_keycodes_modify_wp_search($where) {

        global $wpdb, $wp;

        if (isset($_GET['s']) && !empty($_GET['s'])) {
            if (is_search() && isset($_GET['post_type']) && $_GET['post_type'] == 'paypal_ipn') {
                $where = preg_replace(
                        "/($wpdb->posts.post_title (LIKE '%{$wp->query_vars['s']}%'))/i", "$0 OR ( $wpdb->postmeta.meta_value LIKE '%{$wp->query_vars['s']}%' )", $where
                );
                add_filter('posts_join_request', array(__CLASS__, 'withinweb_wwkc_keycodes_modify_wp_search_join'));
                add_filter('posts_distinct_request', array(__CLASS__, 'withinweb_wwkc_keycodes_modify_wp_search_distinct'));
            }
        }

        return $where;
    }
	//--------------------------------------------
    //wordpress join search query
    public static function withinweb_wwkc_keycodes_modify_wp_search_join($join) {

        global $wpdb;

        return $join .= " LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id) ";
    }
	//--------------------------------------------
    //wordpress distinct search query
    public static function withinweb_wwkc_keycodes_modify_wp_search_distinct($distinct) {

        return 'DISTINCT';
    }
    //--------------------------------------------
    //View link goes to 404 Not Found Issue #69
    public function withinweb_wwkc_keycodes_remove_row_actions($actions, $post) {
        global $current_screen;
        if( $current_screen->post_type == 'paypal_ipn' ) {
            unset( $actions['view'] );
            unset( $actions['inline hide-if-no-js'] );
        }
        return $actions;
    }
    //--------------------------------------------    
    public function withinweb_wwkc_keycodes_remove_postmeta($pid) {
        global $wpdb;
        if( get_post_type($pid) == 'paypal_ipn' || get_post_type($pid) == 'ipn_history' ) {
            if ( $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE post_id = %d", $pid ) ) ) {
                $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->postmeta} WHERE post_id = %d", $pid ) );
            }
        }
    }
}