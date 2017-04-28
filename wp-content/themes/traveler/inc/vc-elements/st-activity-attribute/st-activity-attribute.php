<?php
if(!st_check_service_available( 'st_activity' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    vc_map( array(
        "name"            => __( "ST Activity Detail Attribute" , ST_TEXTDOMAIN ) ,
        "base"            => "st_activity_detail_attribute" ,
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
                "value"       => st_list_taxonomy( 'st_activity' ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Item Size" , ST_TEXTDOMAIN ) ,
                "param_name"  => "item_col" ,
                "description" => "" ,
                "value"       => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    2                                  => 2 ,
                    3                                  => 3 ,
                    4                                  => 4 ,
                    5                                  => 5 ,
                    6                                  => 6 ,
                    7                                  => 7 ,
                    8                                  => 8 ,
                    9                                  => 9 ,
                    10                                 => 10 ,
                    11                                 => 11 ,
                    12                                 => 12 ,
                ) ,
            )
        )
    ) );
}

if(!function_exists( 'st_activity_detail_attribute' )) {
    function st_activity_detail_attribute( $attr , $content = false )
    {
        $default = array(
            'item_col'  => 2 ,
            'font_size' => 4
        );
        $attr    = wp_parse_args( $attr , $default );
        if(is_singular( 'st_activity' )) {
            return st()->load_template( 'activity/elements/attribute' , null , array( 'attr' => $attr ) );
        }
    }
}
if(st_check_service_available( 'st_activity' )) {
    st_reg_shortcode( 'st_activity_detail_attribute' , 'st_activity_detail_attribute' );
}
