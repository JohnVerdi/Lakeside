jQuery(document).ready(function($) {
    if ($(".st_single_hotel_room").length <1) return;
    var time;
    var scroll = '';
    var offset_form = '';
    $(window).resize(function(event) {
        clearTimeout(time);
        time = setTimeout(function(){
                $(window).scroll(function(event) {
                    if($(window).width() >= 992){
                        if(scroll == ''){
                            scroll = $(window).scrollTop();
                        }
                        var t = 0;
                        if($('#top_toolbar-sticky-wrapper').length && $('#top_toolbar-sticky-wrapper').hasClass('is-sticky')){
                            if($('#top_toolbar').length){
                                t += $('#top_toolbar').height();
                            }
                        }
                        if($('#st_header_wrap_inner-sticky-wrapper').length && $('#st_header_wrap_inner-sticky-wrapper').hasClass('is-sticky')){
                            if($('#main-header').length){
                                t += $('#main-header').height();
                            }
                            if($('#top_toolbar').length){
                                t += $('#top_toolbar').height();
                            }
                        }
                        if($('#menu1-sticky-wrapper').length && $('#menu1-sticky-wrapper').hasClass('is-sticky')){
                            if($('#menu1').length){
                                t += $('#menu1').height();
                            }
                            if($('#top_toolbar').length){
                                t += $('#top_toolbar').height();
                            }
                        }
                        var h = 0;
                        if($('.hotel-room-form').length){
                            h = $('.hotel-room-form').offset().top - $(window).scrollTop();
                            if(offset_form == ''){
                                offset_form = $('.hotel-room-form').offset().top;
                            }
                        }
                        if(h <= t){

                            w = $('.hotel-room-form').width();

                            var top_kc = t;
                            if ($('#wpadminbar').length > 0){
                                top_kc += $('#wpadminbar').height();
                            }

                            if( ! $('.hotel-room-form').hasClass('sidebar-fixed')){
                                $('.hotel-room-form').css('top', top_kc);
                                $('.hotel-room-form').addClass('sidebar-fixed').css('width', w);
                                $('.hotel-room-form').addClass('no_margin_top');
                            }
                        }
                        if($(window).scrollTop() <= offset_form && $(window).scrollTop() < scroll){
                            $('.hotel-room-form').removeClass('sidebar-fixed').css('width', 'auto');
                            $('.hotel-room-form').css('top', 0);
                            $('.hotel-room-form').removeClass('no_margin_top');
                        }

                        scroll = $(window).scrollTop();
                    }
                });
        }, 500);
    }).resize();

    var disabled_dates = [];
    var fist_half_day = [];
    var last_half_day = [];
    $('input.checkin_hotel, input.checkout_hotel').each(function() {
        var $this = $(this);
        $this.datepicker({
            language:st_params.locale,
            format: $('[data-date-format]').data('date-format'),
            todayHighlight: true,
            autoclose: true,
            startDate: 'today',
            weekStart: 1,
            setRefresh: true,
            beforeShowDay: function(date){
                var d = date;
                var curr_date = d.getDate();
                if(curr_date < 10){
                    curr_date = "0"+curr_date;
                }
                var curr_month = d.getMonth() + 1; //Months are zero based
                if(curr_month < 10){
                    curr_month = "0"+curr_month;
                }
                var curr_year = d.getFullYear();
                var key = 'st_calendar_'+curr_date + "_" + curr_month + "_" + curr_year; // class building
                return {
                    classes: key
                };
            }
        });
        $this.click(function(){ // click on calendar checkin/checkout input
            if(fist_half_day.length > 0){
                for (var i = 0; i < fist_half_day.length; i++) {
                    var $key ='st_calendar_'+fist_half_day[i];
                    $('.'+$key).addClass('st_fist_half_day');
                }
            }
            if(last_half_day.length > 0){
                for (var i = 0; i < last_half_day.length; i++) {
                    var $key ='st_calendar_'+last_half_day[i];
                    $('.'+$key).addClass('st_last_half_day');
                }
            }
            if(disabled_dates.length > 0){
                for (var i = 0; i < disabled_dates.length; i++) {
                    var $key ='st_calendar_'+disabled_dates[i];
                    $('.'+$key).addClass('disabled disabled-date booked day st_booked');
                }
            }
        });
        $('.date-overlay').addClass('open');
        var date_start = $(this).datepicker('getDate');
        if(date_start == null)
            date_start = new Date();
        year_start = date_start.getFullYear();
        month_start = date_start.getMonth() + 1;
        ajaxGetHotelOrder(month_start,year_start,$(this));
    });
    $('input.checkin_hotel').on('changeMonth', function(e) {
        year_start =  new Date(e.date).getFullYear();
        month_start =  new Date(e.date).getMonth() + 1;
        ajaxGetHotelOrder(month_start,year_start,$(this));
    });
    $('input.checkin_hotel').on('changeDate', function (e) {
        var new_date = e.date;
        new_date.setDate(new_date.getDate() + 1);
        $('input.checkout_hotel').datepicker('setStartDate', new_date); // setting new input date
    });
    $('input.checkin_hotel, input.checkout_hotel').on('keyup', function (e) {
        setTimeout(function(){
            if(fist_half_day.length > 0){
                for (var i = 0; i < fist_half_day.length; i++) {
                    var $key ='st_calendar_'+fist_half_day[i];
                    $('.'+$key).addClass('st_fist_half_day');
                }
            }
            if(last_half_day.length > 0){
                for (var i = 0; i < last_half_day.length; i++) {
                    var $key ='st_calendar_'+last_half_day[i];
                    $('.'+$key).addClass('st_last_half_day');
                }
            }
            if(disabled_dates.length > 0){
                for (var i = 0; i < disabled_dates.length; i++) {
                    var $key ='st_calendar_'+disabled_dates[i];
                    $('.'+$key).addClass('disabled disabled-date booked day st_booked');
                }
            }
        },200)
    });
    $('input.checkout_hotel').on('changeMonth', function(e) {
        year_start =  new Date(e.date).getFullYear();
        month_start =  new Date(e.date).getMonth() + 1;
        ajaxGetHotelOrder(month_start,year_start,$(this));
    });

    function ajaxGetHotelOrder(month, year, me){
        post_id = angular.element(document.querySelector('[ng-controller="PropertyController as property"]')).scope().propertyId;
        $('.date-overlay').addClass('open');
        if( !typeof post_id != 'undefined' || parseInt(post_id) > 0 ){
            var data = {
                room_id : post_id,
                month: month,
                year: year,
                security:st_params.st_search_nonce,
                action:'st_get_disable_date_hotel'
            };
            $.post(st_params.ajax_url, data, function(respon) { // Widget calendar getting data
                disabled_dates = Object.keys(respon.disable).map(function (key) {return respon.disable[key]});
                fist_half_day = Object.keys(respon.fist_half_day).map(function (key) {return respon.fist_half_day[key]});
                last_half_day = Object.keys(respon.last_half_day).map(function (key) {return respon.last_half_day[key]});
                if(fist_half_day.length > 0){
                    for (var i = 0; i < fist_half_day.length; i++) {
                        var $key ='st_calendar_'+fist_half_day[i];
                        $('.'+$key).addClass('st_fist_half_day');
                    }
                }
                if(last_half_day.length > 0){
                    for (var i = 0; i < last_half_day.length; i++) {
                        var $key ='st_calendar_'+last_half_day[i];
                        $('.'+$key).addClass('st_last_half_day');
                    }
                }
                if(disabled_dates.length > 0){
                    for (var i = 0; i < disabled_dates.length; i++) {
                        var $key ='st_calendar_'+disabled_dates[i];
                        $('.'+$key).addClass('disabled disabled-date booked day st_booked');
                    }
                }
                $('.date-overlay').removeClass('open');
            },'json');

        }else{
            $('.date-overlay').removeClass('open');
        }
    }

    var HotelCalendar = function(container){
        var self = this;
        this.container = container;
        this.calendar= null;
        this.form_container = null;
console.log(22);
        this.init = function(){
            self.container = container;
            self.calendar = $('.calendar-content', self.container);
            self.form_container = $('.calendar-form', self.container);
            self.initCalendar();
        };

        this.initCalendar = function(){
            self.calendar.fullCalendar({
                firstDay: 1,
                lang:st_params.locale,
                customButtons: {
                    reloadButton: {
                        text: st_params.text_refresh,
                        click: function() {
                            self.calendar.fullCalendar( 'refetchEvents' );
                        }
                    }
                },
                timezone: 'UTC',
                header : {
                    left:   'prev',
                    center: 'title',
                    right:  'next'
                },
                contentHeight: 360,
                events:function(start, end, timezone, callback) {
                    $.ajax({
                        url: st_params.ajax_url,
                        dataType: 'json',
                        type:'post',
                        data: {
                            action:'st_get_availability_hotel_room_custom',
                            post_id: angular.element(document.querySelector('[ng-controller="PropertyController as property"]')).scope().propertyId,
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
                            console.log('Calendar error occured!');
                        }
                    });
                },
                eventClick: function(event, element, view) {

                    d = new Date(event.date);
                    utc = d.getTime() + (d.getTimezoneOffset() * 60000);
                    date_formated = new Date(utc + (3600000*offset));

                    var date = date_formated,

                        $scope = angular.element($('#single-room')).scope(),
                        checkInInput = $('#book_start_date'),
                        checkOutInput = $('#book_end_date'),
                        inxRangeBlueBtns = [];

                    if (event.status !== 'booked' && event.status !== 'past') {
                        $(this).find('button').addClass('blue');
                        var buttons = $('.btn-available');

                        if( !checkInInput.val() ) {
                            $scope.$apply(function () {
                                $scope.book.checkin = getFormattedDate(date);
                            });
                        } else if ( checkInInput.val() && !checkOutInput.val()) {
                            $scope.$apply(function () {
                                $scope.book.checkout = getFormattedDate(date);
                            });

                            $.each( buttons, function (index, button) {
                                if ( $(button).hasClass('blue') ) {
                                    inxRangeBlueBtns.push(index);
                                }
                            });

                            $.each( buttons, function () {
                                for ( inxRangeBlueBtns[0]; inxRangeBlueBtns[0] < inxRangeBlueBtns[1]; inxRangeBlueBtns[0]++ ) {
                                    buttons.eq( inxRangeBlueBtns[0] ).addClass('blue');
                                }
                            });
                        } else {
                            inxRangeBlueBtns = [];
                            $scope.$apply(function () {
                                $scope.book.checkout = '';
                                $scope.book.checkin = '';
                            });

                            $.each( buttons, function () {
                                buttons.removeClass('blue');
                            });
                        }
                    }

                    function getFormattedDate(date) {
                        var year = date.getFullYear();
                        var month = (1 + date.getMonth()).toString();
                        month = month.length > 1 ? month : '0' + month;
                        var day = date.getDate().toString();
                        day = day.length > 1 ? day : '0' + day;
                        return month + '/' + day + '/' + year;
                    }
                },
                eventRender: function(event, element, view){
                    var html = "";
                    var title = "";
                    var html_class = "btn-disabled";
                    var is_disabled = "disabled";
                    var today_y_m_d = new Date().getFullYear() +"-"+(new Date().getMonth()+1)+"-"+new Date().getDate();
                    if(event.status == 'booked'){
                        html_class = "btn-available bnt-booked";
                    }

                    if(event.status == 'first'){
                        html_class = "btn-available bnt-first";
                        is_disabled = "";
                        title = st_checkout_text.origin_price + ": "+event.price;
                    }

                    if(event.status == 'still'){
                        html_class = "btn-available bnt-still";
                        is_disabled = "";
                        title = st_checkout_text.origin_price + ": "+event.price;
                    }

                    if(event.status == 'past'){ }
                    if(event.status == 'disabled'){ }

                    if(event.status == 'avalable'){
                        html_class = "btn-available";
                        is_disabled = "";
                        title = st_checkout_text.origin_price + ": "+event.price;
                    }

                    if(event.status == 'available_allow_fist'){
                        html_class = "btn-calendar btn-available_allow_fist available_allow_fist single-room";
                        is_disabled = "";
                        title = st_checkout_text.origin_price + ": "+event.price;
                    }
                    if(event.status == 'available_allow_last'){
                        html_class = "btn-calendar btn-available_allow_last available_allow_last single-room";
                        is_disabled = "";
                        title = st_checkout_text.origin_price + ": "+event.price;
                    }
                    var month = self.calendar.fullCalendar('getDate').format("MM");

                    var month_now = $.fullCalendar.moment(event.start._i).format("MM");
                    var _class = '';
                    if(month_now != month){
                        _class = 'next_month';
                    }

                    html += "<button  "+is_disabled+" data-toggle='tooltip' data-placement='top' class= '"+html_class+" "+_class+" btn' title ='"+title+"' data-date ='"+event.date+"''>"+event.day;
                    if (today_y_m_d === event.date) {
                        html += "<span class='triangle'></span>";
                    }
                    html+="</button>";
                    $('.fc-content', element).html(html);
                },
                eventAfterRender: function( event, element, view ) {
                    $('[data-toggle="tooltip"]').tooltip({html:true});
                    $scope = angular.element($('#single-room')).scope();

                    // if (! $scope.book.checkin || !$scope.book.checkin) {
                    //     return
                    // }
                    //
                    // if ($scope.aviabilityDaysStatus === false){
                    //     return
                    // }

                    // var checkInDate = new Date($scope.book.checkin);
                    // // console.log(checkInDate);
                    // var checkOutDate = new Date($scope.book.checkout);
                    // // console.log(checkOutDate);
                    // var d = new Date(event.date);
                    // d.setHours(0,0,0,0);
                    // var dateEvent = new Date(d)
                    var check_in =  $scope.book.checkin.split('/');
                    var checkInDate = check_in[2]+'-'+check_in[0]+'-'+check_in[1];

                    var check_out =  $scope.book.checkout.split('/');
                    var checkOutDate = check_out[2]+'-'+check_out[0]+'-'+check_out[1];

                    if (compareDates(checkInDate, event.date) || compareDates(checkOutDate, event.date)) {
                        element.find('button').addClass('blue');
                        // If defined check in and check out date, fill in the intermediate dates
                        if ($('.btn-available.blue').length === 2) {
                            var buttons = $('.btn-available'),
                                inxRangeBlueBtns = [];

                            $.each( buttons, function (index, button) {
                                if ( $(button).hasClass('blue') ) {
                                    inxRangeBlueBtns.push(index);
                                }
                            });

                            $.each( buttons, function () {
                                for ( inxRangeBlueBtns[0]; inxRangeBlueBtns[0] < inxRangeBlueBtns[1]; inxRangeBlueBtns[0]++ ) {
                                    buttons.eq( inxRangeBlueBtns[0] ).addClass('blue');
                                }
                            });
                        }
                    }

                    // return true if dates the same or false
                    function compareDates(firstDate, secondDate) {
                        return (
                            firstDate === secondDate
                        )
                    }
                },
                loading: function(isLoading, view){
                    if(isLoading){
                        $('.calendar-wrapper-inner .overlay-form').fadeIn();
                    }else{
                        $('.calendar-wrapper-inner .overlay-form').fadeOut();
                    }
                }
            });
        }
    };
    if($('.calendar-wrapper').length){
        $('.calendar-wrapper').each(function(index, el) {
            var t = $(this);
            var hotel = new HotelCalendar(t);
            hotel.init();
        });
    };

    var single_hotel_room  = $(".st_single_hotel_room");
    if(single_hotel_room.length>0){
        var fancy_arr = single_hotel_room.data('fancy_arr');
        if (fancy_arr ==1){
            $('a#fancy-gallery').on("click",function(event) {
                var list = fancy_arr;
                $.fancybox.open(list);
            });
        }

    }
    $('a.button-readmore').click(function(){
        if($('#read-more').length > 0){
            $('#read-more').removeClass('hidden');
            $(this).addClass('hidden');
            $('#show-description').remove();
        }
    });
});
