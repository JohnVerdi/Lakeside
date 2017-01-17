<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Search Form", ST_TEXTDOMAIN),
            "base" => "st_search_form",
            "as_parent" => array(
                'only' => 'st_search_field_hotel,st_search_field_rental,st_search_field_car,st_search_field_tour,st_search_field_activity',
            ),
            "content_element" => true,
            "show_settings_on_create" => true,
            "js_view" => 'VcColumnView',
            "icon" => "icon-st",
            'params'=>array(
                array(
                    "type" => "textfield",
                    "heading" => __("Title Form", ST_TEXTDOMAIN),
                    "param_name" => "st_title_form",
                    "description" => __("Title Form",ST_TEXTDOMAIN),
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Post Type", ST_TEXTDOMAIN),
                    "param_name" => "st_post_type",
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( 'Hotel' , ST_TEXTDOMAIN ) => 'st_hotel' ,
                        __( 'Rental' , ST_TEXTDOMAIN )     => 'st_rental' ,
                        __( 'Car' , ST_TEXTDOMAIN )        => 'st_cars' ,
                        __( 'Tour' , ST_TEXTDOMAIN )       => 'st_tours' ,
                        __( 'Activity' , ST_TEXTDOMAIN )   => 'st_activity' ,
                    ) ,
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Button search text", ST_TEXTDOMAIN),
                    "param_name" => "st_button_search",
                    "description" => __("Button search text",ST_TEXTDOMAIN),
                    "value"=>__("Search",ST_TEXTDOMAIN),
                ),
            )
        ) );

        /*
         * HOTEL
         * */
        vc_map( array(
            "name" => __("ST Search Field Hotel", ST_TEXTDOMAIN),
            "base" => "st_search_field_hotel",
            "content_element" => true,
            "as_child" => array('only' => 'st_search_form'),
            "icon" => "icon-st",
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Title", ST_TEXTDOMAIN),
                    "param_name" => "st_title",
                    "description" => __("Title field",ST_TEXTDOMAIN),
                ),
                array(
                    "type" => "dropdown",
                    "heading" =>"Select field",
                    "param_name" => "st_select_field",
                    "description" => __("Select field",ST_TEXTDOMAIN),
                    "value" =>TravelHelper::st_get_field_search("st_hotel"),
                ),
                array(
                    "type" => "dropdown",
                    "heading" =>"Select taxonomy",
                    "param_name" => "st_select_taxonomy",
                    "description" => __("Select taxonomy",ST_TEXTDOMAIN),
                    "value" =>st_get_post_taxonomy('st_hotel',false),
                    'dependency' => array(
                        'element' => 'st_select_field',
                        'value' => array( 'taxonomy' )
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Size Col", ST_TEXTDOMAIN),
                    "param_name" => "st_col",
                    "description" => __("Size Col",ST_TEXTDOMAIN),
                    'value'=>array(
                        __("1 column",ST_TEXTDOMAIN)=>'col-md-1',
                        __("2 columns",ST_TEXTDOMAIN)=>'col-md-2',
                        __("3 columns",ST_TEXTDOMAIN)=>'col-md-3',
                        __("4 columns",ST_TEXTDOMAIN)=>'col-md-4',
                        __("5 columns",ST_TEXTDOMAIN)=>'col-md-5',
                        __("6 columns",ST_TEXTDOMAIN)=>'col-md-6',
                        __("7 columns",ST_TEXTDOMAIN)=>'col-md-7',
                        __("8 columns",ST_TEXTDOMAIN)=>'col-md-8',
                        __("9 columns",ST_TEXTDOMAIN)=>'col-md-9',
                        __("10 columns",ST_TEXTDOMAIN)=>'col-md-10',
                        __("11 columns",ST_TEXTDOMAIN)=>'col-md-11',
                        __("12 columns",ST_TEXTDOMAIN)=>'col-md-12',
                    ),
                ),
            )
        ) );
        /*
         * RENTAL
         * */
        vc_map( array(
            "name" => __("ST Search Field Rental", ST_TEXTDOMAIN),
            "base" => "st_search_field_rental",
            "content_element" => true,
            "as_child" => array('only' => 'st_search_form'),
            "icon" => "icon-st",
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Title", ST_TEXTDOMAIN),
                    "param_name" => "st_title",
                    "description" => __("Title field",ST_TEXTDOMAIN),
                ),
                array(
                    "type" => "dropdown",
                    "heading" =>"Select field",
                    "param_name" => "st_select_field",
                    "description" => __("Select field",ST_TEXTDOMAIN),
                    "value" =>TravelHelper::st_get_field_search("st_rental"),
                ),
                array(
                    "type" => "dropdown",
                    "heading" =>"Select taxonomy",
                    "param_name" => "st_select_taxonomy",
                    "description" => __("Select taxonomy",ST_TEXTDOMAIN),
                    "value" =>st_get_post_taxonomy('st_rental',false),
                    'dependency' => array(
                        'element' => 'st_select_field',
                        'value' => array( 'taxonomy' )
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Size Col", ST_TEXTDOMAIN),
                    "param_name" => "st_col",
                    "description" => __("Size Col",ST_TEXTDOMAIN),
                    'value'=>array(
                        __("1 column",ST_TEXTDOMAIN)=>'col-md-1',
                        __("2 columns",ST_TEXTDOMAIN)=>'col-md-2',
                        __("3 columns",ST_TEXTDOMAIN)=>'col-md-3',
                        __("4 columns",ST_TEXTDOMAIN)=>'col-md-4',
                        __("5 columns",ST_TEXTDOMAIN)=>'col-md-5',
                        __("6 columns",ST_TEXTDOMAIN)=>'col-md-6',
                        __("7 columns",ST_TEXTDOMAIN)=>'col-md-7',
                        __("8 columns",ST_TEXTDOMAIN)=>'col-md-8',
                        __("9 columns",ST_TEXTDOMAIN)=>'col-md-9',
                        __("10 columns",ST_TEXTDOMAIN)=>'col-md-10',
                        __("11 columns",ST_TEXTDOMAIN)=>'col-md-11',
                        __("12 columns",ST_TEXTDOMAIN)=>'col-md-12',
                    ),
                ),
            )
        ) );
        /*
        * CAR
        * */
        vc_map( array(
            "name" => __("ST Search Field Car", ST_TEXTDOMAIN),
            "base" => "st_search_field_car",
            "content_element" => true,
            "as_child" => array('only' => 'st_search_form'),
            "icon" => "icon-st",
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Title", ST_TEXTDOMAIN),
                    "param_name" => "st_title",
                    "description" => __("Title field",ST_TEXTDOMAIN),
                ),
                array(
                    "type" => "dropdown",
                    "heading" =>"Select field",
                    "param_name" => "st_select_field",
                    "description" => __("Select field",ST_TEXTDOMAIN),
                    "value" =>TravelHelper::st_get_field_search("st_cars"),
                ),
                array(
                    "type" => "dropdown",
                    "heading" =>"Select taxonomy",
                    "param_name" => "st_select_taxonomy",
                    "description" => __("Select taxonomy",ST_TEXTDOMAIN),
                    "value" =>st_get_post_taxonomy('st_cars',false),
                    'dependency' => array(
                        'element' => 'st_select_field',
                        'value' => array( 'taxonomy' )
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Size Col", ST_TEXTDOMAIN),
                    "param_name" => "st_col",
                    "description" => __("Size Col",ST_TEXTDOMAIN),
                    'value'=>array(
                        __("1 column",ST_TEXTDOMAIN)=>'col-md-1',
                        __("2 columns",ST_TEXTDOMAIN)=>'col-md-2',
                        __("3 columns",ST_TEXTDOMAIN)=>'col-md-3',
                        __("4 columns",ST_TEXTDOMAIN)=>'col-md-4',
                        __("5 columns",ST_TEXTDOMAIN)=>'col-md-5',
                        __("6 columns",ST_TEXTDOMAIN)=>'col-md-6',
                        __("7 columns",ST_TEXTDOMAIN)=>'col-md-7',
                        __("8 columns",ST_TEXTDOMAIN)=>'col-md-8',
                        __("9 columns",ST_TEXTDOMAIN)=>'col-md-9',
                        __("10 columns",ST_TEXTDOMAIN)=>'col-md-10',
                        __("11 columns",ST_TEXTDOMAIN)=>'col-md-11',
                        __("12 columns",ST_TEXTDOMAIN)=>'col-md-12',
                    ),
                ),

            )
        ) );
        /*
        * TOUR
        * */
        vc_map( array(
            "name" => __("ST Search Field Tour", ST_TEXTDOMAIN),
            "base" => "st_search_field_tour",
            "content_element" => true,
            "as_child" => array('only' => 'st_search_form'),
            "icon" => "icon-st",
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Title", ST_TEXTDOMAIN),
                    "param_name" => "st_title",
                    "description" => __("Title field",ST_TEXTDOMAIN),
                ),
                array(
                    "type" => "dropdown",
                    "heading" =>"Select field",
                    "param_name" => "st_select_field",
                    "description" => __("Select field",ST_TEXTDOMAIN),
                    "value" =>TravelHelper::st_get_field_search("st_tours"),
                ),
                array(
                    "type" => "dropdown",
                    "heading" =>"Select taxonomy",
                    "param_name" => "st_select_taxonomy",
                    "description" => __("Select taxonomy",ST_TEXTDOMAIN),
                    "value" =>st_get_post_taxonomy('st_tours',false),
                    'dependency' => array(
                        'element' => 'st_select_field',
                        'value' => array( 'taxonomy' )
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Size Col", ST_TEXTDOMAIN),
                    "param_name" => "st_col",
                    "description" => __("Size Col",ST_TEXTDOMAIN),
                    'value'=>array(
                        __("1 column",ST_TEXTDOMAIN)=>'col-md-1',
                        __("2 columns",ST_TEXTDOMAIN)=>'col-md-2',
                        __("3 columns",ST_TEXTDOMAIN)=>'col-md-3',
                        __("4 columns",ST_TEXTDOMAIN)=>'col-md-4',
                        __("5 columns",ST_TEXTDOMAIN)=>'col-md-5',
                        __("6 columns",ST_TEXTDOMAIN)=>'col-md-6',
                        __("7 columns",ST_TEXTDOMAIN)=>'col-md-7',
                        __("8 columns",ST_TEXTDOMAIN)=>'col-md-8',
                        __("9 columns",ST_TEXTDOMAIN)=>'col-md-9',
                        __("10 columns",ST_TEXTDOMAIN)=>'col-md-10',
                        __("11 columns",ST_TEXTDOMAIN)=>'col-md-11',
                        __("12 columns",ST_TEXTDOMAIN)=>'col-md-12',
                    ),
                ),

            )
        ) );
        /*
        * ACTIVITY
        * */
        vc_map( array(
            "name" => __("ST Search Field Activity", ST_TEXTDOMAIN),
            "base" => "st_search_field_activity",
            "content_element" => true,
            "as_child" => array('only' => 'st_search_form'),
            "icon" => "icon-st",
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Title", ST_TEXTDOMAIN),
                    "param_name" => "st_title",
                    "description" => __("Title field",ST_TEXTDOMAIN),
                ),
                array(
                    "type" => "dropdown",
                    "heading" =>"Select field",
                    "param_name" => "st_select_field",
                    "description" => __("Select field",ST_TEXTDOMAIN),
                    "value" =>TravelHelper::st_get_field_search("st_activity"),
                ),
                array(
                    "type" => "dropdown",
                    "heading" =>"Select taxonomy",
                    "param_name" => "st_select_taxonomy",
                    "description" => __("Select taxonomy",ST_TEXTDOMAIN),
                    "value" =>st_get_post_taxonomy('st_activity',false),
                    'dependency' => array(
                        'element' => 'st_select_field',
                        'value' => array( 'taxonomy' )
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Size Col", ST_TEXTDOMAIN),
                    "param_name" => "st_col",
                    "description" => __("Size Col",ST_TEXTDOMAIN),
                    'value'=>array(
                        __("1 column",ST_TEXTDOMAIN)=>'col-md-1',
                        __("2 columns",ST_TEXTDOMAIN)=>'col-md-2',
                        __("3 columns",ST_TEXTDOMAIN)=>'col-md-3',
                        __("4 columns",ST_TEXTDOMAIN)=>'col-md-4',
                        __("5 columns",ST_TEXTDOMAIN)=>'col-md-5',
                        __("6 columns",ST_TEXTDOMAIN)=>'col-md-6',
                        __("7 columns",ST_TEXTDOMAIN)=>'col-md-7',
                        __("8 columns",ST_TEXTDOMAIN)=>'col-md-8',
                        __("9 columns",ST_TEXTDOMAIN)=>'col-md-9',
                        __("10 columns",ST_TEXTDOMAIN)=>'col-md-10',
                        __("11 columns",ST_TEXTDOMAIN)=>'col-md-11',
                        __("12 columns",ST_TEXTDOMAIN)=>'col-md-12',
                    ),
                ),

            )
        ) );
    }
    if ( class_exists( 'WPBakeryShortCodesContainer' ) and !class_exists('WPBakeryShortCode_st_search_form')) {
        class WPBakeryShortCode_st_search_form extends WPBakeryShortCodesContainer {
            protected function content($arg, $content = null) {
                $data = shortcode_atts(array(
                    'st_title_form'=>'',
                    'st_post_type'=>"st_hotel",
                    'st_button_search'=>__("Search",ST_TEXTDOMAIN)
                ), $arg,'st_search_form');
                extract($data);
                $content = st_remove_wpautop($content);
                $text='  <h2>'.$st_title_form.'</h2>
                         <form role="search" method="get" class="search main-search" action="'.home_url( '/' ).'">
                                '.TravelHelper::get_input_multilingual_wpml().'
                                <input type="hidden" name="s" value="">
                                <input type="hidden" name="post_type" value="'.$st_post_type.'">
                                <input type="hidden" name="layout" value="'.STInput::get('layout').'">
                                   <div class="row">'.$content.'</div>
                                <button class="btn btn-primary btn-lg" type="submit">'.$st_button_search.'</button>
                         </form>';
                return $text;
            }
        }
    }
    if ( class_exists( 'WPBakeryShortCode' )and !class_exists('WPBakeryShortCode_st_search_field_hotel') ) {
        class WPBakeryShortCode_st_search_field_hotel extends WPBakeryShortCode {
            protected function content($arg, $content = null) {
                $data = shortcode_atts(array(
                    'st_title'=>'',
                    'st_col'=>"col-md-1",
                    'st_select_field'=>"st_hotel",
                    'st_select_taxonomy'=>"",
                ), $arg,'st_search_field_hotel');
                extract($data);

                $default =array(
                    'title'=>$st_title,
                    'taxonomy'=>$st_select_taxonomy
                );
                $text = '<div class="'.$st_col.'">
                            '.st()->load_template('hotel/elements/search/field_'.$st_select_field,false,array('data'=>$default,'field_size'=>'lg')).'
                        </div>';
                return $text;
            }
        }
    }
    if ( class_exists( 'WPBakeryShortCode' )and !class_exists('WPBakeryShortCode_st_search_field_rental') ) {
        class WPBakeryShortCode_st_search_field_rental extends WPBakeryShortCode {
            protected function content($arg, $content = null) {
                $data = shortcode_atts(array(
                    'st_title'=>'',
                    'st_col'=>"col-md-1",
                    'st_select_field'=>"st_hotel",
                    'st_select_taxonomy'=>"",
                ), $arg,'st_search_field_rental');
                extract($data);

                $default =array(
                    'title'=>$st_title,
                    'taxonomy'=>$st_select_taxonomy
                );
                $text = '<div class="'.$st_col.'">
                            '.st()->load_template('hotel/elements/search/field_'.$st_select_field,false,array('data'=>$default,'field_size'=>'lg')).'
                        </div>';
                return $text;
            }
        }
    }
    if ( class_exists( 'WPBakeryShortCode' )and !class_exists('WPBakeryShortCode_st_search_field_car') ) {
        class WPBakeryShortCode_st_search_field_car extends WPBakeryShortCode {
            protected function content($arg, $content = null) {
                $data = shortcode_atts(array(
                    'st_title'=>'',
                    'st_col'=>"col-md-1",
                    'st_select_field'=>"st_hotel",
                    'st_select_taxonomy'=>"",
                ), $arg,'st_search_field_car');
                extract($data);

                $default =array(
                    'title'=>$st_title,
                    'taxonomy'=>$st_select_taxonomy
                );
                $text = '<div class="'.$st_col.'">
                            '.st()->load_template('cars/elements/search/field-'.$st_select_field,false,array('data'=>$default,'field_size'=>'lg')).'
                        </div>';
                return $text;
            }
        }
    }
    if ( class_exists( 'WPBakeryShortCode' )and !class_exists('WPBakeryShortCode_st_search_field_tour') ) {
        class WPBakeryShortCode_st_search_field_tour extends WPBakeryShortCode {
            protected function content($arg, $content = null) {
                $data = shortcode_atts(array(
                    'st_title'=>'',
                    'st_col'=>"col-md-1",
                    'st_select_field'=>"st_hotel",
                    'st_select_taxonomy'=>"",
                ), $arg,'st_search_field_tour');
                extract($data);

                $default =array(
                    'title'=>$st_title,
                    'taxonomy'=>$st_select_taxonomy
                );
                $text = '<div class="'.$st_col.'">
                            '.st()->load_template('tours/elements/search/field-'.$st_select_field,false,array('data'=>$default,'field_size'=>'lg')).'
                        </div>';
                return $text;
            }
        }
    }
    if ( class_exists( 'WPBakeryShortCode' )and !class_exists('WPBakeryShortCode_st_search_field_activity') ) {
        class WPBakeryShortCode_st_search_field_activity extends WPBakeryShortCode {
            protected function content($arg, $content = null) {
                $data = shortcode_atts(array(
                    'st_title'=>'',
                    'st_col'=>"col-md-1",
                    'st_select_field'=>"st_hotel",
                    'st_select_taxonomy'=>"",
                ), $arg,'st_search_field_activity');
                extract($data);

                $default =array(
                    'title'=>$st_title,
                    'taxonomy'=>$st_select_taxonomy
                );
                $text = '<div class="'.$st_col.'">
                            '.st()->load_template('activity/elements/search/field-'.$st_select_field,false,array('data'=>$default,'field_size'=>'lg')).'
                        </div>';
                return $text;
            }
        }
    }