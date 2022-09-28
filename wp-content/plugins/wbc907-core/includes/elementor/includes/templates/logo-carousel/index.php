<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
	
		if( !empty( $atts['item_width'] ) ){
			$this->add_render_attribute( 'wrapper', 'data-item-width', $atts['item_width'] );
		}

		if( !empty( $atts['item_scroll'] ) ){
			$this->add_render_attribute( 'wrapper', 'data-item-scroll', $atts['item_scroll'] );
		}

		if( !empty( $atts['item_min'] ) ){
			$this->add_render_attribute( 'wrapper', 'data-item-min', $atts['item_min'] );
		}

		if( !empty( $atts['item_max'] ) ){
			$this->add_render_attribute( 'wrapper', 'data-item-max', $atts['item_max'] );
		}
		
		$logos = '';
		$count = 0;
		foreach ( $atts['logos'] as $logo ) {
			if( !isset( $logo['logo_image']['url'] ) || empty($logo['logo_image']['url'])) continue;
			$logos .= '<div class="wbc-logo">';
			
			if(isset($logo['logo_image']) && isset($logo['logo_image']['source']) && $logo['logo_image']['source'] =='library'){
				$logo['logo_image_size'] = 'full';
				$logo_img = wp_kses_post( \Elementor\Group_Control_Image_Size::get_attachment_image_html( $logo, 'logo_image' ) );
			}else{
				$logo_img = '<img src="'. esc_attr( $logo['logo_image']['url'] ) .'" alt="'.esc_attr("client logo").'">';
			}

			if ( ! empty( $logo['link']['url'] ) ) {
				$this->add_link_attributes( 'logo_link'.$count , $logo['link'] );
				$logo_img = '<a '.$this->get_render_attribute_string( 'logo_link'.$count ).'>'.$logo_img.'</a>';
			}

			$logos .= $logo_img;

			$logos .= '</div>';
			$count++;
		}
		
		$html  = '<div class="wbc-logo-wrap">';
		$html .= '<div class="wbc-logo-carousel" '.$this->get_render_attribute_string('wrapper').'>';
		$html .= $logos;
		$html .= '</div>';
		$html .= '<a href="#" class="wbc-arrow-buttons logo-prev button btn-primary"><i class="fa fa-angle-left"></i></a>';
		$html .= '<a href="#" class="wbc-arrow-buttons logo-next button btn-primary"><i class="fa fa-angle-right"></i></a>';
		$html .= '</div>';

		echo !empty( $html ) ? $html :'';

?>