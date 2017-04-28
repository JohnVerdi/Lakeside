<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 23/03/2016
 * Time: 15:22 CH
 */
if (function_exists('vc_map')){
    vc_map( array(
        "name" => __("ST Progress Bar", ST_TEXTDOMAIN),
        "base" => "st_progress_bar",
        "as_parent" => array('only' => 'st_progress_bar_item'),
        "content_element" => true,
        "show_settings_on_create" => false,
        "js_view" => 'VcColumnView',
        "icon" => "icon-st",
        "category"=>"Shinetheme",
        "params" => array(
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Element style", ST_TEXTDOMAIN),
                "param_name" => "style",
                "description" =>"",
                'value'=>array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    /*__('Tour box style (Vertical ) ',ST_TEXTDOMAIN)=>'vertical',
                    __('Tour box style (Horizontal ) ',ST_TEXTDOMAIN)=>'horizontal',*/
                ),
            ),
        )
    ) );
    vc_map( array(
        "name" => __("ST Progress Bar item", ST_TEXTDOMAIN),
        "base" => "st_progress_bar_item",
        "content_element" => true,
        "as_child" => array('only' => 'st_progress_bar'),
        "icon" => "icon-st",
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __("Title", ST_TEXTDOMAIN),
                "param_name" => "st_title",
                "description" =>"",
            ),
            /*array(
                "type" => "textarea_html",
                "heading" => __("Content", ST_TEXTDOMAIN),
                "param_name" => "content",
                "description" =>"",
            ),*/
            array(
                "type" => "textfield",
                "heading" => __("Value percentage", ST_TEXTDOMAIN),
                "param_name" => "value",
                "description" =>"",
            ),
        )
    ) );
}
if ( class_exists( 'WPBakeryShortCodesContainer' ) and !class_exists('WPBakeryShortCode_st_progress_bar')) {
    class WPBakeryShortCode_st_progress_bar extends WPBakeryShortCodesContainer {
        protected function content($arg, $content = null) {
            extract(wp_parse_args($arg,array('style'=>'')));
            $content_data= st_remove_wpautop($content);
            return "<div class='st_progress_bar'>".$content_data."</div>";
        }
    }
}
if ( class_exists( 'WPBakeryShortCode' ) and !class_exists('WPBakeryShortCode_st_progress_bar_item') ) {
    class WPBakeryShortCode_st_progress_bar_item extends WPBakeryShortCode {
        protected function content($arg, $content = null) {
            extract(wp_parse_args($arg,array('st_title'=>'','value'=>'')));
            $return = "";
            if (!empty($st_title) and !empty($value)){
                $bgr_main_width = (((int)$value<=100)?(int)$value : 100) ."%";
                $main_color = ($value<100) ? "main-color" : "white";
                $return .="<div class='st_progress_bar_item row'>";
                $return .="<div class='col-xs-12 col-lg-3'>".$st_title."</div>";
                $return .=" <div class='col-xs-12 col-lg-9'>
 <div class='st_tour_ver'>
 <div class='value ".$main_color."'>".$bgr_main_width."</div>
 <div class='bgr-main' style='width:".$bgr_main_width."'></div>
 </div>
 </div>";
                $return .="</div>";
            }
            return $return ;
        }
    }
}