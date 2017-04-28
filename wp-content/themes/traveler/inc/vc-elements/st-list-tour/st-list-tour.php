<?php
if(!st_check_service_available( 'st_tours' )) {
    return;
}
if(function_exists( 'vc_map' ) and class_exists('TravelerObject')) {
    $list_taxonomy = st_list_taxonomy( 'st_tours' );

    $list_location                                              = TravelerObject::get_list_location();
    $list_location_data[ __( '-- Select --' , ST_TEXTDOMAIN ) ] = '';
    if(!empty( $list_location )) {
        foreach( $list_location as $k => $v ) {
            $list_location_data[ $v[ 'title' ] ] = $v[ 'id' ];
        }
    }

    $params  = array(
        array(
            "type"             => "textfield" ,
            'admin_label' => true,
            "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
            "param_name"       => "title" ,
            "description"      => "" ,
            "value"            => "" ,
            'edit_field_class' => 'vc_col-sm-6' ,
        ) ,
        array(
            "type"             => "dropdown" ,
            'admin_label' => true,
            "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
            "param_name"       => "font_size" ,
            "description"      => "" ,
            "value"            => array(
                __('--Select--',ST_TEXTDOMAIN)=>'',
                __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                __( "H5" , ST_TEXTDOMAIN ) => '5' ,
            ) ,
            'edit_field_class' => 'vc_col-sm-6' ,
        ) ,
        array(
            "type"        => "textfield" ,
            'admin_label' => true,
            "heading"     => __( "List ID in Tour" , ST_TEXTDOMAIN ) ,
            "param_name"  => "st_ids" ,
            "description" => __( "Ids separated by commas" , ST_TEXTDOMAIN ) ,
            'value'       => "" ,
        ) ,
        array(
            "type"        => "textfield" ,
            'admin_label' => true,
            "heading"     => __( "Number tour" , ST_TEXTDOMAIN ) ,
            "param_name"  => "st_number_tour" ,
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
                    __( 'Price' , ST_TEXTDOMAIN )            => 'sale' ,
                    __( 'Rate' , ST_TEXTDOMAIN )             => 'rate' ,
                    /*__( 'Discount rate' , ST_TEXTDOMAIN )    => 'discount' ,*/
                    //__( 'Last Minute Deal' , ST_TEXTDOMAIN ) => 'last_minute_deal' ,
                )
            ) : array() ,
        ) ,
        array(
            "type"             => "dropdown" ,
            'admin_label' => true,
            "heading"          => __( "Order" , ST_TEXTDOMAIN ) ,
            "param_name"       => "st_order" ,
            'value'            => array(
                __('--Select--',ST_TEXTDOMAIN)=>'',
                __( 'Asc' , ST_TEXTDOMAIN )  => 'asc' ,
                __( 'Desc' , ST_TEXTDOMAIN ) => 'desc'
            ) ,
            'edit_field_class' => 'vc_col-sm-6' ,
        ) ,
        array(
            "type"        => "dropdown" ,
            'admin_label' => true,
            "heading"     => __( "Style Tour" , ST_TEXTDOMAIN ) ,
            "param_name"  => "st_style" ,
            "description" => "" ,
            'value'       => array(
                __('--Select--',ST_TEXTDOMAIN)=>'',
                __( 'Style 1' , ST_TEXTDOMAIN ) => 'style_1' ,
                __( 'Style 2' , ST_TEXTDOMAIN ) => 'style_2' ,
                __( 'Style 3' , ST_TEXTDOMAIN ) => 'style_3' ,
                __( 'Style 4' , ST_TEXTDOMAIN ) => 'style_4' ,
            ) ,
        ) ,
        array(
            "type"             => "dropdown" ,
            'admin_label' => true,
            "heading"          => __( "Items per row" , ST_TEXTDOMAIN ) ,
            "param_name"       => "st_tour_of_row" ,
            'edit_field_class' => 'vc_col-sm-12' ,
            "description"=>__("only for style 1 , style 2 , style 3",ST_TEXTDOMAIN),
            "value"            => array(
                __('--Select--',ST_TEXTDOMAIN)=>'',
                __( 'Four' , ST_TEXTDOMAIN )  => '4' ,
                __( 'Three' , ST_TEXTDOMAIN ) => '3' ,
                __( 'Two' , ST_TEXTDOMAIN )   => '2' ,
            ) ,
        ) ,
        array(
            "type"             => "dropdown" ,
            'admin_label' => true,
            "heading"          => __( "Only in Featured Location" , ST_TEXTDOMAIN ) ,
            "param_name"       => "only_featured_location" ,
            "value"            => array(
                __('--Select--',ST_TEXTDOMAIN)=>'',
                __( 'No' , ST_TEXTDOMAIN )  => 'no' ,
                __( 'Yes' , ST_TEXTDOMAIN ) => 'yes' ,
            ) ,
        ) ,
        array(
            "type"        => "st_list_location" ,
            'admin_label' => true,
            "heading"     => __( "Location" , ST_TEXTDOMAIN ) ,
            "param_name"  => "st_location" ,
            "description" => __( "Location" , ST_TEXTDOMAIN ) ,
            "dependency"    =>
                array(
                    "element"   => "only_featured_location",
                    "value"     => "no"
                ),
        ) ,
        array(
            "type"        => "dropdown" ,
            'admin_label' => true,
            "heading"     => __( "Sort By Taxonomy" , ST_TEXTDOMAIN ) ,
            "param_name"  => "sort_taxonomy" ,
            "description" => "" ,
            "value"       => $list_taxonomy ,
        ) ,
    );
    $data_vc = STTour::get_taxonomy_and_id_term_tour();
    $params  = array_merge( $params , $data_vc[ 'list_vc' ] );
    vc_map( array(
        "name"            => __( "ST List Tour" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_tour" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        "params"          => $params
    ) );
}
if(!function_exists( 'st_vc_list_tour' )) {
    function st_vc_list_tour( $attr , $content = false )
    {
        $data_vc = STTour::get_taxonomy_and_id_term_tour();
		global $st_search_args;
        $param   = array(
            'st_ids'                 => '' ,
            'st_number_tour'         => 4 ,
            'st_order'               => '' ,
            'st_orderby'             => '' ,
            'st_tour_of_row'         => '' ,
            'st_style'               => 'style_1' ,
            'only_featured_location' => 'no' ,
            'st_location'            => '' ,
            'sort_taxonomy'          => '' ,
            'title'                  => '' ,
            'font_size'              => '3' ,
        );
        $param   = array_merge( $param , $data_vc[ 'list_id_vc' ] );
        $data    = shortcode_atts( $param , $attr , 'st_list_tour' );
        extract( $data );
		$st_search_args=$data;

        $page = STInput::request( 'paged' );
        if(!$page) {
            $page = get_query_var( 'paged' );
        }
        $query = array(
            'post_type'      => 'st_tours' ,
            'posts_per_page' => $st_number_tour ,
            'paged'          => $page ,
            'order'          => $st_order ,
            'orderby'        => $st_orderby,
        );

		$st_search_args['featured_location']=STLocation::inst()->get_featured_ids();
		$tour=STTour::get_instance();
		$tour->alter_search_query();
        query_posts( $query );
        global $wp_query;
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
        wp_reset_query();
		$tour->remove_alter_search_query();
		$st_search_args=null;

        if(!empty( $title ) and !empty( $r )) {
            $r = '<h' . $font_size . '>' . $title . '</h' . $font_size . '>' . $r;
        }
        return $r;
    }
}
if(st_check_service_available( 'st_tours' )) {
    st_reg_shortcode( 'st_list_tour' , 'st_vc_list_tour' );
}
