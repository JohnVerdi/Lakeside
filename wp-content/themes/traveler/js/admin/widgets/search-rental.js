/**
 * Created by me664 on 12/22/14.
 */

jQuery(document).ready(function($){


    function initSort()
    {
        $('.fields-wrap').each(function(){
            $(this).sortable({
                placeholder: "ui-state-highlight",
                axis: "y",
                stop:function(event, ui){
                    var index=ui.item.index();
                    ui.item.find('.field_order').val(index);
                }
            });
        });
    }


    $(document).ajaxSuccess(function(e, xhr, settings) {
        initSort();
    });

    $(window).load(function(){
        initSort();
    });

    $(document).on('click','.st_add_rental_field',function(){
        var parent=$(this).parents('.st-search-fields');
        var new_index=parent.find('.fields-wrap .ui-state-default').length;
        //
        //var item=$("<li></li>");
        //item.addClass("ui-state-default");
        //item.html('<p><label>Title:</label><input class="widefat" name="field_title['+new_index+']" type="text" value=""></p>');
        //item.append('<input type="hidden" class="field_order" name="order['+new_index+']" value="'+new_index+'">');
        //item.append('<p><label>Field:</label>'+st_search_hotel.fields+'</p>');
        //item.append('<p class=""><label>Taxonomy:</label>'+st_search_hotel.taxonomy+'</p>');
        //item.append('<p class=""><a href="#" class="button st_delete_field" onclick="return false">Delete</a></p>');

        var item=$(st_search_rental.default_item);

        item.find('.field_name').attr('name','field['+new_index+']');
        item.find('.field_taxonomy').attr('name','taxonomy['+new_index+']');
        item.find('.field_order').attr('name','order['+new_index+']');
        item.find('.field_title').attr('name','title['+new_index+']');
        parent.find('.fields-wrap').append(item).sortable( "refresh" );

    });

    $(document).on('click','.st_save_rental_fields',function(){
        var parent=$(this).parents('.st-search-fields');
        var form=parent.children('.fields-form');

        var data=[];

        form.find('.ui-state-default').each(function(){
            var me=$(this);
            data.push({
                'order':me.find('.field_order').val(),
                'title':me.find('.field_title').val(),
                'field':me.find('.field_name').val(),
                'taxonomy':me.find('.field_taxonomy').val()
            });
        });
        console.log(data);
        parent.find('.st_search_fields_value').val(JSON.stringify(data));
    });
    $(document).on('click','.st_delete_field',function(){
        $(this).parents('.ui-state-default').remove();
    });


});