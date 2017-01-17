<?php
/**
 *
 *
 * @since 1.1.5
 * */
if(function_exists( 'vc_map' )) {
    $list_location = TravelerObject::get_list_location();
    $list_location_data[ __( '-- Select --' , ST_TEXTDOMAIN ) ] = '';
    if(!empty( $list_location )) {
        foreach( $list_location as $k => $v ) {
            $list_location_data[ $v[ 'title' ] ] = $v[ 'id' ];
        }
    }
    vc_map( array(
        "name"     => __( "ST List Map New" , ST_TEXTDOMAIN ) ,
        "base"     => "st_list_map_new" ,
        "class"    => "" ,
        "icon"     => "icon-st" ,
        "category" => "Shinetheme" ,
        "params"   => array(
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Select Location" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_list_location" ,
                "description" => "" ,
                "value" => $list_location_data
            ) ,
            array(
                "type"        => "checkbox" ,
                "holder"      => "div" ,
                "heading"     => __( "Type" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_type" ,
                "description" => "" ,
                'value'       => array(
                    __( 'All Post Type' , ST_TEXTDOMAIN ) => 'st_hotel,st_cars,st_tours,st_rental,st_activity' ,
                    __( 'Hotel' , ST_TEXTDOMAIN ) => 'st_hotel' ,
                    __( 'Car' , ST_TEXTDOMAIN )        => 'st_cars' ,
                    __( 'Tour' , ST_TEXTDOMAIN )       => 'st_tours' ,
                    __( 'Rental' , ST_TEXTDOMAIN )     => 'st_rental' ,
                    __( 'Activities' , ST_TEXTDOMAIN ) => 'st_activity' ,
                ) ,
                'edit_field_class'=>'vc_col-sm-12',
            ) ,
            array(
                "type"       => "textfield" ,
                "holder"     => "div" ,
                "class"      => "" ,
                "heading"    => __( "Number" , ST_TEXTDOMAIN ) ,
                "param_name" => "number" ,
                "value"      => 12 ,
                'edit_field_class'=>'vc_col-sm-3',
            ) ,
            array(
                "type"       => "textfield" ,
                "holder"     => "div" ,
                "class"      => "" ,
                "heading"    => __( "Zoom" , ST_TEXTDOMAIN ) ,
                "param_name" => "zoom" ,
                "value"      => 13 ,
                'edit_field_class'=>'vc_col-sm-3',
            ) ,
            array(
                "type"        => "textfield" ,
                "holder"      => "div" ,
                "class"       => "" ,
                "heading"     => __( "Map Height" , ST_TEXTDOMAIN ) ,
                "param_name"  => "height" ,
                "description" => "pixels" ,
                "value"       => 500 ,
                'edit_field_class'=>'vc_col-sm-3',
            ) ,
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Show Circle" , ST_TEXTDOMAIN ) ,
                "param_name"  => "show_circle" ,
                "description" => "" ,
                "value"       => array(
                    __("No",ST_TEXTDOMAIN)=>"no",
                    __("Yes",ST_TEXTDOMAIN)=>"yes"
                ) ,
                'edit_field_class'=>'vc_col-sm-3',
            ) ,
            array(
                "type"        => "textfield" ,
                "holder"      => "div" ,
                "heading"     => __( "Range" , ST_TEXTDOMAIN ) ,
                "param_name"  => "range" ,
                "description" => "Km" ,
                "value"       => "20" ,
                'edit_field_class'=>'vc_col-sm-6',
            ) ,
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Style Map" , ST_TEXTDOMAIN ) ,
                "param_name"  => "style_map" ,
                "description" => "" ,
                'value'       => array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __( 'Normal' , ST_TEXTDOMAIN )      => 'normal' ,
                    __( 'Midnight' , ST_TEXTDOMAIN )    => 'midnight' ,
                    __( 'Family Fest' , ST_TEXTDOMAIN ) => 'family_fest' ,
                    __( 'Open Dark' , ST_TEXTDOMAIN )   => 'open_dark' ,
                    __( 'Riverside' , ST_TEXTDOMAIN )   => 'riverside' ,
                    __( 'Ozan' , ST_TEXTDOMAIN )        => 'ozan' ,
                ) ,
                'edit_field_class'=>'vc_col-sm-6',
            ) ,
        )
    ) );
}

if(!function_exists( 'st_list_map_new' )) {
    function st_list_map_new( $attr , $content = false )
    {
        $data = shortcode_atts(
            array(
                'title'  => '' ,
                'st_list_location'  => '' ,
                'st_type'           => 'st_hotel' ,
                'zoom'              => '13' ,
                'height'            => '500' ,
                'number'            => '12' ,
                'style_map'         => 'normal' ,
                'show_circle' => 'no' ,
                'range' => '20' ,
            ) , $attr , 'st_list_map_new' );
        extract( $data );
        $st_type=explode(',',$st_type);
        $map_lat         = get_post_meta( $st_list_location , 'map_lat' , true );
        $map_lng         = get_post_meta( $st_list_location , 'map_lng' , true );
        $location_center = '[' . $map_lat . ',' . $map_lng . ']';
        $class_traveler = new TravelerObject();
        $data_post = $class_traveler->get_near_by_lat_lng($st_list_location , $map_lat,$map_lng,$st_type,$range,$number);



        global $post;
        $stt = 0;
        $data_map = array();
        if(!empty( $data_post )) {
            foreach( $data_post as $post ) :
                setup_postdata( $post );
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
            endforeach;
            wp_reset_postdata();

        }
        if($location_center =='[,]')$location_center='[0,0]';
        if($show_circle == 'no') {
            $range = 0;
        }
        $data_tmp = array(
            'location_center'  => $location_center ,
            'zoom'             => $zoom ,
            'data_map'         => $data_map ,
            'height'           => $height ,
            'style_map'        => $style_map ,
            'st_type'          => $st_type ,
            'number'           => $number ,
            'title'            => $title ,
            'range'           => $range ,
        );
        $data_tmp[ 'data_tmp' ] = $data_tmp;
        $html     = st()->load_template( 'vc-elements/st-list-map-new/html' , '' , $data_tmp );

        return $html;
    }
}

st_reg_shortcode( 'st_list_map_new' , 'st_list_map_new' );
