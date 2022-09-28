<?php
/************************************************************************
* Footer File
*************************************************************************/

do_action( 'wbc907_before_footer' );

if ( !function_exists( 'wbc907_do_template_location' ) ||  ! wbc907_do_template_location( 'footer' ) ) {
global $wbc907_data;

$wbc907_footer_columns = ( isset( $wbc907_data['opts-footer'] ) && is_numeric( $wbc907_data['opts-footer'] ) ) ? $wbc907_data['opts-footer'] : 4;

$footer_class = ( $wbc907_footer_columns == 4 ) ? 'col-sm-6 col-lg-3' : 'col-sm-6 col-lg-4';

//Check for enabled states of footer/widget area/copyright area
$wbc907_footer_enable      = apply_filters( 'wbc907_footer_enable' , true );
$wbc907_widget_area_enable = apply_filters( 'wbc907_widget_area_enable' , true );
$wbc907_copy_area_enable   = apply_filters( 'wbc907_copy_area_enable' , true );
$extra_footer_class = "";

if(isset($wbc907_data['opts-footer-fullwidth']) && $wbc907_data['opts-footer-fullwidth'] == 1){
	$extra_footer_class = " wbc-fullwidth-container";
}

//if footer option true
if ( $wbc907_footer_enable ):
?>
		<!-- Begin Footer -->
		<footer class="main-footer<?php echo esc_attr($extra_footer_class);?>">

		<?php if ( $wbc907_widget_area_enable === true && (is_active_sidebar(  'footer-1' ) || is_active_sidebar(  'footer-2' ) || is_active_sidebar(  'footer-3' ) || is_active_sidebar(  'footer-4' ) && $wbc907_footer_columns == 4)): ?>

			  <div class="widgets-area">
			    <div class="container">
			      <div class="row">


			        <div class="<?php echo esc_attr( $footer_class); ?>">

			          <?php if ( is_active_sidebar(  'footer-1' ) ){
			          	dynamic_sidebar( 'footer-1' );
			          }
			        	?>

			        </div>

					<div class="<?php echo esc_attr( $footer_class); ?>">
			          
			          <?php if ( is_active_sidebar(  'footer-2' ) ){
			          	dynamic_sidebar( 'footer-2' );
			          }
			          ?>

			       	</div>

					<div class="<?php echo esc_attr( $footer_class); ?>">
			          
			          <?php if ( is_active_sidebar(  'footer-3' ) ){
			          		dynamic_sidebar( 'footer-3' );
			          }
			          ?>
			        </div>



			        <?php if ( $wbc907_footer_columns == 4 ): ?>
			          <div class="<?php echo esc_attr( $footer_class); ?>">
			            
			            <?php if ( is_active_sidebar(  'footer-4' ) ){
			          		dynamic_sidebar( 'footer-4' );
			          	}
			          	?>
			          </div>

			        <?php endif; ?>



			      </div>
			    </div> <!-- ./container -->
			  </div>
		  <?php endif; //$wbc907_widget_area_enable ?>

		  <?php if ( $wbc907_copy_area_enable ): ?>

			  <div class="bottom-band">
			    <div class="container">
			      <div class="row">
			        <div class="col-sm-6 copy-info">

			        <?php

						if ( isset( $wbc907_data['opts-footer-credit'] ) && !empty( $wbc907_data['opts-footer-credit'] ) ) {
							
							echo wp_kses_post( do_shortcode( $wbc907_data['opts-footer-credit'] ) );

						}else {
							$footer_text = sprintf( __( '&copy; <a href="%1s">%2s</a> All Rights Reserved %3s - Powered By <a href="http://wordpress.org">WordPress</a>', 'ninezeroseven' ),
								home_url(),
								get_bloginfo( 'name' ),
								date('Y') );

							echo wp_kses_post( $footer_text );
						}

					?>
			        </div>

			        <div class="col-sm-6 extra-info">
			        <?php

						do_action( 'before_extra_info' );

						wp_nav_menu( array(
								'container'       => 'nav',
								'container_class' => 'footer-menu',
								'container_id'    => 'wbc9-footer',
								'menu'            => apply_filters( 'wbc907_page_footer_menu', '' ),
								'menu_id'         => 'footer-menu',
								'menu_class'      => 'wbc_footer_menu',
								'theme_location'  => 'wbc907-footer',
								'fallback_cb'     => ''
							) );

						do_action( 'after_extra_info' );

					?>
			        </div>
			      </div>
			    </div>
			  </div>
		<?php endif; //$copy_area_enable ?>
		</footer>

<?php endif; // wbc907_footer_enable 
}
?>

	</div> <!-- ./page-wrapper -->

<?php

do_action( 'wbc907_after_page_content' );

wp_footer();
?>
</body>
</html>