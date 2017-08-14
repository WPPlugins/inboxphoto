<?php
/*
Plugin Name: inbox.photo helper
Plugin URI: https://blog.koffeeware.com/wordpress-plug-in-for-inbox-photo/
Description: Streamline integration of inbox.photo.
Version: 2.0
Author: Koffeeware
Author URI: http://www.koffeeware.com
License: GPL2
Text Domain: inboxphoto
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once( 'include/settings.php' );
require_once( 'include/actions.php' );
require_once( 'include/widget.php' );

if ( is_admin() ){
	add_action( 'admin_menu' , 'inbox_photo_options_page' );
	add_action( 'admin_init' , 'register_inbox_photo_settings' );
//	add_action( 'admin_init' , 'inboxphoto_scripts' );
}
else {
	add_action( 'wp_head' , 'hook_inbox_photo_css' );
	add_shortcode( 'inboxphoto' , 'shortcode_inbox_photo_button_func' );
	add_shortcode( 'inboxphoto_button' , 'shortcode_inbox_photo_button_func' );
	add_shortcode( 'inboxphoto_snippet' , 'shortcode_inbox_photo_snippet_func' );
}

function inboxphoto_load_textdomain() {
	load_plugin_textdomain( 'inboxphoto', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}
add_action('plugins_loaded', 'inboxphoto_load_textdomain');
