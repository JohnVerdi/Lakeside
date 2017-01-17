<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST All Post Type Search Results", ST_TEXTDOMAIN),
            "base" => "st_all_post_type_content_search",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>'Shinetheme',
            "params" => array(
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Number Items", ST_TEXTDOMAIN),
                    "param_name" => "st_number",
                    "description" =>"",
                    "value" => 5,
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Style", ST_TEXTDOMAIN),
                    "param_name" => "st_style",
                    "description" =>"",
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('List',ST_TEXTDOMAIN)=>'1',
                        __('Grid',ST_TEXTDOMAIN)=>'2',
                    ),
                )
            )
        ) );
    }

    if(!function_exists('st_vc_all_post_type_content_search')){
        function st_vc_all_post_type_content_search($attr,$content=false)
        {
            $default=array(
                'st_style'=>1,
                'st_number'=>5,
            );
            $attr=wp_parse_args($attr,$default);
            extract($attr);
            if(!is_page_template('template-search-all-post-type.php')){
                return "";
            }
            $html ='';
            global $wp_query, $st_search_query;

            $data_post_type = STInput::request('data_post_type','all');
            if($data_post_type == 'all'){
                $data_post_type = array('st_hotel','st_rental' , 'st_cars' , 'st_tours' , 'st_activity');
            }else{
                $data_post_type = array($data_post_type);
            }
            ///////////////////////////////
            ////// Hotel
            //////////////////////////////
            if(st_check_service_available('st_hotel') and in_array('st_hotel',$data_post_type) ){
                $hotel = new STHotel();
                $hotel = STHotel::inst();
                $hotel->alter_search_query();
                query_posts(
                    array(
                        'post_type' => 'st_hotel',
                        's'         => '',
                        'paged'     => get_query_var('paged'),
                        'posts_per_page' => $st_number
                    )
                );
                $st_search_query = $wp_query;
                $html .=  st()->load_template('search/search-all-post-type/content','all-post-type',array('attr'=>$attr));
                $html .='<br>';
                $hotel->remove_alter_search_query();
                wp_reset_query();
            }
            ///////////////////////////////
            ////// Rental
            //////////////////////////////
            if(st_check_service_available('st_rental') and in_array('st_rental',$data_post_type) ) {
                $rental = new STRental();
                add_action( 'pre_get_posts' , array( $rental , 'change_search_arg' ) );
                query_posts(
                    array(
                        'post_type'      => 'st_rental' ,
                        's'              => '' ,
                        'paged'          => get_query_var( 'paged' ) ,
                        'posts_per_page' => $st_number
                    )
                );
                $st_search_query = $wp_query;
                $html .= st()->load_template( 'search/search-all-post-type/content' , 'all-post-type' , array( 'attr' => $attr ) );
                $html .= '<br>';
                remove_action( 'pre_get_posts' , array( $rental , 'change_search_arg' ) );
                $rental->remove_alter_search_query();
                wp_reset_query();
            }

            ///////////////////////////////
            ////// Activity
            //////////////////////////////
            if(st_check_service_available('st_activity')and in_array('st_activity',$data_post_type) ) {
                $activity = STActivity::inst();
				$activity->alter_search_query();
                query_posts(
                    array(
                        'post_type'      => 'st_activity' ,
                        's'              => '' ,
                        'paged'          => get_query_var( 'paged' ) ,
                        'posts_per_page' => $st_number
                    )
                );
                $st_search_query = $wp_query;
                $html .= st()->load_template( 'search/search-all-post-type/content' , 'all-post-type' , array( 'attr' => $attr ) );
                $html .= '<br>';
                $activity->remove_alter_search_query();
                wp_reset_query();
            }

            ///////////////////////////////
            ////// Cars
            //////////////////////////////
            if(st_check_service_available('st_cars') and in_array('st_cars',$data_post_type) ) {
                $cars = new STCars();
                add_action( 'pre_get_posts' , array( $cars , 'change_search_cars_arg' ) );
                query_posts(
                    array(
                        'post_type'      => 'st_cars' ,
                        's'              => '' ,
                        'paged'          => get_query_var( 'paged' ) ,
                        'posts_per_page' => $st_number
                    )
                );
                $st_search_query = $wp_query;
                $html .= st()->load_template( 'search/search-all-post-type/content' , 'all-post-type' , array( 'attr' => $attr ) );
                $html .= '<br>';
                remove_action( 'pre_get_posts' , array( $cars , 'change_search_cars_arg' ) );
                $cars->remove_alter_search_query();
                wp_reset_query();
            }
            ///////////////////////////////
            ////// Tours
            //////////////////////////////
            if(st_check_service_available('st_tours') and in_array('st_tours',$data_post_type) ) {
                $tours = new STTour();
                $tours->alter_search_query();
                add_action( 'pre_get_posts' , array( $tours , 'change_search_tour_arg' ) );
                query_posts(
                    array(
                        'post_type'      => 'st_tours' ,
                        's'              => '' ,
                        'paged'          => get_query_var( 'paged' ) ,
                        'posts_per_page' => $st_number
                    )
                );
                $st_search_query = $wp_query;
                $html .= st()->load_template( 'search/search-all-post-type/content' , 'all-post-type' , array( 'attr' => $attr ) );
                $html .= '<br>';
                $tours->remove_alter_search_query();
                wp_reset_query();
            }


            return $html;
        }
    }
    st_reg_shortcode('st_all_post_type_content_search','st_vc_all_post_type_content_search');