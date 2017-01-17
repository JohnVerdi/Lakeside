jQuery(document).ready(function($) {
	$('#st-update-glocation').click(function(event) {
		$('.update-glocation-wrapper').toggleClass('open');
		return false;
	});

	$('.update-glocation-close').click(function(event) {
		close_update_popup();
		return false;
	});

	$(document).keyup(function(event) {
		if(event.which == 27){
			close_update_popup();
		}
	});

	$('.update-glocation-button').click(function(event) {
		var t = $(this);

		if(!t.hasClass('running')){
			t.addClass('running');
			var update_table_post_type = $('.update-item-form input#update_table_post_type:checked').val();
			var update_location_nested = $('.update-item-form input#update_location_nested:checked').val();
			var update_location_relationships = $('.update-item-form input#update_location_relationships:checked').val();
			var reset_table = $('input[name="reset_table"]:checked').val();

			var step = '';
			if( typeof update_table_post_type != 'undefined' && update_table_post_type != ''){
				step = 'update_table_post_type';
			}else{
				if( typeof update_location_nested != 'undefined' && update_location_nested != ''){
					step = 'update_location_nested';
				}else{
					if( typeof update_location_relationships != 'undefined' && update_location_relationships != ''){
						step = 'update_location_relationships';
					}
				}
			}

			get_date_glocation(1, '', step, update_table_post_type, update_location_nested, update_location_relationships, reset_table, 'st_hotel', '0');
		}
	});
	var progress_ajax;
	function close_update_popup(){
		if($('.update-glocation-wrapper').hasClass('open')){
			if($('.update-glocation-button').hasClass('running')){
				var  cf = confirm('Are you sure? If it is running, it will be canceled.');
				if(cf == true){
					progress_ajax.abort();
					$('.update-glocation-button .text').text('Start');
					$('.update-glocation-progress .progress-bar span').css('width', '0%');
					$('.update-glocation-button').removeClass('running');
					$('.update-glocation-wrapper').removeClass('open');
					$('.update-glocation-message').html('');
				}else{
					return false;
				}
			}else{
				$('.update-glocation-wrapper').removeClass('open');
				$('.update-glocation-button .text').text('Start');
				$('.update-glocation-progress .progress-bar span').css('width', '0%');
				$('.update-glocation-message').html('');
			}
			
		}
	}

	function get_date_glocation(page, number_page, step, update_table_post_type,  update_location_nested, update_location_relationships, reset_table, post_type, progress){
		var data = {
				'action' : 'st_get_data_location_nested',
				'page' : page,
				'number_page' : number_page,
				'step' : step,
				'update_table_post_type' : update_table_post_type,
				'update_location_nested' : update_location_nested,
				'update_location_relationships' : update_location_relationships,
				'reset_table' : reset_table,
				'post_type' : post_type,
				'progress' : progress
			}

		$('.update-glocation-button .text').text('Running');
		$('.update-glocation-message').html('');
		progress_ajax = $.post(ajaxurl, data, function(respon, textStatus, xhr) {
			$('.update-glocation-message').html('');
			if(typeof respon == 'object'){
				$('.update-glocation-progress .progress-bar span').css('width', respon.progress+'%');

				if(respon.status == 'continue'){
					get_date_glocation(respon.page, respon.number_page, respon.step, respon.update_table_post_type, respon.update_location_nested, respon.update_location_relationships, respon.reset_table, respon.post_type, respon.progress);
					$('.update-glocation-message').html(respon.message);
				}else{
					$('.update-glocation-button').removeClass('running');
					if(respon.status == 'completed'){
						$('.update-glocation-button .text').text('Completed');
					}else{
						$('.update-glocation-button .text').text('Error');
						$('.update-glocation-message').html(respon.message);
					}
				}

			}
		}, 'json');
		
	}
});