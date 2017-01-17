<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STFramework
 *
 * Created by ShineTheme
 *
 */
if(!class_exists('STFramework')){
    /**
     * Class STFramework
     */
    class STFramework{

        /**
         * @var string
         */
        public $template_dir="st_templates";

        public $plugin_name="";



        /**
         *
         */
        function __construct()
        {
            
        }

        /**
        *
        * @since 1.1.7
        * @update 1.2.0
        */
        static function _class_init()
        {
            $libs=array(
                'core/class.assets',
                'core/class.optiontree',
                'core/class.template',
                'core/class.updater',
                'st-hook-functions',
                'st-hook-register',
                'plugins/BFI_Thumb',
                'plugins/otf_regen_thumbs',
                'plugins/class-tgm-plugin-activation',
                'st-required-plugins',
                'helpers/class.sthelper',
                'helpers/class.input',
                'helpers/class.string',
                'helpers/class.upload-fonticon'
            );

            self::load_libs($libs);
        }

        static function dir($url=false)
        {
            //$template = locate_template('/inc/'. $url);
            return get_template_directory().'/inc/'.$url;
        }

        /**
         *
         *
         *
         * @since 1.0.7
         * */
        static function dir_name($url=false)
        {
            return "inc/".$url;
        }

        function url($url=false)
        {
            return get_template_directory_uri().'/inc/'.$url;
        }

        /**
         * @param bool $url
         * @return string
         */
        function plugin_dir($url=false)
        {
            return  ABSPATH . 'wp-content/plugins/'.$this->plugin_name.'/'.$url;
        }

        function plugin_url($url=false)
        {
            return plugins_url().'/'.$this->plugin_name.'/'.$url;
        }

        static function load_libs($libs=array())
        {
            if(!empty($libs) and is_array($libs)){
                foreach($libs as $value){

                    get_template_part('inc/'.$value);

                }
            }
        }




        /*---------Begin Helper Functions----------------*/


        function get_option($option_id,$default=false){

            if(class_exists('OT_Loader') and function_exists('ot_options_id')){
                /* get the saved options */
                $options = get_option( ot_options_id() );

                /* look for the saved value */
                if ( isset( $options[$option_id] ) && '' != $options[$option_id] ) {

                    return $options[$option_id];

                }

                return $default;
            }
            return $default;
        }

        function load_template($slug,$name=false,$data=array())
        {
            if(is_array($data))
            extract($data);

            if($name){
                $slug=$slug.'-'.$name;
            }

            //Find template in folder st_templates/
            $template=locate_template($this->template_dir.'/'.$slug.'.php');

            if(!$template){
                //If not, find it in plugins folder
                $template=$this->plugin_dir().'/'.$slug.'.php';

            }


            //If file not found
            if(is_file($template))
            {
                ob_start();

                include $template;

                $data=@ob_get_clean();

                return $data;
            }


        }

        static function write_log ( $log )  {
            if ( true === WP_DEBUG ) {
                if ( is_array( $log ) || is_object( $log ) ) {
                    error_log( print_r( $log, true ) );
                } else {
                    error_log( $log );
                }
            }
        }
    }
}

