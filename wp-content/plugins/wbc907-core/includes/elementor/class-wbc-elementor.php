<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 *  WBC_Elementor_Class - WBC907 Theme
 *  @author  Webcreations907
 *
 */
if ( !class_exists( 'WBC_Elementor_Class' ) ) {
	class WBC_Elementor_Class {

		/**
		 * Instance of this class.
		 *
		 * @since    1.0.0
		 *
		 * @var      object
		 */
		protected static $instance = null;

		/**
		 * Holds shortcodes loaded in.
		 *
		 * @var array
		 */
		protected $wbc_shortcodes =  array();


		/**
		 * Fire it up
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

		}


		public function init() {
			if( class_exists('Wbc_Plugin_Admin_Init') && Wbc_Plugin_Admin_Init::get_instance()->is_registered_action('elementor') ){
				$this->includes();
				add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
				add_action( 'elementor/elements/categories_registered', [ $this , 'add_category'] );
			}
		}


		public function includes(){
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/wbc-elementor-extend.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/wbc-locations.php';
		}

		public function add_category(){
			\Elementor\Plugin::instance()->elements_manager->add_category(
		        'wbc-ninezeroseven',
		        array(
		          'title' => '907 Theme',
		          'icon'  => 'fonts',
		    ));
		}


		public function init_widgets(){
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/blog-posts.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/portfolio.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/portfolio-carousel.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/logo-carousel.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/service-box.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/team-member.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/heading.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/testimonial-carousel.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/animated-chart.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/progress-bar.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/featured-content.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/contact-form7.php';
			include WBC_INCLUDES_DIRECTORY.'elementor/includes/widgets/menu-list.php';
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

	WBC_Elementor_Class::get_instance()->init();
}