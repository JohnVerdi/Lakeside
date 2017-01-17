<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 12/15/14
 * Time: 9:44 AM
 */

if(!st_check_service_available( 'st_hotel' )) {
    return;
}
/**
 * ST Hotel header
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Hotel Header" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_hotel_header' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Hotel' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}

if(!function_exists( 'st_hotel_header' )) {
    function st_hotel_header( $arg )
    {
        if(is_singular( 'st_hotel' )) {
            return st()->load_template( 'hotel/elements/header' , false , array( 'arg' => $arg ) );
        }
        return false;
    }
}

st_reg_shortcode( 'st_hotel_header' , 'st_hotel_header' );

/**
 * ST Hotel star
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'            => __( "ST Hotel Star" , ST_TEXTDOMAIN ) ,
            'base'            => 'st_hotel_star' ,
            'content_element' => true ,
            'icon'            => 'icon-st' ,
            'category'        => 'Hotel' ,
            'params'          => array(
                array(
                    "type"        => "textfield" ,
                    "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "title" ,
                    'admin_label' => true ,
                    'std'         => 'Hotel Star'
                )
            )
        )
    );
}


if(!function_exists( 'st_hotel_star' )) {
    function st_hotel_star( $attr = array() )
    {
        $attr = wp_parse_args( $attr , array(
            'title' => ''
        ) );
        if(is_singular( 'st_hotel' )) {
            return st()->load_template( 'hotel/elements/star' , false , $attr );
        }
        return false;
    }
}
st_reg_shortcode( 'st_hotel_star' , 'st_hotel_star' );
/**
 * ST Hotel Video
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( 'ST Hotel Video' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_hotel_video' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Hotel' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}

if(!function_exists( 'st_hotel_video' )) {
    function st_hotel_video( $attr = array() )
    {
        if(is_singular( 'st_hotel' )) {
            if($video = get_post_meta( get_the_ID() , 'video' , true )) {
                return "<div class='media-responsive'>" . wp_oembed_get( $video ) . "</div>";
            }
        }
    }
}

st_reg_shortcode( 'st_hotel_video' , 'st_hotel_video' );

/**
 * ST Hotel Price
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'            => __( 'ST Hotel Price' , ST_TEXTDOMAIN ) ,
            'base'            => 'st_hotel_price' ,
            'icon'            => 'icon-st' ,
            'category'        => 'Hotel' ,
            "content_element" => true ,
            'params'          => array()
        )
    );
}


if(!function_exists( 'st_hotel_price_func' )) {
    function st_hotel_price_func( $attr , $content = false )
    {
        if(is_singular( 'st_hotel' )) {
            return st()->load_template( 'hotel/elements/price' );
        }
    }
}

st_reg_shortcode( 'st_hotel_price' , 'st_hotel_price_func' );

/**
*hotel policy
*@since 1.1.9
*/

if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'            => __( 'ST Hotel Policy' , ST_TEXTDOMAIN ) ,
            'base'            => 'st_hotel_policy' ,
            'icon'            => 'icon-st' ,
            'category'        => 'Hotel' ,
            "content_element" => true ,
            'params'          => array()
        )
    );
}


if(!function_exists( 'st_hotel_policy_func' )) {
    function st_hotel_policy_func( $attr , $content = false )
    {
        if(is_singular( 'st_hotel' )) {
            return st()->load_template( 'hotel/elements/policy' );
        }
    }
}

st_reg_shortcode( 'st_hotel_policy' , 'st_hotel_policy_func' );


/**
 * ST Hotel Logo
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'            => __( 'ST Hotel Logo' , ST_TEXTDOMAIN ) ,
            'base'            => 'st_hotel_logo' ,
            'content_element' => true ,
            'icon'            => 'icon-st' ,
            'category'        => 'Hotel' ,
            'params'          => array(
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
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
                array(
                    'type'       => 'dropdown' ,
                    'heading'    => __( 'Thumbnail Size' , ST_TEXTDOMAIN ) ,
                    'param_name' => 'thumbnail_size' ,
                    'value'      => array(
                        'Full'      => 'full' ,
                        'Large'     => 'large' ,
                        'Medium'    => 'medium' ,
                        'Thumbnail' => 'thumbnail'
                    )
                ) ,
            )
        )
    );
}

if(!function_exists( 'st_hotel_logo' )) {
    function st_hotel_logo( $attr = array() )
    {
        if(is_singular( 'st_hotel' )) {
            $default = array(
                'thumbnail_size' => 'full' ,
                'title'          => '' ,
                'font_size'      => '3' ,
            );

            extract( wp_parse_args( $attr , $default ) );

            $meta = get_post_meta( get_the_ID() , 'logo' , true );

            if(is_numeric($meta)){
                $meta = wp_get_attachment_url($meta);
            }
            $html = '';
            if($meta) {
                /*$html = wp_get_attachment_image( $meta , $thumbnail_size , false , array(
                    'class' => 'img-responsive' ,
                    'style' => 'margin-bottom:10px;'
                ) );*/
                $html = "<img src=".$meta." class='img-responsive' style='margin-bottom:10px;'/>";
            }

            if(!empty( $title ) and !empty( $html )) {
                $html = '<h' . $font_size . '>' . $title . '</h' . $font_size . '>' . $html;
            }
            return $html;
        }
    }
}

st_reg_shortcode( 'st_hotel_logo' , 'st_hotel_logo' );


/**
 * ST Hotel Add Review
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( 'ST Add Hotel Review' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_hotel_add_review' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Hotel' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}

if(!function_exists( 'st_hotel_add_review' )) {
    function st_hotel_add_review()
    {
        if(is_singular( 'st_hotel' )) {
            return '<div class="text-right mb10">
                      <a class="btn btn-primary" href="' . get_comments_link() . '">' . __( 'Write a review' , ST_TEXTDOMAIN ) . '</a>
                   </div>';
        }
    }
}

st_reg_shortcode( 'st_hotel_add_review' , 'st_hotel_add_review' );

/**
 * ST Hotel Nearby
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( 'ST Hotel Nearby' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_hotel_nearby' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Hotel' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
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
            )
        )
    );
}

if(!function_exists( 'st_hotel_nearby' )) {
    function st_hotel_nearby( $attr = array() , $content = null )
    {
        if(is_singular( 'st_hotel' )) {
            $default = array(
                'title'     => '' ,
                'font_size' => '3' ,
            );
            $attr    = wp_parse_args( $attr , $default );
            return st()->load_template( 'hotel/elements/nearby' , false , array( 'attr' => $attr ) );
        }
    }
}

st_reg_shortcode( 'st_hotel_nearby' , 'st_hotel_nearby' );

/**
 * ST Hotel Review
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( 'ST Hotel Review' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_hotel_review' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Hotel' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
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
            )
        )
    );
}

if(!function_exists( 'st_hotel_review' )) {
    function st_hotel_review( $attr = array() )
    {
        if(is_singular( 'st_hotel' )) {
            $default = array(
                'title'     => '' ,
                'font_size' => '3' ,
            );
            extract( wp_parse_args( $attr , $default ) );
            if(comments_open() and st()->get_option( 'hotel_review' ) == 'on') {
                ob_start();
                comments_template( '/reviews/reviews.php' );
                $html = @ob_get_clean();
                if(!empty( $title ) and !empty( $html )) {
                    $html = '<h' . $font_size . '>' . $title . '</h' . $font_size . '>' . $html;
                }
                return $html;
            }
        }
    }
}

st_reg_shortcode( 'st_hotel_review' , 'st_hotel_review' );

/**
 * ST Hotel Detail List Rooms
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( 'ST Detailed List of Hotel Rooms' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_hotel_detail_list_rooms' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Hotel' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}


if(!function_exists( 'st_hotel_detail_list_rooms' )) {
    function st_hotel_detail_list_rooms( $attr = array() )
    {
        if(is_singular( 'st_hotel' )) {
            return st()->load_template( 'hotel/elements/loop_room' , null , array( 'attr' => $attr ) );
        }
    }
}

st_reg_shortcode( 'st_hotel_detail_list_rooms' , 'st_hotel_detail_list_rooms' );

/**
 * ST Hotel Detail Card Accept
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'            => __( 'ST Detailed Hotel Card Accept' , ST_TEXTDOMAIN ) ,
            'base'            => 'st_hotel_detail_card_accept' ,
            'content_element' => true ,
            'icon'            => 'icon-st' ,
            'category'        => 'Hotel' ,
            "params"          => array(
                // add params same as with any other content element
                array(
                    "type"        => "textfield" ,
                    "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "title" ,
                    "description" => "" ,
                ) ,
            )
        )
    );
}

if(!function_exists( 'st_hotel_detail_card_accept' )) {
    function st_hotel_detail_card_accept( $arg = array() )
    {
        $arg = wp_parse_args( $arg , array(
            'title' => ''
        ) );
        if(is_singular( 'st_hotel' )) {
            return st()->load_template( 'hotel/elements/card' , false , array( 'arg' => $arg ) );
        }
        return false;
    }
}

st_reg_shortcode( 'st_hotel_detail_card_accept' , 'st_hotel_detail_card_accept' );

/**
 * ST Hotel Detail Search Room
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( 'ST Hotel Rooms Search Results' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_hotel_detail_search_room' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Hotel' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
                array(
                    "type"             => "textfield" ,
					"admin_label"           => true ,
                    "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "title" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
                array(
                    "type"             => "dropdown" ,
					"admin_label"           => true ,
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
                    "type"             => "dropdown" ,
                    "heading"          => __( "Style" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "style" ,
                    "description"      => "" ,
                    "value"            => array(
                        __( "Horizontal" , ST_TEXTDOMAIN )         => 'horizon' ,
                        __( "Vertical" , ST_TEXTDOMAIN )         => 'vertical' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );
}

if(!function_exists( 'st_hotel_detail_search_room' )) {
    function st_hotel_detail_search_room( $attr = array() )
    {
        if(is_singular( 'st_hotel' )) {
            $default = array(
                'title'     => '' ,
                'font_size' => '3' ,
				'style'=>'horizon'
            );
            extract( wp_parse_args( $attr , $default ) );
            $html = st()->load_template( 'hotel/elements/search_room' , null , array( 'attr' => $attr ) );
            if(!empty( $title ) and !empty( $html )) {
                $html = '<h' . $font_size . '>' . $title . '</h' . $font_size . '>' . $html;
            }
            return $html;
        }
    }
}

st_reg_shortcode( 'st_hotel_detail_search_room' , 'st_hotel_detail_search_room' );

/**
 * ST Hotel Detail Review Detail
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( 'ST Detailed Hotel Review' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_hotel_detail_review_detail' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Hotel' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}


if(!function_exists( 'st_hotel_detail_review_detail' )) {
    function st_hotel_detail_review_detail()
    {
        if(is_singular( 'st_hotel' )) {
            return st()->load_template( 'hotel/elements/review_detail' );
        }
    }
}

st_reg_shortcode( 'st_hotel_detail_review_detail' , 'st_hotel_detail_review_detail' );

/**
 * ST Hotel Detail Review Summary
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( 'ST Hotel Review Summary' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_hotel_detail_review_summary' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Hotel' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}

if(!function_exists( 'st_hotel_detail_review_summary' )) {
    function st_hotel_detail_review_summary()
    {
        if(is_singular( 'st_hotel' )) {
            return st()->load_template( 'hotel/elements/review_summary' );
        }
    }
}

st_reg_shortcode( 'st_hotel_detail_review_summary' , 'st_hotel_detail_review_summary' );

/**
 * ST Hotel Detail Map
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( 'ST Detailed Hotel Map' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_hotel_detail_map' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Hotel' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
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
                        __("No",ST_TEXTDOMAIN)=>"no",
                        __("Yes",ST_TEXTDOMAIN)=>"yes"
                    ) ,
                ) ,
            )
        )
    );
}

if(!function_exists( 'st_hotel_detail_map' )) {
    function st_hotel_detail_map( $attr )
    {
        if(is_singular( 'st_hotel' )) {
            $hotel=new STHotel();
            $data=$hotel->get_near_by(get_the_ID(),200,10);
            $default = array(
                'number'      => '12' ,
                'range'       => '20' ,
                'show_circle' => 'no' ,
            );
            extract( wp_parse_args( $attr , $default ) );
            $lat   = get_post_meta( get_the_ID() , 'map_lat' , true );
            $lng   = get_post_meta( get_the_ID() , 'map_lng' , true );
            $zoom  = get_post_meta( get_the_ID() , 'map_zoom' , true );
            $hotel = new STHotel();
            $data  = $hotel->get_near_by( get_the_ID() , $range , $number );
            $location_center                     = '[' . $lat . ',' . $lng . ']';
            $data_map                            = array();
            $data_map[ 0 ][ 'id' ]               = get_the_ID();
            $data_map[ 0 ][ 'name' ]             = get_the_title();
            $data_map[ 0 ][ 'post_type' ]        = get_post_type();
            $data_map[ 0 ][ 'lat' ]              = $lat;
            $data_map[ 0 ][ 'lng' ]              = $lng;
            $data_map[ 0 ][ 'icon_mk' ]          = get_template_directory_uri() . '/img/mk-single.png';
            $data_map[ 0 ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/hotel' , false , array( 'post_type' => '' ) ) );
            $data_map[ 0 ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/hotel' , false , array( 'post_type' => '' ) ) );
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
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_hotel_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_black.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/hotel' , false , array( 'post_type' => '' ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/hotel' , false , array( 'post_type' => '' ) ) );
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
            );
            $data_tmp[ 'data_tmp' ] = $data_tmp;
            $html                   = '<div class="map_single">'.st()->load_template( 'hotel/elements/detail' , 'map' , $data_tmp ).'</div>';
            return $html;
        }
    }
}

st_reg_shortcode( 'st_hotel_detail_map' , 'st_hotel_detail_map' );

