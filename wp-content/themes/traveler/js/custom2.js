/**
 * Created by me664 on 1/13/15.
 */
jQuery(function($){
    $("#st_enable_javascript").each(function(){
        if($(this).hasClass("allow")){
            $("#st_enable_javascript").html(
                ".search-tabs-bg > .tabbable >.tab-content > .tab-pane{display: none; opacity: 0;}"+
                ".search-tabs-bg > .tabbable >.tab-content > .tab-pane.active{display: block;opacity: 1;}"+
                ".search-tabs-to-top { margin-top: -120px;}"
            );
        }
    });

});
jQuery(document).ready(function($){

    if(typeof $.fn.sticky!='undefined'){
        var topSpacing=0; 
        if($(window).width()>481 && $('body').hasClass('admin-bar') ){
            topSpacing=$('#wpadminbar').height();
        }
        //console.log(topSpacing);  
        set_sticky();   

    }
    function set_sticky(){

        var is_menu1 = $(".menu_style1").length ;
        var is_menu2 = $(".menu_style2").length ;
        var is_menu3 = $(".menu_style3").length ;
        var is_menu4 = $(".menu_style4").length ;
        var is_topbar = $("#top_toolbar").length ; 
        var sticky_topbar = $(".enable_sticky_topbar").length ; 
        var sticky_menu = $(".enable_sticky_menu").length ; 
        var sticky_header = $(".enable_sticky_header").length ; 

        if(sticky_header || (sticky_menu && sticky_topbar)){
            $("#st_header_wrap_inner").sticky({topSpacing:topSpacing});
            return;
        }else {
            if (sticky_topbar && is_topbar) {
                $("#top_toolbar").sticky({topSpacing:topSpacing});                
            }            
            if (sticky_menu && (is_menu1 || is_menu2 || is_menu3 || is_menu4)) {
                var topSpacing_topbar = topSpacing;
                if (is_topbar && sticky_topbar) {topSpacing_topbar  += $("#top_toolbar").height(); }
                /*console.log(topSpacing);
                console.log($("#top_toolbar").height());
                console.log(topSpacing_topbar);*/
                $("#menu1").sticky({topSpacing:topSpacing_topbar});
                $("#menu2").sticky({topSpacing:topSpacing_topbar});
                $("#menu3").sticky({topSpacing:topSpacing_topbar});
                $("#menu4").sticky({topSpacing:topSpacing_topbar});
                return;
            }
        }
        return ;
         
    }
    function other_sticky(spacing){

    }
    if($('body').hasClass('search_enable_preload'))
    {
        window.setTimeout(function(){
            $('.full-page-absolute').fadeOut().addClass('.hidden');
        },1000);
    }
    /*Begin gotop*/
    $('#gotop').click(function(){
        $("body,html").animate({
            scrollTop:0
        },1000,function(){
            $('#gotop').fadeOut();
        });
    });

    $(window).scroll(function(){
        var scrolltop=$(window).scrollTop();

        if(scrolltop>200){
            $('#gotop').fadeIn();
        }else{
            $('#gotop').fadeOut();
        }        
        scroll_with_out_transparent();         
    });
    scroll_with_out_transparent();
    function scroll_with_out_transparent(){
        var sdlfkjsdflksd_scrolltop=$(window).scrollTop(); 
        var header_bgr_default = {'background-color' : ""};
        if ($("body").hasClass("menu_style2") && sdlfkjsdflksd_scrolltop !=0 && $('.enable_sticky_menu.header_transparent').length !==0){     

            $(".header-top").css(st_params.header_bgr);
            //$(".header-top").addClass('no_transparent');
             
        }else { 
            $(".header-top").css(header_bgr_default);
            //$(".header-top").removeClass('no_transparent');
             
        }
    }

    var top_ajax_search=$('.st-top-ajax-search');

    top_ajax_search.typeahead({
            hint: true,
            highlight: true,
            minLength: 3,
            limit: 8
        },
        {
            source: function(q, cb) {
                $('.st-top-ajax-search').parent().addClass('loading');
                return $.ajax({
                    dataType: 'json',
                    type: 'get',
                    url: st_params.ajax_url,
                    data:{
                        security:st_params.st_search_nonce,
                        action:'st_top_ajax_search',
                        s:q,
                        lang:top_ajax_search.data('lang')
                    },
                    cache:true,
                    success: function(data) {
                        $('.st-top-ajax-search').parent().removeClass('loading');
                        var result = [];
                        if(data.data){
                            $.each(data.data, function(index, val) {
                                result.push({
                                    value: val.title,
                                    location_id:val.id,
                                    type_color:'success',
                                    type:val.type,
                                    url:val.url
                                });
                            });
                            cb(result);
                            console.log(result);
                        }

                    },
                    error:function(e){
                        $('.st-top-ajax-search').parent().removeClass('loading');
                    }
                });
            },
            templates: {
                suggestion: Handlebars.compile('<p class="search-line-item"><label class="label label-{{type_color}}">{{type}}</label><strong> {{value}}</strong></p>')
            }
        });

    top_ajax_search.bind('typeahead:selected', function(obj, datum, name) {
        if(datum.url){
            window.location.href=datum.url;
        }
    });

    if($.fn.chosen){
        $(".chosen_select").chosen();
    }

    $('.woocommerce-ordering .posts_per_page').change(function(){
        $('.woocommerce-ordering').submit();
    });
    var product_timeout;
    $('.woocommerce li.product').hover(function(){
        var me=$(this);
        product_timeout=window.setTimeout(function(){
            me.find('.product-info-hide').slideDown('fast');
        },250);
    },function(){
        window.clearTimeout(product_timeout);
        var me=$(this);
        me.find('.product-info-hide').slideUp('fast');
    });

    // Menu style 3
    var menu3_resize = null;
    $(window).resize(function(event) {
        clearTimeout(menu3_resize);
        if($('header#menu3').length){
            menu3_resize = setTimeout(function(){
                if(window.matchMedia("(min-width: 1200px)").matches){
                    var container = $('#top_header .container').height();
                    var menu = $('#slimmenu').height();
                    $('header#menu3 .nav').css('margin-top', (container - menu) / 2);
                }
            }, 500);
        }
    }).resize();

    $('#search-icon').click(function(event) {
        /* Act on the event */
        $('.main-header-search').fadeIn('fast');
        return false;
    });
    $('#search-close').click(function(event) {
        /* Act on the event */
        $('.main-header-search').fadeOut('fast');
        return false;
    });

    $('.st-slider-list-hotel').owlCarousel({
        items: 1,
        singleItem: true,
        slideSpeed: 500,
        transitionStyle: $('.st-slider-list-hotel').data('effect'),
        autoHeight: true
    });

    $("#owl-twitter").owlCarousel({
        navigation : true,
        slideSpeed : 300,
        paginationSpeed : 400,
        singleItem:true,
        navigationText :	["",""],
        pagination:false,
        autoPlay:true
    });
    var st_list_partner  = $(".st_list_partner");
    st_list_partner.each(function(){
        var items=$(this).data('items');
        $(this).owlCarousel({
            slideSpeed : 300,
            paginationSpeed : 400,
            navigationText :	["",""],
            pagination:false,
            navigation: false,
            autoPlay:false,
            items : items, //10 items above 1000px browser width
            itemsDesktop : [1000,4], //5 items between 1000px and 901px
            itemsDesktopSmall : [900,3], // betweem 900px and 601px
            itemsTablet: [600,1], //2 items between 600 and 0;
            itemsMobile : false // itemsMobile disabled - inherit from itemsTablet option
        });
    });
    // Custom Navigation Events
    $(".st_list_partner_nav .next").click(function() {
        st_list_partner.trigger('owl.next');
    });

    $(".st_list_partner_nav .prev").click(function() {
        st_list_partner.trigger('owl.prev');
    });

    /* Simple Timer. The countdown to 20:30 2100.05.09
     ---------------------------------------------------------
     https://github.com/mrfratello/SyoTimer
     */
    $(".st_tour_ver_countdown").each(function(){
        $(this).syotimer({
            year: parseInt($(this).data('year')),
            month: parseInt($(this).data('month')),
            day: parseInt($(this).data('day')),
            hour: 0,
            minute: 0,
            lang: ($(this).data('lang')),
        });
    })
    $('.st_tour_ver_fotorama').fotorama({
        nav: false,
    });

    /*---- AJAX GET COUPON ----*/
    var flag_ajax_coupon = false;
    $('body').on('click', '.add-coupon-ajax', function(){
        var t = $(this),
            overlay = t.closest('.booking-item-payment').find('.overlay-form'),
            form = t.closest('form'),
            alert = $('.alert', form),
            data = form.serializeArray();

        if( flag_ajax_coupon ){
            return false;
        }
        flag_ajax_coupon = true;
        overlay.fadeIn();
        alert.addClass('hidden').html('');
        $.post(st_params.ajax_url, data, function(respon, textStatus, xhr) {
            if( respon.status == 1 ){
                overlay.fadeIn();
                var data = {
                    'action' : 'modal_get_cart_detail'
                };
                $.post( st_params.ajax_url, data, function( respon, textStatus, xhr) {
                    t.closest('.booking-item-payment').html( respon );
                    overlay.fadeOut();
                    flag_ajax_coupon = false;
                }, 'json');
            }else{
                alert.removeClass('hidden').html(respon.message);
                overlay.fadeOut();
                flag_ajax_coupon = false;
            }
        }, 'json');

        return false;
    });

    $('body').on('click', '.ajax-remove-coupon', function(event) {
        event.preventDefault();
        var t = $(this),
            overlay = t.closest('.booking-item-payment').find('.overlay-form'),
            form = t.closest('form'),
            alert = $('.alert', form);

        if( flag_ajax_coupon ){
            return false;
        }
        flag_ajax_coupon = true;
        overlay.fadeIn();

        var data = {
            'action' : 'ajax_remove_coupon',
            'coupon' : $(this).data('coupon')
        };
        $.post(st_params.ajax_url, data, function(respon, textStatus, xhr) {
            overlay.fadeIn();
            var data = {
                'action' : 'modal_get_cart_detail'
            };
            $.post( st_params.ajax_url, data, function( respon, textStatus, xhr) {
                t.closest('.booking-item-payment').html( respon );
                overlay.fadeOut();
                flag_ajax_coupon = false;
            }, 'json');
        }, 'json');

    });

    $('#myModal').modal('show');

});