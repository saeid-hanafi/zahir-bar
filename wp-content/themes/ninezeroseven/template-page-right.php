<?php 
/*
Template Name: Right Sidebar
 */

/* Load Header */
get_header();

$has_sidebar = false;
	if( is_active_sidebar( apply_filters( 'wbc907_custom_sidebars' , 'sidebar-1' ) ) === false ){
		$col_span = 'col-sm-12';
	}else{
		$has_sidebar = true;
		$col_span = 'col-sm-9';
	}
	
?>

		<!-- BEGIN MAIN -->

	    <div class="main-content-area clearfix">

	    	<div class="container">
        
					<div class="row">

						<div class="<?php echo esc_attr( $col_span ); ?>">

							<div class="page-content clearfix">
								<?php 

									while( have_posts()) : the_post();

											the_content();
								?>
							</div> <!-- ./page-content -->

							<?php wp_link_pages(); ?>

							<?php if ( comments_open() || '0' != get_comments_number() ) : ?>
					
								<div class="comment-block">

									<?php comments_template(); ?>

								</div>
								
							<?php endif;?>

							<?php endwhile; ?>


						</div><!-- ./col-sm-9 -->


						<!-- SideBar -->

						<?php 
							if($has_sidebar){
						?>
						<div class="col-sm-3">
							<?php get_sidebar();?>
						</div>
						<?php 
							}
						?>


					</div><!-- ./row -->

				</div><!-- ./container -->

	    <!-- END Main -->
		</div>


<?php
/* Load Footer */
get_footer();
?>