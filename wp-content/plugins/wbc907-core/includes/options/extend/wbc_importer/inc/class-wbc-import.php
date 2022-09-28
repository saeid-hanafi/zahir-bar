<?php
/**
 *
 *
 * @package     WBC_Importer - Extension for Importing demo content
 * @author      Webcreations907
 * @version     1.0.2
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if ( !class_exists( 'WBC_Demo_Importer' ) ) {

	class WBC_Demo_Importer {

		public static $instance;

		protected $demos = [];


		protected $importer_files;


		protected $WP_Import;

		public $elementor_data = false;


		public $active_import;
		public $active_import_id;


		public $theme_xml;
		public $theme_options;
		public $theme_widgets;

		public $theme_options_id = 'wbc907_data';


		/**
		 * Class Constructor
		 *
		 * @since       1.0
		 * @access      public
		 * @return      void
		 */
		public function __construct( ) {
			if ( !class_exists( 'WBC_Demo_Importer_Data' ) ) {
				include wp_normalize_path( dirname( __FILE__ ) ).'/class-wbc-import-data.php';
			}

			$this->demos = WBC_Demo_Importer_Data::get_demos();

			add_action( 'wp_ajax_redux_wbc_importer', array( $this, 'ajax_importer' ) );

		}

		public function ajax_importer() {
			if ( !isset( $_REQUEST['nonce'] ) || !wp_verify_nonce( $_REQUEST['nonce'], "wbc_demo_importer" ) ) {
				die( 0 );
			}

			if ( isset( $_REQUEST['type'] ) && $_REQUEST['type'] == "import-demo-content" && array_key_exists( $_REQUEST['demo_import_id'], $this->demos ) ) {

				$reimporting = false;

				if ( isset( $_REQUEST['wbc_import'] ) && $_REQUEST['wbc_import'] == 're-importing' ) {
					$reimporting = true;
				}

				$this->active_import_id = $_REQUEST['demo_import_id'];

				$this->active_import = array( $this->active_import_id => $this->wbc_import_files[$this->active_import_id] );

				if ( !isset( $import_parts['imported'] ) || true === $reimporting ) {

					$this->importer_files = new WBC_Demo_Importer_Data( $this->active_import_id );

					if ( ! $this->importer_files->demo_downloaded() ) {
						$this->importer_files->do_download();

						$count = 0;
						$done = false;

						do {
							if ( $this->importer_files->demo_downloaded() ){
								$done = true;
							}
							$count++;
						}while( $done == false && $count < 5 );
					}

					if( $this->importer_files->demo_downloaded() ){

						$this->theme_xml     = $this->importer_files->get_file( 'content.xml');
						$this->theme_widgets = $this->importer_files->get_file('widgets.json');
						$this->theme_options = $this->importer_files->get_file('theme-options.txt');

						$this->before_import();
						$this->process_import();

					}else{
						echo esc_html__( "Failed to install demo", 'wbc907-core' );
					}
				}else {
					echo esc_html__( "Demo Already Imported", 'wbc907-core' );
				}

				die();
			}

			die();
		}


		public function after_importer(){

			if( $this->importer_files->get_revsliders() && class_exists( 'RevSlider' ) ){

				foreach ($this->importer_files->get_revsliders() as $slider => $file) {
					if ( file_exists( $file ) ) {
						ob_start();
						$slider = new RevSlider();
						$slider->importSliderFromPost( true, true, $file );
						ob_end_clean();

					}
				}
				
			}

			if ( $this->importer_files->get_homepage() ) {
				$page = get_page_by_title( $this->importer_files->get_homepage() );
				if ( isset( $page->ID ) ) {
					update_option( 'page_on_front', $page->ID );
					update_option( 'show_on_front', 'page' );
				}
			}

			$menu = $this->importer_files->get_menus();
			if ( $menu && !empty( $menu ) ) {
				$top_menu = get_term_by( 'name', $menu , 'nav_menu' );
				if ( isset( $top_menu->term_id ) ) {
					set_theme_mod( 'nav_menu_locations', array(
							'wbc907-primary' => $top_menu->term_id,
							'wbc907-footer'  => $top_menu->term_id
						)
					);
				}
			}

			$shop_pages = $this->importer_files->get_woo_pages();
			if ( $shop_pages && is_array( $shop_pages ) && count( $shop_pages ) > 0){
				foreach ($shop_pages as $option => $shop_page) {
					if( get_option( 'woocommerce_'.$option.'_page_id' ) == false ){
						$shop_page = get_page_by_title( $shop_page );
						if ( isset( $shop_page->ID ) ) {
							update_option( 'woocommerce_'.$option.'_page_id', $shop_page->ID);
						}
					}
				}
			}
			

		}


		public function process_import(){
			$this->before_import();
			if ( !empty( $this->theme_xml ) && is_file( $this->theme_xml ) ) {
			  $this->import_xml_content( $this->theme_xml );
			}

			if ( $this->theme_options && is_file( $this->theme_options ) ) {
				$this->set_demo_theme_options( $this->theme_options );
			}

			if ( !empty( $this->theme_widgets ) && is_file( $this->theme_widgets ) ) {
				$this->import_widgets( $this->theme_widgets );
			}
		}



		public function import_xml_content( $file ) {

	      if ( !defined( 'WP_LOAD_IMPORTERS' ) ) define( 'WP_LOAD_IMPORTERS', true );

	      require_once ABSPATH . 'wp-admin/includes/import.php';

	      $importer_error = false;

	      if ( !class_exists( 'WP_Importer' ) ) {

	        $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

	        if ( file_exists( $class_wp_importer ) ) {

	          require_once $class_wp_importer;

	        } else {

	          $importer_error = true;

	        }

	      }

	      if ( !class_exists( 'WP_Import' ) ) {

	        $class_wp_import = dirname( __FILE__ ) .'/importer/wordpress-importer.php';

	        if ( file_exists( $class_wp_import ) )
	          require_once $class_wp_import;
	        else
	          $importer_error = true;

	      }

	      if ( $importer_error ) {

	        die( "Error on import" );

	      } else {

	        if ( !is_file( $file ) ) {

	          echo "The XML file containing the dummy content is not available or could not be read .. You might want to try to set the file permission to chmod 755.<br/>If this doesn't work please use the Wordpress importer and import the XML file (should be located in your download .zip: Sample Content folder) manually ";

	        } else {
	          @set_time_limit(0);
	          $this->WP_Import = new WP_Import();
	          $this->WP_Import->fetch_attachments = true;
	          $this->WP_Import->import( $file );
			}

	      }

	    }


		/**
		 * Available widgets
		 *
		 * Gather site's widgets into array with ID base, name, etc.
		 * Used by export and import functions.
		 *
		 * @since 2.2.0
		 *
		 * @global array $wp_registered_widget_updates
		 * @return array Widget information
		 */
		function available_widgets() {

			global $wp_registered_widget_controls;

			$widget_controls = $wp_registered_widget_controls;

			$available_widgets = array();

			foreach ( $widget_controls as $widget ) {

				if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes

					$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
					$available_widgets[$widget['id_base']]['name'] = $widget['name'];

				}

			}

			return apply_filters( 'radium_theme_import_widget_available_widgets', $available_widgets );

		}

		/**
		 * Import widget JSON data
		 *
		 * @since 2.2.0
		 * @global array $wp_registered_sidebars
		 * @param object  $data JSON widget data from .wie file
		 * @return array Results array
		 */
		public function import_widgets( $file ) {

			global $wp_registered_sidebars;


			$data = file_get_contents( $file );

			$data = json_decode( $data );

			// Have valid data?
			// If no data or could not decode
			if ( empty( $data ) || ! is_object( $data ) ) {
				return;
			}

			// Hook before import
			$data = apply_filters( 'radium_theme_import_widget_data', $data );

			// Get all available widgets site supports
			$available_widgets = $this->available_widgets();

			// Get all existing widget instances
			$widget_instances = array();
			foreach ( $available_widgets as $widget_data ) {
				$widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
			}

			// Begin results
			$results = array();

			// Loop import data's sidebars
			foreach ( $data as $sidebar_id => $widgets ) {

				// Skip inactive widgets
				// (should not be in export file)
				if ( 'wp_inactive_widgets' == $sidebar_id ) {
					continue;
				}

				// Check if sidebar is available on this site
				// Otherwise add widgets to inactive, and say so
				if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
					$sidebar_available = true;
					$use_sidebar_id = $sidebar_id;
					$sidebar_message_type = 'success';
					$sidebar_message = '';
				} else {
					$sidebar_available = false;
					$use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
					$sidebar_message_type = 'error';
					$sidebar_message = __( 'Sidebar does not exist in theme (using Inactive)', 'radium' );
				}

				// Result for sidebar
				$results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
				$results[$sidebar_id]['message_type'] = $sidebar_message_type;
				$results[$sidebar_id]['message'] = $sidebar_message;
				$results[$sidebar_id]['widgets'] = array();

				// Loop widgets
				foreach ( $widgets as $widget_instance_id => $widget ) {

					$fail = false;

					// Get id_base (remove -# from end) and instance ID number
					$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
					$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

					// Does site support this widget?
					if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
						$fail = true;
						$widget_message_type = 'error';
						$widget_message = __( 'Site does not support widget', 'radium' ); // explain why widget not imported
					}

					// Filter to modify settings before import
					// Do before identical check because changes may make it identical to end result (such as URL replacements)
					$widget = apply_filters( 'radium_theme_import_widget_settings', $widget );

					// Does widget with identical settings already exist in same sidebar?
					if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

						// Get existing widgets in this sidebar
						$sidebars_widgets = get_option( 'sidebars_widgets' );
						$sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

						// Loop widgets with ID base
						$single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
						foreach ( $single_widget_instances as $check_id => $check_widget ) {

							// Is widget in same sidebar and has identical settings?
							if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

								$fail = true;
								$widget_message_type = 'warning';
								$widget_message = __( 'Widget already exists', 'radium' ); // explain why widget not imported

								break;

							}

						}

					}

					// No failure
					if ( ! $fail ) {

						// Add widget instance
						$single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
						$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
						$single_widget_instances[] = (array) $widget; // add it

						// Get the key it was given
						end( $single_widget_instances );
						$new_instance_id_number = key( $single_widget_instances );

						// If key is 0, make it 1
						// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
						if ( '0' === strval( $new_instance_id_number ) ) {
							$new_instance_id_number = 1;
							$single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
							unset( $single_widget_instances[0] );
						}

						// Move _multiwidget to end of array for uniformity
						if ( isset( $single_widget_instances['_multiwidget'] ) ) {
							$multiwidget = $single_widget_instances['_multiwidget'];
							unset( $single_widget_instances['_multiwidget'] );
							$single_widget_instances['_multiwidget'] = $multiwidget;
						}

						// Update option with new widget
						update_option( 'widget_' . $id_base, $single_widget_instances );

						// Assign widget instance to sidebar
						$sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
						$new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
						$sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
						update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

						// Success message
						if ( $sidebar_available ) {
							$widget_message_type = 'success';
							$widget_message = __( 'Imported', 'radium' );
						} else {
							$widget_message_type = 'warning';
							$widget_message = __( 'Imported to Inactive', 'radium' );
						}

					}

					// Result for widget instance
					$results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
					$results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = $widget->title ? $widget->title : __( 'No Title', 'radium' ); // show "No Title" if widget instance is untitled
					$results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
					$results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;

				}

			}

			// Hook after import
			do_action( 'radium_theme_import_widget_after_import' );

			// Return results
			return apply_filters( 'radium_theme_import_widget_results', $results );

		}

		public function set_demo_theme_options( $file ) {

			if ( ! file_exists( $file ) ) {
				wp_die(
					__( 'Theme options Import file could not be found. Please try again.', 'radium' ),
					'',
					array( 'back_link' => true )
				);
			}

			// Get file contents and decode
			$data = file_get_contents( $file );
			$data = json_decode( $data, true );
			$data = maybe_unserialize( $data );


			// Only if there is data
			if ( !empty( $data ) || is_array( $data ) ) {
				$data = apply_filters( 'radium_theme_import_theme_options', $data );
				update_option( $this->theme_options_id, $data );
			}

			do_action( 'wbc_importer_after_theme_options_import' );

		}


		public function before_import(){
			add_filter( 'add_post_metadata', array( $this, 'check_previous_meta' ), 10, 5 );
			add_action( 'import_end', array( $this, 'after_wp_importer' ) );
			add_filter( 'wp_import_post_meta', array( $this, 'on_wp_import_post_meta' ) );
			add_action( 'wbc_importer_after_content_import', array( $this, 'after_importer' ) );
		}

		public function on_wp_import_post_meta( $post_meta ) {

			foreach ( $post_meta as &$meta ) {
		          if ( '_elementor_data' === $meta['key'] ) {
		          	$this->elementor_data = true;
		          	$elementor = get_plugins( '/elementor' );
			          	if ( ! empty( $elementor ) && version_compare( $elementor['elementor.php']['Version'], '2.9.13', '>' ) ) {
			          		$meta['value'] = wp_slash( $meta['value'] );
			          	}
			       break;    
	        	}
			}

			return $post_meta;
	    }


		public function check_previous_meta( $continue, $post_id, $meta_key, $meta_value, $unique ) {

			$old_value = get_metadata( 'post', $post_id, $meta_key );

			if ( count( $old_value ) == 1 ) {
				if ( $old_value[0] === $meta_value ) {
					return false;
				}elseif ( $old_value[0] !== $meta_value ) {
					update_post_meta( $post_id, $meta_key, $meta_value );
					return false;
				}
			}

			return null;
		}

		public function update_url_elementor( $old, $new ){
			global $wpdb;

			$from = trim( $old  );
			$to = trim( $new  );

			$is_valid_urls = ( filter_var( $from, FILTER_VALIDATE_URL ) && filter_var( $to, FILTER_VALIDATE_URL ) );
					
			if ( $from != $to && $is_valid_urls) {
				$wpdb->query(
					"UPDATE {$wpdb->postmeta} " .
					"SET `meta_value` = REPLACE(`meta_value`, '" . str_replace( '/', '\\\/', $from ) . "', '" . str_replace( '/', '\\\/', $to ) . "') " .
					"WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE '[%' ;" );
			}
		}

		public function after_wp_importer() {

		    global $wp_rewrite;
			$wp_rewrite->set_permalink_structure('/%postname%/');
			
			update_option( "rewrite_rules", FALSE ); 
			
			$wp_rewrite->flush_rules( true );

			if( $this->elementor_data && is_array( $this->WP_Import->url_remap ) ){
				foreach ($this->WP_Import->url_remap as $old_url => $new_url ) {
					if( strpos( $old_url, 'webcreations907.com' ) !== false ){
						$this->update_url_elementor( $old_url, $new_url );
					}
				}
			}

			if( $this->elementor_data && $this->importer_files->get_preview() ){
				$this->update_url_elementor( untrailingslashit( $this->importer_files->get_preview() ), untrailingslashit( home_url() ) );
			}

			$imported_demos = get_option( 'wbc_imported_demos' );

			$this->active_import[$this->active_import_id]['imported'] = 'imported';

			if ( empty( $imported_demos ) ) {
				$imported_demos = $this->active_import;
			}else {
				$imported_demos = array_merge( $imported_demos , $this->active_import );
			}

			update_option( 'wbc_imported_demos', $imported_demos );
			
			do_action( 'wbc_importer_after_content_import' );

		}

		
		public static function get_instance() {
			return self::$instance;
		}

	}//end class
	new WBC_Demo_Importer;
} //end class exists
