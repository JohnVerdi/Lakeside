<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Search with background gallery", ST_TEXTDOMAIN),
            "base" => "st_bg_gallery",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "attach_images",
                    "holder" => "div",
                    "heading" => __("Images in", ST_TEXTDOMAIN),
                    "param_name" => "st_images_in",
                    "description" =>"",
                ),
                array(
                    "type" => "attach_images",
                    "holder" => "div",
                    "heading" => __("Images not in", ST_TEXTDOMAIN),
                    "param_name" => "st_images_not_in",
                    "description" =>"",
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Effect Speed", ST_TEXTDOMAIN),
                    "param_name" => "st_speed",
                    "description" =>__('Example : 1200ms',ST_TEXTDOMAIN),
                    'edit_field_class'=>'vc_col-sm-6',
                    'value'=>1200
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Columns", ST_TEXTDOMAIN),
                    "param_name" => "st_col",
                    "description" =>__('Example : 8',ST_TEXTDOMAIN),
                    'edit_field_class'=>'vc_col-sm-6',
                    'value'=>8
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Rows", ST_TEXTDOMAIN),
                    "param_name" => "st_row",
                    "description" =>__('Example : 4',ST_TEXTDOMAIN),
                    'edit_field_class'=>'vc_col-sm-6',
                    'value'=>4
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
        ) );
    }
    if(!function_exists('st_vc_bg_gallery')){
        function st_vc_bg_gallery($attr,$content=false)
        {
            wp_enqueue_script('gridrotator.js');
            $data = shortcode_atts(
                array(
                    'st_images_in' =>0,
                    'st_images_not_in' =>0,
                    'st_col'=>8,
                    'st_row'=>4,
                    'st_speed'=>1200,
                    'opacity'=>'0.5'
                ), $attr, 'st_bg_gallery' );
            extract($data);
            $per_page = ($st_col * $st_row)+5;
            $query_images_args = array(
                'post_type' => 'attachment',
                //'post_mime_type' =>'image',
                'post_status' => 'public',
                'posts_per_page'=>$per_page,
                'post__not_in'=>explode(',',$st_images_not_in),
                'post__in'=>explode(',',$st_images_in)
            );
            $list = query_posts($query_images_args);
            $txt='';
            $int_i = 0;
            $int_i = count($list);
            foreach ($list as $k=>$v){
                $image=wp_get_attachment_image($v->ID,array(300,300));
                $txt .= '<li class="image-'.$v->ID.'">
                        <a href="#">
                            '.$image.'
                        </a>
                     </li>';
            }
            wp_reset_query();
            if($list_in = count(explode(',',$st_images_in)) < $per_page){
                $query_images_args = array(
                    'post_type' => 'attachment',
                    'post_mime_type' =>'image',
                    'post_status' => 'inherit',
                    'posts_per_page'=>$per_page - $list_in,
                    'post__not_in'=>explode(',',$st_images_not_in.','.$st_images_in),
                );
                $list = query_posts($query_images_args);
                foreach ($list as $k=>$v){
                    $image=wp_get_attachment_image($v->ID,array(300,300));
                    $txt .= '<li class="image-'.$v->ID.'">
                        <a href="#">
                            '.$image.'
                        </a>
                     </li>';
                   // $int_i++;
                }
                wp_reset_query();
            }
            $class_bg = Assets::build_css("opacity: ".$opacity."!important;");
            $r =  '<div class="bg-holder">
                    <div class="bg-mask-darken '.$class_bg.'"></div>
                    <div class="bg-parallax"></div>
                    <!-- START GRIDROTATOR -->
                     <div class="ri-grid" id="ri-grid" xcount="'.$int_i.'" data-col="'.$st_col.'" data-row="'.$st_row.'" data-speed="'.$st_speed.'">
                        <ul>'.$txt.'</ul>
                    </div>
                    <!-- END GRIDROTATOR -->
                    <div class="bg-front full-center">
                        <div class="container">
                            '.st()->load_template('vc-elements/st-search/search','form',array('st_style_search' =>'style_1', 'st_box_shadow'=>'no' ,'class'=>'') ).'
                        </div>
                    </div>
                </div>';
            return $r;
        }
    }
    st_reg_shortcode('st_bg_gallery','st_vc_bg_gallery');
