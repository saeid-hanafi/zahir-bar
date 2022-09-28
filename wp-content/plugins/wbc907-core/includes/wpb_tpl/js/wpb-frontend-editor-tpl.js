(function($) {
    'use strict';

    $('.wbc907-templates-container').on('scroll',function(){
        var parent_pos = $(this).scrollTop() + $(this).offset().top,
            parent_height = $(this).height();

        $('.wbc907-templates-container .lazy-img').each(function(){
            if((parent_height + parent_pos) > $(this).offset().top && $(this).parents('.vc_ui-template').css('display') == 'block'){
                $(this).attr('src',$(this).data('lazy-src')).removeClass('lazy-img');
            }else{
                return;
            }
        });
        
    });

    $('.wbc-category-template-list ul li').each(function(){
        var filter_category = $(this).data('filter');
        if(filter_category != 'all'){
            var count = $('.wbc907-templates-container .'+filter_category).length;
            if(count < 1){
                $(this).hide();
            }
            $(this).find('.wbc-template-count').text(count);
        }else{
            $(this).find('.wbc-template-count').text($('.wbc907-templates-container .vc_ui-template').length);
        }
        
    });

    $('body').on('click','.wbc-category-template-list ul li',function(){
        $('.wbc-category-template-list ul li').removeClass('wbc-active-template-tab');
        $(this).addClass('wbc-active-template-tab');
        var filter_category = $(this).data('filter');
        if(filter_category == 'all'){
            $('body .wbc907-templates-container .vc_ui-template-list .vc_ui-template').show();
        }else{
            $('body .wbc907-templates-container .vc_ui-template-list .vc_ui-template').hide();
            $('body .wbc907-templates-container .vc_ui-template-list').find('.'+filter_category).show();
        }
        $('.wbc907-templates-container').trigger('scroll');
    });

    $('body').on('click','.wbc907-templates-container .vc_ui-template', function(e){
        e.preventDefault();
        $(this).addClass('wbc-vc-add-template').find('.vc_ui-control-button').trigger('click');

        setTimeout(function(){
            $(this).removeClass('wbc-vc-add-template')
        },500);

    });

    $('body').on('click','.vc_templates-button', function(e){
        e.preventDefault();

        $('.wbc907-templates-container .wbc-vc-add-template').removeClass('wbc-vc-add-template');
        setTimeout(function(){
            $('.wbc907-templates-container').trigger('scroll');
        },100);

    });
    
})(window.jQuery);
