<?php
/*
Plugin Name: Download Pdf
Plugin URI: #
Description: Used by millions, Akismet is quite possibly the best way in the world to <strong>protect your blog from spam</strong>. It keeps your site protected even while you sleep. To get started: activate the Akismet plugin and then go to your Akismet Settings page to set up your API key.
Version: 4.1.7
Author: Wordress
Author URI: #
 
*/




 



// add custo field to category 
include_once(dirname(__FILE__). "/data/Controller.php");
 



function wcp_install() {
    global $wpdb;
 
    $download_pdf_emails=$wpdb->prefix.'download_pdf_emails';

   
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS `$download_pdf_emails` (
        ID bigint(20) NOT NULL AUTO_INCREMENT,
        product_id text NOT NULL,
		 name text NOT NULL,
        email text NOT NULL,
        phone_no text NOT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (ID)
    ) $charset_collate;";

    


    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
   

    add_option( 'wcp_download_pdf_db_version', '1.0');
}
 
register_activation_hook( __FILE__, 'wcp_install' );




// 