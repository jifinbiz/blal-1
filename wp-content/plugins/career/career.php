<?php
/**
 * Plugin Name: Career Plugin
 * Plugin URI: #
 * Description: Custom Plugin for customization
 * Version: 1.0.0
 * Author: SK
 * Author URI: 
 * License: 
 */


define('CAREER_PLUGIN_VERSION', '1.0.0');
define('CAREER_PLUGIN_DOMAIN', 'career');
define('CAREER_PLUGIN_URL', WP_PLUGIN_URL . '/career');
define('CAREER_PLUGIN_PATH', plugin_dir_path(__FILE__));




// add custo field to category 
include_once(dirname(__FILE__). "/WCP/DATA/Controller.php");
 
 


