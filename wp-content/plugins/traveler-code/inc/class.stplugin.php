<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 11/13/14
 * Time: 9:11 AM
 */
class STPlugin{

    public $plugin_url;
    public  $plugin_dir;

    function __construct($file)
    {
        $this->plugin_url=plugin_dir_url($file);
        $this->plugin_dir=plugin_dir_path($file);

        $file=array(

            'custom-posttype',
            'inc/st.shortcode',
            'inc/class.language',

        );

        $this->load_libs($file);

//        $this->loadWidgets();
//
//        self::loadShortCodes();
//
//        if(class_exists('Vc_Manager'))
//        {
//            add_action('init',array($this,'loadVcElements'),20);
//        }


    }

    function loadWidgets($exclude=array())
    {
        $dirs = array_filter(glob($this->dir().'/widgets/*'), 'is_dir');

        $exclude=apply_filters('st_exclude_widgets',$exclude);

        $hasExclude=false;

        if(is_array($exclude) and !empty($exclude))
        {
            $hasExclude=true;
        }

        if(!empty($dirs))
        {
            foreach($dirs as $key=>$value)
            {
                $dirname=basename($value);

                if(!$hasExclude or !in_array($dirname,$exclude))
                {
                    if(is_file($value.'/'.$dirname.'.php')){

                        //echo get_template_directory().'/inc/widgets/'.$value.'/'.$value.'.php';
                        require_once $value.'/'.$dirname.'.php';
                    }
                }



            }
        }
    }

    function dir($url=false)
    {

        return $this->plugin_dir.$url;
    }

    function url($url=false)
    {
        return $this->plugin_url.$url;
    }



    function load_libs($libs)
    {

        if(!empty($libs)){
            foreach($libs as $value){

                $file=$this->dir($value.'.php');


                if(file_exists($file)){

                    require_once($file);

                }

            }
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
    function loadShortCodes($exclude=array())
    {
        $dirs = array_filter(glob($this->plugin_dir.'/shortcodes/*'), 'is_dir');

        $exclude=apply_filters('st_exclude_shortcodes',$exclude);

        $hasExclude=false;

        if(is_array($exclude) and !empty($exclude))
        {
            $hasExclude=true;
        }

        if(!empty($dirs))
        {
            foreach($dirs as $key=>$value)
            {
                $dirname=basename($value);

                if(!$hasExclude or !in_array($dirname,$exclude))
                {
                    if(is_file($value.'/'.$dirname.'.php')){

                        //echo get_template_directory().'/inc/widgets/'.$value.'/'.$value.'.php';
                        require_once $value.'/'.$dirname.'.php';
                    }
                }


            }
        }
    }



    function loadVcElements($exclude=array())
    {
        $dirs = array_filter(glob($this->plugin_dir.'/vc-elements/*'), 'is_dir');

        $exclude=apply_filters('st_exclude_vcelements',$exclude);

        $hasExclude=false;

        if(is_array($exclude) and !empty($exclude))
        {
            $hasExclude=true;
        }

        if(!empty($dirs))
        {
            foreach($dirs as $key=>$value)
            {
                $dirname=basename($value);

                if(!$hasExclude or !in_array($dirname,$exclude))
                {
                    $file=$value.'/'.$dirname.'.php';

                    if(is_file($file)){

                        //echo get_template_directory().'/inc/widgets/'.$value.'/'.$value.'.php';
                        require_once $file;
                    }
                }
            }
        }
    }

}