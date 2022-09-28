<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//Helper Functions
require_once( dirname( __FILE__ ) . '/class-helper-functions.php' );

require_once( dirname( __FILE__ ) . '/custom-colors.php' );

//Theme Functions
require_once( dirname( __FILE__ ) . '/theme-functions.php' );

//PageLoader
require_once( dirname( __FILE__ ) . '/class-wbc-pageloader.php' );

//Page Nav Next/Prev
require_once( dirname( __FILE__ ) . '/class-wbc-page-nav-next-prev.php' );

//Back To Top
require_once( dirname( __FILE__ ) . '/class-wbc-backtotop.php' );
?>