<?php 
/**
*@since 1.1.7
**/	
if(function_exists('vc_map')){
	vc_map( array(
        'name'      => __('ST Room Map', ST_TEXTDOMAIN),
        'base'      => 'st_room_map',
        'class'     => '',
        'icon' => 'icon-st',
        'category'=>'Shinetheme',
        'params'    => array(
            array(
                "type"        => "textfield" ,
                "holder"      => "div" ,
                "heading"     => __( "Range" , ST_TEXTDOMAIN ) ,
                "param_name"  => "range" ,
                "description" => "Km" ,
                "value"       => "20" ,
            ) ,
            array(
                "type"        => "textfield" ,
                "holder"      => "div" ,
                "heading"     => __( "Number" , ST_TEXTDOMAIN ) ,
                "param_name"  => "number" ,
                "description" => "" ,
                "value"       => "12" ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Show Circle" , ST_TEXTDOMAIN ) ,
                "param_name"  => "show_circle" ,
                "description" => "" ,
                "value"       => array(
                    __( "No" , ST_TEXTDOMAIN )  => "no" ,
                    __( "Yes" , ST_TEXTDOMAIN ) => "yes"
                ) ,
            )
        ),
    ));   
}
if(!function_exists('st_room_map_fc')){
	function st_room_map_fc($attr = array(), $content = null){
        $default = array(
            'number'      => '12' ,
            'range'       => '20' ,
            'show_circle' => 'no' ,
        );
        extract( $dump = wp_parse_args( $attr , $default ) );
        $hotel_id = get_post_meta(get_the_ID(), 'room_parent', true );
        if(!empty($hotel_id) and is_singular('hotel_room' ) || is_singular('rental_room')){
            $lat   = get_post_meta( $hotel_id , 'map_lat' , true );
            $lng   = get_post_meta( $hotel_id , 'map_lng' , true );
            $zoom  = get_post_meta( $hotel_id , 'map_zoom' , true );
            if( get_post_type() =='st_hotel'){
                $class = new STHotel();
            }else{
                $class = new STRental();
            }
            $data  = $class->get_near_by( $hotel_id , $range , $number );
            $location_center                     = '[' . $lat . ',' . $lng . ']';
            $data_map                            = array();
            $data_map[ 0 ][ 'id' ]               = $hotel_id;
            $data_map[ 0 ][ 'name' ]             = get_the_title();
            $data_map[ 0 ][ 'post_type' ]        = get_post_type();
            $data_map[ 0 ][ 'lat' ]              = $lat;
            $data_map[ 0 ][ 'lng' ]              = $lng;
            $data_map[ 0 ][ 'icon_mk' ]          = get_template_directory_uri() . '/img/mk-single.png';
            $data_map[ 0 ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/room' , false , array( 'hotel_id' => $hotel_id ) ) );
            $data_map[ 0 ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/room' , false , array( 'hotel_id' => $hotel_id ) ) );
            $stt                                 = 1;
            global $post;
            if(!empty( $data )) {
                foreach( $data as $post ) :
                    setup_postdata( $post );
                    $map_lat = get_post_meta( get_the_ID() , 'map_lat' , true );
                    $map_lng = get_post_meta( get_the_ID() , 'map_lng' , true );
                    if(!empty( $map_lat ) and !empty( $map_lng ) and is_numeric( $map_lat ) and is_numeric( $map_lng )) {
                        $post_type                              = get_post_type();
                        $data_map[ $stt ][ 'id' ]               = get_the_ID();
                        $data_map[ $stt ][ 'name' ]             = get_the_title();
                        $data_map[ $stt ][ 'post_type' ]        = $post_type;
                        $data_map[ $stt ][ 'lat' ]              = $map_lat;
                        $data_map[ $stt ][ 'lng' ]              = $map_lng;
                        if($post_type == 'st_hotel'){
                            $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_hotel_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_yellow.png' );

                        }else{
                            $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_rental_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_yellow.png' );
                        }
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/room' , false , array( 'hotel_id' => $hotel_id ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/room' , false , array( 'hotel_id' => $hotel_id ) ) );
                        $stt++;
                    }
                endforeach;
                wp_reset_postdata();
            }
            if($location_center == '[,]')
                $location_center = '[0,0]';
            if($show_circle == 'no') {
                $range = 0;
            }
            $data_tmp               = array(
                'location_center' => $location_center ,
                'zoom'            => $zoom ,
                'data_map'        => $data_map ,
                'height'          => 500 ,
                'style_map'       => 'normal' ,
                'number'          => $number ,
                'range'           => $range ,
                'hotel_id'           => $hotel_id ,
            );
            $data_tmp[ 'data_tmp' ] = $data_tmp;
            $html                   = '<div class="map_single">'.st()->load_template( 'hotel/elements/detail' , 'map' , $data_tmp ).'</div>';
            return $html;
        }
	}
}
st_reg_shortcode( 'st_room_map' , 'st_room_map_fc' );
