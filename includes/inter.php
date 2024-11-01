<?php
//Internationlisation
class withinweb_wwkc_keycodes_i18n {
	
	//---------------------------------
	//the text domain for this plugin
    private $domain;

	//---------------------------------
	//load text domian
    public function load_plugin_textdomain() {


        $locale = apply_filters('plugin_locale', get_locale(), $this->domain);

        load_textdomain($this->domain, WP_LANG_DIR . '/' . $this->domain . '/' . $this->domain . '-' . $locale . '.mo');

        load_plugin_textdomain($this->domain, false, wwkc_PLUGIN_LOCALE . '/languages/');		
		
		//echo(WP_LANG_DIR . '/' . $this->domain . '/' . $this->domain . '-' . $locale . '.mo');
		//echo(wwkc_PLUGIN_LOCALE . '/languages/');
		//exit();
		
    }
	//---------------------------------
	//set domain to spcified domain
    public function set_domain($domain) {
        $this->domain = $domain;
    }

}