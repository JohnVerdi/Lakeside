<?php 
// login dropdown 
// from 1.1.9
if (empty($container)){$container = "div"; }
if (empty($class)) {$class = "top-user-area-avatar" ;}
$class_default = "nav-drop" ; 

$login_fb=st()->get_option('social_fb_login','on');
$login_gg=st()->get_option('social_gg_login','on');
$login_tw=st()->get_option('social_tw_login','on');

$is_user_nav = st()->get_option('enable_user_nav','on') ?>
<?php if($is_user_nav == 'on'): ?>
    <?php if(is_user_logged_in()):?>
    <?php echo '<'.$container.' class="'.$class.'">'; ?>    
        <?php
        $account_dashboard = st()->get_option('page_my_account_dashboard');
        $location='#';
        if(!empty($account_dashboard)){
            $location = get_permalink($account_dashboard);
        }
        ?>
        <a href="<?php echo esc_url($location) ?>">
            <?php
            $current_user = wp_get_current_user();
             echo st_get_profile_avatar($current_user->ID,40);
            //echo st_get_language('hi').', '.$current_user->display_name;
            printf(__('hi, %s',ST_TEXTDOMAIN),$current_user->display_name);
            ?>
        </a>
    <?php echo  '</'.esc_attr($container).'>' ;?>
    <?php echo  '<'.esc_attr($container).'>' ;?>
        <a class="btn-st-logout" href="<?php echo wp_logout_url(home_url())?>"><?php st_the_language('sign_out')?></a>
    <?php echo  '</'.$container.'>' ;?>
    <?php else: ?>
         <?php echo '<'.esc_attr($container).' class="'.esc_attr($class_default).'">'; ?>    
            <?php
            $enable_popup_login = st()->get_option('enable_popup_login','off');
            $page_login = st()->get_option('page_user_login');
            $page_user_register = st()->get_option('page_user_register');
            $login_modal = $res_modal = '';
            $page_login = esc_url(get_the_permalink($page_login));
            $page_user_register = esc_url(get_the_permalink($page_user_register));
            if($enable_popup_login == 'on'){
                $login_modal = 'data-toggle="modal" data-target="#login_popup"';
                $res_modal = 'data-toggle="modal" data-target="#register_popup"';
                $page_login = $page_user_register = 'javascript:void(0)';
            }
            ?>
            <a href="#" onclick="return false;"><?php echo __("Account", ST_TEXTDOMAIN);?><i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i></a>
            <ul class="list nav-drop-menu user_nav_big social_login_nav_drop" >
                <li><a  class="" <?php echo ($login_modal); ?>  href="<?php echo ($page_login) ?>"><?php echo __("Login", ST_TEXTDOMAIN);?></a></li>
                <li><a  class="" <?php echo ($res_modal); ?>  href="<?php echo ($page_user_register) ?>" ><?php echo __("Sign Up", ST_TEXTDOMAIN);?></a></li>

                <?php /*if($login_fb=="on"): */?><!--
                    <li><a onclick="return false" class="btn_login_fb_link login_social_link" href="<?php /*echo STSocialLogin::get_provider_login_url('Facebook') */?>"><?php /*st_the_language('connect_with')*/?> <i class="fa fa-facebook"></i></a></li>
                <?php /*endif;*/?>

                <?php /*if($login_gg=="on"): */?>
                    <li><a onclick="return false" class="btn_login_gg_link login_social_link" href="<?php /*echo STSocialLogin::get_provider_login_url('Google') */?>"><?php /*st_the_language('connect_with')*/?> <i class="fa fa-google-plus"></i></a></li>
                <?php /*endif;*/?>

                <?php /*if($login_tw=="on"): */?>
                    <li><a onclick="return false" class="btn_login_tw_link login_social_link" href="<?php /*echo STSocialLogin::get_provider_login_url('Twitter') */?>"><?php /*st_the_language('connect_with')*/?> <i class="fa fa-twitter"></i></a></li>
                --><?php /*endif;*/?>
                <?php if(defined('WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL')) { ?>
                <?php if($login_fb=="on"): ?>
                    <li class="social_img"><a onclick="return false" class="btn_login_fb_link login_social_link" href="<?php echo STSocialLogin::get_provider_login_url('Facebook') ?>"><img src="<?php echo get_template_directory_uri()."/img/social/facebook-logo.jpg"; ?>"/></a></li>
                <?php endif;?>

                <?php if($login_gg=="on"): ?>
                    <li class="social_img"><a onclick="return false" class="btn_login_gg_link login_social_link" href="<?php echo STSocialLogin::get_provider_login_url('Google') ?>"><img src="<?php echo get_template_directory_uri()."/img/social/google-plus.jpg"; ?>"/></a></li>
                <?php endif;?>

                <?php if($login_tw=="on"): ?>
                    <li class="social_img"><a onclick="return false" class="btn_login_tw_link login_social_link" href="<?php echo STSocialLogin::get_provider_login_url('Twitter') ?>"><img src="<?php echo get_template_directory_uri()."/img/social/twitter-logo.png"; ?>"/></a></li>
                <?php endif;?>
                <?php }; ?>
            </ul>
        <?php echo  '</'.esc_attr($container).'>' ;?>
    <?php endif;?>
<?php endif; ?>