<?php
	$url = $target = $html = '';

	$atts = extract( shortcode_atts(
			array(
				'url' => '',
				'target'    => '',
			), $atts ) );

	if ( !empty( $url ) ) {
		$target = ( isset( $link['target'] ) && !empty( $link['target'] ) ) ? $link['target'] : '_self';
		$html = '<a href='.esc_attr( $url ).' target="'.esc_attr( $target ).'">'.do_shortcode( $content ).'</a>';
	}

	echo !empty( $html ) ? $html :'';

?>
