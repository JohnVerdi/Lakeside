<?php
if(!st_check_service_available( 'st_tours' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    vc_map( array(
        "name"            => __( "ST Tour Detail Attribute" , ST_TEXTDOMAIN ) ,
        "base"            => "st_tour_detail_attribute" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => 'Shinetheme' ,
        "params"          => array(
            array(
                "type"             => "textfield" ,
                "holder"           => "div" ,
                "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"       => "title" ,
                "description"      => "" ,
                "value"            => "" ,
                'edit_field_class' => 'vc_col-sm-6' ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                "param_name"       => "font_size" ,
                "description"      => "" ,
                "value"            => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( "H1" , ST_TEXTDOMAIN )         => '1' ,
                    __( "H2" , ST_TEXTDOMAIN )         => '2' ,
                    __( "H3" , ST_TEXTDOMAIN )         => '3' ,
                    __( "H4" , ST_TEXTDOMAIN )         => '4' ,
                    __( "H5" , ST_TEXTDOMAIN )         => '5' ,
                ) ,
                'edit_field_class' => 'vc_col-sm-6' ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Select Taxonomy" , ST_TEXTDOMAIN ) ,
                "param_name"  => "taxonomy" ,
                "description" => "" ,
                "value"       => st_list_taxonomy( 'st_tours' ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Item col" , ST_TEXTDOMAIN ) ,
                "param_name"  => "item_col" ,
                "description" => "" ,
                "value"       => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    12                                 => 12 ,
                    11                                 => 11 ,
                    10                                 => 10 ,
                    9                                  => 9 ,
                    8                                  => 8 ,
                    7                                  => 7 ,
                    6                                  => 6 ,
                    5                                  => 5 ,
                    4                                  => 4 ,
                    3                                  => 3 ,
                    2                                  => 2 ,

                ) ,
            )
        )
    ) );
}

if(!function_exists( 'st_tour_detail_attribute' )) {
    function st_tour_detail_attribute( $attr , $content = false )
    {
        $default = array(
            'item_col'  => 12 ,
            'font_size' => 4
        );
        $attr    = wp_parse_args( $attr , $default );

        if(is_singular( 'st_tours' )) {
            return st()->load_template( 'tours/elements/attribute' , null , array( 'attr' => $attr ) );
        }
    }
}
if(st_check_service_available( 'st_tours' )) {
    st_reg_shortcode( 'st_tour_detail_attribute' , 'st_tour_detail_attribute' );
}