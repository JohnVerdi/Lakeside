<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Gird", ST_TEXTDOMAIN),
            "base" => "st_gird",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "colorpicker",
                    "holder" => "div",
                    "heading" => __("Color", ST_TEXTDOMAIN),
                    "param_name" => "st_color",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                    'value'=>'#999'
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Size", ST_TEXTDOMAIN),
                    "param_name" => "st_size",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('col-1',ST_TEXTDOMAIN)=>'col-md-1',
                        __('col-2',ST_TEXTDOMAIN)=>'col-md-2',
                        __('col-3',ST_TEXTDOMAIN)=>'col-md-3',
                        __('col-4',ST_TEXTDOMAIN)=>'col-md-4',
                        __('col-5',ST_TEXTDOMAIN)=>'col-md-5',
                        __('col-6',ST_TEXTDOMAIN)=>'col-md-6',
                        __('col-7',ST_TEXTDOMAIN)=>'col-md-7',
                        __('col-8',ST_TEXTDOMAIN)=>'col-md-8',
                        __('col-9',ST_TEXTDOMAIN)=>'col-md-9',
                        __('col-10',ST_TEXTDOMAIN)=>'col-md-10',
                        __('col-11',ST_TEXTDOMAIN)=>'col-md-11',
                        __('col-12',ST_TEXTDOMAIN)=>'col-md-12',
                    )
                ),
            )
        ) );
    }

    if(!function_exists('st_vc_gird')){
        function st_vc_gird($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'st_color' =>'',
                    'st_size' =>'col-md-1',
                ), $attr, 'st_gird' );
            extract($data);
            $class = Assets::build_css('background : '.$st_color. ' ; height : 20px');
            $txt ='<div class="row"><div class="demo-grid">
                    <div class="'.$st_size.'">
                        <div class="'.$class.'"></div>
                    </div>
               </div></div>';
            return $txt;
        }
    }
    st_reg_shortcode('st_gird','st_vc_gird');