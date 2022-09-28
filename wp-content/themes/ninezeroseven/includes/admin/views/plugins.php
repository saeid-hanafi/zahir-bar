<?php 
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<div class="wbc-admin-plugin-messages">
<?php 
global $wbc907tgmpa;

	if ( isset( $_GET['tgmpa-install'] ) || isset( $_GET['tgmpa-update'] ) || isset( $_GET['tgmpa-activate'] ) ) {
		echo '<div class="wbc-tgmpa-wrap">';
		//should use do_plugin_install(), but is protected. Extcl?
		$wbc907tgmpa->install_plugins_page();

		echo '</div>';

		// echo '<script>window.onload=function(){window.location.href = "'.esc_url($wbc907tgmpa->get_tgmpa_url()).'";}</script>';

	}else if(isset( $_GET['tgmpa-deactivate'] ) ){
		
		if(isset($wbc907tgmpa->plugins[$_GET['plugin']]) && is_array($wbc907tgmpa->plugins[$_GET['plugin']]) && isset($wbc907tgmpa->plugins[$_GET['plugin']]['file_path']) && !empty($wbc907tgmpa->plugins[$_GET['plugin']]['file_path'])){
			if( wp_verify_nonce( $_GET['tgmpa-nonce'], 'tgmpa-deactivate' )){
				deactivate_plugins($wbc907tgmpa->plugins[$_GET['plugin']]['file_path']);
				 wp_redirect( admin_url( 'admin.php?page=ninezeroseven-plugins' ) );
			}
		}
	}

?>
</div>
<div class="about-wrap wbc-admin-view">
	<?php $this->view_header_part('plugins');?>
	<div class="feature-section">
		<?php 

			do_action( 'wbc907_admin_plugins_before');

			if(!$this->is_registered()){
				echo '<div class="wbc-registered-message">';
				echo '<p>'.sprintf(__('Whoops! Looks like you need to <a href="%1$s">register your license</a> for NineZeroSeven Theme to unlock the premium plugins and other features.','ninezeroseven'),admin_url( 'admin.php?page=ninezeroseven-registration' )).'</p>';
				echo '</div>';
			}
		?>
		<div class="theme-browser rendered">
				<div class="themes wp-clearfix">
	
	<?php 

		foreach ($wbc907tgmpa->plugins as $plugin => $values) {
			$link = $this->get_action_link( $wbc907tgmpa->plugins[$plugin] );
			$type = '';
			$has_update = false;
			if(is_array($link) && count($link)>0){
				if(array_key_exists( 'update' , $link)){
					$type   = 'update';
					$p_link = $link['update'];
					$r_link = $link['raw_url'];
					$has_update = true;
				}else if(array_key_exists( 'install' , $link)){
					$type   = 'install';
					$p_link = $link['install'];
					$r_link = $link['raw_url'];
				}else if(array_key_exists( 'activate' , $link)){
					$type   = 'activate';
					$p_link = $link['activate'];
					$r_link = $link['raw_url'];
				}else  if(array_key_exists( 'deactivate' , $link)){
					$type   = 'deactivate';
					$p_link = $link['deactivate'];
					$r_link = $link['raw_url'];
				}else  if(array_key_exists( 'premium' , $link)){
					$type   = 'premium';
					$p_link = $link['premium'];
					$r_link = $link['raw_url'];
				}else{
					$type   = 'none';
					$p_link = '<a href="#"></a>';
					$r_link = '#';
				}

			}else{
					$type   = 'active';
					$p_link = '<a href="#"></a>';
					$r_link = '#';
			}

			$class = ($type =='deactivate') ? 'active' : $type;
			$class = ($has_update) ? $class.' has-update' : $class;

			$version = $wbc907tgmpa->get_installed_version($values['slug']);
			?>
			
					<div class="theme <?php echo esc_attr($class);?>" tabindex="0">
						<div class="theme-screenshot">

							<?php 
								if(isset($values['required']) && $values['required']){
									echo '<div class="plugin-required">Required</div>';
								}else if(isset($type) && $type == 'premium' || isset($values['source']) && $values['source'] == 'premium'){
									echo '<div class="plugin-locked dashicons-before dashicons-lock"></div>';
								}

								if(isset($values['screen-image']) && !empty($values['screen-image'])){
									echo '<img src="'.esc_url($values['screen-image']).'" alt="'.esc_attr($values['name']).'">';
								}

								if(!empty($version)){
									echo '<span class="version-info">'.esc_html('version','ninezeroseven').': '.$version.'</span>';
								}
							?>
							
						</div>

						<?php 
							if( $has_update ){
								echo  '<div class="update-message notice inline notice-warning notice-alt"><p>'.esc_html__(' New Update Available!.','ninezeroseven').'</p></div>';
							}
						?>
						
						<span class="more-details">
							<!-- <div class="theme-actions"> -->
							<a class="button button-primary" href="<?php echo esc_url($r_link);?>"><?php echo esc_html($type);?></a>
							<!-- </div> -->
						</span>
						<div class="theme-author"></div>

						<div class="theme-id-container">
				
							<h3 class="theme-name">
								<?php echo esc_html( $values['name'] ); ?>
							</h3>
							
							<div class="theme-actions">
								<span class="spinner"><?php echo esc_html( 'Please Wait..','ninezeroseven' ); ?></span>
							<a class="button button-primary" href="<?php echo esc_url($r_link);?>"><?php echo esc_html($type);?></a>
							</div>
						</div>
					</div>
				

			<?php
		}//END FOR EACH
	?>
		</div>
			</div>
			<?php 
				do_action( 'wbc907_admin_plugins_after');
			?>
	</div><!--END feature Section-->
</div>