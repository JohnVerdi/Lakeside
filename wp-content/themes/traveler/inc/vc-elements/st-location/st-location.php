<?php
/*    if(function_exists('vc_map')){
        vc_map( 
            array(
            "name" => __("ST Location", ST_TEXTDOMAIN),
            "base" => "st_location",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params"    =>array(
                array(
                    "type"  =>"dropdown",
                    "holder"=>"div",
                    "heading"=>__("Location item custom", ST_TEXTDOMAIN), // 
                    "param_name" => "st_location_custom_type",
                    "description" =>__("Select <b>Custom</b> if you want customize infomation text of Location<br> OR not we get all information of location ",ST_TEXTDOMAIN),
                    "value"     =>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Normal', ST_TEXTDOMAIN) => 'normal',
                        __('Custom', ST_TEXTDOMAIN) => 'custom',
                        )
                    ),
                array(
                    "type"  =>"textfield",
                    "holder"=>"div",
                    "heading"=>__("From price custom", ST_TEXTDOMAIN), // 
                    "param_name" => "st_location_price_custom",
                    "description" =>__("Your custom from price ",ST_TEXTDOMAIN),
                    "dependency"    =>
                        array(
                            "element"   =>"st_location_custom_type",
                            "value"     =>"custom"
                        ),
                ),
                array(
                    "type"  =>"textfield",
                    "holder"=>"div",
                    "heading"=>__("Offers number", ST_TEXTDOMAIN), // 
                    "param_name" => "st_location_number_offers",
                    "description" =>__("Your custom offers number ",ST_TEXTDOMAIN),
                    "dependency"    =>
                        array(
                            "element"   =>"st_location_custom_type",
                            "value"     =>"custom"
                        ),
                ),
                array(
                    "type"  =>"textfield",
                    "holder"=>"div",
                    "heading"=>__("Reviews number", ST_TEXTDOMAIN), // 
                    "param_name" => "st_location_number_reviews",
                    "description" =>__("Your custom reviews number ",ST_TEXTDOMAIN),
                    "dependency"    =>
                        array(
                            "element"   =>"st_location_custom_type",
                            "value"     =>"custom"
                        ),
                ),
                
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Your post type", ST_TEXTDOMAIN),
                    "param_name" => "st_location_post_type",
                    "description" =>__("Your post type",ST_TEXTDOMAIN),
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Hotel', ST_TEXTDOMAIN) => 'st_hotel',
                        __('Car', ST_TEXTDOMAIN) => 'st_cars',
                        __('Rental', ST_TEXTDOMAIN) => 'st_rental',
                        __('Activity', ST_TEXTDOMAIN) => 'st_activity',
                        __('Tour', ST_TEXTDOMAIN) => 'st_tours',
                    ),
                    "dependency"    =>
                        array(
                            "element"   =>"st_location_custom_type",
                            "value"     =>"normal"
                        ),
                ),
                array(
                    "type" => "attach_image",
                    "holder" => "div",
                    "heading" => __("Your post type thumbnail", ST_TEXTDOMAIN),
                    "param_name" => "st_location_post_type_thumb",
                    "description" =>__("Your post type thumbnail",ST_TEXTDOMAIN),
                ),
            )
            
        ) );
    }

    if (!function_exists('st_location_func')){
        function st_location_func($attr){
            return ;
            $data = shortcode_atts(
                array(          
                'st_location_custom_type'=>'normal',
                'st_location_post_type'=>'st_hotel',
                'st_location_number_offers'=>'',
                'st_location_number_reviews'=>'',
                'st_location_price_custom'=>'',
                'st_location_post_type_thumb'=>''
                ), $attr, 'st_location' );
            extract($data);
            
            if (!is_singular('location')){return ; }

            $post_type = $st_location_post_type ; 

            if ($st_location_custom_type =="custom"){
                $array = array(
                    'post_type'=>       $st_location_post_type,
                    'thumb'=>       $st_location_post_type_thumb ,
                    'post_type_name'=>      get_post_type_object($post_type)->labels->name,
                    'reviews'=>     $st_location_number_reviews,
                    'offers'=>      $st_location_number_offers,
                    'min_max_price'=>  array(
                        'price_min'=>$st_location_price_custom ,
                        )     
                    );
            }
            else {
                // get infomation from location ID
                $array = STLocation::get_info_by_post_type(get_the_ID(), $post_type);
                $array['thumb']= $attr['st_location_post_type_thumb'] ;
                $array['post_type']=$attr['st_location_post_type'];
            }
            return st()->load_template('location/location-content-item' , null, $array ) ;
            
        }
        st_reg_shortcode('st_location','st_location_func');
    }*/

    /**
    * @since 1.1.3
    * @Description build Location page header
    *
    */
    if (function_exists('vc_map')){
        
        vc_map( array(
            "name" => __("ST Location count rate ", ST_TEXTDOMAIN),
            "base" => "st_location_header_rate_count",
            "content_element" => true,
            "icon" => "icon-st",
            "params" => array(
                // add params same as with any other content element
                array(
                    "type"  =>"checkbox",
                    "holder"=>"div",
                    "heading"=>__("Post type select ?", ST_TEXTDOMAIN), // 
                    "param_name" => "post_type",
                    "description" =>__("Select your post types which you want ?",ST_TEXTDOMAIN),    
                    "value" => array(
                        __('--- All ---',ST_TEXTDOMAIN)=>'all',
                        __('Hotel', ST_TEXTDOMAIN) => 'st_hotel',
                        __('Car', ST_TEXTDOMAIN) => 'st_cars',
                        __('Rental', ST_TEXTDOMAIN) => 'st_rental',
                        __('Activity', ST_TEXTDOMAIN) => 'st_activity',
                        __('Tour', ST_TEXTDOMAIN) => 'st_tours',
                    )            
                ),     
                          

            )
        ) );
        vc_map( array(
            "name" => __("ST Location statistical", ST_TEXTDOMAIN),
            "base" => "st_location_header_static",
            "content_element" => true,
            "icon" => "icon-st",
            "params" => array(
                // add params same as with any other content element
                array(
                    "type"  =>"checkbox",
                    "holder"=>"div",
                    "heading"=>__("Post type select ?", ST_TEXTDOMAIN), // 
                    "param_name" => "post_type",
                    "description" =>__("Select your post types",ST_TEXTDOMAIN),    
                    "value" => array(
                        __('--- All ---',ST_TEXTDOMAIN)=>'all',
                        __('Hotel', ST_TEXTDOMAIN) => 'st_hotel',
                        __('Car', ST_TEXTDOMAIN) => 'st_cars',
                        __('Rental', ST_TEXTDOMAIN) => 'st_rental',
                        __('Activity', ST_TEXTDOMAIN) => 'st_activity',
                        __('Tour', ST_TEXTDOMAIN) => 'st_tours',
                    )            
                ),
                array(
                    "type"  =>"checkbox",
                    "holder"=>"div",
                    "heading"=>__("Select star list ", ST_TEXTDOMAIN), // 
                    "param_name" => "star_list",
                    "description" =>__("Select star list to static and show",ST_TEXTDOMAIN),    
                    "value" => array(
                        __('--- All ---<br>',ST_TEXTDOMAIN)=>'all',
                        __('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> (5)<br> ', ST_TEXTDOMAIN) => '5',
                        __('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> (4)<br> ', ST_TEXTDOMAIN) => '4',
                        __('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> (3)<br> ', ST_TEXTDOMAIN) => '3',
                        __('<i class="fa fa-star"></i><i class="fa fa-star"></i> (2) <br> ', ST_TEXTDOMAIN) => '2',
                        __('<i class="fa fa-star"></i> (1)  ', ST_TEXTDOMAIN) => '1',
                    )            
                ),
            )
        ) );
        
    }
    
    if(!function_exists('st_location_header_rate_count')){
        function st_location_header_rate_count($arg){

            $defaults = array(
                'post_type'=>'all',
            );
            $arg = wp_parse_args( $arg, $defaults );
            
            return st()->load_template('vc-elements/st-location/location' , 'header-rate-count' , $arg); 
        }
        st_reg_shortcode('st_location_header_rate_count','st_location_header_rate_count' );
    }
    if(!function_exists('st_location_header_static')){
        function st_location_header_static($arg){
            $defaults = array(
                'post_type'=>'all',
                'star_list'=>'all'
            );
            $arg = wp_parse_args( $arg, $defaults );
            
            return st()->load_template('vc-elements/st-location/location' , 'header-static' , $arg); 
        }
        st_reg_shortcode('st_location_header_static','st_location_header_static' );
    }
    /**
    * @since 1.1.3
    * @Description build Location page content
    * create a vc_tab and get Shinetheme element into here 
    */
    if (function_exists('vc_map')){
        /**
        * @since 1.1.3
        * St location information slider 
        */
        vc_map(
            array(
            "name" => __("ST Gallery slider ", ST_TEXTDOMAIN),
            "base" => "st_location_slider",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params"    =>array(
                array(
                    "type"  =>"attach_images",
                    "holder"=>"div",
                    "heading"=>__("Gallery slider ", ST_TEXTDOMAIN), // 
                    "param_name" => "st_location_list_image"             
                    
                )
            )
        )
        );
        
        if (!function_exists('st_location_infomation_func')){
            function st_location_infomation_func($attr){
                return STLocation::get_slider($attr['st_location_list_image']);
            }
            st_reg_shortcode('st_location_slider','st_location_infomation_func' );

        };   
        $params = array(

            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Style", ST_TEXTDOMAIN),
                "param_name" => "st_location_style",
                "description" =>"Default style",
                'value'=> array(
                    __('--Select --',ST_TEXTDOMAIN)=>'',
                    __('List',ST_TEXTDOMAIN)=>'list',
                    __('Grid',ST_TEXTDOMAIN)=>'grid')
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "heading" => __("No. items displayed", ST_TEXTDOMAIN),
                "param_name" => "st_location_num",
                "description" =>"Number of items shown",
                'value'=>4,
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Order By", ST_TEXTDOMAIN),
                "param_name" => "st_location_orderby",
                "description" =>"",
                'value'=>st_get_list_order_by()
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Order",ST_TEXTDOMAIN),
                "param_name" => "st_location_order",
                'value'=>array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('Asc',ST_TEXTDOMAIN)=>'asc',
                    __('Desc',ST_TEXTDOMAIN)=>'desc'
                ),
            )
        );
        vc_map(
            array(
                "name" => __("ST Location list car ", ST_TEXTDOMAIN),
                "base" => "st_location_list_car",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",
                "params"    =>$params
            ));
        vc_map(
            array(
                "name" => __("ST Location list hotel ", ST_TEXTDOMAIN),
                "base" => "st_location_list_hotel",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",
                "params"    =>$params
            ));
        vc_map(
            array(
                "name" => __("ST Location list rental ", ST_TEXTDOMAIN),
                "base" => "st_location_list_rental",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",
                "params"    =>$params
            ));
        vc_map(
            array(
                "name" => __("ST Location list activity ", ST_TEXTDOMAIN),
                "base" => "st_location_list_activity",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",
                "params"    =>$params
            ));
        vc_map(
            array(
                "name" => __("ST Location list tour ", ST_TEXTDOMAIN),
                "base" => "st_location_list_tour",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",
                "params"    =>$params
            )
        );
        vc_map(
            array(
                "name" => __("ST Location map", ST_TEXTDOMAIN),
                "base" => "st_location_map",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",   
                'params'=> array(
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "heading" => __("Number", ST_TEXTDOMAIN),
                        "param_name" => "map_spots",
                    )             
                )
            )
        );
        if (!function_exists('st_location_list_car_func')){
            function st_location_list_car_func($attr){
                global $st_search_args;
                $data = shortcode_atts(
                array( 
                    'st_location_style'=>"",
                    'st_location_num'=>"",
                    'st_location_orderby'=>"",
                    'st_location_order'=>"",
                    'st_location'=>get_the_ID()
                ), $attr, 'st_location_list_car' );
                extract($data);
                $st_search_args=$data;
                $return ="";
                $query=array(
                    'post_type' => 'st_cars',
                    'posts_per_page'=>$st_location_num,
                    'order'=>$st_location_order,
                    'orderby'=>$st_location_orderby,
                    'post_status'=>'publish',
                );

                if (STInput::request('style')){$st_location_style = STInput::request('style');};

                if ($st_location_style =="list"){
                    $return .='<ul class="booking-list loop-cars style_list">' ; 
                }else {
                    $return .='<div class="row row-wrap">';
                }
                $cars=STCars::get_instance();
                $cars->alter_search_query();
                query_posts($query);
                while(have_posts()){
                    the_post();
                    if ($st_location_style =="list"){
                            $return .=st()->load_template('cars/elements/loop/loop-1');
                        }else {
                            $return .=st()->load_template('cars/elements/loop/loop-2');
                        }
                }
                $cars->remove_alter_search_query();
                wp_reset_query();
                $st_search_args=null;
                if ($st_location_style =="list"){
                    $return .='</ul>' ; 
                }else {
                    $return .="</div>";
                }
                return $return ;
            }
            st_reg_shortcode('st_location_list_car','st_location_list_car_func' );
        };
        if (!function_exists('st_location_list_hotel_func')){
            function st_location_list_hotel_func($attr){
                global $st_search_args;
                $data = shortcode_atts(
                array( 
                    'st_location_style'=>"",
                    'st_location_num'=>"",
                    'st_location_orderby'=>"",
                    'st_location_order'=>"",
                    'st_location'=>get_the_ID()
                ), $attr, 'st_location_list_hotel' );
                extract($data);
                $st_search_args=$data;
                $hotel = STHotel::inst();
                $hotel->alter_search_query();
                $return = '' ;
                $query=array(
                    'post_type' => 'st_hotel',
                    'posts_per_page'=>$st_location_num,
                    'order'=>$st_location_order,
                    'orderby'=>$st_location_orderby,
                    'post_status'=>'publish',
                );
                $data['query'] = $query; 
                $data['style'] =$st_location_style;
				$data['taxonomy']=FALSE;
                query_posts($query);
                if ( have_posts() ) :
					if($st_location_style=='grid'){
						$return.='<div class="row row-wrap loop_hotel loop_grid_hotel style_box">';
					}
                    while ( have_posts() ) : the_post();
						switch($st_location_style){
							case "grid":
								$return .=st()->load_template('hotel/loop','grid',$data);
								break;
							default:
								$return .=st()->load_template('hotel/loop','list',$data);
								break;
						}
                    endwhile;
					if($st_location_style=='grid'){
						$return.='</div>';
					}
                endif;
                $hotel->remove_alter_search_query();
                wp_reset_query();
                return $return;
            }
            st_reg_shortcode('st_location_list_hotel','st_location_list_hotel_func' );
        };
        if (!function_exists('st_location_list_tour_func')){
            function st_location_list_tour_func($attr){
                global $st_search_args;
                $data = shortcode_atts(
                array( 
                    'st_location_style'=>"",
                    'st_location_num'=>"",
                    'st_location_orderby'=>"",
                    'st_location_order'=>"",
                    'st_location'=>get_the_ID()
                ), $attr, 'st_location_list_tour' );
                extract($data);
                $st_search_args=$data;
                $return = '' ;
                $query=array(
                    'post_type' => 'st_tours',
                    'posts_per_page'=>$st_location_num,
                    'order'=>$st_location_order,
                    'orderby'=>$st_location_orderby,
                    'post_status'=>'publish',
                );
                if (STInput::request('style')){$st_location_style = STInput::request('style');};
                if($st_location_style == 'list'){
                    $return .="<ul class='booking-list loop-tours style_list loop-tours-location'>";
                }
                else{
                    $return .='<div class="row row-wrap grid-tour-location">';
                }
                $tour = STTour::get_instance();
                $tour->alter_search_query();
                query_posts($query);
                while(have_posts()){
                    the_post();
                    if($st_location_style == 'list'){
                        $return .=st()->load_template('tours/elements/loop/loop-1',null , array('tour_id'=>get_the_ID()));
                    }
                    else{
                        $return .=  st()->load_template('tours/elements/loop/loop-2',null, array('tour_id'=>get_the_ID()));
                    }
                }
                $tour->remove_alter_search_query();
                wp_reset_query();
                if($st_location_style == 'list'){
                    $return .="</ul>";
                }
                else{
                    $return .="</div>";
                }
                return $return ;
            }
            st_reg_shortcode('st_location_list_tour','st_location_list_tour_func' );
        };
        if (!function_exists('st_location_list_rental_func')){
            function st_location_list_rental_func($attr){

                global $st_search_args;
                $data = shortcode_atts(
                array( 
                    'st_location_style'=>"",
                    'st_location_num'=>"",
                    'st_location_orderby'=>"",
                    'st_location_order'=>"",
                    'st_location'=>get_the_ID()
                ), $attr, 'st_location_list_rental' );
                extract($data);
                $return = '' ;
                $st_search_args = $data;
                $query=array(
                    'post_type' => 'st_rental',
                    'posts_per_page'=>$st_location_num,
                    'order'=>$st_location_order,
                    'orderby'=>$st_location_orderby,
                    'post_status'=>'publish',
                );
                $rental = STRental::inst();
                $rental->alter_search_query();
                query_posts($query);
                $data['style'] = $st_location_style ;
                if ( have_posts() ) :
					$return.=st()->load_template('rental/loop',FALSE,$data);
                endif;
                $rental->remove_alter_search_query();
                wp_reset_query();   
                return $return ;
            }
            st_reg_shortcode('st_location_list_rental','st_location_list_rental_func' );
        };
        if (!function_exists('st_location_list_activity_func')){
            function st_location_list_activity_func($attr){
                global $st_search_args;
                $data = shortcode_atts(
                array( 
                    'st_location_style'=>"",
                    'st_location_num'=>"",
                    'st_location_orderby'=>"",
                    'st_location_order'=>"",
                    'st_location'=>get_the_ID()
                ), $attr, 'st_location_list_activity' );
                extract($data);
                $st_search_args = $data;
                $return = '' ;
                $query=array(
                    'post_type' => 'st_activity',
                    'posts_per_page'=>$st_location_num,
                    'order'=>$st_location_order,
                    'orderby'=>$st_location_orderby,
                    'post_status'=>'publish',
                );
                if (STInput::request('style')){$st_location_style = STInput::request('style');};
                if($st_location_style == 'list'){
                    $return .="<ul class='booking-list loop-tours style_list loop-activity-location'>";
                }
                else{
                    $return .='<div class="row row-wrap grid-activity-location">';
                }
                $activity = STActivity::inst();
                $activity->alter_search_query();
                query_posts($query);
                while(have_posts()){
                    the_post();
                    if($st_location_style == 'list'){
                        $return .=st()->load_template('activity/elements/loop/loop-1' ,null , array('is_location'=>true) );
                    }
                    else{
                        $return .=st()->load_template('activity/elements/loop/loop-2' ,null , array('is_location'=>true) );
                    }
                }
                $activity->remove_alter_search_query();
                wp_reset_query();
                if($st_location_style == 'list'){
                    $return .="</ul>";
                }
                else{
                    $return .='</div>';
                }
                return $return ;
            }
            st_reg_shortcode('st_location_list_activity','st_location_list_activity_func' );
        };
        if (!function_exists('st_location_map')){
            function st_location_map($attr){
                /**
                 *
                 * @since 1.2.4
                 * @author quandq
                 */
                if(!is_singular( 'location' )) {
                    return false;
                }
                $data = shortcode_atts(
                    array(
                        'map_spots'=>"500",
                    ), $attr, 'st_location_map' );
                extract($data);
                $map_location_style = get_post_meta(get_the_ID(),'map_location_style',true);
                if (!$map_location_style){$map_location_style = 'normal';}
                $zoom = get_post_meta( get_the_ID() , 'map_zoom' , true );
                if(empty( $zoom ) or !$zoom) {
                    $zoom = 15;
                }
                $default = array(
                    'tab_icon_'          => 'fa fa-map-marker' ,
                    'map_height'         => 500 ,
                    'map_spots'          => $map_spots ,
                    'map_location_style' => 'normal' ,
                    'tab_item_key'       => "location_map" ,
                    'show_circle'        => ''
                );
                $data    = extract( wp_parse_args( $default , $attr ) );
                $st_type = array();
                if($is_hotel = st_check_service_available( 'st_hotel' )) {
                    $st_type[ ] = 'st_hotel';
                }
                if($is_cars = st_check_service_available( 'st_cars' )) {
                    $st_type[ ] = 'st_cars';
                }
                if($st_tours = st_check_service_available( 'st_tours' )) {
                    $st_type[ ] = 'st_tours';
                }
                if($st_rental = st_check_service_available( 'st_rental' )) {
                    $st_type[ ] = 'st_rental';
                }
                if($st_activity = st_check_service_available( 'st_activity' )) {
                    $st_type[ ] = 'st_activity';
                }
                $map_lat                    = get_post_meta( get_the_ID() , 'map_lat' , true );
                $map_lng                    = get_post_meta( get_the_ID() , 'map_lng' , true );
                $location_center            = '[' . $map_lat . ',' . $map_lng . ']';
                $_SESSION[ 'el_post_type' ] = $st_type;
                $st_location                = new st_location();
                add_filter( 'posts_where' , array( $st_location , '_get_query_where' ) );
                $query = array(
                    'post_type'      => $st_type ,
                    'posts_per_page' => $map_spots ,
                    'post_status'    => 'publish' ,
                );
                global $wp_query;
                query_posts( $query );
                unset( $_SESSION[ 'el_post_type' ] );
                remove_filter( 'posts_where' , array( $st_location , '_get_query_where' ) );
                $stt = 0;
                $data_map = array();
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
                wp_reset_query();
                if(empty( $location_center ) or $location_center == '[,]')
                    $location_center = '[0,0]';
                $data_tmp               = array(
                    'location_center'    => $location_center ,
                    'zoom'               => $zoom ,
                    'data_map'           => $data_map ,
                    'height'             => $map_height ,
                    'style_map'          => $map_location_style ,
                    'st_type'            => $st_type ,
                    'number'             => $map_spots ,
                    'show_search_box'    => 'no' ,
                    'show_data_list_map' => 'no' ,
                    'range'              => '0' ,
                );
                $data_tmp[ 'data_tmp' ] = $data_tmp;
                return "<div class='single_location'>".st()->load_template( 'vc-elements/st-list-map-new/html' , '' , $data_tmp )."</div>";
            }
            st_reg_shortcode('st_location_map' , 'st_location_map');
        }
    }
