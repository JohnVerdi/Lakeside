<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Team", ST_TEXTDOMAIN),
            "base" => "st_team",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Element style", ST_TEXTDOMAIN),
                    "param_name" => "st_style",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-12',
                    'value'=>array(
                        __('--Default--',ST_TEXTDOMAIN)=>'',
                        __("Tour box style",ST_TEXTDOMAIN)     =>"st_tour_ver",
                    )
                ),
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
                    "heading" => __("Position", ST_TEXTDOMAIN),
                    "param_name" => "st_position",
                    "description" =>"",
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Social Effect ", ST_TEXTDOMAIN),
                    "param_name" => "st_effect",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-6',
                    'value'=>array(
                        __("Hover",ST_TEXTDOMAIN)   =>"",
                        __("Hold",ST_TEXTDOMAIN)    =>"hover-hold",
                    )
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Position Social", ST_TEXTDOMAIN),
                    "param_name" => "st_position_social",
                    "description" =>"",
                    'edit_field_class'=>'vc_col-sm-6',
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __("Top Left",ST_TEXTDOMAIN)     =>"-top-left",
                        __("Top Right",ST_TEXTDOMAIN)    =>"-top-right",
                        __("Bottom Left",ST_TEXTDOMAIN)  =>"-bottom-left",
                        __("Bottom Right",ST_TEXTDOMAIN) =>"-bottom-right",
                        __("Center",ST_TEXTDOMAIN)       =>"",
                        __("Center Top",ST_TEXTDOMAIN)   =>"-center-top",
                        __("Center Bottom",ST_TEXTDOMAIN)=>"-center-bottom",
                    )
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Facebook", ST_TEXTDOMAIN),
                    "param_name" => "st_facebook",
                    "description" =>"",
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Twitter", ST_TEXTDOMAIN),
                    "param_name" => "st_twitter",
                    "description" =>"",
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Google Plus", ST_TEXTDOMAIN),
                    "param_name" => "st_google",
                    "description" =>"",
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Instagram", ST_TEXTDOMAIN),
                    "param_name" => "st_instagram",
                    "description" =>"",
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("LinkedIn", ST_TEXTDOMAIN),
                    "param_name" => "st_linkedin",
                    "description" =>"",
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("Youtube", ST_TEXTDOMAIN),
                    "param_name" => "st_youtube",
                    "description" =>"",
                ),
                array(
                    "type" => "textarea",
                    "holder" => "div",
                    "heading" => __("Other Social Link", ST_TEXTDOMAIN),
                    "param_name" => "st_other_social",
                    "description" =>"Ex : ".htmlentities("<li><a href='#' class='fa fa-facebook box-icon-normal round'></a></li>").'<br>Social icons <a target="_blank"  href="http://fortawesome.github.io/Font-Awesome/icons/" >click here</a>',
                ),
                array(
                    'type'  => 'textarea',
                    'holder' => 'div',
                    'heading'   => "Description",
                    'param_name'    => 'st_description',
                    'description'   => ''
                ),
            )
        ) );
    }

    if(!function_exists('st_vc_team')){
        function st_vc_team($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'st_style'=> '',
                    'st_avatar' =>0,
                    'st_name' => 0,
                    'st_position'=>'',
                    'st_position_social'=>'-top-left',
                    'st_effect'=>'',
                    'st_facebook'=>'',
                    'st_twitter'=>'',
                    'st_google'=>'',
                    'st_instagram'=>'',
                    'st_linkedin'=> '',
                    'st_youtube'=> '',
                    'st_other_social'=>'',
                    'st_description'=>''
                ), $attr, 'st_team' );
            extract($data);

            $img = wp_get_attachment_image_src($st_avatar,'full');
            $txt ="";
            switch ($st_style) {
                default :
                    $round_box = "box-icon-normal round";
                    $list_social ='';

                    if(!empty($st_facebook)){
                        $list_social .='<li><a href="'.$st_facebook.'" class="fa fa-facebook '.$round_box.'"></a></li>';
                    }
                    if(!empty($st_twitter)){
                        $list_social .='<li><a href="'.$st_twitter.'" class="fa fa-twitter '.$round_box.'"></a></li>';
                    }
                    if(!empty($st_google)){
                        $list_social .='<li><a href="'.$st_google.'" class="fa fa-google-plus '.$round_box.'"></a></li>';
                    }
                    if(!empty($st_instagram)){
                        $list_social .='<li><a href="'.$st_instagram.'" class="fa fa-instagram '.$round_box.'"></a></li>';
                    }
                    if(!empty($st_linkedin)){
                        $list_social .='<li><a href="'.$st_linkedin.'" class="fa fa-linkedin '.$round_box.'"></a></li>';
                    }
                    if(!empty($st_youtube)){
                        $list_social .='<li><a href="'.$st_youtube.'" class="fa fa-youtube '.$round_box.'"></a></li>';
                    }

                    if(!empty($st_other_social)){
                        $list_social .=$st_other_social;
                    }
                    $txt .=  '<div class="thumb text-center st_team">
                        <header class="thumb-header hover-img">
                            <img class="round" src="'.bfi_thumb($img[0],array('width'=>300,'height'=>300)).'" alt="'.$st_name.'" title="'.$st_name.'" />
                            <ul class="hover-icon-group'.$st_position_social.' '.$st_effect.' ">
                                    '.$list_social.'
                            </ul>
                        </header>
                        <div class="thumb-caption">
                            <h5 class="thumb-title">'.$st_name.'</h5>
                            <p class="thumb-meta text-small">'.$st_position.'</p>
                        </div>
                  </div>';
                    break;
                case "st_tour_ver":
                    $list_social ='<div class="st_social style1">';
                    if(!empty($st_facebook)){
                        $list_social .='<a href="'.$st_facebook.'" class="'.$st_style .' "><i class="fa fa-facebook"></i></a>';
                    }
                    if(!empty($st_twitter)){
                        $list_social .='<a href="'.$st_twitter.'" class="'.$st_style .'"><i class="fa fa-twitter"></i></a>';
                    }
                    if(!empty($st_google)){
                        $list_social .='<a href="'.$st_google.'" class="'.$st_style .' "><i class="fa fa-google-plus"></i></a>';
                    }
                    if(!empty($st_instagram)){
                        $list_social .='<a href="'.$st_instagram.'" class="'.$st_style .'"><i class="fa fa-instagram "></i></a>';
                    }
                    if(!empty($st_linkedin)){
                        $list_social .='<a href="'.$st_linkedin.'" class="'.$st_style .'"><i class="fa fa-linkedin"></i></a>';
                    }
                    if(!empty($st_youtube)){
                        $list_social .='<a href="'.$st_youtube.'" class="'.$st_style .'"><i class="fa fa-youtube "></i></a>';
                    }

                    if(!empty($st_other_social)){
                        $list_social .=$st_other_social;
                    }
                    $list_social .="</div>";
                    $txt .=
                    '<div class="st_team_item_'.$st_style.'">
                        '.wp_get_attachment_image($st_avatar ,'full').'
                        <div class="st_name"><h5>'.$st_name.'</h5></div>
                        <div class="st_position"><small><i>'.$st_position.'</i></small></div>
                        <div class="st_description">'.$st_description.'</div>
                        '.$list_social.'
                    </div>';

                    break;

            }





            return $txt;
        }
    }
    st_reg_shortcode('st_team','st_vc_team');