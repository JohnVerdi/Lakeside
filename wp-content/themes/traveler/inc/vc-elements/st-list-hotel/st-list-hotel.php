<?php
if(!st_check_service_available( 'st_hotel' )) {
    return;
}
if(function_exists( 'vc_map' ) and class_exists( 'TravelerObject' )) {
    $list_location  = TravelerObject::get_list_location();
    $list_location_data[ __( '-- Select --' , ST_TEXTDOMAIN ) ] = '';
    if(!empty( $list_location )) {
        foreach( $list_location as $k => $v ) {
            $list_location_data[ $v[ 'title' ] ] = $v[ 'id' ];
        }
    }

    vc_map( array(
        "name"            => __( "ST List of Hotels" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_hotel" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        "params"          => array(
            array(
                "type"        => "textfield" ,
                'admin_label' => true,
                "heading"     => __( "List ID in Hotel" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_ids" ,
                "description" => __( "Ids separated by commas" , ST_TEXTDOMAIN ) ,
                'value'       => "" ,
            ) ,
            array(
                "type"        => "textfield" ,
                'admin_label' => true,
                "heading"     => __( "Number hotel" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_number_ht" ,
                "description" => "" ,
                'value'       => 4 ,
            ) ,
            array(
                "type"             => "dropdown" ,
                'admin_label' => true,
                "heading"          => __( "Order By" , ST_TEXTDOMAIN ) ,
                "param_name"       => "st_orderby" ,
                "description"      => "" ,
                'edit_field_class' => 'vc_col-sm-6' ,
                'value'            => function_exists( 'st_get_list_order_by' ) ? st_get_list_order_by(
                    array(
                        __( 'Sale' , ST_TEXTDOMAIN )          => 'sale' ,
                        __( 'Rate' , ST_TEXTDOMAIN )          => 'rate' ,
                        __( 'Discount rate' , ST_TEXTDOMAIN ) => 'discount'
                    )
                ) : array() ,
            ) ,
            array(
                "type"             => "dropdown" ,
                'admin_label' => true,
                "heading"          => __( "Order" , ST_TEXTDOMAIN ) ,
                "param_name"       => "st_order" ,
                'value'            => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'Asc' , ST_TEXTDOMAIN )        => 'asc' ,
                    __( 'Desc' , ST_TEXTDOMAIN )       => 'desc'
                ) ,
                'edit_field_class' => 'vc_col-sm-6' ,
            ) ,
            array(
                "type"        => "dropdown" ,
                'admin_label' => true,
                "heading"     => __( "Style hotel" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_style_ht" ,
                "description" => "" ,
                'value'       => array(
                    __( '--Select--' , ST_TEXTDOMAIN )          => '' ,
                    __( 'Hot Deals' , ST_TEXTDOMAIN )           => 'hot-deals' ,
                    __( 'Grid' , ST_TEXTDOMAIN )                => 'grid' ,
                    __( 'Grid Style 2' , ST_TEXTDOMAIN )        => 'grid2' ,
                ) ,
            ) ,
            array(
                "type"             => "dropdown" ,
                'admin_label' => true,
                "heading"          => __( "Items per row" , ST_TEXTDOMAIN ) ,
                "param_name"       => "st_ht_of_row" ,
                'edit_field_class' => 'vc_col-sm-12' ,
                "description"      => __( 'Noticed: the field "Items per row" only applicable to "Last Minute Deal" style' , ST_TEXTDOMAIN ) ,
                "value"            => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'Four' , ST_TEXTDOMAIN )       => 4 ,
                    __( 'Three' , ST_TEXTDOMAIN )      => 3 ,
                    __( 'Two' , ST_TEXTDOMAIN )        => 2 ,
                ) ,
            ) ,
            array(
                "type"             => "dropdown" ,
                'admin_label' => true,
                "heading"          => __( "Only in Featured Location" , ST_TEXTDOMAIN ) ,
                "param_name"       => "only_featured_location" ,
                'edit_field_class' => 'vc_col-sm-12' ,
                "value"            => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'No' , ST_TEXTDOMAIN )         => 'no' ,
                    __( 'Yes' , ST_TEXTDOMAIN )        => 'yes' ,
                ) ,
            ) ,
            array(
                "type"        => "st_list_location" ,
                'admin_label' => true,
                "heading"     => __( "Location" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_location" ,
                "description" => __( "Location" , ST_TEXTDOMAIN ) ,
                "dependency"  =>
                    array(
                        "element" => "only_featured_location" ,
                        "value"   => "no"
                    ) ,
            ) ,
        )
    ) );
}
if(!function_exists( 'st_vc_list_hotel' )) {
    function st_vc_list_hotel( $attr , $content = false )
    {
        global $st_search_args;
        $data = shortcode_atts(
            array(
                'st_ids'                 => "" ,
                'st_number_ht'           => 4 ,
                'st_order'               => '' ,
                'st_orderby'             => '' ,
                'st_ht_of_row'           => 4 ,
                'st_style_ht'            => 'hot-deals' ,
                'only_featured_location' => 'no' ,
                'st_location'            => '' ,
            ) , $attr , 'st_list_hotel' );
        extract( $data );
        $st_search_args = $data;
        if($st_style_ht == "last_minute_deals") $st_style_ht = "grid2";
        $query = array(
            'post_type'      => 'st_hotel' ,
            'posts_per_page' => $st_number_ht ,
            'order'          => $st_order ,
            'orderby'        => $st_orderby
        );
        $st_search_args['featured_location']=STLocation::inst()->get_featured_ids();
        $hotel = STHotel::inst();
        $hotel->alter_search_query();
        query_posts($query);
        $r = st()->load_template( 'vc-elements/st-list-hotel/loop' , $st_style_ht , $data );
        $hotel->remove_alter_search_query();
        wp_reset_query();
        return $r;
    }
}
if(st_check_service_available( 'st_hotel' )) {
    st_reg_shortcode( 'st_list_hotel' , 'st_vc_list_hotel' );
}