<?php
if(function_exists( 'vc_map' )) {
    vc_map( array(
        "name"                    => __( "ST Search Filter" , ST_TEXTDOMAIN ) ,
        "base"                    => "st_search_filter" ,
        "as_parent"               => array( 'only' => 'st_filter_price,st_filter_rate,st_filter_hotel_rate,st_filter_taxonomy' ) ,
        "content_element"         => true ,
        "show_settings_on_create" => true ,
        "js_view"                 => 'VcColumnView' ,
        "icon"                    => "icon-st" ,
        "category"                => "Shinetheme" ,
        "params"                  => array(
            array(
                "type"        => "textfield" ,
                "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"  => "title" ,
                "description" => "" ,
            ) ,
            array(
                "type"       => "dropdown" ,
                "heading"    => __( "Style" , ST_TEXTDOMAIN ) ,
                "param_name" => "style" ,
                "value"      => array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __( 'Dark' , ST_TEXTDOMAIN )  => 'dark' ,
                    __( 'Light' , ST_TEXTDOMAIN ) => 'light' ,
                ) ,
            ) ,
        )
    ) );
    vc_map( array(
        "name"            => __( "ST Filter Price" , ST_TEXTDOMAIN ) ,
        "base"            => "st_filter_price" ,
        "content_element" => true ,
        "as_child"        => array( 'only' => 'st_search_filter' ) ,
        "icon"            => "icon-st" ,
        "params"          => array(
            array(
                "type"        => "textfield" ,
                "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"  => "title" ,
                "description" => "" ,
            ) ,
            array(
                "type"       => "dropdown" ,
                "heading"    => __( "Post Type" , ST_TEXTDOMAIN ) ,
                "param_name" => "post_type" ,
                "value"      => array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __( 'Hotel' , ST_TEXTDOMAIN )    => 'st_hotel' ,
                    __( 'Hotel Room' , ST_TEXTDOMAIN )    => 'hotel_room' ,
                    __( 'Rental' , ST_TEXTDOMAIN )   => 'st_rental' ,
                    __( 'Car' , ST_TEXTDOMAIN )      => 'st_cars' ,
                    __( 'Tour' , ST_TEXTDOMAIN )     => 'st_tours' ,
                    __( 'Activity' , ST_TEXTDOMAIN ) => 'st_activity' ,
                    __( 'All Post Type' , ST_TEXTDOMAIN )    => 'all' ,
                ) ,
            ) ,
        )
    ) );
    vc_map( array(
        "name"            => __( "ST Filter Rate" , ST_TEXTDOMAIN ) ,
        "base"            => "st_filter_rate" ,
        "content_element" => true ,
        "as_child"        => array( 'only' => 'st_search_filter' ) ,
        "icon"            => "icon-st" ,
        "params"          => array(
            array(
                "type"        => "textfield" ,
                "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"  => "title"
            )
        )
    ) );
    vc_map( array(
        "name"            => __( "ST Filter Hotel Star Rating" , ST_TEXTDOMAIN ) ,
        "base"            => "st_filter_hotel_rate" ,
        "content_element" => true ,
        "as_child"        => array( 'only' => 'st_search_filter' ) ,
        "icon"            => "icon-st" ,
        "params"          => array(
            array(
                "type"        => "textfield" ,
                "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"  => "title" ,
                "description" => "" ,
            ) ,
        )
    ) );

    $param_taxonomy = array(
        array(
            "type"        => "textfield" ,
            "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
            "param_name"  => "title" ,
            "description" => "" ,
        ) ,
        array(
            "type"       => "dropdown" ,
            "holder"     => "div" ,
            "heading"    => __( "Post Type" , ST_TEXTDOMAIN ) ,
            "param_name" => "st_post_type" ,
            "value"      => array(
                __('--Select--',ST_TEXTDOMAIN)=>'',
                __( 'Hotel' , ST_TEXTDOMAIN )    => 'st_hotel' ,
                __( 'Room Hotel' , ST_TEXTDOMAIN ) => 'hotel_room' ,
                __( 'Rental' , ST_TEXTDOMAIN )   => 'st_rental' ,
                __( 'Car' , ST_TEXTDOMAIN )      => 'st_cars' ,
                __( 'Tour' , ST_TEXTDOMAIN )     => 'st_tours' ,
                __( 'Activity' , ST_TEXTDOMAIN ) => 'st_activity' ,
            ) ,
        )
    );

    $list_post_type = array(
        __( 'Hotel' , ST_TEXTDOMAIN )    => 'st_hotel' ,
        __( 'Room Hotel' , ST_TEXTDOMAIN ) => 'hotel_room' ,
        __( 'Rental' , ST_TEXTDOMAIN )   => 'st_rental' ,
        __( 'Car' , ST_TEXTDOMAIN )      => 'st_cars' ,
        __( 'Tour' , ST_TEXTDOMAIN )     => 'st_tours' ,
        __( 'Activity' , ST_TEXTDOMAIN ) => 'st_activity' ,
    );
    foreach( $list_post_type as $k => $v ) {
        $_taxonomy      = st_list_taxonomy( $v );
        $_param         = array(
            "type"       => "dropdown" ,
            "holder"     => "div" ,
            "heading"    => sprintf(__("Taxonomy %s",ST_TEXTDOMAIN),$k),
            "param_name" => "taxonomy_" . $v ,
            "value"      => '' ,
            'dependency' => array(
                'element' => 'st_post_type' ,
                'value'   => array( $v )
            ) ,
        );
        $_list_taxonomy = array();
        $_list_taxonomy[__('--Select--',ST_TEXTDOMAIN)]='';
        foreach( $_taxonomy as $key => $value ) {
            $_list_taxonomy[ $key ] = $value;
        }
        $_param[ 'value' ] = $_list_taxonomy;
        $param_taxonomy[ ] = $_param;
    }

    vc_map( array(
        "name"            => __( "ST Filter Taxonomy" , ST_TEXTDOMAIN ) ,
        "base"            => "st_filter_taxonomy" ,
        "content_element" => true ,
        "as_child"        => array( 'only' => 'st_search_filter' ) ,
        "icon"            => "icon-st" ,
        "params"          => $param_taxonomy
    ) );
}

if(class_exists( 'WPBakeryShortCodesContainer' ) and !class_exists( 'WPBakeryShortCode_st_search_filter' )) {
    class WPBakeryShortCode_st_search_filter extends WPBakeryShortCodesContainer
    {
        protected function content( $arg , $content = null )
        {
            $data = shortcode_atts( array(
                'title' => "" ,
                'style' => "" ,
            ) , $arg , 'st_search_filter' );
            extract( $data );
            $content = do_shortcode( $content );
            if($style == 'dark') {
                $class_side_bar = 'booking-filters text-white';
            } else {
                $class_side_bar = 'booking-filters booking-filters-white';
            }
            $html = '<aside class="st-elements-filters ' . $class_side_bar . '">
                        <h3>' . $title . '</h3>
                        <ul class="list booking-filters-list">' . $content . '</ul>
                    </aside>';
            return $html;
        }
    }
}
if(class_exists( 'WPBakeryShortCode' ) and !class_exists( 'WPBakeryShortCode_st_filter_price' )) {
    class WPBakeryShortCode_st_filter_price extends WPBakeryShortCode
    {
        protected function content( $arg , $content = null )
        {
            $data = shortcode_atts( array(
                'title'     => "" ,
                'post_type' => "" ,
            ) , $arg , 'st_filter_price' );
            extract( $data );
            $html = '<li><h5 class="booking-filters-title">' . $title . '</h5>' . st()->load_template( 'vc-elements/st-search-filter/filter' , 'price' , array( 'post_type' => $post_type ) ) . '</li>';
            return $html;
        }
    }
}
if(class_exists( 'WPBakeryShortCode' ) and !class_exists( 'WPBakeryShortCode_st_filter_rate' )) {
    class WPBakeryShortCode_st_filter_rate extends WPBakeryShortCode
    {
        protected function content( $arg , $content = null )
        {
            $data = shortcode_atts( array(
                'title' => "" ,
            ) , $arg , 'st_filter_rate' );
            extract( $data );
            $html = '<li><h5 class="booking-filters-title">' . $title . '</h5>' . st()->load_template( 'vc-elements/st-search-filter/filter' , 'rate' , array() ) . '</li>';
            return $html;
        }
    }
}
if(class_exists( 'WPBakeryShortCode' ) and !class_exists( 'WPBakeryShortCode_st_filter_hotel_rate' )) {
    class WPBakeryShortCode_st_filter_hotel_rate extends WPBakeryShortCode
    {
        protected function content( $arg , $content = null )
        {
            $data = shortcode_atts( array(
                'title' => "" ,
            ) , $arg , 'st_filter_hotel_rate' );
            extract( $data );
            $html = '<li><h5 class="booking-filters-title">' . $title . '</h5>' . st()->load_template( 'vc-elements/st-search-filter/filter' , 'hotel-rate' , array() ) . '</li>';
            return $html;
        }
    }
}
if(class_exists( 'WPBakeryShortCode' ) and !class_exists( 'WPBakeryShortCode_st_filter_taxonomy' )) {
    class WPBakeryShortCode_st_filter_taxonomy extends WPBakeryShortCode
    {
        protected function content( $arg , $content = null )
        {
            $data = shortcode_atts( array(
                'title'                => "" ,
                'st_post_type'            => "" ,
                'taxonomy_st_hotel'    => "" ,
                'taxonomy_st_rental'   => "" ,
                'taxonomy_st_cars'     => "" ,
                'taxonomy_st_tours'    => "" ,
                'taxonomy_st_activity' => "" ,
                'taxonomy_hotel_room' => "" ,
            ) , $arg , 'st_filter_taxonomy' );
            extract( $data );
            switch($st_post_type){
                case"st_hotel":
                    $taxonomy =$taxonomy_st_hotel;
                    break;
                case"st_rental":
                    $taxonomy =$taxonomy_st_rental;
                    break;
                case"st_cars":
                    $taxonomy =$taxonomy_st_cars;
                    break;
                case"st_tours":
                    $taxonomy =$taxonomy_st_tours;
                    break;
                case"st_activity":
                    $taxonomy =$taxonomy_st_activity;
                    break;
                case"hotel_room":
                    $taxonomy =$taxonomy_hotel_room;
                    break;
            }
            $html = '<li><h5 class="booking-filters-title">' . $title . '</h5>' . st()->load_template( 'vc-elements/st-search-filter/filter' , 'taxonomy' ,  array('taxonomy'=>$taxonomy,'post_type'=>$st_post_type) ) . '</li>';
            return $html;
        }
    }
}
