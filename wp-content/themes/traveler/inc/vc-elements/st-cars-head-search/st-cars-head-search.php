<?php
if(!st_check_service_available( 'st_cars' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    vc_map( array(
        "name"            => __( "ST Sum of Cars Search Results" , ST_TEXTDOMAIN ) ,
        "base"            => "st_search_cars_title" ,
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
if(!function_exists( 'st_search_cars_title' )) {
    function st_search_cars_title( $arg = array() )
    {
        if(!get_post_type() == 'st_cars' and get_query_var( 'post_type' ) != "st_cars")
            return;

        $default = array(
            'search_modal' => 1
        );

        extract( wp_parse_args( $arg , $default ) );

        $car  = new STCars();
        $html = '<h3 class="booking-title">' . balanceTags( $car->get_result_string() );

        if($search_modal) {
            $html .= '<small><a class="popup-text" href="#search-dialog" data-effect="mfp-zoom-out">' . __( 'Change search' , ST_TEXTDOMAIN ) . '</a></small>';
        }
        $html .= '</h3>';

        return $html;
    }
}
if(st_check_service_available( 'st_cars' )) {
    st_reg_shortcode( 'st_search_cars_title' , 'st_search_cars_title' );
}
