<?php
//Menu for admins displays and hooks
class withinweb_wwkc_keycodes_admin_display {

	//---------------------------------------
    //Initialise menu
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_settings_menu'));
    }
	//---------------------------------------
	//add admin settings page
    public static function add_settings_menu() {
		//Create the menus
		add_menu_page( "PHP-KeyCodes", 'KeyCodes', 'manage_options', __FILE__, array(__CLASS__, 'withinweb_wwkc_keycodes_about'), 'dashicons-admin-network');
			
		//__CLASS__ returns the class name so __CLASS__.'_settings returns withinweb_wwkc_keycodes_admin_display_settings which is the slug name
		//array(__CLASS__, 'withinweb_wwkc_keycodes_general_settings') is a way of calling withinweb_wwkc_keycodes_general_settings in the class
		
		add_submenu_page(__FILE__, 'Setting for PHP-KeyCodes', __('Settings','withinweb-wwkc-keycodes'), 'manage_options', __CLASS__.'_settings', array(__CLASS__, 'withinweb_wwkc_keycodes_general_settings'));
		add_submenu_page(__FILE__, 'Create KeyCode Item', __('Create Item','withinweb-wwkc-keycodes'), 'manage_options', __CLASS__.'_createitem', array(__CLASS__, 'withinweb_wwkc_keycodes_createitem'));
		add_submenu_page(__FILE__, 'KeyCode Item List', __('Item List','withinweb-wwkc-keycodes'), 'manage_options', __CLASS__.'_listitems', array(__CLASS__, 'withinweb_wwkc_keycodes_listitems'));	
		add_submenu_page(__FILE__, 'PHP-KeyCodes Sales', __('Sales','withinweb-wwkc-keycodes'), 'manage_options', __CLASS__.'_sales', array(__CLASS__, 'withinweb_wwkc_keycodes_sales'));
		add_submenu_page(__FILE__, 'PayPal IPN List', __('IPN Details','withinweb-wwkc-keycodes'), 'manage_options', 'edit.php?post_type=paypal_ipn' );		
		//Link to upgrade page (not needed on Premium Version)
		add_submenu_page(__FILE__, 'Upgrade To Premium', '<strong style="color: #FCB214;">Upgrade To Premium</strong>', 'manage_options', __CLASS__.'_premium', array(__CLASS__, 'withinweb_wwkc_keycodes_premium') );
		
		// This is the hidden page for displaying the button code.  It is not displayed as part of the menu system
		add_submenu_page(null, 'Test an item', 'Local Test', 'manage_options', __CLASS__.'_localtest', array(__CLASS__, 'withinweb_wwkc_keycodes_localtest'));		
		add_submenu_page(null, 'Product button code', 'Product button code', 'manage_options', __CLASS__.'_buttoncode', array(__CLASS__, 'withinweb_wwkc_keycodes_buttoncode'));
		add_submenu_page(null, 'Edit Item', 'Edit Item', 'manage_options', __CLASS__.'_edititem', array(__CLASS__, 'withinweb_wwkc_keycodes_edititem'));
		add_submenu_page(null, 'Delete Item', 'Delete Item', 'manage_options', __CLASS__.'_deleteitem', array(__CLASS__, 'withinweb_wwkc_keycodes_deleteitem'));
		add_submenu_page(null, 'Sales Details', 'Sales Details', 'manage_options', __CLASS__.'_salesdetails', array(__CLASS__, 'withinweb_wwkc_keycodes_salesdetails'));
    }
	
	//---------------------------------------
	//Trigger hooks for about
    public static function withinweb_wwkc_keycodes_about() {		
		do_action('withinweb_wwkc_keycodes_about_display');
    }	

	//---------------------------------------
	//Trigger hooks for about
    public static function withinweb_wwkc_keycodes_premium() {		
		do_action('withinweb_wwkc_keycodes_premium_display');
    }	
	
	//---------------------------------------	
	//Trigger hooks for option setiings
    public static function withinweb_wwkc_keycodes_general_settings() { 
		do_action('withinweb_wwkc_keycodes_general_setting_save_field');		
		do_action('withinweb_wwkc_keycodes_general_setting_display');
    }

	//---------------------------------------	
	//Trigger hooks for create items
    public static function withinweb_wwkc_keycodes_createitem() { 
		do_action('withinweb_wwkc_keycodes_createitem_save_field');		
		do_action('withinweb_wwkc_keycodes_createitem_display');
    }	

	//---------------------------------------
	//Trigger hooks for list item
    public static function withinweb_wwkc_keycodes_listitems() {
		do_action('withinweb_wwkc_keycodes_listitems_display');
    }		

	//---------------------------------------	
	//Trigger for sales
	 public static function withinweb_wwkc_keycodes_sales() {		
		do_action('withinweb_wwkc_keycodes_sales_display');
    }

	//---------------------------------------	
	//Trigger for sales details
	 public static function withinweb_wwkc_keycodes_salesdetails() {		
		do_action('withinweb_wwkc_keycodes_salesdetails_display');
    }
	
	//---------------------------------------	
	//Trigger for Local Test
	 public static function withinweb_wwkc_keycodes_localtest() {
		do_action('withinweb_wwkc_keycodes_localtest_update');				 
		do_action('withinweb_wwkc_keycodes_localtest_display');
    }
	
	//---------------------------------------	
	//Trigger hooks for button code page which is not available in main menu
	public static function withinweb_wwkc_keycodes_buttoncode() {
		do_action('withinweb_wwkc_keycodes_buttoncode_display');
	}	
	
	//---------------------------------------	
	//Trigger hooks for button code page which is not available in main menu
	public static function withinweb_wwkc_keycodes_edititem() {
		do_action('withinweb_wwkc_keycodes_edititem_save_field');
		do_action('withinweb_wwkc_keycodes_edititem_display');
	}	
	
	//---------------------------------------	
	//Trigger hooks for button code page which is not available in main menu
	public static function withinweb_wwkc_keycodes_deleteitem() {
		do_action('withinweb_wwkc_keycodes_deleteitem_save_field');
		do_action('withinweb_wwkc_keycodes_deleteitem_display');
	}		
	
	/*
    public static function display_short_content($content, $numberOfWords = 10) {
        if( isset($content) && !empty($content) ) {
            $contentWords = substr_count($content," ") + 1;
            $words = explode(" ",$content,($numberOfWords+1));
            if( $contentWords > $numberOfWords ){
                $words[count($words) - 1] = '...';
            }
            $excerpt = join(" ",$words);
            return $excerpt;
        }
    }
	*/

}

withinweb_wwkc_keycodes_admin_display::init();
