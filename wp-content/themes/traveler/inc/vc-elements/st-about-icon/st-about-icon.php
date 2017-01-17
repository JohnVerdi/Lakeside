<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST About Icon", ST_TEXTDOMAIN),
            "base" => "st_about_icon",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Icon", ST_TEXTDOMAIN),
                    "param_name" => "st_icon",
                    "description" =>"",
                    'edit_field_class'=>'st_iconpicker vc_col-sm-12'
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
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("To Icon color", ST_TEXTDOMAIN),
                    "param_name" => "st_to_color",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-3',
                    'value'=>'black'
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Position Icon", ST_TEXTDOMAIN),
                    "param_name" => "st_pos_icon",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-3',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Top',ST_TEXTDOMAIN)=>'top',
                        __('Left',ST_TEXTDOMAIN)=>'left',
                        __('Right',ST_TEXTDOMAIN)=>'right'
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
                        __('Big',ST_TEXTDOMAIN)=>'box-icon-big'
                    )
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Text Align", ST_TEXTDOMAIN),
                    "param_name" => "st_text_align",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-3',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Center',ST_TEXTDOMAIN)=>'text-center',
                        __('Left',ST_TEXTDOMAIN)=>'text-left',
                        __('Right',ST_TEXTDOMAIN)=>'text-right'
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
                        __('Yes',ST_TEXTDOMAIN)=>'box-icon-border',
                    )
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Animation", ST_TEXTDOMAIN),
                    "param_name" => "st_animation",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-6',
                    'value'=>'animate-icon-top-to-bottom'
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
                    "heading" => __("Link", ST_TEXTDOMAIN),
                    "param_name" => "st_link",
                    "description" =>"",
                ),
                array(
                    "type" => "textarea",
                    "holder" => "div",
                    "heading" => __("Description", ST_TEXTDOMAIN),
                    "param_name" => "st_description",
                    "description" =>"",
                ),
            )
        ) );
    }
    if(!function_exists('st_vc_about_icon')){
        function st_vc_about_icon($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'st_icon' =>'',
                    'st_name' => '',
                    'st_description'=>'',
                    'st_link'=>'',
                    'st_pos_icon'=>'top',
                    'st_color_icon'=>'',
                    'st_size_icon'=>'box-icon-sm',
                    'st_text_align'=>'text-center',
                    'st_border'=>'',
                    'st_to_color'=>'black',
                    'st_animation'=>'animate-icon-top-to-bottom',
                ), $attr, 'st_about_icon' );
            extract($data);

            $icons_center='';
            if($st_text_align =='text-center'){
                $icons_center = 'box-icon-center';
            }

            $class_bg_color = Assets::build_css("background: ".$st_color_icon."");

            if(!empty($st_border)){
                $class_bg_color = Assets::build_css("border-color: ".$st_color_icon."!important;
                                                 color: ".$st_color_icon."!important;");
            }

            if(empty($st_link)){
                $title = '<h4 class="thumb-title">'.$st_name.'</h4>';
            }else{
                $title = ' <h5 class="thumb-title">
                        <a href="'.$st_link.'" class="text-darken">'.$st_name.'</a>
                       </h5>';
            }
            $txt =  '
                    <div class="thumb '.$st_text_align.'">
                            <header class="thumb-header pull-'.$st_pos_icon.' st-thumb-header">
                               <i class="fa '.$st_icon.' '.$st_size_icon.' box-icon-'.$st_pos_icon.' '.$icons_center.' round '.$class_bg_color.' '.$st_animation.' '.$st_border.'  box-icon-to-'.$st_to_color.' "></i>
                            </header>
                            <div class="thumb-caption pull-'.$st_pos_icon.' st-thumb-caption">
                                 '.$title.'
                                <p class="thumb-desc">'.$st_description.'</p>
                            </div>
                    </div>
                    ';
            return $txt;
        }
    }
    st_reg_shortcode('st_about_icon','st_vc_about_icon');