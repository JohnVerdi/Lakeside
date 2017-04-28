<?php 
if(!class_exists('ST_Tour_Calendar_Field')){
	class ST_Tour_Calendar_Field{
		public  $url;
        public $dir;

        function __construct(){

            $this->dir = st()->dir('plugins/ot-custom/fields/tour-calendar');
            $this->url = st()->url('plugins/ot-custom/fields/tour-calendar');


            add_action('admin_enqueue_scripts',array($this,'add_scripts'));
        }
        function init(){

            if( !class_exists( 'OT_Loader' ) ) return false;

            add_filter( 'ot_st_tour_calendar_unit_types', array($this, 'ot_post_select_ajax_unit_types'), 10, 2 );

            add_filter( 'ot_option_types_array', array($this, 'ot_add_custom_option_types'));

        }
        function add_scripts(){
            if(get_post_type() == 'st_tours'){
                wp_register_script('fullcalendar', get_template_directory_uri() . '/js/fullcalendar-2.4.0/fullcalendar.min.js', array('jquery'), NULL, TRUE);
                wp_enqueue_script('fullcalendar-lang', get_template_directory_uri() . '/js/fullcalendar-2.4.0/lang-all.js', array('jquery'), NULL, TRUE);
                wp_register_script('fullcalendar-lang', get_template_directory_uri() . '/js/fullcalendar-2.4.0/lang-all.js', array('jquery'), NULL, TRUE);
                wp_register_script('moment.js', get_template_directory_uri() . '/js/fullcalendar-2.4.0/lib/moment.min.js', array('jquery'), NULL, TRUE);
                wp_register_script('date.js', get_template_directory_uri() . '/js/date.js', array('jquery'), NULL, TRUE);
                wp_register_script('st-availablility-init', $this->url . '/js/custom.js', array('jquery', 'moment.js', 'fullcalendar','date.js','fullcalendar-lang'), NULL, TRUE);

                wp_register_style('fullcalendar', get_template_directory_uri() . '/js/fullcalendar-2.4.0/fullcalendar.min.css');
                wp_register_style('st-availablility-init', $this->url . '/css/custom.css');
                wp_register_style('st-availablility-bootstrap', $this->url . '/css/bootstrap.min.css');

                $locale = get_locale();
                if($locale and $locale!='en') {
                    $locale_array = explode('_',$locale);
                    if(!empty($locale_array) and $locale_array[0]){
                        $locale = $locale_array[0];
                    }
                }
                wp_localize_script('jquery', 'st_params', array(
                    'locale'            =>$locale,
                    'text_refresh'=>__("Refresh",ST_TEXTDOMAIN)
                ));
            }
            
        }

        function ot_post_select_ajax_unit_types($array, $id){
            return apply_filters( 'st_tour_calendar', $array, $id );
        }

        function ot_add_custom_option_types( $types ) {
            $types['st_tour_calendar'] = __('Availability tour',ST_TEXTDOMAIN);

            return $types;
        }

        function load_view($view = false, $data = array()){

            if(!$view) $view = 'index';

            $file_name = $this->dir.'/views/'.$view.'.php';

            if(file_exists($file_name)){
                extract($data);

                ob_start();

                include $file_name;

                return @ob_get_clean();
            }
        }
	}

    $calendar_tour = new ST_Tour_Calendar_Field();
    $calendar_tour->init();

    if(!function_exists('ot_type_st_tour_calendar')){
        function ot_type_st_tour_calendar($args = array()){
            $calendar_tour = new ST_Tour_Calendar_Field();
            $default = array(
                'field_name' => ''
            );
            $args = wp_parse_args($args, $default);

            wp_enqueue_script('st-availablility-init');
            wp_enqueue_style('fullcalendar');
            wp_enqueue_style('st-availablility-bootstrap');
            wp_enqueue_style('st-availablility-init');

            echo $calendar_tour->load_view(false, $args);
        }
    }    
}
?>