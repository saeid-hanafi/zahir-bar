<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
	
		if( !empty( $atts['item_height'] ) ){
			echo $atts['item_height'];
			$auto_height = ($atts['item_height'] == 'yes') ? 'auto' : 'variable';
			$this->add_render_attribute( 'wrapper', 'data-item-height', $auto_height);
		}

		if( !empty( $atts['item_speed'] ) ){
			$speed = (!empty($atts['item_speed']) && is_numeric($atts['item_speed'])) ? $atts['item_speed'] : '7000';
			$this->add_render_attribute( 'wrapper', 'data-item-speed', $speed );
		}
		
		$testimonails = '';
		$count = 0;
		foreach ( $atts['testimonials'] as $logo ) {
		
			$wbc_color_before = ( !empty( $wbc_color ) ) ? '[wbc_color color="'.$wbc_color.'"]' : '[wbc_color]';
			$wbc_color_after = '[/wbc_color]';

			$user_name = $logo['name'];

			if ( preg_match_all( "/\|([^\|]+)\|/", $user_name, $matches, PREG_SET_ORDER ) !== false ) {

				foreach ( $matches as $key => $value ) {
					if ( !empty( $matches[$key][0] ) && !empty( $matches[$key][1] ) ) {
						$user_name = str_replace( $matches[$key][0], $wbc_color_before.$matches[$key][1].$wbc_color_after, $user_name );
					}
				}
			}

			$user_message = $logo['testimonial_content'];
			if ( preg_match_all( "/\|([^\|]+)\|/", $user_message, $matches, PREG_SET_ORDER ) !== false ) {

				foreach ( $matches as $key => $value ) {
					if ( !empty( $matches[$key][0] ) && !empty( $matches[$key][1] ) ) {
						$user_message = str_replace( $matches[$key][0], $wbc_color_before.$matches[$key][1].$wbc_color_after, $user_message );
					}
				}
			}
			
			$user_image_html = '';
			if(isset($logo['image']['id']) && !empty( $logo['image']['id'] ) ){
				$user_image = $logo['image']['id'];
				$user_img = wp_get_attachment_image_src( $user_image , 'thumbnail' );
				$user_image_html = '<img src="'. esc_attr( $user_img[0] ).'" alt="'. esc_attr( get_the_title( $user_image ) ).'" width="'. esc_attr( $user_img[1] ).'" height="'. esc_attr( $user_img[2] ).'">';
			}elseif(isset($logo['image']['url']) && !empty( $logo['image']['url'])){
				$user_image_html = '<img src="'. esc_attr( $logo['image']['url'] ).'" alt="'. esc_attr( 'Custom Image' ).'">';
			}

			$testimonails .= '<div>';
			$testimonails .= '<div class="wbc-testimonial">';
			$testimonails .= '<span class="testimonial-message">'. do_shortcode( $user_message ) .'</span>';
			$testimonails .= $user_image_html;
			$testimonails .= '<div class="testimonial-info">';
			$testimonails .= '<div class="testimonial-name">'. do_shortcode( $user_name ) .'</div>';
			$testimonails .= '<small>'.$logo['position'].'</small>';
			$testimonails .= '</div>';
			$testimonails .= '</div>';
			$testimonails .= '</div>';



			$count++;
		}

		$html  = '<div class="wbc-testimonial-wrap">';
		$html .= '<div class="wbc-testimonail-carousel" '.$this->get_render_attribute_string('wrapper').'>';
		$html .= do_shortcode($testimonails);
		$html .= '</div>';
		$html .= '<div class="wbc-testimonial-nav">';
		$html .= '<a href="#" class="wbc-arrow-buttons carousel-prev button btn-primary"><i class="fa fa-angle-left"></i></a>';
		$html .= '<a href="#" class="wbc-arrow-buttons carousel-next button btn-primary"><i class="fa fa-angle-right"></i></a>';
		$html .= '</div>';
		$html .= '</div>';

		echo !empty( $html ) ? $html :'';

?>