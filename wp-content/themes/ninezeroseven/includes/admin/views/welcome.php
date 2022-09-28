<?php 
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<div class="about-wrap wbc-welcome wbc-admin-view">
	<?php $this->view_header_part();?>
	<div class="wbc-changelog">
			<a class="button button-secondary" href="#change-log"><?php esc_html_e('View Changelog','ninezeroseven'); ?></a>
		</div>
	<div class="feature-section ">
		
	<div class="one-col">
		<div class="col wbc-main-view">
			<h2><?php esc_html_e( 'Theme version 5.0! Elementor Compatibility Added 🎉','ninezeroseven') ?></h2>
			<p class="text-center"><?php esc_html_e( 'Version 5.0 NineZeroSeven theme now compatible with Elementor page builder. You now have the choice between Elementor and WPBakery :)','ninezeroseven');?></p>
			<div class="text-center"><a class="button button-primary wbc-checkit-out" href="http://themes.webcreations907.com/ninezeroseven/" target="_blank"><?php esc_html_e('Check Out Demos!','ninezeroseven'); ?></a></div>
		</div>
	</div>
	
	<div class="has-3-columns is-fullwidth">
		<div class="column">
			<h3><?php esc_html_e( 'New Demos','ninezeroseven') ?></h3>
			<p><?php printf(__( 'Now 10 Demos for Elementor Page Builder, check them out <a href="%1$s" target="_blank">Here</a>','ninezeroseven'),'http://themes.webcreations907.com/ninezeroseven/');?></p>
		</div>
		<div class="column">
			<h3><?php esc_html_e( 'Elementor Widgets','ninezeroseven') ?></h3>
			<p><?php esc_html_e( '7 NEW widgets added for Elementor page builder, check changelog below.','ninezeroseven');?></p>
		</div>
		<div class="column">
			<h3><?php esc_html_e( 'Demo Importer','ninezeroseven') ?></h3>
			<p><?php printf(__( 'New filter buttons on <a href="%1$s">demo importer page</a> to view demos by page builder. ','ninezeroseven'),esc_url( admin_url( 'admin.php?page=_options&tab=wbc-demo-importer&linked=true' ) ));?></p>
		</div>
	</div>
	
	</div><!--END feature Section-->
	<div class="one-col wbc907-changes" id="change-log">
		<div class="col">
			<h3><?php esc_html_e( 'Change log','ninezeroseven') ?></h3>
			<div class="wbc907-change-log">
			<?php
				echo '<pre><code>';
				include_once(get_template_directory().'/changelog.txt');
				echo '</code></pre>';
			?>
			</div>
		</div>
	</div>

</div>