<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Gallery", ST_TEXTDOMAIN),
            "base" => "st_gallery",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Number of images", ST_TEXTDOMAIN),
                    "param_name" => "st_number_image",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-6 vc_col-md-6',
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Number of Columns", ST_TEXTDOMAIN),
                    "param_name" => "st_col",
                    'edit_field_class'=>'vc_col-sm-6 vc_col-md-6',
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Four',ST_TEXTDOMAIN)=>'4',
                        __('Three',ST_TEXTDOMAIN)=>'3',
                        __('Two',ST_TEXTDOMAIN)=>'2',
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Animation effect", ST_TEXTDOMAIN),
                    "param_name" => "st_effect",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-6 vc_col-md-6',
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
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Show Image Title", ST_TEXTDOMAIN),
                    "param_name" => "st_image_title",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-6 vc_col-md-6',
                    'value'=>array(
                        __('--Select --',ST_TEXTDOMAIN)=>'',
                        __('Yes',ST_TEXTDOMAIN)=>'y',
                        __('No',ST_TEXTDOMAIN)=>'n',
                    )
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Icon hover", ST_TEXTDOMAIN),
                    "param_name" => "st_icon",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-4 vc_col-md-4',
                    'value'=>"fa-plus"
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Margin Item", ST_TEXTDOMAIN),
                    "param_name" => "margin_item",
                    'edit_field_class'=>'vc_col-sm-4 vc_col-md-4',
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Normal',ST_TEXTDOMAIN)=>'normal',
                        __('Full width',ST_TEXTDOMAIN)=>'full', 
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Image Size", ST_TEXTDOMAIN),
                    "param_name" => "image_size",
                    'edit_field_class'=>'vc_col-sm-4 vc_col-md-4',
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Thumbnail',ST_TEXTDOMAIN)=>'thumbnail',
                        __('Medium',ST_TEXTDOMAIN)=>'medium', 
                        __('Large',ST_TEXTDOMAIN)=>'large',
                        __('Full',ST_TEXTDOMAIN)=>'full',
                    ),
                ),
                array(
                    "type" => "attach_images",
                    "holder" => "div",
                    "heading" => __("List Image", ST_TEXTDOMAIN),
                    "param_name" => "st_images_in",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                ),
                array(
                    "type" => "attach_images",
                    "holder" => "div",
                    "heading" => __("Images not in", ST_TEXTDOMAIN),
                    "param_name" => "st_images_not_in",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                ),
            )
        ) );
    }

    if(!function_exists('st_vc_gallery')){
        function st_vc_gallery($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'st_number_image'=>'',
                    'st_col'=>4,
                    'margin_item'=>'',
                    'image_size'=> array(800,600) , 
                    'st_images_not_in'=>'',
                    'st_images_in'=>'',
                    'st_effect'=>'mfp-zoom-out',
                    'st_icon'=>'fa-plus',
                    'st_image_title'=> ''
                ), $attr, 'st_gallery' );
            extract($data);
            $query_images_args = array(
                'post_type' => 'attachment',
                'post_mime_type' =>'image',
                'post_status' => 'inherit',
                'posts_per_page'=>$st_number_image,
                'post__not_in'=>explode(',',$st_images_not_in),
                'post__in'=>explode(',',$st_images_in)
            );
            $list = query_posts($query_images_args);
            $txt='';
            $icon = (!empty($st_icon)) ? '<i class="fa '.$st_icon.' round box-icon-small hover-icon i round"></i>' : "";
            foreach ($list as $k=>$v){
                $col = 12 / $st_col ;

                $image_src=wp_get_attachment_image_src($v->ID,'full');

                $image_src_raw= (isset($image_src[0])?$image_src[0]:false);
                //$sub_title = (strlen(get_the_title($v->ID)) >10) ? substr(get_the_title($v->ID),1,25) ."..." : get_the_title($v->ID);
                $image_title = (!empty($st_image_title)  and ($st_image_title =='y')) ? "<div class='bgr-main st_image_title text-1line'><div>".get_the_title($v->ID)."</div></div>" : "";
                $txt .= '<div class="st_fix_gallery col-md-' . $col . '">';
                        if ($margin_item =='full') $txt .='<div class="row" >';
                        $txt.=$image_title.'<a class="hover-img popup-gallery-image" href="' .$image_src_raw. '" data-effect="'.$st_effect.'">'.
                           wp_get_attachment_image($v->ID,$image_size)
                            .$icon.
                        '</a>';
                        if ($margin_item =='full') $txt .='</div>';
                        $txt .='</div>';
            }
            wp_reset_query();
            if($st_number_image > $list_in = count(explode(',',$st_images_in)) ) {
                $query_images_args = array(
                    'post_type' => 'attachment',
                    'post_mime_type' =>'image',
                    'post_status' => 'inherit',
                    'posts_per_page'=>$st_number_image - $list_in,
                    'post__not_in'=>explode(',',$st_images_not_in.','.$st_images_in),
                );
                $list = query_posts($query_images_args);
                foreach ($list as $k=>$v){
                    $col = 12 / $st_col ;

                    $image_src=wp_get_attachment_image_src($v->ID,'full');
                    $image_src_raw= (isset($image_src[0])?$image_src[0]:false);
                    //$sub_title = (strlen(get_the_title($v->ID)) >10) ? substr(get_the_title($v->ID),1,25) ."..." : get_the_title($v->ID);
                    $image_title = (!empty($st_image_title)  and ($st_image_title =='y')) ? "<div class='bgr-main st_image_title text-1line'><div>".get_the_title($v->ID)."</div></div>" : "";
                    $txt .= '<div class="st_fix_gallery2 col-md-'.$col.'">';
                        if ($margin_item =='full') $txt .='<div class="row" >';
                        $txt .=$image_title.'<a class="hover-img popup-gallery-image" href="'. $image_src_raw.'" data-effect="'.$st_effect.'">
                            '.wp_get_attachment_image($v->ID,$image_size).
                            $icon.
                        '</a>';
                        if ($margin_item =='full') $txt .='</div>';
                        $txt.='
                    </div>';
                }
                wp_reset_query();
            }
            $row_class = ($margin_item =='full') ? "" :'row' ;
            $r =  '<div id="popup-gallery">
                    <div class="'.$row_class .' row-col-gap">
                      '.$txt.'
                    </div>
                </div>';
            return $r;
        }
    }
    st_reg_shortcode('st_gallery','st_vc_gallery');