<?php

/*
 * Plugin Name: گوگل آنالیتیکس
 * Plugin URI: http://fbscodes.ir
 * Author: سعید حنفی
 * Author URI: https://github.com/saeid-hanafi
 * Version: 1.0.0
 * Description: افزونه افزودن اسنیپ های گوگل آنالیتیکس
 * Licence: GPLv2 or later
 */

defined('ABSPATH') || exit;

define('MZBAP_ADMIN_INC_DIR', plugin_dir_path( __FILE__ ) . 'admin/includes/');

require_once (MZBAP_ADMIN_INC_DIR . "functions.php");

add_action( 'wp_head','mzbap_google_analytics',0 );