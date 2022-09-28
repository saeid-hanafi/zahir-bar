<?php
/************************************************************************
* Single File
*************************************************************************/

/* Load Header */
get_header();

global $wbc907_data;

$wbc907_page_template = ( isset( $wbc907_data['opts-blog-layout'] ) ) ? esc_html( $wbc907_data['opts-blog-layout'] ) : 'default';
?>

		<!-- BEGIN MAIN -->

	    <div class="main-content-area clearfix">
	    	<?php
	    	if ( !function_exists('wbc907_do_template_location') ||  !wbc907_do_template_location( 'single' ) ) {
					if( is_singular( 'elementor_library' ) ){
						while(have_posts()) : the_post();
						?>

						<article class="single-library-article clr">

						<div class="entry clr">

							<?php the_content(); ?>
						</div>

					</article>

					<?php
					endwhile;
					}else{
						get_template_part( 'assets/php/post-templates/single-template' , $wbc907_page_template );
					}
					 
				}
			?>

		<?php do_action('wbc907_after_single_post_template'); ?>
	    <!-- END Main -->
		</div>


<?php
/* Load Footer */
get_footer();
?>
