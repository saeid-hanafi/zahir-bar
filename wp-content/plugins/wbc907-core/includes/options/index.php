<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/************************************************************************
* Redux Extension Loader
*************************************************************************/

if ( !function_exists( 'wbc907_custom_extension_loader' ) ) {
	function wbc907_custom_extension_loader( $ReduxFramework ) {
		$path = dirname( __FILE__ ) . '/extend/';
		$folders = scandir( $path, 1 );
		foreach ( $folders as $folder ) {
			if ( $folder === '.' or $folder === '..' or !is_dir( $path . $folder ) ) {
				continue;
			}
			$extension_class = 'ReduxFramework_Extension_' . $folder;
			if ( !class_exists( $extension_class ) ) {
				// In case you wanted override your override, hah.
				$class_file = $path . $folder . '/extension_' . $folder . '.php';
				$class_file = apply_filters( 'redux/extension/'.$ReduxFramework->args['opt_name'].'/'.$folder, $class_file );
				if ( $class_file ) {
					require_once $class_file;
					$extension = new $extension_class( $ReduxFramework );
				}
			}
		}
	}

	add_action( "redux/extensions/wbc907_data/before", 'wbc907_custom_extension_loader', 0 );
}

/************************************************************************
* Load color spacing
*************************************************************************/
if ( !function_exists( 'wbc907_override_spacing_field' ) ) {
	function wbc907_override_spacing_field( $field ) {
		return dirname(__FILE__).'/fields/spacing/field_spacing.php';
	}

	add_filter( "redux/wbc907_data/field/class/spacing", "wbc907_override_spacing_field" );
}

/************************************************************************
* Load color alpha Field
*************************************************************************/
if ( !function_exists( 'wbc907_color_add_alpha_field' ) ) {
	function wbc907_color_add_alpha_field($field) {
		return dirname(__FILE__).'/fields/color_alpha/field_color_alpha.php';
	}

	add_filter( "redux/wbc907_data/field/class/color_alpha", "wbc907_color_add_alpha_field" );
}

/************************************************************************
* Load color typography
*************************************************************************/
if ( !function_exists( 'wbc907_override_typography_field' ) ) {
	function wbc907_override_typography_field($field) {
		return dirname(__FILE__).'/fields/typography/field_typography.php';
	}

	add_filter( "redux/wbc907_data/field/class/typography", "wbc907_override_typography_field" );
}

//Customtize Redux CSS
if(!function_exists('wbc907_redux_custom_css_style')){
	
	function wbc907_redux_custom_css_style(){
		if(!is_admin()) return;
		wp_enqueue_style(
                    'wbc907-redux-admin-css',
                    trailingslashit( plugin_dir_url( __FILE__ )).'css/redux-custom-styles.css',
                    array(),
                    '1.0',
                    'all'
                );

		wp_enqueue_script(
                    'wbc907-redux-admin-js',
                    trailingslashit( plugin_dir_url( __FILE__ )).'js/redux-custom-script.js',
                    array( 'jquery' ),
                    '1.0',
                    true
                );
	}
	add_action( "redux/page/wbc907_data/enqueue", 'wbc907_redux_custom_css_style');
}

/************************************************************************
* Redux Options Panel
*************************************************************************/
if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/ReduxCore/framework.php' ) ) {
	require_once dirname( __FILE__ ) . '/ReduxCore/framework.php';
}

/************************************************************************
* Remove Redux About Page
*************************************************************************/
if(!function_exists('wbc907_remove_redux_menu')){
	function wbc907_remove_redux_menu() {
		global $submenu;
	    remove_submenu_page('tools.php','redux-about');
	    if ( current_user_can( 'edit_theme_options' ) && array_key_exists('ninezeroseven', $submenu) ) {
			$submenu['ninezeroseven'][] = array(esc_attr__( 'Demo Importer', 'ninezeroseven' ), 'manage_options',admin_url( 'admin.php?page=_options&tab=wbc-demo-importer&linked=true' ));
		}
	}
	add_action( 'admin_menu', 'wbc907_remove_redux_menu',12 );
}

/************************************************************************
* WBC Importer Extension
*************************************************************************/

if(!function_exists('wbc_theme_importer_description')){
	function wbc_theme_importer_description( $description ){

		return '<b>'.$description.'</b>';

	}

	add_filter('wbc_importer_description','wbc_theme_importer_description');
}


if ( !function_exists( 'wbc_filter_title' ) ) {
	function wbc_filter_title( $title ) {
		return trim( ucfirst( str_replace( "-", " ", $title ) ) );
	}
	add_filter( 'wbc_importer_directory_title', 'wbc_filter_title', 10 );
}


/**
 * WP_IMPORTER Filter
 *
 * Filter ran only when import demo content, replaces URL's set by VC
 * when using buttons, links, etc.
 *
 */
if ( !function_exists( 'wbc_url_post_update' ) ) {
	function wbc_url_post_update( $import_data, $wp_import_post ) {

		if ( isset( $import_data['post_type'] ) && $import_data['post_type'] == 'page' && isset( $import_data['post_content'] ) && !empty( $import_data['post_content'] ) ) {

			$encode_url = urlencode( trailingslashit( home_url() ) );
			$replace_array = array(
				'http%3A%2F%2Fthemes.webcreations907.com%2Fninezeroseven%2Fdemo7%2F' => $encode_url,
				'http%3A%2F%2Fthemes.webcreations907.com%2Fninezeroseven%2Fdemo8%2F' => $encode_url
			);

			$import_data['post_content'] = strtr( $import_data['post_content'] , $replace_array );
		}

		return $import_data;
	}

	add_filter( 'wp_import_post_data_processed', 'wbc_url_post_update', 10, 2 );
}

/************************************************************************
* WPML function for admin texts/string translations.
*************************************************************************/
if ( !function_exists( 'wbc907_wmpl_save_file' ) ) {

	function wbc907_wmpl_save_file( $option_values, $changed_options ) {

		if ( !function_exists( 'icl_register_string' ) ) {
			return;
		}

		//Only needs to be updated if topbar left in use.
		if ( !array_key_exists( 'opts-topbar-left', $changed_options ) ) {
			return;
		}

		$options = get_option( 'wbc907_data' );


		$wpml_text  = '';
		$wpml_text .='<wpml-config>'."\n";
		$wpml_text .='<admin-texts>'."\n";
		$wpml_text .='<key name="wbc907_data">'."\n";
		$wpml_text .='	<key name="opts-nav-text" />'."\n";
		$wpml_text .='	<key name="opts-footer-credit" />'."\n";
		$wpml_text .='	<key name="opts-topbar-left">'."\n";
		$wpml_text .='		<key name="field-info">'."\n";

		for ( $j=0; $j < count( $options['opts-topbar-left']['field-info'] ); $j++ ) {
			$wpml_text .='			<key name="'.esc_attr($j).'" />'."\n";
		}

		$wpml_text .='		</key>'."\n";
		$wpml_text .='	</key>'."\n";
		$wpml_text .='</key>'."\n";
		$wpml_text .='</admin-texts>'."\n";
		$wpml_text .='</wpml-config>'."\n";


		@file_put_contents( get_template_directory().'/wpml-config.xml', $wpml_text, LOCK_EX );


	}

	add_action( 'redux/options/wbc907_data/saved', 'wbc907_wmpl_save_file', 10, 2 );
}
?>