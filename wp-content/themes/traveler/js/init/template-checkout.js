/**
 * Created by me664 on 12/19/14.
 */
jQuery(document).ready(function($){
    if ($(".st_template_checkout").length <1) return;
    var old_order_id=false;
    var new_nonce=false;
    function st_validate_checkout(me)
    {
        me.find('.form_alert').addClass('hidden');
        var data=me.serializeArray();
        var dataobj = {};

        var form_validate=true;

        for (var i = 0; i < data.length; ++i)
        {
            dataobj[data[i].name] = data[i].value;
        }


        me.find('input.required,select.required,textarea.required').removeClass('error');

        me.find('input.required,select.required,textarea.required').each(function(){
            if(!$(this).val())
            {
                $(this).addClass('error');
                form_validate=false;

            }
        });


        if(form_validate==false){
            me.find('.form_alert').addClass('alert-danger').removeClass('hidden');
            me.find('.form_alert').html(st_checkout_text.validate_form);
            return false;
        }
        //term_condition
        if(!dataobj.term_condition && $('[name=term_condition]',me).length){
            me.find('.form_alert').addClass('alert-danger').removeClass('hidden');
            me.find('.form_alert').html(st_checkout_text.error_accept_term);
            return false;
        }

        return true;
    }

    // Check out Submit
    $('.btn-st-checkout-submit').click(function(){
        var button=$(this);
        var me=$('#cc-form');
        var data=me.serializeArray();

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
        dataobj['order_id']=old_order_id;

        var validate=st_validate_checkout(me);
        if(!validate) return false;

        button.addClass('loading');
        $.ajax({
            type:'post',
            url:st_params.ajax_url,
            data:dataobj,
            dataType:'json',
            success:function(data){
                if(typeof (data.order_id)!='undefined' && data.order_id)
                {
                    old_order_id=data.order_id;
                }
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

                if(data.new_nonce)
                {
                    //$('#travel_order').val(data.new_nonce);
                }

                var widget_id='st_recaptchar_'+dataobj.item_id;

                get_new_captcha(me);
                button.removeClass('loading');
            },
            error:function(e){
                button.removeClass('loading');
                alert('Lost connect to server');
                get_new_captcha(me);
            }
        });
    });

    function get_new_captcha(me)
    {
        var captcha_box=me.find('.captcha_box');
        url=captcha_box.find('.captcha_img').attr('src');
        captcha_box.find('.captcha_img').attr('src',url);
    }

    $('.payment-item-radio').on('ifChecked',function(){
        var parent=$(this).closest('li.payment-gateway');
        id=parent.data('gateway');
        parent.addClass('active').siblings().removeClass('active');
        $('.st-payment-tab-content .st-tab-content[data-id="'+id+'"]').siblings().fadeOut('fast');
        $('.st-payment-tab-content .st-tab-content[data-id="'+id+'"]').fadeIn('fast');
    });
});