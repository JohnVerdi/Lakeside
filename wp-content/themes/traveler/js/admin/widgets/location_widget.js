// open media wordpess when click button 
if (jQuery('.set_custom_images').length > 0) {

    if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
        jQuery('.wrap').on('click', '.set_custom_images', function(e) {
            e.preventDefault();
            var button = jQuery(this);
            var id = button.prev();
            wp.media.editor.send.attachment = function(props, attachment) {
                id.val(attachment.id);
            };
            wp.media.editor.open(button);
            return false;
        });
    }
};
// change display when click button  
jQuery(function($){
    $("button.del_bgr").each(function(){
       
        $(this).click(function(){
            var parent = $(this).parent("p");
            parent.find(".bgr_info_hidden").val("");
            parent.find("img").remove();
            $(this).remove();
            return false ; 
        });
        
         /*$(this).click(function(){
            $.ajax({
                url: ajaxurl,
                data: {
                    'action':'update_background',
                },
                success:function(data) {
                    // This outputs the result of the ajax request
                    
                },
                error: function(error){
                    console.log(error);
                }
            }); 
            return false; 

        });*/

        
    })
});
jQuery(document).ready(function($){  
    $(this).on("click",".checked_label_location",function(){
        $(this).prev().attr("checked" , "");       
    });
});