<?php
//Bail no-go amigo :)
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/************************************************************************
* ReduxFrameWork
*************************************************************************/
if ( !function_exists( 'wbc907_load_panel_plugin' ) ) {

	function wbc907_load_panel_plugin() {
		require_once dirname( __FILE__ ) . '/meta-config.php';
		require_once dirname( __FILE__ ) . '/theme-config.php';
	}
	
	global $wp_customize;
	if ( is_admin() || isset( $wp_customize ) ) {
		add_action( 'init' , 'wbc907_load_panel_plugin', 10 );
	}else {
		add_action( 'wp' , 'wbc907_load_panel_plugin', 10 );
	}

}

/************************************************************************
* Remove Redux Dashboard widget
*************************************************************************/
if(!function_exists('wbc907_remove_redux_widget')){
	function wbc907_remove_redux_widget(){
		remove_meta_box('redux_dashboard_widget', 'dashboard', 'side');
	}
	add_action('wp_dashboard_setup', 'wbc907_remove_redux_widget',20);
}
/************************************************************************
* Redux Widget Markup
*************************************************************************/

if ( !function_exists( 'wbc907_widght_markup' ) ) {
	function wbc907_widght_markup( $options ) {
		$options = array(
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>'
		);
		return $options;
	}

	add_filter( 'redux_custom_widget_args' , 'wbc907_widght_markup' , 10 , 1 );
}