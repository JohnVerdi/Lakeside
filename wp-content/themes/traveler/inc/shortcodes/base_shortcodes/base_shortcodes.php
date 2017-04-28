<?php

    if(!function_exists('st_sc_custom_meta'))
    {
        function st_sc_custom_meta($attr,$content=false)
        {
            $data = shortcode_atts(
                array(
                    'key' =>''
                ), $attr, 'st_custom_meta' );
            extract($data);
            if(!empty($key)){
                $data = get_post_meta(get_the_ID() , $key  ,true);
                return balanceTags($data);
            }

        }
        st_reg_shortcode('st_custom_meta','st_sc_custom_meta');
    }
    if (!function_exists('st_sc_admin_email')){
        function st_sc_admin_email(){
            return '<a class="contact_admin_email" href="mailto:'.get_bloginfo('admin_email').'" ><i class="fa fa-envelope-o"></i>  '.get_bloginfo('admin_email')."</a>";
        }
        st_reg_shortcode('admin_email' , 'st_sc_admin_email');
    }
    if (!function_exists('st_sc_languages_select')){
        function st_sc_languages_select(){
            return st()->load_template("menu/language_select" , null , array('container' =>"div" , "class"=>"top-user-area-lang nav-drop" )) ; 
        }
        st_reg_shortcode('languages_select' , 'st_sc_languages_select');
    }
    if (!function_exists('st_sc_social')){
        function st_sc_social($attr){
            $data = shortcode_atts(
                array(
                    'name' =>'',
                    'link' =>''
                    ) , $attr , 'social_link'
                );
            extract($data); 
            if( !empty($name)) {
                switch ($name) {
                    case 'facebook':
                        $icon = "fa fa-facebook" ; 
                        break;
                    case 'twitter':
                        $icon = "fa fa-twitter" ; 
                        break;
                    case 'youtube':
                        $icon = "fa fa-youtube-play" ; 
                        break;
                    default:
                        # code...
                        break;
                }
                return "<a class='top_bar_social' href='".$link."'><i class='".$icon."'></i></a>";
            }
        }
        st_reg_shortcode('social_link' ,'st_sc_social' );
    }
    if (!function_exists('st_sc_login_select')){
        function st_sc_login_select(){
            return st()->load_template("menu/login_select" , null , array('container' =>"div" )) ; 
        }
        st_reg_shortcode('login_select' , 'st_sc_login_select') ; 
    }
    if (!function_exists('st_sc_currency_select')){
        function st_sc_currency_select(){
            return st()->load_template("menu/currency_select" , null , array('container' =>"div" )) ; 
        }
        st_reg_shortcode('currency_select' , 'st_sc_currency_select') ; 
    } 




