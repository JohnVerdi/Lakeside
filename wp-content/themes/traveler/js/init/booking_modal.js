/**
 * Created by me664 on 12/29/14.
 */
jQuery(document).ready(function($){

    var old_order_id=false;

    $('.btn-st-add-cart').click(function(){
        var me=$(this);
        var data = [];
        var holder=$('.message_box');
        var data1 = $('#form-booking-inpage').serializeArray();
        for(var i = 0; i < data1.length; i++){
            data.push({
                name : data1[i].name,
                value : data1[i].value
            });
        }
        data.push({
            name : 'action',
            value : 'st_add_to_cart'
        });

        var dataobj = {};
        for (var i = 0; i < data.length; ++i){
            dataobj[data[i].name] = data[i].value;

        }

        me.addClass('loading');
        holder.html('');
        $.ajax({
            'type':'post',
            'dataType':'json',
            'url':st_params.ajax_url,
            'data':dataobj,
            'success':function(data){
                me.removeClass('loading');

                if(data.message){
                    setMessage(holder,data.message,'danger');
                }

                if(data.status){
                    $.magnificPopup.open({
                        items: {
                            type: 'inline',
                            src: me.data('target')
                        },
                        close: function(){
                            old_order_id=false;
                        }

                    });
                    get_cart_detail(me.data('target'));
                }

            },
            error:function(data){
                me.removeClass('loading');
            }
        });
    });

    $('.btn-st-show-cart-modal').click(function(){
        var me = $(this);
        $.magnificPopup.open({
            items: {
                type: 'inline',
                src: me.data('target')
            },
            close: function(){
                old_order_id=false;
            }

        });
        get_cart_detail(me.data('target'));
    });

    function get_cart_detail(dom){
        // from 1.2.3
        var dom_div = dom + " " + " .booking-item-payment";
        var me = $(dom_div);
        $.ajax({
            'type':'post',
            'dataType':'json',
            'url':st_params.ajax_url,
            'data': {
                action : 'modal_get_cart_detail'
            },
            success: function(result){
                me.html(result);
            },
            error:function(data){
                // cosole.log("timquen")
            }
        });
    }

    $(document).on('click','.btn-st-checkout-submit',function(){
        var me = $(this).parents('.booking_modal_form');
        submit_form(me, $(this));
    });

    //$(document).on('click','.btn_hotel_booking',function(){
    //    var form=$(this).closest('form');
    //    if(!checkRequiredBooking(form)){
    //        return false;
    //    }
    //
    //    var tar_get=$(this).data('target');
    //
    //    $.magnificPopup.open({
    //        items: {
    //            type: 'inline',
    //            src: tar_get
    //        }
    //
    //    });
    //
    //});



    function do_scrollTo(el)
    {
        if(el.length){
            var top=el.offset().top;
            if($('#wpadminbar').length && $('#wpadminbar').css('position')=='fixed')
            {
                top-=32;
            }
            top-=300;
            $('html,body').animate({
                'scrollTop':top
            },500);
        }
    }

    function setMessage(holder,message,type)
    {
        if(typeof  type=='undefined'){
            type='infomation';
        }
        var html='<div class="alert alert-'+type+'">'+message+'</div>';
        if(!holder.length) return;
        holder.html('');
        holder.html(html);
        console.log(holder.offset().top);
        console.log($(window).height());

        if(holder.offset().top>$(window).height()){
            do_scrollTo(holder);
        }
    }


    function checkRequiredBooking(searchbox)
    {
        var searchform=$('.booking-item-dates-change');
        if(typeof searchbox!="undefined")
        {
            var data=searchbox.find('input,select,textarea').serializeArray();
        }

        var dataobj = {};
        for (var i = 0; i < data.length; ++i)
            dataobj[data[i].name] = data[i].value;

        var holder=$('.search_room_alert');

        holder.html('');
        if(dataobj.room_num_search=="1"){
            if(dataobj.adult_number=="" || dataobj.child_number=='' ||typeof dataobj.adult_number=='undefined' || typeof dataobj.child_number=='undefined'){

                setMessage(holder,st_hotel_localize.booking_required_adult_children,'danger');
                return false;
            }

        }
        if(dataobj.check_in=="" || dataobj.check_out=='')
        {
            if(dataobj.check_in==""){
                searchform.find('[name=start]').addClass('error');
            }
            if(dataobj.check_out==""){
                searchform.find('[name=end]').addClass('error');
            }
            setMessage(holder,st_hotel_localize.is_not_select_date,'danger');
            return false;
        }

        return true;

    }

    function submit_form(me,clicked){
        var button=clicked;
        var data = me.serializeArray();
        //var data1 = $('#form-booking-inpage').serializeArray();
        //for(var i = 0; i < data1.length; i++){
        //    data.push({
        //        name : data1[i].name,
        //        value : data1[i].value
        //    });
        //}
        data.push({
            name : 'action',
            value : 'booking_form_direct_submit'
        });
        me.find('.form-control').removeClass('error');
        me.find('.form_alert').addClass('hidden');

        var dataobj = {};
        var form_validate=true;


        for (var i = 0; i < data.length; ++i){
            dataobj[data[i].name] = data[i].value;

        }
        $('input.required,select.required,textarea.required', me).removeClass('error');
        $('input.required,select.required,textarea.required', me).each(function(){
            if(!$(this).val()){
                $(this).addClass('error');
                form_validate = false;
            }
        });


        if(form_validate==false){
            me.find('.form_alert').addClass('alert-danger').removeClass('hidden');
            me.find('.form_alert').html(st_checkout_text.validate_form);
            return false;
        }
        //term_condition
        if(!dataobj.term_condition){
            me.find('.form_alert').addClass('alert-danger').removeClass('hidden');
            me.find('.form_alert').html(st_checkout_text.error_accept_term);

            return false;
        }
        //console.log(dataobj);
        dataobj['order_id']=old_order_id;


        button.addClass('loading');
        $.ajax({
            'type':'post',
            'dataType':'json',
            'url':st_params.ajax_url,
            'data':dataobj,
            'success':function(data){
                button.removeClass('loading');

                if(data.message){
                    me.find('.form_alert').addClass('alert-danger').removeClass('hidden');
                    me.find('.form_alert').html(data.message);
                }

                if(data.redirect){
                    window.location.href=data.redirect;
                }
                if(data.redirect_form){
                    $('body').append(data.redirect_form);
                }

                if(typeof (data.order_id)!='undefined' && data.order_id)
                {
                    old_order_id=data.order_id;
                }
                if(data.new_nonce)
                {
                   // $('#travel_order').val(data.new_nonce);
                }

                var widget_id='st_recaptchar_'+dataobj.item_id;

                get_new_captcha(me);
            },
            error:function(data){
                alert('Ajax Fail');

                var widget_id='st_recaptchar_'+dataobj.item_id;

                get_new_captcha(me);
                button.removeClass('loading');

            }
        });

        function get_new_captcha(me)
        {
            var captcha_box=me.find('.captcha_box');
            url=captcha_box.find('.captcha_img').attr('src');
            captcha_box.find('.captcha_img').attr('src',url);
        }
    }

    $('.payment-item-radio').on('ifChecked',function(){
        var parent=$(this).closest('li.payment-gateway');
        id=parent.data('gateway');
        parent.addClass('active').siblings().removeClass('active');
        $('.st-payment-tab-content .st-tab-content[data-id="'+id+'"]').siblings().fadeOut('fast');
        $('.st-payment-tab-content .st-tab-content[data-id="'+id+'"]').fadeIn('fast');
    });
});