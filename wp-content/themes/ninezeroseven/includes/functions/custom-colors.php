<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//Primary Colors
if(!function_exists('wbc_primary_colors')){
	function wbc_primary_colors( $colors ){
		
		$new_css = array(
			        'background-color' => '.wbc-loader-color,.wbc-loader div .wbc-loader-child-color,.wbc-loader div .wbc-loader-child-color-before:before,.wpb-js-composer .vc_tta-color-wbc-theme-primary-color.vc_tta-style-flat .vc_tta-tab.vc_active > a,.wpb-js-composer .vc_general.vc_tta-color-wbc-theme-primary-color.vc_tta-style-flat .vc_tta-tab > a,.wpb-js-composer .vc_tta-color-wbc-theme-primary-color.vc_tta-style-flat.vc_tta-tabs .vc_tta-panels, .wpb-js-composer .vc_tta-color-wbc-theme-primary-color.vc_tta-style-classic .vc_tta-tab > a,.wpb-js-composer .vc_tta-color-wbc-theme-primary-color .vc_tta-panel .vc_tta-panel-heading,.wbc-icon-box:hover .wbc-icon-style-4 .wbc-icon,.wbc-icon-style-4:hover .wbc-icon,.wbc-icon-box:hover .wbc-icon-style-3 .wbc-icon,.wbc-icon-style-2 .wbc-icon,.wbc-icon-style-3:hover .wbc-icon,.wbc-price-table .plan-cost::before, .wbc-price-table .plan-cost::after, .wbc-price-table .plan-head,.wbc-service:hover .wbc-hr,.top-extra-bar, .btn-primary,.item-link-overlay,.quote-format, a.link-format,.flex-direction-nav .flex-prev, .flex-direction-nav .flex-next,.flex-direction-nav .flex-prev, .flex-direction-nav .flex-next,.wbc-icon.icon-background,input[type="submit"],.widget_tag_cloud a,.wbc-pagination a, .wbc-pagination span,.wbc-pager a',
			        'border-color' => '.wpb-js-composer .vc_tta-color-wbc-theme-primary-color.vc_tta-style-classic .vc_tta-tab > a,.wpb-js-composer .vc_tta-color-wbc-theme-primary-color .vc_tta-panel .vc_tta-panel-heading,.wbc-icon-style-1:hover,.wbc-icon-box:hover .wbc-icon-style-1,.wbc-icon-style-2,.wbc-icon-style-3,.wbc-icon.icon-outline,.top-extra-bar, .btn-primary,input[type="submit"],.wbc-pagination a:hover, .wbc-pagination span:hover,.wbc-pagination .current,.wbc-pager a:hover',
			        'color' => '.primary-menu .wbc_menu > li.current-menu-item > a,.primary-menu .wbc_menu a:hover,.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .mobile-nav-menu .wbc_menu a:hover,.wbc-icon-box:hover .wbc-icon-style-1 .wbc-icon,.wbc-icon-style-1:hover .wbc-icon, .wbc-icon-box:hover .wbc-icon-style-2 .wbc-icon,.wbc-icon-style-2:hover .wbc-icon,.wbc-icon-style-3 .wbc-icon,.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .menu-icon:hover, .has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .menu-icon.menu-open, .has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .wbc_menu > li > a:hover, .has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .wbc_menu > li.active > a,.wbc-service:hover .wbc-icon,.wbc-service:hover .service-title,.wbc-content-loader,.mobile-nav-menu .wbc_menu a:hover,.mobile-menu .wbc_menu li.mega-menu ul li a:hover,.menu-icon:hover, .menu-icon.menu-open,a,a:hover,.wbc_menu a:hover, .wbc_menu .active > a, .wbc_menu .current-menu-item > a,.wbc-color,.logo-text a:hover,.entry-title a:hover,.pager li > a, .pager li > a:focus, .pager li > a:hover, .pager li > span'
			    );


		return wbc_arrays_to_options( $new_css , $colors );
	}

	add_filter( 'opts-primary-color', 'wbc_primary_colors' );

}

if(!function_exists('wbc_buttons_text_color')){
	function wbc_buttons_text_color( $colors ){
		
		$new_css = array(
				    'color' => '.flex-direction-nav a:before,.woocommerce #respond input#submit,.woocommerce a.button,.woocommerce button.button,.btn-primary, input[type="submit"], .wbc-pagination a, .wbc-pagination span, .wbc-pagination .current, .wbc-pager a',
				);


		return wbc_arrays_to_options( $new_css , $colors );
	}

	add_filter( 'opts_buttons_text_color', 'wbc_buttons_text_color' );

}


if(!function_exists('wbc_buttons_text_hover_color')){
	function wbc_buttons_text_hover_color( $colors ){
		
		$new_css = array(
				    'color' => '.flex-direction-nav a:hover:before,.woocommerce #respond input#submit:hover,.woocommerce a.button:hover,.woocommerce button.button:hover,.wbc-pagination .current,.btn-primary:hover, input[type="submit"]:hover, .wbc-pagination a:hover, .wbc-pagination span:hover, .wbc-pagination .current:hover, .wbc-pager a:hover,.wbc-filter .btn-primary.selected',
				);


		return wbc_arrays_to_options( $new_css , $colors );
	}

	add_filter( 'opts_buttons_text_hover_color', 'wbc_buttons_text_hover_color' );

}




if(!function_exists('wbc_buttons_bg_hover_color')){
	function wbc_buttons_bg_hover_color( $colors ){
		
		$new_css = array(
				    'background-color' => '.flex-direction-nav .flex-prev:hover,.flex-direction-nav .flex-next:hover,.woocommerce #respond input#submit:hover,.woocommerce a.button:hover,.woocommerce button.button:hover,.wbc-pagination .current,.btn-primary:hover, input[type="submit"]:hover, .wbc-pagination a:hover, .wbc-pagination span:hover, .wbc-pagination .current:hover, .wbc-pager a:hover,.wbc-filter .btn-primary.selected',
				    'border-color' => '.flex-direction-nav .flex-prev:hover,.flex-direction-nav .flex-next:hover,.woocommerce #respond input#submit:hover,.woocommerce a.button:hover,.woocommerce button.button:hover,.wbc-pagination .current,.btn-primary:hover, input[type="submit"]:hover, .wbc-pagination a:hover, .wbc-pagination span:hover, .wbc-pagination .current:hover, .wbc-pager a:hover,.wbc-filter .btn-primary.selected',
				);


		return wbc_arrays_to_options( $new_css , $colors );
	}

	add_filter( 'opts_buttons_bg_hover_color', 'wbc_buttons_bg_hover_color' );

}

if(!function_exists('wbc_buttons_bg_color')){
	function wbc_buttons_bg_color( $colors ){
		
		$new_css = array(
				    'background-color' => '.flex-direction-nav .flex-prev,.flex-direction-nav .flex-next,.woocommerce #respond input#submit,.woocommerce a.button,.woocommerce button.button,.btn-primary, input[type="submit"], .wbc-pagination a, .wbc-pagination span, .wbc-pagination .current, .wbc-pager a',
				    'border-color' => '.flex-direction-nav .flex-prev,.flex-direction-nav .flex-next,.woocommerce #respond input#submit,.woocommerce a.button,.woocommerce button.button,.wpb-js-composer .vc_tta-color-wbc-theme-primary-color.vc_tta-style-classic .vc_tta-tab > a, .wpb-js-composer .vc_tta-color-wbc-theme-primary-color .vc_tta-panel .vc_tta-panel-heading, .wbc-icon-style-1:hover, .wbc-icon-box:hover .wbc-icon-style-1, .wbc-icon-style-2, .wbc-icon-style-3, .wbc-icon.icon-outline, .btn-primary, input[type="submit"], .wbc-pagination a:hover, .wbc-pagination span:hover, .wbc-pagination .current, .wbc-pager a:hover',
				);


		return wbc_arrays_to_options( $new_css , $colors );
	}

	add_filter( 'opts_buttons_bg_color', 'wbc_buttons_bg_color' );

}

//Page Content colors
if(!function_exists('wbc_page_content_color')){
	function wbc_page_content_color( $colors ){
		$new_css = array(
					'background-color'    => '.menu-bar-wrapper,.author-wrap,.gallery-item,.blog-style-3 .post-contents, .page-title-wrap,.pager li > a, .pager li > a:focus, .pager li > a:hover, .pager li > span',
					'border-color'        => 'blockquote,.post-comments .comment,.single .wbc-portfolio,.post',
					'border-bottom-color' =>'.widget ul li'
				);


		return wbc_arrays_to_options( $new_css , $colors );
	}

	add_filter( 'opts-page-content-color', 'wbc_page_content_color' );

}
?>