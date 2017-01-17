<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Lightbox", ST_TEXTDOMAIN),
            "base" => "st_lightbox",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Type Lightbox", ST_TEXTDOMAIN),
                    "param_name" => "st_type",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-4',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Image',ST_TEXTDOMAIN)=>'image',
                        __('Iframe',ST_TEXTDOMAIN)=>'iframe',
                        __('HTML',ST_TEXTDOMAIN)=>'html',
                    )
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Animation effect", ST_TEXTDOMAIN),
                    "param_name" => "st_effect",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-4',
                    'value'=>array(
                        __('Default',ST_TEXTDOMAIN)=>'',
                        __('Zoom out',ST_TEXTDOMAIN)=>'mfp-zoom-out',
                        __('Zoom in',ST_TEXTDOMAIN)=>'mfp-zoom-in',
                        __('Fade',ST_TEXTDOMAIN)=>'mfp-fade',
                        __('Move horizontal',ST_TEXTDOMAIN)=>'mfp-move-horizontal',
                        __('Move from top',ST_TEXTDOMAIN)=>'mfp-move-from-top',
                        __('Newspaper',ST_TEXTDOMAIN)=>'mfp-newspaper',
                        __('3D unfold',ST_TEXTDOMAIN)=>'mfp-3d-unfold',
                    )
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Icon hover", ST_TEXTDOMAIN),
                    "param_name" => "st_icon",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-4',
                    'value'=>"fa-plus"
                ),
                array(
                    "type" => "attach_image",
                    "holder" => "div",
                    "heading" => __("image", ST_TEXTDOMAIN),
                    "param_name" => "st_image",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-2',
                ),
                array(
                    "type" => "textarea",
                    "holder" => "div",
                    "heading" => __("Link Iframe", ST_TEXTDOMAIN),
                    "param_name" => "st_link",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-10',
                    'value'=>''
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Title", ST_TEXTDOMAIN),
                    "param_name" => "st_title",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                    'value'=>''
                ),
                array(
                    "type" => "textarea",
                    "holder" => "div",
                    "heading" => __("Content html", ST_TEXTDOMAIN),
                    "param_name" => "content",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                    'value'=>''
                ),
            )
        ) );
    }

    if(!function_exists('st_vc_lightbox')){
        function st_vc_lightbox($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'st_type' =>'image',
                    'st_effect' =>'mfp-zoom-out',
                    'st_image' =>'',
                    'st_link' =>'',
                    'st_title' =>'',
                    'st_icon' =>'fa-plus',
                ), $attr, 'st_lightbox' );
            extract($data);
            $img = wp_get_attachment_image_src($st_image,'full');
            if($st_type == "image"){
                $txt ='<a href="'.$img[0].'" class="hover-img popup-image" data-effect="'.$st_effect.'" >
                       <img title="" alt="" src="'.$img[0].'">
                       <i class="fa '.$st_icon.' round box-icon-small hover-icon i round"></i>
                   </a>';
            }
            if($st_type == "iframe"){
                $txt ='<a class="popup-iframe" data-effect="'.$st_effect.'" href="'.$st_link.'" inline_comment="lightbox">'.$st_title.'</a>';
            }
            if($st_type == "html"){
                $id = rand();
                $txt ='<a class="popup-text" data-effect="'.$st_effect.'" href="#small-dialog-'.$id.'">'.$st_title.'</a>
                    <div id="small-dialog-'.$id.'" class="mfp-with-anim mfp-dialog mfp-hide">
                    '.st_remove_wpautop($content).'
                    </div>';
            }
            return $txt;
        }
    }
    st_reg_shortcode('st_lightbox','st_vc_lightbox');