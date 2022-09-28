<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
		
		echo '<ul class="wbc-price-list">';

		foreach ( $atts['price_list'] as $index => $item ){
			if( ! empty( $item['title'] ) || ! empty( $item['price'] ) || ! empty( $item['item_description'] ) ){
				?>
				<li>
					<div class="wbc-price-list-item">
						<div class="wbc-price-list-text">
							<div class="wbc-price-list-info">
								<?php 
									if( !empty( $item['title'] ) ){
										echo '<span class="wbc-price-list-info-title">'.$item['title'].'</span>';
									}
								  	// print_r($item);
								  	if( 'none' != $atts['separator_style'] ){
										echo '<span class="wbc-price-list-info-sep"></span>';
									}

									if( !empty( $item['price'] ) ){
										echo '<span class="wbc-price-list-info-price">'.$item['price'].'</span>';
									}
								 ?>

							</div>

							<?php 
								if( !empty( $item['item_description'] ) ){
									echo '<div class="wbc-price-list-content">'.$item['item_description'].'</div>';
								}
							 ?>
						</div>
					</div>
				</li>
				<?php
			}
		}

		echo '</ul>';


?>