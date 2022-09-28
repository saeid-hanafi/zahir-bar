(function($){
	$(window).load(function(){
		if(!$('body').hasClass('907-theme_page__options')) return;
		var url = window.location.href;
		if(url && url.match(/linked=true/) && url.match(/tab=wbc-demo-importer/) && url.match(/page=_options/) ){
			var demo_importer = $('.redux-main').find('#wbc907_data-wbc_demo_importer').parents('.redux-group-tab').attr('id');
			if(demo_importer.match(/_section_group/)){
				$('#'+demo_importer+'_li_a').trigger('click');
			}
		}
	});
})(jQuery);