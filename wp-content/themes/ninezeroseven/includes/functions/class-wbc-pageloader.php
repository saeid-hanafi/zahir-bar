<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'WBC907_Page_Loader' ) ) {
	class WBC907_Page_Loader {

		public $options;

		public $loader_size;

		public $loader_style;

		public function __construct() {
			if ( is_admin() ) {
				return;
			}

			add_action( 'wp', array($this, 'init_settings'), 20);


			if( $this->is_loader_active() ){
				add_filter( 'opts-primary-color', array( $this, 'add_primary_color'));
			}
			
		}

		public function is_loader_active(){
			global $wbc907_data;

			if(!isset( $wbc907_data ) || !is_array($wbc907_data)){
				$wbc907_data = get_option( 'wbc907_data' );
			}

			if (!isset($wbc907_data['opts-page-loader']) || $wbc907_data['opts-page-loader'] != 1) {
				return false;
			}

			return true;
		}

		public function init_settings() {
			global $wbc907_data;

			if (!isset($wbc907_data['opts-page-loader']) || $wbc907_data['opts-page-loader'] != 1) {
				return;
			}

			$this->options = $wbc907_data;

			$post_meta = get_post_meta( get_the_id(), 'opts-page-loader-override', true );
			if ( $post_meta && $post_meta == '1') {

				$this->loader_style = get_post_meta( get_the_id(), 'opts-page-loader-style-override', true );
				$this->loader_size  = get_post_meta( get_the_id(), 'opts-page-loader-size-override', true );

			}else{
				$this->loader_style = $wbc907_data['opts-page-loader-style'];
				$this->loader_size  = $wbc907_data['opts-page-loader-size'];
			}

			$this->loader_size  = ( empty( $this->loader_size ) ) ? '60' : $this->loader_size;
			$this->loader_style = ( empty( $this->loader_style ) ) ? 'wbc-loader-spinner' : $this->loader_style;
			
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles'), 30 );
			add_action( 'wp_head', array( $this, 'custom_css'), 200 );
			add_action( 'wbc907_before_page_content', array( $this, 'output_loader'), 1 );


		}

		public function add_primary_color( $colors ){
			$new_css = array(
			        'background-color' => '.wbc-loader-color,.wbc-loader div .wbc-loader-child-color,.wbc-loader div .wbc-loader-child-color-before:before'
			    );

			return wbc_arrays_to_options( $new_css , $colors );
		}


		public function enqueue_styles(){
			wp_enqueue_style( 'wbc-page-loader', get_template_directory_uri().'/assets/css/wbc-page-loaders.css' );
		}


		public function custom_css(){
			add_filter( 'opts-primary-color', array( $this, 'add_primary_color'));
			if (isset($this->loader_size) && $this->loader_size != 60 && is_numeric($this->loader_size)) {
				echo '<style type="text/css">.'.$this->loader_style.'{width:'.$this->loader_size.'px;height:'.$this->loader_size.'px;}</style>';
			}
		}

		public function output_loader(){
			
			$html  = '';
			$html .=  '<div class="wbc-loader-wrapper">';
			$html .=  '<div class="wbc-loader-content">';

			switch ( $this->loader_style ) {
			case 'wbc-loader-rotating-plane';

				$loader_html = '<div class="wbc-loader-color wbc-loader-rotating-plane"></div>';

				break;

			case 'wbc-loader-double-bounce';

				$loader_html = '<div class="wbc-loader-double-bounce"><div class="wbc-loader-child wbc-loader-child-color wbc-loader-double-bounce1"></div><div class="wbc-loader-child wbc-loader-child-color wbc-loader-double-bounce2"></div></div>';

				break;

			case 'wbc-loader-wave';

				$loader_html = '<div class="wbc-loader-wave"><div class="wbc-loader-rect wbc-loader-child-color wbc-loader-rect1"></div><div class="wbc-loader-rect wbc-loader-child-color wbc-loader-rect2"></div><div class="wbc-loader-rect wbc-loader-child-color wbc-loader-rect3"></div><div class="wbc-loader-rect wbc-loader-child-color wbc-loader-rect4"></div><div class="wbc-loader-rect wbc-loader-child-color wbc-loader-rect5"></div></div>';

				break;

			case 'wbc-loader-wandering-cubes';

				$loader_html = '<div class="wbc-loader-wandering-cubes"><div class="wbc-loader-cube wbc-loader-child-color wbc-loader-cube1"></div><div class="wbc-loader-cube wbc-loader-child-color wbc-loader-cube2"></div></div>';

				break;

			case 'wbc-loader-spinner-pulse';

				$loader_html = '<div class="wbc-loader-color wbc-loader-spinner wbc-loader-spinner-pulse"></div>';

				break;

			case 'wbc-loader-chasing-dots';

				$loader_html = '<div class="wbc-loader-chasing-dots"><div class="wbc-loader-child wbc-loader-child-color wbc-loader-dot1"></div><div class="wbc-loader-child wbc-loader-child-color wbc-loader-dot2"></div></div>';

				break;

			case 'wbc-loader-three-bounce';

				$loader_html = '<div class="wbc-loader-three-bounce"><div class="wbc-loader-child wbc-loader-child-color wbc-loader-bounce1"></div><div class="wbc-loader-child wbc-loader-child-color wbc-loader-bounce2"></div><div class="wbc-loader-child wbc-loader-child-color wbc-loader-bounce3"></div></div>';

				break;

			case 'wbc-loader-circle';

				$loader_html = '<div class="wbc-loader-circle"><div class="wbc-loader-circle1 wbc-loader-child wbc-loader-child-color-before"></div><div class="wbc-loader-circle2 wbc-loader-child wbc-loader-child-color-before"></div><div class="wbc-loader-circle3 wbc-loader-child wbc-loader-child-color-before"></div><div class="wbc-loader-circle4 wbc-loader-child wbc-loader-child-color-before"></div><div class="wbc-loader-circle5 wbc-loader-child wbc-loader-child-color-before"></div><div class="wbc-loader-circle6 wbc-loader-child wbc-loader-child-color-before"></div><div class="wbc-loader-circle7 wbc-loader-child wbc-loader-child-color-before"></div><div class="wbc-loader-circle8 wbc-loader-child wbc-loader-child-color-before"></div><div class="wbc-loader-circle9 wbc-loader-child wbc-loader-child-color-before"></div><div class="wbc-loader-circle10 wbc-loader-child wbc-loader-child-color-before"></div><div class="wbc-loader-circle11 wbc-loader-child wbc-loader-child-color-before"></div><div class="wbc-loader-circle12 wbc-loader-child wbc-loader-child-color-before"></div></div>';

				break;

			case 'wbc-loader-cube-grid';

				$loader_html = '<div class="wbc-loader-cube-grid"><div class="wbc-loader-cube wbc-loader-child-color wbc-loader-cube1"></div><div class="wbc-loader-cube wbc-loader-child-color wbc-loader-cube2"></div><div class="wbc-loader-cube wbc-loader-child-color wbc-loader-cube3"></div><div class="wbc-loader-cube wbc-loader-child-color wbc-loader-cube4"></div><div class="wbc-loader-cube wbc-loader-child-color wbc-loader-cube5"></div><div class="wbc-loader-cube wbc-loader-child-color wbc-loader-cube6"></div><div class="wbc-loader-cube wbc-loader-child-color wbc-loader-cube7"></div><div class="wbc-loader-cube wbc-loader-child-color wbc-loader-cube8"></div><div class="wbc-loader-cube wbc-loader-child-color wbc-loader-cube9"></div></div>';

				break;

			case 'wbc-loader-fading-circle';

				$loader_html = '<div class="wbc-loader-fading-circle"><div class="wbc-loader-circle1 wbc-loader-child-color-before wbc-loader-circle"></div><div class="wbc-loader-circle2 wbc-loader-child-color-before wbc-loader-circle"></div><div class="wbc-loader-circle3 wbc-loader-child-color-before wbc-loader-circle"></div><div class="wbc-loader-circle4 wbc-loader-child-color-before wbc-loader-circle"></div><div class="wbc-loader-circle5 wbc-loader-child-color-before wbc-loader-circle"></div><div class="wbc-loader-circle6 wbc-loader-child-color-before wbc-loader-circle"></div><div class="wbc-loader-circle7 wbc-loader-child-color-before wbc-loader-circle"></div><div class="wbc-loader-circle8 wbc-loader-child-color-before wbc-loader-circle"></div><div class="wbc-loader-circle9 wbc-loader-child-color-before wbc-loader-circle"></div><div class="wbc-loader-circle10 wbc-loader-child-color-before wbc-loader-circle"></div><div class="wbc-loader-circle11 wbc-loader-child-color-before wbc-loader-circle"></div><div class="wbc-loader-circle12 wbc-loader-child-color-before wbc-loader-circle"></div></div>';

				break;


			case 'wbc-loader-folding-cube';

				$loader_html = '<div class="wbc-loader-folding-cube"><div class="wbc-loader-cube1 wbc-loader-cube wbc-loader-child-color-before"></div><div class="wbc-loader-cube2 wbc-loader-cube wbc-loader-child-color-before"></div><div class="wbc-loader-cube4 wbc-loader-cube wbc-loader-child-color-before"></div><div class="wbc-loader-cube3 wbc-loader-cube wbc-loader-child-color-before"></div></div>';

				break;


			default:

				$loader_html = '<div class="wbc-loader-spinner wbc-loader-spinner-pulse"></div>';

				break;



			}

			$html .=  '<div class="wbc-loader">';

			$html .=  $loader_html;

			$html .=  '</div>';

			$html .=  '</div></div>';


			echo ( !empty( $html ) ) ? $html : '';
		}

	}
	new WBC907_Page_Loader();
}

?>