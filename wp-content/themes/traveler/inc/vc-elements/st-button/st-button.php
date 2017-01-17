<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Button", ST_TEXTDOMAIN),
            "base" => "st_button",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Text Button", ST_TEXTDOMAIN),
                    "param_name" => "st_title",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12'
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Link Buttons", ST_TEXTDOMAIN),
                    "param_name" => "st_link",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Type Button", ST_TEXTDOMAIN),
                    "param_name" => "st_type",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-4',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Default',ST_TEXTDOMAIN)=>'btn-default',
                        __('Primary',ST_TEXTDOMAIN)=>'btn-primary',
                        __('Success',ST_TEXTDOMAIN)=>'btn-success',
                        __('Info',ST_TEXTDOMAIN)=>'btn-info',
                        __('Warning',ST_TEXTDOMAIN)=>'btn-warning',
                        __('Danger',ST_TEXTDOMAIN)=>'btn-danger',
                        __('Link',ST_TEXTDOMAIN)=>'btn-link',
                    )
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Button size", ST_TEXTDOMAIN),
                    "param_name" => "st_size",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-4',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Normal',ST_TEXTDOMAIN)=>'btn-normal',
                        __('Large',ST_TEXTDOMAIN)=>'btn-lg',
                        __('Small',ST_TEXTDOMAIN)=>'btn-sm',
                        __('Extra small',ST_TEXTDOMAIN)=>'btn-xs',
                    )
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Ghost button", ST_TEXTDOMAIN),
                    "param_name" => "st_ghost",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-4',
                    'value'=>array(
                        __('No',ST_TEXTDOMAIN)=>'',
                        __('Yes',ST_TEXTDOMAIN)=>'btn-ghost',
                    )
                ),
            )
        ) );
    }
    if(!function_exists('st_vc_button')){
        function st_vc_button($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'st_title' =>'',
                    'st_link' =>'',
                    'st_type' =>'btn-default',
                    'st_size' =>'btn-normal',
                    'st_ghost' =>'',
                ), $attr, 'st_button' );
            extract($data);
            $txt ='<a href="'.$st_link.'" class="btn '.$st_type.' '.$st_size.' '.$st_ghost.'">'.$st_title.'</a>';
            return $txt;
        }

    }
    st_reg_shortcode('st_button','st_vc_button');