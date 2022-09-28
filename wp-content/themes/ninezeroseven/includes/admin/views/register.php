<?php 
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<div class="about-wrap wbc-admin-view">
	<?php $this->view_header_part('registration');?>
	<div class="feature-section">
		<?php

			do_action( 'wbc907_admin_plugins_before');

			if(!$this->is_registered()){
				echo '<div class="wbc-registered-message">';
				echo '<p>'.sprintf(__('Please register your license to unlock the premium plugins and theme demo imports.','ninezeroseven'),admin_url( 'admin.php?page=ninezeroseven-registration' )).'</p>';
				echo'</div>';
			}
			
	?>
	<div class="wbc-register-area">
		<?php 
			do_action('wbc907_admin_register'); 
		?>
			<hr>
			<div class="lead-text wbc-admin-lead-box">
			<?php 
				printf(__('<strong>Please note</strong> as outlined in <a href="%1$s" target="_blank">Envato\'s Standard License</a> that your purchase license grants you to use the product on <strong>1 domain name</strong>, if you require more installs you\'d need to purchase seperate license\'s for each domain name.','ninezeroseven'),'https://themeforest.net/licenses/standard?license=regular');
			?>
			</div>
			<h3><?php esc_html_e('Where To Find Your License Key','ninezeroseven');?></h3>
			<ol class="wbc-register-steps">
			<li><?php 
				printf(__('Go to your <a href="%1$s" target="_blank">Downloads Section on Themeforest</a> and locate your NineZeroSeven(907) theme purchase.','ninezeroseven'),'https://themeforest.net/downloads?filter_by=themeforest.net');
			?>
			</li>
			<li><?php 
				esc_html_e('Click the "Download" button, in the menu that appears choose "License certificate & purchase code" to download.','ninezeroseven');
			?>
			</li>
			<li><?php 
				esc_html_e('Open the "License certificate & purchase code" file and locate "Item Purchase Code:" copy and paste it into input field above and click "Register" button.','ninezeroseven');
			?>
			</li>
			<li><?php 
				esc_html_e('Once activated you\'ll have access to the premium plugins and demo imports.','ninezeroseven');
			?>
			</li>
			</ol>
	</div>
	</div><!--END feature Section-->
</div>