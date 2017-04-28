<?php
if(function_exists( 'vc_map' )) {
    vc_map( array(
        "name"            => __( "ST Owner Listing" , ST_TEXTDOMAIN ) ,
        "base"            => "st_info_owner" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        "params"          => array(
            array(
                "type"        => "textfield" ,
                "holder"      => "div" ,
                "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"  => "title" ,
                "description" => "" ,
            ) ,
            array(
                "type"       => "dropdown" ,
                "holder"     => "div" ,
                "heading"    => __( "Config show/hide avatar" , ST_TEXTDOMAIN ) ,
                "param_name" => "show_avatar" ,
                'value'      => array(
                    __( 'Show' , ST_TEXTDOMAIN ) => 'true' ,
                    __( 'Hide' , ST_TEXTDOMAIN )  => 'false' ,
                )
            ) ,
            array(
                "type"       => "dropdown" ,
                "holder"     => "div" ,
                "heading"    => __( "Config show/hide email, social icons" , ST_TEXTDOMAIN ) ,
                "param_name" => "show_social" ,
                'value'      => array(
                    __( 'Show' , ST_TEXTDOMAIN ) => 'true' ,
                    __( 'Hide' , ST_TEXTDOMAIN )  => 'false' ,
                )
            ) ,
            array(
                "type"       => "dropdown" ,
                "holder"     => "div" ,
                "heading"    => __( "Config show/hide: Member since" , ST_TEXTDOMAIN ) ,
                "param_name" => "show_member_since" ,
                'value'      => array(
                    __( 'Show' , ST_TEXTDOMAIN ) => 'true' ,
                    __( 'Hide' , ST_TEXTDOMAIN )  => 'false' ,
                )
            ) ,
            array(
                "type"       => "dropdown" ,
                "holder"     => "div" ,
                "heading"    => __( "Config show/hide: Short Description" , ST_TEXTDOMAIN ) ,
                "param_name" => "show_short_info" ,
                'value'      => array(
                    __( 'Show' , ST_TEXTDOMAIN ) => 'true' ,
                    __( 'Hide' , ST_TEXTDOMAIN )  => 'false' ,
                )
            ) ,
        )
    ) );

}
if(!function_exists( 'st_vc_info_owner' )) {
    function st_vc_info_owner( $attr , $content = false )
    {
        $data = shortcode_atts(
            array(
                'title'             => '' ,
                'show_avatar'       => 'true' ,
                'show_social'       => 'true' ,
                'show_member_since' => 'true' ,
                'show_short_info'   => 'true' ,
            ) , $attr , 'st_info_owner' );
        extract( $data );

        return st()->load_template( 'vc-elements/st-info-owner/st-info-owner' , false , array( "data" => $data ) );
    }

}
st_reg_shortcode( 'st_info_owner' , 'st_vc_info_owner' );

