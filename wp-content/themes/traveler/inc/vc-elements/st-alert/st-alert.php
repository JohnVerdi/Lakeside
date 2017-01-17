<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Alert", ST_TEXTDOMAIN),
            "base" => "st_alert",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "textarea",
                    "holder" => "div",
                    "heading" => __("Content Alert", ST_TEXTDOMAIN),
                    "param_name" => "st_content",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12'
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Type Alert", ST_TEXTDOMAIN),
                    "param_name" => "st_type",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Success',ST_TEXTDOMAIN)=>'alert-success',
                        __('Info',ST_TEXTDOMAIN)=>'alert-info',
                        __('Warning',ST_TEXTDOMAIN)=>'alert-warning',
                        __('Danger',ST_TEXTDOMAIN)=>'alert-danger',
                    )
                ),

            )
        ) );
    }
    if(!function_exists('st_vc_alert')){
        function st_vc_alert($attr,$content=false)
        {

            $data = shortcode_atts(
                array(
                    'st_content' =>'',
                    'st_type' =>'alert-success',
                ), $attr, 'st_alert' );
            extract($data);
            $txt ='<div class="alert '.$st_type.'">
                    <button data-dismiss="alert" type="button" class="close"><span aria-hidden="true">×</span>
                    </button>
                    <p class="text-small">'.$st_content.'</p>
                </div>';
            return $txt;
        }
    }
    st_reg_shortcode('st_alert','st_vc_alert');
