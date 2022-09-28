<?php
/**
 * @package     WBC_Importer - Extension for Importing demo content
 * @author      Webcreations907
 * @version     1.0.2
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if ( !class_exists( 'WBC_Demo_Importer_Data' ) ) {

	class WBC_Demo_Importer_Data {

		public static $instance;

		protected $demos_info_url = '';

		protected $demo_content_url = '';


		protected $demo;

		protected $demos = [];


		protected $demodir;

		protected $basedir;


		protected static $api = 'http://support.webcreations907.com/wp-admin/admin-ajax.php';


		// pr

		/**
		 * Class Constructor
		 *
		 * @since       1.0
		 * @access      public
		 * @return      void
		 */
		public function __construct( $demo = '' ) {

			if( empty( $demo ) || is_null( $demo ) ){
				return;
			}
			
			$demos = $this->get_demos();

			if( !isset( $demos[$demo] ) ){
				return;
			}

			$this->demo = $demo;
			$this->demo_data = $demos[ $demo ];

			$upload_dir    = wp_upload_dir();
			$this->basedir = wp_normalize_path( $upload_dir['basedir'] . '/ninezeroseven-demos' );


			$this->demodir = wp_normalize_path( $this->basedir . '/' . $this->demo);
		}

		public function filesystem(){
	
			if( ! defined( 'FS_METHOD' ) ){
				define( 'FS_METHOD', 'direct' );
				WP_Filesystem();
			}
			
			if( ! defined( 'FS_CHMOD_DIR' ) ){
				define( 'FS_CHMOD_DIR', ( 0755 & ~ umask() ) );
			}
			
			if( ! defined( 'FS_CHMOD_FILE' ) ){
				define( 'FS_CHMOD_FILE', ( 0644 & ~ umask() ) );
			}
			
			global $wp_filesystem;
		
			if( empty( $wp_filesystem ) ){
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			return $wp_filesystem;
		}

		public static function get_demos(){
			$demos = array();
			$demo_file = wp_normalize_path( plugin_dir_path( __DIR__ ) .'demo-data/demos.php');

			if( file_exists( $demo_file ) ){
				$demo_file = include $demo_file;

				if( is_array( $demo_file ) ){
					$demos = $demo_file;
					ksort( $demos );
				}
			}
			
            return $demos;
		}

		public function get_file( $file ) {
			if( file_exists( $this->demodir.'/'.$file ) ){
				return $this->demodir.'/'.$file;
			}
			return false;
		}


		public function demo_downloaded(){
			if( file_exists( $this->demodir.'/demo-data.zip' ) ){
				return true;
			}
			return false;
		}

		public function get_revsliders(){
			if( file_exists( $this->demodir.'/revsliders') ){
				$sliders = [];
				foreach( glob( $this->demodir.'/revsliders/*.zip' ) as $revslider ) {
					$sliders[] = wp_normalize_path( $revslider );

				}

				if( count( $sliders ) > 0 ){
					return $sliders;
				}
			}
			return false;
		}

		public function do_download(){
			$this->build_dir();
			$this->get_demo_data();
		}


		protected function get_demo_data(){

			$api_url = add_query_arg(array(
						'action'   => 'wbc_get_demo',
						'token'    => get_option('wbc907_theme_token'),
						'demo_id'  => $this->demo,
						'site_url' => rawurlencode( get_option( 'siteurl') )
						), self::$api );

			$response = @wp_remote_get( $api_url, array( 'user-agent' => 'ninezeroseven-theme', 'timeout' => 300 ) );
			
			if( wp_remote_retrieve_response_code( $response ) == 200 && wp_remote_retrieve_header( $response, 'content-type' ) == 'application/zip' ){

				$body = wp_remote_retrieve_body( $response );
				
				if( empty( $body ) ){
					return false;
				}
				
				$wp_filesystem = $this->filesystem();

				$file = $this->demodir.'/demo-data.zip';

				$didload = $wp_filesystem->put_contents( $file, $body, FS_CHMOD_FILE );

				if( !$didload ){

					unlink( $file  );
					$zipfile = fopen( $file , 'w' ); 

					$writezip = fwrite( $zipfile, $body ); 
					fclose( $zipfile ); 
					if ( false === $writezip ) {
						return false;
					}else{
						$didload = true;
					}
				}
				

				if( !$didload ){
					return false;
				}

				if ( class_exists( 'ZipArchive' ) ) {
					$zip = new ZipArchive();
					if ( true === $zip->open( $file ) ) {
						$zip->extractTo( $this->demodir );
						$zip->close();
						return true;
					}
				}else{

					$unzipfile	= unzip_file( $file, $this->demodir );

					if( is_wp_error( $unzipfile ) ){
						if( ! defined( 'FS_METHOD' ) ){
							define('FS_METHOD', 'direct');
						}

						WP_Filesystem();

						$unzipfile	= unzip_file( $file, $this->demodir );
					}

					if( $unzipfile ){
						return true;
					}
				}

			}
			return false;
		}


		protected function build_dir(){
			if ( ! file_exists( $this->basedir ) ) {
				wp_mkdir_p( $this->basedir );
			}
			if ( ! file_exists( $this->demodir ) ) {
				wp_mkdir_p( $this->demodir );
			}
		}

		public function get_woo_pages(){
			$defaults = array('shop','cart','checkout','myaccount');
			if( isset( $this->demo_data['woocommerce'] ) && !empty( $this->demo_data['woocommerce'] ) && is_array( $this->demo_data['woocommerce'] ) ){
				foreach ( $this->demo_data['woocommerce'] as $key => $value ) {
					if( !in_array($key, $defaults) || empty( $value ) ){
						unset( $this->demo_data['woocommerce'][$key] );
					}
				}

				if( count( $this->demo_data['woocommerce'] ) > 0){
					return $this->demo_data['woocommerce'];
				}
			}

			return false;
		}

		public function get_homepage(){
			if( isset( $this->demo_data['homepage'] ) && !empty( $this->demo_data['homepage'] ) ){
				return $this->demo_data['homepage'];
			}

			return 'Homepage';
		}

		public function get_preview(){
			if( isset( $this->demo_data['preview'] ) && !empty( $this->demo_data['preview'] ) ){
				return $this->demo_data['preview'];
			}

			return false;
		}

		public function get_menus(){
			if( isset( $this->demo_data['menu'] ) && !empty( $this->demo_data['menu'] )  && !is_array( $this->demo_data['menus'] ) ){
				return $this->demo_data['menu'];
			}

			return false;
		}

	}
}