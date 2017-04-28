<?php
class STImporter
{

    static $plugin_dir;
    static $plugin_url;
    static $page_id_option;

    static function register()
    {
        add_action('wp_ajax_st_import_content',array(__CLASS__,'init'));
    }

    static function init()
    {
        self::$plugin_dir=plugin_dir_path(__FILE__ );
        self::$plugin_url=plugin_dir_url(__FILE__ );
        self::$page_id_option = "st-importer-content";
        self::doImport() ;
        die;
    }
	//add css javascript
	static function add_css(){
		add_action('admin_print_scripts',array('STImporter','st_add_scripts_import'));
	}
	static function st_add_scripts_import(){
		     wp_enqueue_script('js_import',plugin_dir_url(__FILE__ ).'js.js',array('jquery'),false,true);
			 wp_enqueue_style('css_import',plugin_dir_url(__FILE__ ).'style.css');
	}
	// function import
    static function doImport(){
        global $wpdb;
        set_time_limit(0) ;
        include "import.config.php";

		$import_config=apply_filters('st_demo_packages_config_'.$_REQUEST['version'],array());
		if(!empty($import_config)) extract($import_config);

        if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); // we are loading importers
        if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist
            $wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            include $wp_importer;
        }
        if ( ! class_exists('WP_Import') ) { // if WP importer doesn't exist
            $wp_import = self::$plugin_dir. '/wordpress-importer.php';
            include $wp_import;
        }
        if ( class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) { // check for main import class and wp import class
		    $step = $_REQUEST['step'];
		    if($step == '1'){
				//Update theme_options
				$data_json = self::$plugin_url .'/xml/'.$_REQUEST['version'].'/theme_options.json'; // widgets data file


                $data_res = wp_remote_get( $data_json );
                $options=unserialize(ot_decode($data_res['body'])); // unserialize
				if(!empty($options)){
                    update_option( ot_options_id(), $options ); // and overwrite the current theme-options
                    $attr = 'a:8:{s:16:"hotel_facilities";a:3:{s:4:"name";s:16:"Hotel Facilities";s:9:"post_type";a:1:{i:0;s:8:"st_hotel";}s:12:"hierarchical";i:1;}s:15:"room_facilities";a:3:{s:4:"name";s:15:"Room facilities";s:9:"post_type";a:1:{i:0;s:10:"hotel_room";}s:12:"hierarchical";i:1;}s:12:"car_features";a:3:{s:4:"name";s:12:"Car Features";s:9:"post_type";a:1:{i:0;s:7:"st_cars";}s:12:"hierarchical";i:1;}s:17:"default_equipment";a:3:{s:4:"name";s:17:"Default Equipment";s:9:"post_type";a:1:{i:0;s:7:"st_cars";}s:12:"hierarchical";i:1;}s:11:"hotel_theme";a:3:{s:4:"name";s:11:"Hotel Theme";s:9:"post_type";a:1:{i:0;s:8:"st_hotel";}s:12:"hierarchical";i:1;}s:9:"amenities";a:3:{s:4:"name";s:9:"Amenities";s:9:"post_type";a:1:{i:0;s:9:"st_rental";}s:12:"hierarchical";i:1;}s:11:"suitability";a:3:{s:4:"name";s:11:"Suitability";s:9:"post_type";a:1:{i:0;s:9:"st_rental";}s:12:"hierarchical";i:1;}s:11:"attractions";a:3:{s:4:"name";s:11:"Attractions";s:9:"post_type";a:1:{i:0;s:11:"st_activity";}s:12:"hierarchical";i:1;}}';
                    $attr = unserialize($attr);
                    update_option( 'st_attribute_taxonomy', $attr );

                    echo json_encode( array(
                            'status'   =>"ok",
                            'messenger'=>"Importing the demo theme options... <span>DONE!</span><br>",
                            'next_url' => admin_url("admin.php?page=".self::$page_id_option."&start_import=1&step=".($step+1)."&version=".$_REQUEST['version']),
                            'next_post_data'=>array(
                                'action'=>'st_import_content',
                                'step'=>$step+1,
                                'version'=>$_REQUEST['version']
                            )
                        )
                    );
                }else{
                    echo json_encode( array(
                            'status'   =>"ok",
                            'messenger'=>"File theme_options null content !",
                            'next_url' => admin_url("admin.php?page=".self::$page_id_option."&start_import=1&step=".($step+1)."&version=".$_REQUEST['version']),
                            'next_post_data'=>array(
                                'action'=>'st_import_content',
                                'step'=>$step+1,
                                'version'=>$_REQUEST['version']
                            )
                        )
                    );
                }
            }
			if($step == '2'){
				$dir = self::$plugin_dir.'/xml/'.$_REQUEST['version'];
				$scan_files =  glob($dir.'/data/*.{xml}', GLOB_BRACE);
                $ds_file=array();
                if(!empty($scan_files) and is_array($scan_files))
                {
                    foreach($scan_files as $key){
                        $ds_file[]=basename($key);
                    }
                }
				if(count($ds_file) > 0){
				   $next_url = admin_url(   "admin.php?page=".self::$page_id_option."&start_import=1&step=".($step+1)."&file=0&file_name=".$ds_file[0]."&version=".$_REQUEST['version']   );
                    $new_step=$step+1;
                    $file_number=0;
                    $file_name_post=$ds_file[0];
				}else{
				   $next_url = admin_url(   "admin.php?page=".self::$page_id_option."&start_import=1&step=".($step+2)."&version=".$_REQUEST['version'] );
                    $new_step=$step+2;
                    $file_number=false;
                    $file_name_post=false;
				}

				if(file_exists( self::$plugin_dir.'/xml/'.$_REQUEST['version'] .'/widget_data.json' ))
				{
					if(function_exists('wpsites_widgets_init'))
					{
						wpsites_widgets_init();
					}
					// Add data to widgets
					$widgets_json = self::$plugin_url .'/xml/'.$_REQUEST['version'].'/widget_data.json'; // widgets data file
					$widgets_json = wp_remote_get( $widgets_json );
					$widget_data = $widgets_json['body'];
					$data_object=json_decode($widget_data);
					$import_widgets = wie_import_data( $data_object );
					echo json_encode( array(
										'status'   =>"ok",
										'messenger'=>"Importing the demo widgets... <span>DONE!</span>.<br>",
										'next_url' => $next_url,
										'file'     => $ds_file,
                                        'next_post_data'=>array(
                                            'action'=>'st_import_content',
                                            'step'=>$new_step,
                                            'version'=>$_REQUEST['version'],
										    'file'     => $file_number,
                                            'file_name'=>$file_name_post
                                        )
									   )
			         );
				}else{	
				    echo json_encode( array(
										'status'   =>"ok",
										'messenger'=>"",
										'next_url' => $next_url,
										'file'     => $ds_file,
                                        'next_post_data'=>array(
                                            'action'=>'st_import_content',
                                            'step'=>$new_step,
                                            'version'=>$_REQUEST['version'],
                                            'file'     => $file_number,
                                            'file_name'=>$file_name_post
                                        )
                        )
			         );
				}
			}
		    if($step == '3'){
                $dir = self::$plugin_dir.'/xml/'.$_REQUEST['version'];
				$stt_file = $_REQUEST['file'];
				$scan_files =  glob($dir.'/data/*.{xml}', GLOB_BRACE);
                $ds_file=array();
                if(!empty($scan_files) and is_array($scan_files))
                {
                    foreach($scan_files as $key){
                        $ds_file[]=basename($key);
                    }
                }
				if(count($ds_file) > ($stt_file+1)){
                   $file_name=basename($ds_file[($stt_file+1)]);
				   $next_url = admin_url(   "admin.php?page=".self::$page_id_option."&start_import=1&step=".$step."&file=".($stt_file+1)."&file_name=".$file_name."&version=".$_REQUEST['version']   );
                    $new_step=$step;
                    $file_number=$stt_file+1;
                    $file_name_post=$file_name;
				}else{
				   $next_url = admin_url(   "admin.php?page=".self::$page_id_option."&start_import=1&step=".($step+1)."&version=".$_REQUEST['version'] );
                    $new_step=$step+1;
                    $file_number=false;
                    $file_name_post=false;
				}
				ob_start();
				    $importer = new WP_Import();
					$theme_xml = self::$plugin_dir.'/xml/'.$_REQUEST['version'].'/data/'.$_REQUEST['file_name'];
					$importer->fetch_attachments = true;
			        $importer->import($theme_xml);
				ob_clean();
				echo json_encode( array(
										'status'   =>"ok",
										'messenger'=>"Importing data package ".$_REQUEST['file_name']." ".($stt_file+1)." of ".(count($ds_file))."... <span>DONE!</span><br>",
										'next_url' => $next_url,
										'file'     => $file_number,
                                        'next_post_data'=>array(
                                            'action'=>'st_import_content',
                                            'step'=>$new_step,
                                            'version'=>$_REQUEST['version'],
                                            'file'     => $file_number,
                                            'file_name'=>$file_name_post
                                        )
									   )
			         );
			}
		    if($step == '4'){
				 //  Set imported menus to registered theme locations
				$locations = get_theme_mod( 'nav_menu_locations' ); // registered menu locations in theme
				$menus = wp_get_nav_menus(); // registered menus
				if($menus) {
					foreach($menus as $menu) { // assign menus to theme locations
						foreach($st_over_menus as $key => $st_over_menu){
						   if( $menu->name == $key ) {
						      $locations[$st_over_menu] = $menu->term_id;
						   }
						}
					}
				}
				set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations
				$next_url = admin_url(   "admin.php?page=".self::$page_id_option."&start_import=1&step=".($step+1)."&version=".$_REQUEST['version'] );
				echo json_encode( array(
										'status'   =>"ok",
										'messenger'=>"Setting menu... <span>DONE!</span><br>",
										'next_url' => $next_url,
                                        'next_post_data'=>array(
                                            'action'=>'st_import_content',
                                            'step'=>$step+1,
                                            'version'=>$_REQUEST['version'],
                                        )
                    )
			         );
			}
			if($step == '5'){
			   // Set reading options	
			   if($homepage_default != ""){
			      $homepage = get_page_by_title( $homepage_default );
				  if($homepage->ID) {
					  update_option('show_on_front', 'page');
					  update_option('page_on_front', $homepage->ID); // Front Page
				  }
			   }
			   if($homepost_default != ""){
				  $homepage = get_page_by_title( $homepost_default );
				  if($homepage->ID) {
					  update_option('show_on_front', 'page');
					  update_option('page_for_posts', $homepage->ID); // Blog Page
				  }
			   }
                $next_url = admin_url(   "admin.php?page=".self::$page_id_option."&start_import=1&step=".($step+1)."&version=".$_REQUEST['version'] );
                echo json_encode( array(
										'status'   =>"ok",
										'messenger'=>"Setting reading options... <span>DONE!</span><br>",
										'next_url' => $next_url,
                                        'next_post_data'=>array(
                                            'action'=>'st_import_content',
                                            'step'=>$step+1,
                                            'version'=>$_REQUEST['version'],
                                        )
									   )
					 );
			}
            if($step == '6'){
                self::st_update_icon_import();
                /*if(class_exists('STDuplicateData')){
                    ob_start();
                        $duplicate = new STDuplicateData();
                        $duplicate->_duplicate_ajax(true);
                    ob_clean();
                }*/
                echo json_encode( array(
                        'status'   =>"ok",
                        'messenger'=>"Update meta icon... <span>DONE!</span><br>
                                                      <br><span>All done!</span>",
                        'next_url' => "",
                    )
                );
            }
        }
    }

    static function st_update_icon_import(){
        $url_file = self::$plugin_url.'xml/'.$_REQUEST['version'] .'/data_icon.shinetheme';
        $url_file_DIR = self::$plugin_dir.'xml/'.$_REQUEST['version'] .'/data_icon.shinetheme';
        $url_file = str_ireplace('\\','/',$url_file);
        if(file_exists( $url_file_DIR ))
        {
            $data_res = wp_remote_get( $url_file );
            $list_icon = unserialize($data_res['body']);
            $taxonomies = get_taxonomies();
            $list_term = get_terms($taxonomies);
            foreach($list_term as $k=>$v){
                foreach($list_icon as $key => $value){
                    if($v->slug == $key){
                        if(!empty($value)){
                            update_option('tax_meta_'.$v->term_id,array('st_icon'=>$value));
                        }
                    }
                }
            }
            return true;
        }else{
            return false;
        }
    }
    static function st_export_icon(){
    $taxonomies = get_taxonomies();
    $list_term = get_terms($taxonomies);
    $data = array();
    foreach($list_term as $k=>$v){
        $icon =  get_tax_meta($v->term_id, 'st_icon');
        $data[$v->slug] = $icon;
    }
    $data = serialize($data);
    $filename = 'data_icon.shinetheme';
    header( 'Content-Description: File Transfer' );
    header( 'Content-Disposition: attachment; filename=' . $filename );
    header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );
    echo $data;
    die();
}

}



if(!function_exists('remove_all_widgets_from_sidebar'))
{
    function remove_all_widgets_from_sidebar($sidebar_id)
    {
        $old_sidebar=get_option('sidebars_widgets',array());
        if(isset($old_sidebar[$sidebar_id]))
        {
            $old_sidebar[$sidebar_id]=array();
        }
        
        update_option('sidebars_widgets',$old_sidebar);
    }
}
// Parsing Widgets Function
// Thanks to http://wordpress.org/plugins/widget-importer-exporter/
/**
 * Import widget JSON data
 * 
 * @since 0.4
 * @global array $wp_registered_sidebars
 * @param object $data JSON widget data from .wie file
 * @return array Results array
 */
if(!function_exists('wie_import_data'))
{
function wie_import_data( $data ) {
    global $wp_registered_sidebars;
    // Have valid data?
    // If no data or could not decode
    if ( empty( $data ) || ! is_object( $data ) ) {
        wp_die(
            __( 'Import data could not be read. Please try a different file.', 'widget-importer-exporter' ),
            '',
            array( 'back_link' => true )
        );
    }
    // Hook before import
    do_action( 'wie_before_import' );
    $data = apply_filters( 'wie_import_data', $data );
    // Get all available widgets site supports
    $available_widgets = wie_available_widgets();
    // Get all existing widget instances
    $widget_instances = array();
    foreach ( $available_widgets as $widget_data ) {
        $widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
    }
    // Begin results
    $results = array();
    // Loop import data's sidebars
    foreach ( $data as $sidebar_id => $widgets ) {
        // Skip inactive widgets
        // (should not be in export file)
        if ( 'wp_inactive_widgets' == $sidebar_id ) {
            continue;
        }
        // Check if sidebar is available on this site
        // Otherwise add widgets to inactive, and say so
        if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
            remove_all_widgets_from_sidebar($sidebar_id);
            $sidebar_available = true;
            $use_sidebar_id = $sidebar_id;
            $sidebar_message_type = 'success';
            $sidebar_message = '';
        } else {
            $sidebar_available = false;
            $use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
            $sidebar_message_type = 'error';
            $sidebar_message = __( 'Sidebar does not exist in theme (using Inactive)', 'widget-importer-exporter' );
        }
        // Result for sidebar
        $results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
        $results[$sidebar_id]['message_type'] = $sidebar_message_type;
        $results[$sidebar_id]['message'] = $sidebar_message;
        $results[$sidebar_id]['widgets'] = array();
        // Loop widgets
        foreach ( $widgets as $widget_instance_id => $widget ) {
            $fail = false;
            // Get id_base (remove -# from end) and instance ID number
            $id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
            $instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );
            // Does site support this widget?
            if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
                $fail = true;
                $widget_message_type = 'error';
                $widget_message = __( 'Site does not support widget', 'widget-importer-exporter' ); // explain why widget not imported
            }
            // Filter to modify settings before import
            // Do before identical check because changes may make it identical to end result (such as URL replacements)
            $widget = apply_filters( 'wie_widget_settings', $widget );
            // Does widget with identical settings already exist in same sidebar?
            if ( ! $fail && isset( $widget_instances[$id_base] ) ) {
                // Get existing widgets in this sidebar
                $sidebars_widgets = get_option( 'sidebars_widgets' );
                $sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go
                // Loop widgets with ID base
                $single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
                foreach ( $single_widget_instances as $check_id => $check_widget ) {
                    // Is widget in same sidebar and has identical settings?
                    if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {
                        $fail = true;
                        $widget_message_type = 'warning';
                        $widget_message = __( 'Widget already exists', 'widget-importer-exporter' ); // explain why widget not imported
                        break;
                    }
    
                }
            }
            // No failure
            if ( ! $fail ) {
                // Add widget instance
                $single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
                $single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
                $single_widget_instances[] = (array) $widget; // add it
                    // Get the key it was given
                    end( $single_widget_instances );
                    $new_instance_id_number = key( $single_widget_instances );
                    // If key is 0, make it 1
                    // When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
                    if ( '0' === strval( $new_instance_id_number ) ) {
                        $new_instance_id_number = 1;
                        $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
                        unset( $single_widget_instances[0] );
                    }
                    // Move _multiwidget to end of array for uniformity
                    if ( isset( $single_widget_instances['_multiwidget'] ) ) {
                        $multiwidget = $single_widget_instances['_multiwidget'];
                        unset( $single_widget_instances['_multiwidget'] );
                        $single_widget_instances['_multiwidget'] = $multiwidget;
                    }
                    // Update option with new widget
                    update_option( 'widget_' . $id_base, $single_widget_instances );
                // Assign widget instance to sidebar
                $sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
                $new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
                $sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
                update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data
                // Success message
                if ( $sidebar_available ) {
                    $widget_message_type = 'success';
                    $widget_message = __( 'Imported', 'widget-importer-exporter' );
                } else {
                    $widget_message_type = 'warning';
                    $widget_message = __( 'Imported to Inactive', 'widget-importer-exporter' );
                }
            }
            // Result for widget instance
            $results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
            $results[$sidebar_id]['widgets'][$widget_instance_id]['title'] =(isset($widget->title) and  $widget->title) ? $widget->title : __( 'No Title', 'widget-importer-exporter' ); // show "No Title" if widget instance is untitled
            $results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
            $results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;
        }
    }
    // Hook after import
    do_action( 'wie_after_import' );
    // Return results
    return apply_filters( 'wie_import_results', $results );
}
}
if(!function_exists('wie_available_widgets')){
	function wie_available_widgets() {
	global $wp_registered_widget_controls;
	$widget_controls = $wp_registered_widget_controls;
	$available_widgets = array();
	foreach ( $widget_controls as $widget ) {
		if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes
			$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
			$available_widgets[$widget['id_base']]['name'] = $widget['name'];
		}
		
	}
	return apply_filters( 'wie_available_widgets', $available_widgets );
}
}