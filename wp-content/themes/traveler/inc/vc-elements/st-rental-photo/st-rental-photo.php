<?php
if(!st_check_service_available( 'st_rental' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    vc_map( array(
        "name"            => __( "ST Rental Photo" , ST_TEXTDOMAIN ) ,
        "base"            => "st_rental_photo" ,
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

if(!function_exists( 'st_vc_rental_photo' )) {
    function st_vc_rental_photo( $attr , $content = false )
    {
        $default = array(
            'style' => 'slide' ,
        );

        $attr = wp_parse_args( $attr , $default );

        if(is_singular( 'st_rental' )) {
            return st()->load_template( 'rental/elements/photo' , null , array( 'attr' => $attr ) );
        }
    }
}
if(st_check_service_available( 'st_rental' )) {
    st_reg_shortcode( 'st_rental_photo' , 'st_vc_rental_photo' );
}