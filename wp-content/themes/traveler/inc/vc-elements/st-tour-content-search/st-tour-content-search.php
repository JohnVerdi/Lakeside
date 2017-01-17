<?php
if(!st_check_service_available( 'st_tours' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    $list = st_list_taxonomy( 'st_tours' );
    $txt  = __( '--Select--' , ST_TEXTDOMAIN );
    unset( $list[ $txt ] );
    vc_map( array(
        "name"            => __( "ST Tour Search Results" , ST_TEXTDOMAIN ) ,
        "base"            => "st_tour_content_search" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => 'Shinetheme' ,
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
            ) ,
            array(
                "type"        => "checkbox" ,
                "holder"      => "div" ,
                "heading"     => __( "Select Taxonomy Show" , ST_TEXTDOMAIN ) ,
                "param_name"  => "taxonomy" ,
                "description" => "" ,
                "value"       => $list ,
            )
        )
    ) );
}

if(!function_exists( 'st_vc_tour_content_search' )) {
    function st_vc_tour_content_search( $attr , $content = false )
    {
        $default = array(
            'st_style' => 1 ,
            'taxonomy' => ''
        );
        $attr    = wp_parse_args( $attr , $default );
		global $st_search_query;
		if(!$st_search_query) return;
        //if(is_search())
        //{
        return st()->load_template( 'tours/content' , 'tours' , array( 'attr' => $attr ) );
        //}
    }
}
if(st_check_service_available( 'st_tours' )) {
    st_reg_shortcode( 'st_tour_content_search' , 'st_vc_tour_content_search' );
}