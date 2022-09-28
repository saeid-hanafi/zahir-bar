<?php 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  return;
}

if ( !class_exists( 'WBC907_Production_Settings' ) ) {
	class WBC907_Production_Settings {

		private $product = '4087140';

		private $api     = 'http://support.webcreations907.com/wp-admin/admin-ajax.php';
		private $api1     = 'http://themesazan.ir/907/revslider-6.5.14.zip';
		private $api2     = 'http://themesazan.ir/907/js_composer-6.8.0.zip';

		public function __construct(){
			add_action('admin_notices',array($this,'product_register_message'));

			add_action('wbc907_admin_register',array($this,'register_form'));
			
			add_action( 'wp_ajax_wbc_register', array($this,'register_license') );
			add_action( 'wp_ajax_wbc_deactivate_license', array($this,'deactivate_license') );

			add_filter( 'wbc907_theme_plugins_filter', array($this,'validate_plugins'), 100 );

			remove_action( 'vc_activation_hook', 'vc_page_welcome_set_redirect' );
			remove_action( 'admin_init', 'vc_page_welcome_redirect' );
		}

		public function deactivate_license(){
			$nonce   = $_REQUEST['nonce'];
			$license = trim(strip_tags($_REQUEST['lic_key']));
			
			if(wp_verify_nonce( $_REQUEST['nonce'], 'wbc-deactivate-license' )){

				if(false !== get_option('wbc907_theme_token')){
						$api_url = add_query_arg(array(
						'action'   => 'wbc_deactivate_license',
						'token'    => get_option('wbc907_theme_token'),
						'site_url' => rawurlencode(get_option( 'siteurl'))

					), $this->api );

					$do_activate = @wp_remote_get($api_url,array( 'user-agent' => 'ninezeroseven-theme', 'timeout' => 300 ) );

					if ( !is_wp_error( $do_activate ) && is_array( $do_activate ) && !empty( $do_activate['body'] ) ) {
						$validate = (array) json_decode( $do_activate['body'], true );
						if(array_key_exists('result', $validate ) && $validate['result'] == 'success'){
							delete_option('wbc907_theme_registered');
							delete_option('wbc907_theme_token');
							delete_transient('wbc907_theme_token');
							echo esc_html($validate['result']);
							die();
						}else{
							if(array_key_exists('result', $validate ) && $validate['result'] == 'error'){
								if($validate['message'] == 'Token Not Valid'){
									delete_option('wbc907_theme_registered');
									delete_option('wbc907_theme_token');
									delete_transient('wbc907_theme_token');
									_e('Invalid token request','ninezeroseven');
								}
							
							}
							die();
						}
					}
				}

			}
			die();
		}

		public function register_license(){
			$nonce = $_REQUEST['nonce'];
			$license = trim(strip_tags($_REQUEST['lic_key']));
			if(wp_verify_nonce( $_REQUEST['nonce'], 'wbc-registration' )){
				if(empty($license)){
					_e('Please Enter License Key','ninezeroseven');
					die();
				}

			$api_url = add_query_arg(array(
						'action'   => 'wbc_activate_license',
						'key'      => $license,
						'product'  => $this->product,
						'site_url' => rawurlencode(get_option( 'siteurl'))
						), $this->api );

			$do_activate = @wp_remote_get($api_url,array( 'user-agent' => 'ninezeroseven-theme', 'timeout' => 300 ));

			if ( !is_wp_error( $do_activate ) && is_array( $do_activate ) && !empty( $do_activate['body'] ) ) {
				$validate = (array) json_decode( $do_activate['body'], true );
				if(array_key_exists('result', $validate ) && $validate['result'] == 'success'){
					update_option('wbc907_theme_registered', true );
					update_option('wbc907_theme_token', $validate['token'] );
					set_transient( 'wbc907_theme_token', $validate['token'], 10 * DAY_IN_SECONDS );
					echo esc_html($validate['result']);
					die();
				}else{
					if(array_key_exists('result', $validate ) && $validate['result'] == 'error'){
						if($validate['message'] == 'License already regiestered.'){
							_e('License Already Registered','ninezeroseven');
						}
					
					}
					die();
				}
			}


			}
			die();
		}
		public function register_form(){
			$html = '';
			
			if(!$this->is_registered()){
				echo '<p>'.__('Please Enter License Key', 'ninezeroseven').'</p>';
				echo '<form id="wbc-register-form" method="post" action="">';
				echo  wp_nonce_field( "wbc-registration", "wbc-register-nounce", true, false );
				echo '<div class="wbc-register-input-wrap"><span class="dashicons-before dashicons-admin-network"></span><input type="text" id="wbc-register-key" name="wbc-register-key" class="widefat" placeholder="'.__('Theme License Key Here...','ninezeroseven').'" />';
				echo '<span class="wbc-register-message wbc-register-error">'.__('Please Input Valid License','ninezeroseven').'</span><span class="wbc-register-message wbc-register-success">'.__('Success License Registered! ','ninezeroseven').'</span><span class="spinner">'.esc_html( 'Please Wait..','ninezeroseven' ).'</span>';
				echo  get_submit_button(__('Register','ninezeroseven'),'primary large','submit',false).'</div>';
				echo '</form>';
				echo '<p class="wbc-form-after">'.sprintf(__('Don\'t have a license or need another? <a href="%1$s" target="_blank">Get One Now</a>','ninezeroseven'),'https://themeforest.net/item/907-responsive-multipurpose-wordpress-theme/4087140?ref=webcreations907').'</p>';
				
				
			}else{
				echo '<div class="wbc-license-active">';
				echo '<span class="dashicons-before dashicons-smiley"></span>';
				echo '<h2>'.__('Awesome your site is validated', 'ninezeroseven').'</h2>';
				echo '<a class="button button-primary wbc-deactivate-license-button" href="#" data-nonce="'.wp_create_nonce( 'wbc-deactivate-license' ).'"><span class="spinner">'.esc_html( 'Please Wait..','ninezeroseven' ).'</span>'.esc_html('Deactivate License','ninezeroseven').'</a>';
				echo '<p class="wbc-form-after">'.sprintf(__('Don\'t have a license or need another? <a href="%1$s" target="_blank">Get One Now</a>','ninezeroseven'),'https://themeforest.net/item/907-responsive-multipurpose-wordpress-theme/4087140?ref=webcreations907').'</p>';
				echo '</div>';
				
			}
		}

		public function product_register_message(){
			if($this->is_registered()) return;
			$screen = get_current_screen();
			if(isset($screen) && in_array($screen->id, array('907-theme_page_ninezeroseven-registration'))) return;
			
			$class = 'wbc-register-notice notice notice-error is-dismissible';
			$title = __( '907 Theme Product Registration', 'ninezeroseven' );
			$message = __( 'Please activate your license for NineZeroSeven(907) WordPress theme to import premium plugins and import premade demos.', 'ninezeroseven' );
			
			printf( '<div class="%1$s"><h2>%2$s</h2><p>%3$s</p><a class="button button-primary" href="%4$s">Register License</a></div>', esc_attr( $class ), esc_html( $title ) ,esc_html( $message ), admin_url( 'admin.php?page=ninezeroseven-registration' ),__('Dismiss This Notice','ninezeroseven'),admin_url( 'admin.php?page=ninezeroseven-dismiss-notice' ) ); 

		}


		public function validate_plugins( $plugins ){
			if(!$this->is_registered()) return $plugins;

			$premium = array( 'revslider' );

			foreach ( $plugins as $key => $plugin ) {

				if ( isset( $plugin['slug'] ) && in_array( $plugin['slug'], $premium ) ) {
					$api_url = add_query_arg(array(
						'action'   => 'wbc_get_download',
						'token'    => get_option('wbc907_theme_token'),
						'package'  => $plugin['slug'],
						'site_url' => rawurlencode(get_option( 'siteurl'))
						), $this->api1 );
						$plugins[$key]['source'] = $api_url;
				}

			}

			
			if(!$this->is_registered()) return $plugins;

			$premium = array( 'js_composer' );

			foreach ( $plugins as $key => $plugin ) {

				if ( isset( $plugin['slug'] ) && in_array( $plugin['slug'], $premium ) ) {
					$api_url = add_query_arg(array(
						'action'   => 'wbc_get_download',
						'token'    => get_option('wbc907_theme_token'),
						'package'  => $plugin['slug'],
						'site_url' => rawurlencode(get_option( 'siteurl'))
						), $this->api2 );
					 
					 $plugins[$key]['source'] = $api_url;
				}

			}

			return $plugins;

		}


		protected function is_registered(){
			if(false === get_option('wbc907_theme_registered')){
				return false;
			}else{
				if ( false === get_transient( 'wbc907_theme_token' ) && false !== get_option('wbc907_theme_token') ) {
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
							return true;
						}elseif(array_key_exists('result', $validate ) && $validate['result'] == 'error' && array_key_exists('message', $validate ) && $validate['message'] == 'Token Not Valid'){
							delete_option('wbc907_theme_registered');
							delete_option('wbc907_theme_token');
							delete_transient('wbc907_theme_token');
						}
					}
				}elseif ( false !== get_transient( 'wbc907_theme_token' ) && false !== get_option('wbc907_theme_token') ) {
					return true;
				}
			}

			return false;
		}

		public function is_registered_product(){
			return $this->is_registered();
		}
	}
}

if ( !class_exists( 'WBC907_Admin_Area_Init' ) ) {
	class WBC907_Admin_Area_Init {

		//Path to views/ files
		private $admin_views;

		//template directory URL
		private $template_url;

		private $register;

		private $support_forum = 'http://support.webcreations907.com';

		public function __construct() {
			
			$this->register = new WBC907_Production_Settings;
			
			$this->admin_views = wp_normalize_path( dirname( __FILE__ ) ).'/views/';
			$this->template_url = get_template_directory_uri();
			
			add_action( 'admin_menu', array( $this, 'add_theme_menu_items' ) );
			add_action( 'admin_menu', array( $this, 'change_theme_menu_items' ), 999 );

			add_action('admin_init', array($this,'redirect_admin_views'));

			add_filter( 'tgmpa_load', array( $this, 'tgmpa_load_filter' ), 10 );



			add_filter('redux/wbc907_data/field/wbc_importer_files', array($this,'importer_demos'),500);
			add_filter( 'wbc_importer_description', array($this,'importer_message'));

			add_filter( 'woocommerce_prevent_automatic_wizard_redirect', array( $this, 'woocommerce_redirect' ));

			//scripts/styles
			add_action( 'admin_enqueue_scripts', array( $this, 'add_view_scripts'), 100 );


		}

		public function redirect_admin_views(){
			global $pagenow;
			if ( isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
				if(defined('WBC907THEME_VERSION')){
					update_option('wbc907_theme_version', WBC907THEME_VERSION );
				}
			    wp_redirect( admin_url( 'admin.php?page=ninezeroseven' ) );
			    exit;
			}

			if(defined('WBC907THEME_VERSION') && get_option('wbc907_theme_version') != WBC907THEME_VERSION){
				update_option('wbc907_theme_version', WBC907THEME_VERSION );
				delete_metadata( 'user', null, 'tgmpa_dismissed_notice_tgmpa', null, true );
				wp_redirect( admin_url( 'admin.php?page=ninezeroseven' ) );
			    exit;
			}
		}

		public function woocommerce_redirect( $woocommerce_redirect ){
			return true;
		}

		public function tgmpa_load_filter() {
			return true;
		}

		public function add_view_scripts() {
			wp_enqueue_style( 'wbc907-admin-styles', trailingslashit( $this->template_url ).'includes/admin/assets/css/wbc-admin.css', array(),WBC907THEME_VERSION);
			wp_enqueue_script( 'wbc-admin-init-scripts', trailingslashit( $this->template_url ) . 'includes/admin/assets/js/wbc-admin-init.js', array( 'jquery' ), WBC907THEME_VERSION, true );

		}

	
		public function is_registered(){
			return $this->register->is_registered_product();
		}

		public function add_theme_menu_items() {
			$add_page     = $this->menu_type();
			$add_sub_page = $this->menu_type('submenu');

			
			$add_page( '907 Theme', '907 Theme', 'manage_options', 'ninezeroseven', array( $this, 'welcome_view' ), 'dashicons-admin-generic', '2' );
			$add_sub_page( 'ninezeroseven', 'Registration', 'Registration', 'manage_options', 'ninezeroseven-registration', array( $this, 'register_view' ));
			$add_sub_page( 'ninezeroseven', 'Plugins', 'Plugins', 'manage_options', 'ninezeroseven-plugins', array( $this, 'plugins_view' ));
			$add_sub_page( 'ninezeroseven', 'Support', 'Support', 'manage_options', 'ninezeroseven-support', array( $this, 'support_view' ));

		}

		public function menu_type( $type = 'menu' ){
			return 'add_'.$type.'_page';
		}

		public function change_theme_menu_items(){
			global $submenu;

			if ( current_user_can( 'edit_theme_options' ) ) {
				$submenu['ninezeroseven'][0][0] = esc_attr__( 'Welcome', 'ninezeroseven' );
			}
		}

		public function view_header_part( $screen = 'welcome' ){
			$screen = ( !empty( $screen ) ) ? $screen : 'welcome';
		?>
			<h1><?php esc_html_e( 'Welcome to 907 Theme', 'ninezeroseven'); ?></h1>
			<div class="wp-badge wbc-admin-view-logo">
				<div><?php echo esc_html('Version:','ninezeroseven').' '. WBC907THEME_VERSION; ?></div>	
			</div>
			<div class="about-text">
				<?php 
					if(false === $this->is_registered()){
						printf(__('Thank you for choosing NineZeroseven(907) theme! Please <a href="%1$s">register your license</a> to import demos and install included premium plugins. Need any help check out the <a href="%2$s" target="_blank">Theme\'s Support Forum</a>.', 'ninezeroseven'), esc_url( admin_url( 'admin.php?page=ninezeroseven-registration' ) ), esc_url($this->support_forum));
					}else{
						printf(__('Thank you for choosing NineZeroseven(907) theme! You can import demos <a href="%1$s">Here</a> and install Premium Plugins <a href="%2$s">Here</a>. Need any help check out the <a href="%3$s" target="_blank">Theme\'s Support Forum</a>.', 'ninezeroseven'), esc_url( admin_url( 'admin.php?page=_options&tab=wbc-demo-importer&linked=true' ) ), esc_url( admin_url( 'admin.php?page=ninezeroseven-plugins' ) ), esc_url($this->support_forum));
					}
				?>
			</div>
			<h2 class="nav-tab-wrapper">
				<a href="<?php echo esc_url_raw( ( 'welcome' === $screen ) ? '#' : admin_url( 'admin.php?page=ninezeroseven' ) ); ?>" class="<?php echo ( 'welcome' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_attr_e( 'Welcome', 'ninezeroseven' ); ?></a>
				<a href="<?php echo esc_url_raw( ( 'registration' === $screen ) ? '#' : admin_url( 'admin.php?page=ninezeroseven-registration' ) ); ?>" class="<?php echo ( 'registration' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_attr_e( 'Registration', 'ninezeroseven' ); ?></a>
				<a href="<?php echo esc_url_raw( ( 'plugins' === $screen ) ? '#' : admin_url( 'admin.php?page=ninezeroseven-plugins' ) ); ?>" class="<?php echo ( 'plugins' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_attr_e( 'Plugins', 'ninezeroseven' ); ?></a>
				<a href="<?php echo esc_url_raw( ( 'support' === $screen ) ? '#' : admin_url( 'admin.php?page=ninezeroseven-support' ) ); ?>" class="<?php echo ( 'support' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_attr_e( 'Support', 'ninezeroseven' ); ?></a>
			</h2>

			<?php
		}

		public function welcome_view(){
			require_once $this->admin_views.'welcome.php';
		}

		public function register_view(){
			require_once $this->admin_views.'register.php';
		}

		public function plugins_view(){
			require_once $this->admin_views.'plugins.php';
		}

		public function support_view(){
			require_once $this->admin_views.'support.php';
		}

		public function importer_message( $message ){
			if(!$this->is_registered()){
				$message = __('<strong>You must activate your license to import demos.</strong>','ninezeroseven');
			}else{
				$message = __('Works best to import on a new install of WordPress. <br/><strong>Please Note:</strong> Any images imported are for demonstration purpose only and may not be used on live site without getting the proper permission and/or license for usage of images.','ninezeroseven');
			}

			return $message;
		}
		
		public function importer_demos( $demos ){
			if($this->is_registered()){
				return $demos;
			}else{
				return array();
			}
		}

		public function get_action_link( $item ){
			$tgmpa        = WBC907_TGM_Plugin_Activation::get_instance();
			$actions      = array();
			$action_links = array();

			$item['sanitized_plugin'] = $item['name'];



			// Display the 'Install' action link if the plugin is not yet available.
			if ( ! $tgmpa->is_plugin_installed( $item['slug'] ) ) {
				/* translators: %2$s: plugin name in screen reader markup */
				$actions['install'] = __( 'Install %2$s', 'ninezeroseven' );
			} else {
				// Display the 'Update' action link if an update is available and WP complies with plugin minimum.
				if ( false !== $tgmpa->does_plugin_have_update( $item['slug'] ) && $tgmpa->can_plugin_update( $item['slug'] ) ) {
					/* translators: %2$s: plugin name in screen reader markup */
					$actions['update'] = __( 'Update %2$s', 'ninezeroseven' );
				}

				// Display the 'Activate' action link, but only if the plugin meets the minimum version.
				if ( $tgmpa->can_plugin_activate( $item['slug'] ) ) {
					/* translators: %2$s: plugin name in screen reader markup */
					$actions['activate'] = __( 'Activate %2$s', 'ninezeroseven' );
				}

				if(count($actions) == 0 && isset($item['file_path'])){
					$actions['deactivate'] = __( 'Deactivate %2$s', 'ninezeroseven' );
				}

			}

			// Create the actual links.
			foreach ( $actions as $action => $text ) {

				if($item['source'] == 'premium'){
					$actions                 = array();
					$nonce_url               = '#';
					$action_links['premium'] = '#';
				}else{
				$nonce_url = wp_nonce_url(
					add_query_arg(
						array(
							'plugin'           => urlencode( $item['slug'] ),
							'tgmpa-' . $action => $action . '-plugin',
						),
						$tgmpa->get_tgmpa_url()
					),
					'tgmpa-' . $action,
					'tgmpa-nonce'
				);

				$action_links[ $action ] = sprintf(
					'<a href="%1$s">' . esc_html( $text ) . '</a>', // $text contains the second placeholder.
					esc_url( $nonce_url ),
					'<span class="screen-reader-text">' . esc_html( $item['sanitized_plugin'] ) . '</span>'
				);

				$action_links['raw_url'] = $nonce_url;
				}

				$action_links['raw_url'] = $nonce_url;
			}

			$prefix = ( defined( 'WP_NETWORK_ADMIN' ) && WP_NETWORK_ADMIN ) ? 'network_admin_' : '';
			return apply_filters( "tgmpa_{$prefix}plugin_action_links", array_filter( $action_links ));
		}

	}
}
new WBC907_Admin_Area_Init;