<?php 
/*
* Form Check Availability Hotel
*/
/*if(function_exists('vc_map')){
	vc_map( array(
        'name' => __('ST Check Availability Hotel', ST_TEXTDOMAIN),
        'base' => 'st_check_availability_hotel',
        'content_element' => true,
        'icon' => 'icon-st',
        'category'=>'Hotel v2',
        'class' => 'st_check_availability_hotel',
        'params' => array(
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'heading' => __('Enter a title', ST_TEXTDOMAIN),
                'param_name' => 'st_title',
                'value' => __('Check Availability', ST_TEXTDOMAIN)
                )
            )
	   )
    );
}
if(!function_exists('st_check_availability_hotel')){
    function st_check_availability_hotel($attr, $content = false){
        $data = shortcode_atts(
            array(
                'st_title'=> __('Check Availability', ST_TEXTDOMAIN),
            ), $attr, 'st_check_availability_hotel' );

        if(is_singular('st_hotel')){
    		return st()->load_template('hotel/style3/availability','form', $data);
        }else{
        	return '';
        }
    }
}
st_reg_shortcode('st_check_availability_hotel','st_check_availability_hotel');*/

/*
* List Room Hotel Slider
*/
/*if(function_exists('vc_map')){
    vc_map( array(
        'name' => __('ST List Hotel Room Slider', ST_TEXTDOMAIN),
        'base' => 'st_list_hotel_room_slider',
        'content_element' => true,
        'icon' => 'icon-st',
        'category'=>'Hotel v2',
        'class' => 'st_list_hotel_room_slider',
        'params' => array(
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'heading' => __('Enter a title', ST_TEXTDOMAIN),
                'param_name' => 'st_title',
                'value' => __('Hotel Luxury Room', ST_TEXTDOMAIN)
                ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'heading' => __('No. Item(s)', ST_TEXTDOMAIN),
                'param_name' => 'st_number_item',
                'value' => 12
                ),
            array(
                'type' => 'dropdown',
                'holder' => 'div',
                'heading' => __('Order By', ST_TEXTDOMAIN),
                'param_name' => 'st_order_by',
                'value' => array(
                    __('Name', ST_TEXTDOMAIN) => 'name',
                    __('Date', ST_TEXTDOMAIN) => 'date',
                    )
                ),
            array(
                'type' => 'dropdown',
                'holder' => 'div',
                'heading' => __('Order', ST_TEXTDOMAIN),
                'param_name' => 'st_order',
                'value' => array(
                    __('ASC', ST_TEXTDOMAIN) => 'ASC',
                    __('DESC', ST_TEXTDOMAIN) => 'DESC',
                    )
                ),
            )
       )
    );
}

if(!function_exists('st_list_hotel_room_slider')){
    function st_list_hotel_room_slider($attr, $content = false){
        $data = shortcode_atts(
            array(
                'st_title'=> __('Hotel Luxury Room', ST_TEXTDOMAIN),
                'st_number_item' => 12,
                'st_order_by' => 'name',
                'st_order' => 'ASC'
            ), $attr, 'st_list_hotel_room_slider' );

        if(is_singular('st_hotel')){
            return st()->load_template('hotel/style3/list_hotel_room','slider', $data);
        }else{
            return '';
        }
    }
}
st_reg_shortcode('st_list_hotel_room_slider','st_list_hotel_room_slider');*/
