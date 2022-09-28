<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (!class_exists('WBC907_Page_Nav_Next_Prev')) {
	class WBC907_Page_Nav_Next_Prev {

		public $previous_post = false;

		public $next_post = false;

		public $did_single_post = false;

		/**
		 * Getting Going ;)
		 */
		public function __construct() {
			add_action('wp', array($this, 'init'));
		}

		/**
		 * Lets do This
		 */
		public function init() {

			add_filter( 'wbc907_do_nav_filter', array($this, 'wbc907_do_nav') , 2 );

			if ( is_single() && !is_admin() && apply_filters( 'wbc907_do_nav_filter', true ) ) {
				$this->previous_post = get_previous_post();
				$this->next_post     = get_next_post();

				add_filter( 'wbc907_post_nav_type', array($this, 'wbc907_nav_style') , 2 );
				$this->add_nav_actions();
			}
		}


		

		/**
		 * Adds actions from theme to output the nav in 
		 *  various places. 
		 */
		public function add_nav_actions(){
			global $wbc907_data;

			if(!isset( $wbc907_data ) || !is_array($wbc907_data)){
				$wbc907_data = get_option( 'wbc907_data' );
			}

			//Floating Nav
			if( isset( $wbc907_data['opts-page-floating-nav'] ) &&  $wbc907_data['opts-page-floating-nav'] == 1){
				add_action( 'wbc907_before_footer', array($this, 'output_nav') , 2 );
			}
				//opts-page-after-main-content-nav
			//opts-page-after-post-nav
			if( isset( $wbc907_data['opts-page-after-post-nav'] ) &&  $wbc907_data['opts-page-after-post-nav'] == 1){

				if( isset( $wbc907_data['opts-page-after-main-content-nav'] ) &&  $wbc907_data['opts-page-after-main-content-nav'] == 1){
					// add_action( 'wbc907_after_single_post_template', array($this, 'output_nav') , 2 );
					add_action( 'wbc907_before_footer', array($this, 'wbc_add_action_nav') , 2 );
					add_action( 'wbc_add_action_nav', array($this, 'output_nav') , 2 );
				}else{
					add_action( 'wbc907_after_single_post', array($this, 'output_nav') , 2 );
					add_action( 'wbc907_after_single_post_template', array($this, 'output_nav') , 2 );
				}
			}
		}

		/**
		 * Need to add new action to theme's footer action
		 */
		public function wbc_add_action_nav(){
			do_action( 'wbc_add_action_nav' );
		}

		/**
		 * Check to see if nav is enabled via theme/post options
		 * @return Bool      return true/false
		 */
		public function wbc907_do_nav( $args ){
			if ( !is_single() || is_admin() ){
				return false;
			}

			global $wbc907_data;

			if(!isset( $wbc907_data ) || !is_array($wbc907_data)){
				$wbc907_data = get_option( 'wbc907_data' );
			}

			if ( !isset( $wbc907_data['opts-page-navigation-enabled'] ) || $wbc907_data['opts-page-navigation-enabled'] != 1 ) {
				return false;
			}

			return true;

		}


		public function wbc907_nav_style( $current_action ){

			if(!isset($current_action) || empty( $current_action ) ) return;


			global $wbc907_data;

			if(!isset( $wbc907_data ) || !is_array($wbc907_data)){
				$wbc907_data = get_option( 'wbc907_data' );
			}


			switch( $current_action ){
				case 'wbc907_before_footer':
					return 'floating-nav';
				break;

				case 'wbc_add_action_nav':
				case 'wbc907_after_single_post_template':
				case 'wbc907_after_single_post':

					$styles = array('wbc-nav-style-2','wbc-nav-style-1');

					if( $this->did_single_post === false ){
						$this->did_single_post = true;
						if( isset( $wbc907_data['opts-page-after-post-nav-style'] ) && in_array($wbc907_data['opts-page-after-post-nav-style'], $styles)){
							return $wbc907_data['opts-page-after-post-nav-style'];
						}

						return 'wbc-nav-style-2';

					}else{
						return false;
					}

				break;

			}

			return $current_action;
		}

		/**
		 * Spits out the code for the nav. :)
		 */
		public function output_nav( ) {
			$nav_type = apply_filters( 'wbc907_post_nav_type' , current_action() );
			if( $nav_type === false ) return;

			switch ( $nav_type ) {
			case 'wbc-nav-style-2':

				if ($this->previous_post || $this->next_post) {
					echo '<div class="wbc-nav-row-2">';
					echo '<div class="container">';
					echo '<div class="row">';
				}
				if ( $this->previous_post ) {
					if($this->next_post){
						echo '<div class="col-6">';
					}else{
						echo '<div class="col-12">';
					}
					echo '<div class="wbc-page-nav wbc-prev-link">';
					echo '<a href="'.esc_url(get_the_permalink( $this->previous_post->ID)).'"><i class="fa fa-angle-left"></i> '.__('PREVIOUS', 'ninezeroseven').'</a>';
					echo '</div>';
					echo '</div>';
				}

				if ( $this->next_post ) {
					if($this->previous_post){
						echo '<div class="col-6">';
					}else{
						echo '<div class="col-12">';
					}
					echo '<div class="wbc-page-nav wbc-next-link">';
					echo '<a href="'.esc_url(get_the_permalink( $this->next_post->ID)).'">'.__('NEXT', 'ninezeroseven').' <i class="fa fa-angle-right"></i></a>';
					echo '</div>';
					echo '</div>';
				}

				if ( $this->previous_post || $this->next_post ) {
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}


				break;
			case 'wbc-nav-style-1':

				if ($this->previous_post || $this->next_post) {
					echo '<div class="wbc-nav-row-1">';
					echo '<div class="container">';
					echo '<div class="row">';
				}
				if ( $this->previous_post ) {
					if($this->next_post){
						echo '<div class="col-6">';
					}else{
						echo '<div class="col-12">';
					}
					echo '<div class="wbc-page-nav wbc-prev-link">';
					echo '<span>'.__('PREVIOUS', 'ninezeroseven').'</span>';
					echo '<h4 class="entry-title wbc-nav-title"><a href="'.esc_url(get_the_permalink( $this->previous_post->ID)).'">'.get_the_title( $this->previous_post->ID ).'</a></h4>';
					echo '</div>';
					echo '</div>';
				}

				if ( $this->next_post ) {
					if($this->previous_post){
						echo '<div class="col-6">';
					}else{
						echo '<div class="col-12">';
					}
					echo '<div class="wbc-page-nav wbc-next-link">';
					echo '<span>'.__('NEXT', 'ninezeroseven').'</span>';
					echo '<h4 class="entry-title wbc-nav-title"><a href="'.esc_url(get_the_permalink( $this->next_post->ID)).'">'.get_the_title( $this->next_post->ID ).'</a></h4>';
					echo '</div>';
					echo '</div>';
				}

				if ( $this->previous_post || $this->next_post ) {
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}

				break;

			default:
				// Previous
				if ( $this->previous_post ) {
					echo '<a class="wbc-page-nav wbc-page-nav-floating wbc-prev-link'.(($this->has_image($this->previous_post->ID)) ? " wbc-has-image" : " wbc-no-image").'" href="'.esc_url(get_the_permalink( $this->previous_post->ID)).'">';
					echo '<span class="wbc-page-nav-icon"><i class="fa fa-angle-left"></i></span>';
					echo '<span class="wbc-nav-wrap">';
					echo '<span class="wbc-nav-content">';
					echo '<span class="wbc-nav-title">'.get_the_title( $this->previous_post->ID ).'</span>';

					if ($this->has_image($this->previous_post->ID)) {
						echo '<span class="wbc-nav-image">';
						echo get_the_post_thumbnail( $this->previous_post->ID, 'thumbnail');
						echo '</span>';
					}

					echo '</span>';
					echo '</span>';
					echo '</a>';

				}

				// Next
				if ( $this->next_post ) {
					echo '<a class="wbc-page-nav wbc-page-nav-floating wbc-next-link'.(($this->has_image($this->next_post->ID)) ? " wbc-has-image" : " wbc-no-image").'" href="'.esc_url(get_the_permalink( $this->next_post->ID)).'">';
					echo '<span class="wbc-page-nav-icon"><i class="fa fa-angle-right"></i></span>';
					echo '<span class="wbc-nav-wrap">';
					echo '<span class="wbc-nav-content">';


					if ($this->has_image($this->next_post->ID)) {
						echo '<span class="wbc-nav-image">';
						echo get_the_post_thumbnail( $this->next_post->ID, 'thumbnail');
						echo '</span>';
					}

					echo '<span class="wbc-nav-title">'.get_the_title( $this->next_post->ID ).'</span>';

					echo '</span>';
					echo '</span>';
					echo '</a>';

				}


				break;
			}
		}

		/**
		 * Checks if post has featured image set.
		 * @return boolean
		 */
		public function has_image( $post_id ) {
			if ( has_post_thumbnail( $post_id ) ) {
				return true;
			}

			return false;
		}

	}

	new WBC907_Page_Nav_Next_Prev;
}

?>