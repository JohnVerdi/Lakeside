<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STAdminHotel
 *
 * Created by ShineTheme
 *
 */
$order_id = 0;
if (!class_exists('STAdminHotel')) {

    class STAdminHotel extends STAdmin
    {   
        static $parent_key = 'room_parent';
        static $booking_page;
        static $_table_version= "1.2.4";
        protected $post_type='st_hotel';

        /**
         *
         *
         * @update 1.1.3
         * */
        function __construct()
        {

            add_action('init', array($this, 'init_post_type'),8);

            if (!st_check_service_available($this->post_type)) return;

            add_action('init', array($this, 'init_metabox'));

            self::$booking_page = admin_url('edit.php?post_type=st_hotel&page=st_hotel_booking');

            //add colum for rooms
            add_filter('manage_hotel_room_posts_columns', array($this, 'add_col_header'), 10);
            add_action('manage_hotel_room_posts_custom_column', array($this, 'add_col_content'), 10, 2);

            //add colum for rooms
            add_filter('manage_st_hotel_posts_columns', array($this, 'add_hotel_col_header'), 10);
            add_action('manage_st_hotel_posts_custom_column', array($this, 'add_hotel_col_content'), 10, 2);

            add_action('admin_menu', array($this, 'add_menu_page'));

            //Check booking edit and redirect
            if (self::is_booking_page()) {

                add_action('admin_enqueue_scripts', array(__CLASS__, 'add_edit_scripts'));
                
                add_action('admin_init',array($this,'_do_save_booking'));
            }


            if (isset($_GET['send_mail']) and $_GET['send_mail'] == 'success') {
                self::set_message(__('Email sent', ST_TEXTDOMAIN), 'updated');
            }

            add_action('wp_ajax_st_room_select_ajax_admin', array(__CLASS__, 'st_room_select_ajax'));

            parent::__construct();

            add_action('save_post', array($this, '_update_avg_price'), 50);
            add_action('save_post', array($this, '_update_min_price'), 50);
            add_action('save_post', array($this, '_update_list_location'), 51, 2);
            add_action('save_post', array($this, '_update_duplicate_data'), 51, 2);
            add_action( 'before_delete_post', array($this, '_delete_data'), 50 );

            add_action('wp_ajax_st_getRoomHotelInfo', array(__CLASS__,'getRoomHotelInfo'), 9999);
            add_action('wp_ajax_st_getRoomHotel', array(__CLASS__,'getRoomHotel'), 9999);

            /**
            *   since 1.2.4 
            *   auto create & update table st_hotel
            **/
            add_action( 'after_setup_theme', array (__CLASS__, '_check_table_hotel') );

        }

        static function check_ver_working(){
            $dbhelper = new DatabaseHelper(self::$_table_version);
            return $dbhelper->check_ver_working( 'st_hotel_table_version' );
        }
        static function _check_table_hotel(){
            $dbhelper = new DatabaseHelper(self::$_table_version);
            $dbhelper->setTableName('st_hotel');
            $column = array(
                'post_id' => array(
                    'type' => 'INT',
                    'length' => 11,
                ),
                'multi_location' => array(
                    'type' => 'text',
                ),
                'id_location'=> array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'address' => array(
                    'type' => 'text',
                ),
                'allow_full_day' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'rate_review' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'hotel_star' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'price_avg' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'min_price' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'hotel_booking_period' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'map_lat' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'map_lng' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'is_sale_schedule' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
            );

            $column = apply_filters( 'st_change_column_st_hotel', $column );

            $dbhelper->setDefaultColums( $column );
            $dbhelper->check_meta_table_is_working( 'st_hotel_table_version' );

            return array_keys( $column );
        }

        function _do_save_booking()
        {
            $section = isset($_GET['section']) ? $_GET['section'] : FALSE;
                switch ($section) {
                    case "edit_order_item":
                        $item_id = isset($_GET['order_item_id']) ? $_GET['order_item_id'] : FALSE;
                        if (!$item_id or get_post_type($item_id) != 'st_order') {
                            return FALSE;
                        }
                        if (isset($_POST['submit']) and $_POST['submit']) $this->_save_booking($item_id);
                        break;
                    case "add_booking":
                        if (isset($_POST['submit']) and $_POST['submit']) $this->_add_booking();
                        break;
                    case 'resend_email':
                        $this->_resend_mail();
                    break;
                }
        }
        /**
        *@since 1.1.8
        **/

        function _update_duplicate_data($id, $data){
            if(!TravelHelper::checkTableDuplicate('st_hotel')) return;
            if(get_post_type($id) == 'st_hotel'){
                $num_rows = TravelHelper::checkIssetPost($id, 'st_hotel');
                $location_str = get_post_meta($id, 'multi_location', true);
                $location_id = ''; // location_id
                $address = get_post_meta($id, 'address', true); // address
                $allow_full_day = get_post_meta($id, 'allow_full_day', true); // address

                $rate_review = STReview::get_avg_rate($id); // rate review
                $hotel_star = get_post_meta($id, 'hotel_star', true); // hotel star
                $price_avg = get_post_meta($id, 'price_avg', true); // price avg
                $min_price = get_post_meta($id, 'min_price', true); // price avg
                $hotel_booking_period = get_post_meta($id, 'hotel_booking_period', true); // price avg
                $map_lat = get_post_meta($id, 'map_lat', true); // map_lat
                $map_lng = get_post_meta($id, 'map_lng', true); // map_lng

                if($num_rows == 1){
                    $data = array(
                        'multi_location' => $location_str,
                        'id_location' => $location_id,
                        'address' => $address,
                        'allow_full_day' => $allow_full_day,
                        'rate_review' => $rate_review,
                        'hotel_star' => $hotel_star,
                        'price_avg' => $price_avg,
                        'min_price' => $min_price,
                        'hotel_booking_period' => $hotel_booking_period,
                        'map_lat' => $map_lat,
                        'map_lng' => $map_lng,
                    );
                    $where = array(
                        'post_id' => $id
                    );
                    TravelHelper::updateDuplicate('st_hotel', $data, $where);
                }elseif($num_rows == 0){
                    $data = array(
                        'post_id' => $id,
                        'multi_location' => $location_str,
                        'id_location' => $location_id,
                        'address' => $address,
                        'allow_full_day' => $allow_full_day,
                        'rate_review' => $rate_review,
                        'hotel_star' => $hotel_star,
                        'price_avg' => $price_avg,
                        'min_price' => $min_price,
                        'hotel_booking_period' => $hotel_booking_period,
                        'map_lat' => $map_lat,
                        'map_lng' => $map_lng,
                    );
                    TravelHelper::insertDuplicate('st_hotel', $data);
                }
            }
        }
        public function _delete_data($post_id){
            if(get_post_type($post_id) == 'st_hotel'){
                global $wpdb;
                $table = $wpdb->prefix.'st_hotel';
                $rs = TravelHelper::deleteDuplicateData($post_id, $table);
                if(!$rs)
                    return false;
                return true;
            }
        }
        function _get_list_room_by_hotel($post_id){
            global $wpdb;
            $sql = "SELECT * ,mt1.meta_value as multi_location
                    FROM {$wpdb->postmeta}
                    JOIN {$wpdb->postmeta} as mt1 ON mt1.post_id = {$wpdb->postmeta}.post_id and mt1.meta_key = 'multi_location'
                    WHERE
                    {$wpdb->postmeta}.meta_key = 'room_parent'

                    AND

                    {$wpdb->postmeta}.meta_value = '{$post_id}'

                    GROUP BY {$wpdb->postmeta}.post_id";

            $list_room = $wpdb->get_results( $sql );

            return $list_room;
        }
        /** 
        *@since 1.1.5
        **/
        function _update_list_location($id, $data){
            $location = STInput::request('multi_location', '');
            if(isset($_REQUEST['multi_location'])){
                if(is_array($location) && count($location)){
                    $location_str = '';
                    foreach($location as $item){
                        if(empty($location_str)){
                            $location_str.= $item;
                        }else{
                            $location_str.=','.$item;
                        }
                    }
                }else{
                    $location_str = '';
                }
                update_post_meta($id,'multi_location', $location_str);
                update_post_meta($id,'id_location', '');
            }
            
        }
        /**
         * Init the post type
         *
         * */
        function init_post_type()
        {
            if(!st_check_service_available($this->post_type))
            {
                return;
            }

            if(!function_exists('st_reg_post_type')) return;

            $labels = array(
                'name'               => __( 'Hotels', ST_TEXTDOMAIN ),
                'singular_name'      => __( 'Hotel', ST_TEXTDOMAIN ),
                'menu_name'          => __( 'Hotels', ST_TEXTDOMAIN ),
                'name_admin_bar'     => __( 'Hotel', ST_TEXTDOMAIN ),
                'add_new'            => __( 'Add New', ST_TEXTDOMAIN ),
                'add_new_item'       => __( 'Add New Hotel', ST_TEXTDOMAIN ),
                'new_item'           => __( 'New Hotel', ST_TEXTDOMAIN ),
                'edit_item'          => __( 'Edit Hotel', ST_TEXTDOMAIN ),
                'view_item'          => __( 'View Hotel', ST_TEXTDOMAIN ),
                'all_items'          => __( 'All Hotels', ST_TEXTDOMAIN ),
                'search_items'       => __( 'Search Hotels', ST_TEXTDOMAIN ),
                'parent_item_colon'  => __( 'Parent Hotels:', ST_TEXTDOMAIN ),
                'not_found'          => __( 'No hotels found.', ST_TEXTDOMAIN ),
                'not_found_in_trash' => __( 'No hotels found in Trash.', ST_TEXTDOMAIN ),
                'insert_into_item'   => __( 'Insert into Hotel', ST_TEXTDOMAIN),
                'uploaded_to_this_item'=> __( "Uploaded to this Hotel", ST_TEXTDOMAIN),
                'featured_image'=> __( "Feature Image", ST_TEXTDOMAIN),
                'set_featured_image'=> __( "Set featured image", ST_TEXTDOMAIN)
            );

            $args = array(
                'labels'             => $labels,
                'menu_icon'               =>'dashicons-building-yl',
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => get_option( 'hotel_permalink' ,'st_hotel' ) ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                //'menu_position'      => null,
                'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
            );

            st_reg_post_type( 'st_hotel', $args );

            $labels = array(
                'name'               => __( 'Room(s)', ST_TEXTDOMAIN ),
                'singular_name'      => __( 'Room', ST_TEXTDOMAIN ),
                'menu_name'          => __( 'Room(s)', ST_TEXTDOMAIN ),
                'name_admin_bar'     => __( 'Room', ST_TEXTDOMAIN ),
                'add_new'            => __( 'Add New', ST_TEXTDOMAIN ),
                'add_new_item'       => __( 'Add New Room', ST_TEXTDOMAIN ),
                'new_item'           => __( 'New Room', ST_TEXTDOMAIN ),
                'edit_item'          => __( 'Edit Room', ST_TEXTDOMAIN ),
                'view_item'          => __( 'View Room', ST_TEXTDOMAIN ),
                'all_items'          => __( 'All Rooms', ST_TEXTDOMAIN ),
                'search_items'       => __( 'Search Rooms', ST_TEXTDOMAIN ),
                'parent_item_colon'  => __( 'Parent Rooms:', ST_TEXTDOMAIN ),
                'not_found'          => __( 'No rooms found.', ST_TEXTDOMAIN ),
                'not_found_in_trash' => __( 'No rooms found in Trash.', ST_TEXTDOMAIN ),
                'insert_into_item'   => __( 'Insert into Room', ST_TEXTDOMAIN),
                'uploaded_to_this_item'=> __( "Uploaded to this Room", ST_TEXTDOMAIN),
                'featured_image'=> __( "Feature Image", ST_TEXTDOMAIN),
                'set_featured_image'=> __( "Set featured image", ST_TEXTDOMAIN)
            );

            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                //'show_in_menu'       => 'edit.php?post_type=hotel',
                'query_var'          => true,
                'rewrite'            => array( 'slug' => get_option( 'hotel_room_permalink' ,'hotel_room' ) ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
                'menu_icon'               =>'dashicons-building-yl',
                'exclude_from_search'=>true
            );

            st_reg_post_type( 'hotel_room', $args );

            $name=__('Room Type',ST_TEXTDOMAIN);
            $labels = array(
                'name'              => $name ,
                'singular_name'     => $name,
                'search_items'      => sprintf(__( 'Search %s' ,ST_TEXTDOMAIN),$name),
                'all_items'         => sprintf(__( 'All %s' ,ST_TEXTDOMAIN),$name),
                'parent_item'       => sprintf(__( 'Parent %s' ,ST_TEXTDOMAIN),$name),
                'parent_item_colon' => sprintf(__( 'Parent %s' ,ST_TEXTDOMAIN),$name),
                'edit_item'         => sprintf(__( 'Edit %s' ,ST_TEXTDOMAIN),$name),
                'update_item'       => sprintf(__( 'Update %s' ,ST_TEXTDOMAIN),$name),
                'add_new_item'      => sprintf(__( 'New %s' ,ST_TEXTDOMAIN),$name),
                'new_item_name'     => sprintf(__( 'New %s' ,ST_TEXTDOMAIN),$name),
                'menu_name'         => $name,
            );

            $args = array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           =>true,
                'show_ui'           => 'edit.php?post_type=st_hotel',
                'query_var'         => true,
            );

            st_reg_taxonomy('room_type' ,'hotel_room', $args );
        }
        /**
         *
         * @since 1.1.1
         * */
        function init_metabox()
        {

            $this->metabox[] = array(
                'id'       => 'hotel_metabox',
                'title'    => __('Hotel Information', ST_TEXTDOMAIN),
                'desc'     => '',
                'pages'    => array('st_hotel'),
                'context'  => 'normal',
                'priority' => 'high',
                'fields'   => array(
                    array(
                        'label' => __('Location', ST_TEXTDOMAIN),
                        'id'    => 'location_tab',
                        'type'  => 'tab'
                    ),

                    array(
                        'label'     => __('Location', ST_TEXTDOMAIN),
                        'id'        => 'multi_location', // id_location
                        'type'      => 'list_item_post_type',
                        'desc'        => __( 'Hotel Location', ST_TEXTDOMAIN),
                        'post_type'   =>'location'
                    ),
                    array(
                        'label' => __('Address', ST_TEXTDOMAIN),
                        'id'    => 'address',
                        'type'  => 'address_autocomplete',
                        'desc'  => __('Hotel Address', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label' => __('Maps', ST_TEXTDOMAIN),
                        'id'    => 'st_google_map',
                        'type'  => 'bt_gmap',
                        'std'   => 'off'
                    ),
                    array(
                        'label' => __('Enable Street Views', ST_TEXTDOMAIN),
                        'id'    => 'enable_street_views_google_map',
                        'type'  => 'on-off',
                        'desc'  => __('Enable Street Views', ST_TEXTDOMAIN),
                        'std'   => 'on'
                    ),
                    array(
                        'label' => __('Hotel Detail', ST_TEXTDOMAIN),
                        'id'    => 'detail_tab',
                        'type'  => 'tab'
                    ),
                    array(
                        'label' => __('Set as Featured', ST_TEXTDOMAIN),
                        'id'    => 'is_featured',
                        'type'  => 'on-off',
                        'desc'  => __('This is set as featured', ST_TEXTDOMAIN),
                        'std'   => 'off'
                    ),
                    array(
                        'label' => __('Hotel Logo', ST_TEXTDOMAIN),
                        'id'    => 'logo',
                        'type'  => 'upload',
                        'class' => '',
                        'desc'  => __('Upload your hotel logo; Recommend: 260px x 195px', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'   => __('Card Accepted', ST_TEXTDOMAIN),
                        'desc'    => __('Card Accepted', ST_TEXTDOMAIN),
                        'id'      => 'card_accepted',
                        'type'    => 'checkbox',
                        'choices' => $this->get_card_accepted_list()
                    ),
                    array(
                        'label'     => __('Custom Layout', ST_TEXTDOMAIN),
                        'id'        => 'st_custom_layout',
                        'post_type' => 'st_layouts',
                        'desc'      => __('Detail Hotel Layout', ST_TEXTDOMAIN),
                        'type'      => 'select',
                        'choices'   => st_get_layout('st_hotel')
                    ),


                    array(
                        'label' => __('Gallery', ST_TEXTDOMAIN),
                        'id'    => 'gallery',
                        'type'  => 'gallery',
                        'desc'  => __('Pick your own image for this hotel', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label' => __('Hotel Video', ST_TEXTDOMAIN),
                        'id'    => 'video',
                        'type'  => 'text',
                        'desc'  => __('Please use youtube or vimeo video', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'        => __('Star Rating', ST_TEXTDOMAIN),
                        'desc'         => __('Star Rating', ST_TEXTDOMAIN),
                        'id'           => 'hotel_star',
                        'type'         => 'numeric-slider',
                        'min_max_step' => '0,5,1',
                        'std'          => 0
                    ),
					array(
						'label'       => __( 'Contact Information', ST_TEXTDOMAIN),
						'id'          => 'agent_tab',
						'type'        => 'tab'
					),
					array(
						'label'       => __( 'Choose which contact info will be shown?', ST_TEXTDOMAIN),
						'id'          => 'show_agent_contact_info',
						'type'        => 'select',
						'choices'	  =>array(

							array(
								'label'=>__("Use Theme Options",ST_TEXTDOMAIN),
								'value'=>'use_theme_option'
							),
							array(
								'label'=>__("Use Agent Contact Info",ST_TEXTDOMAIN),
								'value'=>'user_agent_info'
							),
							array(
								'label'=>__("Use Item Info",ST_TEXTDOMAIN),
								'value'=>'user_item_info'
							),
						),
						'desc'        => __( 'Show Agent Contact Info', ST_TEXTDOMAIN),
					),
					array(
						'label' => __('Hotel Email', ST_TEXTDOMAIN),
						'id'    => 'email',
						'type'  => 'text',
						'desc'  => __('Hotel Email Address, this address will received email when have new booking', ST_TEXTDOMAIN),
					),

					array(
						'label' => __('Hotel Website', ST_TEXTDOMAIN),
						'id'    => 'website',
						'type'  => 'text',
						'desc'  => __('Hotel Website. Ex: <em>http://domain.com</em>', ST_TEXTDOMAIN),
					),
					array(
						'label' => __('Hotel Phone', ST_TEXTDOMAIN),
						'id'    => 'phone',
						'type'  => 'text',
						'desc'  => __('Hotel Phone Number', ST_TEXTDOMAIN),
					),
					array(
						'label' => __('Fax Number', ST_TEXTDOMAIN),
						'id'    => 'fax',
						'type'  => 'text',
						'desc'  => __('Hotel Fax Number', ST_TEXTDOMAIN),
					),
                    array(
                        'label' => __('Price', ST_TEXTDOMAIN),
                        'id'    => 'sale_number_tab',
                        'type'  => 'tab'
                    ),

                    /*array(
                        'label' => __('Total Sale Number', ST_TEXTDOMAIN),
                        'id'    => 'total_sale_number',
                        'type'  => 'text',
                        'desc'  => __('Total Number Booking of this hotel', ST_TEXTDOMAIN),
                        'std'   => 0
                    ),*/

                    array(
                        'label' => __('Auto calculate average price', ST_TEXTDOMAIN),
                        'id'    => 'is_auto_caculate',
                        'type'  => 'on-off',
                        'desc'  => __('Average price is automatically calculated', ST_TEXTDOMAIN),
                        'std'   => 'on'
                    ),
                    array(
                        'label'      => __('Average price', ST_TEXTDOMAIN),
                        'id'         => 'price_avg',
                        'type'       => 'text',
                        'desc'       => __('Average price', ST_TEXTDOMAIN),
                        'std'        => 0,
                        'conditions' => 'is_auto_caculate:is(on)'
                    ),

                    array(
                        'label' => __('Check in/out time', ST_TEXTDOMAIN),
                        'id' => 'check_in_out_time',
                        'type' => 'tab'
                    ),
                    array(
                        'label' => __('Allow booking full day', ST_TEXTDOMAIN),
                        'id' => 'allow_full_day',
                        'type' => 'on-off',
                        'std' => 'on',
                        'desc' => __('Allow hotel is booked full day', ST_TEXTDOMAIN)
                    ),
                    array(
                        'label' => __('Check in time', ST_TEXTDOMAIN),
                        'id' => 'check_in_time',
                        //'type' => 'st_timepicker',
                        'type'  => 'text',
                        'std' => '12:00 pm',
                        'desc' => __('Check in time', ST_TEXTDOMAIN)
                    ),
                    array(
                        'label' => __('Check out time', ST_TEXTDOMAIN),
                        'id' => 'check_out_time',
                        //'type' => 'st_timepicker',
                        'type'  => 'text',
                        'std' => '12:00 pm',
                        'desc' => __('Check out time in the next day', ST_TEXTDOMAIN)
                    ),
                    array(
                        'label' => __('Other Options', ST_TEXTDOMAIN),
                        'id'    => 'hotel_options',
                        'type'  => 'tab'
                    ),
                    array(
                        'label'        => __('Minimum days to book before arrival', ST_TEXTDOMAIN),
                        'id'           => 'hotel_booking_period',
                        'type'         => 'numeric-slider',
                        'min_max_step' => '0,30,1',
                        'std'          => 0,
                        'desc'         => __('The time period allowed booking.', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'        => __('Minimum days to book room', ST_TEXTDOMAIN),
                        'id'           => 'min_book_room',
                        'type'         => 'numeric-slider',
                        'min_max_step' => '0,30,1',
                        'std'          => 0,
                        'desc'         => __('The Minium days allowed to book room.', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'       => __( 'Hotel policy', ST_TEXTDOMAIN),
                        'id'          => 'hotel_policy_tab',
                        'type'        => 'tab'
                        ),
                    array(
                        'label'       => __( 'Hotel policy', ST_TEXTDOMAIN),
                        'id'          => 'hotel_policy',
                        'type'        => 'list-item',
                        'settings'      =>array(
                            array(
                                'label' => __('Policy Description' , ST_TEXTDOMAIN) ,
                                'id'    =>'policy_description',
                                'type'  => 'textarea'
                                )
                            ),

                        ),                    
                    array(
                        'label'       => __( 'Discount Flash', ST_TEXTDOMAIN),
                        'id'          => 'discount_banner_tab',
                        'type'        => 'tab'
                    ),
                    array(
                        'label'       => __( 'Discount Text', ST_TEXTDOMAIN),
                        'desc'       => __( 'Discount Text', ST_TEXTDOMAIN),
                        'id'          => 'discount_text',
                        'type'        => 'text',
                    ),
//            array(
//                'label'       => __( 'Avg Rate Review', ST_TEXTDOMAIN),
//                'id'          => 'rate_review',
//                'type'        => 'numeric-slider',
//                'min_max_step'=> '1,5,1',
//            ),


                )
            );

            $custom_field = st()->get_option('hotel_unlimited_custom_field');
            if (!empty($custom_field) and is_array($custom_field)) {
                $this->metabox[0]['fields'][] = array(
                    'label' => __('Custom fields', ST_TEXTDOMAIN),
                    'id'    => 'custom_field_tab',
                    'type'  => 'tab'
                );
                foreach ($custom_field as $k => $v) {
                    $key = str_ireplace('-', '_', 'st_custom_' . sanitize_title($v['title']));
                    $this->metabox[0]['fields'][] = array(
                        'label' => $v['title'],
                        'id'    => $key,
                        'type'  => $v['type_field'],
                        'desc'  => '<input value=\'[st_custom_meta key="' . $key . '"]\' type=text readonly />',
                        'std'   => $v['default_field']
                    );
                }
            }

            parent::register_metabox($this->metabox);
        }

        /**
         *
         *
         * @since 1.1.1
         * */
        function get_card_accepted_list()
        {
            $data = array();

            $options = st()->get_option('booking_card_accepted', array());

            if (!empty($options)) {
                foreach ($options as $key => $value) {
                    $data[] = array(
                        'label' => $value['title'],
                        'src'   => $value['image'],
                        'value' => sanitize_title_with_dashes($value['title'])
                    );
                }
            }

            return $data;
        }

        /**
         *
         *
         * @update 1.2.4
         *
         */
        static function _update_avg_price($post_id = FALSE)
        {
            if (!$post_id) {
                $post_id = get_the_ID();
            }
            $post_type = get_post_type($post_id);
            if ($post_type == 'st_hotel') {
                $hotel_id = $post_id;
                $is_auto_caculate = get_post_meta($hotel_id, 'is_auto_caculate', TRUE);
                if ($is_auto_caculate != 'off') {
                    $query = array(
                        'post_type'      => 'hotel_room',
                        'posts_per_page' => 100,
                        'meta_key'       => 'room_parent',
                        'meta_value'     => $hotel_id
                    );
                    $traver = new WP_Query($query);
                    $price = 0;
                    while ($traver->have_posts()) {
                        $traver->the_post();
                        $discount = get_post_meta(get_the_ID(), 'discount_rate', TRUE);
                        $item_price = get_post_meta(get_the_ID(), 'price', TRUE);
                        if($discount) {
                            if($discount > 100) $discount = 100;
                            $item_price = $item_price - ( $item_price / 100 ) * $discount;
                        }
                        $price += $item_price ;
                    }
                    wp_reset_query();
                    $avg_price=0;
                    if($traver->post_count){
                        $avg_price = $price / $traver->post_count;
                    }
                    update_post_meta($hotel_id,'price_avg',$avg_price);
                }
            }
        }
        /**
         *
         *
         * @update 1.2.4
         *
         */
        static function _update_min_price($post_id = FALSE)
        {
            if (!$post_id) {
                $post_id = get_the_ID();
            }
            $post_type = get_post_type($post_id);
            if ($post_type == 'st_hotel') {
                $hotel_id = $post_id;
                $query = array(
                    'post_type'      => 'hotel_room',
                    'posts_per_page' => 999,
                    'meta_key'       => 'room_parent',
                    'meta_value'     => $hotel_id
                );
                $traver = new WP_Query($query);

                $prices= array();
                while ($traver->have_posts()) {
                    $traver->the_post();
                    $discount = get_post_meta(get_the_ID(), 'discount_rate', TRUE);
                    $item_price = get_post_meta(get_the_ID(), 'price', TRUE);
                    if($discount) {
                        if($discount > 100) $discount = 100;
                        $item_price = $item_price - ( $item_price / 100 ) * $discount;
                    }
                    $prices[] = $item_price;
                }
                wp_reset_query();
                if(!empty($prices)){
                    $min_price = min($prices);
                    update_post_meta($hotel_id,'min_price',$min_price);
                }else{
                    update_post_meta($hotel_id,'min_price','0');
                }
            }
        }


        function _resend_mail()
        {
            $order_item = isset($_GET['order_item_id']) ? $_GET['order_item_id'] : FALSE;

            $test = isset($_GET['test']) ? $_GET['test'] : FALSE;

            if ($order_item) {

                $order = $order_item;

                if ($test) {
                    global $order_id;
                    $order_id = $order_item;
                    $email_to_admin = st()->get_option('email_for_admin', '');
                    $email = st()->load_template('email/header');
                    $email .= do_shortcode($email_to_admin);
                    $email .= st()->load_template('email/footer');
                    echo($email);
                    die;

                }


                if ($order) {
                    $booking_by = get_post_meta($order_item, 'booking_by', true);
                    $made_by_admin = false;
                    if($booking_by && $booking_by == 'admin'){
                        $made_by_admin = true;
                    }
                    STCart::send_mail_after_booking($order, $made_by_admin);
                }
            }

            wp_safe_redirect(self::$booking_page . '&send_mail=success');
        }

        static function  st_room_select_ajax()
        {
            extract(wp_parse_args($_GET, array(
                'room_parent' => '',
                'post_type'   => '',
                'q'           => ''
            )));

            $query = array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'posts_per_page' => 10,
                'meta_query' => array(
                    array(
                        'key'     => 'room_parent',
                        'value'   => $room_parent,
                        'compare' => 'IN',
                    ),
                ),
            );
            query_posts($query);
            
            $r = array(
                'items' => array(),
            );
            while (have_posts()) {
                the_post();
                $r['items'][] = array(
                    'id'          => get_the_ID(),
                    'name'        => get_the_title(),
                    'description' => ''
                );
            }

            wp_reset_query();

            echo json_encode($r);
            die;

        }

        static function  add_edit_scripts()
        {
            wp_enqueue_script('select2');
            wp_enqueue_script('st-edit-booking', get_template_directory_uri() . '/js/admin/edit-booking.js', array('jquery'), NULL, TRUE);
            wp_enqueue_script('st-hotel-edit-booking', get_template_directory_uri() . '/js/admin/hotel-booking.js', array('jquery'), NULL, TRUE);
            wp_enqueue_script('st-jquery-ui-datepicker',get_template_directory_uri().'/js/jquery-ui.js');
            wp_enqueue_style('jjquery-ui.theme.min.css', get_template_directory_uri() . '/css/admin/jquery-ui.min.css');
            
        }

        static function is_booking_page()
        {
            if (is_admin()
                and isset($_GET['post_type'])
                and $_GET['post_type'] == 'st_hotel'
                and isset($_GET['page'])
                and $_GET['page'] = 'st_hotel_booking'
            ) return TRUE;

            return FALSE;
        }

        function add_menu_page()
        {
            //Add booking page

            add_submenu_page('edit.php?post_type=st_hotel',__('Hotel Bookings',ST_TEXTDOMAIN), __('Hotel Bookings',ST_TEXTDOMAIN), 'manage_options', 'st_hotel_booking', array($this,'__hotel_booking_page'));
        }

        function  __hotel_booking_page()
        {

            $section = isset($_GET['section']) ? $_GET['section'] : FALSE;

            if ($section) {
                switch ($section) {
                    case "edit_order_item":
                        $this->edit_order_item();
                        break;
                    /*case 'add_booking':
                        $this->add_booking();
                        break;*/
                }
            } else {

                $action = isset($_POST['st_action']) ? $_POST['st_action'] : FALSE;
                switch ($action) {
                    case "delete":
                        $this->_delete_items();
                        break;
                }
                echo balanceTags($this->load_view('hotel/booking_index', FALSE));
            }

        }

        function add_booking()
        {

            echo balanceTags($this->load_view('hotel/booking_edit', FALSE, array('page_title' => __('Add new Hotel Booking', ST_TEXTDOMAIN))));
        }

        function _delete_items()
        {

            if (empty($_POST) or !check_admin_referer('shb_action', 'shb_field')) {
                //// process form data, e.g. update fields
                return;
            }
            $ids = isset($_POST['post']) ? $_POST['post'] : array();
            if (!empty($ids)) {
                foreach ($ids as $id)
                    wp_delete_post($id, TRUE);

            }

            STAdmin::set_message(__("Delete item(s) success", ST_TEXTDOMAIN), 'updated');

        }

        function edit_order_item()
        {
            $item_id = isset($_GET['order_item_id']) ? $_GET['order_item_id'] : FALSE;
            if (!$item_id or get_post_type($item_id) != 'st_order') {
                //wp_safe_redirect(self::$booking_page); die;
                return FALSE;
            }


            

            echo balanceTags($this->load_view('hotel/booking_edit'));
        }

        function _add_booking()
        {
            if (!check_admin_referer('shb_action', 'shb_field')) die;

            $data = $this->_check_validate();
            if(is_array($data) && count($data)){
                extract($data);

                $order = array(
                    'post_title'  => __('Order', ST_TEXTDOMAIN) . ' - ' . date(get_option('date_format')) . ' @ ' . date(get_option('time_format')),
                    'post_type'   => 'st_order',
                    'post_status' => 'publish'
                );

                $order_id = wp_insert_post($order);

                if($order_id){

                    $check_out_field = STCart::get_checkout_fields();

                    if(!empty($check_out_field)){
                        foreach ($check_out_field as $field_name => $field_desc) {
                            update_post_meta($order_id, $field_name, STInput::post($field_name));
                        }
                    }

                    $id_user = get_current_user_id();
                    update_post_meta($order_id, 'id_user', $id_user);

                    update_post_meta($order_id, 'payment_method', 'st_submit_form');

                    $item_price = floatval(get_post_meta($room_id, 'price', true));
                    $origin_price = STPrice::getRoomPriceOnlyCustomPrice($room_id, strtotime($check_in), strtotime($check_out), $room_num_search);
                    //Extra price
                    $extras = STInput::request('extra_price', array());
                    $numberday = TravelHelper::dateDiff($check_in, $check_out);
                    $extra_price = STPrice::getExtraPrice($room_id, $extras, $room_num_search, $numberday);

                    $sale_price = STPrice::getRoomPrice($room_id, strtotime($check_in), strtotime($check_out), $room_num_search);
                    $price_with_tax = STPrice::getPriceWithTax($sale_price + $extra_price);
                    $deposit_money['data'] = array();
                        
                    $deposit_money = STPrice::getDepositData($room_id, $deposit_money);

                    if(!empty($deposit_money['data']['deposit_money'])){
                        $data_deposit_money = $deposit_money['data']['deposit_money'];
                    } else $data_deposit_money="";

                    $deposit_price = STPrice::getDepositPrice($data_deposit_money, $price_with_tax, 0);
                    if(isset($deposit_money['data']['deposit_money'])){
                        $total_price = $deposit_price;
                    }else{
                        $total_price = $price_with_tax;
                    }
                    $data_prices = array(
                        'origin_price' => $origin_price,
                        'sale_price' => $sale_price + $extra_price,
                        'coupon_price' => 0,
                        'price_with_tax' => $price_with_tax,
                        'total_price' => $total_price,
                        'deposit_price' => $deposit_price
                    );
                    
                    $item_data = array(
                        'item_number' => $room_num_search,
                        'item_id'     => $item_id,
                        'item_price'  => $item_price,
                        'check_in'    => date('Y-m-d',strtotime($check_in)),
                        'check_out'   => date('Y-m-d',strtotime($check_out)),
                        'adult_number' => $adult_number,
                        'child_number' => $child_number,
                        'room_id' => $room_id,
                        'total_price' => $total_price,
                        'deposit_money' => $data_deposit_money,
                        'booking_by' => 'admin',
                        'st_tax' => STPrice::getTax(),
                        'st_tax_percent' => STPrice::getTax(),
                        'status' => $_POST['status'],
                        'data_prices' => $data_prices,
                        'extras' => $extras,
                        'extra_price' => $extra_price,
                        'currency'        => TravelHelper::get_current_currency('symbol'),
                        'currency_rate' => TravelHelper::get_current_currency('rate'),
                        'commission' => TravelHelper::get_commission()
                    );

                    foreach ($item_data as $val => $value) {
                        update_post_meta($order_id, $val, $value);
                    }

                    do_action('st_booking_success', $order_id);

                    //Check email
                    $user_name=STInput::post('st_email');
                    $user_id = email_exists( $user_name );
                    if ( !$user_id and email_exists($user_name) == false OR $user_id == false) {
                        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                        $userdata = array(
                            'user_login'  =>  $user_name,
                            'user_pass'   =>  $random_password,
                            'user_email'  =>$user_name,
                            'first_name'  =>STInput::post('st_first_name'), // When creating an user, `user_pass` is expected.
                            'last_name'  =>STInput::post('st_last_name') // When creating an user, `user_pass` is expected.
                        );
                        $user_id = wp_insert_user( $userdata );
                        //Create User Success, send the nofitication
                        wp_new_user_notification($user_id);
                    }

                    if(TravelHelper::checkTableDuplicate('st_hotel')){
                        global $wpdb;

                        $table = $wpdb->prefix.'st_order_item_meta';
                        $g_post = get_post($item_id);
                        $partner_id = $g_post ? $g_post->post_author : '';
                        global $sitepress;
                        if($sitepress){
                            $post_type = get_post_type($st_booking_id);
                            if($post_type == 'st_hotel'){
                                $post_type = 'hotel_room';
                                $id = $room_id;
                            }else{
                                $id = $st_booking_id;
                            }
                            $lang_code = $sitepress->get_default_language();
                            $origin_id = icl_object_id($id, $post_type, true, $lang_code);
                        }else{
                            $origin_id = $st_booking_id;
                        }
                        $data = array(
                            'order_item_id' => $order_id,
                            'type' => 'normal_booking',
                            'check_in' => $check_in,
                            'check_out' => $check_out,
                            'st_booking_post_type' => 'st_hotel',
                            'st_booking_id' => $item_id,
                            'room_id' => $room_id,
                            'adult_number' => $adult_number,
                            'child_number' => $child_number,
                            'room_num_search' => $room_num_search,
                            'check_in_timestamp' => strtotime($check_in),
                            'check_out_timestamp' => strtotime($check_out),
                            'user_id' => $id_user,
                            'status' => $_POST['status'],
                            'wc_order_id' => $order_id,
                            'partner_id' => $partner_id,
                            'created' => get_the_date('Y-m-d',$order_id),
                            'total_order'=>$total_price,
                            'commission' => TravelHelper::get_commission(),
                            'origin_id' => $origin_id
                        );
                        $wpdb->insert($table, $data);
                    }
                    
                    //STCart::send_mail_after_booking($order_id, true);
                    STCart::send_email_confirm($order_id);
                    
                    wp_safe_redirect(self::$booking_page);
                }
            }else{
                return false;
            }

        }

        function _save_booking($order_id)
        {
            if (!check_admin_referer('shb_action', 'shb_field')) die;
            $data = $this->_check_validate();
            if(is_array($data)){
                
                $item_data = array(
                    'status' => $_POST['status'],
                );

                foreach ($item_data as $val => $value) {
                    update_post_meta($order_id, $val, $value);
                }

                $check_out_field = STCart::get_checkout_fields();

                if (!empty($check_out_field)) {
                    foreach ($check_out_field as $field_name => $field_desc) {
                        update_post_meta($order_id, $field_name, STInput::post($field_name));
                    }
                }
                
                if(TravelHelper::checkTableDuplicate('st_hotel')){
                    global $wpdb;
                    $table = $wpdb->prefix.'st_order_item_meta';
                    $where = array(
                        'order_item_id' => $order_id
                    );
                    $data = array(
                        'status' => $_POST['status']
                    );
                    $wpdb->update($table, $data, $where);
                }

                do_action('update_booking_hotel',$order_id);

                STCart::send_mail_after_booking($order_id, true);
                wp_safe_redirect(self::$booking_page);
            }
        }

        public function _check_validate(){
            $data = array();
            $order_item_id = STInput::request('order_item_id','');

            $st_first_name = STInput::request('st_first_name','');
            if(empty($st_first_name)){
                STAdmin::set_message(__('The firstname field is not empty.', ST_TEXTDOMAIN), 'danger');
                return false;
            }

            $st_last_name = STInput::request('st_last_name','');
            if(empty($st_last_name)){
                STAdmin::set_message(__('The lastname field is not empty.', ST_TEXTDOMAIN), 'danger');
                return false;
            }

            $st_email = STInput::request('st_email', '');
            if(empty($st_email)){
                STAdmin::set_message(__('The email field is not empty.', ST_TEXTDOMAIN), 'danger');
                return false;
            }

            if(!filter_var($st_email, FILTER_VALIDATE_EMAIL)){
                STAdmin::set_message(__('Invalid email format.', ST_TEXTDOMAIN), 'danger');
                return false;
            }

            $st_phone = STInput::request('st_phone', '');
            if(empty($st_phone)){
                STAdmin::set_message(__('The phone field is not empty.', ST_TEXTDOMAIN), 'danger');
                return false;
            }
            if(STInput::request('section', '') != 'edit_order_item'){
                $item_id = intval(STInput::request('item_id', ''));
                if($item_id <= 0 || get_post_type($item_id) != 'st_hotel'){
                    STAdmin::set_message(__('This hotel is not available.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }

                $room_id = intval(STInput::request('room_id', ''));
                if($room_id <= 0 || get_post_type($room_id) != 'hotel_room' || get_post_meta($room_id, 'room_parent', true) != $item_id){
                    STAdmin::set_message(__('This room is not available.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }

                $check_in = STInput::request('check_in', '');
                if(empty($check_in)){
                    STAdmin::set_message(__('Date is invalid', ST_TEXTDOMAIN), 'danger');
                    return false;
                }

                $check_out = STInput::request('check_out', '');
                if(empty($check_out)){
                    STAdmin::set_message(__('Date is invalid', ST_TEXTDOMAIN), 'danger');
                    return false;
                }
                if(STInput::request('item_number', '') == ""){
                    STAdmin::set_message(__('The number of room field is not empty', ST_TEXTDOMAIN), 'danger');
                    return false;
                }
                $room_num_search = intval(STInput::request('item_number', ''));
                if($room_num_search <= 0) $room_num_search = 1;

                if(STInput::request('adult_number', '') == ""){
                    STAdmin::set_message(__('The No. adults field is not empty', ST_TEXTDOMAIN), 'danger');
                    return false;
                }
                $adult_number = intval(STInput::request('adult_number', ''));
                if($adult_number <= 0) $adult_number = 1;

                if(STInput::request('child_number', '') == ""){
                    STAdmin::set_message(__('The No. children field is not empty', ST_TEXTDOMAIN), 'danger');
                    return false;
                }
                $child_number = intval(STInput::request('child_number', ''));
                if($child_number <= 0) $child_number = 0;

                if(strtotime($check_out) - strtotime($check_in) <= 0){
                    STAdmin::set_message(__('The check-out is later than the check-in.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }

                $num_room = intval(get_post_meta($room_id, 'number_room', true));
                $adult = intval(get_post_meta($room_id, 'adult_number', true));
                $children = intval(get_post_meta($room_id, 'children_number', true));

                if($room_num_search > $num_room){
                    STAdmin::set_message(__('Max of rooms are incorrect.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }
                if($adult_number > $adult){
                    STAdmin::set_message(__('Number of adults in the room are incorrect.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }
                if($child_number > $children){
                    STAdmin::set_message(__('Number of children in the room are incorrect.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }
                
                $today = date('m/d/Y');

                $period = TravelHelper::dateDiff($today, $check_in);
                $compare = TravelHelper::dateCompare($today, $check_in);
                
                $booking_period = get_post_meta($item_id,'hotel_booking_period', true);
                if(empty($booking_period) || $booking_period <=0) $booking_period = 0;

                if($compare < 0){
                    STAdmin::set_message( __( 'You can not set check-in date in the past' , ST_TEXTDOMAIN ) , 'danger' );
                    return false;
                }
                if ($period < $booking_period) {
                    STAdmin::set_message(sprintf(__('This hotel allow minimum booking is %d day(s)', ST_TEXTDOMAIN), $booking_period), 'danger');
                    return false;
                } 

                $checkin_ymd = date('Y-m-d', strtotime($check_in));
                $checkout_ymd = date('Y-m-d', strtotime($check_out));
                if(!HotelHelper::check_day_cant_order($room_id, $checkin_ymd, $checkout_ymd, $room_num_search)){
                    STAdmin::set_message(sprintf(__('This room is not available from %s to %s.', ST_TEXTDOMAIN), $checkin_ymd, $checkout_ymd), 'danger');
                    $pass_validate = FALSE;
                    return false;
                }
                if(!HotelHelper::_check_room_available($room_id, $checkin_ymd, $checkout_ymd, $room_num_search, $order_item_id)){
                    STAdmin::set_message(__('You can not book this room.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }

                $data = array(
                    'order_item_id' => $order_item_id,
                    'item_id' => $item_id,
                    'type' => 'normal_booking',
                    'check_in' => $check_in,
                    'check_out' => $check_out,
                    'st_booking_post_type' => 'st_hotel',
                    'st_booking_id' => $item_id,
                    'room_id' => $room_id,
                    'adult_number' => $adult_number,
                    'child_number' => $child_number,
                    'room_num_search' => $room_num_search,
                    'check_in_timestamp' => strtotime($check_in),
                    'check_out_timestamp' => strtotime($check_out),
                    'status' => $_POST['status'],
                );
            }
            
            return $data;
        }
        function is_able_edit()
        {
            $item_id = isset($_GET['order_item_id']) ? $_GET['order_item_id'] : FALSE;
            if (!$item_id or get_post_type($item_id) != 'st_order') {
                wp_safe_redirect(self::$booking_page);
                die;
            }

            return TRUE;
        }


        function add_col_header($defaults)
        {

            $this->array_splice_assoc($defaults,2,0,array('room_number'=>__('Room(s)',ST_TEXTDOMAIN)));
            $this->array_splice_assoc($defaults,2,0,array('hotel_parent'=>__('Hotel',ST_TEXTDOMAIN)));

            return $defaults;
        }

        function add_hotel_col_header($defaults)
        {
            $this->array_splice_assoc($defaults, 2, 0, array('hotel_layout' => __('Layout', ST_TEXTDOMAIN)));

            return $defaults;
        }

        function array_splice_assoc(&$input, $offset, $length = 0, $replacement = array())
        {
            $tail = array_splice($input, $offset);
            $extracted = array_splice($tail, 0, $length);
            $input += $replacement + $tail;

            return $extracted;
        }

        function add_col_content($column_name, $post_ID)
        {

            if ($column_name == 'hotel_parent') {
                // show content of 'directors_name' column
                $parent = get_post_meta($post_ID, 'room_parent', TRUE);

                if ($parent) {
                    echo "<a href='" . get_edit_post_link($parent) . "'>" . get_the_title($parent) . "</a>";
                }

            }
            if ($column_name == 'room_number') {
                echo get_post_meta($post_ID, 'number_room', TRUE);
            }
        }

        function add_hotel_col_content($column_name, $post_ID)
        {

            if ($column_name == 'hotel_layout') {
                // show content of 'directors_name' column
                $parent = get_post_meta($post_ID, 'st_custom_layout', TRUE);

                if ($parent) {
                    echo "<a href='" . get_edit_post_link($parent) . "'>" . get_the_title($parent) . "</a>";
                } else {
                    echo __('Default', ST_TEXTDOMAIN);
                }


            }
        }

        static function st_get_custom_price_by_date( $post_id , $start_date = null , $price_type = 'default' )
        {
            global $wpdb;
            if(!$post_id)
                $post_id = get_the_ID();
            if(empty( $start_date ))
                $start_date = date( "Y-m-d" );
            $rs = $wpdb->get_results( "SELECT * FROM " . $wpdb->base_prefix . "st_price WHERE post_id=" . $post_id . " AND price_type='" . $price_type . "'  AND start_date <='" . $start_date . "' AND end_date >='" . $start_date . "' AND status=1 ORDER BY priority DESC LIMIT 1" );
            if(!empty( $rs )) {
                return $rs[ 0 ]->price;
            } else {
                return false;
            }
        }

        static function getRoomHotelInfo(){
            $room_id = intval(STInput::request('room_id', ''));
            $data = array(
                'price' => '',
                'extras' => 'None',
                'adult_html' => '',
                'child_html' => '',
                'room_html' => ''
            );
            if($room_id <= 0 || get_post_type($room_id) != 'hotel_room'){
                echo json_encode($data);
            }else{
                $html = '';
                $price = floatval(get_post_meta($room_id, 'price', true));
                $adult_number = intval(get_post_meta($room_id, 'adult_number', true));
                if($adult_number <= 0) $adult_number = 1;
                $adult_html = '<select name="adult_number" class="form-control" style="width: 100px">';
                for($i = 1; $i <= $adult_number; $i++){
                    $adult_html .= '<option value="'.$i.'">'.$i.'</option>';
                }
                $adult_html .= '</select>';

                $child_number = intval(get_post_meta($room_id, 'children_number', true));
                if($child_number <= 0) $child_number = 0;
                $child_html = '<select name="child_number" class="form-control" style="width: 100px">';
                for($i = 0; $i <= $child_number; $i++){
                    $child_html .= '<option value="'.$i.'">'.$i.'</option>';
                }
                $child_html .= '</select>';

                $room_number = intval(get_post_meta($room_id, 'number_room', true));
                if($room_number <= 0) $room_number = 1;
                $room_html = '<select name="room_num_search" class="form-control" style="width: 100px">';
                for($i = 1; $i <= $room_number; $i++){
                    $room_html .= '<option value="'.$i.'">'.$i.'</option>';
                }
                $room_html .= '</select>';

                $extras = get_post_meta($room_id, 'extra_price', true);
                if(is_array($extras) && count($extras)):
                    $html = '<table class="table">';
                    foreach($extras as $key => $val):
                    $html .= '
                    <tr>
                        <td width="80%">
                            <label for="'.$val['extra_name'].'" class="ml20">'.$val['title'].' ('.TravelHelper::format_money($val['extra_price']).')'.'</label>
                            <input type="hidden" name="extra_price[price]['.$val['extra_name'].']" value="'.$val['extra_price'].'">
                            <input type="hidden" name="extra_price[title]['.$val['extra_name'].']" value="'.$val['title'].'">
                        </td>
                        <td width="20%">
                            <select style="width: 100px" class="form-control" name="extra_price[value]['.$val['extra_name'].']" id="">';
                            
                                $max_item = intval($val['extra_max_number']);
                                if($max_item <= 0) $max_item = 1;
                                for($i = 0; $i <= $max_item; $i++):
                                $html .= '<option value="'.$i.'">'.$i.'</option>';
                            endfor;
                        $html .= '
                            </select>
                        </td>
                    </tr>';
                    endforeach;
                $html .= '</table>';
                endif;
                $data['price'] = TravelHelper::format_money_from_db($price, false);
                $data['extras'] = $html;
                $data['adult_html'] = $adult_html;
                $data['child_html'] = $child_html;
                $data['room_html'] = $room_html;
                echo json_encode($data);
            }
            die();
        }
        static function getRoomHotel(){
            $hotel_id = intval(STInput::request('hotel_id',''));
            $room_id = intval(STInput::request('room_id',''));
            if($hotel_id <=0 || get_post_type($hotel_id) != 'st_hotel'){
                echo "";
                die();
            }else{
                $list_room = "<select name='room_id' id='room_id' class='form-control form-control-admin'>";
                $list_room .= "<option value=''>".__('----Select a room----', ST_TEXTDOMAIN)."</option>";
                $query = array(
                    'post_status' => 'publish',
                    'post_type' => 'hotel_room',
                    'posts_per_page' => -1,
                    'order_by' => 'title',
                    'order' => 'DESC',
                    'meta_query' => array(
                        array(
                            'key'     => 'room_parent',
                            'value'   => $hotel_id,
                            'compare' => 'IN',
                        ),
                    ),
                );

                query_posts($query);
                while(have_posts()): the_post();
                    $selected = ($room_id == intval(get_the_ID())) ? 'selected' : '';
                    $list_room .= "<option ".$selected." value='".get_the_ID()."'>".get_the_title()."</option>";
                endwhile;
                wp_reset_query(); wp_reset_postdata();
                $list_room .= "</select>";

                echo $list_room;
                die();
            }
        }
    }

    new STAdminHotel();
}