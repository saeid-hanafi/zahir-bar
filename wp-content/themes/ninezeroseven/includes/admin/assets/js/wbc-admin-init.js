(function($) {
	$('body').on('submit','#wbc-register-form', function(event){
		event.preventDefault();

		if($(this).hasClass('wbc-submited')) return;
		$(this).removeClass('has-message-success').removeClass('has-message-error').addClass('wbc-submited');
		var form_ob = $(this);
		var data = $(this).data();
		data.action = 'wbc_register';
		data.nonce = $(this).find('#wbc-register-nounce').val();
		data.lic_key = $(this).find('#wbc-register-key').val();

		if(data.lic_key.length == 0){
			$(this).removeClass('wbc-submited');
			$(this).addClass('has-message-error');
			return;
		}
		if(data.lic_key.length < 35 ||  data.lic_key.length > 45 || (data.lic_key.match(/-/g) || []).length < 4){
			$(this).removeClass('wbc-submited');
			$(this).addClass('has-message-error');
			return;
		}

		$(this).removeClass('has-message-error');

		jQuery.post(ajaxurl, data, function(response) {
			if (response.length > 0 && response.match(/success/gi)) {
				form_ob.removeClass('wbc-submited').addClass('has-message-success');
				location.reload();
			}else if(response.length > 0 ){
				form_ob.find('.wbc-register-error').html(response);
				form_ob.removeClass('wbc-submited').addClass('has-message-error');
			}else{
				form_ob.removeClass('wbc-submited').addClass('has-message-error');
				location.reload();
			}
		});
	});

	$('body').on('click','.wbc-deactivate-license-button', function(event){
		event.preventDefault();
		$(this).addClass('wbc-clicked');
		var button = $(this);
		var data = $(this).data();
		data.action = 'wbc_deactivate_license';
		data.nonce = $(this).attr('data-nonce');
		
		jQuery.post(ajaxurl, data, function(response) {
			if (response.length > 0 && response.match(/success/gi)) {
				location.reload();
			}else if(response.length > 0 ){
				location.reload();
			}
		});

	});



	// $('body').on('click',' .wbc-admin-view .theme:not(.premium) .button', function(event){
	// 	event.preventDefault();
	// 	$(this).parents('.theme').find('.button').hide();
	// 	$(this).parents('.theme').removeClass('active').addClass('wbc-process');

	// 	$('.theme-browser').load($(this).attr('href') + " .theme-browser >*", function() {
	// 			location.reload();
	// 		});

	// });

})(jQuery);