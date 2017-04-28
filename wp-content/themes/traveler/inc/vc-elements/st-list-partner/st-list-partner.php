<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 23/03/2016
 * Time: 15:22 CH
 */
if (function_exists('vc_map')){
    vc_map( array(
        "name" => __("ST List Partner", ST_TEXTDOMAIN),
        "base" => "st_list_partner",
        "as_parent" => array('only' => 'st_list_partner_item'),
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
            array(
                'type'=> "textfield",
                'holder'=> 'div',
                'heading'=> __("Items showing" , ST_TEXTDOMAIN),
                'param_name'    => 'items',
            ),
        )
    ) );
    vc_map( array(
        "name" => __("ST List Partner item", ST_TEXTDOMAIN),
        "base" => "st_list_partner_item",
        "content_element" => true,
        "as_child" => array('only' => 'st_list_partner'),
        "icon" => "icon-st",
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __("Title", ST_TEXTDOMAIN),
                "param_name" => "st_title",
                "description" =>"",
            ),
            array(
                "type" => "textfield",
                "heading" => __("URL", ST_TEXTDOMAIN),
                "param_name" => "st_link",
                "description" =>"",
            ),
            array(
                "type" => "attach_image",
                "heading" => __("Select partner's image", ST_TEXTDOMAIN),
                "param_name" => "st_image",
                "description" =>"",
            ),
        )
    ) );
}
if ( class_exists( 'WPBakeryShortCodesContainer' ) and !class_exists('WPBakeryShortCode_st_list_partner')) {
    class WPBakeryShortCode_st_list_partner extends WPBakeryShortCodesContainer {
        protected function content($arg, $content = null) {
            extract(wp_parse_args($arg,array('style'=>'','items'=>4)));
            $content_data= st_remove_wpautop($content);
            $div_navigation = "<div class='st_list_partner_nav' >
<i class=\" prev fa main-color  fa-angle-left box-icon-sm box-icon-border round\">  </i>
<i class=\" next fa main-color  fa-angle-right box-icon-sm box-icon-border round\">  </i>
</div>";
            return "
                <div class='st_list_partner owl-theme' data-items ='".$items."'>".balanceTags($content_data)."</div>"
            ." "
            .balanceTags($div_navigation);
        }
    }
}
if ( class_exists( 'WPBakeryShortCode' ) and !class_exists('WPBakeryShortCode_st_list_partner_item') ) {
    class WPBakeryShortCode_st_list_partner_item extends WPBakeryShortCode {
        protected function content($arg, $content = null) {
            extract(wp_parse_args($arg,array('st_title'=>'','st_link'=>'','st_image')));
            $return ="";
            if (!empty($st_image)){
                $return .= "<div class='item st_tour_ver'>
<div class='dummy'></div>
<div class='img-container'><a href='".$st_link."'>".wp_get_attachment_image ($st_image,'full', array('alt'=>$st_title))."</a></div>
</div>";
            }
            
            return $return ;
        }
    }
}