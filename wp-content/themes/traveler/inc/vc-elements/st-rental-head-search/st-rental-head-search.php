<?php
if(!st_check_service_available( 'st_rental' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    vc_map( array(
        "name"            => __( "ST Sum of Rental Search Results" , ST_TEXTDOMAIN ) ,
        "base"            => "st_search_rental_title" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        "params"          => array(
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Search Modal" , ST_TEXTDOMAIN ) ,
                "param_name"  => "search_modal" ,
                "description" => "" ,
                "value"       => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'Yes' , ST_TEXTDOMAIN )        => '1' ,
                    __( 'No' , ST_TEXTDOMAIN )         => '0' ,
                ) ,
            )
        )
    ) );
}
if(!function_exists( 'st_search_rental_title' )) {
    function st_search_rental_title( $arg = array() )
    {
        if(!get_post_type() == 'st_rental' and get_query_var( 'post_type' ) != 'st_rental')
            return;

        $default = array(
            'search_modal' => 1
        );

        extract( wp_parse_args( $arg , $default ) );

        $object = new STRental();
        $a      = '<h3 class="booking-title">' . balanceTags( $object->get_result_string() );

        if($search_modal) {
            $a .= '<small><a class="popup-text" href="#search-dialog" data-effect="mfp-zoom-out">' . __( 'Change search' , ST_TEXTDOMAIN ) . '</a></small>';
        }
        $a .= '</h3>';

        return $a;
    }
}
if(st_check_service_available( 'st_rental' )) {
    st_reg_shortcode( 'st_search_rental_title' , 'st_search_rental_title' );
}