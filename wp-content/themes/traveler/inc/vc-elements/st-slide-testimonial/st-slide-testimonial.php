<?php
    if(function_exists('vc_map')){
        vc_map(
            array(
                "name" => __("ST Testimonial Slide", ST_TEXTDOMAIN),
                "category"=>"Shinetheme",
                "base" => "st_slide_testimonial",
                "as_parent" => array('only' => 'st_testimonial_item'),
                "content_element" => true,
                "show_settings_on_create" => true,
                "js_view" => 'VcColumnView',
                "icon" => "icon-st",
                "params" => array(
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
                        "type" => "textfield",
                        "holder" => "div",
                        "heading" => __("Speed", ST_TEXTDOMAIN),
                        "param_name" => "st_speed",
                        "description" =>__("Ex : 500ms",ST_TEXTDOMAIN),
                        'value'=>'500'
                    ),
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "heading" => __("Auto Play Time", ST_TEXTDOMAIN),
                        "param_name" => "st_play",
                        "description" =>__("Set 0 to turn off autoplay",ST_TEXTDOMAIN),
                        'value'=>'4500'
                    ),
                    array(
                        "type" => "dropdown",
                        "holder" => "div",
                        "heading" => __("Show form", ST_TEXTDOMAIN),
                        "param_name" => "is_form",
                        "description" =>__("Yes to show form search",ST_TEXTDOMAIN),
                        'value'=>array(
                            __('--Select--',ST_TEXTDOMAIN)=>'',
                            __('Yes',ST_TEXTDOMAIN)=>'yes',
                            __('No',ST_TEXTDOMAIN)=>'no',
                        ),
                    ),
                    array(
                        "type" => "dropdown",
                        "holder" => "div",
                        "heading" => __("Background", ST_TEXTDOMAIN),
                        "param_name" => "is_bgr",
                        "description" =>__("No to tranparent background",ST_TEXTDOMAIN),
                        'value'=>array(
                            __('--Select--',ST_TEXTDOMAIN)=>'',
                            __('Yes',ST_TEXTDOMAIN)=>'yes',
                            __('No',ST_TEXTDOMAIN)=>'no',
                        ),
                    ),
                    array(
                        "type" => "dropdown",
                        "holder" => "div",
                        "heading" => __("Items in screen", ST_TEXTDOMAIN),
                        "param_name" => "items_per_row",
                        "description" =>__("Items number in a carousel item",ST_TEXTDOMAIN),
                        'value'=>array(
                            __('--Select--',ST_TEXTDOMAIN)=>'',
                            __('1',ST_TEXTDOMAIN)=>'1',
                            __('2',ST_TEXTDOMAIN)=>'2',
                            __('3',ST_TEXTDOMAIN)=>'3',
                            __('4',ST_TEXTDOMAIN)=>'4',
                        ),
                    ),
                    array(
                        "type" => "dropdown",
                        "holder" => "div",
                        "heading" => __("Navigation", ST_TEXTDOMAIN),
                        "param_name" => "navigation",
                        "description" =>__("No to hide navigation",ST_TEXTDOMAIN),
                        'value'=>array(
                            __('--Select--',ST_TEXTDOMAIN)=>'',
                            __('Yes',ST_TEXTDOMAIN)=>'true',
                            __('No',ST_TEXTDOMAIN)=>'false',
                        ),
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
            )
        );
        vc_map(
            array(
                "name" => __("Testimonial Item", ST_TEXTDOMAIN),
                "base" => "st_testimonial_item",
                "content_element" => true,
                "as_child" => array('only' => 'st_slide_testimonial'),
                "icon" => "icon-st",
                "params" => array(
                    array(
                        "type" => "attach_image",
                        "holder" => "div",
                        "heading" => __("Avatar", ST_TEXTDOMAIN),
                        "param_name" => "st_avatar",
                        "description" =>"",
                    ),
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "heading" => __("Name", ST_TEXTDOMAIN),
                        "param_name" => "st_name",
                        "description" =>"",
                    ),
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "heading" => __("Sub", ST_TEXTDOMAIN),
                        "param_name" => "st_sub",
                        "description" =>"",
                    ),
                    array(
                        "type" => "textarea",
                        "holder" => "div",
                        "heading" => __("Description", ST_TEXTDOMAIN),
                        "param_name" => "st_desc",
                        "description" =>"",
                    ),
                    array(
                        "type" => "attach_image",
                        "holder" => "div",
                        "heading" => __("Background", ST_TEXTDOMAIN),
                        "param_name" => "st_bg",
                        "description" =>"",
                    ),
                    /*array(
                        "type" => "dropdown",
                        "holder" => "div",
                        "heading" => __("Position", ST_TEXTDOMAIN),
                        "param_name" => "st_pos",
                        "description" =>"",
                        'value'=>array(
                            'right'=>'7',
                            'left'=>'0'
                        )
                    ),*/
                )
            )
        );
    }
    if ( class_exists( 'WPBakeryShortCodesContainer' ) and !class_exists('WPBakeryShortCode_st_slide_testimonial') ) {
        class WPBakeryShortCode_st_slide_testimonial extends WPBakeryShortCodesContainer {
            protected function content($arg, $content = null) {
                global $st_opacity;
                $data = shortcode_atts(
                    array(
                        'effect' =>'fade',
                        'st_speed' =>500,
                        'st_play' => 4500,
                        'is_form'   => 'yes',
                        'is_bgr'    => 'yes',
                        'items_per_row'=> 1,
                        'navigation' =>'true',
                        'opacity'=>'0.5'
                    ), $arg, 'st_slide_testimonial' );
                extract($data);
                $st_opacity = $opacity;
                $content = do_shortcode($content);
                $bgr_class = ($is_bgr =='no') ? 'transparent' : "";
                $form_class = ($is_form =='yes')? "is_form" : "no_form"; 
                $r =  '<!-- TOP AREA -->
                <div class="top-area show-onload '.$form_class.'">
                    <div class="bg-holder full">';
                        if ($is_form !=='no'){
                    $r.='<div class="bg-front bg-front-mob-rel">
                            <div class="container" style="position: relative">
                                 '.st()->load_template('vc-elements/st-search/search','form',array('st_style_search' =>'style_1', 'st_box_shadow'=>'no' ,'class'=>'search-tabs-abs-bottom') ).'
                             </div>
                        </div>';
                    }
                        $r.='<div class=" '.$bgr_class.' owl-carousel owl-slider owl-carousel-area " id="slide-testimonial" data-navigation ="'.$navigation.'" data-items="'.$items_per_row.'" data-speed="'.$st_speed.'" data-play="'.$st_play.'" data-effect="'.$effect.'">
                          '.$content.'
                        </div>
                    </div>
                </div>
                <!-- END TOP AREA  -->';
                unset($st_opacity);
                return $r;
            }
        }
    }
    if ( class_exists( 'WPBakeryShortCode' ) and !class_exists('WPBakeryShortCode_st_testimonial_item') ) {
        class WPBakeryShortCode_st_testimonial_item extends WPBakeryShortCode {
            protected function content($arg, $content = null) {
                global $st_opacity;
                $data = shortcode_atts(
                    array(
                        'st_avatar' =>0,
                        'st_name' => 0,
                        'st_sub'=>'',
                        'st_desc'=>'',
                        'st_bg'=>'',
                        'st_pos'=>'',
                        'opacity'=>$st_opacity
                    ), $arg, 'st_testimonial_item' );
                extract($data);
                $text = st()->load_template('vc-elements/st-slide-testimonial/loop',false,$data);
                return $text;
            }
        }
    }
