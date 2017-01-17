<?php
if(!st_check_service_available( 'st_rental' )) {
    return;
}
/**
* @since 1.1.3
* Rental Room Header
**/	
if(function_exists('vc_map')){
		vc_map(array(
    		'name'                    => __('ST Rental Room Header',ST_TEXTDOMAIN),
    		'base'                    => 'st_rental_room_header',
    		'content_element'         => true,
    		'icon'                    => 'icon-st',
    		'category'                => 'Rental',
    		'show_settings_on_create' => false,
    		'params'                  =>array()
		));
}
if(!function_exists('st_rental_room_header_ft')){
	function st_rental_room_header_ft($args){
		if(is_singular('rental_room')){
			return st()->load_template('vc-elements/st-rental-room/st_rental_room_header', false, array('data' => $args));
		}
		return false;
	}
}
st_reg_shortcode('st_rental_room_header','st_rental_room_header_ft');

/**
* @since 1.1.3
* Rental Room Excerpt
**/
if(function_exists('vc_map')){

    vc_map(array(
        'name'                    => __('ST Rental Room Content',ST_TEXTDOMAIN),
        'base'                    => 'st_rental_room_content',
        'content_element'         => true,
        'icon'                    => 'icon-st',
        'category'                => 'Rental',
        'show_settings_on_create' => false,
        'params'                  =>array()
    ));
}

if(!function_exists('st_rental_room_content_ft')){
    function st_rental_room_content_ft($attr, $content = null){
        if(is_singular('rental_room')){
            return '<div class="content mt20">'.get_the_content( ).'</div>';
        }
        return false;
    }
}
st_reg_shortcode('st_rental_room_content','st_rental_room_content_ft');
/**
* @since 1.1.3
* Rental Room Gallery
**/
if(function_exists('vc_map')){
    vc_map( array(
        "name" => __("ST Rental Room Gallery", ST_TEXTDOMAIN),
        "base" => "st_rental_room_gallery",
        "content_element" => true,
        "icon" => "icon-st",
        "category"=>'Rental',
        "params" => array(
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Style", ST_TEXTDOMAIN),
                "param_name" => "style",
                "description" =>"",
                "value" => array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('Slide',ST_TEXTDOMAIN)=>'slide',
                    __('Grid',ST_TEXTDOMAIN)=>'grid',
                ),
            )
        )
    ) );
}

if(!function_exists('st_rental_room_gallery_ft')){
    function st_rental_room_gallery_ft($attr,$content=false)
    {
        if(is_singular('rental_room'))
        {
            return st()->load_template('vc-elements/st-rental-room/st_rental_room_gallery',null,array('attr'=>$attr));
        }
    }
}
st_reg_shortcode('st_rental_room_gallery','st_rental_room_gallery_ft');

/**
* @since 1.1.3
* List Rental Room
**/
if(function_exists('vc_map')){

    vc_map(array(
        'name' => __('ST List Rental Room',ST_TEXTDOMAIN),
        'base' => 'st_list_rental_room',
        'content_element' => true,
        'show_settings_on_create' => true,
        'icon' => 'icon-st',
        'category' => 'Rental',
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __('Header text', ST_TEXTDOMAIN),
                'param_name' => 'header_title',
                'value' => __('Rental Room List', ST_TEXTDOMAIN)
                ),
            array(
                'type' => 'textfield',
                'heading' => __('Posts per page',ST_TEXTDOMAIN),
                'param_name' => 'post_per_page',
                'value' => 12
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Order by', ST_TEXTDOMAIN),
                'param_name' => 'order_by',
                'value' => array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('none',ST_TEXTDOMAIN) => 'none',
                    __('ID',ST_TEXTDOMAIN) => 'ID',
                    __('Name',ST_TEXTDOMAIN) => 'name',
                    __('Date',ST_TEXTDOMAIN) => 'date',
                    __('Random',ST_TEXTDOMAIN) => 'rand'
                    )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Order',ST_TEXTDOMAIN),
                'param_name' => 'order',
                'value' => array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('Ascending',ST_TEXTDOMAIN) => 'asc',
                    __('Descending',ST_TEXTDOMAIN) => 'desc'
                    )
                ),
        ),

    ));
}
if(!function_exists('st_list_rental_room_ft')){

    function st_list_rental_room_ft($attr, $content = false){
        $attr = wp_parse_args( $attr, array(
            'header_title' => '',
            'post_per_page' => 12,
            'order_by' => 'date',
            'order' => 'desc'
        ));
        if(is_singular('st_rental')){

            return st()->load_template('vc-elements/st-rental-room/st_list_rental_room',null,array('attr'=>$attr));
        }
    }
}

st_reg_shortcode('st_list_rental_room','st_list_rental_room_ft');

/**
* @since 1.1.3
* List Related Rental Room
**/
if(function_exists('vc_map')){

    vc_map(array(
        'name' => __('ST Related Rental Room',ST_TEXTDOMAIN),
        'base' => 'st_related_rental_room',
        'content_element' => true,
        'show_settings_on_create' => true,
        'icon' => 'icon-st',
        'category' => 'Rental',
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __('Header text', ST_TEXTDOMAIN),
                'param_name' => 'header_title',
                'value' => __('Related Rental Room', ST_TEXTDOMAIN)
                ),
            array(
                'type' => 'textfield',
                'heading' => __('Number of room', ST_TEXTDOMAIN),
                'param_name' => 'number_of_room',
                'value' => 5
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Show Excerpt', ST_TEXTDOMAIN),
                'param_name' => 'show_excerpt',
                'value' => array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('Yes', ST_TEXTDOMAIN) => 'yes',
                    __('No', ST_TEXTDOMAIN) => 'no'
                    ),
                'std' => 'no'
            )
        )
    ));
}
if(!function_exists('st_related_rental_room_ft')){

    function st_related_rental_room_ft($attr, $content = null){
        if(is_singular('rental_room')){

            return st()->load_template('vc-elements/st-rental-room/st_related_rental_room',null,array('attr'=>$attr));
        }
    }
}
st_reg_shortcode('st_related_rental_room','st_related_rental_room_ft');

/**
* ST Rental Room Review
* @since 1.1.4
**/
if(function_exists('vc_map')){
    vc_map(
        array(
            'name' => __("ST Rental Room Review", ST_TEXTDOMAIN),
            'base' => 'st_rental_room_review',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Rental',
            'show_settings_on_create' => false,
            'params'=>array()
        )
    );
}

if(!function_exists('st_rental_room_review'))
{
    function st_rental_room_review()
    {
        
        if(get_post_type() == 'rental_room')
        {
            if(comments_open() and st()->get_option('rental_review')=='on')
            {
                ob_start();
                comments_template('/reviews/reviews.php');
                return @ob_get_clean();
            }
        }
    }
    st_reg_shortcode('st_rental_room_review','st_rental_room_review');

}

/**
* ST Room rental facility
* @since 1.1.4
**/
if(function_exists('vc_map')){
    vc_map(
        array(
            'name' => __("ST Rental Room Facility", ST_TEXTDOMAIN),
            'base' => 'st_rental_room_facility',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Hotel',
            'show_settings_on_create' => true,
            'params' => array(
                array(
                    'type' => 'checkbox',
                    'heading' => __('Choose taxonomies', ST_TEXTDOMAIN),
                    'param_name' => 'choose_taxonomies',
                    'description' => __('Will be shown in layout', ST_TEXTDOMAIN),
                    'value' => STRental::listTaxonomy()
                )
            )
        )
    );
}

if(!function_exists('st_rental_room_facility_ft'))
{
    function st_rental_room_facility_ft($attr, $content = null)
    {
        $default=array(
                'choose_taxonomies'=>''
            );

        $attr=wp_parse_args($attr,$default);
        
        if(get_post_type() == 'rental_room')
        {
            return st()->load_template('vc-elements/st-rental-room/st_rental_room_facility',false,array('args'=>$attr));
        }
        return false;
    }
    st_reg_shortcode('st_rental_room_facility','st_rental_room_facility_ft');

}

?>