<?php
/************************************************************************
* Index Template w Right Sidebar
*************************************************************************/
	$has_sidebar = false;
	if( is_active_sidebar( apply_filters( 'wbc907_custom_sidebars' , 'sidebar-1' ) ) === false ){
		$col_span = 'col-sm-12';
	}else{
		$has_sidebar = true;
		$col_span = 'col-md-9';
	}
?>
				<div class="<?php echo esc_attr( $col_span ); ?>">
					<div class="posts <?php echo apply_filters( 'wbc907_blog_layout_class', '' ); ?>">

						<?php

						if ( have_posts() ) : while ( have_posts() ) : the_post();

							get_template_part( 'assets/php/post-formats/entry', get_post_format() );

						endwhile;

						else:

							get_template_part( 'assets/php/misc/no-results' );

						endif;
						?>
					</div> <!-- ./.posts -->

					<?php wbc907_paging_nav(); ?>

				</div><!-- ./right -->

				<!-- SideBar -->

				<?php 
					if($has_sidebar){
				?>
				<div class="col-md-3">
					<?php get_sidebar();?>
				</div>
				<?php 
					}
				?>
