<?php
return;
if(!st_check_service_available( 'st_hotel' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    vc_map( array(
        "name"            => __( "ST Hotel List Room" , ST_TEXTDOMAIN ) ,
        "base"            => "st_hotel_list_room" ,
        "content_element" => true ,
        "category"        => "Shinetheme" ,
        "icon"            => "icon-st" ,
        "params"          => array(
            array(
                "type"       => "dropdown" ,
                "heading"    => __( "Rows" , ST_TEXTDOMAIN ) ,
                "param_name" => "st_rows" ,
                "value"      => array(
                    __( "--Select--" , ST_TEXTDOMAIN ) => "" ,
                    __( "1" , ST_TEXTDOMAIN )          => "1" ,
                    __( "2" , ST_TEXTDOMAIN )          => "2" ,
                    __( "3" , ST_TEXTDOMAIN )          => "3" ,
                    __( "4" , ST_TEXTDOMAIN )          => "4" ,
                    __( "5" , ST_TEXTDOMAIN )          => "5" ,
                    __( "6" , ST_TEXTDOMAIN )          => "6" ,
                )
            ) ,
            array(
                "type"       => "dropdown" ,
                "heading"    => __( "Items in a row" , ST_TEXTDOMAIN ) ,
                "param_name" => "st_items_in_row" ,
                "value"      => array(
                    __( "--Select--" , ST_TEXTDOMAIN ) => "" ,
                    __( "1" , ST_TEXTDOMAIN )          => "1" ,
                    __( "2" , ST_TEXTDOMAIN )          => "2" ,
                    __( "3" , ST_TEXTDOMAIN )          => "3" ,
                    __( "4" , ST_TEXTDOMAIN )          => "4" ,
                    __( "6" , ST_TEXTDOMAIN )          => "6" ,
                )
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => __( "Show Title" , ST_TEXTDOMAIN ) ,
                "param_name"  => "is_title" ,
                "description" => "" ,
                "value"       => array(
                    __( "--Select--" , ST_TEXTDOMAIN ) => "" ,
                    __( "Yes" , ST_TEXTDOMAIN )        => "yes" ,
                    __( "No" , ST_TEXTDOMAIN )         => "no" ,
                )
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => __( "Show Price" , ST_TEXTDOMAIN ) ,
                "param_name"  => "is_price" ,
                "description" => "" ,
                "value"       => array(
                    __( "--Select--" , ST_TEXTDOMAIN ) => "" ,
                    __( "Yes" , ST_TEXTDOMAIN )        => "yes" ,
                    __( "No" , ST_TEXTDOMAIN )         => "no" ,
                )
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => __( "Show Facilities" , ST_TEXTDOMAIN ) ,
                "param_name"  => "is_facilities" ,
                "description" => "" ,
                "value"       => array(
                    __( "--Select--" , ST_TEXTDOMAIN ) => "" ,
                    __( "Yes" , ST_TEXTDOMAIN )        => "yes" ,
                    __( "No" , ST_TEXTDOMAIN )         => "no" ,
                )
            ) ,

        )
    ) );
    if(!function_exists( 'st_hotel_list_room_func' )) {
        function st_hotel_list_room_func( $attr )
        {
            $data = shortcode_atts(
                array(
                    'st_rows'         => 2 ,
                    'st_items_in_row' => 3 ,
                    'is_title'        => 'yes' ,
                    'is_facilities'   => 'yes' ,
                    'is_price'        => 'yes'

                ) , $attr , 'st_hotel_list_room' );
            extract( $data );
            $arg = array(
                'post_type'      => 'hotel_room' ,
                'posts_per_page' => $st_items_in_row * $st_rows ,
                'post_status'    => 'publish' ,
            );
            query_posts( $arg );
            $return = "";
            $return .= "<div class='st_hotel_list_room st_grid'>";
            if(have_posts()) {
                while( have_posts() ) {
                    the_post();
                    $return .= st()->load_template( 'vc-elements/st-hotel-list-room/st_hotel_list_room' , null , $data );
                }
            }
            wp_reset_postdata();
            $return .= "</div>";
            return $return;

        }

        if(st_check_service_available( 'st_hotel' )) {
            st_reg_shortcode( 'st_hotel_list_room' , 'st_hotel_list_room_func' );
        }

    }
}