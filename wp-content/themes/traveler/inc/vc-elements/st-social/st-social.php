<?php
return;
if (function_exists('vc_map')){
    vc_map(array(
        "name"            => __( "ST Social Links" , ST_TEXTDOMAIN ) ,
        "base"            => "st_social" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => 'Shinetheme' ,
        "params"        => array(
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Element style", ST_TEXTDOMAIN),
                "param_name" => "style",
                "description" =>"",
                'value'=>array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('Top bar',ST_TEXTDOMAIN)=>'topbar',
                    __('Footer',ST_TEXTDOMAIN)=>'footer',
                    __('Style 1',ST_TEXTDOMAIN)=>'style1',
                    __('Style 2',ST_TEXTDOMAIN)=>'style2',
                ),
            ),
        )
    ));
}
if (!function_exists('st_social_fn')){
    function st_social_fn($attr){
        extract($attr);
        if (empty($style)) $style = "style1";
        $social_links = st()->get_option('social_pages' , '');
        $html = "<div class='st_social ".$style."'>";
        switch ($style){
            case "footer":
                if (!empty($social_links) and is_array($social_links)){
                    foreach ($social_links as $item ){
                        extract($item);
                        if(!empty($title) and !empty($font_class) and !empty($link)){
                            $html .= "<i class='".$font_class."'></i>";
                            $html .= "&nbsp;&nbsp;&nbsp;";
                            $html .= "<a href='".$link."'>".$title."</a>";
                            $html .= "<br>";
                        }
                    }
                }
                break ;
            case "topbar":
                if (!empty($social_links) and is_array($social_links)){
                    foreach ($social_links as $item ){
                        extract($item);
                        if(!empty($title) and !empty($font_class) and !empty($link)){
                            $icon = "<i class='".$font_class."'></i>";
                            $html .= "<a href='".$link."'>".$icon."</a>";
                        }
                    }
                }
                break;
            case "style2":
                if (!empty($social_links) and is_array($social_links)){
                    foreach ($social_links as $item ){
                        extract($item);
                        if(!empty($title) and !empty($font_class) and !empty($link)){
                            $icon = "<i class='".$font_class."'></i>";
                            $html .= "<a class='st_tour_ver' href='".$link."'>".$icon."</a>";
                        }
                    }
                }
                break ;
            default:
                if (!empty($social_links) and is_array($social_links)){
                    foreach ($social_links as $item ){
                        extract($item);
                        if(!empty($title) and !empty($font_class) and !empty($link)){
                            $icon = "<i class='".$font_class."'></i>";
                            $html .= "<a class='st_tour_ver' href='".$link."'>".$icon."</a>";
                        }
                    }
                }
                break ;
        }
        $html .="</div>";
        return $html;
    }

}
st_reg_shortcode('st_social' , 'st_social_fn');