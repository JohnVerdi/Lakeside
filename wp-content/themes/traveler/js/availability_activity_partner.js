jQuery(function($){
	if ($(".st_partner_avaiablity.edit-activity").length <1) return;
	$('.date-picker').datepicker({
        dateFormat: "mm/dd/yy",
        weekStart: 1
    });

	var ActivityCalendar = function(container){
		var self = this;
		this.container = container;
		this.calendar= null;
		this.form_container = null;

		this.init = function(){
			self.container = container;
			self.calendar = $('.calendar-content', self.container);
			self.form_container = $('.calendar-form', self.container);
			setCheckInOut('', '', self.form_container);
			self.initCalendar();
		}

		this.initCalendar = function(){
            var hide_adult = self.calendar.data('hide_adult');
            var hide_children = self.calendar.data('hide_children');
            var hide_infant = self.calendar.data('hide_infant');
			self.calendar.fullCalendar({
				firstDay: 1,
                lang:st_params.locale,
                timezone: st_timezone.timezone_string,
				customButtons: {
			        reloadButton: {
                        text: st_params.text_refresh,
			            click: function() {
			                self.calendar.fullCalendar( 'refetchEvents' );
			            }
			        }
			    },
				header : {
					left:   'today,reloadButton',
					center: 'title',
					right:  'prev, next'
				},
				contentHeight: 560,
				selectable: true,
				select : function(start, end, jsEvent, view){
					var start_date = new Date(start._d).toString("MM");
					var end_date = new Date(end._d).toString("MM");
					var start_year = new Date(start._d).toString("yyyy");
					var end_year = new Date(end._d).toString("yyyy");
					var today = new Date().toString("MM");
					var today_year = new Date().toString("yyyy");
					if((start_date < today && start_year <= today_year) || (end_date < today && end_year <= today_year)){
						self.calendar.fullCalendar('unselect');
						setCheckInOut('', '', self.form_container);
					}else{
						var zone = moment(start._d).format('Z');
							zone = zone.split(':');
							zone = "" + parseInt(zone[0]) + ":00";
						var check_in = moment(start._d).utcOffset(zone).format("MM/DD/YYYY");
						var	check_out = moment(end._d).utcOffset(zone).subtract(1, 'day').format("MM/DD/YYYY");
						setCheckInOut(check_in, check_out, self.form_container);
					}

				},
				events:function(start, end, timezone, callback) {
                    $.ajax({
                        url: ajaxurl,
                        dataType: 'json',
                        type:'post',
                        data: {
                            action: 'st_get_availability_activity',
                            activity_id: self.container.data('post-id'),
                            start: start.unix(),
                            end: end.unix()
                        },
                        success: function(doc){
                        	if(typeof doc == 'object'){
                            	callback(doc);
                        	}
                        },
                        error:function(e)
                        {
                            alert('Can not get the availability slot. Lost connect with your sever');
                        }
                    });
                },
				eventClick: function(event, element, view){

				},
				eventRender: function(event, element, view){
					var html = '';
					if(event.status == 'available'){
                        if (event.adult_price != 0 && hide_adult != 'on') {
                            html += '<div class="price">'+st_checkout_text.adult_price+': '+event.adult_price+'</div>';
                        }
                        if (event.child_price != 0 && hide_children != 'on') {
                            html += '<div class="price">'+st_checkout_text.child_price+': '+event.child_price+'</div>';
                        }
                        if (event.infant_price != 0 && hide_infant != 'on') {
                            html += '<div class="price">'+st_checkout_text.infant_price+': '+event.infant_price+'</div>';
                        }
						$(element).addClass('bgr-main');
					}
					if(typeof event.status == 'undefined' || event.status != 'available'){
						html += '<div class="not_available">Not Available</div>';
					}
					$('.fc-content', element).html("<div class='bgr-main'>"+ html+"</div>");

                    element.bind('click', function(calEvent, jsEvent, view) {
                        $('.fc-day-grid-event').removeClass('st-active');
                        $(this).addClass('st-active');
                        date = $.fullCalendar.moment(event.start._i).format("MM/DD/YYYY");
                        $('input#calendar_check_in').val(date).parent().show();
                        if(typeof event.end != 'undefined' && event.end && typeof event.end._i != 'undefined'){
                            date = new Date(event.end._i);
                            date.setDate(date.getDate() - 1);
                            date = $.fullCalendar.moment(date).format("MM/DD/YYYY");
                            $('input#calendar_check_out').val(date);
                        }else{
                            date = $.fullCalendar.moment(event.start._i).format("MM/DD/YYYY");
                            $('input#calendar_check_out').val(date);

                        }
                        $('input#calendar_adult_price').val(event.adult_price);
                        $('input#calendar_child_price').val(event.child_price);
                        $('input#calendar_infant_price').val(event.infant_price);
                    });
				},
                loading: function(isLoading, view){
                    if(isLoading){
                        $('.calendar-wrapper-inner .overlay-form').fadeIn();
                    }else{
                        $('.calendar-wrapper-inner .overlay-form').fadeOut();
                    }
                },

			});
		}
	};

	function setCheckInOut(check_in, check_out, form_container){
		$('#calendar_check_in', form_container).val(check_in);
		$('#calendar_check_out', form_container).val(check_out);
	}
	function resetForm(form_container){
		$('#calendar_check_in', form_container).val('');
		$('#calendar_check_out', form_container).val('');
		$('#calendar_adult_price', form_container).val('');
		$('#calendar_child_price', form_container).val('');
		$('#calendar_infant_price', form_container).val('');
		$('#calendar_number', form_container).val('');
	}
	jQuery(document).ready(function($) {
		if($('a[href="#availablility_tab"]').length){
			$('a[href="#availablility_tab"]').click(function(event) {
				setTimeout(function(){
					$('.calendar-content', '.calendar-wrapper').fullCalendar('today');
				}, 1000);
			});
		}
		$('.calendar-wrapper').each(function(index, el) {
			var t = $(this);
			var activity = new ActivityCalendar(t);
			activity.init();

			var flag_submit = false;
			$('#calendar_submit', t).click(function(event) {
				var data = $('input, select', '.calendar-form').serializeArray();
					data.push({
						name: 'action',
						value: 'st_add_custom_price_activity'
					});
				$('.form-message', t).attr('class', 'form-message').find('p').html('');	
				$('.overlay', self.container).addClass('open');
				if(flag_submit) return false; flag_submit = true;
				$.post(ajaxurl, data, function(respon, textStatus, xhr) {
					if(typeof respon == 'object'){
						if(respon.status == 1){
							resetForm(t);
							$('.calendar-content', t).fullCalendar('refetchEvents');
						}else{
							$('.form-message', t).addClass(respon.type).find('p').html(respon.message);
							$('.overlay', self.container).removeClass('open');
						}
					}else{
						$('.overlay', self.container).removeClass('open');
					}

					flag_submit = false;
				}, 'json');
				return false;
			});
		});
		
		if($('select#type_activity').length && $('select#type_activity').val() == 'daily_activity'){
			$('input#calendar_groupday').prop('checked', false).parents('.form-group').hide();
		}else{
			$('input#calendar_groupday').parents('.form-group').show();
		}
		$('select#type_activity').change(function(event) {
			activity_type = $(this).val();
			if(activity_type == 'daily_activity'){
				$('input#calendar_groupday').prop('checked', false).parents('.form-group').hide();
			}else{
				$('input#calendar_groupday').parents('.form-group').show();
			}
		});
	});
});
