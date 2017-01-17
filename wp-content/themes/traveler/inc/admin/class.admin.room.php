<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STAdminRoom
 *
 * Created by ShineTheme
 *
 */
if(!class_exists('STAdminRoom'))
{

    class STAdminRoom extends STAdmin
    {
        static $_inst;
        static $_table_version= "1.2.8";
        static $booking_page;
        protected $post_type='hotel_room';
        /**
         *
         *
         * @update 1.1.3
         * */
        function __construct()
        {
            if (!st_check_service_available($this->post_type)) return;

            add_filter('st_hotel_room_layout', array($this, 'custom_hotel_room_layout'));

            add_action('init',array($this,'init_metabox'));

            self::$booking_page = admin_url('edit.php?post_type=hotel_room&page=st_hotel_room_booking');

            //alter where for search room
            add_filter( 'posts_where' , array(__CLASS__,'_alter_search_query') );


            //Hotel Hook
            /*
             * todo Re-cal hotel min price
             * */
            add_action( 'update_post_meta', array($this,'hotel_update_min_price') ,10,4);
            add_action( 'updated_post_meta', array($this,'meta_updated_update_min_price') ,10,4);
            add_action('added_post_meta',array($this,'hotel_update_min_price') ,10,4);
            add_action('save_post', array($this,'_update_avg_price'),50);
            add_action('save_post', array($this,'_update_min_price'),50);
            add_action('save_post', array($this, '_update_duplicate_data'), 51, 2);

            add_action('save_post', array($this, '_update_list_location'), 51, 2);


            /**
             *   since 1.2.6
             *   auto create & update table st_hotel
             **/
            add_action( 'after_setup_theme', array (__CLASS__, '_check_table_hotel_room') );


            add_action('admin_menu', array($this, 'add_menu_page'));
            //Check booking edit and redirect
            if (self::is_booking_page()) {
                add_action('admin_enqueue_scripts', array(__CLASS__, 'add_edit_scripts'));
                add_action('admin_init',array($this,'_do_save_booking'));
            }
            parent::__construct();

            /**
            *@since 1.2.8
            **/
            add_action('restrict_manage_posts', array( $this, 'restrict_manage_posts_hotel_room'));
            add_action('parse_query', array( $this, 'parse_query_hotel_room'));

        }

        public function restrict_manage_posts_hotel_room( $post_type ){
            if( $post_type == 'hotel_room'):
                global $wp_query;
            ?>
            <div class="alignleft actions">
                <input  type="text" class="filter-by-hotel" name="filter_st_hotel" value="<?php echo STInput::request('filter_st_hotel', ''); ?>" placeholder="Filter by hotel name">
            </div>
            <?php
            endif;
        }

        public function parse_query_hotel_room( $query ){
            global $pagenow;
            if (isset($_GET['post_type'])) {
                $type = $_GET['post_type'];
                if ( 'hotel_room' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['filter_st_hotel']) && $_GET['filter_st_hotel'] != '') {
                    add_filter('posts_where', array($this, 'posts_where_hotel_room'));
                    add_filter('posts_join', array($this, 'posts_join_hotel_room'));
                }
            }
            
        }
        public function posts_where_hotel_room( $where ){
            global $wpdb;
            $hotel_name = $_GET['filter_st_hotel'];
            $where .= " AND mt2.meta_value in (select ID from {$wpdb->prefix}posts where post_title like '%{$hotel_name}%' and post_type = 'st_hotel' and post_status in ('publish', 'private') ) ";

            return $where;
        }
        public function posts_join_hotel_room( $join ){
            global $wpdb;
            $join .= " inner join {$wpdb->prefix}postmeta as mt2 on mt2.post_id = {$wpdb->prefix}posts.ID and mt2.meta_key='room_parent' ";
            return $join;
        }

        static function check_ver_working(){
            $dbhelper = new DatabaseHelper(self::$_table_version);
            return $dbhelper->check_ver_working( 'st_hotel_room_table_version' );
        }
        static function _check_table_hotel_room(){
            $dbhelper = new DatabaseHelper(self::$_table_version);
            $dbhelper->setTableName('hotel_room');
            $column = array(
                'post_id' => array(
                    'type' => 'INT',
                    'length' => 11,
                ),
                'room_parent' => array(
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
                'price' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'number_room' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'discount_rate' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
            );

            $column = apply_filters( 'st_change_column_st_hotel_room', $column );

            $dbhelper->setDefaultColums( $column );
            $dbhelper->check_meta_table_is_working( 'st_hotel_room_table_version' );

            return array_keys( $column );
        }
        /**
         *@since 1.2.6
         **/
        static function is_booking_page()
        {
            if (is_admin()
                and isset($_GET['post_type'])
                and $_GET['post_type'] == 'hotel_room'
                and isset($_GET['page'])
                and $_GET['page'] = 'st_hotel_room_booking'
            ) return TRUE;

            return FALSE;
        }
        /**
         *@since 1.2.6
         **/
        static function  add_edit_scripts()
        {
            wp_enqueue_script('select2');
            wp_enqueue_script('st-edit-booking', get_template_directory_uri() . '/js/admin/edit-booking.js', array('jquery'), NULL, TRUE);
            wp_enqueue_script('st-hotel-edit-booking', get_template_directory_uri() . '/js/admin/hotel-booking.js', array('jquery'), NULL, TRUE);
            wp_enqueue_script('st-jquery-ui-datepicker',get_template_directory_uri().'/js/jquery-ui.js');
            wp_enqueue_style('jjquery-ui.theme.min.css', get_template_directory_uri() . '/css/admin/jquery-ui.min.css');
        }
        /**
         *@since 1.2.6
         **/
        function add_menu_page()
        {

            //Add booking page
            add_submenu_page('edit.php?post_type=hotel_room',__('Room Bookings',ST_TEXTDOMAIN), __('Room Bookings',ST_TEXTDOMAIN), 'manage_options', 'st_hotel_room_booking', array($this,'__hotel_room_booking_page'));
        }
        /**
         *@since 1.2.6
         **/
        function  __hotel_room_booking_page()
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
                echo balanceTags($this->load_view('hotel_room/booking_index', FALSE));
            }

        }
        /**
         *@since 1.2.6
         **/
        function add_booking()
        {
            echo balanceTags($this->load_view('hotel_room/booking_edit', FALSE, array('page_title' => __('Add new Hotel Booking', ST_TEXTDOMAIN))));
        }
        /**
         *@since 1.2.6
         **/
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
        /**
         *@since 1.2.6
         **/
        function edit_order_item()
        {
            $item_id = isset($_GET['order_item_id']) ? $_GET['order_item_id'] : FALSE;
            if (!$item_id or get_post_type($item_id) != 'st_order') {
                //wp_safe_redirect(self::$booking_page); die;
                return FALSE;
            }
            echo balanceTags($this->load_view('hotel_room/booking_edit'));
        }
        /**
         *@since 1.2.6
         **/
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
         *@since 1.2.6
         **/
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
                        'sale_price' => $sale_price,
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
        /**
         *@since 1.2.6
         **/
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

                if(TravelHelper::checkTableDuplicate('hotel_room')){
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

                do_action('update_booking_hotel_room',$order_id);

                STCart::send_mail_after_booking($order_id, true);
                wp_safe_redirect(self::$booking_page);
            }
        }
        /**
         *@since 1.2.6
         **/
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

                $room_id = intval(STInput::request('room_id', ''));
                $item_id = $room_id;
                if($room_id <= 0 || get_post_type($room_id) != 'hotel_room'){
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
        /**
         *@since 1.2.6
         **/
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












        /**
        *@since 1.2.6
        **/
        public function custom_hotel_room_layout($old_layout_id=false){

            if(is_singular('hotel_room')){

                $meta=get_post_meta(get_the_ID(),'st_custom_layout',true);
                if($meta)
                {
                    return $meta;
                }
            }
            return $old_layout_id;
        }

        /**
         *@since 1.2.6
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
         *
         *
         * @since 1.1.1
         * */
        function init_metabox()
        {

            //Room
            $this->metabox[] = array(
                'id'          => 'room_metabox',
                'title'       => __( 'Room Setting', ST_TEXTDOMAIN),
                'desc'        => '',
                'pages'       => array( 'hotel_room' ),
                'context'     => 'normal',
                'priority'    => 'high',
                'fields'      => array(
                    array(
                        'label' => __('Location', ST_TEXTDOMAIN),
                        'id'    => 'location_tab',
                        'type'  => 'tab'
                    ),

                    array(
                        'label'     => __('Location', ST_TEXTDOMAIN),
                        'id'        => 'multi_location', // id_location
                        'type'      => 'list_item_post_type',
                        'desc'        => __( 'Room Location', ST_TEXTDOMAIN),
                        'post_type'   =>'location'
                    ),
                    array(
                        'label' => __('Address', ST_TEXTDOMAIN),
                        'id'    => 'address',
                        'type'  => 'address_autocomplete',
                        'desc'  => __('Room Address', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'       => __( 'General', ST_TEXTDOMAIN),
                        'id'          => 'room_reneral_tab',
                        'type'        => 'tab'
                    ),

                    array(
                        'label'       => __( 'Hotel', ST_TEXTDOMAIN),
                        'id'          => 'room_parent',
                        'type'        => 'post_select_ajax',
                        'desc'        => __( 'Choose the hotel that the room belongs to', ST_TEXTDOMAIN),
                        'post_type'   =>'st_hotel',
                        'placeholder' =>__('Search for a Hotel',ST_TEXTDOMAIN)
                    ),

                    array(
                        'label'       => __( 'Number of Rooms', ST_TEXTDOMAIN),
                        'id'          => 'number_room',
                        'type'        => 'text',
                        'desc'        => __( 'Number of rooms available for booking', ST_TEXTDOMAIN),
                        'std'         =>1
                    ),

                    /**
                    ** @since 1.1.3
                    **/

                    array(
                        'label' => __('Gallery',ST_TEXTDOMAIN),
                        'id' => 'gallery',
                        'type' => 'gallery'
                    ),
                    array(
                        'label'     => __('Hotel Room Layout', ST_TEXTDOMAIN),
                        'id'        => 'st_custom_layout',
                        'post_type' => 'st_layouts',
                        'desc'      => __('Hotel Room Layout', ST_TEXTDOMAIN),
                        'type'      => 'select',
                        'choices'   => st_get_layout('hotel_room')
                    ),
                    array(
                        'label'       => __( 'Room Price', ST_TEXTDOMAIN),
                        'id'          => 'room_price_tab',
                        'type'        => 'tab'
                    ),
                    array(
                        'label' => __('Allow booking full day', ST_TEXTDOMAIN),
                        'id' => 'allow_full_day',
                        'type' => 'on-off',
                        'std' => 'on',
                        'desc' => __('Allow room is booked full day', ST_TEXTDOMAIN)
                    ),
                    array(
                        'label'       => sprintf( __( 'Price (%s)', ST_TEXTDOMAIN),TravelHelper::get_default_currency('symbol')),
                        'id'          => 'price',
                        'type'        => 'text',
                        'desc'        => __( 'Per night', ST_TEXTDOMAIN),
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
                                'label' => __('Discount', ST_TEXTDOMAIN),
                                'type' => 'text',
                                'desc' => __('Price', ST_TEXTDOMAIN),
                            ),
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
                        'label' => __('Extra Price Unit', ST_TEXTDOMAIN),
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
                            ),
                        'desc' => __('Extra price unit', ST_TEXTDOMAIN)
                    ),
                    array(
                        'label'       =>  __( 'Discount Rate', ST_TEXTDOMAIN),
                        'id'          => 'discount_rate',
                        'type'        => 'text',
                        'desc'        => __( 'Discount by %', ST_TEXTDOMAIN),
                    ),

                    /*array(
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
                    ),*/
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
                        'label'     => __('Deposit payment amount', ST_TEXTDOMAIN),
                        'desc'      => __('Leave empty for disallow deposit payment', ST_TEXTDOMAIN),
                        'id'        => 'deposit_payment_amount',
                        'type'      => 'text',
                        'condition' => 'deposit_payment_status:not()'
                    ),
                    array(
                        'label'       => __( 'Room Facility', ST_TEXTDOMAIN),
                        'id'          => 'room_detail_tab',
                        'type'        => 'tab'
                    ),

                    array(
                        'label'       => __( 'No. adults', ST_TEXTDOMAIN),
                        'id'          => 'adult_number',
                        'type'        => 'text',
                        'desc'        => __( 'Number of Adults in room', ST_TEXTDOMAIN),
                        'std'         =>1
                    ),
                    array(
                        'label'       => __( 'No. children', ST_TEXTDOMAIN),
                        'id'          => 'children_number',
                        'type'        => 'text',
                        'desc'        => __( 'Number of Children in room', ST_TEXTDOMAIN),
                        'std'         =>0
                    ),
                    array(
                        'label'       => __( 'No. beds', ST_TEXTDOMAIN),
                        'id'          => 'bed_number',
                        'type'        => 'text',
                        'desc'        => __( 'Number of Beds in room', ST_TEXTDOMAIN),
                        'std'         =>0
                    ),
                    array(
                        'label'       => __( 'Room footage (square feet)', ST_TEXTDOMAIN),
                        'desc'       => __( 'Room footage (square feet)', ST_TEXTDOMAIN),
                        'id'          => 'room_footage',
                        'type'        => 'text',
                    ),
                    array(
                        'label' => __('Room external booking',ST_TEXTDOMAIN),
                        'id' => 'st_room_external_booking',
                        'type'        => 'on-off',
                        'std' => "off",
                    ),
                    array(
                        'label' => __('Room external booking',ST_TEXTDOMAIN),
                        'id' => 'st_room_external_booking_link',
                        'type'        => 'text',
                        'std' => "",
                        'condition'   =>'st_room_external_booking:is(on)',
                        'desc'=>"<em>".__('Notice: Must be http://...',ST_TEXTDOMAIN)."</em>",
                    ),
                    array(
                        'label' => __('Other facility', ST_TEXTDOMAIN),
                        'id' => 'other_facility',
                        'type' => 'tab'
                    ),
                    array(
                        'label'    => __('Add a facility', ST_TEXTDOMAIN),
                        'id'       => 'add_new_facility',
                        'type'     => 'list-item',
                        'settings' => array(
                            array(
                                'id'    => 'facility_value',
                                'type'  => 'text',
                                'std'   => '',
                                'label' => __('Value', ST_TEXTDOMAIN)
                            ),
                            array(
                                'id' => 'facility_icon',
                                'type' => 'text',
                                'std' => '',
                                'label' => __('Icon', ST_TEXTDOMAIN),
                                'desc' => __('Support: fonticon <code>(eg: fa-facebook)</code>', ST_TEXTDOMAIN)
                            ),
                        )

                    ),
                    array(
                        'label' => __('Description', ST_TEXTDOMAIN),
                        'id' => 'room_description',
                        'type' => 'textarea',
                        'std' => ''
                    ),
                    array(
                        'label' => __('Availability', ST_TEXTDOMAIN),
                        'id' => 'availability_tab',
                        'type' => 'tab'
                    ),
                    array(
                        'label'=>__("Default State",ST_TEXTDOMAIN),
                        'id'=>'default_state',
                        'type'=>'select',
                        'choices'=>array(
                            array(
                                'value'=>"available",
                                'label'=>__("Available",ST_TEXTDOMAIN)
                            ),
                            array(
                                'value'=>"not_available",
                                'label'=>__("Not Available",ST_TEXTDOMAIN)
                            ),
                        )
                    ),
                    array(
                        'label' => __('Calendar', ST_TEXTDOMAIN),
                        'id' => 'st_hotel_calendar',
                        'type' => 'st_hotel_calendar'
                    ),array(
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

            parent::register_metabox($this->metabox);
        }


        /**
         *
         *
         * @since 1.0.9
         *
         */
        static function _update_avg_price($post_id=false)
        {
            if(empty($post_id))
            {
                $post_id=get_the_ID();
            }
            $post_type=get_post_type($post_id);
            if($post_type=='hotel_room')
            {
                $hotel_id = get_post_meta($post_id,'room_parent',true);
                if(!empty($hotel_id)) {
                    $is_auto_caculate = get_post_meta($hotel_id,'is_auto_caculate',true);
                    if($is_auto_caculate != 'off' ){
                        $query = array(
                            'post_type'      => 'hotel_room',
                            'posts_per_page' => 999,
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
        }
        // since 1.2.4
        static function _update_min_price($post_id=false)
        {
            if(empty($post_id))
            {
                $post_id=get_the_ID();
            }
            $post_type=get_post_type($post_id);
            if($post_type=='hotel_room')
            {
                $hotel_id = get_post_meta($post_id,'room_parent',true);
                if(!empty($hotel_id)) {
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
                    if(!empty($prices)) {
                        $min_price = min($prices);
                        update_post_meta($hotel_id,'min_price',$min_price);
                    }
                }
            }
        }
        /**from 1.1.9*/
        function _update_duplicate_data($id , $data){
            // for room
            if(!TravelHelper::checkTableDuplicate('hotel_room')) return;
            if(get_post_type($id) == 'hotel_room'){
                $num_rows = TravelHelper::checkIssetPost($id, 'hotel_room');
                $allow_full_day = get_post_meta($id, 'allow_full_day', true); // address
                $data = array(
                    'room_parent' => get_post_meta($id , 'id_location' , true),
                    'multi_location' => get_post_meta($id , 'multi_location' , true),
                    'id_location' => get_post_meta($id , 'id_location' , true),
                    'address' => get_post_meta($id , 'address' , true),
                    'allow_full_day' => $allow_full_day,
                    'price' => get_post_meta($id , 'price' , true),
                    'number_room' => get_post_meta($id , 'number_room' , true),
                    'discount_rate' => get_post_meta($id , 'discount_rate' , true),
                );
                if($num_rows == 1){
                    $where = array(
                        'post_id' => $id
                    );
                    TravelHelper::updateDuplicate('hotel_room', $data, $where);
                }elseif($num_rows == 0){
                    $data['post_id'] = $id;
                    TravelHelper::insertDuplicate('hotel_room', $data);
                }
            }

            // for hotel
            if(!TravelHelper::checkTableDuplicate('st_hotel')) return;
            if(get_post_type($id) == 'hotel_room'){
                $hotel_id = get_post_meta($id,'room_parent',true);

                $price_avg = (get_post_meta($hotel_id, 'price_avg' , true));
                $min_price = (get_post_meta($hotel_id, 'min_price' , true));
                if (!$price_avg) {return ; }


                $data = array(
                    'multi_location' => get_post_meta($hotel_id , 'multi_location' , true),
                    'id_location' => get_post_meta($hotel_id , 'id_location' , true),
                    'address' => get_post_meta($hotel_id , 'address' , true),
                    'rate_review' => get_post_meta($hotel_id , 'rate_review' , true),
                    'hotel_star' => get_post_meta($hotel_id , 'hotel_star' , true),
                    'price_avg' => $price_avg,
                    'min_price' => $min_price,
                    'hotel_booking_period' => get_post_meta($hotel_id , 'hotel_booking_period' , true),
                    'map_lat' => get_post_meta($hotel_id , 'map_lat' , true),
                    'map_lng' => get_post_meta($hotel_id , 'map_lng' , true),
                );
                $where = array(
                    'post_id' => $hotel_id
                );
                TravelHelper::updateDuplicate('st_hotel', $data, $where);
            }

        }
        static function _alter_search_query($where)
        {
            global $wp_query;

            if(!is_admin()) return $where;

            if($wp_query->get('post_type') !='hotel_room' ) return $where;

            global $wpdb;

            if($wp_query->get('s')){
                $_GET['s'] = isset($_GET['s'])? sanitize_title_for_query($_GET['s']): '';
                $add_where=" OR $wpdb->posts.ID IN (SELECT post_id FROM
                     $wpdb->postmeta
                    WHERE $wpdb->postmeta.meta_key ='room_parent'
                    AND $wpdb->postmeta.meta_value IN (SELECT $wpdb->posts.ID
                        FROM $wpdb->posts WHERE  $wpdb->posts.post_title LIKE '%{$_GET['s']}%'
                    )

             )  ";

                $where.=$add_where;


            }

            return $where;
        }

        function hotel_update_min_price($meta_id, $object_id, $meta_key, $meta_value)
        {

            $post_type=get_post_type($object_id);
            if ( wp_is_post_revision( $object_id ) )
                return;
            if($post_type=='hotel_room')
            {
                //Update old room and new room
                if( $meta_key=='room_parent')
                {

                    $old=get_post_meta($object_id,$meta_key,true);


                    if($old!=$meta_value)
                    {
                        $this->_do_update_hotel_min_price($old,false,$object_id);
                        $this->_do_update_hotel_min_price($meta_value);
                    }else{

                        $this->_do_update_hotel_min_price($meta_value);
                    }
                }


            }

        }
        function meta_updated_update_min_price($meta_id, $object_id, $meta_key, $meta_value)
        {
            if($meta_key=='price')
            {
                $hotel_id=get_post_meta($object_id,'room_parent',true);
                $this->_do_update_hotel_min_price($hotel_id);

            }
        }

        function _do_update_hotel_min_price($hotel_id,$current_meta_price=false,$room_id=false){
            if(!$hotel_id) return;
            $query=array(
                'post_type' =>'hotel_room',
                'posts_per_page'=>100,
                'meta_key'=>'room_parent',
                'meta_value'=>$hotel_id
            );

            if($room_id){
                $query['posts_not_in']=array($room_id);
            }


            $q=new WP_Query($query);

            $min_price=0;
            $i=1;
            while($q->have_posts()){
                $q->the_post();
                $price=get_post_meta(get_the_ID(),'price',true);
                if($i==1){
                    $min_price=$price;
                }else{
                    if($price<$min_price){
                        $min_price=$price;
                    }
                }


                $i++;
            }

            wp_reset_query();

            if($current_meta_price!==FALSE){
                if($current_meta_price<$min_price){
                    $min_price=$current_meta_price;
                }
            }

            update_post_meta($hotel_id,'min_price',$min_price);

        }

        static function inst()
        {
            if(!self::$_inst){
                self::$_inst=new self();
            }
            return self::$_inst;
        }
    }
    STAdminRoom::inst();
}
