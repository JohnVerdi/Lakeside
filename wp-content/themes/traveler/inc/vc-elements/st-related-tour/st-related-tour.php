<?php
if(!st_check_service_available( 'st_tours' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    $list_taxonomy = st_list_taxonomy( 'st_tours' );
    $params  = array(
        array(
            "type"             => "textfield" ,
            "holder"           => "div" ,
            "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
            "param_name"       => "title" ,
            "description"      => "" ,
            "value"            => "" ,
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
    $data_vc = STTour::get_taxonomy_and_id_term_tour();
    $params  = array_merge( $params , $data_vc[ 'list_vc' ] );
    vc_map( array(
        "name"            => __( "ST List Tour related" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_tour_related" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        "params"          => $params
    ) );
}
if(!function_exists( 'st_list_tour_related' )) {
    function st_list_tour_related( $attr , $content = false )
    {
        global $st_search_args;
        $data_vc = STTour::get_taxonomy_and_id_term_tour();
        $param   = array(
            'title'          => '' ,
            'st_ids'                 => '' ,
            'sort_taxonomy'  => '' ,
            'posts_per_page' => 3 ,
            'st_orderby' =>'ID' ,
            'st_order'=>'DESC',
            'st_style'       => 'style_4' ,
            'font_size'      => '3' ,
        );
        $param   = array_merge( $param , $data_vc[ 'list_id_vc' ] );
        $data    = shortcode_atts(
            $param , $attr , 'st_list_tour_related' );
        extract( $data );
        $st_search_args = $data;
        $page = STInput::request( 'paged' );
        if(!$page) {
            $page = get_query_var( 'paged' );
        }
        $query = array(
            'post_type'      => 'st_tours' ,
            'posts_per_page' => $posts_per_page ,
            'post_status'    => 'publish' ,
            'paged'          => $page ,
            'order'          =>  $st_order,
            'orderby'        => $st_orderby,
            'post__not_in'   => array( get_the_ID() )
        );
        $tour = STTour::get_instance();
        $tour->alter_search_query();
        query_posts( $query );
        if($st_style == 'style_1') {
            $r = "<div class='list_tours'>" . st()->load_template( 'vc-elements/st-list-tour/loop' , '' , $data ) . "</div>";
        }
        if($st_style == 'style_2') {
            $r = "<div class='list_tours'>" . st()->load_template( 'vc-elements/st-list-tour/loop2' , '' , $data ) . "</div>";
        }
        if($st_style == 'style_3') {
            $r = "<div class='list_tours'>" . st()->load_template( 'vc-elements/st-list-tour/loop3' , '' , $data ) . "</div>";
        }
        if($st_style == 'style_4') {
            $r = "<div class='list_tours'>" . st()->load_template( 'vc-elements/st-list-tour/loop4' , '' , $data ) . "</div>";
        }
        $tour->remove_alter_search_query();
        wp_reset_query();
        if(!empty( $title ) and !empty( $r )) {
            $r = '<h' . $font_size . '>' . $title . '</h' . $font_size . '>' . $r;
        }
        return $r;
    }
}
if(st_check_service_available( 'st_tours' )) {
    st_reg_shortcode( 'st_list_tour_related' , 'st_list_tour_related' );
}