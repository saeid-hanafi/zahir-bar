<?php 
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<div class="about-wrap wbc-admin-view">
	<?php $this->view_header_part('support');?>
	<div class="feature-section wbc-support-section">
	<div class="wbc-register-area">
			<h3><?php esc_html_e('Get Support.','ninezeroseven');?></h3>
			<p>
				<?php 
				esc_html_e('The support forum has been set up to provide you with a better support experience and needed features like image uploading, code inserting/highlighting, search topics, and much much more. If you run into any issues or just have question about a feature, plugin, or something else related to your theme purchase please post on the Theme\'s Support Forum','ninezeroseven');
				?>
			</p>
			<div class="wbc-button-wrap" style="text-align:center;">
				<a class="button button-primary wbc-checkit-out" href="http://support.webcreations907.com/" target="_blank"><?php esc_html_e('Visit Support Forum','ninezeroseven'); ?></a>
				<a class="button button-primary wbc-checkit-out" href="http://support.webcreations907.com/forums/topic/forum-registration/" target="_blank"><?php esc_html_e('How To Sign Up','ninezeroseven'); ?></a>
				<a class="button button-primary wbc-checkit-out" href="http://support.webcreations907.com/search/" target="_blank"><?php esc_html_e('Search Support Forum','ninezeroseven'); ?></a>
			</div>
	</div>
	</div><!--END feature Section-->
</div>
