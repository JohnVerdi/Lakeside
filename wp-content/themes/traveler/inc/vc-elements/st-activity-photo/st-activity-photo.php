<?php
if(!st_check_service_available( 'st_activity' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    vc_map( array(
        "name"            => __( "ST Detailed Activity Gallery" , ST_TEXTDOMAIN ) ,
        "base"            => "st_activity_detail_photo" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => 'Shinetheme' ,
        "params"          => array(
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Style" , ST_TEXTDOMAIN ) ,
                "param_name"  => "style" ,
                "description" => "" ,
                "value"       => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'Slide' , ST_TEXTDOMAIN )      => 'slide' ,
                    __( 'Grid' , ST_TEXTDOMAIN )       => 'grid' ,
                ) ,
            )
        )
    ) );
}
if(!function_exists( 'st_vc_activity_detail_photo' )) {
    function st_vc_activity_detail_photo( $attr , $content = false )
    {
        $default = array(
            'style' => 'slide'
        );
        $attr    = wp_parse_args( $attr , $default );
        if(is_singular( 'st_activity' )) {
            return st()->load_template( 'activity/elements/photo' , null , array( 'attr' => $attr ) );
        }
    }
}

if(st_check_service_available( 'st_activity' )) {
    st_reg_shortcode( 'st_activity_detail_photo' , 'st_vc_activity_detail_photo' );
}