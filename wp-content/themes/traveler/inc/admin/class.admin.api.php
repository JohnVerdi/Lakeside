<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STAdminAPI
 *
 * Created by ShineTheme
 *
 */
if(!class_exists('STAdminAPI'))
{
    class STAdminAPI extends STAdmin
    {
        static $_inst;
        function __construct()
        {
        }
        function init(){
            if( self::is_optiontree_page() ){
                add_action('admin_enqueue_scripts', array(__CLASS__, 'add_scripts'));
            }
            add_action( 'wp_ajax_st_update_content_api_roomorama' , array( $this , 'st_update_content_api_roomorama_func' ) );
            add_action( 'wp_ajax_nopriv_st_update_content_api_roomorama' , array( $this , 'st_update_content_api_roomorama_func' ) );
        }
        function st_update_content_api_roomorama_func(){

            $access_token = st()->get_option('st_roomorama_token');
            $st_step = STInput::request("st_step");
            switch($st_step){
                case "1":
                    $url_link = 'https://api.roomorama.com/v1.0/me.json';
                    $data = self::st_get_data($url_link,array('access_token'=>$access_token));
                    if(!empty($data->result)){
                        echo json_encode(array(
                            'data'=>array(
                                'st_step'=>'2',
                                'action'=>"st_update_content_api_roomorama"
                            ),
                            'msg'=>__("Check Token Access : <span>DONE!</span><br>")
                        ));
                    }else{
                        echo json_encode(array(
                            'data'=>array(
                                'st_step'=>false,
                                'action'=>"st_update_content_api_roomorama"
                            ),
                            'msg'=>__("Check Token Access : <span>Error!</span><br>")
                        ));
                    }
                    break;
                case "2":
                    $url_link = 'https://api.roomorama.com/v1.0/host/rooms.json';
                    $page = STInput::request('page','1');
                    $limit = 1;
                    $data = self::st_get_data($url_link,array('access_token'=>$access_token,'page'=>$page,'limit'=>$limit));
                    if(!empty($data->result)){
                        $msg = __("Update Room  : <span>DONE!</span><br>");
                        $next_page = ($page+1);
                        if(  !empty($data->pagination)   ){
                            $pagination =$data->pagination;
                            $total = $pagination->count;
                            $item = $pagination->current * $limit;
                            $msg = __("Update Room  {$item}/{$total}: <span>DONE!</span><br>");
                        }
                        self::update_data_roomorama($data->result);
                        echo json_encode(array(
                            'data'=>array(
                                'page'=>$next_page,
                                'st_step'=>'2',
                                'action'=>"st_update_content_api_roomorama"
                            ),
                            'msg'=>$msg
                        ));
                    }else{
                        echo json_encode(array(
                            'data'=>array(
                                'st_step'=>3,
                                'action'=>"st_update_content_api_roomorama"
                            ),
                            'msg'=>''
                        ));
                    }
                    break;
                default:
                    echo json_encode(array(
                        'data'=>array(
                            'st_step'=>0,
                            'action'=>"st_update_content_api_roomorama"
                        ),
                       'msg'=>'All Done <span>DONE!</span><br>'
                    ));
            }
            die();
        }
        /**
         *@since 1.2.6
         **/
        function update_data_roomorama($data){

            if(!empty($data)){
                foreach($data as $k=>$v){
                    if($v->type == "room"){
                        $title = $v->title;
                        global $wpdb;
                        $post_id = $wpdb->get_var( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'st_custom_item_api_id' AND meta_value = {$v->id}" );
                        $current_user = wp_get_current_user();
                        if(empty($post_id)){
                            $my_post = array(
                                'post_title'   => $title ,
                                'post_content' => stripslashes($v->description) ,
                                'post_status'  => 'publish' ,
                                'post_author'  => $current_user->ID ,
                                'post_type'    => 'hotel_room' ,
                                'post_excerpt' => stripslashes($v->description),
                            );
                            $post_id = wp_insert_post( $my_post );
                        }else{
                            $my_post = array(
                                'ID'           => $post_id,
                                'post_title'   => $title ,
                                'post_content' => stripslashes($v->description) ,
                                'post_status'  => 'publish' ,
                                'post_author'  => $current_user->ID ,
                                'post_type'    => 'hotel_room' ,
                                'post_excerpt' => stripslashes($v->description),
                            );
                            wp_update_post( $my_post );
                        }
                        // UPDATE META BOX
                        if(!empty($post_id)){
                            update_post_meta( $post_id , 'st_custom_item_api_type' , 'st_roomorama' );
                            update_post_meta( $post_id , 'st_custom_item_api_id' , $v->id );
                            /////////////////////////////////////
                            /// Update featured
                            /////////////////////////////////////
                            $url_image = $v->thumbnail;
                            //$url_image = str_ireplace('_search_result.','_gallery.',$url_image);
                            $id_image = self::_insert_file_image_by_url($url_image);
                            if(!empty($id_image)){
                                update_post_meta($post_id, '_thumbnail_id', $id_image);
                            }
                            /////////////////////////////////////
                            /// Update Metabox
                            /////////////////////////////////////
                            //tab location

                            if(!empty($v->city)){
                                $location_name = $v->city;
                                $location_id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title like '".$location_name."' AND post_type='location'");
                                if(!empty($location_id)){
                                    $location_str = "_".$location_id."_";
                                    update_post_meta( $post_id , 'multi_location' , $location_str );
                                }else{

                                }
                            }
                            update_post_meta( $post_id , 'address' , $v->address );
                            //tab general
                            update_post_meta( $post_id , 'room_parent' , 0 );
                            update_post_meta( $post_id , 'number_room' , 1 );
                            //tab general
                            update_post_meta( $post_id , 'price' , $v->nightly_rate );
                            update_post_meta( $post_id , 'discount_rate' , 0);

                            //tab room facility
                            update_post_meta( $post_id , 'adult_number' , $v->max_guests );
                            update_post_meta( $post_id , 'children_number' , 0 );
                            $bed_number = 0 ;
                            if(!empty($v->number_of_double_beds))$bed_number+=$v->number_of_double_beds;
                            if(!empty($v->number_of_single_beds))$bed_number+=$v->number_of_single_beds;
                            if(!empty($v->number_of_sofa_beds))$bed_number+=$v->number_of_sofa_beds;
                            update_post_meta( $post_id , 'bed_number' , $bed_number );
                            update_post_meta( $post_id , 'room_footage' , 0 );
                            update_post_meta( $post_id , 'room_description' ,$v->description);
                            $st_api_external_booking = st()->get_option('st_api_external_booking','off');
                            if($st_api_external_booking == "on"){
                                $external_link = "https://www.roomorama.com/room/".$v->id;
                                update_post_meta( $post_id , 'st_room_external_booking' , "on" );
                                update_post_meta( $post_id , 'st_room_external_booking_link' , $external_link );
                            }else{
                                $external_link = "https://www.roomorama.com/room/".$v->id;
                                update_post_meta( $post_id , 'st_room_external_booking' , "off" );
                                update_post_meta( $post_id , 'st_room_external_booking_link' , $external_link );
                            }


                            /////////////////////////////////////
                            /// Update taxonomy
                            /////////////////////////////////////
                            if(!empty($v->amenities)){
                                $taxonomy = $v->amenities;
                                $tax = array();
                                $my_terms = wp_get_post_terms( $post_id, 'room_facilities' );
                                if(!empty($my_terms)){
                                    foreach($my_terms as $key_tmp=>$value_tmp){
                                        $tax[ $key_tmp ] = $value_tmp->term_id;
                                    }
                                }
                                foreach($taxonomy as $key=>$value){
                                    $check_term  = get_term_by('slug', str_ireplace('_','-',$value), 'room_facilities');
                                    if(empty($check_term->term_id)){
                                        $term_id = wp_insert_term(
                                            ucwords(str_ireplace('_',' ',$value)),   // the term
                                            'room_facilities'
                                        );
                                        $term_id = $term_id['term_id'];
                                    }else{
                                        $term_id = $check_term->term_id;
                                    }
                                    $tax[ $term_id ] = $term_id;
                                }
                                wp_set_post_terms( $post_id , $tax , 'room_facilities' );
                            }
                            /////////////////////////////////////
                            ///  Update extra
                            /////////////////////////////////////
                            $extra = $v->services;
                            if(isset($extra)){
                                $list_extras = array();
                                foreach($extra as $key => $val){
                                    if($val->available){
                                        $list_extras[$key] = array(
                                            'title' => ucwords(str_ireplace('_',' ',$key)),
                                            'extra_name' => ucwords(str_ireplace('_',' ',$key)),
                                            'extra_max_number' => 1,
                                            'extra_price' => $val->rate
                                        );
                                    }
                                }
                                update_post_meta($post_id, 'extra_price', $list_extras);
                            }
                            $my_post = array( 'ID'  => $post_id );
                            wp_update_post( $my_post );
                        }
                    }//end if
                }
            }
        }
        /**
         *@since 1.2.6
         **/
        function _insert_file_image_by_url($file){
            if ( ! empty( $file ) ) {

                // Set variables for storage, fix file filename for query strings.
                preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
                if ( ! $matches ) {
                    return new WP_Error( 'image_sideload_failed', __( 'Invalid image URL' ) );
                }

                $file_array = array();
                $file_array['name'] = basename( $matches[0] );

                // Download file to temp location.
                $file_array['tmp_name'] = download_url( $file );

                // If error storing temporarily, return the error.
                if ( is_wp_error( $file_array['tmp_name'] ) ) {
                    return $file_array['tmp_name'];
                }

                // Do the validation and storage stuff.
                $id = media_handle_sideload( $file_array, '' );

                // If error storing permanently, unlink.
                if ( is_wp_error( $id ) ) {
                    @unlink( $file_array['tmp_name'] );
                    return $id;
                }

               return $id;
            }

        }

        /**
         *@since 1.2.6
         **/
        function st_get_data($url,$arg=array()){
            if(!empty($arg)){
                $url_new = add_query_arg( $arg , $url );
            }else{
                $url_new = $url;
            }
            $data = wp_remote_fopen($url_new);
            $data = json_decode($data);
            return $data;
        }
        /**
         *@since 1.2.6
         **/
        static function is_optiontree_page()
        {
            if (is_admin()
                and isset($_GET['page'])
                and $_GET['page'] == 'st_traveler_options'
            ) return TRUE;
            return FALSE;
        }
        /**
         *@since 1.2.6
         **/
        static function  add_scripts()
        {
            wp_enqueue_script('admin_api.js', get_template_directory_uri() . '/inc/js/admin_api.js', array('jquery'), NULL, TRUE);
            wp_enqueue_style('admin_api.css', get_template_directory_uri() . '/inc/css/admin_api.css');
        }
        static function inst()
        {
            if(!self::$_inst){
                self::$_inst=new self();
            }
            return self::$_inst;
        }
    }
    STAdminAPI::inst()->init();
}

if(!function_exists('ot_type_show_content_api_roomorama')){
    function ot_type_show_content_api_roomorama(){
        echo '<a id="btn_run_sync_roomorama" class="button button-primary btn_run_sync_roomorama">'.__("Run sync",ST_TEXTDOMAIN).'</a><div id="content_run_sync_roomorama" class="content_run_sync_roomorama"></div>';
    }
}
