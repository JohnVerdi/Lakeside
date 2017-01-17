<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Image Effect", ST_TEXTDOMAIN),
            "base" => "st_image_effect",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "attach_image",
                    "holder" => "div",
                    "heading" => __("image", ST_TEXTDOMAIN),
                    "param_name" => "st_image",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Type Hover", ST_TEXTDOMAIN),
                    "param_name" => "st_type",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                    'value'=>array(
                        __('Simple Hover',ST_TEXTDOMAIN)=>'',
                        __('Icon',ST_TEXTDOMAIN)=>'icon',
                        __('Icon Group',ST_TEXTDOMAIN)=>'icon-group',
                        __('Title',ST_TEXTDOMAIN)=>'title',
                        __('Inner Full',ST_TEXTDOMAIN)=>'inner-full',
                        __('Inner Block',ST_TEXTDOMAIN)=>'inner-block',
                    )
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Position Layout", ST_TEXTDOMAIN),
                    "param_name" => "st_pos_layout",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-4',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __("Top Left",ST_TEXTDOMAIN)     =>"-top-left",
                        __("Top Right",ST_TEXTDOMAIN)    =>"-top-right",
                        __("Bottom Left",ST_TEXTDOMAIN)  =>"-bottom-left",
                        __("Bottom Right",ST_TEXTDOMAIN) =>"-bottom-right",
                        __("Center",ST_TEXTDOMAIN)       =>"-center",
                        __("Center Top",ST_TEXTDOMAIN)   =>"-center-top",
                        __("Center Bottom",ST_TEXTDOMAIN)=>"-center-bottom",
                        __("Top",ST_TEXTDOMAIN)   =>"-top",
                        __("Bottom",ST_TEXTDOMAIN)=>"-bottom",
                    )
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Hover Hold", ST_TEXTDOMAIN),
                    "param_name" => "st_hover_hold",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-4',
                    'value'=>array(
                        __("No",ST_TEXTDOMAIN)     =>"",
                        __("Yes",ST_TEXTDOMAIN)    =>"hover-hold",
                    )
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Title", ST_TEXTDOMAIN),
                    "param_name" => "st_title",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Url", ST_TEXTDOMAIN),
                    "param_name" => "url",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Class Icon", ST_TEXTDOMAIN),
                    "param_name" => "st_class_icon",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                ),
                array(
                    "type" => "textarea",
                    "holder" => "div",
                    "heading" => __("Icon Group", ST_TEXTDOMAIN),
                    "param_name" => "content",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                    'value'=>''
                ),
            )
        ) );
    }

    if(!function_exists('st_vc_image_effect')){
        function st_vc_image_effect($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'st_image' =>'',
                    'st_type' =>'icon',
                    'st_pos_layout' =>'-top-left',
                    'st_hover_hold' =>'',
                    'st_title' =>'',
                    'st_class_icon'=>'',
                    'st_icon_group'=>'',
                    'url'=>''
                ), $attr, 'st_image_effect' );
            extract($data);
            $img = wp_get_attachment_image_src($st_image,'full');
            $txt='';
            if($st_type == ""){
                $txt ='<a href="#" class="hover-img">
                     <img title="" alt="" src="'.$img[0].'">
                   </a>';
            }

            if($st_type == "icon"){
                $txt ='<a href="'.$url.'" class="hover-img">
                     <img title="" alt="" src="'.$img[0].'">
                     <i class="fa '.$st_class_icon.' box-icon hover-icon'.$st_pos_layout.' '.$st_hover_hold.' round"></i>
                   </a>';
            }
            if($st_type == "icon-group"){
                $content = str_ireplace('<ul>','',$content);
                $content = str_ireplace('</ul>','',$content);
                $txt ='<div class="hover-img">
                     <img title="" alt="" src="'.$img[0].'">
                     <ul class="hover-icon-group'.$st_pos_layout.' '.$st_hover_hold.'">
                    '.st_remove_wpautop($content).'
                    </ul>
                   </div>';
            }
            if($st_type == "title"){
                $txt ='<a href="'.$url.'" class="hover-img">
                            <img title="" alt="" src="'.$img[0].'">
                            <h5 class="hover-title'.$st_pos_layout.' '.$st_hover_hold.'">'.$st_title.'</h5>
                         </a>';
            }
            if($st_type == "inner-full"){
                $txt ='<a href="'.$url.'" class="hover-img">
                            <img title="" alt="" src="'.$img[0].'">
                            <div class="hover-inner">'.$st_title.'</div>
                         </a>';
            }
            if($st_type == "inner-block") {
                $txt = '<a href="'.$url.'" class="hover-img">
                            <img title="" alt="" src="' . $img[0] . '">
                            <div class="hover-inner hover-hold">' . $st_title . '</div>
                         </a>';
            }
            return $txt;
        }
    }
    st_reg_shortcode('st_image_effect','st_vc_image_effect');