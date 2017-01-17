/**
 * Created by me664 on 12/30/14.
 */
jQuery(document).ready(function($){

    var item_type=$('#item_type').val();
    var post_type;

    $('#check_in').datepicker({dateFormat:'yy-mm-dd', firstDay: 1});
    $('#check_out').datepicker({"dateFormat":'yy-mm-dd', firstDay: 1});

    $('#item_type').change(function(){
        item_type=$('#item_type').val();

        $('.form_row_spec').hide();

        $('.form_row_spec.'+item_type).show();

        switch(item_type){
            case "hotel":
                    $('label[for=item_id2]').html('Room');
                break;

        }
    });
    $('#item_type').trigger('change');
    $('.st_add_order_item').click(function(){
        $('.st_create_item_wrap').removeClass('hidden');
    });

    function get_post_type()
    {
        switch (item_type){
            case "hotel":
                return "hotel_room";
                break;
        }
    }
    var on_ajax_load=false;
    $('.st_save_new_item').click(function(){
        var me=$(this);
        var next=me.next('.loading');
        if(on_ajax_load) return;
        $(this).next('.loading').remove();

        $(this).after('<span class="loading">Saving...</span>');

        on_ajax_load=true;

        var data=$('.st_create_item').find('input,textarea,select').serializeArray();


        $.ajax({
            dataType:'json',
            url:ajaxurl,
            data:data,
            success:function(res){
                if(typeof res.message=="undefined" && res.message){
                    alert(res.message);
                }
                if(res.reload) window.location.reload();
                me.next('.loading').remove();
                on_ajax_load=false;
            },
            error:function(res){
                alert('Ajax Fail');
                console.log(res);
                me.next('.loading').remove();
                on_ajax_load=false;
            }
        });
    });

    $('#item_id').select2({
        //placeholder: me.data('placeholder'),
        minimumInputLength:2,
        ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
            url: ajaxurl,
            dataType: 'json',
            quietMillis: 250,
            data: function (term, page) {
                return {
                    q: term, // search term,
                    action:'st_order_select',
                    post_type:$('#item_type').val()
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
        formatResult: function(state){
            if (!state.id) return state.name; // optgroup
            return state.name+'<p><em>'+state.description+'</em></p>';
        },
        formatSelection: function(state){
            if (!state.id) return state.name; // optgroup
            return state.name+'<p><em>'+state.description+'</em></p>';
        },
        escapeMarkup: function(m) { return m; }
    });
    $('#item_id2').select2({
        //placeholder: me.data('placeholder'),
        minimumInputLength:2,
        ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
            url: ajaxurl,
            dataType: 'json',
            quietMillis: 250,
            data: function (term, page) {
                return {
                    q: term, // search term,
                    action:'st_order_select',
                    post_type:get_post_type(),
                    item_id:$('#item_id').val()
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
        formatResult: function(state){
            if (!state.id) return state.name; // optgroup
            return state.name+'<p><em>'+state.description+'</em></p>';
        },
        formatSelection: function(state){
            if (!state.id) return state.name; // optgroup
            return state.name+'<p><em>'+state.description+'</em></p>';
        },
        escapeMarkup: function(m) { return m; }
    });

    $('#item_id2').on('change',function(){

    });

    $('.st_delete_item').click(function(){
       var id=$(this).data('id');
       var me=$(this);

        if(id){
            if(confirm('Are you sure?')){
                $.ajax({
                    dataType:'json',
                    url:ajaxurl,
                    data:{
                        action:'st_delete_order_item',
                        id:id
                    },
                    success:function(res){

                        if(typeof res.message=="undefined" && res.message){
                            alert(res.message);
                        }
                        if(res.reload) window.location.reload();
                        on_ajax_load=false;
                        me.parents('tr').remove();
                    },
                    error:function(res){
                        alert('Ajax Fail');
                        console.log(res);
                        on_ajax_load=false;
                    }
                });
            }
        }
    });




});