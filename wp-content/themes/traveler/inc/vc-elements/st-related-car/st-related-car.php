<?php
if(!st_check_service_available( 'st_cars' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    $list_taxonomy = st_list_taxonomy( 'st_cars' );
    //$list_taxonomy = array_merge( array( "---Select---" => "" ) , $list_taxonomy );

    $params  = array(
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
            "type"        => "textfield" ,
            "holder"      => "div" ,
            "heading"     => __( "List ID in Tour" , ST_TEXTDOMAIN ) ,
            "param_name"  => "st_ids" ,
            "description" => __( "Ids separated by commas" , ST_TEXTDOMAIN ) ,
            'value'       => "" ,
        ) ,
        array(
            "type"             => "textfield" ,
            "holder"           => "div" ,
            "heading"          => __( "Number of Posts" , ST_TEXTDOMAIN ) ,
            "param_name"       => "posts_per_page" ,
            "description"      => "" ,
            "value"            => "" ,
            'edit_field_class' => 'vc_col-sm-6' ,
        ) ,
        array(
            "type"        => "dropdown" ,
            "holder"      => "div" ,
            "heading"     => __( "Sort By Taxonomy" , ST_TEXTDOMAIN ) ,
            "param_name"  => "sort_taxonomy" ,
            "description" => "" ,
            "value"       => $list_taxonomy ,
        ) ,
        array(
            "type"             => "dropdown" ,
            "holder"           => "div" ,
            "heading"          => __( "Order By" , ST_TEXTDOMAIN ) ,
            "param_name"       => "st_orderby" ,
            "description"      => "" ,
            'edit_field_class' => 'vc_col-sm-6' ,
            'value'            => function_exists( 'st_get_list_order_by' ) ? st_get_list_order_by() : array() ,
        ) ,
        array(
            "type"             => "dropdown" ,
            "holder"           => "div" ,
            "heading"          => __( "Order" , ST_TEXTDOMAIN ) ,
            "param_name"       => "st_order" ,
            'value'            => array(
                __('--Select--',ST_TEXTDOMAIN)=>'',
                __( 'Asc' , ST_TEXTDOMAIN )  => 'asc' ,
                __( 'Desc' , ST_TEXTDOMAIN ) => 'desc'
            ) ,
            'edit_field_class' => 'vc_col-sm-6' ,
        ) ,
    );
    $data_vc = STCars::get_taxonomy_and_id_term_tour();
    $params  = array_merge( $params , $data_vc[ 'list_vc' ] );
    vc_map( array(
        "name"            => __( "ST List cars related" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_car_related" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        "params"          => $params
    ) );
}
if(!function_exists( 'st_list_car_related' )) {
    function st_list_car_related( $attr , $content = false )
    {
        global $st_search_args;
        $data_vc = STCars::get_taxonomy_and_id_term_tour();
        $param   = array(
            'title'          => '' ,
            'st_ids'         => '' ,
            'sort_taxonomy'  => '' ,
            'posts_per_page' => 3 ,
            'st_orderby'     => 'ID' ,
            'st_order'       => 'DESC' ,
            'font_size'      => '3' ,
            'number_of_row'  => 1
        );
        $param   = array_merge( $param , $data_vc[ 'list_id_vc' ] );
        $data    = shortcode_atts(
            $param , $attr , 'st_list_car_related' );
        extract( $data );
        $st_search_args = $data;
        $page           = STInput::request( 'paged' );
        if(!$page) {
            $page = get_query_var( 'paged' );
        }
        $query = array(
            'post_type'      => 'st_cars' ,
            'posts_per_page' => $posts_per_page ,
            'post_status'    => 'publish' ,
            'paged'          => $page ,
            'order'          => $st_order ,
            'orderby'        => $st_orderby ,
            'post__not_in'   => array( get_the_ID() )
        );
        $cars  = STCars::get_instance();
        $cars->alter_search_query();
        query_posts( $query );
        $r = "<div class='list_car_related'>" . st()->load_template( 'vc-elements/st-list-cars/loop-list' , '2' , array() ) . "</div>";
        wp_reset_query();
        wp_reset_query();
        $cars->remove_alter_search_query();
        $st_search_args = null;
        if(!empty( $title ) and !empty( $r )) {
            $r = '<h' . $font_size . '>' . $title . '</h' . $font_size . '>' . $r;
        }
        return $r;
    }
}
if(st_check_service_available( 'st_cars' )) {
    st_reg_shortcode( 'st_list_car_related' , 'st_list_car_related' );
}