<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STAnalytics
 *
 * Created by ShineTheme
 *
 */
if(!class_exists('STAnalytics'))
{
    class STAnalytics
    {
        static $is_working=false;
        static $time_out=300;

        static private $useronline='st_user_online';

        static function  init()
        {
            add_action('after_setup_theme',array(__CLASS__,'_check_is_working'));

            add_action('template_redirect',array(__CLASS__,'_update_user_online'));

            add_action('wp_enqueue_scripts',array(__CLASS__,'_enqueue_data'));
        }

        static function _enqueue_data()
        {
            //'Most recent booking for this property was 15 minute ago .Most recent booking for this property was 15 minute ago'
            if(!self::$is_working) return;
            $data=array();
            if(is_singular()){

                $post_type=get_post_type();
                $post_id=get_the_ID();
                $noti_session=isset($_SESSION['_st_notification_post_'.$post_id])?$_SESSION['_st_notification_post_'.$post_id]:false;
                $book_able=array(
                    'st_hotel',
                    'st_cars',
                    'st_tours',
                    'st_activity',
                    'st_rental',
                    'cruise',
                );
                if(!in_array($post_type,$book_able)) return;

                $label=get_post_type_object($post_type);

                $demo_mode=st()->get_option('edv_enable_demo_mode','off');
                $once_notification_per_each_session=st()->get_option('once_notification_per_each_session','off');


                if(st()->get_option('enable_user_online_noti','on')=='on')
                {
                    if($once_notification_per_each_session=='off' or !$noti_session)
                    {
                        if($demo_mode=='on')
                        {
                            $data['noty'][]=array(
                                'icon'=>'home',
                                'message'=>sprintf(st_get_language('now_s_users_seeing_this_s'),rand(300,5000),$label->labels->singular_name),
                                'type'   =>'success'
                            );
                        }else
                        {
                            $data['noty'][]=array(
                                'icon'=>'home',
                                'message'=>sprintf(st_get_language('now_s_users_seeing_this_s'),self::get_user_online(get_the_ID()),$label->labels->singular_name),
                                'type'   =>'success'
                            );
                        }
                    }

                }

                if(st()->get_option('enable_last_booking_noti','on')=='on')
                {
                    if($once_notification_per_each_session=='off' or !$noti_session)
                    {
                        if($demo_mode=='on'){
                            $data['noty'][]=array(
                                'icon'=>'clock-o',
                                'message'=>sprintf(st_get_language('most_revent_booking_for_this_s_was_s')

                                    ,$label->labels->singular_name,
                                    sprintf(__('%s minutes ago',ST_TEXTDOMAIN),rand(2,50))
                                ),
                                'type'=>'warning'
                            );
                        }else{
                            if (TravelerObject::get_last_booking_string(get_the_ID())){
                                $data['noty'][]=array(
                                    'icon'=>'clock-o',
                                    'message'=>sprintf(st_get_language('most_revent_booking_for_this_s_was_s')

                                        ,$label->labels->singular_name,
                                        TravelerObject::get_last_booking_string(get_the_ID())
                                    ),
                                    'type'=>'warning'
                                );
                            }
                        }
                    }

                }

                $data['noti_position']=st()->get_option('noti_position','topRight');
                $_SESSION['_st_notification_post_'.$post_id]=1;

            }
            wp_localize_script('jquery','stanalytics',$data);
        }



        static function _check_is_working()
        {
            global $wpdb;

            $table_name = $wpdb->prefix .self::$useronline;
            if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

                //table is not created. you may create the table here.
                global $wpdb;
                $charset_collate = $wpdb->get_charset_collate();

                $sql = "CREATE TABLE $table_name (
                        id mediumint(9) NOT NULL AUTO_INCREMENT,
                        ip int  NOT NULL,
                        dt int NOT NULL,
                        item_id int,
                        UNIQUE KEY id (id)
                    ) $charset_collate;";

                $wpdb->query( $sql );

                if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

                    self::$is_working=false;

                }else
                {
                    self::$is_working=true;
                }

            }else{
                self::$is_working=true;
            }
        }

        static function get_user_online($item_id=false)
        {
            if(!self::$is_working) return;
            global $wpdb;
            $table_name=$wpdb->prefix .self::$useronline;

			// delete old data
			//$delete_query="DELETE FROM {$table_name} WHERE 1=1 AND   ";
			//$wpdb->get_var($delete_query);

            $where='';

            $time=time()- self::$time_out;

            if($item_id){
                $where.=" AND item_id=".sanitize_title_for_query($item_id);
            }

            $where.=' AND dt>='.sanitize_title_for_query($time);

            $query="SELECT COUNT(id) as total FROM {$table_name} WHERE 1=1 {$where} ";


            $count= $wpdb->get_var($query);

            return $count;

        }

        static function _update_user_online()
        {
            if(!self::$is_working) return;

            if(is_admin()) return;

			$delete_old=get_option('st_useronline_last_clear');

            global $wpdb;
            $table_name=$wpdb->prefix .self::$useronline;
            $intIp=STInput::ip_address();

            $item_id=0;

            if(is_singular()){
                $item_id=get_the_ID();
            }

            $where='';

            if($item_id){
                $where.=' AND item_id='.sanitize_title_for_query($item_id);
            }

            $ip_exists= $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$table_name}
            WHERE ip=%d ".$where,
                $intIp
            ));

            if(!empty($ip_exists) and is_array($ip_exists)){
                $wpdb->query($wpdb->prepare(
                    "UPDATE {$table_name}
                SET dt=%d
              WHERE ip=%s ".$where,
                    time(),
                    $intIp
                ));
            }else{
                $wpdb->query($wpdb->prepare(
                    "INSERT INTO {$table_name}
                (ip,dt,item_id)
                values(%s,%d,%d)",
                    $intIp,
                    time(),
                    $item_id
                ));
            }
        }
    }

    STAnalytics::init();
}
