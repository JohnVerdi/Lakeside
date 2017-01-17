<?php
    $list1 = (STLocation::get_post_type_list_active());
    $location_ids = '';
    $st_types = '';
    $list = array();
    $list = array(  __('--Select--',ST_TEXTDOMAIN)=>'' ) ; 
    if (!empty($list1) and is_array($list1)){
        foreach ($list1 as $key => $value) {
            if ($value =='st_cars'){
                $list[  __('Car',ST_TEXTDOMAIN)] = $value;
            }    
            if ($value =='st_tours'){
                $list[  __('Tour',ST_TEXTDOMAIN)] = $value;
            } 
            if ($value =='st_hotel'){
                $list[  __('Hotel',ST_TEXTDOMAIN)] = $value;
            } 
            if ($value =='st_rental'){
                $list[  __('Rental',ST_TEXTDOMAIN)] = $value;
            } 
            if ($value =='st_activity'){
                $list[  __('Activity',ST_TEXTDOMAIN)] = $value;
            }   
        }
    }  
    $st_location_type = array();
    $list_terms = STLocation::get_location_terms();   
    if(!empty($list_terms) and is_array($list_terms)) {
        foreach ($list_terms as $key => $value) {
            $st_location_type[$value->name] = $value->taxonomy."/".$value->term_id;
        }
    } 
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST List of Location", ST_TEXTDOMAIN),
            "base" => "st_list_location",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("List IDs in Location", ST_TEXTDOMAIN),
                    "param_name" => "st_ids",
                    "description" =>__("Ids separated by commas",ST_TEXTDOMAIN),
                    'value'=>"",
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
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Post type", ST_TEXTDOMAIN),
                    "param_name" => "st_type",
                    "description" =>"",
                    'value'=>$list,
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
                    'edit_field_class'=>'vc_col-sm-6',
                ),

                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Number Location", ST_TEXTDOMAIN),
                    "param_name" => "st_number",
                    "description" =>__("Number Location", ST_TEXTDOMAIN),
                    'value'=>4,
                    'edit_field_class'=>'vc_col-sm-6',
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
                    'edit_field_class'=>'vc_col-sm-6 clear',
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Items per row", ST_TEXTDOMAIN),
                    "param_name" => "st_col",
                    "description" =>"",
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Four',ST_TEXTDOMAIN)=>'4',
                        __('Three',ST_TEXTDOMAIN)=>'3',
                        __('Two',ST_TEXTDOMAIN)=>'2',
                    ),
                    'edit_field_class'=>'vc_col-sm-6',
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Style location",ST_TEXTDOMAIN),
                    "param_name" => "st_style",
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Normal',ST_TEXTDOMAIN)=>'normal',
                        __('Curved',ST_TEXTDOMAIN)=>'curved',
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Show Logo",ST_TEXTDOMAIN),
                    "param_name" => "st_show_logo",
                    'edit_field_class'=>'vc_col-sm-6',
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Yes',ST_TEXTDOMAIN)=>'yes',
                        __('No',ST_TEXTDOMAIN)=>'no',
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Logo Position",ST_TEXTDOMAIN),
                    "param_name" => "st_logo_position",
                    'edit_field_class'=>'vc_col-sm-6',
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Left',ST_TEXTDOMAIN)=>'left',
                        __('Right',ST_TEXTDOMAIN)=>'right',
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Order By", ST_TEXTDOMAIN),
                    "param_name" => "st_orderby",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-6',
                    'value'=>function_exists('st_get_list_order_by')? st_get_list_order_by(
                        array(
                            __('Sale',ST_TEXTDOMAIN) => 'sale' ,
                            __('Rate',ST_TEXTDOMAIN) => 'rate',
                            __('Min Price',ST_TEXTDOMAIN) => 'price',
                        )
                    ):array(),
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Order",ST_TEXTDOMAIN),
                    "param_name" => "st_order",
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Asc',ST_TEXTDOMAIN)=>'asc',
                        __('Desc',ST_TEXTDOMAIN)=>'desc'
                    ),
                    'edit_field_class'=>'vc_col-sm-6',
                ),
            )
        ) );
    }

    if(!function_exists('st_vc_list_location')){
        function st_vc_list_location($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'st_ids' =>"",
                    'st_type'=>'st_hotel',
                    'is_featured' =>'no',
                    'st_number' =>4,
                    'link_to' =>'page_search',
                    'st_col'=>4,
                    'st_style'=>'normal',
                    'st_show_logo'=>'yes',
                    'st_logo_position'=>'left',
                    'st_orderby'=>'',
                    'st_order'=>'asc',
                    'st_location_type'=> ''
                ), $attr, 'st_list_location' );

            extract($data);

            $query=array(
                'post_type' => 'location',
                'posts_per_page'=>$st_number,
                'order'=>$st_order,
                'orderby'=>$st_orderby,
            );

            if(!empty($st_ids)){
                $location_ids = explode(',',$st_ids);
				$query['post__in']=$location_ids;
            }
            if(!empty($meta_query)){
                $query['meta_query'] = $meta_query;
            }

            if($st_orderby == 'price'){
                $query['meta_key']='min_price_'.$st_type.'';
                $query['orderby']='meta_value';
            }

            if($st_orderby == 'sale'){
                $query['meta_key']='total_sale_number';
                $query['orderby']='meta_value';
            }

            if($st_orderby == 'rate'){
                $query['meta_key']='review_'.$st_type.'';
                $query['orderby']='meta_value';
            }
            if($is_featured == 'yes'){
                $query['orderby']='meta_value_num';
                $query['meta_query'][]=
                    array(
                        'key'     => 'is_featured',
                        'value'   => 'on',
                        'compare' => '=',
                    );
                
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
            $r =  st()->load_template('vc-elements/st-list-location/loop',$st_style,$data); ;
            wp_reset_query();
            return $r;
        }
    }
    st_reg_shortcode('st_list_location','st_vc_list_location');
