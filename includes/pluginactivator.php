<?php
//Plug in activation code
class withinweb_wwkc_keycodes_activator {

	//---------------------------------------------
    public static function activate() {
       	//call create_files function when plugin active
       	self::create_files();
		self::keycodes_install();
    }	
	//---------------------------------------------
    //Create files/directories
    public static function create_files() {
        //Install files and folders for uploading files and prevent hotlinking in log directory
        $upload_dir = wp_upload_dir();

        $files = array(
            array(
                'base' => wwkc_KEYCODES_LOG_DIR,
                'file' => '.htaccess',
                'content' => 'deny from all'
            ),
            array(
                'base' => wwkc_KEYCODES_LOG_DIR,
                'file' => 'index.html',
                'content' => ''
            )
        );

        foreach ($files as $file) {
            if (wp_mkdir_p($file['base']) && !file_exists(trailingslashit($file['base']) . $file['file'])) {
                if ($file_handle = @fopen(trailingslashit($file['base']) . $file['file'], 'w')) {
                    fwrite($file_handle, $file['content']);
                    fclose($file_handle);
                }
            }
        }
    }	
	//---------------------------------------------
	//Do all installation processes
	public static function keycodes_install() {
	
		if (version_compare( get_bloginfo( 'version' ), '3.1', '<' ) ) {

				echo "<p>Plugin requires WordPress 3.1 or higher</p>";
				exit();

				//deactivate_plugins( basename( __FILE__ ) );		//deactivate the plugin	
		}
		else
		{		
			//ipn call back url
			update_option('withinweb_wwkc_keycodes_ipn_url', site_url('?withinweb_wwkc_keycodes&action=ipn_handler') );
			update_option('withinweb_wwkc_keycodes_paypal_environment', 'live' );
			
			self::withinweb_wwkc_keycodes_install_saleshistory_table();
			self::withinweb_wwkc_keycodes_install_items_table();
			//withinweb_wwkc_keycodes_install_data();
		}				
		
	}	
	//---------------------------------------------
	//Create table if not exists
	public static function withinweb_wwkc_keycodes_install_saleshistory_table() {
		
		global $wpdb;
		$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_saleshistory";      

		//You have to put each field on its own line in your SQL statement.
		//You have to have two spaces between the words PRIMARY KEY and the definition of your primary key.
		//You must use the keyword KEY rather than its synonym INDEX and you must include at least one KEY.
		//Example of sql:
		//$sql = "CREATE TABLE " . $table . " (
		 //         id INT NOT NULL AUTO_INCREMENT,
		 //         name VARCHAR(100) NOT NULL DEFAULT '',
		 //         email VARCHAR(100) NOT NULL DEFAULT '',
		 //         UNIQUE KEY id (id)
		 //         );";	  

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		recid INT NOT NULL AUTO_INCREMENT,	
		receiver_email VARCHAR( 100 )  NOT NULL ,
		item_name VARCHAR( 100 )  NOT NULL ,
		item_number VARCHAR( 50 )  NOT NULL ,
		payment_status VARCHAR( 50 ) , 	
		mc_gross VARCHAR( 10 )  NOT NULL ,		
		payer_email VARCHAR( 100 )  NOT NULL ,			
		txn_type VARCHAR( 50 ) ,		
		txn_id VARCHAR( 100 ) , 
		mc_currency VARCHAR(10) NOT NULL , 
		completed  DATETIME NOT NULL ,
		quantity INT default '1' NOT NULL ,
		licencecodes TEXT ,
		business VARCHAR(255),
		receiver_id VARCHAR(255),
		invoice VARCHAR(255),
		custom TEXT,
		memo TEXT,
		tax VARCHAR(255),
		option_name1 VARCHAR(255),
		option_selection1 VARCHAR(255),
		option_name2 VARCHAR(255),
		option_selection2 VARCHAR(255),
		num_cart_items VARCHAR(255),
		mc_fee VARCHAR(255),
		payment_date VARCHAR(255),
		payment_type VARCHAR(255),
		first_name VARCHAR(255),
		last_name VARCHAR(255),
		payer_business_name VARCHAR(255),
		address_name VARCHAR(255),
		address_street VARCHAR(255),
		address_city VARCHAR(255),
		address_state VARCHAR(255),
		address_zip VARCHAR(255),
		address_country VARCHAR(255),
		address_status VARCHAR(255),
		payer_id VARCHAR(255),
		payer_status VARCHAR(255),
		notify_version VARCHAR(255),
		verify_sign VARCHAR(255),
		PRIMARY KEY  (recid)	
		);";
		
		//echo($sql);
		//exit();
		
	   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	   dbDelta( $sql );

	   //add_option( "withinweb_wwkc_keycodes_db_version", $withinweb_wwkc_keycodes_db_version );
	   //echo("<p>withinweb_wwkc_keycodes_saleshistory created</p>");
		
	}
	//---------------------------------------------
	//Create table if not exists	
	public static function withinweb_wwkc_keycodes_install_items_table() {
		
		global $wpdb;
		$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";	

		//note that item number is unique which is needed later on as it is this value that is looked up in the database.

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		recid INT NOT NULL AUTO_INCREMENT,		
		item_number VARCHAR(50) UNIQUE NOT NULL,		
		item_name VARCHAR(100) UNIQUE NOT NULL ,
		item_title VARCHAR(100) NOT NULL,
		item_description TEXT,
		item_description_full TEXT,
		mc_gross VARCHAR(10) NOT NULL ,	
		mc_currency VARCHAR(10), 	
		currency VARCHAR(20),
		emailsubject VARCHAR(255),
		emailtextkeycodes TEXT,	
		lowerlimit INT default '5',
		keycodes TEXT,
		PRIMARY KEY  (recid)	
		);";	

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );	
		
	}	
	//---------------------------------------------
	//Used to install default data into the table if that is required - not used in this application
	public static function withinweb_wwkc_keycodes_install_data() {  
   		//global $wpdb;   
   		//$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_saleshistory";
   		//$rows_affected = $wpdb->insert( $table_name, array( 'reveiver_email' => 'fred@blogs.com', 'item_name' => 'myitem', 'item_number' => 'mynumber', 'mc_gross' => '2', 'payer_email' => "me@me.com", mc_currency => 'USD' , 'completed' => '2014-01-01') );   
	}
	
}