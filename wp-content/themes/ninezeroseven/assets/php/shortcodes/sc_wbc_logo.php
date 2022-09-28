<?php
$atts = extract( shortcode_atts(
				array(
					'logo_image' => '',
					'logo_link'  => '',
					'source'     => '',
					'custom_src' => '',
					'ref_name'   => '',
					'show_name'  => ''
				), $atts ) );

		$has_custom = false;

		if( isset($source) && $source == "external_link" && isset($custom_src) && !empty($custom_src) ){
		    $has_custom = true;
		}

		$link = wbc_build_link( $logo_link );

		$logo_img = wp_get_attachment_image_src( $logo_image , 'full' );
		$name = '';
		$ex_class ='';
		if( !empty( $ref_name ) && $show_name == 'yes' ){
			$name = '<div class="wbc-logo-text">'.$ref_name.'</div>';
			$ex_class = ' wbc-has-name';
		}
		$html  = '';
		$html .= '<div class="wbc-logo'.$ex_class.'">';

		if( $logo_img || $has_custom ){

			if($has_custom == true){
				$logo_html = '<img src="'. esc_attr( $custom_src ).'" alt="'.esc_attr("client logo").'">';
			}else{
				 $logo_html = wp_get_attachment_image($logo_image , 'full', false, ['alt' => get_the_title( $logo_image )] );
			}
			
			if ( isset( $link['url'] ) && !empty( $link['url'] ) ) {
				$target = ( isset( $link['target'] ) && !empty( $link['target'] ) ) ? $link['target'] : '_self';
				$html .= '<a href="'.esc_url( $link['url'] ).'" target="'.trim( esc_attr( $target ) ).'">'.$logo_html.'</a>';
			}else{
				$html .= $logo_html;
			}
		}
		$html .= $name;
		$html .= '</div>';
		

		echo !empty( $html ) ? $html :'';

?>