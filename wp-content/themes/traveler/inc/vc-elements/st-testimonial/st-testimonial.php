<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Testimonial", ST_TEXTDOMAIN),
            "base" => "st_testimonial",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Style", ST_TEXTDOMAIN),
                    "param_name" => "st_style",
                    "description" =>"",
                    "value"=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __("Style 1",ST_TEXTDOMAIN)=>"style1",
                        __("Style 2",ST_TEXTDOMAIN)=>"style2"
                    )
                ),
                array(
                    "type" => "attach_image",
                    "holder" => "div",
                    "heading" => __("Avatar", ST_TEXTDOMAIN),
                    "param_name" => "st_avatar",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-4',
                ),
                array(
                    "type" => "colorpicker",
                    "holder" => "div",
                    "heading" => __("Text Color", ST_TEXTDOMAIN),
                    "param_name" => "st_color",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-4',
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Testimonial color", ST_TEXTDOMAIN),
                    "param_name" => "st_testimonial_color",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-4',
                    'value'=>array(
                        __('No',ST_TEXTDOMAIN)=>'',
                        __('Yes',ST_TEXTDOMAIN)=>'testimonial-color',
                    )
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
            )
        ) );
    }

    if(!function_exists('st_vc_testimonial')){
        function st_vc_testimonial($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'st_style' =>'style1',
                    'st_avatar' =>0,
                    'st_color' =>"#000",
                    'st_testimonial_color' =>"",
                    'st_name' => 0,
                    'st_sub'=>'',
                    'st_desc'=>''
                ), $attr, 'st_testimonial' );
            extract($data);
            $img = wp_get_attachment_image_src($st_avatar,array(50,50));
            $class_color = Assets::build_css('color : '.$st_color);
            if($st_style == 'style1'){
                $txt =  '  <div class="testimonial '.$class_color.' '.$st_testimonial_color.' ">
                        <div class="testimonial-inner ">
                            <blockquote>
                                <p>'.$st_desc.'</p>
                            </blockquote>
                        </div>
                        <div class="testimonial-author">
                            <img src="'.($img[0]).'" alt="Avatar" title="'.$st_name.'" />
                            <p class="testimonial-author-name" >'.$st_name.'</p>
                            <cite>'.$st_sub.'</cite>
                        </div>
                    </div>';
            }
            if($st_style == 'style2'){
                $txt =  '  <div class="testimonial style2 '.$class_color.' '.$st_testimonial_color.'">
                        <div class="testimonial-inner">
                            <div class="row">
                                <div class="col-md-3">
                                     <img src="'.($img[0]).'" alt="Avatar" title="'.$st_name.'" />
                                </div>
                                <div class="col-md-8">
                                     <blockquote>
                                        <p>'.$st_desc.'</p>
                                     </blockquote>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-author">
                            <p class="testimonial-author-name dark" >'.$st_name.'</p>
                            <cite>'.$st_sub.'</cite>
                        </div>
                    </div>';
            }

            return $txt;
        }
    }
    st_reg_shortcode('st_testimonial','st_vc_testimonial');