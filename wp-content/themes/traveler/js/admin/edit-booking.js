/**
 * Created by me664 on 1/16/15.
 */
(function($){

    function repoFormatResult(state) {
        if (!state.id) return state.name; // optgroup
        return state.name+'<p><em>'+state.description+'</em></p>';
    }
    $('.st_post_select_ajax').each(function(){
        var me=$(this);
        $(this).select2({
            placeholder: me.data('placeholder'),
            minimumInputLength:2,
            ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                url: ajaxurl,
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term, // search term,
                        action:'st_post_select',
                        post_type:me.data('post-type')
                    };
                },
                results: function (data, page) { // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to alter the remote JSON data
                    return { results: data.items };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                // the input tag has a value attribute preloaded that points to a preselected repository's id
                // this function resolves that id attribute to an object that select2 can render
                // using its formatResult renderer - that way the repository name is shown preselected
                var id = $(element).val();
                if (id !== "") {
                    var data={
                        id:id,
                        name:$(element).data('pl-name'),
                        description:$(element).data('pl-desc')
                    };
                    callback(data);
                }
            },
            formatResult: repoFormatResult,
            formatSelection: repoFormatResult,
            escapeMarkup: function(m) { return m; }
        });
    });
    $('.st_room_select_ajax').each(function(){
        var me=$(this);
        $(this).select2({
            placeholder: me.data('placeholder'),
            minimumInputLength:2,
            ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                url: ajaxurl,
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term, // search term,
                        action:'st_room_select_ajax_admin',
                        post_type : me.data('post-type'),
                        room_parent : $('[name=item_id]').val()
                    };
                },
                results: function (data, page) { // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to alter the remote JSON data
                    return { results: data.items };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                // the input tag has a value attribute preloaded that points to a preselected repository's id
                // this function resolves that id attribute to an object that select2 can render
                // using its formatResult renderer - that way the repository name is shown preselected
                var id = $(element).val();
                if (id !== "") {
                    var data={
                        id:id,
                        name:$(element).data('pl-name'),
                        description:$(element).data('pl-desc')
                    };
                    callback(data);
                }
            },
            formatResult: repoFormatResult,
            formatSelection: repoFormatResult,
            escapeMarkup: function(m) { return m; }
        });
    });
    $('.st_user_select_ajax').each(function(){
        var me=$(this);
        $(this).select2({
            placeholder: me.data('placeholder'),
            minimumInputLength:2,
            ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                url: ajaxurl,
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term, // search term,
                        action:'st_user_select_ajax'
                        //post_type:me.data('post-type')
                    };
                },
                results: function (data, page) { // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to alter the remote JSON data
                    return { results: data.items };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                // the input tag has a value attribute preloaded that points to a preselected repository's id
                // this function resolves that id attribute to an object that select2 can render
                // using its formatResult renderer - that way the repository name is shown preselected
                var id = $(element).val();
                if (id !== "") {
                    var data={
                        id:id,
                        name:$(element).data('pl-name'),
                        description:$(element).data('pl-desc')
                    };
                    callback(data);
                }
            },
            formatResult: repoFormatResult,
            formatSelection: repoFormatResult,
            escapeMarkup: function(m) { return m; }
        });
    });



    jQuery('.st_datepicker').each(function(){
        jQuery(this).datepicker({

            dateFormat : 'yy/mm/dd',

        });
    });
    
    // Tour Booking
    $('select#type_tour').change(function(){

        if($(this).val()=='daily_tour')
        {
            $('.type_tour_daily_tuor').show();
            $('.type_tour_specific_date').hide();
        }else
        {
            $('.type_tour_daily_tuor').hide();
            $('.type_tour_specific_date').show();

        }

    }).trigger('change');

    $('select#type_price').change(function(){

        if($(this).val()=='tour_price')
        {
            $('.type_price_tour_price').show();
            $('.type_price_people_price').hide();
        }else
        {
            $('.type_price_tour_price').hide();
            $('.type_price_people_price').show();

        }

    }).trigger('change');

    $('select#activity_type_price').change(function(){

        if($(this).val()=='tour_price')
        {
            $('.type_price_activity_price').show();
            $('.type_price_people_price').hide();
        }else
        {
            $('.type_price_activity_price').hide();
            $('.type_price_people_price').show();

        }

    }).trigger('change');




})(jQuery);