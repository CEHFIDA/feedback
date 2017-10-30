$(function(){
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});

	$(document).on('submit', '.AjaxSend', function(e){
		e.preventDefault();
		var $form = $(this);
		var $button = $form.find('button[type=submit]');
		// var l = $button.ladda();
		// l.ladda('start');
		
		$form.find(".alert").remove();
		$form.find('.help-block').remove();
		$form.find('.has-error').removeClass('has-error');		
		
		$.ajax({
			method: $form.attr("method"),
			url: $form.attr("action"),
			data: $form.serialize(),
			dataType: 'json',
			success: function (data) {
				if(data.redirect){
					window.location.href = data.redirect;
					return ;
				}
				$.each($form.serializeArray(), function(i, field) {
					var father = $form.find('[name='+field.name+']').parent('.input-wrap');
                    father.removeClass('has-error').removeClass('has-success');
                    father.find('.help-block').html('');
				});

				// $form.find(".alert").remove();
				if(!$form.hasClass('NoAddToPageError')){
					if(data.message){
						$form.prepend("<div class='alert alert-success'><p>"+data.message+"</p></div>");
						$('.g-recaptcha iframe').attr('src', $('.g-recaptcha iframe').attr('src'));
					}
				}else{
					$('#ErrorModal').find('.modal-body').html(data);
				}

				$form.trigger('reset');
				// l.ladda('stop');
			},
			error: function(data){
				
				// l.ladda('stop');
        		if(!$form.hasClass('NoAddToPageError')){
	        		if(data.responseJSON){
						$.each(data.responseJSON.errors, function(i,e){
							var campo = $form.find('[name='+i+']');
		                    var father = campo.parents('.input-wrap');
		                    father.removeClass('has-success');
		                    father.addClass('has-error');
		                    father.append('<div class="help-block"><strong>'+e+'</strong></div>');
						});
					}
				}else{
					$('#ErrorModal').find('.modal-body').html('');
					if(data.responseJSON){
						$.each(data.responseJSON.errors, function(i,e){
							$('#ErrorModal').find('.modal-body').append('<li>'+e+'</li>');
						})
					}else{
						$('#ErrorModal').find('.modal-body').html(data);
					}
					// console.log(data.responseJSON.errors);
					
					$('#ErrorModal').modal('show');
				}
			},
			statusCode: {
				500: function(msg) {
					console.log("errors 500");
				}
			}
		})
	});
});