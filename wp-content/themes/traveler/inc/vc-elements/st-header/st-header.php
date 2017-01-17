<?php
if (function_exists('vc_map')){
    vc_map(array(
        "name"            => __( "ST Tours Header" , ST_TEXTDOMAIN ) ,
        "base"            => "st_header" ,
        "icon"            => "icon-st" ,
        "category"        => 'Shinetheme',
        'params'    => array(
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Heading size", ST_TEXTDOMAIN),
                "param_name" => "heading_size",
                "description" =>"",
                "value" => array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('1',ST_TEXTDOMAIN)=>'1',
                    __('2',ST_TEXTDOMAIN)=>'2',
                    __('3',ST_TEXTDOMAIN)=>'3',
                    __('4',ST_TEXTDOMAIN)=>'4',
                    __('5',ST_TEXTDOMAIN)=>'5',
                    __('6',ST_TEXTDOMAIN)=>'6',
                ),
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "heading" => __("Font Weight", ST_TEXTDOMAIN),
                "param_name" => "font_weight",
                "description" =>"Example: bold<br> <a href='http://www.w3schools.com/cssref/pr_font_weight.asp'>Read More</a>",
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Show Location ?", ST_TEXTDOMAIN),
                "param_name" => "is_location",
                "description" =>"",
                "value" => array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('Yes',ST_TEXTDOMAIN)=>'1',
                    __('No',ST_TEXTDOMAIN)=>'2',
                ),
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Show contact", ST_TEXTDOMAIN),
                "param_name" => "is_contact",
                "description" =>"",
                "value" => array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('Yes',ST_TEXTDOMAIN)=>'1',
                    __('No',ST_TEXTDOMAIN)=>'2',
                ),
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "heading" => __("Extra Class", ST_TEXTDOMAIN),
                "param_name" => "extra_class",
                "description" =>__("Class for each patch",ST_TEXTDOMAIN),
            ),
        ),
    ));
}
if (!function_exists('st_vc_header')){
    function st_vc_header($attr){

        $return = '
        <header class="booking-item-header">
            <div class="row">
                <div class="col-md-9 col-xs-12">'.
                st()->load_template('tours/elements/header',null,array('attr'=>$attr))
                .'</div>
                <div class="col-md-3 text-right price_activity">
						<p class="booking-item-header-price">'.
						STActivity::get_price_html(get_the_ID())
						.'</p>
                </div>
            </div>
        </header>';
        return $return ;
    }
}

st_reg_shortcode('st_header','st_vc_header');