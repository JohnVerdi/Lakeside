<?php
/**
 *
 *
 * @since 1.1.5
 * */
if(function_exists( 'vc_map' )) {
    $list_location                                              = TravelerObject::get_list_location();
    $list_location_data[ __( '-- Select --' , ST_TEXTDOMAIN ) ] = '';
    if(!empty( $list_location )) {
        foreach( $list_location as $k => $v ) {
            $list_location_data[ $v[ 'title' ] ] = $v[ 'id' ];
        }
    }
    vc_map( array(
        "name"                    => __( "ST List Map" , ST_TEXTDOMAIN ) ,
        "base"                    => "st_list_map" ,
        "class"                   => "" ,
        "icon"                    => "icon-st" ,
        "category"                => "Shinetheme" ,
        "content_element"         => true ,
        "show_settings_on_create" => true ,
        "js_view"                 => 'VcColumnView' ,
        "as_parent"               => array(
            'only' => 'st_list_map_field_hotel,st_list_map_field_rental,st_list_map_field_car,st_list_map_field_tour,st_list_map_field_activity,st_list_map_field_range_km' ,
        ) ,
        "params"                  => array(
            array(
                "type"       => "textfield" ,
                "holder"     => "div" ,
                "class"      => "" ,
                "heading"    => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name" => "title" ,
                "value"      => '' ,
            ) ,
            array(
                "type"       => "dropdown" ,
                "holder"     => "div" ,
                "class"      => "" ,
                "heading"    => __( "Type" , ST_TEXTDOMAIN ) ,
                "param_name" => "type" ,
                "value"      => array(
                    __( "Normal" , ST_TEXTDOMAIN )      => 'normal' ,
                    __( "Use for Search Result Page" , ST_TEXTDOMAIN ) => 'page_search'
                ) ,
            ) ,
            array(
                "type"        => "st_post_type_location" ,
                "holder"      => "div" ,
                "heading"     => __( "Select Location" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_list_location" ,
                "description" => "" ,
                /*"value"       => $list_location_data ,*/
                "dependency"  =>
                    array(
                        "element" => "type" ,
                        "value"   => "normal"
                    ) ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "heading"          => __( "Post Type" , ST_TEXTDOMAIN ) ,
                "param_name"       => "st_type" ,
                "description"      => "" ,
                'value'            => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'Hotel' , ST_TEXTDOMAIN )      => 'st_hotel' ,
                    __( 'Car' , ST_TEXTDOMAIN )        => 'st_cars' ,
                    __( 'Tour' , ST_TEXTDOMAIN )       => 'st_tours' ,
                    __( 'Rental' , ST_TEXTDOMAIN )     => 'st_rental' ,
                    __( 'Activities' , ST_TEXTDOMAIN ) => 'st_activity' ,
                    // __( 'All Post Type' , ST_TEXTDOMAIN ) => 'st_hotel,st_cars,st_tours,st_rental,st_activity' ,
                ) ,
                'edit_field_class' => 'vc_col-sm-6' ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "heading"          => __( "Show Search Box" , ST_TEXTDOMAIN ) ,
                "param_name"       => "show_search_box" ,
                "description"      => "" ,
                'value'            => array(
                    __( 'Yes' , ST_TEXTDOMAIN ) => 'yes' ,
                    __( 'No' , ST_TEXTDOMAIN )  => 'no' ,
                ) ,
                'edit_field_class' => 'vc_col-sm-6' ,
            ) ,
            array(
                "type"             => "textfield" ,
                "holder"           => "div" ,
                "class"            => "" ,
                "heading"          => __( "Number" , ST_TEXTDOMAIN ) ,
                "param_name"       => "number" ,
                "value"            => 12 ,
                'edit_field_class' => 'vc_col-sm-3' ,
            ) ,
            array(
                "type"             => "textfield" ,
                "holder"           => "div" ,
                "class"            => "" ,
                "heading"          => __( "Zoom" , ST_TEXTDOMAIN ) ,
                "param_name"       => "zoom" ,
                "value"            => 13 ,
                'edit_field_class' => 'vc_col-sm-3' ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "class"            => "" ,
                "heading"          => __( "Fit Bounds" , ST_TEXTDOMAIN ) ,
                "param_name"       => "fit_bounds" ,
                "description"      => "on|off" ,
                'value'            => array(
                    __( 'Off' , ST_TEXTDOMAIN ) => 'off' ,
                    __( 'On' , ST_TEXTDOMAIN )  => 'on' ,
                ) ,
                'edit_field_class' => 'vc_col-sm-3' ,
            ) ,
            array(
                "type"             => "textfield" ,
                "holder"           => "div" ,
                "class"            => "" ,
                "heading"          => __( "Map Height" , ST_TEXTDOMAIN ) ,
                "param_name"       => "height" ,
                "description"      => "pixels" ,
                "value"            => 500 ,
                'edit_field_class' => 'vc_col-sm-3' ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "heading"          => __( "Style Map" , ST_TEXTDOMAIN ) ,
                "param_name"       => "style_map" ,
                "description"      => "" ,
                'value'            => array(
                    __( '--Select--' , ST_TEXTDOMAIN )  => '' ,
                    __( 'Normal' , ST_TEXTDOMAIN )      => 'normal' ,
                    __( 'Midnight' , ST_TEXTDOMAIN )    => 'midnight' ,
                    __( 'Family Fest' , ST_TEXTDOMAIN ) => 'family_fest' ,
                    __( 'Open Dark' , ST_TEXTDOMAIN )   => 'open_dark' ,
                    __( 'Riverside' , ST_TEXTDOMAIN )   => 'riverside' ,
                    __( 'Ozan' , ST_TEXTDOMAIN )        => 'ozan' ,
                ) ,
                'edit_field_class' => 'vc_col-sm-12 clear' ,
            ) ,
        )
    ) );

    /*
     * HOTEL
     * */
    vc_map( array(
        "name"            => __( "ST Search Field Hotel" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_map_field_hotel" ,
        "content_element" => true ,
        "admin_label"     => true ,
        "as_child"        => array( 'only' => 'st_list_map' ) ,
        "icon"            => "icon-st" ,
        "params"          => array(
            array(
                "type"        => "textfield" ,
                "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_title" ,
                "description" => __( "Title field" , ST_TEXTDOMAIN ) ,
            ) ,
            array(
                "type"       => "textfield" ,
                "holder"     => "div" ,
                "class"      => "" ,
                "heading"    => __( "Placeholder" , ST_TEXTDOMAIN ) ,
                "param_name" => "placeholder" ,
                "value"      => '' ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Select field" ,
                "param_name"  => "st_select_field" ,
                "description" => __( "Select field" , ST_TEXTDOMAIN ) ,
                "value"       => TravelHelper::st_get_field_search( "st_hotel" ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Select taxonomy" ,
                "param_name"  => "st_select_taxonomy" ,
                "description" => __( "Select taxonomy" , ST_TEXTDOMAIN ) ,
                "value"       => st_get_post_taxonomy( 'st_hotel' , false ) ,
                'dependency'  => array(
                    'element' => 'st_select_field' ,
                    'value'   => array( 'taxonomy' )
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Advance fields" ,
                "param_name"  => "st_advance_field" ,
                "description" => __( "Advance fields" , ST_TEXTDOMAIN ) ,
                "value"       =>  array(
                    __("No",ST_TEXTDOMAIN)=>"no",
                    __("Yes",ST_TEXTDOMAIN)=>"yes",
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Field required" ,
                "param_name"  => "is_required" ,
                "description" => __( "Field required" , ST_TEXTDOMAIN ) ,
                "value"       =>  array(
                    __("No",ST_TEXTDOMAIN)=>"off",
                    __("Yes",ST_TEXTDOMAIN)=>"on",
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => __( "Size Col" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_col" ,
                "description" => __( "Size Col" , ST_TEXTDOMAIN ) ,
                'value'       => array(
                    __( "1 column" , ST_TEXTDOMAIN )   => 'col-md-1' ,
                    __( "2 columns" , ST_TEXTDOMAIN )  => 'col-md-2' ,
                    __( "3 columns" , ST_TEXTDOMAIN )  => 'col-md-3' ,
                    __( "4 columns" , ST_TEXTDOMAIN )  => 'col-md-4' ,
                    __( "5 columns" , ST_TEXTDOMAIN )  => 'col-md-5' ,
                    __( "6 columns" , ST_TEXTDOMAIN )  => 'col-md-6' ,
                    __( "7 columns" , ST_TEXTDOMAIN )  => 'col-md-7' ,
                    __( "8 columns" , ST_TEXTDOMAIN )  => 'col-md-8' ,
                    __( "9 columns" , ST_TEXTDOMAIN )  => 'col-md-9' ,
                    __( "10 columns" , ST_TEXTDOMAIN ) => 'col-md-10' ,
                    __( "11 columns" , ST_TEXTDOMAIN ) => 'col-md-11' ,
                    __( "12 columns" , ST_TEXTDOMAIN ) => 'col-md-12' ,
                ) ,
            ) ,
        )
    ) );
    /*
     * RENTAL
     * */
    vc_map( array(
        "name"            => __( "ST Search Field Rental" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_map_field_rental" ,
        "content_element" => true ,
        "as_child"        => array( 'only' => 'st_list_map' ) ,
        "icon"            => "icon-st" ,
        "params"          => array(
            array(
                "type"        => "textfield" ,
                "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_title" ,
                "description" => __( "Title field" , ST_TEXTDOMAIN ) ,
            ) ,
            array(
                "type"       => "textfield" ,
                "holder"     => "div" ,
                "class"      => "" ,
                "heading"    => __( "Placeholder" , ST_TEXTDOMAIN ) ,
                "param_name" => "placeholder" ,
                "value"      => '' ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Select field" ,
                "param_name"  => "st_select_field" ,
                "description" => __( "Select field" , ST_TEXTDOMAIN ) ,
                "value"       => TravelHelper::st_get_field_search( "st_rental" ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Select taxonomy" ,
                "param_name"  => "st_select_taxonomy" ,
                "description" => __( "Select taxonomy" , ST_TEXTDOMAIN ) ,
                "value"       => st_get_post_taxonomy( 'st_rental' , false ) ,
                'dependency'  => array(
                    'element' => 'st_select_field' ,
                    'value'   => array( 'taxonomy' )
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Advance fields" ,
                "param_name"  => "st_advance_field" ,
                "description" => __( "Advance fields" , ST_TEXTDOMAIN ) ,
                "value"       =>  array(
                    __("No",ST_TEXTDOMAIN)=>"no",
                    __("Yes",ST_TEXTDOMAIN)=>"yes",
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Field required" ,
                "param_name"  => "is_required" ,
                "description" => __( "Field required" , ST_TEXTDOMAIN ) ,
                "value"       =>  array(
                    __("No",ST_TEXTDOMAIN)=>"off",
                    __("Yes",ST_TEXTDOMAIN)=>"on",
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => __( "Size Col" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_col" ,
                "description" => __( "Size Col" , ST_TEXTDOMAIN ) ,
                'value'       => array(
                    __( "1 column" , ST_TEXTDOMAIN )   => 'col-md-1' ,
                    __( "2 columns" , ST_TEXTDOMAIN )  => 'col-md-2' ,
                    __( "3 columns" , ST_TEXTDOMAIN )  => 'col-md-3' ,
                    __( "4 columns" , ST_TEXTDOMAIN )  => 'col-md-4' ,
                    __( "5 columns" , ST_TEXTDOMAIN )  => 'col-md-5' ,
                    __( "6 columns" , ST_TEXTDOMAIN )  => 'col-md-6' ,
                    __( "7 columns" , ST_TEXTDOMAIN )  => 'col-md-7' ,
                    __( "8 columns" , ST_TEXTDOMAIN )  => 'col-md-8' ,
                    __( "9 columns" , ST_TEXTDOMAIN )  => 'col-md-9' ,
                    __( "10 columns" , ST_TEXTDOMAIN ) => 'col-md-10' ,
                    __( "11 columns" , ST_TEXTDOMAIN ) => 'col-md-11' ,
                    __( "12 columns" , ST_TEXTDOMAIN ) => 'col-md-12' ,
                ) ,
            ) ,
        )
    ) );
    /*
    * CAR
    * */
    vc_map( array(
        "name"            => __( "ST Search Field Car" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_map_field_car" ,
        "content_element" => true ,
        "as_child"        => array( 'only' => 'st_list_map' ) ,
        "icon"            => "icon-st" ,
        "params"          => array(
            array(
                "type"        => "textfield" ,
                "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_title" ,
                "description" => __( "Title field" , ST_TEXTDOMAIN ) ,
            ) ,
            array(
                "type"       => "textfield" ,
                "holder"     => "div" ,
                "class"      => "" ,
                "heading"    => __( "Placeholder" , ST_TEXTDOMAIN ) ,
                "param_name" => "placeholder" ,
                "value"      => '' ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Select field" ,
                "param_name"  => "st_select_field" ,
                "description" => __( "Select field" , ST_TEXTDOMAIN ) ,
                "value"       => TravelHelper::st_get_field_search( "st_cars" ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Select taxonomy" ,
                "param_name"  => "st_select_taxonomy" ,
                "description" => __( "Select taxonomy" , ST_TEXTDOMAIN ) ,
                "value"       => st_get_post_taxonomy( 'st_cars' , false ) ,
                'dependency'  => array(
                    'element' => 'st_select_field' ,
                    'value'   => array( 'taxonomy' )
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Advance fields" ,
                "param_name"  => "st_advance_field" ,
                "description" => __( "Advance fields" , ST_TEXTDOMAIN ) ,
                "value"       =>  array(
                    __("No",ST_TEXTDOMAIN)=>"no",
                    __("Yes",ST_TEXTDOMAIN)=>"yes",
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Field required" ,
                "param_name"  => "is_required" ,
                "description" => __( "Field required" , ST_TEXTDOMAIN ) ,
                "value"       =>  array(
                    __("No",ST_TEXTDOMAIN)=>"off",
                    __("Yes",ST_TEXTDOMAIN)=>"on",
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => __( "Size Col" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_col" ,
                "description" => __( "Size Col" , ST_TEXTDOMAIN ) ,
                'value'       => array(
                    __( "1 column" , ST_TEXTDOMAIN )   => 'col-md-1' ,
                    __( "2 columns" , ST_TEXTDOMAIN )  => 'col-md-2' ,
                    __( "3 columns" , ST_TEXTDOMAIN )  => 'col-md-3' ,
                    __( "4 columns" , ST_TEXTDOMAIN )  => 'col-md-4' ,
                    __( "5 columns" , ST_TEXTDOMAIN )  => 'col-md-5' ,
                    __( "6 columns" , ST_TEXTDOMAIN )  => 'col-md-6' ,
                    __( "7 columns" , ST_TEXTDOMAIN )  => 'col-md-7' ,
                    __( "8 columns" , ST_TEXTDOMAIN )  => 'col-md-8' ,
                    __( "9 columns" , ST_TEXTDOMAIN )  => 'col-md-9' ,
                    __( "10 columns" , ST_TEXTDOMAIN ) => 'col-md-10' ,
                    __( "11 columns" , ST_TEXTDOMAIN ) => 'col-md-11' ,
                    __( "12 columns" , ST_TEXTDOMAIN ) => 'col-md-12' ,
                ) ,
            ) ,

        )
    ) );
    /*
    * TOUR
    * */
    vc_map( array(
        "name"            => __( "ST Search Field Tour" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_map_field_tour" ,
        "content_element" => true ,
        "as_child"        => array( 'only' => 'st_list_map' ) ,
        "icon"            => "icon-st" ,
        "params"          => array(
            array(
                "type"        => "textfield" ,
                "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_title" ,
                "description" => __( "Title field" , ST_TEXTDOMAIN ) ,
            ) ,
            array(
                "type"       => "textfield" ,
                "holder"     => "div" ,
                "class"      => "" ,
                "heading"    => __( "Placeholder" , ST_TEXTDOMAIN ) ,
                "param_name" => "placeholder" ,
                "value"      => '' ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Select field" ,
                "param_name"  => "st_select_field" ,
                "description" => __( "Select field" , ST_TEXTDOMAIN ) ,
                "value"       => TravelHelper::st_get_field_search( "st_tours" ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Select taxonomy" ,
                "param_name"  => "st_select_taxonomy" ,
                "description" => __( "Select taxonomy" , ST_TEXTDOMAIN ) ,
                "value"       => st_get_post_taxonomy( 'st_tours' , false ) ,
                'dependency'  => array(
                    'element' => 'st_select_field' ,
                    'value'   => array( 'taxonomy' )
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Advance fields" ,
                "param_name"  => "st_advance_field" ,
                "description" => __( "Advance fields" , ST_TEXTDOMAIN ) ,
                "value"       =>  array(
                    __("No",ST_TEXTDOMAIN)=>"no",
                    __("Yes",ST_TEXTDOMAIN)=>"yes",
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Field required" ,
                "param_name"  => "is_required" ,
                "description" => __( "Field required" , ST_TEXTDOMAIN ) ,
                "value"       =>  array(
                    __("No",ST_TEXTDOMAIN)=>"off",
                    __("Yes",ST_TEXTDOMAIN)=>"on",
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => __( "Size Col" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_col" ,
                "description" => __( "Size Col" , ST_TEXTDOMAIN ) ,
                'value'       => array(
                    __( "1 column" , ST_TEXTDOMAIN )   => 'col-md-1' ,
                    __( "2 columns" , ST_TEXTDOMAIN )  => 'col-md-2' ,
                    __( "3 columns" , ST_TEXTDOMAIN )  => 'col-md-3' ,
                    __( "4 columns" , ST_TEXTDOMAIN )  => 'col-md-4' ,
                    __( "5 columns" , ST_TEXTDOMAIN )  => 'col-md-5' ,
                    __( "6 columns" , ST_TEXTDOMAIN )  => 'col-md-6' ,
                    __( "7 columns" , ST_TEXTDOMAIN )  => 'col-md-7' ,
                    __( "8 columns" , ST_TEXTDOMAIN )  => 'col-md-8' ,
                    __( "9 columns" , ST_TEXTDOMAIN )  => 'col-md-9' ,
                    __( "10 columns" , ST_TEXTDOMAIN ) => 'col-md-10' ,
                    __( "11 columns" , ST_TEXTDOMAIN ) => 'col-md-11' ,
                    __( "12 columns" , ST_TEXTDOMAIN ) => 'col-md-12' ,
                ) ,
            ) ,

        )
    ) );
    /*
    * ACTIVITY
    * */
    vc_map( array(
        "name"            => __( "ST Search Field Activity" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_map_field_activity" ,
        "content_element" => true ,
        "as_child"        => array( 'only' => 'st_list_map' ) ,
        "icon"            => "icon-st" ,
        "params"          => array(
            array(
                "type"        => "textfield" ,
                "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_title" ,
                "description" => __( "Title field" , ST_TEXTDOMAIN ) ,
            ) ,
            array(
                "type"       => "textfield" ,
                "holder"     => "div" ,
                "class"      => "" ,
                "heading"    => __( "Placeholder" , ST_TEXTDOMAIN ) ,
                "param_name" => "placeholder" ,
                "value"      => '' ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Select field" ,
                "param_name"  => "st_select_field" ,
                "description" => __( "Select field" , ST_TEXTDOMAIN ) ,
                "value"       => TravelHelper::st_get_field_search( "st_activity" ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Select taxonomy" ,
                "param_name"  => "st_select_taxonomy" ,
                "description" => __( "Select taxonomy" , ST_TEXTDOMAIN ) ,
                "value"       => st_get_post_taxonomy( 'st_activity' , false ) ,
                'dependency'  => array(
                    'element' => 'st_select_field' ,
                    'value'   => array( 'taxonomy' )
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Advance fields" ,
                "param_name"  => "st_advance_field" ,
                "description" => __( "Advance fields" , ST_TEXTDOMAIN ) ,
                "value"       =>  array(
                    __("No",ST_TEXTDOMAIN)=>"no",
                    __("Yes",ST_TEXTDOMAIN)=>"yes",
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Field required" ,
                "param_name"  => "is_required" ,
                "description" => __( "Field required" , ST_TEXTDOMAIN ) ,
                "value"       =>  array(
                    __("No",ST_TEXTDOMAIN)=>"off",
                    __("Yes",ST_TEXTDOMAIN)=>"on",
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => __( "Size Col" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_col" ,
                "description" => __( "Size Col" , ST_TEXTDOMAIN ) ,
                'value'       => array(
                    __( "1 column" , ST_TEXTDOMAIN )   => 'col-md-1' ,
                    __( "2 columns" , ST_TEXTDOMAIN )  => 'col-md-2' ,
                    __( "3 columns" , ST_TEXTDOMAIN )  => 'col-md-3' ,
                    __( "4 columns" , ST_TEXTDOMAIN )  => 'col-md-4' ,
                    __( "5 columns" , ST_TEXTDOMAIN )  => 'col-md-5' ,
                    __( "6 columns" , ST_TEXTDOMAIN )  => 'col-md-6' ,
                    __( "7 columns" , ST_TEXTDOMAIN )  => 'col-md-7' ,
                    __( "8 columns" , ST_TEXTDOMAIN )  => 'col-md-8' ,
                    __( "9 columns" , ST_TEXTDOMAIN )  => 'col-md-9' ,
                    __( "10 columns" , ST_TEXTDOMAIN ) => 'col-md-10' ,
                    __( "11 columns" , ST_TEXTDOMAIN ) => 'col-md-11' ,
                    __( "12 columns" , ST_TEXTDOMAIN ) => 'col-md-12' ,
                ) ,
            ) ,

        )
    ) );
    /*
     * Range KM
     * */
    vc_map( array(
        "name"            => __( "ST Search Field Range Kilometers" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_map_field_range_km" ,
        "content_element" => true ,
        "admin_label"     => true ,
        "as_child"        => array( 'only' => 'st_list_map' ) ,
        "icon"            => "icon-st" ,
        "params"          => array(
            array(
                "type"        => "textfield" ,
                "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_title" ,
                "description" => __( "Title field" , ST_TEXTDOMAIN ) ,
            ) ,
            array(
                "type"        => "textfield" ,
                "heading"     => __( "Max Range Kilometers" , ST_TEXTDOMAIN ) ,
                "param_name"  => "max_range_km" ,
                "description" => __( "Kilometer" , ST_TEXTDOMAIN ) ,
                "value"       => 20 ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => "Advance fields" ,
                "param_name"  => "st_advance_field" ,
                "description" => __( "Advance fields" , ST_TEXTDOMAIN ) ,
                "value"       =>  array(
                    __("No",ST_TEXTDOMAIN)=>"no",
                    __("Yes",ST_TEXTDOMAIN)=>"yes",
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "heading"     => __( "Size Col" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_col" ,
                "description" => __( "Size Col" , ST_TEXTDOMAIN ) ,
                'value'       => array(
                    __( "1 column" , ST_TEXTDOMAIN )   => 'col-md-1' ,
                    __( "2 columns" , ST_TEXTDOMAIN )  => 'col-md-2' ,
                    __( "3 columns" , ST_TEXTDOMAIN )  => 'col-md-3' ,
                    __( "4 columns" , ST_TEXTDOMAIN )  => 'col-md-4' ,
                    __( "5 columns" , ST_TEXTDOMAIN )  => 'col-md-5' ,
                    __( "6 columns" , ST_TEXTDOMAIN )  => 'col-md-6' ,
                    __( "7 columns" , ST_TEXTDOMAIN )  => 'col-md-7' ,
                    __( "8 columns" , ST_TEXTDOMAIN )  => 'col-md-8' ,
                    __( "9 columns" , ST_TEXTDOMAIN )  => 'col-md-9' ,
                    __( "10 columns" , ST_TEXTDOMAIN ) => 'col-md-10' ,
                    __( "11 columns" , ST_TEXTDOMAIN ) => 'col-md-11' ,
                    __( "12 columns" , ST_TEXTDOMAIN ) => 'col-md-12' ,
                ) ,
            ) ,
        )
    ) );

}


if(class_exists( 'WPBakeryShortCodesContainer' ) and !class_exists( 'WPBakeryShortCode_st_list_map' )) {
    class WPBakeryShortCode_st_list_map extends WPBakeryShortCodesContainer
    {
        protected function content( $attr , $content = null )
        {
            global $st_form_search_advance_field;
            $data = shortcode_atts(
                array(
                    'title'              => '' ,
                    'type'               => 'normal' ,
                    'st_list_location'   => '' ,
                    'st_type'            => 'st_hotel' ,
                    'zoom'               => '13' ,
                    'height'             => '500' ,
                    'number'             => '12' ,
                    'fit_bounds'         => 'no' ,
                    'style_map'          => 'normal' ,
                    'custom_code_style'  => '' ,
                    'show_search_box'    => 'yes' ,
                    'show_data_list_map' => 'yes' ,
                ) , $attr , 'st_list_map' );
            extract( $data );
            $data_map       = array();
            $form_search    = st_remove_wpautop( $content );
            $form_search_advance    = $st_form_search_advance_field;
            $html           = '';
            $map_lat_center = 0;
            $map_lng_center = 0;
            if($type == "normal") {
                global $wp_query;
                global $st_search_args;
                $data['location_id'] = $data['st_list_location'];
                $st_search_args=$data;

                $ids = $st_list_location;
                if( count(explode(',',$st_list_location)) > 1) {
                    $ids = explode( ',' , $st_list_location );
                    $ids = $ids[0];
                }
                $map_lat         = get_post_meta( $ids , 'map_lat' , true );
                $map_lng         = get_post_meta( $ids , 'map_lng' , true );
                $location_center = '[' . $map_lat . ',' . $map_lng . ']';

                switch($st_type){
                    case"st_hotel":
                        $hotel = STHotel::inst();
                        $hotel->alter_search_query();
                        break;
                    case"st_rental":
                        $rental = STRental::inst();
                        $rental->alter_search_query();
                        break;
                    case"st_cars":
                        st()->car->alter_search_query();
                        break;
                    case"st_tours":
                        st()->tour->alter_search_query();
                        break;
                    case"st_activity":
                        $activity = STActivity::inst();
                        $activity->alter_search_query();
                        break;
                }
                $query           = array(
                    'post_type'      => explode( ',' , $st_type ) ,
                    'posts_per_page' => $number ,
                    'post_status'    => 'publish' ,
                );
                query_posts( $query );
                switch( $st_type ) {
                    case"st_hotel":
                        $hotel->remove_alter_search_query();
                        break;
                    case"st_rental":
                        $rental->remove_alter_search_query();
                        break;
                    case"st_cars":
                        st()->car->remove_alter_search_query();
                        break;
                    case"st_tours":
                        st()->tour->remove_alter_search_query();
                        break;
                    case"st_activity":
                        $activity->remove_alter_search_query();
                        break;
                }
            }
            if($type == "page_search") {
                $location_center = '[0,0]';
                $address_center  = '';
                if(STInput::request( 'pick-up' )) {
                    $ids_location = TravelerObject::_get_location_by_name( STInput::get( 'pick-up' ) );
                    if(!empty( $ids_location )) {
                        $_REQUEST[ 'pick-up' ] = implode( ',' , $ids_location );
                        $map_lat_center        = get_post_meta( $ids_location[ 0 ] , 'map_lat' , true );
                        $map_lng_center        = get_post_meta( $ids_location[ 0 ] , 'map_lng' , true );
                        $location_center       = '[' . $map_lat_center . ',' . $map_lng_center . ']';
                        $address_center        = get_the_title( $ids_location[ 0 ] );
                    }
                }
                if(STInput::request( 'location_id' )) {
                    $map_lat_center  = get_post_meta( STInput::request( 'location_id' ) , 'map_lat' , true );
                    $map_lng_center  = get_post_meta( STInput::request( 'location_id' ) , 'map_lng' , true );
                    $location_center = '[' . $map_lat_center . ',' . $map_lng_center . ']';
                    $address_center  = get_the_title( STInput::request( 'location_id' ) );
                }
                if(STInput::request( 'location_id_pick_up' )) {
                    $map_lat_center  = get_post_meta( STInput::request( 'location_id_pick_up' ) , 'map_lat' , true );
                    $map_lng_center  = get_post_meta( STInput::request( 'location_id_pick_up' ) , 'map_lng' , true );
                    $location_center = '[' . $map_lat_center . ',' . $map_lng_center . ']';
                    $address_center  = get_the_title( STInput::request( 'location_id_pick_up' ) );
                }

                global $wp_query , $st_search_query;
                switch( $st_type ) {
                    case"st_hotel":
                        $hotel = STHotel::inst();
                        $hotel->alter_search_query();
                        break;
                    case"st_rental":
                        $rental = STRental::inst();
                        $rental->alter_search_query();
                        break;
                    case"st_cars":
                        st()->car->alter_search_query();
                        break;
                    case"st_tours":
                        st()->tour->alter_search_query();
                        break;
                    case"st_activity":
                        $activity = STActivity::inst();
                        $activity->alter_search_query();
                        break;
                }

                $query = array(
                    'post_type'      => $st_type ,
                    'posts_per_page' => $number ,
                    'post_status'    => 'publish' ,
                    's'              => '' ,
                );
                query_posts( $query );
            }

            $stt = 0;
            while( have_posts() ) {
                the_post();
                $map_lat = get_post_meta( get_the_ID() , 'map_lat' , true );
                $map_lng = get_post_meta( get_the_ID() , 'map_lng' , true );
                if(!empty( $map_lat ) and !empty( $map_lng ) and is_numeric( $map_lat ) and is_numeric( $map_lng )) {
                    $post_type                       = get_post_type();
                    $data_map[ $stt ][ 'id' ]        = get_the_ID();
                    $data_map[ $stt ][ 'name' ]      = get_the_title();
                    $data_map[ $stt ][ 'post_type' ] = $post_type;
                    $data_map[ $stt ][ 'lat' ]       = $map_lat;
                    $data_map[ $stt ][ 'lng' ]       = $map_lng;
                    $post_type_name                  = get_post_type_object( $post_type );
                    $post_type_name->label;
                    switch( $post_type ) {
                        case"st_hotel";
                            $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_hotel_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_black.png' );
                            $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/hotel' , false , array( 'post_type' => $post_type_name->label ) ) );
                            $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/hotel' , false , array( 'post_type' => $post_type_name->label ) ) );
                            break;
                        case"st_rental";
                            $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_rental_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_brown.png' );
                            $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/rental' , false , array( 'post_type' => $post_type_name->label ) ) );
                            $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/rental' , false , array( 'post_type' => $post_type_name->label ) ) );
                            break;
                        case"st_cars";
                            $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_cars_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_green.png' );
                            $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/car' , false , array( 'post_type' => $post_type_name->label ) ) );
                            $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/car' , false , array( 'post_type' => $post_type_name->label ) ) );
                            break;
                        case"st_tours";
                            $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_tours_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_purple.png' );
                            $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/tour' , false , array( 'post_type' => $post_type_name->label ) ) );
                            $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/tour' , false , array( 'post_type' => $post_type_name->label ) ) );
                            break;
                        case"st_activity";
                            $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_activity_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_yellow.png' );
                            $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/activity' , false , array( 'post_type' => $post_type_name->label ) ) );
                            $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/activity' , false , array( 'post_type' => $post_type_name->label ) ) );
                            break;
                    }
                    $stt++;
                }

            }
            if($type == "page_search") {
                $st_search_query = $wp_query;
                switch( $post_type ) {
                    case"st_hotel":
                        $hotel->remove_alter_search_query();
                        break;
                    case"st_rental":
                        $rental->remove_alter_search_query();
                        break;
                    case"st_cars":
                        st()->car->remove_alter_search_query();
                        break;
                    case"st_tours":
                        st()->tour->remove_alter_search_query();
                        break;
                    case"st_activity":
                        $activity->remove_alter_search_query();
                        break;
                }
            }

            wp_reset_query();
            if(empty( $location_center ) or $location_center == '[,]')
                $location_center = '[0,0]';
            $data_tmp               = array(
                'location_center'    => $location_center ,
                'zoom'               => $zoom ,
                'data_map'           => $data_map ,
                'height'             => $height ,
                'style_map'          => $style_map ,
                'st_type'            => $st_type ,
                'number'             => $number ,
                'fit_bounds'         => $fit_bounds ,
                'title'              => $title ,
                'show_search_box'    => $show_search_box ,
                'show_data_list_map' => $show_data_list_map ,
                'form_search'        => $form_search ,
                'form_search_advance'        => $form_search_advance ,
            );
            $data_tmp[ 'data_tmp' ] = $data_tmp;
            $html                   = st()->load_template( 'vc-elements/st-list-map/html' , '' , $data_tmp );

            return $html;
        }
    }
}
if(class_exists( 'WPBakeryShortCode' ) and !class_exists( 'WPBakeryShortCode_st_list_map_field_hotel' )) {
    class WPBakeryShortCode_st_list_map_field_hotel extends WPBakeryShortCode
    {
        protected function content( $arg , $content = null )
        {
            global $st_form_search_advance_field;
            $data = shortcode_atts( array(
                'st_title'           => '' ,
                'placeholder'           => '' ,
                'st_col'             => "col-md-1" ,
                'st_select_field'    => "location" ,
                'st_advance_field' => "no" ,
                'st_select_taxonomy' => "" ,
                'is_required' => "off" ,
            ) , $arg , 'st_list_map_field_hotel' );
            extract( $data );
            $default = array(
                'title'    => $st_title ,
                'taxonomy' => $st_select_taxonomy,
                'placeholder' => $placeholder,
                'is_required' => $is_required,
            );
            $text = "";
            if($st_advance_field == 'no'){
                $text    = '<div class="' . $st_col . '">' . st()->load_template( 'hotel/elements/search/field_' . $st_select_field , false , array( 'data'       => $default , 'field_size' => 'md' ) ) . '</div>';
            }else{
                $st_form_search_advance_field .= '<div class="' . $st_col . '">' . st()->load_template( 'hotel/elements/search/field_' . $st_select_field , false , array( 'data'       => $default , 'field_size' => 'md' ) ) . '</div>';
            }
            return $text;
        }
    }
}
if(class_exists( 'WPBakeryShortCode' ) and !class_exists( 'WPBakeryShortCode_st_list_map_field_rental' )) {
    class WPBakeryShortCode_st_list_map_field_rental extends WPBakeryShortCode
    {
        protected function content( $arg , $content = null )
        {
            global $st_form_search_advance_field;
            $data = shortcode_atts( array(
                'st_title'           => '' ,
                'placeholder'           => '' ,
                'st_col'             => "col-md-1" ,
                'st_select_field'    => "location" ,
                'st_advance_field' => "no" ,
                'st_select_taxonomy' => "" ,
                'is_required' => "off" ,
            ) , $arg , 'st_list_map_field_rental' );
            extract( $data );

            $default = array(
                'title'    => $st_title ,
                'taxonomy' => $st_select_taxonomy,
                'placeholder' => $placeholder,
                'is_required' => $is_required,
            );
            $text = "";
            if($st_advance_field == 'no'){
                $text    = '<div class="' . $st_col . '">' . st()->load_template( 'hotel/elements/search/field_' . $st_select_field , false , array( 'data'       => $default , 'field_size' => 'md' ) ) . '</div>';
            }else{
                $st_form_search_advance_field .= '<div class="' . $st_col . '">' . st()->load_template( 'hotel/elements/search/field_' . $st_select_field , false , array( 'data'       => $default , 'field_size' => 'md' ) ) . '</div>';
            }
            return $text;
        }
    }
}
if(class_exists( 'WPBakeryShortCode' ) and !class_exists( 'WPBakeryShortCode_st_list_map_field_car' )) {
    class WPBakeryShortCode_st_list_map_field_car extends WPBakeryShortCode
    {
        protected function content( $arg , $content = null )
        {
            global $st_form_search_advance_field;
            $data = shortcode_atts( array(
                'st_title'           => '' ,
                'placeholder'           => '' ,
                'st_col'             => "col-md-1" ,
                'st_select_field'    => "location" ,
                'st_advance_field' => "no" ,
                'st_select_taxonomy' => "" ,
                'is_required' => "off" ,
            ) , $arg , 'st_list_map_field_car' );
            extract( $data );

            $default = array(
                'title'    => $st_title ,
                'taxonomy' => $st_select_taxonomy,
                'placeholder' => $placeholder,
                'is_required' => $is_required,
            );

            $text = "";
            if($st_advance_field == 'no'){
                $text    = '<div class="' . $st_col . '">' . st()->load_template( 'cars/elements/search/field-' . $st_select_field , false , array( 'data'       => $default , 'field_size' => 'md' ) ) . '</div>';
            }else{
                $st_form_search_advance_field .= '<div class="' . $st_col . '">' . st()->load_template( 'cars/elements/search/field-' . $st_select_field , false , array( 'data'       => $default , 'field_size' => 'md' ) ) . '</div>';
            }
            return $text;
        }
    }
}
if(class_exists( 'WPBakeryShortCode' ) and !class_exists( 'WPBakeryShortCode_st_list_map_field_tour' )) {
    class WPBakeryShortCode_st_list_map_field_tour extends WPBakeryShortCode
    {
        protected function content( $arg , $content = null )
        {
            global $st_form_search_advance_field;
            $data = shortcode_atts( array(
                'st_title'           => '' ,
                'placeholder'           => '' ,
                'st_col'             => "col-md-1" ,
                'st_select_field'    => "location" ,
                'st_advance_field' => "no" ,
                'st_select_taxonomy' => "" ,
                'is_required' => "off" ,
            ) , $arg , 'st_list_map_field_tour' );
            extract( $data );

            $default = array(
                'title'    => $st_title ,
                'taxonomy' => $st_select_taxonomy,
                'placeholder' => $placeholder,
                'is_required' => $is_required,
            );

            $text = "";
            if($st_advance_field == 'no'){
                $text    = '<div class="' . $st_col . '">' . st()->load_template( 'tours/elements/search/field-' . $st_select_field , false , array( 'data'       => $default , 'field_size' => 'md' ) ) . '</div>';
            }else{
                $st_form_search_advance_field .= '<div class="' . $st_col . '">' . st()->load_template( 'tours/elements/search/field-' . $st_select_field , false , array( 'data'       => $default , 'field_size' => 'md' ) ) . '</div>';
            }
            return $text;
        }
    }
}
if(class_exists( 'WPBakeryShortCode' ) and !class_exists( 'WPBakeryShortCode_st_list_map_field_activity' )) {
    class WPBakeryShortCode_st_list_map_field_activity extends WPBakeryShortCode
    {
        protected function content( $arg , $content = null )
        {
            global $st_form_search_advance_field;
            $data = shortcode_atts( array(
                'st_title'           => '' ,
                'placeholder'           => '' ,
                'st_col'             => "col-md-1" ,
                'st_select_field'    => "location" ,
                'st_advance_field' => "no" ,
                'st_select_taxonomy' => "" ,
                'is_required' => "off" ,
            ) , $arg , 'st_list_map_field_activity' );
            extract( $data );

            $default = array(
                'title'    => $st_title ,
                'taxonomy' => $st_select_taxonomy,
                'placeholder' => $placeholder,
                'is_required' => $is_required
            );

            $text = "";
            if($st_advance_field == 'no'){
                $text    = '<div class="' . $st_col . '">' . st()->load_template( 'activity/elements/search/field-' . $st_select_field , false , array( 'data'       => $default , 'field_size' => 'md' ) ) . '</div>';
            }else{
                $st_form_search_advance_field  .= '<div class="' . $st_col . '">' . st()->load_template( 'activity/elements/search/field-' . $st_select_field , false , array( 'data'       => $default , 'field_size' => 'md' ) ) . '</div>';
            }
            return $text;
        }
    }
}
if(class_exists( 'WPBakeryShortCode' ) and !class_exists( 'WPBakeryShortCode_st_list_map_field_range_km' )) {
    class WPBakeryShortCode_st_list_map_field_range_km extends WPBakeryShortCode
    {
        protected function content( $arg , $content = null )
        {
            global $st_form_search_advance_field;
            $data = shortcode_atts( array(
                'st_title'     => '' ,
                'st_col'       => "col-md-1" ,
                'max_range_km' => 20 ,
                'st_advance_field' => "no" ,
            ) , $arg , 'st_list_map_field_range_km' );
            extract( $data );
            $data_min_max[ "min" ] = 0;
            $data_min_max[ "max" ] = $max_range_km;

            $text = "";
            if($st_advance_field == 'no'){
                $text    = '
                 <div class="' . $st_col . '">
                    <div class="form-group form-group-md ">
                         <label>' . $st_title . '</label>
                         <input type="text" name="range" value="' . STInput::get( 'range' ) . '" class="range-slider" data-symbol="' . TravelHelper::get_current_currency( 'symbol' ) . '" data-min="' . $data_min_max[ 'min' ] . '" data-max="' . $data_min_max[ 'max' ] . '" data-step="1">
                    </div>
                 </div>';
            }else{
                $st_form_search_advance_field  .= '
                 <div class="' . $st_col . '">
                    <div class="form-group form-group-md ">
                         <label>' . $st_title . '</label>
                         <input type="text" name="range" value="' . STInput::get( 'range' ) . '" class="range-slider" data-symbol="' . TravelHelper::get_current_currency( 'symbol' ) . '" data-min="' . $data_min_max[ 'min' ] . '" data-max="' . $data_min_max[ 'max' ] . '" data-step="1">
                    </div>
                 </div>';
            }
            return $text;

        }
    }
}

if(!class_exists( 'st_list_map' )) {
    class st_list_map
    {
        static function _get_query_where( $where )
        {
            $post_type     = $_SESSION[ 'el_st_type' ];
            $location_id = $_SESSION[ 'el_location_id' ];
            if(!TravelHelper::checkTableDuplicate( $post_type ))
                return $where;

            if(!empty( $location_id ) ) {
                $where = TravelHelper::_st_get_where_location($location_id,array($post_type),$where);
            }
            return $where;

            /*$location_field = 'id_location';
            if($st_type == 'st_rental')
                $location_field = 'location_id';

            if(is_array( $location_id )) {
                $where .= " AND (";
                foreach( $location_id as $k => $v ) {
                    $list = TravelHelper::getLocationByParent( $v );
                    $list[] = $v;
                    if(is_array( $list ) && count( $list )) {
                        if($k == 0)
                            $where .= "  ("; else $where .= " OR (";;
                        $where_tmp = "";
                        foreach( $list as $item ) {
                            if(empty( $where_tmp )) {
                                $where_tmp .= "tb.multi_location LIKE '%_{$item}_%'";
                            } else {
                                $where_tmp .= " OR tb.multi_location LIKE '%_{$item}_%'";
                            }
                        }
                        $list = implode( ',' , $list );
                        $where_tmp .= " OR tb.{$location_field} IN ({$list})";
                        $where .= $where_tmp . ")";
                    } else {
                        $where .= " AND (tb.multi_location LIKE '%_{$location_id}_%' OR tb.{$location_field} IN ('{$location_id}')) ";
                    }
                }
                $where .= " )";
            } else {
                $list = TravelHelper::getLocationByParent( $location_id );
                $list[] = $location_id;
                if(is_array( $list ) && count( $list )) {
                    $where .= " AND (";
                    $where_tmp = "";
                    foreach( $list as $item ) {
                        if(empty( $where_tmp )) {
                            $where_tmp .= "tb.multi_location LIKE '%_{$item}_%'";
                        } else {
                            $where_tmp .= " OR tb.multi_location LIKE '%_{$item}_%'";
                        }
                    }
                    $list = implode( ',' , $list );
                    $where_tmp .= " OR tb.{$location_field} IN ({$list})";
                    $where .= $where_tmp . ")";
                } else {
                    $where .= " AND (tb.multi_location LIKE '%_{$location_id}_%' OR tb.{$location_field} IN ('{$location_id}')) ";
                }
            }
            return $where;*/
        }

        static function _get_query_join( $join )
        {
            $st_type = $_SESSION[ 'el_st_type' ];
            if(!TravelHelper::checkTableDuplicate( $st_type ))
                return $join;
            global $wpdb;

            $table = $wpdb->prefix . $st_type;

            $join .= " INNER JOIN {$table} as tb ON {$wpdb->prefix}posts.ID = tb.post_id";
            return $join;
        }
    }
}