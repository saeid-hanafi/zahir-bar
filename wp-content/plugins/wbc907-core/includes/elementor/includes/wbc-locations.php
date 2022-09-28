<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 *  WBC907_Locations - WBC907 Theme
 *  @author  Webcreations907
 *
 */
if ( !class_exists( 'WBC907_Locations' ) ) {
	class WBC907_Locations {

		/**
		 * Instance of this class.
		 *
		 * @since    1.0.0
		 *
		 * @var      object
		 */
		protected static $instance = null;


		/**
		 * Fire it up
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

		}


		public function init() {
			
			add_action( 'elementor/theme/register_locations', [ $this , 'register_locations'] );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles'] );
			
		}

		
		public function enqueue_styles(){

		}

		function register_locations( $elementor_theme_manager ) {
			$elementor_theme_manager->register_all_core_location();
			// $elementor_theme_manager->register_location( 'archive' );
		}
		
		public function init_widgets(){
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/blog-posts.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/portfolio.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/portfolio-carousel.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/logo-carousel.php';
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

	WBC907_Locations::get_instance()->init();
}

if( !function_exists('wbc907_do_template_location') ){
	function wbc907_do_template_location( $location ){
		if( empty( $location ) ) return false;

		//ELEMENTOR PRO FIRST
		if( function_exists( 'elementor_location_exits' ) && elementor_location_exits( $location, true ) == true ){
			do_action("wbc907_theme_location_before_{$location}");
			elementor_theme_do_location( $location );
			do_action("wbc907_theme_location_after_{$location}");
			return true;
		}else{
			if( apply_filters('wbc907_theme_location_exists', $location ) === true ){
				do_action("wbc907_theme_location_before_{$location}");
				do_action("wbc907_theme_location_{$location}");
				do_action("wbc907_theme_location_after_{$location}");
				return true;
			}
		}

		return false;
	}
}
