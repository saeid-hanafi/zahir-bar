var $j = jQuery.noConflict();

jQuery(document).ready(function(){
	"use strict";

		$j( document.body ).bind( 'country_to_state_changed', function() {
			// setTimeout(function(){
				$j('body:not(.wbc-noselect-wrap) .page-wrapper select:not([multiple]').each(function(){

	            	if($j(this).parents('.wbc-select-wrap').length == 0){
			            var h = $j(this).parent().find('.select2-container').innerHeight() - 2;

			            $j(this).wrap('<div class="wbc-select-wrap"></div>').after('<div class="wbc-select-arrow" style="line-height:'+h+'px;height:'+h+'px;width:'+h+'px;z-index:100;"></div>');
	            	}
	        	});

			// },1000);
        
    	});

		wbc_add_to_cart();
		wbc_shop_quanity_buttons();

		$j('body.wbc-cart-animate').on('click', '.products .add_to_cart_button', function(){
			var img = $j(this).parents('.wbc-shop-item-wrap').find('img').clone();

			var t = $j(this).parents('.wbc-shop-item-wrap').find('.wbc-shop-image-wrapper').offset().top,
			    l = $j(this).parents('.wbc-shop-item-wrap').find('.wbc-shop-image-wrapper').offset().left, 
			    h = $j(this).parents('.wbc-shop-item-wrap').find('.wbc-shop-image-wrapper img').height(),
			    w = $j(this).parents('.wbc-shop-item-wrap').find('.wbc-shop-image-wrapper img').width();

			var ct = $j('.wbc-shop-cart').offset().top,
				cl = $j('.wbc-shop-cart').offset().left;
			
			img.css({
				'position':'absolute',
				'top': t,
				'left': l,
				'width':w,
				'height':h,
				'opacity':'0.5',
				'z-index':2001
			}).addClass('wbc-animating-to-cart');

			$j('body').append(img);

			$j('.wbc-animating-to-cart').animate({
                'top': ct,
                    'left': cl,
                    'width': 75,
                    'height': 75,

            }, 1000, 'easeInOutExpo').fadeOut("slow",function(){
            	$j(this).remove();
            });
		});

});

function wbc_shop_quanity_buttons(){
	"use strict";
	$j('.quantity input[type=number]').each(function(){
			var el = $j(this);

			el.attr('type', 'text');

			$j('<input type="button" class="minus" title="Minus" value="-">').insertBefore(el);
			$j('<input type="button" class="plus" title="Plus" value="+">').insertAfter(el);


		});

		$j('.minus').on('click',function(){
			var parent = $j(this).parents('.quantity'),
			el         = parent.find('.input-text.qty'),
			cur_num    = el.val(),
			_minus     = ((parseInt(cur_num, 10) - 1) > 0) ? (parseInt(cur_num, 10) - 1) : 1 ;
			
			el.val(_minus);

			$j( 'div.woocommerce > form button[name="update_cart"]' ).prop( 'disabled', false );
		});

		$j('.plus').on('click',function(){
			var parent = $j(this).parents('.quantity'),
			el         = parent.find('.input-text.qty'),
			cur_num    = el.val(),
			_plus      = parseInt(cur_num, 10) + 1 ;
			
			el.val(_plus);

			$j( 'div.woocommerce > form button[name="update_cart"]' ).prop( 'disabled', false );
		});
}

function wbc_add_to_cart(){
	"use strict";

	$j('body').on('click','.add_to_cart_button', function(){
		var parent = $j(this).parents('.product:eq(0)'),
		img_wrap = parent.find('.wbc-shop-image-wrapper');

		parent.addClass('loading-cart').removeClass('loaded-cart');

		img_wrap.find('.wbc-cart-animation').html('').append('<i class="fa fa-spin fa-spinner"></i>');
	});

	$j('body').bind('added_to_cart', function(){
		var parent = $j('.product.loading-cart'),
		img_wrap = parent.find('.wbc-shop-image-wrapper');

		parent.removeClass('loading-cart').addClass('loaded-cart');

		img_wrap.find('.wbc-cart-animation').html('').append('<i class="fa fa-check"></i>');

		setTimeout(function(){
			$j('.product.loaded-cart').find('.wbc-cart-animation').fadeOut(function(){
				$j('.product.loaded-cart').removeClass('loaded-cart');
			$j('.product').find('.wbc-cart-animation').attr('style','').html('');
			});
			
		},1500);
	});

	$j('body').on('updated_cart_totals', function(){
		wbc_shop_quanity_buttons();
	});

}