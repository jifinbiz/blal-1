<?php
/**
 * Plugin Name: Tenders Plugin
 * Plugin URI: #
 * Description: Custom Plugin for customization
 * Version: 1.0.0
 * Author: SK
 * Author URI: 
 * License: 
 */





define('WCP_PLUGIN_VERSION', '1.0.0');
define('WCP_PLUGIN_DOMAIN', 'tenders');
define('WCP_PLUGIN_URL', WP_PLUGIN_URL . '/tenders');
define('WCP_PLUGIN_PATH', plugin_dir_path(__FILE__));




// add custo field to category 
include_once(dirname(__FILE__). "/WCP/DATA/Controller.php");
function myplugin_ajaxurl(){

 echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}
add_action('wp_head', 'myplugin_ajaxurl');


global $wcp_db_version;
$wcp_db_version = '1.0';

 


