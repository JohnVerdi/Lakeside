<?php 
if(!class_exists('ST_Location_From_To')){
	class ST_Location_From_To extends BTOptionField{
		static  $instance = null;
        public $curent_key;

        function __construct(){
            parent::__construct(__FILE__);

            parent::init(array(
                'id'=>'st_location_from_to',
                'name'          =>__('Pick Up - Drop Off',ST_TEXTDOMAIN)
            ));


            add_action('admin_enqueue_scripts',array($this,'add_scripts'));
        }

        static function instance()
        {
            if(self::$instance==null)
            {
                self::$instance=new self();
            }

            return self::$instance;
        }

        function add_scripts(){
            if(get_post_type() == 'st_cars'){
                wp_register_script('select2.js', $this->_url . '/js/select2/select2.min.js', array('jquery'), NULL, TRUE);

                $lang=get_locale();
                $lang_file=$this->_dir.'/js/select2/select2_locale_'.$lang.'.js';
                if(file_exists($lang_file)){
                    wp_register_script('select2-lang',$this->_url.'/js/select2/select2_locale_'.$lang.'.js',array('jquery','select2.js'),null,true);
                }else{
                    $locale_array=explode('_',$lang);
                    if(!empty($locale_array) and $locale_array[0] and $locale_array[0]!='en'){
                        $locale=$locale_array[0];

                        $lang_file=$this->_dir.'/js/select2/select2_locale_'.$locale.'.js';
                        if(file_exists($lang_file))
                            wp_register_script('select2-lang',$this->_url.'/js/select2/select2_locale_'.$locale.'.js',array('jquery','select2.js'),null,true);
                    }
                }
                wp_register_script('st-location-init', $this->_url . '/js/custom.js', array('jquery', 'select2.js'), NULL, TRUE);

                wp_register_style('st-location-bootstrap', $this->_url . '/css/bootstrap.min.css');
                wp_register_style('st-select2', $this->_url . '/js/select2/select2.css');
                wp_register_style('st-location-css', $this->_url . '/css/custom.css');

            }
            
        }
	}

    ST_Location_From_To::instance();

    if(!function_exists('ot_type_st_location_from_to')){
        function ot_type_st_location_from_to($args = array()){
            $default = array(
                'field_name' => ''
            );
            $args = wp_parse_args($args, $default);

            wp_enqueue_script('st-location-init');

            wp_enqueue_style('st-location-bootstrap');
            wp_enqueue_style('st-select2');
            wp_enqueue_style('st-selectize-bootstrap');
            wp_enqueue_style('st-location-css');

            ST_Location_From_To::instance()->curent_key=$args['field_name'];

            echo ST_Location_From_To::instance()->load_view(false,array('args'=>$args));
        }
    }   

    if(!function_exists('ot_type_st_location_from_to_html')){
        function ot_type_st_location_from_to_html($args = array()){
            $default = array(
                'field_name' => ''
            );
            $args = wp_parse_args($args, $default);

            wp_enqueue_script('st-location-init');

            wp_enqueue_style('st-location-bootstrap');
            wp_enqueue_style('st-select2');
            wp_enqueue_style('st-selectize-bootstrap');
            wp_enqueue_style('st-location-css');

            ST_Location_From_To::instance()->curent_key=$args['field_name'];

            echo ST_Location_From_To::instance()->load_view(false,array('args'=>$args));
        }
    }   
}
?>