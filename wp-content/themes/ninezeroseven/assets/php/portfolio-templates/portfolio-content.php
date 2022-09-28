<?php 

$this_id = get_the_id();

$content = get_the_content();

$link_post_single_image = apply_filters( 'wbc907_link_portfolio_single_images', true );
global $wbc907_data;

$has_image = false;

if(isset($wbc907_data['wbc-image-format']['id']) && !empty($wbc907_data['wbc-image-format']['id'])){
	$this_id = (isset($wbc907_data['wbc-image-format']['id'])) ? $wbc907_data['wbc-image-format']['id'] : $this_id ;
	$has_image = true;
}

?>
<article id="post-<?php the_id();?>" <?php post_class('clearfix');?>>
      
      <?php 

    	if(has_post_thumbnail() || $has_image ){


    		if( $has_image == true ){
    			//Use meta image
    			
    			$large_image_url = wp_get_attachment_image_src( $this_id , 'full' );
    			$thumb_image_url = wp_get_attachment_image_src( $this_id , 'large' );

    			$image_title = get_the_title(  $this_id );

    			$image_html = '<img width="'.$large_image_url[1].'" height="'.$large_image_url[2].'" alt="'. esc_attr($image_title) .'" class="attachment-large wp-post-image" src="'.$thumb_image_url[0].'">';
    		
    		}else{
    			//Use featured image
    			
    			$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $this_id ), 'full' );
    			$image_title = get_the_title( get_post_thumbnail_id( $this_id ) );

    			$image_html = get_the_post_thumbnail( $this_id , 'large' );
    		}

    		echo '<div class="post-featured">';
    		echo '<div class="wbc-image-wrap">';
            if( !is_single() ){
        		echo '<a href="'.esc_attr( get_permalink() ).'">';
        		echo !empty( $image_html ) ? $image_html : '';
        		echo '</a>';
            }else{
               echo !empty( $image_html ) ? $image_html : '';
            }

    		if(!is_single()){
    			echo '		<a class="item-link-overlay" href="'.esc_attr( get_permalink() ).'"></a>';
    		}

            if( $link_post_single_image == true || !is_single() ){
                echo '<div class="item-link-overlay"></div>';
        		echo '		<div class="wbc-extra-links">';
    			echo '			<a data-fancybox title="'. esc_attr($image_title) .'" href="'.$large_image_url[0].'" class="wbc-photo-up"><i class="fa fa-search"></i></a>';
    			if(!is_single()){echo '			<a href="'.esc_attr( get_permalink() ).'" class="wbc-go-link"><i class="fa fa-link"></i></a>';}
    			echo '		</div>';
            }
    		echo '	</div>';
    		echo '</div>';
    	}

       ?>

      <div class="post-contents">
      
	      	<header class="post-header">
		      	<?php 
		      		if(is_single()){
		      			echo '<h1 class="entry-title">'.get_the_title().'</h1>';
		      		}else{
		      			echo '<h2 class="entry-title"><a href="'.esc_attr( get_permalink() ).'">'.get_the_title().'</a></h2>';
		      		}

		      	?>
		        <div class="entry-meta">
                    <span class="date"><i class="far fa-calendar-alt"></i> <?php echo get_the_date( get_option( 'date_format' ) )?></span>
                    <span class="user"><i class="fas fa-user"></i> <?php esc_html_e( 'By', 'ninezeroseven' ); ?> <?php the_author_posts_link(); ?></span>
                    <?php if ( get_post_type() == 'post' ) { ?> <span class="post-in"><i class="fas fa-pencil-alt"></i> <?php esc_html_e( 'In', 'ninezeroseven' ); ?> <?php the_category( ', ' ) ?></span><?php } ?>
                    <span class="comments"><i class="fas fa-comments"></i> <?php comments_number( esc_html__( 'No Comments', 'ninezeroseven' ), esc_html__( '1 Comment', 'ninezeroseven' ), esc_html__( '% Comments', 'ninezeroseven' ) );?></span>
                </div>
	     	</header>

	      <div class="entry-content clearfix">

			<?php 
				if(is_single()){

					echo apply_filters('the_content', $content);

				}else{

					the_excerpt();

					printf('<div class="more-link"><a href="%1s" class="button btn-primary">%2s</a></div>',
							get_permalink(),
							esc_html__('Read More','ninezeroseven')
							);

				}
			?>

		</div>
    </div>

</article> <!-- ./post -->