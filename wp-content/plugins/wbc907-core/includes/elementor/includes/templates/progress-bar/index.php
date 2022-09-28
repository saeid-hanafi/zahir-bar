<?php
		$atts = extract( shortcode_atts(
				array(
					//Title
					'title'             => esc_html__('Title', 'ninezeroseven' ),
					'title_font_size'   => '',
					'title_color'       => '',
					//Percent
					'percent'           => '50',
					'percent_color'     => '',
					'percent_font_size' => '',
					'bar_height'        => '',
					'bar_color'         => '',
					'bg_color'          => '',
					'bg_spacing'        => '',
					'border_radius'     => ''
				), $atts ) );


		$data_tags = ' ';

		$data_array = array(
			'percent'  => (is_numeric($percent['size'])) ? $percent['size'] : 0,
		);

		foreach ( $data_array as $key => $value ) {

			if ( !empty( $value ) && is_numeric( $value ) ) {
				$data_tags .='data-'.$key.'="'.$value.'" ';
			}
		}
		
		$html  = '<div class="wbc-progress-wrap init-progress">';

		$html .= '<div class="wbc-progress-info">';
		$html .= '<span class="wbc-progress-title">'.$title.'</span>';
		$html .= '<span class="wbc-progress-percent">0%</span>';
		$html .= '</div>';

		$html .= '<div class="wbc-progress-back">';
		$html .= '<div class="wbc-progress"'.$data_tags.'></div>';
		$html .= '</div>';
		$html .= '</div>';


	echo !empty( $html ) ? $html :'';

?>