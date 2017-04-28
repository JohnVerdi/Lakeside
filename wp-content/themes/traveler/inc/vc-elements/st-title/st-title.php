<?php
if (function_exists('vc_map')){
    vc_map(array(
        "name"            => __( "ST Title" , ST_TEXTDOMAIN ) ,
        "base"            => "st_title" ,
        "icon"            => "icon-st" ,
        "category"        => 'Shinetheme',
        'params'    => array(
            array(
                "type" => "textfield",
                "holder" => "div",
                "heading" => __("Heading", ST_TEXTDOMAIN),
                "param_name" => "heading",
                'edit_field_class'=>'vc_col-sm-12',
                "description" =>__("type 1 to H1", ST_TEXTDOMAIN),
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "heading" => __("Text align", ST_TEXTDOMAIN),
                "param_name" => "align",
                'edit_field_class'=>'vc_col-sm-12',
                "description" =>__("http://www.w3schools.com/cssref/pr_text_text-align.asp", ST_TEXTDOMAIN),
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "heading" => __("Color", ST_TEXTDOMAIN),
                "param_name" => "color",
                'edit_field_class'=>'vc_col-sm-12',
                 "description" =>__("http://www.w3schools.com/cssref/css_colors.asp", ST_TEXTDOMAIN),
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "heading" => __("Font Weight", ST_TEXTDOMAIN),
                "param_name" => "font_weight",
                'edit_field_class'=>'vc_col-sm-12',

            ),
        ),
    ));
}
if (!function_exists('st_vc_title')){
    function st_vc_title($attr){
        extract(shortcode_atts( array('heading'=>1,'align'=>'center','color'=>"white",'font_weight'=>'bold'),$attr));
        $title = apply_filters('the_title',get_the_title() );
        return "<h".$heading." style='font-weight: ".$font_weight."; color: ".$color." ;text-align:".$align."'>".$title."</h".$heading.">";
    }
}

st_reg_shortcode('st_title','st_vc_title');