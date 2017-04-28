<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Cruise Photo", ST_TEXTDOMAIN),
            "base" => "st_cruise_photo",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>'Shinetheme',
            "params" => array(
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Style", ST_TEXTDOMAIN),
                    "param_name" => "style",
                    "description" =>"",
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Slide',ST_TEXTDOMAIN)=>'slide',
                        __('Grid',ST_TEXTDOMAIN)=>'grid',
                    ),
                )
            )
        ) );
    }
    if(!function_exists('st_vc_cruise_photo')){
        function st_vc_cruise_photo($attr,$content=false)
        {
            if(is_singular('cruise'))
            {
                return st()->load_template('cruise/elements/photo',null,array('attr'=>$attr));
            }
        }
    }
    st_reg_shortcode('st_cruise_photo','st_vc_cruise_photo');