<?php 
return;
//if(function_exists('vc_map')){
//	$query = array(
//		'post_type' => 'st_hotel',
//		'posts_per_page' => -1,
//		'order_by' => 'name',
//		'order' => 'ASC'
//	);
//    $hotel_helper = new HotelHelper();
//    add_filter( 'posts_where' , array( $hotel_helper , '_get_query_where_validate' ) );
//    query_posts($query);
//    remove_filter( 'posts_where' , array( $hotel_helper , '_get_query_where_validate' ) );
//	$list_hotel = array();
//	while(have_posts()) : the_post();
//		$list_hotel[get_the_ID()] = get_the_title();
//	endwhile;
//	wp_reset_query(); wp_reset_postdata();
//	vc_map( array(
//            'name' => __('ST Slider List Hotel', ST_TEXTDOMAIN),
//            'base' => 'st_slider_list_hotel',
//            'content_element' => true,
//            'icon' => 'icon-st',
//            'category'=>'Hotel v2',
//            'class' => 'st_slider_list_hotel',
//            'params' => array(
//            	array(
//            		'type' => 'st_post_type_hotel',
//            		'holder' => 'div',
//            		'heading' => __('Select hotels', ST_TEXTDOMAIN),
//            		'param_name' => 'st_hotel_id',
//            		),
//	            	array(
//	            		'type' => 'checkbox',
//	            		'holder' => 'div',
//	            		'heading' => __('Show hotel description?', ST_TEXTDOMAIN),
//	            		'param_name' => 'st_hotel_description',
//	            		'value' => array(
//	            			__('Yes', ST_TEXTDOMAIN) => 'yes'
//	            		)
//	            	),
//	            	array(
//	            		'type' => 'checkbox',
//	            		'holder' => 'div',
//	            		'heading' => __('Show booking button?', ST_TEXTDOMAIN),
//	            		'param_name' => 'st_booking_button',
//	            		'value' => array(
//	            			__('Yes', ST_TEXTDOMAIN) => 'yes'
//	            		)
//	            	),
//	            	array(
//	                    'type' => 'dropdown',
//	                    'holder' => 'div',
//	                    'heading' => __('Effect', ST_TEXTDOMAIN),
//	                    'param_name' => 'st_effect',
//	                    'description' =>'',
//	                    'value'=>array(
//	                        __('--Select--',ST_TEXTDOMAIN) => '',
//	                        __('None',ST_TEXTDOMAIN) => 'false',
//	                        __('Fade',ST_TEXTDOMAIN) => 'fade',
//	                        __('Back Slide',ST_TEXTDOMAIN) => 'backSlide',
//	                        __('Go Down',ST_TEXTDOMAIN) => 'goDown',
//	                        __('Fade Up',ST_TEXTDOMAIN) => 'fadeUp',
//	                    ),
//	                ),
//            	),
//            )
//	);
//}
//if(!function_exists('st_slider_list_hotel_fc')){
//    function st_slider_list_hotel_fc($attr, $content = false){
//    	$data = shortcode_atts(
//            array(
//                'st_hotel_id'=> '',
//                'st_hotel_description' => 'no',
//                'st_booking_button' => 'no',
//                'st_effect' => 'fade',
//            ), $attr, 'st_slider_list_hotel' );
//        if(is_singular('st_hotel')){
//    		return st()->load_template('vc-elements/st-slider-list-hotel/style','1',$data);
//        }else{
//        	return '';
//        }
//    }
//}
//st_reg_shortcode('st_slider_list_hotel','st_slider_list_hotel_fc');