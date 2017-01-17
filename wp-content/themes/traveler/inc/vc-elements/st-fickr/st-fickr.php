<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Flickr", ST_TEXTDOMAIN),
            "base" => "st_flickr",
            "content_element" => true,
            "icon" => "icon-st",
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Number", ST_TEXTDOMAIN),
                    "param_name" => "st_number",
                    "description" =>"",
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("User", ST_TEXTDOMAIN),
                    "param_name" => "st_user",
                    "description" => ""
                ),
            )
        ) );
    }

    if(!function_exists('st_vc_flickr')){
        function st_vc_flickr($arg,$content=false)
        {
            $data = shortcode_atts(array(
                'st_number' =>5,
                'st_user'=>'23401669@N00',
            ), $arg, 'st_flickr' );
            extract($data);
            $r = st()->load_template('vc-elements/st-fickr/html',null,$data);
            return $r;
        }
    }
    st_reg_shortcode('st_flickr','st_vc_flickr');
