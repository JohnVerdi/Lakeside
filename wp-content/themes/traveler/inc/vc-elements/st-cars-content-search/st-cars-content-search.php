<?php
if(!st_check_service_available( 'st_cars' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    vc_map( array(
        "name"            => __( "ST Cars Search Results" , ST_TEXTDOMAIN ) ,
        "base"            => "st_cars_content_search" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        "params"          => array(
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Style" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_style" ,
                "description" => "" ,
                "value"       => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'List' , ST_TEXTDOMAIN )       => '1' ,
                    __( 'Grid' , ST_TEXTDOMAIN )       => '2' ,
                ) ,
            )
        )
    ) );
}
if(!function_exists( 'st_vc_cars_content_search' )) {
    function st_vc_cars_content_search( $attr , $content = false )
    {
        $default = array(
            'st_style' => 1
        );
        $attr    = wp_parse_args( $attr , $default );
		global $st_search_query;
		if(!$st_search_query) return;
        return st()->load_template( 'cars/content' , 'cars' , array( 'attr' => $attr ) );
    }
}

if(st_check_service_available( 'st_cars' )) {
    st_reg_shortcode( 'st_cars_content_search' , 'st_vc_cars_content_search' );
}