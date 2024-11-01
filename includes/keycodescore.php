<?php
// The core plugin class.
// 
//  This is used to define internationalization, dashboard-specific hooks, and
//  public-facing site hooks.
// 
//  Also maintains the unique identifier of this plugin as well as the current
//  version of the plugin. 
class withinweb_wwkc_keycodes {
    
    //The loader that's responsible for maintaining and registering all hooks that power the plugin.
    //@var      withinweb_wwkc_keycodes_Loader    $loader    Maintains and registers all hooks for the plugin.    
    protected $loader;   
    protected $plugin_name; 	//The plugin name    
    protected $version;			//The current version of the plugin.

	//-------------------------------------------------------------
    // Set the plugin name and the plugin version that can be used throughout the plugin.
    // Load the dependencies, define the locale, and set the hooks for the Dashboard and
    // the public-facing side of the site.
    public function __construct() {

        $this->plugin_name = 'withinweb-wwkc-keycodes';
		//$this->plugin_name = 'withinweb-wordpress-php-keycodes';    
        $this->version = '2.1.4';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_constants();
        $this->define_public_hooks();

        // register API endpoints
        add_action('init', array($this, 'add_endpoint'), 0);
        // handle handle_api_requests endpoint requests
        add_action('parse_request', array($this, 'handle_api_requests'), 0);
        // Create logging folder and file if not exist  
        add_action('init', array($this, 'create_required_files'), 0);

        add_action('withinweb_wwkc_keycodes_api_ipn_handler', array($this, 'withinweb_wwkc_keycodes_api_ipn_handler'));

        //Add action links
        //http://stackoverflow.com/questions/22577727/problems-adding-action-links-to-wordpress-plugin
        $prefix = is_network_admin() ? 'network_admin_' : '';
        add_filter("{$prefix}plugin_action_links_" . wwkc_PLUGIN_BASENAME, array($this, 'plugin_action_links'), 10, 4);
    }

	/*
	//http://www.wprecipes.com/how-to-show-an-urgent-message-in-the-wordpress-admin-area/
	//https://premium.wpmudev.org/blog/adding-admin-notices/
	public function showAdminMessages()
	{
		// Shows as an error message. You could add a link to the right page if you wanted.
		showMessage("You need to upgrade your database as soon as possible...", true);

		// Only show to admins
		if (user_can("manage_options")) {
		   showMessage("Hello admins!");
		}
	}	
	*/
	
	//-------------------------------------------------------------
    // Return the plugin action links.  This will only be called if the plugin is active.
    //
    // @param array $actions associative array of action names to anchor tags
    // @return array associative array of plugin action links
    public function plugin_action_links($actions, $plugin_file, $plugin_data, $context) {
        $custom_actions = array(
            'docs' => sprintf('<a href="%s" target="_blank">%s</a>', 'http://www.withinweb.com/wordpresskeycodes/', __('Docs', 'withinweb-wwkc-keycodes')),
            'support' => sprintf('<a href="%s" target="_blank">%s</a>', 'https://wordpress.org/plugins/withinweb-php-keycodes/', __('Support', 'withinweb-wwkc-keycodes')),
            'review' => sprintf('<a href="%s" target="_blank">%s</a>', 'http://wordpress.org/support/view/plugin-reviews/withinweb-php-keycodes', __('Write a Review', 'withinweb-wwkc-keycodes')),
        );

        // add the links to the front of the actions list
        return array_merge($custom_actions, $actions);
    }
  	//-------------------------------------------------------------
    //Load the required dependencies for this plugin and create an instance of the loader which 
	//will be used to register the hooks with WordPress.
    private function load_dependencies() {
        
        //Class for registering all actions and filter        
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/loader.php';
        //defines internationalization functionality
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/inter.php';
        //actions for Dashboard        
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/admin.php';     
        //function related to log file      
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/logger.php';
        //helper clsss
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/helper.php';
        //IPN forwarder related functons    
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/ipnforwarder.php';
        //public-facing
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/public.php';	
		//keycode actions
	   	require_once plugin_dir_path(dirname(__FILE__)) . 'keycodes/keycodesactions.php';		
		//retrieve keycodes
	   	require_once plugin_dir_path(dirname(__FILE__)) . 'keycodes/paypalkeycodes.php';		
		
        $this->loader = new withinweb_wwkc_keycodes_Loader();
    }
	//-------------------------------------------------------------
    //Define the locale for this plugin for internationalization
    //Uses the withinweb_wwkc_keycodes_i18n class in order to set the domain and to register the hook with WordPress.
    private function set_locale() {

        $plugin_i18n = new withinweb_wwkc_keycodes_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }
	//-------------------------------------------------------------
	//Register hooks for dashboard
    private function define_admin_hooks() {

        $plugin_admin = new withinweb_wwkc_keycodes_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'admin_enqueue_scripts');
        $this->loader->add_action('posts_where_request', $plugin_admin, 'withinweb_wwkc_keycodes_modify_wp_search');
        $this->loader->add_action('post_row_actions', $plugin_admin, 'withinweb_wwkc_keycodes_remove_row_actions', 10, 2);
        $this->loader->add_action('delete_post', $plugin_admin, 'withinweb_wwkc_keycodes_remove_postmeta', 10);
    }
	//-------------------------------------------------------------
	//Register public hooks
    private function define_public_hooks() {

        $plugin_public = new withinweb_wwkc_keycodes_public($this->get_plugin_name(), $this->get_version());

        //$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('the_posts', $plugin_public, 'withinweb_wwkc_keycodes_load_shortcode_asset', 10, 1);
        $this->loader->add_action('wp', $plugin_public, 'withinweb_wwkc_keycodes_private_ipn_post', 10);
    }
	//-----------------------------------------------------		
	//execute hooks
    public function run() {
        $this->loader->run();
    }
	//-----------------------------------------------------	
	//Plugin version name
    public function get_plugin_name() {
        return $this->plugin_name;
    }
	//-----------------------------------------------------
	//Plugin version number	
    public function get_version() {
        return $this->version;
    }
	//-----------------------------------------------------	
	//loader reference
    public function get_loader() {
        return $this->loader;
    } 
 	//-----------------------------------------------------
	//Handle API requests
    public function handle_api_requests() {
        global $wp;

        if (isset($_GET['action']) && $_GET['action'] == 'ipn_handler') {
            $wp->query_vars['withinweb_wwkc_keycodes'] = sanitize_text_field($_GET['action']);
        }

        // handle_api_requests endpoint requests
        if (!empty($wp->query_vars['withinweb_wwkc_keycodes'])) {

            // Buffer, we won't want any output here
            ob_start();

            // Get API trigger
            $api = strtolower(esc_attr($wp->query_vars['withinweb_wwkc_keycodes']));

            // Trigger actions
            do_action('withinweb_wwkc_keycodes_api_' . $api);

            // Done, clear buffer and exit
            ob_end_clean();
            die('1');
        }
    }
  	//-----------------------------------------------------
	//add_endpoint function
    public function add_endpoint() {
        // Add re-write rule
        add_rewrite_endpoint('withinweb_wwkc_keycodes', EP_ALL);
    }	
	//----------------------------------------------------
	public function withinweb_wwkc_keycodes_api_ipn_handler() {

        //ipn handler
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/helper.php';
        $withinweb_wwkc_keycodes_paypal_helper_Object = new withinweb_wwkc_keycodes_paypal_helper();

        //The check_ipn_request function check validation for ipn response
        if ($withinweb_wwkc_keycodes_paypal_helper_Object->check_ipn_request()) {
            $withinweb_wwkc_keycodes_paypal_helper_Object->successful_request($IPN_status = true);
        } else {
            $withinweb_wwkc_keycodes_paypal_helper_Object->successful_request($IPN_status = false);
        }
    }	
	//----------------------------------------------------
    //Define withinweb_wwkc_keycodes Constants
    private function define_constants() {
        if (!defined('wwkc_KEYCODES_LOG_DIR')) {
            define('wwkc_KEYCODES_LOG_DIR', ABSPATH . 'withinweb-ipn-logs/');
        }
    }	
	//----------------------------------------------------
    //Create folder and file if not exist for log directory
    public function create_required_files() {
        // Install files and folders for uploading files and prevent hotlinking
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

}
