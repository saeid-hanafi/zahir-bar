<?php
/************************************************************************
* WooCommerce Settings
*************************************************************************/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( defined( 'WBC907_CORE_PLUGIN_VERSION' ) && version_compare( WBC907_CORE_PLUGIN_VERSION , '3.0', '>=' ) ){
	add_filter( 'elementor/icons_manager/additional_tabs', 'wbc907_elementor_theme_icons', 9999999, 1 );
	add_action( 'elementor/init', 'wbc907_elementor_set_settings' );
	add_filter( 'wbc907_show_page_title', 'wbc907_show_elementor_page_title' );

	add_filter( 'opts-primary-color', 'wbc_elementor_primary_colors' );
}

if ( !function_exists( 'wbc_elementor_primary_colors' ) ) {

	function wbc_elementor_primary_colors( $colors ) {

		$new_css = array(
			'background-color' => '.elementor-button',
		);

		return wbc_arrays_to_options( $new_css , $colors );
	}

}

if(!function_exists('wbc_elementor_buttons_text_color')){
	function wbc_elementor_buttons_text_color( $colors ){
		
		$new_css = array(
				   'color' => '.elementor-button,.elementor-button:focus, .elementor-button:hover, .elementor-button:visited',
				);


		return wbc_arrays_to_options( $new_css , $colors );
	}

	add_filter( 'opts_buttons_text_color', 'wbc_elementor_buttons_text_color' );

}


if(!function_exists('wbc_elementor_buttons_text_hover_color')){
	function wbc_elementor_buttons_text_hover_color( $colors ){
		
		$new_css = array(
				   'color' => '.elementor-button:focus, .elementor-button:hover',
				);


		return wbc_arrays_to_options( $new_css , $colors );
	}

	add_filter( 'opts_buttons_text_hover_color', 'wbc_elementor_buttons_text_hover_color' );

}

if(!function_exists('wbc_elementor_buttons_bg_hover_color')){
	function wbc_elementor_buttons_bg_hover_color( $colors ){
		
		$new_css = array(
				   'background-color' => '.elementor-button:focus, .elementor-button:hover',
				);


		return wbc_arrays_to_options( $new_css , $colors );
	}

	add_filter( 'opts_buttons_bg_hover_color', 'wbc_elementor_buttons_bg_hover_color' );

}

if(!function_exists('wbc_elementor_buttons_bg_color')){
	function wbc_elementor_buttons_bg_color( $colors ){
		
		$new_css = array(
				   'background-color' => '.elementor-button',
				);


		return wbc_arrays_to_options( $new_css , $colors );
	}

	add_filter( 'opts_buttons_bg_color', 'wbc_elementor_buttons_bg_color' );

}


if ( ! function_exists( 'wbc907_show_elementor_page_title' ) ) {
	function wbc907_show_elementor_page_title( $val ) {
		if( class_exists( 'Elementor\Plugin' ) ){
			$current_doc = \Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}



function wbc907_elementor_set_settings(){
	if( ! get_option( 'wbc907_elementor_settings' ) ){
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
		update_option( 'elementor_page_title_selector', '.page-title-wrap' );
		update_option( 'elementor_container_width', 1170);
		update_option( 'elementor_cpt_support', array( 'post', 'page', 'wbc-portfolio', 'wbc-reuseables' ) );
		
		update_option( 'wbc907_elementor_settings', true );

		if( class_exists('Elementor\Plugin') ){
			Elementor\Plugin::$instance->files_manager->clear_cache();
		}
	}
}


if( !function_exists( 'wbc907_elementor_theme_icons' ) ){
	
	function wbc907_elementor_theme_icons( $tabs ){
		$theme_icons = array();

		$theme_icons['et-line'] = array(
			'name'          => 'et-line',
			'label'         => 'Et Line Icons',
			'url'           => get_template_directory_uri().'/assets/css/font-icons/etline/et-icons.css',
			'enqueue'       => [get_template_directory_uri().'/assets/css/font-icons/etline/et-icons.css'],
			'prefix'        => 'et-',
			'displayPrefix' => '',
			'labelIcon'     => 'et-icon-laptop',
			'ver'           => '2',
			'fetchJson'     => get_template_directory_uri().'/assets/css/font-icons/etline/etline-icons.js',
		);

		$theme_icons['flat-icons'] = array(
			'name'          => 'flat-icons',
			'label'         => 'Mixed Icons',
			'url'           => get_template_directory_uri().'/assets/css/font-icons/flaticon/flat-icons.css',
			'enqueue'       => [ get_template_directory_uri().'/assets/css/font-icons/flaticon/flat-icons.css'],
			'prefix'        => 'flaticon-',
			'displayPrefix' => '',
			'labelIcon'     => 'flaticon-architecture',
			'ver'           => '2',
			'fetchJson'     => get_template_directory_uri().'/assets/css/font-icons/flaticon/flaticon-icons.js',
		);

		$tabs = array_merge( $theme_icons, $tabs );
		return $tabs;
	}

}

if ( !function_exists( 'wbc907_elementor_panel_options' ) ) {
	function wbc907_elementor_panel_options( $sections ) {
		//$sections = array();
		$sections[] = array(
			'title' => __( 'Elementor', 'ninezeroseven' ),
			// 'desc' => __( 'Settings for Elementor when installed', 'ninezeroseven' ),
			'icon' => 'dashicons-before dashicons-admin-generic wbc-elementor-icon',
			// Leave this as a blank section, no options just some intro text set above.
			'fields' => array(
				array(
					'id'       => 'opts-elementor-promotional-widgets',
					'type'     => 'switch',
					'title'    => esc_html__('Hide Promotinal Widgets?', 'ninezeroseven'),
					'subtitle' => esc_html__('Hides unuseable promotional widgets/categories.', 'ninezeroseven'),
					'default'  => 1,
					'on'       => 'Enabled',
					'off'      => 'Disabled',
					)

			)
		);
		return $sections;
	}
}