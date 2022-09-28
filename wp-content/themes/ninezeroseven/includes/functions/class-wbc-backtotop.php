<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'WBC907_Back_To_Top' ) ) {
	class WBC907_Back_To_Top {

		public $options;

		public $btt_size;

		public $btt_radius;

		public $icon_style;

		public $loader_style = 'fas fa-arrow-up';

		public function __construct() {
			if ( is_admin() ) {
				return;
			}

			if( $this->is_loader_active() ){
				add_action( 'wp', array($this, 'init_settings'), 20);
				add_filter( 'opts-primary-color', array( $this, 'add_primary_color'));
			}
			
		}

		public function is_loader_active(){
			global $wbc907_data;

			if(!isset( $wbc907_data ) || !is_array($wbc907_data)){
				$wbc907_data = get_option( 'wbc907_data' );
			}

			if (!isset($wbc907_data['opts-page-btt']) || $wbc907_data['opts-page-btt'] != 1) {
				return false;
			}

			return true;
		}

		public function init_settings() {
			global $wbc907_data;

			$this->options = $wbc907_data;

			$post_meta = false;// get_post_meta( get_the_id(), 'opts-page-loader-override', true );
			if ( $post_meta && $post_meta == '1') {

				// $this->loader_style = get_post_meta( get_the_id(), 'opts-page-loader-style-override', true );
				// $this->btt_size  = get_post_meta( get_the_id(), 'opts-page-loader-size-override', true );

			}else{
				$this->btt_size   = ( isset( $wbc907_data['opts-page-btt-size'] ) ) ? $wbc907_data['opts-page-btt-size'] : '';
				$this->btt_radius = ( isset( $wbc907_data['opts-page-btt-radius'] ) ) ? $wbc907_data['opts-page-btt-radius'] : '';
				$this->icon_style = ( isset( $wbc907_data['opts-page-btt-icon'] ) ) ? $wbc907_data['opts-page-btt-icon'] : '';
				$this->icon_size  = ( isset( $wbc907_data['opts-page-btt-icon-size'] ) ) ? $wbc907_data['opts-page-btt-icon-size'] : '';
			}


			$this->btt_radius = ( empty( $this->btt_radius ) ) ? '3' : $this->btt_radius;
			$this->icon_style = ( empty( $this->icon_style ) ) ? 'fas fa-arrow-up' : $this->icon_style;
			$this->icon_size  = ( empty( $this->icon_size ) ) ? '30' : $this->icon_size;
			$this->btt_size   = ( empty( $this->btt_size ) ) ? '60' : $this->btt_size;
			
			add_action( 'wp_head', array( $this, 'custom_css'), 200 );
			add_action( 'wbc907_after_page_content', array( $this, 'output'), 30 );


		}

		public function add_primary_color( $colors ){
			$new_css = array(
			        'background-color' => '.wbc-backtotop-button',
			        'color' => '.wbc-backtotop-button:hover'
			    );

			return wbc_arrays_to_options( $new_css , $colors );
		}


		public function custom_css(){
			global $wbc907_data;
			add_filter( 'opts-primary-color', array( $this, 'add_primary_color'));
			
			$css_ouput = '';
			
			if (isset($this->btt_size) && $this->btt_size != 60 && is_numeric($this->btt_size)) {
				$css_ouput .= '.wbc-backtotop-button{width:'.$this->btt_size.'px;height:'.$this->btt_size.'px;}';
				$css_ouput .= '.wbc-backtotop-button .wbc-font-icon{line-height:'.$this->btt_size.'px}';
			}

			if (isset($this->btt_radius) && $this->btt_radius != 3 && is_numeric($this->btt_radius)) {
				$css_ouput .= '.wbc-backtotop-button{border-radius:'.$this->btt_radius.'px;}';
			}

			if (isset($this->icon_size) && $this->icon_size != 30 && is_numeric($this->icon_size)) {
				$css_ouput .= '.wbc-backtotop-button .wbc-font-icon{font-size:'.$this->icon_size.'px;}';
			}
			
			if( !empty( $css_ouput ) ){
				echo '<style type="text/css">';
				echo esc_html( $css_ouput );
				echo '</style>'; 
			}
		}
		public function data_tags(){
			$data_tags = array();
			global $wbc907_data;

			$attributes = '';
			if (isset($wbc907_data['opts-page-btt-offset']) && $wbc907_data['opts-page-btt-offset'] != 300 && is_numeric( $wbc907_data['opts-page-btt-offset'] )) {
				$data_tags[] = 'data-top-offset="'.esc_attr( $wbc907_data['opts-page-btt-offset'] ).'"';
			}


			if (isset( $wbc907_data['opts-page-anchor'] ) && !empty( $wbc907_data['opts-page-anchor'] ) ) {
				$data_tags[] = 'data-anchor="'.esc_attr( $wbc907_data['opts-page-anchor'] ).'"';
			}

			if (isset($wbc907_data['opts-page-btt-duration']) && $wbc907_data['opts-page-btt-duration'] != 1500 && is_numeric( $wbc907_data['opts-page-btt-duration'] )) {
				$data_tags[] = 'data-duration="'.esc_attr( $wbc907_data['opts-page-btt-duration'] ).'"';
			}

			if (isset($wbc907_data['opts-page-btt-animation']) && !empty( $wbc907_data['opts-page-btt-animation'] ) && $wbc907_data['opts-page-btt-animation'] != 'swing' ) {
				$data_tags[] = 'data-animation="'.esc_attr( $wbc907_data['opts-page-btt-animation'] ).'"';
			}
			

			if( count( $data_tags ) > 0){
				$attributes = join(" ", $data_tags);
			}
			return $attributes;
		}

		public function classes( $class = ''){
			global $wbc907_data;

			if ( isset( $wbc907_data['opts-page-btt-position'] ) && !empty( $wbc907_data['opts-page-btt-position'] ) && $wbc907_data['opts-page-btt-position'] != 'wbc-btt-bottom-right' ) {
				$class = $class." ".$wbc907_data['opts-page-btt-position'];
			}

			return $class;
		}
		public function output(){
			global $wbc907_data;
			$html  = '';
			$html .= '<div class="'.esc_attr($this->classes('wbc-backtotop-button')).'" '.$this->data_tags().'>';

			$html .= apply_filters( 'wbc907_backtotop_icon', '<i class="wbc-font-icon '.$this->icon_style.'"></i>' );
			
			$html .= '</div>';

			echo ( !empty( $html ) ) ? $html : '';
		}

	}
	new WBC907_Back_To_Top();
}

?>