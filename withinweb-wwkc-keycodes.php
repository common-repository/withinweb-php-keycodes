<?php
/*
Plugin Name: WithinWeb WordPress PHP-KeyCodes
Plugin URI:  http://www.withinweb.com/wordpresskeycodes/
Description: Sell software license codes, key codes or PIN numbers using PayPal IPN.
Author: Paul Gibbs
Version: 2.1.6
Author URI: http://www.withinweb.com/wordpresskeycodes/
Text Domain: withinweb-wwkc-keycodes
Domain Path: /languages
*/

//withinweb-wordpress-php-keycodes

if ( ! defined( 'ABSPATH' )  ) exit;


 //define wwkc_PLUGIN_URL constant for global use
if (!defined('wwkc_PLUGIN_URL'))
    define('wwkc_PLUGIN_URL', plugin_dir_url(__FILE__));


 //define log file path
if (!defined('wwkc_KEYCODES_LOG_DIR')) {
    $upload_dir = wp_upload_dir();
    define('wwkc_KEYCODES_LOG_DIR', $upload_dir['basedir'] . '/withinweb-ipn-logs/');
}


 //define plugin basename
if (!defined('wwkc_PLUGIN_BASENAME')) {
    define('wwkc_PLUGIN_BASENAME', plugin_basename(__FILE__));
}


 //define set_locale path
if (!defined('wwkc_PLUGIN_LOCALE')) {
    define('wwkc_PLUGIN_LOCALE', dirname(plugin_basename(__FILE__)));
}


//plugin activation
function activate_withinweb_wwkc_keycodes() {
    require_once plugin_dir_path(__FILE__) . 'includes/pluginactivator.php';
    withinweb_wwkc_keycodes_activator::activate();
}


//plugin deactivation
function deactivate_withinweb_wwkc_keycodes() {
    require_once plugin_dir_path(__FILE__) . 'includes/plugindeactivator.php';
    withinweb_wwkc_keycodes_deactivator::deactivate();
}


register_activation_hook(__FILE__, 'activate_withinweb_wwkc_keycodes');
register_deactivation_hook(__FILE__, 'deactivate_withinweb_wwkc_keycodes');


//define internationalization,
//dashboard-specific hooks, and public-facing site hooks 
require plugin_dir_path(__FILE__) . 'includes/keycodescore.php';


/**
 * Begins execution of the plugin
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_withinweb_wwkc_keycodes() {

    $plugin = new withinweb_wwkc_keycodes();
    $plugin->run();
}

run_withinweb_wwkc_keycodes();
