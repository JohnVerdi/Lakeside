<?php

    if(function_exists('vc_map')){
        $list_location = TravelerObject::get_list_location();
        $list_location_data[__('-- Select --',ST_TEXTDOMAIN)]= '';
        if(!empty($list_location)){
            foreach($list_location as $k=>$v){
                $list_location_data[$v['title']] = $v['id'];
            }
        }
        $st_location_type = array();
        $list_terms = STLocation::get_location_terms();   
        if(!empty($list_terms) and is_array($list_terms)) {
            foreach ($list_terms as $key => $value) {
                $st_location_type[$value->name] = $value->taxonomy."/".$value->term_id;
            }
        }

        vc_map( array(
            "name" => __("ST Slide Location", ST_TEXTDOMAIN),
            "base" => "st_slide_location",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Title", ST_TEXTDOMAIN),
                    "param_name" => "title",
                    "description" =>"",
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Explore type", ST_TEXTDOMAIN),
                    "param_name" => "st_type",
                    "description" =>"",
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Hotel',ST_TEXTDOMAIN)=>'st_hotel',
                        __('Car',ST_TEXTDOMAIN)=>'st_cars',
                        __('Tour',ST_TEXTDOMAIN)=>'st_tours',
                        __('Rental',ST_TEXTDOMAIN)=>'st_rental',
                        __('Activities',ST_TEXTDOMAIN)=>'st_activity',
//                __('Cruise',ST_TEXTDOMAIN)=>'cruise',
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Show Only Featured Location", ST_TEXTDOMAIN),
                    "param_name" => "is_featured",
                    "description" =>__("Show Only Featured Location",ST_TEXTDOMAIN),
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __("No",ST_TEXTDOMAIN)=>'no',
                        __("Yes",ST_TEXTDOMAIN)=>'yes',
                    ),
                ),
                array(
                    "type" => "checkbox",
                    "holder" => "div",
                    "heading" => __("Location type", ST_TEXTDOMAIN),
                    "param_name" => "st_location_type",
                    "description" =>"",
                    "value" => $st_location_type
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Number Location", ST_TEXTDOMAIN),
                    "param_name" => "st_number",
                    "description" =>"",
                    'value'=>4,
                    'dependency' => array(
                        'element' => 'is_featured',
                        'value' => array( 'yes' )
                    ),
                ),
                array(
                    "type" => "st_post_type_location",
                    "holder" => "div",
                    "heading" => __("Select Location ", ST_TEXTDOMAIN),
                    "param_name" => "st_list_location",
                    "description" =>"",
                    'dependency' => array(
                        'element' => 'is_featured',
                        'value' => array( 'no' )
                    ),

                ),

                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Style show ", ST_TEXTDOMAIN),
                    "param_name" => "st_style",
                    "description" =>"",
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Text info center',ST_TEXTDOMAIN)=>'style_1',
                        __('Show Search Box',ST_TEXTDOMAIN)=>'style_2',
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Effect", ST_TEXTDOMAIN),
                    "param_name" => "effect",
                    "description" =>"",
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('None',ST_TEXTDOMAIN)=>'false',
                        __('Fade',ST_TEXTDOMAIN)=>'fade',
                        __('Back Slide',ST_TEXTDOMAIN)=>'backSlide',
                        __('Go Down',ST_TEXTDOMAIN)=>'goDown',
                        __('Fade Up',ST_TEXTDOMAIN)=>'fadeUp',
                    ),
                ),

                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Weather show ", ST_TEXTDOMAIN),
                    "param_name" => "st_weather",
                    "description" =>"",
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Yes',ST_TEXTDOMAIN)=>'yes',
                        __('No',ST_TEXTDOMAIN)=>'no',
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Height", ST_TEXTDOMAIN),
                    'dependency' => array(
                            'element' => 'st_style' ,
                            'value'   => 'style_1'
                        ) ,
                    "param_name" => "st_height",
                    "description" =>"",
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Full height',ST_TEXTDOMAIN)=>'full',
                        __('Half height',ST_TEXTDOMAIN)=>'half',
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Link To", ST_TEXTDOMAIN),
                    "param_name" => "link_to",
                    "description" =>__("Link To",ST_TEXTDOMAIN),
                    'value'=>array(
                        __("Page Search",ST_TEXTDOMAIN)=>'page_search',
                        __("Single",ST_TEXTDOMAIN)=>'single'
                    ),
                    'edit_field_class'=>'vc_col-sm-12',
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Opacity Background", ST_TEXTDOMAIN),
                    "param_name" => "opacity",
                    "description" =>__(" Enter value form 0 - 0.5 - 1 ",ST_TEXTDOMAIN),
                    'value'=>'0.5'
                ),
            )
        ) );
    }

    if(!function_exists('st_vc_slide_location')){
        function st_vc_slide_location($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'st_type'=>'st_hotel',
                    'is_featured' =>'no',
                    'st_number' =>4,
                    'st_list_location' =>0,
                    'st_weather'=>'no',
                    'st_style'=>'style_1',
                    'st_height'=>'full',
                    'effect'=>'fade',
                    'link_to'=>'page_search',
                    'st_location_type'=> '',
                    'title'=>__("Find Your Perfect Trip",ST_TEXTDOMAIN),
                    'opacity'=>'0.5'

                ), $attr, 'st_slide_location' );
            extract($data);
            $query=array(
                'post_type' => 'location',
                'posts_per_page' => -1 ,
            );
            if($is_featured == 'yes'){
                $query['posts_per_page']= $st_number;
                $query['orderby']='meta_value_num';
                $query['meta_query']=array(
                    array(
                        'key'     => 'is_featured',
                        'value'   => 'on',
                        'compare' => '=',
                    ),
                );
            }else{
                $ids = explode(',',$st_list_location);
                if (is_array($ids)){
                    $query['post__in']= $ids ;
                }                
            }
            if ($st_location_type) {
                
                $tax_query = array();
                $st_location_type = explode(',',$st_location_type );
                $tmp = array();
                if (!empty($st_location_type) and is_array($st_location_type)) {
                    foreach ($st_location_type as $key => $value) {                         
                        $value = explode('/', $value);                                          
                        $tmp[] = $value;
                    };
                } 
                $tmp_term  =array();
                $tmp_tax = array();
                if (!empty($tmp) and is_array($tmp)) {
                    foreach ($tmp as $key => $value) {
                    if ($key== 0 or (!in_array($value[0], $tmp_tax))) {
                        $tmp_tax[] = $value[0];
                        $tmp_term[$value[0]] = array($value[1]);
                    }else {
                        if (in_array($value[0], $tmp_tax)) {
                            $type = $value[0] ; 
                            $tmp_term[$type][] = $value[1];
                        } 
                    }
                }   
            };   
                if (!empty($tmp_term) and is_array($tmp_term)){
                    foreach ($tmp_term as $key => $value) {
                        $query['tax_query'][] = array(
                            'taxonomy'  => $key,
                            'field' => 'id' , 
                            'terms' => $value , 
                            'operator'  => "IN"
                            );
                    }
                    $query['tax_query']['relation'] = "AND";
                } 

            } 
            query_posts($query);
            $txt='';
            while(have_posts()){
                the_post();
                if($st_style == 'style_1'){
                    $txt .= st()->load_template('vc-elements/st-slide-location/loop-style','1',$data);
                }
                if($st_style == 'style_2'){
                    $txt .= st()->load_template('vc-elements/st-slide-location/loop-style','2',$data);
                }

            }
            wp_reset_query();
            if($st_height == 'full'){
                $class = 'top-area show-onload';
            }else{
                $class = 'special-area';
            }


            if($st_style == 'style_1') {
                $r = '<div class="'.$class.'">
                    <div class="owl-carousel owl-slider owl-carousel-area" id="owl-carousel-slider" data-effect="'.$effect.'">
                    ' . $txt . '
                    </div>
                  </div>';
            }
            $bgr = "";
            if (!empty($ids[0])){
                $bgr_url = wp_get_attachment_image_src(get_post_thumbnail_id($ids[0]), 'full');
                $bgr_url= $bgr_url[0];
                $bgr = " style='background-image: url(".$bgr_url.")'";        
            }

            if($st_style == 'style_2') {
                $r = '<div class="'.$class.'">
                <div class="bg-holder full st-slider-location"'.$bgr.' >
                    <div class="bg-front bg-front-mob-rel">
                        <div class="container">
                        '.st()->load_template('vc-elements/st-search/search','form',array('st_style_search' =>'style_2', 'st_box_shadow'=>'no' ,'class'=>'search-tabs-abs mt50','title'=>$data['title']) ).'
                        </div>
                    </div>
                    <div class="owl-carousel owl-slider owl-carousel-area visible-lg" id="owl-carousel-slider" data-effect="'.$effect.'">
                      '.$txt.'
                    </div>
                </div>
            </div>';
            }
            if(empty($txt)){
                $r="";
            }
            return $r;
        }
    }
    st_reg_shortcode('st_slide_location','st_vc_slide_location');