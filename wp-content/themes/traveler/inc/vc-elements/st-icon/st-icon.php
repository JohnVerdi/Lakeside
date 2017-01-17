<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Icon", ST_TEXTDOMAIN),
            "base" => "st_icon",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Position Tooltip", ST_TEXTDOMAIN),
                    "param_name" => "st_pos_tooltip",
                    'edit_field_class'=>'vc_col-sm-6',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('No',ST_TEXTDOMAIN)=>'none',
                        __('Top',ST_TEXTDOMAIN)=>'top',
                        __('Bottom',ST_TEXTDOMAIN)=>'bottom',
                        __('Left',ST_TEXTDOMAIN)=>'left',
                        __('Right',ST_TEXTDOMAIN)=>'right'
                    )
                ),array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Tooltip", ST_TEXTDOMAIN),
                    "param_name" => "st_tooltips",
                    "description" =>__("Place your tooltip" , ST_TEXTDOMAIN),
                    'edit_field_class'=>'vc_col-sm-6'
                ),

                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Icon", ST_TEXTDOMAIN),
                    "param_name" => "st_icon",
                    "description" =>"",
                    'edit_field_class'=>'st_iconpicker vc_col-sm-6'
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Text Content", ST_TEXTDOMAIN),
                    "param_name" => "st_text_content",
                    "description" =>__("Must be empty Icon to use this Param" .ST_TEXTDOMAIN),
                    'edit_field_class'=>'st_iconpicker vc_col-sm-6'
                ),
                array(
                    "type" => "colorpicker",
                    "holder" => "div",
                    "heading" => __("Icon color", ST_TEXTDOMAIN),
                    "param_name" => "st_color_icon",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-3'
                ),
                array(
                    "type" => "colorpicker",
                    "holder" => "div",
                    "heading" => __("To Icon color", ST_TEXTDOMAIN),
                    "param_name" => "st_to_color",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-3',
                    'value'=>''
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Alignment", ST_TEXTDOMAIN),
                    "param_name" => "st_aligment",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-3',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('No',ST_TEXTDOMAIN)=>'box-icon-none',
                        __('Left',ST_TEXTDOMAIN)=>'box-icon-left',
                        __('Right',ST_TEXTDOMAIN)=>'box-icon-right',
                        __('Center',ST_TEXTDOMAIN)=>'box-icon-center'
                    )
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Size Icon", ST_TEXTDOMAIN),
                    "param_name" => "st_size_icon",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-3',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Small',ST_TEXTDOMAIN)=>'box-icon-sm',
                        __('Medium',ST_TEXTDOMAIN)=>'box-icon-md',
                        __('Big',ST_TEXTDOMAIN)=>'box-icon-big',
                        __('Large',ST_TEXTDOMAIN)=>'box-icon-large',
                        __('Huge',ST_TEXTDOMAIN)=>'box-icon-huge',
                    )
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Round Icon", ST_TEXTDOMAIN),
                    "param_name" => "st_round",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-3',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('No',ST_TEXTDOMAIN)=>'',
                        __('Yes',ST_TEXTDOMAIN)=>'round',
                    )
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Icon Box border", ST_TEXTDOMAIN),
                    "param_name" => "st_border",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-3',
                    'value'=>array(
                        __('No',ST_TEXTDOMAIN)=>'',
                        __('Normal',ST_TEXTDOMAIN)=>'box-icon-border',
                        __('Dashed',ST_TEXTDOMAIN)=>'box-icon-border-dashed',
                    )
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Animation", ST_TEXTDOMAIN),
                    "param_name" => "st_animation",
                    "description" =>__("http://daneden.github.io/animate.css/",ST_TEXTDOMAIN),
                    'edit_field_class'=>'vc_col-sm-6',
                    'value'=>''
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("CSS Display", ST_TEXTDOMAIN),
                    "param_name" => "st_icon_display",
                    'edit_field_class'=>'vc_col-sm-6',
                    'value'=>array(
                        __('Block',ST_TEXTDOMAIN)=>'block',
                        __('Inline',ST_TEXTDOMAIN)=>'inline',
                        __('Inline-block',ST_TEXTDOMAIN)=>'inline-block',
                    )
                ),
            )
        ) );
    }

    if(!function_exists('st_vc_icon')){
        function st_vc_icon($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'st_tooltips' =>'',
                    'st_pos_tooltip' =>'none',
                    'st_icon' =>'',
                    'st_text_content'=>'',
                    'st_color_icon'=>'',
                    'st_to_color'=>'',
                    'st_size_icon'=>'box-icon-sm',
                    'st_round'=>'',
                    'st_border'=>'',
                    'st_animation'=>'',
                    'st_aligment'=>'box-icon-none',
                    'st_icon_display'=>''
                ), $attr, 'st_about_icon' );
            extract($data);

            $class_bg_color = Assets::build_css("background: ".$st_color_icon."!important;");
            $class_bg_to_color = Assets::build_css("background: ".$st_to_color."!important;
                                                border-color: ".$st_to_color."!important;
                                                ",":hover");
            if($st_animation == "border-rise"){
                $class__ = Assets::build_css("box-shadow: 0 0 0 2px ".$st_to_color." ",":after");
                $class_bg_to_color =  $class_bg_to_color." ".$class__;
            }


            if(!empty($st_border)){
                $class_bg_color = Assets::build_css("border-color: ".$st_color_icon."!important;
                                                 color: ".$st_color_icon."!important;");
            }
            if(!$st_pos_tooltip or $st_pos_tooltip != 'none'){
                $html_tooltip = 'data-placement="'.$st_pos_tooltip.'" title="" rel="tooltip" data-original-title="'.$st_tooltips.'"';
            }else{$html_tooltip="";}

            if(!empty($st_animation)){
                $animate = "animate-icon-".$st_animation;
            }else{$animate = "";}
            $class__text_font = $class__css_display = "";
            if (!empty($st_icon)) {
                $st_text_content = "";
            }else {
                $class__text_font .= Assets::build_css("font-family: "."inherit;");
            }

            if (!empty($st_icon_display)) {
                $class__css_display = Assets::build_css("display:" .$st_icon_display.";");
            }
            $txt ='<i class="fa '.$class__css_display .' ' .$class__text_font.' '. $st_icon.' '.$st_size_icon.' '.$st_border.' '.$st_aligment.' '.$class_bg_color.' '.$st_round.' '.$class_bg_to_color.' '.$animate.' " '.$html_tooltip.'>'.$st_text_content .'  </i>';
            return $txt;
        }
    }
    st_reg_shortcode('st_icon','st_vc_icon');