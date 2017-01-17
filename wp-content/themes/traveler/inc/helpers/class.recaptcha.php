<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STRecaptcha
 *
 * Created by ShineTheme
 *
 */
if(!class_exists('STRecaptcha'))
{
    class STRecaptcha
    {
        static $key='6LfOAAETAAAAAGMpKXOEfaXJAjmQxD8oG7_zrO1w';
        static $secret_key='6LfOAAETAAAAAOoVLsmhL8a2xR4NUvWRKR2nNGn3';


        static function init()
        {
            $key=st()->get_option('recaptcha_key');
            $skey=st()->get_option('recaptcha_secretkey');
            if($key and $skey)
            {

                self::$key=$key;
                self::$secret_key=$skey;
            }
            add_action('wp_enqueue_scripts',array(__CLASS__,'register_script'));
        }

        static function register_script()
        {
            if(is_singular())
            {

                wp_register_script('recaptchar','https://www.google.com/recaptcha/api.js',array(),null,true);

                wp_localize_script('recaptchar','st_recaptchar',array(
                    'sitekey'=>self::$key,
                    'recaptcha_ids'=>array()
                ));
                wp_enqueue_script('recaptchar');
            }
        }

        static function get_captcha($post_id=false)
        {
            return '<div id="st_recaptchar_'.$post_id.'" class="g-recaptcha" data-sitekey="'.self::$key.'"></div>';
        }


        static function validate_captcha()
        {
            $url='https://www.google.com/recaptcha/api/siteverify';

            $url.='?secret='.self::$secret_key;
            $url.='&response='.$_POST['g-recaptcha-response'];

            $url.='&remoteip='.$_SERVER['REMOTE_ADDR'];

            $data=wp_remote_get($url);

            if(!is_wp_error($data)){
                $body=wp_remote_retrieve_body($data);

                $body_obj=json_decode($body);

                if(isset($body_obj->success) and $body_obj->success){
                    return array('status'=>1);
                }else{
                    return array('status'=>0,'message'=>__('Your captcha is not correct',ST_TEXTDOMAIN));
                }
            }else{
                return array(
                    'status'=>0,
                    'message'=>__('Can not verify reCAPTCHA',ST_TEXTDOMAIN)
                );
            }
        }
    }

    STRecaptcha::init();
}
