<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

		global $post, $wbc_carousel_count;

		$temp_post = $post;

		if ( empty( $wbc_carousel_count ) ) $wbc_carousel_count = 0;

		$atts = extract( shortcode_atts(
				array(
					'padding'         => '',
					'portfolio_cats'  => '',
					'img_size'        => 'square',
					'order_by'        => 'name',
					'order_dir'       => 'DESC',
					'post_in'         => '',
					'post_not_in'     => '',
					
					'show_post'       => -1,
					'layout_type'     => '',
					'overlay_color'   => '',
					'link_overlay'    => '',
					
					//Column Settings
					'item_width'      => '',
					'item_min'        => '',
					'item_max'        => '',
					'item_scroll'     => '',
					'item_speed'      => '',
					
					'hide_popup_link' => '',
					'hide_page_link'  => '',
					'hide_title'      => '',
					'title_size'      => '',
					'title_color'     => '',
					'icon_color'      => '',
				), $atts ) );

		$brick_wall = false;

		$heading_css = '';
		$icons_css = '';

		$styleArray = array(
			'font-size'        => $title_size,
			'color'            => $title_color,
		);

		$heading_css = wbc_generate_css( $styleArray );

		if(!empty($heading_css)){
			$heading_css = str_replace(';', ' !important;', $heading_css);
		}

		$styleArray = array(
			'border-color' => $icon_color,
			'color'        => $icon_color,
		);

		$icons_css = wbc_generate_css( $styleArray );

		if(!empty($icons_css)){
			$icons_css = str_replace(';', ' !important;', $icons_css);
		}


		$args = array(
			'post_type'      => 'wbc-portfolio',
			'meta_key'       => '_thumbnail_id',
			'order'          => $order_dir,
			'orderby'        => $order_by,
			'posts_per_page' => $show_post,
		);

		if ( !empty( $portfolio_cats ) ) {
			$args['portfolio-categories'] = $portfolio_cats;
		}

		$style = '';

		if ( !empty( $post_in ) ) {
			$post_ids = explode( ',', $post_in );
			foreach ( $post_ids as $key => $value ) {
				$value = trim( $value );

				if ( !is_numeric( $value ) || empty( $value ) ) {
					unset( $post_ids[$key] );
				}
			}

			$post_ids = array_values( $post_ids );

			$args['post__in'] = $post_ids;
		}

		if ( !empty( $post_not_in ) ) {
			$post_ids = explode( ',', $post_not_in );
			foreach ( $post_ids as $key => $value ) {
				$value = trim( $value );

				if ( !is_numeric( $value ) || empty( $value ) ) {
					unset( $post_ids[$key] );
				}
			}

			$post_ids = array_values( $post_ids );

			$args['post__not_in'] = $post_ids;
		}

		$q = new WP_Query( $args );

		$html = '';

		$count = 0;


		$overlay_style = '';
		if ( !empty( $overlay_color ) ) {
			$overlay_style = 'style="background-color:'.$overlay_color.';"';
		}


		if ( $q->have_posts() ) {

			$data_tags = ' ';
			$data_array = array(
				'item-width' => $item_width,
				'item-scroll' => $item_scroll,
				'item-min' => $item_min,
				'item-max'=> $item_max,
				'item-scroll-speed' => $item_speed,
			);
			foreach ( $data_array as $key => $value ) {

				if ( !empty( $value ) && is_numeric( $value ) ) {
					$data_tags .='data-'.$key.'="'.$value.'" ';
				}
			}

			$html .= '<ul class="wbc-carousel-banner" '.$data_tags.'>';


			while ( $q->have_posts() ) {

				$q->the_post();

				$post_meta = wbc_get_meta( get_the_id() );

				$link_items = '';
				$extra_class = '';

				$html .= '<li>';

				$id = $wbc_carousel_count;

				$content_type = ( isset( $post_meta['opts-portfolio-type'] ) ) ? $post_meta['opts-portfolio-type'] : 'image';

				$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ), 'full' );

				$html .= '<div class="post-featured">';
				$html .= '	<div class="wbc-image-wrap">';
				$html .= '		<a href="'.esc_attr( get_permalink() ).'">';
				$html .=    get_the_post_thumbnail( get_the_id(), $img_size );
				$html .= '		</a>';
				if( $link_overlay ){
					$html .= '		<a class="item-link-overlay" href="'.esc_attr( get_permalink() ).'" '.$overlay_style.'></a>';
				}else {
					$html .= '		<div class="item-link-overlay" '.$overlay_style.'></div>';
				}

				$html .= '		<div class="wbc-extra-links">';

				if($hide_title != 'yes'){
					$html .= '		<h4 class="item-title" '.$heading_css.'>'.get_the_title().'</h4>';
				}

				switch ( $content_type ) {
				case 'video':

					$video_embed_code = ( isset( $post_meta['wbc-portfolio-video-embed'] ) && !empty( $post_meta['wbc-portfolio-video-embed'] ) ) ? $post_meta['wbc-portfolio-video-embed'] : false;

					if ( $video_embed_code !== false ) {
						if ( 1 === preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_embed_code, $matches ) ) {

							$url = 'http://youtube.com/watch?v='.$matches[1];

						}elseif ( 1 === preg_match( '/vimeo.com\/(?:video\/)?([0-9]+)/', $video_embed_code , $matches ) ) {

							$url = 'http://vimeo.com/'.$matches[1];
						}
					}
					if ( isset( $url ) ) {
						$link_items .= '<a data-fancybox title="'.get_the_title( get_the_id() ).'" href="'.esc_attr( $url ).'" class="wbc-photo-up" '.$icons_css.'><i class="fa fa-search"></i></a>';
					}else {
						$link_items .= '<a data-fancybox title="'.get_the_title( get_post_thumbnail_id( get_the_id() ) ).'" href="'.$large_image_url[0].'" class="wbc-photo-up" '.$icons_css.'><i class="fa fa-search"></i></a>';
					}

					break;

				case 'gallery':
					//$id = get_the_id();

					$link_items .= '<a data-fancybox="fancy-lightbox[gallery-'.$id.'-'.get_the_id().']" title="'.get_the_title( get_post_thumbnail_id( get_the_id() ) ).'" href="'.$large_image_url[0].'" data-thumb="'.esc_attr( $large_image_url[0] ).'" class="wbc-photo-up" '.$icons_css.'><i class="fa fa-search"></i></a>';

					$gallery_images = ( isset( $post_meta['wbc-portfolio-gallery-format'] ) && !empty( $post_meta['wbc-portfolio-gallery-format'] ) ) ? $post_meta['wbc-portfolio-gallery-format'] : false;

					if ( $gallery_images !== false ) {
						$gallery_ids = explode( ',', $gallery_images );


						foreach ( $gallery_ids as $image ) {

							$path = wp_get_attachment_image_src( $image, 'large' );

							$link_items .='<a data-fancybox="fancy-lightbox[gallery-'.$id.'-'.get_the_id().']" title="'.get_the_title( $image ).'" href="'.$path[0].'" data-thumb="'.esc_attr( $path[0] ).'" class="wbc-gallery"></a>';

						}
					}
					break;

				default:
					$link_items .= '<a data-fancybox title="'.get_the_title( get_post_thumbnail_id( get_the_id() ) ).'" href="'.$large_image_url[0].'" class="wbc-photo-up" '.$icons_css.'><i class="fa fa-search"></i></a>';
					break;
				}


				if( $hide_popup_link ){
					$link_items = '';
				}

				if( $hide_page_link != 'yes') {
					$link_items .= '			<a href="'.esc_attr( get_permalink() ).'" class="wbc-go-link" '.$icons_css.'><i class="fa fa-link"></i></a>';
				}

				if(isset($post_meta['opts-portfolio-link']) && !empty($post_meta['opts-portfolio-link'])){
					$target = (isset($post_meta['opts-portfolio-link-target']) && $post_meta['opts-portfolio-link-target'] == 1) ? '_self' : '_blank';
					$link_icon = (isset($post_meta['opts-portfolio-link-icon']) && !empty($post_meta['opts-portfolio-link-icon'])) ? $post_meta['opts-portfolio-link-icon'] : 'fa fa-external-link';
					$link_items .= ' <a href="'.esc_url($post_meta['opts-portfolio-link']).'" class="wbc-ext-link" target="'.$target.'" '.$icons_css.'><i class="'.$link_icon.'"></i></a>';
				}

				if(!empty($link_items)){
					$html .= '<div class="wbc-link-content">'. $link_items. '</div>';
				}

				$html .= '		</div>';
				$html .= '	</div>';
				$html .= '</div>';

				$html .= '</li>';


				$count++;

			} // Ends while have posts


			$html .='</ul>';

			$portfolio_wrap = '';

			$portfolio_wrap .= '<div class="wbc-carousel-wrapper">';

			$portfolio_wrap .= $html;

			$portfolio_wrap .='</div>';

			$html = $portfolio_wrap;

		} //Ends if have_posts

		$wbc_carousel_count++;

		$post = $temp_post;


	echo !empty( $html ) ? $html :'';

?>