<?php
/************************************************************************
* WBC-Reuseables template for WPBakery builder.
* This template is only really meant for WPBakery frontend builder
* so that the view is full width and without headers, sidebars, footer, etc
*************************************************************************/
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<!-- Mobile Specific Metas
  ================================================== -->

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

<?php
global $wbc907_data;
wp_head();
?>
</head>

<body <?php body_class(); ?>>

	<!-- Page Wrapper -->
	<div class="page-wrapper">

	    <div class="main-content-area full-width-template">

			<div class="page-content clearfix">
				<?php

					while( have_posts()) : the_post();
						
						the_content();
					
					endwhile;	
				?>
			</div> <!-- ./page-content -->
		<!-- END Main -->
		</div>


	</div> <!-- ./page-wrapper -->

<?php
wp_footer();
?>
</body>
</html>