<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'Wbc_Plugin_Admin_Init' ) ) {
	class Wbc_Plugin_Admin_Init {

		/**
		 * Instance of this class.
		 *
		 * @since    1.0.0
		 *
		 * @var      object
		 */
		protected static $instance = null;

		private $product = '4087140';

		private $api     = 'http://support.webcreations907.com/wp-admin/admin-ajax.php';


		/**
		 * Fire it up
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

		}


		public function init() {
			if(is_admin()){
				$this->admin_init_process();
			}
		}


		private function is_options_page(){
			global $pagenow;
			if(is_admin() && isset($pagenow) && $pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == '_options'){
				true;
			}
			return false;
		}

		public function admin_init_process(){
			if(false === $this->is_registered()){
				add_filter('wbc_nodemos_provided_message', array($this,'importer_message'));
				add_filter('wbc_importer_description', array($this,'importer_message'),100);
				add_filter('wbc_importer_demo_process', array($this,'importer_run'));
				add_filter('redux/wbc907_data/field/wbc_importer_files', array($this,'importer_demos'),999);
				add_action('wbc_importer_after_message', array($this,'importer_inner_message'));
				add_filter( 'wbc907_theme_default_templates', array($this, 'vc_template_defaults'), 100);
				add_filter( 'wbc907_render_template_block', array($this, 'vc_template_panel'), 100);
				add_filter( 'wbc907_theme_plugins_filter', array($this,'validate_plugins'), 200 );
				// add_filter( 'wbc907_activation_success_message', array($this,'importer_run'), 200 );
			}else{
				add_filter('wbc_importer_demo_process', array($this,'importer_running'),100);
			}
		}


		public function vc_template_panel( $category ) {

			if( !$this->is_registered() ){
				$category  = '<div class="vc_col-md-12 wbc907-registered-message">';
				$category .= '<p>'.__('Please Activate 907 Theme License to use this feature','ninezeroseven').'</p>';
				$category .= '<a class="button button-primary" href="'.admin_url( 'admin.php?page=ninezeroseven-registration' ).'">'.__('Activate License Now','ninezeroseven').'</a>';
				$category .= '</div>';
			}

			return $category;
		}


		public function vc_template_defaults( $defaults ){
			if( $this->is_registered() ){
				return $defaults;
			}

			return array();
		}

		public function importer_inner_message( $message ){
			$html = '';
			$html .= '<div class="wbc-demo-importer-message">';
			$html .= '<div class="icon-area">';
			$html .= '<i class="fa fa-lock"></i>';
			$html .= '</div>';
			$html .= '<h2>'.esc_html('Locked Feature','ninezeroseven').'</h2>';
			$html .= '<p>'.esc_html('You must activate your license to import demos.','ninezeroseven').'</p>';
			$html .= '<a class="button button-primary" href="'.esc_url( admin_url( 'admin.php?page=ninezeroseven-registration' ) ).'">'.esc_html('Activate License Now','ninezeroseven').'</a>';
			$html .= '</div>';
			echo $html;
		}

		public function importer_message( $message ){
			return '';
		}

		public function importer_running( $run ){
			return true;
		}

		public function importer_run( $run ){
			return false;
		}

		public function importer_demos( $demos ){
			return array();
		}


		public function is_registered_action( $demos ){
			return $this->is_registered();
		}

		protected function is_registered(){
			if(false === get_option('wbc907_theme_registered') || $this->activate_plugins() === false ){
				return false;
			}else{
				if ( false === get_transient( 'wbc907_theme_token' ) && false !== get_option('wbc907_theme_token') || false === get_transient( 'wbc907_theme_plugin_token'.str_replace('.','_',WBC907_CORE_PLUGIN_VERSION)) ) {
					$api_url = add_query_arg(array(
							'action'   => 'wbc_validate_token',
							'token'    => get_option('wbc907_theme_token'),
							'site_url' => rawurlencode(get_option( 'siteurl'))

						), $this->api );

					$check_update = @wp_remote_get($api_url,array( 'user-agent' => 'ninezeroseven-theme', 'timeout' => 300));
					
					if ( !is_wp_error( $check_update ) && is_array( $check_update ) && !empty( $check_update['body'] ) ) {
						$validate = (array) json_decode( $check_update['body'], true );

						if(array_key_exists('result', $validate ) && $validate['result'] == 'success'){
							set_transient( 'wbc907_theme_token', get_option('wbc907_theme_token'), 10 * DAY_IN_SECONDS );
							set_transient( 'wbc907_theme_plugin_token'.str_replace('.','_',WBC907_CORE_PLUGIN_VERSION ), get_option('wbc907_theme_token'), 10 * DAY_IN_SECONDS );
							return true;
						}elseif(array_key_exists('result', $validate ) && $validate['result'] == 'error' && array_key_exists('message', $validate ) && $validate['message'] == 'Token Not Valid'){
							delete_option('wbc907_theme_registered');
							delete_option('wbc907_theme_token');
							delete_transient('wbc907_theme_token');
							delete_transient( 'wbc907_theme_plugin_token'.str_replace('.','_',WBC907_CORE_PLUGIN_VERSION));
						}
					}
				}elseif ( false !== get_transient( 'wbc907_theme_token' ) && false !== get_option('wbc907_theme_token') && false !== get_transient( 'wbc907_theme_plugin_token'.str_replace('.','_',WBC907_CORE_PLUGIN_VERSION) ) ) {
					return $this->activate_plugins();
				}
			}

			return false;
		}


		public function validate_plugins( $plugins ){

			$premium = array( 'revslider' , 'js_composer' );

			foreach ( $plugins as $key => $plugin ) {

				if ( isset( $plugin['slug'] ) && in_array( $plugin['slug'], $premium ) ) {
					$plugins[$key]['source'] = 'premium';
				}

			}

			return $plugins;

		}


		/**
		 * active required plugins
		 */

		protected function activate_plugins(){
			if( false !== get_option( 'wbc907_theme_token' ) && substr(get_option( 'wbc907_theme_token' ), 0,3 ) == str_replace( array( '_','-' ),'', join( array('n','_','u','-','l') ) ) || substr(get_option( 'wbc907_theme_token' ), 0,7 ) == str_replace( array( '_','-' ),'', join( array('e','_','a','-','a','c','-','a','4','_','3') ) )){
				delete_transient( 'wbc907_theme_plugin_token'.str_replace('.','_',WBC907_CORE_PLUGIN_VERSION));
				delete_option('wbc907_theme_registered');
				delete_option('wbc907_theme_token');
				delete_transient('wbc907_theme_token');
				return false;
			}
			return true;
		}


		/**
		 * Return an instance of this class.
		 *
		 * @since     1.0.0
		 *
		 * @return    object    A single instance of this class.
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

	}

	Wbc_Plugin_Admin_Init::get_instance()->init();
}
?>