<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STAdmin
 *
 * Created by ShineTheme
 *
 */

if(!class_exists( 'STAdmin' )) {

    class STAdmin
    {
		static $_instance=FALSE;
        private        $template_dir = 'inc/admin/views';
        static private $message      = "";
        static private $message_type = "";
        public         $metabox;

        function __construct()
        {
            if(is_super_admin()) {
                add_filter( 'show_admin_bar' , '__return_true' , 999 );
            }
            /**
             * @since 1.1.7
             **/
            add_action( 'wp_enqueue_scripts' , array( $this , 'reg_multi_location' ) );
            add_action( 'admin_enqueue_scripts' , array( $this , 'reg_multi_location' ) );

        }

        

        function reg_multi_location()
        {
            $gg_api_key  = st()->get_option('google_api_key', "");
			if (is_ssl()){
				$url=add_query_arg(array(
					'v'=>'3', //.exp
					'signed_in'=>'true',
					'libraries'=>'places',
                    'sensor'=>'false',
					'language'=>'en',
                    'key' => $gg_api_key
				),'https://maps.googleapis.com/maps/api/js');
				wp_register_script('gmap-apiv3', $url, array(), null, true);
			}else {
				$url=add_query_arg(array(
					'v'=>'3', //.exp
					'signed_in'=>'true',
					'libraries'=>'places',
                    'sensor'=>'false',
					'language'=>'en',
                    'key' => $gg_api_key
				),'http://maps.googleapis.com/maps/api/js');
				wp_register_script('gmap-apiv3', $url, array(), null, true);
			}
            wp_enqueue_script( 'list_item_post_type' , get_template_directory_uri() . '/js/multi_location.js' , array( 'selectize' ) , null , true );
        }

        static function getListDate( $start , $end )
        {

            $start = new DateTime( $start );
            $end   = new DateTime( $end . ' +1 day' );
            $list  = array();
            foreach( new DatePeriod( $start , new DateInterval( 'P1D' ) , $end ) as $day ) {
                $list[ ] = $day->format( TravelHelper::getDateFormat() );
            }
            return $list;
        }

        /**************** Price *****************/


        function st_create_custom_price()
        {
            $data = apply_filters( 'st_data_custom_price' , array() );
            if(!empty( $data ) and is_array( $data )) {
                add_meta_box( 'st_custom_price' , $data[ 'title' ] , array(
                    $this ,
                    'st_custom_price_func'
                ) , $data[ 'post_type' ] , 'normal' , 'high' );
            }
        }

        function st_custom_price_func( $object , $box )
        {
            echo self::load_view( 'admin/html' , 'price' , array(
                'post_id'               => $object->ID ,
                'st_custom_price_nonce' => wp_create_nonce( plugin_basename( __FILE__ ) )
            ) );
        }

        function st_save_custom_price( $post_id , $post )
        {
            if(!empty( $_POST[ 'st_custom_price_nonce' ] )) {
                if(!wp_verify_nonce( $_POST[ 'st_custom_price_nonce' ] , plugin_basename( __FILE__ ) ))
                    return $post_id;

                if(!current_user_can( 'edit_post' , $post_id ))
                    return $post_id;
                $price_new  = $_REQUEST[ 'st_price' ];
                $price_type = $_REQUEST[ 'st_price_type' ];
                $start_date = $_REQUEST[ 'st_start_date' ];
                $end_date   = $_REQUEST[ 'st_end_date' ];
                $status     = $_REQUEST[ 'st_status' ];
                $priority   = $_REQUEST[ 'st_priority' ];
                self::st_delete_price( $post_id );

                if($price_new and $start_date and $end_date) {
                    foreach( $price_new as $k => $v ) {
                        if(!empty( $v )) {
                            self::st_add_price( $post_id , $price_type[ $k ] , $v , $start_date[ $k ] , $end_date[ $k ] , $status[ $k ] , $priority[ $k ] );
                        }
                    }
                }
            }
        }

        static function st_get_all_price( $post_id )
        {
            global $wpdb;
            $rs = $wpdb->get_results( "SELECT * FROM " . $wpdb->base_prefix . "st_price WHERE post_id=" . $post_id );
            return $rs;
        }

        static function st_add_price( $post_id , $type_price = 'default' , $price , $start_date , $end_date , $status = 1 , $priority = 0 )
        {
            global $wpdb;
            if($the_post = wp_is_post_revision( $post_id ))
                $post_id = $the_post;
            $check = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->base_prefix . "st_price WHERE post_id=" . $post_id . " AND price_type='" . $type_price . "' AND price=" . $price . " AND start_date='" . $start_date . "' AND end_date='" . $end_date . "' AND status=" . $status . " AND priority=" . $priority );
            if(empty( $check )) {
                $wpdb->insert( $wpdb->base_prefix . 'st_price' , array(
                    'post_id'    => $post_id ,
                    'price_type' => $type_price ,
                    'price'      => $price ,
                    'start_date' => $start_date ,
                    'end_date'   => $end_date ,
                    'status'     => $status ,
                    'priority'   => $priority
                ) );
                $insert_id = (int)$wpdb->insert_id;
                if($insert_id) {
                    return $insert_id;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function st_update_price( $post_id , $type_price = 'default' , $price , $start_date , $end_date , $status = 1 , $priority = 0 )
        {

        }

        static function st_delete_price( $post_id )
        {
            global $wpdb;
            $wpdb->delete( $wpdb->base_prefix . 'st_price' , array( 'post_id' => $post_id ) );
        }


        /**************** End Price *****************/

        function admin_enqueue_scripts()
        {
            wp_enqueue_style( 'st-admin' , get_template_directory_uri() . '/css/admin/admin.css' );
            wp_enqueue_script( 'st-edit-booking' , get_template_directory_uri() . '/js/admin/edit-booking.js' , array( 'jquery' ) , null , true );
            wp_enqueue_script( 'st-custom-price' , get_template_directory_uri() . '/js/admin/custom-price.js' , array( 'jquery' ) , null , true );
            wp_enqueue_script( 'st-moment' , get_template_directory_uri() . '/js/moment.js' , array( 'jquery', ) , null , true );
            wp_enqueue_script( 'st-moment-timezone' , get_template_directory_uri() . '/js/moment-timezone-with-data-2010-2020.js' , array( 'jquery', ) , null , true );
            wp_enqueue_script( 'st-selectize' , get_template_directory_uri() . '/js/selectize/selectize.min.js' , array( 'jquery' ) , null , true );
            wp_enqueue_script( 'st-custom-admin' , get_template_directory_uri() . '/js/admin/custom-admin.js' , array( 'jquery', 'st-selectize' ) , null , true );

            wp_enqueue_style('st-selectize', get_template_directory_uri() . '/js/selectize/selectize.css');
            wp_enqueue_style('st-selectize-bt3', get_template_directory_uri() . '/js/selectize/selectize.bootstrap3.css');

            wp_enqueue_style('thickbox');
            wp_enqueue_script('thickbox');
        }

        function update_location_info( $post_id )
        {
            if(wp_is_post_revision( $post_id ))
                return;
            $post_type = get_post_type( $post_id );

            if($post_type == 'st_cars' or $post_type == 'st_activity' or $post_type == 'st_tours' or $post_type == 'st_rental' or $post_type == 'st_hotel') {
                if($post_type == 'st_rental' /*or $post_type=='hotel'*/) {
                    $location    = 'location_id';
                    $location_id = get_post_meta( $post_id , $location , true );
                } else {
                    $location    = 'id_location';
                    $location_id = get_post_meta( $post_id , $location , true );
                }
                $ids_in  = array();
                $parents = get_posts( array(
                    'numberposts' => -1 ,
                    'post_status' => 'publish' ,
                    'post_type'   => 'location' ,
                    'post_parent' => $location_id
                ) );

                $ids_in[ ] = $location_id;

                foreach( $parents as $child ) {
                    $ids_in[ ] = $child->ID;
                }
                $arg   = array(
                    'post_type'      => $post_type ,
                    'posts_per_page' => '-1' ,
                    'meta_query'     => array(
                        array(
                            'key'     => $location ,
                            'value'   => $ids_in ,
                            'compare' => 'IN' ,
                        ) ,
                    ) ,
                );
                $query = new WP_Query( $arg );
                $offer = $query->post_count;

                // get total review
                $arg   = array(
                    'post_type'      => $post_type ,
                    'posts_per_page' => '-1' ,
                    'meta_query'     => array(
                        array(
                            'key'     => $location ,
                            'value'   => $ids_in ,
                            'compare' => 'IN' ,
                        ) ,
                    ) ,
                );
                $query = new WP_Query( $arg );
                $total = 0;
                if($query->have_posts()) {
                    while( $query->have_posts() ) {
                        $query->the_post();
                        $total += get_comments_number();
                    }
                }
                // get car min price
                $meta_key = 'sale_price';
                if($post_type == 'st_hotel') {
                    $meta_key = 'price_avg';
                }
                $arg   = array(
                    'post_type'      => $post_type ,
                    'posts_per_page' => '1' ,
                    'order'          => 'ASC' ,
                    'meta_key'       => $meta_key ,
                    'orderby'        => 'meta_value_num' ,
                    'meta_query'     => array(
                        array(
                            'key'     => $location ,
                            'value'   => $ids_in ,
                            'compare' => 'IN' ,
                        ) ,
                    ) ,
                );
                $query = new WP_Query( $arg );
                if($query->have_posts()) {
                    $query->the_post();
                    $price_min = get_post_meta( get_the_ID() , 'sale_price' , true );
                    if($post_type == 'st_hotel') {
                        $price_min = get_post_meta( get_the_ID() , 'price_avg' , true );
                    }
                }
                wp_reset_postdata();
                update_post_meta( $location_id , 'review_' . $post_type , $total );
                if(isset( $price_min ))update_post_meta( $location_id , 'min_price_' . $post_type , $price_min );
                update_post_meta( $location_id , 'offer_' . $post_type , $offer );
            }
        }

        function array_splice_assoc( &$input , $offset , $length = 0 , $replacement = array() )
        {
            $tail      = array_splice( $input , $offset );
            $extracted = array_splice( $tail , 0 , $length );
            $input += $replacement + $tail;
            return $extracted;
        }

        static function get_history_bookings( $type = "st_hotel" , $offset , $limit , $author = false )
        {
            global $wpdb;
            
            $where  = '';
            $join   = '';
            $select = '';

            if(isset( $_GET[ 'st_date_start' ] ) and $_GET[ 'st_date_start' ]) {

                if($type == 'st_cars'){
                    $date = (date( 'm/d/Y' , strtotime( $_GET[ 'st_date_start' ] ) ));
                    $where .= " AND {$wpdb->prefix}st_order_item_meta.check_in >= '{$date}'";
                }else{
                    $date = strtotime(date( 'Y-m-d' , strtotime( $_GET[ 'st_date_start' ] ) ));
                    $where .= " AND CAST({$wpdb->prefix}st_order_item_meta.check_in_timestamp as UNSIGNED) >= {$date}";
                }

            }

            if(isset( $_GET[ 'st_date_end' ] ) and $_GET[ 'st_date_end' ]) {

                if($type == 'st_cars'){
                    $date = (date( 'm/d/Y' , strtotime( $_GET[ 'st_date_end' ] ) ));
                    $where .= " AND {$wpdb->prefix}st_order_item_meta.check_in <= '{$date}'";
                }else{
                    $date = strtotime(date( 'Y-m-d' , strtotime( $_GET[ 'st_date_start' ] ) ));
                    $where .= " AND CAST({$wpdb->prefix}st_order_item_meta.check_in_timestamp as UNSIGNED) <= {$date}";
                }


            }

            if($c_name = STInput::get( 'st_custommer_name' )) {
                $join .= " INNER JOIN {$wpdb->prefix}postmeta as mt3 on mt3.post_id= {$wpdb->prefix}st_order_item_meta.order_item_id";
                $where .= ' AND  mt3.meta_key=\'st_first_name\'
             ';
                $where .= ' AND mt3.meta_value like \'%' . esc_sql( $c_name ) . '%\'';
            }

            if($author) {
                $author = " AND {$wpdb->prefix}st_order_item_meta.user_id = ". $author;
            }

            $querystr = " 
            SELECT SQL_CALC_FOUND_ROWS  {$wpdb->prefix}posts.* from {$wpdb->prefix}st_order_item_meta
            {$join}
            INNER JOIN {$wpdb->prefix}posts ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}st_order_item_meta.order_item_id
            WHERE 1=1 AND st_booking_post_type = '{$type}' AND type='normal_booking' {$where}
            ORDER BY {$wpdb->prefix}st_order_item_meta.id DESC
            LIMIT {$offset},{$limit}
            ";
            
            $pageposts = $wpdb->get_results( $querystr , OBJECT );

            return array( 'total' => $wpdb->get_var( "SELECT FOUND_ROWS();" ) , 'rows' => $pageposts );
        }

        static function set_message( $message , $type = '' )
        {
            self::$message      = $message;
            self::$message_type = $type;
        }

        static function message()
        {
            if(self::$message):
                ?>
                <div id="message" class="<?php echo self::$message_type?> below-h2">
                    <p><?php echo self::$message ?>
                    </p>
                </div>
            <?php endif;
        }


        function load_view( $slug , $name = false , $data = array() )
        {

            extract( $data );

            if($name) {
                $slug = $slug . '-' . $name;
            }

            //Find template in folder inc/admin/views/
            $template = locate_template( $this->template_dir . '/' . $slug . '.php' );


            //If file not found
            if(is_file( $template )) {
                ob_start();

                include $template;

                $data = @ob_get_clean();

                return $data;
            }
        }

        function register_metabox( $custom_metabox )
        {
            /**
             * Register our meta boxes using the
             * ot_register_meta_box() function.
             */
            if(function_exists( 'ot_register_meta_box' )) {
                if(!empty( $custom_metabox )) {
                    foreach( $custom_metabox as $value ) {
                        ot_register_meta_box( $value );
                    }
                }
            }
        }

        function init()
        {

            $files = array(
                'admin/class.user' ,
                'admin/class.admin.menus' ,
                'admin/class.attributes' ,
                'admin/class.admin.hotel' ,
                'admin/class.admin.room' ,
                'admin/class.admin.rental' ,
                'admin/class.admin.cars' ,
                'admin/class.admin.tours' ,
                'admin/class.admin.activity' ,
                'admin/class.admin.location' ,
                'admin/class.admin.order' ,
                'admin/class.admin.permalink' ,
                'admin/class.admin.uploadfonticon' ,
                'admin/class.admin.update.content' ,
                'admin/class.admin.woo.checkout',
                'admin/class.admin.normal.checkout',
                'admin/class.admin.rental.room' ,
                'admin/class.admin.duplicate.data' ,
                'admin/class.admin.neworder.data' ,
                'admin/class.admin.availability' ,
                'admin/class.admin.upgrade.data' ,
                'admin/class.admin.location.relationships' ,
                //'admin/class.admin.glocation' ,
                'admin/class.admin.landing.page' ,
                'admin/class.admin.testimonial' ,
                'admin/class.admin.withdrawal' ,
                'admin/class.admin.api' ,
            );


            STTraveler::load_libs( $files );

            add_action( 'admin_enqueue_scripts' , array( $this , 'admin_enqueue_scripts' ) );
            add_action( 'save_post' , array( $this , 'update_location_info' ) );
            add_action( 'deleted_post' , array( $this , 'update_location_info' ) );

            add_action( 'admin_menu' , array( $this , 'st_create_custom_price' ) );
            add_action( 'save_post' , array( $this , 'st_save_custom_price' ) , 10 , 2 );

            add_action( 'init' , array( $this , 'st_register_location_tax' ) );
        }

        function st_register_location_tax()
        {
            $booking_type = apply_filters( 'st_booking_post_type' , array(
                'st_hotel' ,
                'st_rental' ,
                'st_tours' ,
                'st_cars' ,
                'st_activity'
            ) );


        }

		static function inst(){
			if(!self::$_instance){
				self::$_instance=new self();
			}

			return self::$_instance;
		}
	}
	STAdmin::inst()->init();
}