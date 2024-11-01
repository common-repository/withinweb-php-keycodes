<?php
//Public facing parts
class withinweb_wwkc_keycodes_public {
	
    private $plugin_name;	//Plugin id name	
    private $version;		//Plugin version number

	//--------------------------------------------
	//Constructor
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();
    }
	//--------------------------------------------
	//Register style sheets for public side of site
    //This function is provided for demonstration purposes only.
    //
    // An instance of this class should be passed to the run() function
    // defined in Paypal_Ipn_Loader as all of the hooks are defined
    // in that particular class.
    // 
    // The Paypal_Ipn_Loader will then create the relationship
    // between the defined hooks and the functions defined in this class.
    public function enqueue_styles() {
    }
 	//--------------------------------------------
	//Register Javascript for public facing side of site
	// This function is provided for demonstration purposes only.
    //  
    // An instance of this class should be passed to the run() function
    // defined in Paypal_Ipn_Loader as all of the hooks are defined
    // in that particular class.
    //
    // The Paypal_Ipn_Loader will then create the relationship
    // between the defined hooks and the functions defined in this class.
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name . 'public-bn', plugin_dir_url(__FILE__) . 'js/withinweb_wwkc_keycodes_public_bn.js', array('jquery'), $this->version, true);
    }
	//--------------------------------------------
    public function enqueue_scripts_for_shortcode() {
    }
 	//--------------------------------------------
	//Fromt end class definiition
    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/classes/publicdisplay.php';
    }
	//--------------------------------------------
	//Adds in extra assests needded for short code paypal_ipn_list and paypal_ipn_data
	public function withinweb_wwkc_keycodes_load_shortcode_asset($posts) {
        if (empty($posts)) {
            return $posts;
        }

        $found = false;

        foreach ($posts as $post) {
            if (strpos($post->post_content, '[paypal_ipn_list') !== false || strpos($post->post_content, '[paypal_ipn_data') !== false) {
                $found = true;
                break;
            }
        }

        if ($found) {
            $this->enqueue_scripts_for_shortcode();
            $this->enqueue_styles();
        }
        return $posts;
    }
    //--------------------------------------------
    public function withinweb_wwkc_keycodes_private_ipn_post() {
        try {
            if ( !is_admin() && ( is_post_type_archive( 'paypal_ipn' ) ||  is_tax( 'paypal_ipn_type' ) ) ) {
                global $wp_query;
                $wp_query->set_404();
                status_header( 404 );
                nocache_headers();
                wp_redirect( home_url() );
                exit();
            }
        } catch (Exception $ex) {
        }
    }
	
	
}