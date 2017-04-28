<?php
if(!st_check_service_available( 'st_rental' ) && !st_check_service_available( 'hotel_room' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    vc_map( array(
        "name"            => __( "ST Custom Discount List" , ST_TEXTDOMAIN ) ,
        "base"            => "st_custom_discount_list" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        'show_settings_on_create' => false,
        "params"          => array(
            )
    ) );
}
if(!function_exists( 'st_custom_discount_list' )) {
    function st_custom_discount_list( $attr , $content = false )
    {
        $attr = wp_parse_args( $attr, array());

        $return = st()->load_template('vc-elements/st-rental/st_discount_by_day','', null);
        return $return;
    }
}
if(function_exists('st_reg_shortcode')) {
    st_reg_shortcode( 'st_custom_discount_list' , 'st_custom_discount_list' );
}