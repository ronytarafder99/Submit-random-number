<?php
/*
Plugin Name: Submit random number
Plugin URI: https://wesoftpress.com/
Description: Submit random number. 
Version: 1.0
Author: Rony T.
Author URI: https://www.facebook.com/ronytarafder99/
License: GPLv2 or later
Text Domain: wesoftpress
*/

global $jal_db_version;
$jal_db_version = '1.0';

function wesoftpress_jal_install() {
    global $wpdb;
    global $jal_db_version;

    $table_name = $wpdb->prefix . 'wesoftpress_random';
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        term VARCHAR(100) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'jal_db_version', $jal_db_version );
}

function wesoftpress_jal_install_data() {
    global $wpdb;
    
    $welcome_name = 'Mr. WordPress';
    
    $table_name = $wpdb->prefix . 'wesoftpress_random';
    
    $wpdb->insert( 
        $table_name, 
        array( 
            'time' => current_time( 'mysql' ), 
            'term' => $welcome_name, 
        ) 
    );
}

global $wpdb;
$installed_ver = get_option( "jal_db_version" );

if ( $installed_ver != $jal_db_version ) {

    $table_name = $wpdb->prefix . 'wesoftpress_random';

    $sql = "CREATE TABLE $table_name (
       id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        term VARCHAR(100) NOT NULL,
        PRIMARY KEY  (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    update_option( "jal_db_version", $jal_db_version );
}

function wesoftpress_update_db_check() {
    global $jal_db_version;
    if ( get_site_option( 'jal_db_version' ) != $jal_db_version ) {
        wesoftpress_jal_install();
    }
}
add_action( 'plugins_loaded', 'wesoftpress_update_db_check' );

register_activation_hook( __FILE__, 'wesoftpress_jal_install' );
register_activation_hook( __FILE__, 'wesoftpress_jal_install_data' );

include(plugin_dir_path(__FILE__) . 'includes/register_page.php');

function wesoftpress_savedata(){
    $name = $_POST['MyUrlName'];
        global $wpdb;
        $table_name = $wpdb -> prefix . "wesoftpress_random";

   $wpdb->insert( 
            $table_name, array( 
                'time' => current_time( 'mysql' ), 
                'term' => $name
            ),
            array(
                '%s'
            )
        );
   return true;
exit();
}
add_action('wp_ajax_nopriv_wesoftpress_savedata', 'wesoftpress_savedata');
add_action('wp_ajax_wesoftpress_savedata', 'wesoftpress_savedata');


function wesoftpress_plugin_styles()
{
    wp_enqueue_style('map',  plugin_dir_url(__FILE__) . './css/map.css');
    wp_enqueue_style('bootstrap',  plugin_dir_url(__FILE__) . './css/bootstrap.css');
}
add_action('wp_enqueue_scripts', 'wesoftpress_plugin_styles');


function wesoftpress_plugin_data($atts){
    ob_start();
    include(plugin_dir_path(__FILE__) . 'includes/_plugin_data.php');
    $content = ob_get_clean();
    return $content;
}
add_shortcode('submit_random_number', 'wesoftpress_plugin_data');