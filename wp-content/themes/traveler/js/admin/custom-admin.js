jQuery(document).ready(function($) {
	$('.user-role-wrap #role').change(function(){
        var $value = $(this).val();
        if($value == "partner"){
            $("#partner_service").show();
        }else{
            $("#partner_service").hide();
        }
    });

   
    if( $('.st-select-loction').length ){
        $('.st-select-loction').each(function(index, el) {
            var parent = $(this);
            var input = $('input[name="search"]', parent);
            var list = $('.list-location-wrapper', parent);
            var timeout;
            input.keyup(function(event) {
                clearTimeout( timeout );
                var t = $(this);
                timeout = setTimeout(function(){
                    var text = t.val().toLowerCase();
                    if( text == ''){
                        $('.item', list).show();
                    }else{
                        $('.item', list).hide();
                        $(".item",list).each(function(){
                            var name = $(this).data("name").toLowerCase();
                            var reg = new RegExp(text, "g");
                            if (reg.test(name)){
                                $(this).show();
                            }
                        });
                        //$(".item[data-name*='"+text+"']", list).show();
                    }
                    
                }, 00);
            });
        });
    }
});