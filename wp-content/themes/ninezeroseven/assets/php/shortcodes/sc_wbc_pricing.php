<?php
		$atts = extract( shortcode_atts(
				array(
					'title'          => '',
					'per_title'      => '',
					'price'          => '',
					'button_text'    => '',
					'text_color'     => '',
					'title_color'    => '',
					'title_bg_color' => '',
					'price_color'    => '',
					'price_bg_color' => '',
					'plan_bg_color'  => '',
					'button_color'   => '',
					'link'           => '',
					'featured'       => '',
				), $atts ) );

		$btn_link = wbc_build_link( $link );

		$ex_class = '';
		if( 'yes' === $featured ){
			$ex_class = ' featured-plan';
		}
		$table_bg_color = '';
		if(!empty($plan_bg_color)){
			$table_bg_color = ' style="background-color:'.esc_attr($plan_bg_color).';"';
		}

		$button_bg_color = '';
		if(!empty($button_color)){
			$button_bg_color = ' style="background-color:'.esc_attr($button_color).';border-color:'.esc_attr($button_color).';"';
		}
		
		$font_table_color = '';
		if(!empty($text_color)){
			$font_table_color = ' style="color:'.esc_attr($text_color).';"';
		}
		$font_price_table_color = '';
		if( !empty($price_color) ){
			$font_price_table_color = ' style="color:'.esc_attr($price_color).';"';
		}

		$font_price_styles = array();
		$font_price_table_color = '';
		if( !empty( $price_color ) ){
			$font_price_styles[] = 'color:'.esc_attr($price_color).';';
		}

		if( !empty( $price_bg_color ) ){
			$font_price_styles[] = 'background-color:'.esc_attr($price_bg_color).';';
		}

		if(count($font_price_styles) > 0){
			$font_price_table_color = ' style="'.join("",$font_price_styles).'"';
		}


		$font_head_styles = array();
		$font_head_table_color = '';
		if( !empty( $title_color ) ){
			$font_head_styles[] = 'color:'.esc_attr($title_color).';';
		}

		if( !empty( $title_bg_color ) ){
			$font_head_styles[] = 'background-color:'.esc_attr($title_bg_color).';';
		}

		if(count($font_head_styles) > 0){
			$font_head_table_color = ' style="'.join("",$font_head_styles).'"';
		}


		$html = '';
		$html .='<div class="wbc-price-table'.esc_attr( $ex_class ).'" '.$font_table_color.'>';
		$html .='<div class="plan-head"  '.$font_head_table_color.'>'.$title.'</div>';
		$html .='<div class="plan-info-wrap" '.$table_bg_color.'>';
		$html .='<div class="plan-price">';
		$html .='<span class="plan-cost" '.$font_price_table_color.'>'.$price.'</span>';
		$html .='<span class="plan-info">'.$per_title.'</span>';
		$html .='</div>';
		$html .= do_shortcode( $content );
		if( isset($btn_link['url']) && !empty($btn_link['url'])){
			$btn_title = (!empty($btn_link['title'])) ? $btn_link['title'] : __('Sign Up Now!', 'ninezeroseven' );
			$html .='<div class="plan-button">';
			$html .='<a class="button btn-primary" '.$button_bg_color.' href="'.esc_url($btn_link['url']).'">'.esc_html( $btn_title ).'</a>';
			$html .='</div>';
		}
		$html .='</div>';
		$html .='</div>';



	echo !empty( $html ) ? $html :'';

?>