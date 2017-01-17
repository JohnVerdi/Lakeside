<?php
if(function_exists('vc_map')){
    vc_map( array(
        "name" => __("ST Under construction", ST_TEXTDOMAIN),
        "base" => "st_under_construction",
        "content_element" => true,
        "icon" => "icon-st",
        "params" => array(
            // add params same as with any other content element
            array(
                "type" => "textfield",
                "heading" => __("Short description", ST_TEXTDOMAIN),
                "param_name" => "st_text",
                "description" =>"",
            ),
            array(
                "type" => "textfield",
                "heading" => __("End Date", ST_TEXTDOMAIN),
                "param_name" => "st_enddate",
                "description" =>"",
            ),

        )
    ) );
}

if(!function_exists('st_vc_under_construction')){
    function st_vc_under_construction($arg)
    {
        echo "<pre>";
        print_r($arg);
        echo "</pre>";
        
        return __FUNCTION__ ;
    }
}
st_reg_shortcode('st_under_construction','st_vc_under_construction');