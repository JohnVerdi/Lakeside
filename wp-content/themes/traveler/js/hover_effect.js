jQuery(function(){
    jQuery(window).load(function(){
        // hover efect 
      jQuery(".effect-layla img").each(function(){
        var c_height = jQuery(this).height();
        console.log(c_height);
        if (c_height>0){
            jQuery(this).parent(".effect-layla").height(c_height-30);
        }
        
      })
    
        
    })
});