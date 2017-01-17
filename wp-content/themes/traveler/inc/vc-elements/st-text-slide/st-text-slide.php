<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Text Slide", ST_TEXTDOMAIN),
            "base" => "st_text_slide",
            "category"=>"Shinetheme",
            "content_element" => true,
            "show_settings_on_create" => true,
            "icon" => "icon-st",
            "params" => array(
                // add params same as with any other content element
                array(
                    "type" => "textfield",
                    "heading" => __("Title", ST_TEXTDOMAIN),
                    "param_name" => "st_title",
                ),
                array(
                    "type" => "textarea",
                    "heading" => __("HTML Code", ST_TEXTDOMAIN),
                    "param_name" => "st_html_code",
                ),
                array(
                    "type" => "dropdown",
                    "heading" => __("Show form search", ST_TEXTDOMAIN),
                    "param_name" => "show_search",
                    "value"=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __("Yes",ST_TEXTDOMAIN)=>'yes',
                        __("No",ST_TEXTDOMAIN)=>'no',
                    )
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Opacity Background", ST_TEXTDOMAIN),
                    "param_name" => "opacity",
                    "description" =>__(" Enter value form 0 - 0.5 - 1 ",ST_TEXTDOMAIN),
                    'value'=>'0.5'
                ),
                array(
                    "type" => "attach_images",
                    "heading" => __("Background", ST_TEXTDOMAIN),
                    "param_name" => "st_background",
                ),
            )
        ) );
    }

    if(!function_exists('st_vc_text_slide')){
        function st_vc_text_slide($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'st_title'=>'',
                    'st_html_code' =>'',
                    'st_background'=>'',
                    'opacity'=>'0.5',
                    'show_search'=>'yes',
                ), $attr, 'st_text_slide' );
            extract($data);
            $bg_image='';
            $class_bg = Assets::build_css("opacity: ".$opacity."!important;");
            foreach(explode(',',$st_background) as $k=>$v){
                $img = wp_get_attachment_image_src($v,'full');
                $bg_image .= '<div class="bg-holder full">
                            <div class="bg-mask '.$class_bg.'"></div>
                            <div class="bg-img" style="background-image:url('.$img[0].');"></div>
                     </div>';
            }
            $html_search = "";
            if($show_search == "yes"){
                $html_search = '<div class="container">
                                '.st()->load_template('vc-elements/st-search/search','form',array('st_style_search' =>'style_1', 'st_box_shadow'=>'no' ,'class'=>'search-tabs-abs-bottom') ).'
                            </div>';
            }
            $txt =  '<div id="text-slider-wrapper" class="top-area show-onload">
                    <div class="bg-holder full">
                        <div class="bg-front full-height">
                            <div class="container full-height">
                                <div class="rel full-height div_tagline">
                                    <div class="tagline" id="tagline">
                                    <span>'.$st_title.'</span>
                                    '.st_remove_wpautop($st_html_code).'
                                    </div>
                                    '.$html_search.'
                                </div>
                            </div>
                        </div>
                        <div class="owl-carousel owl-slider owl-carousel-area" id="owl-carousel-slider" data-nav="false">
                                '.$bg_image.'
                        </div>

                    </div>
                </div>';
            return $txt;
        }
    }
    st_reg_shortcode('st_text_slide','st_vc_text_slide');