<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STAdminRental
 *
 * Created by ShineTheme
 *
 */
$order_id = 0;
if(!class_exists('STAdminRental'))
{

    class STAdminRental extends STAdmin
    {

        static $booking_page;
        public $metabox;
        static $_table_version= "1.2.7";
        public $post_type='st_rental';


        function __construct()
        {


            add_action('init',array($this,'_reg_post_type'),8);

            if (!st_check_service_available($this->post_type)) return;

            //add colum for rooms
            add_filter('manage_st_rental_posts_columns', array($this,'add_col_header'), 10);
            add_action('manage_st_rental_posts_custom_column', array($this,'add_col_content'), 10, 2);

            self::$booking_page=admin_url('edit.php?post_type=st_rental&page=st_rental_booking');
            //rental Hook
            /*
             * todo Re-cal rental min price
             * */

            add_action( 'save_post', array($this,'meta_update_sale_price') ,10,4);
            add_action('admin_menu',array($this,'new_menu_page'));

            //Check booking edit and redirect
            if(self::is_booking_page())
            {
                add_action('admin_enqueue_scripts',array(__CLASS__,'add_edit_scripts'));

                add_action('admin_init',array($this,'_do_save_booking'));
            }

            if(isset($_GET['send_mail']) and $_GET['send_mail']=='success')
            {
                self::set_message(__('Email sent',ST_TEXTDOMAIN),'updated');
            }

            add_action('wp_ajax_st_room_select_ajax',array(__CLASS__,'st_room_select_ajax'));


            add_action('init',array($this,'_add_metabox'));

            add_action('st_search_fields_name',array($this,'get_search_fields_name'),10,2);
            /**
            *@since 1.1.6
            **/
            add_action('save_post', array($this, '_update_list_location'), 50, 2);
            add_action('save_post', array($this, '_update_duplicate_data'), 50, 2);
            add_action( 'before_delete_post', array($this, '_delete_data'), 50 );

            parent::__construct();

            add_action('wp_ajax_st_getRentalInfo', array(__CLASS__,'getRentalInfo'), 9999);

            /**
            *   since 1.2.4 
            *   auto create & update table st_rental
            **/
            add_action( 'after_setup_theme', array (__CLASS__, '_check_table_rental') );
        }

        static function check_ver_working(){
            $dbhelper = new DatabaseHelper(self::$_table_version);
            return $dbhelper->check_ver_working( 'st_rental_table_version' );
        }
        static function _check_table_rental(){
            $dbhelper = new DatabaseHelper(self::$_table_version);
            $dbhelper->setTableName('st_rental');
            $column = array(
                'post_id' => array(
                    'type' => 'INT',
                    'length' => 11,
                ),
                'multi_location' => array(
                    'type' => 'text',
                ),
                'location_id'=> array(
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
                'rental_max_adult' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'rental_max_children' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'rate_review' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'sale_price' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'rentals_booking_period' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'is_sale_schedule' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'discount_rate' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'sale_price_from' => array(
                    'type' => 'date',
                    'length' => 255
                ),
                'sale_price_to' => array(
                    'type' => 'date',
                    'length' => 255
                ),
                'price' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
            );

            $column = apply_filters( 'st_change_column_st_rental', $column );

            $dbhelper->setDefaultColums( $column );
            $dbhelper->check_meta_table_is_working( 'st_rental_table_version' );

            return array_keys( $column );
        }

        function _do_save_booking(){
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
        public function _delete_data($post_id){
            if(get_post_type($post_id) == 'st_rental'){
                global $wpdb;
                $table = $wpdb->prefix.'st_rental';
                $rs = TravelHelper::deleteDuplicateData($post_id, $table);
                if(!$rs)
                    return false;
                return true;
            }
        }
        function _update_duplicate_data($id, $data){
            if(!TravelHelper::checkTableDuplicate('st_rental')) return;
            global $pagenow;
            if ( $pagenow == 'admin-ajax.php' )
             return $id;
            
            /* don't save during autosave */
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
                return $id;

            /* don't save if viewing a revision */
            if ( $data->post_type == 'revision' || $pagenow == 'revision.php' )
                return $id;

            if(get_post_type($id) == 'st_rental'){
                $num_rows = TravelHelper::checkIssetPost($id, 'st_rental');

                $location_str = get_post_meta($id, 'multi_location', true);
                $location_id = ''; // location_id
                

                $address = get_post_meta($id, 'address', true); // address
                $allow_full_day = get_post_meta($id, 'allow_full_day', true); // address
                $rentals_booking_period = get_post_meta($id, 'rentals_booking_period', true); // rentals_booking_period
                $rental_max_adult = get_post_meta($id, 'rental_max_adult', true); // rental max adult
                $rental_max_children = get_post_meta($id, 'rental_max_children', true); // rental max children

                $sale_price = get_post_meta($id,'price',true); // sale price
                $price = get_post_meta($id,'price',true); // sale price

                $discount = get_post_meta($id,'discount_rate',true);
                $is_sale_schedule = get_post_meta($id,'is_sale_schedule',true);
                $sale_from=get_post_meta($id,'sale_price_from',true);
                $sale_to=get_post_meta($id,'sale_price_to',true);
                if($is_sale_schedule=='on')
                {

                    if($sale_from and $sale_from){

                        $today=date('Y-m-d');
                        $sale_from = date('Y-m-d', strtotime($sale_from));
                        $sale_to = date('Y-m-d', strtotime($sale_to));
                        if (($today >= $sale_from) && ($today <= $sale_to))
                        {

                        }else{

                            $discount=0;
                        }

                    }else{
                        $discount=0;
                    }
                }
                if($discount){
                    $sale_price= $sale_price - ($sale_price/100)*$discount;
                }
                $rate_review = STReview::get_avg_rate($id); // rate review

                if($num_rows == 1){
                    $data = array(
                        'multi_location' => $location_str,
                        'location_id' => $location_id,
                        'address' => $address,
                        'rental_max_adult' => $rental_max_adult,
                        'rental_max_children' => $rental_max_children,
                        'rate_review' => $rate_review,
                        'price' => $price,
                        'sale_price' => $sale_price,
                        'discount_rate'=> $discount,
                        'sale_price_from'=> $sale_from,
                        'sale_price_to'=> $sale_to,
                        'is_sale_schedule'=> $is_sale_schedule,
                        'rentals_booking_period' => $rentals_booking_period,
                        'allow_full_day' => $allow_full_day,
                    );
                    $where = array(
                        'post_id' => $id
                    );
                    TravelHelper::updateDuplicate('st_rental', $data, $where);
                }elseif($num_rows == 0){
                    $data = array(
                        'post_id' => $id,
                        'multi_location' => $location_str,
                        'location_id' => $location_id,
                        'address' => $address,
                        'rental_max_adult' => $rental_max_adult,
                        'rental_max_children' => $rental_max_children,
                        'rate_review' => $rate_review,
                        'price' => $price,
                        'sale_price' => $sale_price,
                        'discount_rate'=> $discount,
                        'sale_price_from'=> $sale_from,
                        'sale_price_to'=> $sale_to,
                        'is_sale_schedule'=> $is_sale_schedule,
                        'rentals_booking_period' => $rentals_booking_period,
                        'allow_full_day' => $allow_full_day,
                    );
                    TravelHelper::insertDuplicate('st_rental', $data);
                }

            }
        }
        /** 
        *@since 1.1.7
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
                update_post_meta($id,'location_id', '');
            }
            
        }

        /**
         *
         *
         * @since 1.1.3
         * */
        function _reg_post_type()
        {
            if(!st_check_service_available($this->post_type))
            {
                return;
            }
            if(!function_exists('st_reg_post_type')) return;
            // Rental ==============================================================
            $labels = array(
                'name'               => __( 'Rental', ST_TEXTDOMAIN ),
                'singular_name'      => __( 'Rental', ST_TEXTDOMAIN ),
                'menu_name'          => __( 'Rental', ST_TEXTDOMAIN ),
                'name_admin_bar'     => __( 'Rental', ST_TEXTDOMAIN ),
                'add_new'            => __( 'Add Rental', ST_TEXTDOMAIN ),
                'add_new_item'       => __( 'Add New Rental', ST_TEXTDOMAIN ),
                'new_item'           => __( 'New Rental', ST_TEXTDOMAIN ),
                'edit_item'          => __( 'Edit Rental', ST_TEXTDOMAIN ),
                'view_item'          => __( 'View Rental', ST_TEXTDOMAIN ),
                'all_items'          => __( 'All Rental', ST_TEXTDOMAIN ),
                'search_items'       => __( 'Search Rental', ST_TEXTDOMAIN ),
                'parent_item_colon'  => __( 'Parent Rental:', ST_TEXTDOMAIN ),
                'not_found'          => __( 'No Rental found.', ST_TEXTDOMAIN ),
                'not_found_in_trash' => __( 'No Rental found in Trash.', ST_TEXTDOMAIN ),
                'insert_into_item'   => __( 'Insert into Rental', ST_TEXTDOMAIN),
                'uploaded_to_this_item'=> __( "Uploaded to this Rental", ST_TEXTDOMAIN),
                'featured_image'=> __( "Feature Image", ST_TEXTDOMAIN),
                'set_featured_image'=> __( "Set featured image", ST_TEXTDOMAIN)
            );

            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => get_option( 'rental_permalink' ,'st_rental' ) ),
                'capability_type'    => 'post',
                'hierarchical'       => false,
                'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
                'menu_icon'          =>'dashicons-admin-home-st'
            );
            st_reg_post_type( 'st_rental', $args );// post type rental

            /**
             *@since 1.1.3
             * Rental room
             **/
            $labels = array(
                'name'               => __( 'Rental Room', ST_TEXTDOMAIN ),
                'singular_name'      => __( 'Room', ST_TEXTDOMAIN ),
                'menu_name'          => __( 'Rental Room', ST_TEXTDOMAIN ),
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
                'not_found_in_trash' => __( 'No rooms found in Trash.', ST_TEXTDOMAIN )
            );

            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => get_option( 'rental_room_permalink' ,'rental_room' ) ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
                'menu_icon'               =>'dashicons-admin-home-st',
                'exclude_from_search'=>true,
            );

            st_reg_post_type( 'rental_room', $args );
        }

        /**
         *
         * @since 1.1.0
         * */
        function get_search_fields_name($fields,$post_type)
        {
            if($post_type==$this->post_type)
            {
                $fields=array(
                    'location'=>array(
                        'value'=>'location',
                        'label'=>__('Location',ST_TEXTDOMAIN)
                    ),
                    'list_location'=>array(
                        'value'=>'list_location',
                        'label'=>__('Location List',ST_TEXTDOMAIN)
                    ),
                    'checkin'=>array(
                        'value'=>'checkin',
                        'label'=>__('Check in',ST_TEXTDOMAIN)
                    ),
                    'checkout'=>array(
                        'value'=>'checkout',
                        'label'=>__('Check out',ST_TEXTDOMAIN)
                    ),
                    'adult'=>array(
                        'value'=>'adult',
                        'label'=>__('Adult',ST_TEXTDOMAIN)
                    ),
                    'children'=>array(
                        'value'=>'children',
                        'label'=>__('Children',ST_TEXTDOMAIN)
                    ),
                    'room_num'=>array(
                        'value'=>'room_num',
                        'label'=>__('Room(s)',ST_TEXTDOMAIN)
                    ),
                    'taxonomy'=>array(
                        'value'=>'taxonomy',
                        'label'=>__('Taxonomy',ST_TEXTDOMAIN)
                    )

                );
            }
            return $fields;
        }

        /**
         *
         * @since 1.0.9
         * */
        function _add_metabox()
        {
            $this->metabox[] = array(
                'id'          => 'st_location',
                'title'       => __( 'Rental Details', ST_TEXTDOMAIN),
                'desc'        => '',
                'pages'       => array( 'st_rental' ),
                'context'     => 'normal',
                'priority'    => 'high',
                'fields'      => array(
                    array(
                        'label'       => __( 'Location', ST_TEXTDOMAIN),
                        'id'          => 'location_tab',
                        'type'        => 'tab'
                    ),
                    array(
                        'label'     => __('Location', ST_TEXTDOMAIN),
                        'id'        => 'multi_location', // id_location
                        'type'      => 'list_item_post_type',
                        'desc'        => __( 'Rental Location', ST_TEXTDOMAIN),
                        'post_type'   =>'location'
                    ),

                    array(
                        'label'       => __( 'Address', ST_TEXTDOMAIN),
                        'id'          => 'address',
                        'type'        => 'address_autocomplete',
                        'desc'        => __( 'Rental Address ', ST_TEXTDOMAIN),
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
                        'label'       => __( 'Rental Information', ST_TEXTDOMAIN),
                        'id'          => 'detail_tab',
                        'type'        => 'tab'
                    ),
                    array(
                        'label'       => __( 'Set as Featured', ST_TEXTDOMAIN),
                        'id'          => 'is_featured',
                        'type'        => 'on-off',
                        'desc'        => __( 'Set this location is featured', ST_TEXTDOMAIN),
                        'std'         =>'off'
                    ),
                    array(
                        'id'          =>'rental_number',
                        'label'       =>__('Numbers',ST_TEXTDOMAIN),
                        'desc'        =>__('Number of rental available for booking',ST_TEXTDOMAIN),
                        'type'        =>'text',
                        'std'         =>'1'
                    ),
                    array(
                        'id'          =>'rental_max_adult',
                        'label'       =>__('Max Adults',ST_TEXTDOMAIN),
                        'desc'       =>__('Max Adults',ST_TEXTDOMAIN),
                        'type'        =>'numeric-slider',
                        'min_max_step'=>'0,100,1',
                        'std'         => 1
                    ),
                    array(
                        'id'          =>'rental_max_children',
                        'label'       =>__('Max Children',ST_TEXTDOMAIN),
                        'desc'       =>__('Max Children',ST_TEXTDOMAIN),
                        'type'        =>'numeric-slider',
                        'min_max_step'=>'0,100,1',
                        'std'         => 1
                    ),
                    array(
                        'label'       => __( 'Custom Layout', ST_TEXTDOMAIN),
                        'id'          => 'custom_layout',
                        'post_type'        => 'st_layouts',
                        'type'        => 'select',
                        'choices'     => st_get_layout('st_rental')
                    ),

                    array(
                        'label'       => __( 'Gallery', ST_TEXTDOMAIN),
                        'id'          => 'gallery',
                        'type'        => 'gallery',
                        'desc'        => __( 'Rental Gallery', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'       => __( 'Video', ST_TEXTDOMAIN),
                        'id'          => 'video',
                        'type'        => 'text',
                        'desc'        => __( 'Youtube or Video url', ST_TEXTDOMAIN),
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
                        'label'       => __( 'Agent Email', ST_TEXTDOMAIN),
                        'id'          => 'agent_email',
                        'type'        => 'text',
                        'desc'        => __( 'Agent Email. This email will receive emails notifying new booking', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'       => __( 'Agent Website', ST_TEXTDOMAIN),
                        'id'          => 'agent_website',
                        'type'        => 'text',
                        'desc'        => __( 'Agent Website', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'       => __( 'Agent Phone', ST_TEXTDOMAIN),
                        'id'          => 'agent_phone',
                        'type'        => 'text',
                        'desc'        => __( 'Agent Phone', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'       => __( 'Agent Fax Number', ST_TEXTDOMAIN),
                        'id'          => 'st_fax',
                        'type'        => 'text',
                        'desc'        => __( 'Agent Fax Number', ST_TEXTDOMAIN),
                    )

                ,array(
                        'label'       => __( 'Rental Price', ST_TEXTDOMAIN),
                        'id'          => 'price_tab',
                        'type'        => 'tab'
                    )
                ,array(
                        'label'       => sprintf( __( 'Price (%s)', ST_TEXTDOMAIN),TravelHelper::get_default_currency('symbol')),
                        'id'          => 'price',
                        'type'        => 'text',
                        'desc'        =>__('Regular Price',ST_TEXTDOMAIN)
                ),
                array(
                    'label' => __('Discount By No. days', ST_TEXTDOMAIN),
                    'type' => 'list-item',
                    'id' => 'discount_by_day',
                    'settings' => array(
                        array(
                            'id' => 'number_day',
                            'label' => __('No. days', ST_TEXTDOMAIN),
                            'type' => 'text',
                            'desc' => __('Enter No. days will be discounted', ST_TEXTDOMAIN)
                        ),
                        array(
                            'id' => 'discount',
                            'label' => __('Discount (percent)', ST_TEXTDOMAIN),
                            'type' => 'text',
                            'desc' => '(%)'
                        )
                    )
                ),
                array(
                    'label' => __('Discount Type ( using for discount by no. days field)', ST_TEXTDOMAIN),
                    'id' => 'discount_type_no_day',
                    'type' => 'select',
                    'choices' => array(
                        array(
                            'label' => __('Percent (%)', ST_TEXTDOMAIN),
                            'value' => 'percent'
                        ),
                        array(
                            'label' => __('Amount', ST_TEXTDOMAIN),
                            'value' => 'fixed'
                        )
                    ),
                    'std' => 'percent'
                ),
                array(
                    'label'    => __('Extra Price', ST_TEXTDOMAIN),
                    'id'       => 'extra_price',
                    'type'     => 'list-item',
                    'settings' => array(
                        array(
                            'id'    => 'extra_name',
                            'type'  => 'text',
                            'std'   => 'extra_',
                            'label' => __('Name of Item', ST_TEXTDOMAIN),
                        ),
                        array(
                            'id'    => 'extra_max_number',
                            'type'  => 'text',
                            'std'   => '',
                            'label' => __('Max of Number', ST_TEXTDOMAIN),
                        ),
                        array(
                            'id' => 'extra_price',
                            'type' => 'text',
                            'std' => '',
                            'label' => __('Price', ST_TEXTDOMAIN),
                            'desc'        => __( 'per 1 Item', ST_TEXTDOMAIN),
                        ),
                    )

                ),

                array(
                    'label' => __('Price Unit', ST_TEXTDOMAIN),
                    'type' => 'select',
                    'id' => 'extra_price_unit',
                    'choices' => array(
                        array(
                            'label' => __('per Day', ST_TEXTDOMAIN),
                            'value' => 'perday'
                            ),
                        array(
                            'label' => __('Fixed', ST_TEXTDOMAIN),
                            'value' => 'fixed'
                            ),
                        )
                ),
                array(
                        'label'       => __( 'Discount Rate', ST_TEXTDOMAIN),
                        'id'          => 'discount_rate',
                        'type'        => 'text',
                        'desc'        =>__('Discount Rate By %',ST_TEXTDOMAIN)
                    )
                ,array(
                        'label'       =>  __( 'Sale Schedule', ST_TEXTDOMAIN),
                        'id'          => 'is_sale_schedule',
                        'type'        => 'on-off',
                        'std'        => 'off',
                    ),
                    array(
                        'label'       =>  __( 'Sale Price Date From', ST_TEXTDOMAIN),
                        'desc'       =>  __( 'Sale Price Date From', ST_TEXTDOMAIN),
                        'id'          => 'sale_price_from',
                        'type'        => 'date-picker',
                        'condition'   =>'is_sale_schedule:is(on)'
                    ),

                    array(
                        'label'       =>  __( 'Sale Price Date To', ST_TEXTDOMAIN),
                        'desc'       =>  __( 'Sale Price Date To', ST_TEXTDOMAIN),
                        'id'          => 'sale_price_to',
                        'type'        => 'date-picker',
                        'condition'   =>'is_sale_schedule:is(on)'
                    ),
                    array(
                        'id'      => 'deposit_payment_status',
                        'label'   => __("Deposit payment options", ST_TEXTDOMAIN),
                        'desc'    => __('You can select <code>Disallow Deposit</code>, <code>Deposit by percent</code>, <code>Deposit by amount</code>'),
                        'type'    => 'select',
                        'choices' => array(
                            array(
                                'value' => '',
                                'label' => __('Disallow Deposit', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => 'percent',
                                'label' => __('Deposit by percent', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => 'amount',
                                'label' => __('Deposit by amount', ST_TEXTDOMAIN)
                            ),
                        )
                    ),
                    array(
                        'label'      => __('Deposit payment amount', ST_TEXTDOMAIN),
                        'desc'       => __('Leave empty for disallow deposit payment', ST_TEXTDOMAIN),
                        'id'         => 'deposit_payment_amount',
                        'type'       => 'text',
                        'condition' => 'deposit_payment_status:not()'
                    ),

                    array(
                        'label' => __('Availability', ST_TEXTDOMAIN),
                        'id' => 'availability_tab',
                        'type' => 'tab'
                    ),
                    array(
                        'label' => __('Rental Calendar', ST_TEXTDOMAIN),
                        'id' => 'st_rental_calendar',
                        'type' => 'st_rental_calendar'
                    ),
                    array(
                        'label' => __('Rental Option',ST_TEXTDOMAIN),
                        'id' => 'rental_options',
                        'type' => 'tab'
                    ),
                    array(
                        'label' => __('Allow booking full day', ST_TEXTDOMAIN),
                        'id' => 'allow_full_day',
                        'type' => 'on-off',
                        'std' => 'on',
                        'desc' => __('Allow rental is booked full day', ST_TEXTDOMAIN)
                    ),
                    array(
                        'label' => __('Minimum days to book before arrival',ST_TEXTDOMAIN),
                        'id' => 'rentals_booking_period',
                        'type'        => 'numeric-slider',
                        'min_max_step'=> '0,30,1',
                        'std' => 0,
                        'desc'        => __( 'The time period allowed booking.', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label' => __('Minimum days to book',ST_TEXTDOMAIN),
                        'id' => 'rentals_booking_min_day',
                        'type'        => 'numeric-slider',
                        'min_max_step'=> '0,180,1',
                        'std' => 0,
                        'desc'        => __( 'The minium times allowed booking.', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label' => __('Rental external booking',ST_TEXTDOMAIN),
                        'id' => 'st_rental_external_booking',
                        'type'        => 'on-off',
                        'std' => "off",
                    ),
                    array(
                        'label' => __('Rental external booking link',ST_TEXTDOMAIN),
                        'id' => 'st_rental_external_booking_link',
                        'type'        => 'text',
                        'std' => "",
                        'condition'   =>'st_rental_external_booking:is(on)',
                        'desc'  =>"<em>".__('Notice: Must be http://...',ST_TEXTDOMAIN)."</em>",
                    ),
					array(
						'label' => __('Cancel Booking', ST_TEXTDOMAIN),
						'id'    => 'st_cancel_booking_tab',
						'type'  => 'tab'
					),
					array(
						'label' => __('Allow Cancel', ST_TEXTDOMAIN),
						'id'    => 'st_allow_cancel',
						'type'  => 'on-off',
						'std'   => 'off'
					),
					array(
						'label' => __('Number of days before the arrival', ST_TEXTDOMAIN),
						'desc' => __('Number of days before the arrival', ST_TEXTDOMAIN),
						'id'    => 'st_cancel_number_days',
						'type'  => 'text',
						'condition'=>'st_allow_cancel:is(on)'
					),
					array(
						'label' => __('Percent of total price', ST_TEXTDOMAIN),
						'desc' => __('Percent of total price for the canceling', ST_TEXTDOMAIN),
						'id'    => 'st_cancel_percent',
						'type'  => 'numeric-slider',
						'min_max_step'=>'0,100,1',
						'condition'=>'st_allow_cancel:is(on)'
					)
                )
            );
            $data_paypment = STPaymentGateways::get_payment_gateways();
            if(!empty($data_paypment) and is_array($data_paypment)){
                $this->metabox[0]['fields'][] = array(
                    'label'       => __( 'Payment', ST_TEXTDOMAIN),
                    'id'          => 'payment_detail_tab',
                    'type'        => 'tab'
                );
                foreach($data_paypment as $k=>$v){
                    $this->metabox[0]['fields'][] = array(
                        'label'       =>$v->get_name() ,
                        'id'          => 'is_meta_payment_gateway_'.$k,
                        'type'        => 'on-off',
                        'desc'        => $v->get_name(),
                        'std'         => 'on'
                    );
                }
            }
            $custom_field = self::get_custom_fields();
            if(!empty($custom_field) and is_array($custom_field)){
                $this->metabox[0]['fields'][]=array(
                    'label'       => __( 'Custom fields', ST_TEXTDOMAIN),
                    'id'          => 'custom_field_tab',
                    'type'        => 'tab'
                );
                foreach($custom_field as $k => $v){
                    $key = str_ireplace('-','_','st_custom_'.sanitize_title($v['title']));
                    $this->metabox[0]['fields'][]=array(
                        'label'       => $v['title'],
                        'id'          => $key,
                        'type'        => $v['type_field'],
                        'desc'        => '<input value=\'[st_custom_meta key="'.$key.'"]\' type=text readonly />',
                        'std'         =>$v['default_field']
                    );
                }
            }

            parent::register_metabox($this->metabox);

        }
        /**
         *
         * @since 1.0.9
         * */
        static function get_custom_fields()
        {
            return st()->get_option('rental_unlimited_custom_field',array());
        }

        function add_col_header($defaults)
        {
            $this->array_splice_assoc($defaults,2,0,array('layout_id'=>__('Layout',ST_TEXTDOMAIN)));

            return $defaults;
        }
        function add_col_content($column_name, $post_ID)
        {
            if ($column_name == 'layout_id') {
                // show content of 'directors_name' column
                $parent=get_post_meta($post_ID,'custom_layout',true);

                if($parent){
                    echo "<a href='".get_edit_post_link($parent)."' target='_blank'>".get_the_title($parent)."</a>";
                }else
                {
                    $layout=st()->get_option('rental_single_layout');
                    if($layout){
                        echo "<a href='".get_edit_post_link($layout)."' target='_blank'>".get_the_title($layout)."</a>";
                    }else{

                    }
                }

            }
        }
        function meta_update_sale_price($post_id)
        {
            if ( wp_is_post_revision( $post_id ) )
                return;
            $post_type=get_post_type($post_id);
            if($post_type=='st_rental')
            {
                $sale_price=get_post_meta($post_id,'price',true);
                $discount=get_post_meta($post_id,'discount',true);
                $is_sale_schedule=get_post_meta($post_id,'is_sale_schedule',true);
                if($is_sale_schedule=='on')
                {
                    $sale_from=get_post_meta($post_id,'sale_price_from',true);
                    $sale_to=get_post_meta($post_id,'sale_price_to',true);
                    if($sale_from and $sale_from){

                        $today=date('Y-m-d');
                        $sale_from = date('Y-m-d', strtotime($sale_from));
                        $sale_to = date('Y-m-d', strtotime($sale_to));
                        if (($today >= $sale_from) && ($today <= $sale_to))
                        {

                        }else{

                            $discount=0;
                        }

                    }else{
                        $discount=0;
                    }
                }
                if($discount){
                    $sale_price= $sale_price - ($sale_price/100)*$discount;
                }
                update_post_meta($post_id,'sale_price',$sale_price);
            }
        }
        function _resend_mail()
        {
            $order_item=isset($_GET['order_item_id'])?$_GET['order_item_id']:false;

            $test=isset($_GET['test'])?$_GET['test']:false;
            if($order_item){

                $order=$order_item;

                if($test){
                    global $order_id;
                    $order_id = $order_item;
                    $email_to_admin = st()->get_option('email_for_admin', '');
                    $email = st()->load_template('email/header');
                    $email .= do_shortcode($email_to_admin);
                    $email .= st()->load_template('email/footer');
                    echo($email);
                    die;
                }

                if($order){
                    $check=STCart::send_mail_after_booking($order);
                }
            }

            wp_safe_redirect(self::$booking_page.'&send_mail=success');
        }
        static  function  st_room_select_ajax()
        {
            extract( wp_parse_args($_GET,array(
                'post_type'=>'',
                'q'=>''
            )));


            query_posts(array('post_type'=>$post_type,'posts_per_page'=>10,'s'=>$q));

            $r=array(
                'items'=>array(),
                't'=>array('post_type'=>$post_type,'posts_per_page'=>10,'s'=>$q)
            );
            while(have_posts())
            {
                the_post();
                $r['items'][]=array(
                    'id'=>get_the_ID(),
                    'name'=>get_the_title(),
                    'description'=>''
                );
            }

            wp_reset_query();

            echo json_encode($r);
            die;

        }
        static function  add_edit_scripts()
        {
            wp_enqueue_script('select2');
            wp_enqueue_script('st-edit-booking',get_template_directory_uri().'/js/admin/edit-booking.js',array('jquery'),null,true);
            wp_enqueue_script('admin-rental-booking',get_template_directory_uri().'/js/admin/rental-booking.js',array('jquery'),null,true);
            wp_enqueue_script('st-jquery-ui-datepicker',get_template_directory_uri().'/js/jquery-ui.js');
            wp_enqueue_style('jjquery-ui.theme.min.css',get_template_directory_uri().'/css/admin/jquery-ui.min.css');
        }
        static function is_booking_page()
        {
            if(is_admin()
                and isset($_GET['post_type'])
                and $_GET['post_type']=='st_rental'
                and isset($_GET['page'])
                and $_GET['page']='st_rental_booking'
            ) return true;
            return false;
        }

        function new_menu_page()
        {
            //Add booking page
            add_submenu_page('edit.php?post_type=st_rental',__('Rental Booking',ST_TEXTDOMAIN), __('Rental Booking',ST_TEXTDOMAIN), 'manage_options', 'st_rental_booking', array($this,'__rental_booking_page'));
        }

        function  __rental_booking_page(){

            $section=isset($_GET['section'])?$_GET['section']:false;

            if($section){
                switch($section)
                {
                    case "edit_order_item":
                        $this->edit_order_item();
                        break;
                    /*case 'add_booking':
                        $this->add_booking();
                        break;*/
                }
            }else{

                $action=isset($_POST['st_action'])?$_POST['st_action']:false;
                switch($action){
                    case "delete":
                        $this->_delete_items();
                        break;
                }
                echo balanceTags($this->load_view('rental/booking_index',false));
            }

        }
        function add_booking()
        {

            echo balanceTags($this->load_view('rental/booking_edit',false,array('page_title'=>__('Add new Rental Booking',ST_TEXTDOMAIN))));
        }
        function _delete_items(){

            if ( empty( $_POST ) or  !check_admin_referer( 'shb_action', 'shb_field' ) ) {
                //// process form data, e.g. update fields
                return;
            }
            $ids=isset($_POST['post'])?$_POST['post']:array();
            if(!empty($ids))
            {
                foreach($ids as $id)
                    wp_delete_post($id,true);

            }

            STAdmin::set_message(__("Delete item(s) success",ST_TEXTDOMAIN),'updated');

        }

        function edit_order_item()
        {
            $item_id=isset($_GET['order_item_id'])?$_GET['order_item_id']:false;
            if(!$item_id or get_post_type($item_id)!='st_order')
            {
                //wp_safe_redirect(self::$booking_page); die;
                return false;
            }


            if(isset($_POST['submit']) and $_POST['submit']) $this->_save_booking($item_id);

            echo balanceTags($this->load_view('rental/booking_edit'));
        }
        function _add_booking()
        {
            if(!check_admin_referer( 'shb_action', 'shb_field' )) die;

            $data = $this->_check_validate();
            if(is_array($data) && count($data)){
                extract($data);

                $order=array(
                    'post_title'=>__('Order',ST_TEXTDOMAIN).' - '.date(get_option( 'date_format' )).' @ '.date(get_option('time_format')),
                    'post_type'=>'st_order',
                    'post_status'=>'publish'
                );

                $order_id = wp_insert_post($order);

                if($order_id){
                    $check_out_field = STCart::get_checkout_fields();

                    if(!empty($check_out_field))
                    {
                        foreach($check_out_field as $field_name=>$field_desc)
                        {
                            update_post_meta($order_id,$field_name,STInput::post($field_name));
                        }
                    }

                    $id_user = get_current_user_id();
                    update_post_meta($order_id, 'id_user', $id_user);

                    update_post_meta($order_id,'payment_method','st_submit_form');

                    $item_price = STPrice::getRentalPriceOnlyCustomPrice($item_id, strtotime($check_in), strtotime($check_out));
                    $numberday = TravelHelper::dateDiff($check_in, $check_out);
                    $origin_price = $item_price;
                    
                    //Extra price
                    $extras = STInput::request('extra_price', array());
                    $extra_price = STPrice::getExtraPrice($item_id, $extras, $room_num_search, $numberday);

                    $sale_price = STPrice::getSalePrice($item_id, strtotime($check_in), strtotime($check_out));
                    $price_with_tax = STPrice::getPriceWithTax($sale_price + $extra_price);

                    $deposit_money['data'] = array();
                    $deposit_money = STPrice::getDepositData($item_id, $deposit_money);
                    $deposit_price = STPrice::getDepositPrice($deposit_money['data']['deposit_money'], $price_with_tax, 0);
                    if(isset($deposit_money['data']['deposit_money'])){
                        $total_price = $deposit_price;
                    }else{
                        $total_price = $price_with_tax;
                    }

                    $data_prices = array(
                        'origin_price' => $origin_price,
                        'sale_price' => $sale_price,
                        'coupon_price' => 0,
                        'price_with_tax' => $price_with_tax,
                        'total_price' => $total_price,
                        'deposit_price' => $deposit_price
                    );

                    $item_data = array(
                        'item_number' => 1,
                        'item_id'    => $item_id,
                        'item_price' => $item_price,
                        'check_in'    => $check_in,
                        'check_out'   => $check_out,
                        'adult_number' => $adult_number,
                        'child_number' => $child_number,
                        'total_price' => $total_price,
                        'data_prices'  => $data_prices,
                        'deposit_money' => $deposit_money['data']['deposit_money'],
                        'booking_by' => 'admin',
                        'st_tax' => STPrice::getTax(),
                        'st_tax_percent' => STPrice::getTax(),
                        'status' => $_POST['status'],
                        'currency'        => TravelHelper::get_current_currency('symbol'),
                        'currency_rate' => TravelHelper::get_current_currency('rate'),
                        'extras' => $extras,
                        'extra_price' => $extra_price,
                        'commission' => TravelHelper::get_commission()
                    );
                    foreach ($item_data as $val => $value) {
                        update_post_meta($order_id, $val, $value);
                    }

                    if(TravelHelper::checkTableDuplicate('st_rental')){
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
                            'st_booking_post_type' => 'st_rental',
                            'st_booking_id' => $item_id,
                            'adult_number' => $adult_number,
                            'child_number' => $child_number,
                            'check_in_timestamp' => strtotime($check_in),
                            'check_out_timestamp' => strtotime($check_out),
                            'room_num_search' => $room_num_search,
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

                    do_action('st_booking_success',$order_id);

                    //Check email
                    $user_name = STInput::post('st_email');
                    $user_id = username_exists( $user_name );
                    if( !$user_id and email_exists($user_name) == false ){
                        $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
                        $userdata = array(
                            'user_login'  =>  $user_name,
                            'user_pass'   =>  $random_password,
                            'user_email'  =>$user_name,
                            'first_name'  =>STInput::post('st_first_name'), // When creating an user, `user_pass` is expected.
                            'last_name'  =>STInput::post('st_last_name') // When creating an user, `user_pass` is expected.
                        );
                        $user_id = wp_insert_user( $userdata );

                        wp_new_user_notification($user_id);
                    }
                    
                    //STCart::send_mail_after_booking($order_id, true);
                    STCart::send_email_confirm($order_id);

                    wp_safe_redirect(self::$booking_page);
                }

            }

        }

        function _save_booking($order_id){
            if(!check_admin_referer( 'shb_action', 'shb_field' )) die;
            $data = $this->_check_validate();
            if(is_array($data)){

                $check_out_field = STCart::get_checkout_fields();

                if(!empty($check_out_field)){
                    foreach($check_out_field as $field_name => $field_desc){
                        update_post_meta($order_id,$field_name,STInput::post($field_name));
                    }
                }

                $item_data = array(
                    'status' => $_POST['status']

                );
                foreach ($item_data as $val => $value) {
                    update_post_meta($order_id, $val, $value);
                }

                if(TravelHelper::checkTableDuplicate('st_rental')){
                    global $wpdb;

                    $table = $wpdb->prefix.'st_order_item_meta';
                    $where = array(
                        'order_item_id' => $order_id,
                    );
                    $data = array(
                        'status' => $_POST['status']
                    );
                    $wpdb->update($table, $data, $where);
                }

                STCart::send_mail_after_booking($order_id, true);

                wp_safe_redirect(self::$booking_page);
            }
            
        }
        public function _check_validate(){
            $data= array();
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

            $st_phone = STInput::request('st_phone', '');
            if(empty($st_phone)){
                STAdmin::set_message(__('The phone field is not empty.', ST_TEXTDOMAIN), 'danger');
                return false;
            }
            if(STInput::request('section', '') != 'edit_order_item'){
                $item_id = intval(STInput::request('item_id', ''));

                if($item_id <= 0 || get_post_type($item_id) != 'st_rental'){
                    STAdmin::set_message(__('The rental field is not empty.', ST_TEXTDOMAIN), 'danger');
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

                $adult = intval(get_post_meta($item_id, 'rental_max_adult', true));
                $children = intval(get_post_meta($item_id, 'rental_max_children', true));

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
                
                $booking_period = get_post_meta($item_id,'rentals_booking_period', true);
                if(empty($booking_period) || $booking_period <=0) $booking_period = 0;

                if($compare < 0){
                    STAdmin::set_message( __( 'You can not set check-in date in the past' , ST_TEXTDOMAIN ) , 'danger' );
                    return false;
                }
                if ($period < $booking_period) {
                    STAdmin::set_message(sprintf(__('This rental allow minimum booking is %d day(s)', ST_TEXTDOMAIN), $booking_period), 'danger');
                    return false;
                }
                
                $checkin_ymd = date('Y-m-d', strtotime($check_in));
                $checkout_ymd = date('Y-m-d', strtotime($check_out));
                if(!RentalHelper::check_day_cant_order($item_id, $checkin_ymd, $checkout_ymd, 1)){
                    STAdmin::set_message(sprintf(__('This rental is not available from %s to %s.', ST_TEXTDOMAIN), $checkin_ymd, $checkout_ymd), 'danger');
                    $pass_validate = FALSE;
                    return false;
                }

                if(!RentalHelper::_check_room_available($item_id, $checkin_ymd, $checkout_ymd, 1)){
                    STAdmin::set_message(__('This rental is not available.', ST_TEXTDOMAIN), 'danger');
                    $pass_validate = FALSE;
                    return false;
                }
                $data = array(
                    'order_item_id' => $order_item_id,
                    'item_id' => $item_id,
                    'type' => 'normal_booking',
                    'check_in' => $check_in,
                    'check_out' => $check_out,
                    'st_booking_post_type' => 'st_rental',
                    'st_booking_id' => $item_id,
                    'adult_number' => $adult_number,
                    'child_number' => $child_number,
                    'room_num_search' => 1,
                    'check_in_timestamp' => strtotime($check_in),
                    'check_out_timestamp' => strtotime($check_out),
                    'status' => $_POST['status']
                );
            }
            
            return $data;
        }
        function is_able_edit()
        {
            $item_id=isset($_GET['order_item_id'])?$_GET['order_item_id']:false;
            if(!$item_id or get_post_type($item_id)!='st_order')
            {
                wp_safe_redirect(self::$booking_page); die;
            }
            return true;
        }
        static function getRentalInfo(){
            $rental_id = intval(STInput::request('rental_id', ''));
            $data = array(
                'price' => '',
                'extras' => 'None',
                'adult_html' => '',
                'child_html' => ''
            );
            if($rental_id <= 0 || get_post_type($rental_id) != 'st_rental'){
                echo json_encode($data);
            }else{
                $adult_number = intval(get_post_meta($rental_id, 'rental_max_adult', true));
                if($adult_number <= 0) $adult_number = 1;
                $adult_html = '<select name="adult_number" class="form-control" style="width: 100px;">';
                for($i = 1; $i <= $adult_number; $i ++){
                    $adult_html .= '<option value="'.$i.'">'.$i.'</option>';
                }
                $adult_html .= '</select>';

                $child_number = intval(get_post_meta($rental_id, 'rental_max_children', true));
                if($child_number <= 0) $child_number = 0;
                $child_html = '<select name="child_number" class="form-control" style="width: 100px;">';
                for($i = 0; $i <= $child_number; $i ++){
                    $child_html .= '<option value="'.$i.'">'.$i.'</option>';
                }
                $child_html .= '</select>';

                $html = '';
                $price = floatval(get_post_meta($rental_id, 'price', true));
                $extras = get_post_meta($rental_id, 'extra_price', true);
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
                echo json_encode($data);
            }
            die();
        }

    }
    new STAdminRental();
}