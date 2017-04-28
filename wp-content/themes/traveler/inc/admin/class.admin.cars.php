<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STAdminCars
 *
 * Created by ShineTheme
 *
 */
$order_id = 0;
if (!class_exists('STAdminCars')) {

    class STAdminCars extends STAdmin
    {
        static $booking_page;
        static $data_term;
        static $_table_version= "1.2.4";
        protected $post_type="st_cars";

        /**
         *
         *
         * @update 1.1.3
         * */
        function __construct()
        {

            add_action('init',array($this,'_init_post_type'),8);

            if (!st_check_service_available($this->post_type)) return;

            add_action('init', array($this, 'get_list_value_taxonomy'), 98);
            add_action('init', array($this, 'init_metabox'), 99);
            add_action('admin_enqueue_scripts', array($this, 'init_data_location_from_to'), 99);

            //add_action( 'save_post', array($this,'cars_update_location') );
            //===============================================================
            add_filter('manage_st_cars_posts_columns', array($this, 'add_col_header'), 10);
            add_action('manage_st_cars_posts_custom_column', array($this, 'add_col_content'), 10, 2);

            //===============================================================
            self::$booking_page = admin_url('edit.php?post_type=st_cars&page=st_car_booking');
            add_action('admin_menu', array($this, 'add_menu_page'));

            if (self::is_booking_page()) {
                add_action('admin_enqueue_scripts', array(__CLASS__, 'add_edit_scripts'));

                add_action('admin_init',array($this,'_do_save_booking'));
            }

            if (isset($_GET['send_mail']) and $_GET['send_mail'] == 'success') {
                self::set_message(__('Email sent', ST_TEXTDOMAIN), 'updated');
            }
            add_action('wp_ajax_st_room_select_ajax', array(__CLASS__, 'st_room_select_ajax'));

            add_action('save_post', array($this, 'meta_update_sale_price'), 10, 4);
            parent::__construct();

            add_action('save_post', array($this, '_update_list_location'), 50, 2);
            add_action('save_post', array($this, '_update_duplicate_data'), 50, 2);
            add_action( 'before_delete_post', array($this, '_delete_data'), 50 );

            add_action('wp_ajax_st_getInfoCar', array(__CLASS__,'getInfoCar'), 9999);
            add_action('wp_ajax_st_getInfoCarPartner', array(__CLASS__,'getInfoCarPartner'), 9999);

            add_action('wp_ajax_st_get_location_childs', array(__CLASS__,'st_get_location_childs'), 9999);

            add_action('save_post', array($this,'st_save_location_from_to'), 9999);

            /**
            *   since 1.2.4 
            *   auto create & update table st_cars
            **/
            add_action( 'after_setup_theme', array (__CLASS__, '_check_table_car') );

        }

        static function check_ver_working(){
            $dbhelper = new DatabaseHelper(self::$_table_version);
            return $dbhelper->check_ver_working( 'st_cars_table_version' );
        }
        static function _check_table_car(){
            $dbhelper = new DatabaseHelper(self::$_table_version);
            $dbhelper->setTableName('st_cars');
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
                'cars_address' => array(
                    'type' => 'text',
                ),
                'cars_price' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'sale_price' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'number_car' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'cars_booking_period' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'is_sale_schedule' => array(
                    'type' => 'varchar',
                    'length' => 255
                ),
                'discount' => array(
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
            );

            $column = apply_filters( 'st_change_column_st_cars', $column );
            
            $dbhelper->setDefaultColums( $column );
            $dbhelper->check_meta_table_is_working( 'st_cars_table_version' );

            return array_keys( $column );
        }

        public function init_data_location_from_to(){

            $post_id = (int) STInput::request('post', '');
            $lists = array();

            $results = $this->get_data_location_from_to( $post_id );
            if( !empty( $results ) ){
                foreach( $results as $item ){
                    $lists[] = array(
                        'pickup' => (int) $item['location_from'],
                        'pickup_text' => get_the_title( (int) $item['location_from'] ),
                        'dropoff' => (int) $item['location_to'],
                        'dropoff_text' => get_the_title( (int) $item['location_to'] )
                    );
                }
            }
            wp_localize_script('jquery','st_location_from_to',array(
                'lists'=> $lists
            ));
        }

        public function get_data_location_from_to( $post_id ){
            global $wpdb;
            $table = $wpdb->prefix.'st_location_relationships';

            $sql = "SELECT * FROM {$table} WHERE post_id = {$post_id} AND location_from <> '' AND location_to <> '' AND location_type = 'location_from_to'";

            return $wpdb->get_results( $sql , ARRAY_A);
        }

        public function st_save_location_from_to( $post_id ){
            if( get_post_type( $post_id ) == 'st_cars'){
                $lists = STInput::request('locations_from_to', '');
                $locations = array();

                global $wpdb;
                $table = $wpdb->prefix.'st_location_relationships';

                if( !empty( $lists ) ){
                    if( !empty( $lists['pickup'] ) && is_array( $lists['pickup'] ) ){
                        foreach( $lists['pickup'] as $key => $list){
                            $locations[] = array(
                                'pickup' => (int) $list,
                                'dropoff' => (isset($lists['dropoff'][$key])) ? (int) $lists['dropoff'][$key] : 0,
                            );
                        }
                    }
                    $string_location = "";
                    if( !empty( $locations ) && is_array( $locations ) ){
                        foreach( $locations as $location){
                            $pickup = (int) $location['pickup'];
                            $dropoff = (int) $location['dropoff'];
                            $string_location .= "(location_from = ".$pickup. " AND location_to = ".$dropoff. ") OR ";
                            $this->insert_location_car( $post_id, $pickup, $dropoff);
                        }

                    }


                    if( !empty( $string_location ) ){

                        $string_location = substr( $string_location, 0, -3);

                        $sql = "DELETE
                            FROM
                                {$table}
                            WHERE
                                post_id = {$post_id}
                            AND location_type = 'location_from_to'
                            AND id NOT IN (
                                SELECT
                                    id
                                FROM
                                    (
                                        SELECT
                                            id
                                        FROM
                                            {$table}
                                    ) AS mytable
                                WHERE
                                    {$string_location}
                            )";
                        $wpdb->query( $sql );
                    }
                }else{
                    $sql = "DELETE
                        FROM
                            {$table}
                        WHERE
                            post_id = {$post_id}
                        AND location_type = 'location_from_to'";
                        
                    $wpdb->query( $sql );
                }
                
            }
        }

        public function insert_location_car( $post_id = '', $pickup = '', $dropoff = ''){
            global $wpdb;
            $table = $wpdb->prefix.'st_location_relationships';

            $sql = "SELECT ID FROM {$table} WHERE post_id = {$post_id} AND location_from = {$pickup} AND location_to = {$dropoff} AND location_type = 'location_from_to'";

            $row = $wpdb->get_var( $sql );
            
            if( empty( $row ) ){
                $data = array(
                    'post_id' => $post_id,
                    'location_from' => $pickup,
                    'location_to' => $dropoff,
                    'post_type' => 'st_cars',
                    'location_type' => 'location_from_to'
                );

                $wpdb->insert( $table, $data );
            }
        }

        static function st_get_location_childs(){
            $location = (int) STInput::request('location_id', '');

            $country = get_post_meta( $location, 'location_country', true);

            global $wpdb;
            $table = $wpdb->prefix.'st_location_nested';
            $result       = array(
                'total_count' => 0,
                'items'       => array(),
                );

            $ns = new Nested_set();
            $ns->setControlParams( $table );

            $nodes = $ns->getNodesWhere("location_country = '". $country. "'");
            
            if( !empty( $nodes ) ){
                $result['total_count'] = count( $nodes );
                foreach( $nodes as $node ){
                    $result['items'][] = array(
                        'id' => (int) $node['location_id'],
                        'name' => get_the_title( (int) $node['location_id'] ),
                        'description' => "ID: ". (int) $node['location_id']
                    );
                }
            }else{
                $result['total_count'] = 1;
                $result['items'][]         = array(
                    'id'                       => $location ,
                    'name'                     => get_the_title( $location ),
                    'description'              => "ID: ". $location
                );
            }

            echo json_encode($result);
            die();
        }
        static function get_price_unit( $need = 'value' ){
            $unit   = st()->get_option( 'cars_price_unit' , 'day' );
            $return = false;

            if($need == 'label') {
                $all = self::get_option_price_unit();

                if(!empty( $all )) {
                    foreach( $all as $key => $value ) {
                        if($value[ 'value' ] == $unit) {
                            if($unit == "distance"){
                                $return = st()->get_option('cars_price_by_distance','kilometer');
                            }else{
                                $return = $value[ 'label' ];
                            }
                        }
                    }
                } else $return = $unit;
            } elseif($need == 'plural') {
                switch( $unit ) {
                    case "hour":
                        $return = __( "hours" , ST_TEXTDOMAIN );
                        break;
                    case "day":
                        $return = __( "days" , ST_TEXTDOMAIN );
                        break;
                        break;
                    case "distance":
                        if(st()->get_option('cars_price_by_distance','kilometer') == "kilometer"){
                            $return = __( "kilometers" , ST_TEXTDOMAIN );
                        }else{
                            $return = __( "miles" , ST_TEXTDOMAIN );
                        }
                        break;
                }

            } else {
                if($unit == "distance"){
                    $return = st()->get_option('cars_price_by_distance','kilometer');
                }else{
                    $return = $unit;
                }
            }

            return apply_filters( 'st_get_price_unit' , $return , $need );
        }
        static function getInfoCar(){
            $car_id = intval(STInput::request('car_id', ''));
            $data = array(
                'price' => 'not infomation',
                'item_equipment' => 'not infomation' 
            );
            if($car_id <= 0 || get_post_type($car_id) != 'st_cars'){
                echo json_encode($data);
                die();
            }else{
                $price = floatval(get_post_meta($car_id, 'cars_price', true));
                $data['price'] = TravelHelper::format_money($price) .' / '.self::get_price_unit();
                $item_equipment = get_post_meta($car_id, 'cars_equipment_list', true);
                
                if(is_array($item_equipment) && count($item_equipment)){
                    $html = '';
                    $i = 0;
                    foreach($item_equipment as $key => $val){
                        $cars_equipment_list_price = TravelHelper::convert_money($val['cars_equipment_list_price']);
                        $cars_equipment_list_price_html = TravelHelper::format_money($cars_equipment_list_price ,false);
                        $html .= '<div class="form-group" style="margin-bottom: 10px">
                        <label for="item_equipment-'.$i.'"><input id="item_equipment-'.$i.'" type="checkbox" name="item_equipment[]" value="'.$val['title'].'--'.$cars_equipment_list_price.'">'.$val['title'].'('.$cars_equipment_list_price_html.')</label>
                        </div>';
                        $i ++;
                    }
                    $data['item_equipment'] = $html;
                }
                echo json_encode($data);
                die();
            }
        }
        static function getInfoCarPartner(){
            $car_id = intval(STInput::request('car_id', ''));
            $data = array(
                'price' => 'not infomation',
                'item_equipment' => 'not infomation' 
            );
            if($car_id <= 0 || get_post_type($car_id) != 'st_cars'){
                echo json_encode($data);
                die();
            }else{
                $price = floatval(get_post_meta($car_id, 'cars_price', true));
                $data['price'] = TravelHelper::format_money($price) .' / '.self::get_price_unit();
                $item_equipment = get_post_meta($car_id, 'cars_equipment_list', true);
                
                if(is_array($item_equipment) && count($item_equipment)){
                    $html = '<table class="table">';
                    $i = 0;
                    foreach($item_equipment as $key => $val){
                        $price_unit = isset($v['price_unit'])? $v['price_unit']: '';
                        $price_max = isset($v['cars_equipment_list_price_max'])? $v['cars_equipment_list_price_max']: '';
                        $cars_equipment_list_price = TravelHelper::convert_money($val['cars_equipment_list_price']);
                        //$cars_equipment_list_price = TravelHelper::format_money($cars_equipment_list_price , false);
                        $html .= '
                            <tr>
                                <td>
                                    <label for="item_equipment-'.$i.'" class="ml20"><input data-price="'.$cars_equipment_list_price.'" data-title="'.$val['title'].'" data-price-max="'.$price_max.'" data-price-unit="'.$price_unit.'" class="i-check list_equipment" id="item_equipment-'.$i.'" type="checkbox" value="'.$val['title'].'--'.$cars_equipment_list_price.'">'.$val['title'].' ('.TravelHelper::format_money($val['cars_equipment_list_price']).')</label>
                                </td>
                            </tr>
                        ';
                        $i ++;
                    }
                    $html .= "</table>";
                    $data['item_equipment'] = $html;
                }
                echo json_encode($data);
                die();
            }
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
                case 'resend_email_cars':
                    $this->_resend_mail();
                    break;
            }
        }
        function _update_duplicate_data($id, $data){
            if(!TravelHelper::checkTableDuplicate('st_cars')) return;
            if(get_post_type($id) == 'st_cars'){
                $num_rows = TravelHelper::checkIssetPost($id, 'st_cars');

                $location_str = get_post_meta($id,'multi_location', true);

                $location_id = ''; // location_id
                
                $cars_address = get_post_meta($id, 'cars_address', true); // address
                $cars_price = get_post_meta($id, 'cars_price', true); // price
                $number_car = get_post_meta($id, 'number_car', true); // number_car
                $cars_booking_period = get_post_meta($id, 'cars_booking_period', true); // cars_booking_period

                $sale_price=get_post_meta($id,'cars_price',true); // sale_price

                $discount = get_post_meta($id,'discount',true);
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

                if($num_rows == 1){
                    $data = array(
                        'multi_location' => $location_str,
                        'id_location' => $location_id,
                        'cars_address' => $cars_address,
                        'cars_price' => $cars_price,
                        'sale_price'=> $sale_price,
                        'discount'=> $discount,
                        'sale_price_from'=> $sale_from,
                        'sale_price_to'=> $sale_to,
                        'is_sale_schedule'=> $is_sale_schedule,
                        'number_car'=> $number_car,
                        'cars_booking_period'=> $cars_booking_period
                    );
                    $where = array(
                        'post_id' => $id
                    );
                    TravelHelper::updateDuplicate('st_cars', $data, $where);
                }elseif($num_rows == 0){
                    $data = array(
                        'post_id' => $id,
                        'multi_location' => $location_str,
                        'id_location' => $location_id,
                        'cars_address' => $cars_address,
                        'cars_price' => $cars_price,
                        'sale_price'=> $sale_price,
                        'discount'=> $discount,
                        'sale_price_from'=> $sale_from,
                        'sale_price_to'=> $sale_to,
                        'is_sale_schedule'=> $is_sale_schedule,
                        'number_car'=> $number_car,
                        'cars_booking_period'=> $cars_booking_period
                    );
                    TravelHelper::insertDuplicate('st_cars', $data);
                }
            }
        }
        public function _delete_data($post_id){
            if(get_post_type($post_id) == 'st_cars'){
                global $wpdb;
                $table = $wpdb->prefix.'st_cars';
                $rs = TravelHelper::deleteDuplicateData($post_id, $table);
                if(!$rs)
                    return false;
                return true;
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
                update_post_meta($id,'id_location', '');
            }
            
        }
        /**
         *
         *
         * @since 1.1.3
         * */
        function _init_post_type()
        {
            if(!st_check_service_available($this->post_type))
            {
                return;
            }

            if(!function_exists('st_reg_post_type')) return;
            // Cars ==============================================================
            $labels = array(
                'name'               => __( 'Cars', ST_TEXTDOMAIN ),
                'singular_name'      => __( 'Car', ST_TEXTDOMAIN ),
                'menu_name'          => __( 'Cars', ST_TEXTDOMAIN ),
                'name_admin_bar'     => __( 'Car', ST_TEXTDOMAIN ),
                'add_new'            => __( 'Add New', ST_TEXTDOMAIN ),
                'add_new_item'       => __( 'Add New Car', ST_TEXTDOMAIN ),
                'new_item'           => __( 'New Car', ST_TEXTDOMAIN ),
                'edit_item'          => __( 'Edit Car', ST_TEXTDOMAIN ),
                'view_item'          => __( 'View Car', ST_TEXTDOMAIN ),
                'all_items'          => __( 'All Cars', ST_TEXTDOMAIN ),
                'search_items'       => __( 'Search Cars', ST_TEXTDOMAIN ),
                'parent_item_colon'  => __( 'Parent Cars:', ST_TEXTDOMAIN ),
                'not_found'          => __( 'No Cars found.', ST_TEXTDOMAIN ),
                'not_found_in_trash' => __( 'No Cars found in Trash.', ST_TEXTDOMAIN ),
                'insert_into_item'   => __( 'Insert into Car', ST_TEXTDOMAIN),
                'uploaded_to_this_item'=> __( "Uploaded to this Car", ST_TEXTDOMAIN),
                'featured_image'=> __( "Feature Image", ST_TEXTDOMAIN),
                'set_featured_image'=> __( "Set featured image", ST_TEXTDOMAIN)
            );

            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => get_option( 'car_permalink' ,'st_car' ) ),
                'capability_type'    => 'post',
                'has_archive'        => false,
                'hierarchical'       => false,
                //'menu_position'      => null,
                'supports'           => array( 'author','title','editor','excerpt','thumbnail','comments' ),
                'menu_icon'          =>'dashicons-dashboard-st'
            );
            st_reg_post_type( 'st_cars', $args );

            // category cars
            $labels = array(
                'name'                       => __( 'Car Category',ST_TEXTDOMAIN ),
                'singular_name'              => __( 'Car Category',  ST_TEXTDOMAIN ),
                'search_items'               => __( 'Search Car Category' , ST_TEXTDOMAIN),
                'popular_items'              => __( 'Popular Car Category' , ST_TEXTDOMAIN),
                'all_items'                  => __( 'All Car Category', ST_TEXTDOMAIN ),
                'parent_item'                => null,
                'parent_item_colon'          => null,
                'edit_item'                  => __( 'Edit Car Category' , ST_TEXTDOMAIN),
                'update_item'                => __( 'Update Car Category' , ST_TEXTDOMAIN),
                'add_new_item'               => __( 'Add New Car Category', ST_TEXTDOMAIN ),
                'new_item_name'              => __( 'New Pickup Car Category', ST_TEXTDOMAIN ),
                'separate_items_with_commas' => __( 'Separate Car Category  with commas' , ST_TEXTDOMAIN),
                'add_or_remove_items'        => __( 'Add or remove Car Category', ST_TEXTDOMAIN ),
                'choose_from_most_used'      => __( 'Choose from the most used Car Category', ST_TEXTDOMAIN ),
                'not_found'                  => __( 'No Car Category found.', ST_TEXTDOMAIN ),
                'menu_name'                  => __( 'Car Category', ST_TEXTDOMAIN ),
            );

            $args = array(
                'hierarchical'          => true,
                'labels'                => $labels,
                'show_ui'               => true,
                'show_admin_column'     => true,
                'query_var'             => true,
                'rewrite'               => array( 'slug' => 'st_category_cars' ),
            );

            st_reg_taxonomy( 'st_category_cars', 'st_cars', $args );

            $labels = array(
                'name'                       => __("Pickup Features",ST_TEXTDOMAIN),
                'singular_name'              => __("Pickup Features",ST_TEXTDOMAIN),
                'search_items'               => __("Search Pickup Features",ST_TEXTDOMAIN),
                'popular_items'              => __( 'Popular Pickup Features' , ST_TEXTDOMAIN),
                'all_items'                  => __( 'All Pickup Features', ST_TEXTDOMAIN ),
                'parent_item'                => null,
                'parent_item_colon'          => null,
                'edit_item'                  => __( 'Edit Pickup Feature' , ST_TEXTDOMAIN),
                'update_item'                => __( 'Update Pickup Feature' , ST_TEXTDOMAIN),
                'add_new_item'               => __( 'Add New Pickup Feature', ST_TEXTDOMAIN ),
                'new_item_name'              => __( 'New Pickup Feature Name', ST_TEXTDOMAIN ),
                'separate_items_with_commas' => __( 'Separate Pickup Features with commas' , ST_TEXTDOMAIN),
                'add_or_remove_items'        => __( 'Add or remove Pickup Features', ST_TEXTDOMAIN ),
                'choose_from_most_used'      => __( 'Choose from the most used Pickup Features', ST_TEXTDOMAIN ),
                'not_found'                  => __( 'No Pickup Features found.', ST_TEXTDOMAIN ),
                'menu_name'                  => __( 'Pickup Features', ST_TEXTDOMAIN ),
            );

            $args = array(
                'hierarchical'          => true,
                'labels'                => $labels,
                'show_ui'               => true,
                'show_admin_column'     => true,
                'update_count_callback' => '_update_post_term_count',
                'query_var'             => true,
                'rewrite'               => array( 'slug' => 'st_cars_pickup_features' ),
            );

            st_reg_taxonomy( 'st_cars_pickup_features', 'st_cars', $args );

        }
        /**
         *
         *
         *
         * @since 1.1.1
         * */
        static function get_list_value_taxonomy()
        {
            $data_value = array();
            $taxonomy = get_object_taxonomies('st_cars', 'object');

            foreach ($taxonomy as $key => $value) {
                if ($key != 'st_category_cars') {
                    if ($key != 'st_cars_pickup_features') {
                        if (is_admin() and !empty($_REQUEST['post'])) {
                            $data_term = get_the_terms($_REQUEST['post'], $key, TRUE);

                            if (!empty($data_term)) {
                                foreach ($data_term as $k => $v) {
                                    array_push(
                                        $data_value, array(
                                            'value'    => $v->term_id,
                                            'label'    => $v->name,
                                            'taxonomy' => $v->taxonomy
                                        )
                                    );
                                }
                            }
                        }
                    }
                }
            }
            self::$data_term = $data_value;
        }

        /**
         *
         *
         * @since 1.1.1
         * */
        function init_metabox()
        {
            $this->metabox[] = array(
                'id'       => 'cars_metabox',
                'title'    => __('Cars Setting', ST_TEXTDOMAIN),
                'desc'     => '',
                'pages'    => array('st_cars'),
                'context'  => 'normal',
                'priority' => 'high',
                'fields'   => array(
                    array(
                        'label' => __('Location', ST_TEXTDOMAIN),
                        'id'    => 'location_tab',
                        'type'  => 'tab'
                    ),/*
                    array(
                        'label' => __('Location Type', ST_TEXTDOMAIN),
                        'id' => 'location_type',
                        'type' => 'select',
                        'choices' => array(
                            array(
                                'value' => 'multi_location',
                                'label' => __('Multi Location', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => 'check_in_out',
                                'label' => __('Limit by Pick Up - Drop Off', ST_TEXTDOMAIN)
                            )
                        )
                    ),*/
                    array(
                        'label'     => __('Location', ST_TEXTDOMAIN),
                        'id'        => 'multi_location', // id_location
                        'type'      => 'list_item_post_type',
                        'desc'        => __( 'Car Location', ST_TEXTDOMAIN),
                        'post_type'   =>'location',
                        /*'condition' => 'location_type:is(multi_location)'*/
                    ),/*
                    array(
                        'label' => __('Pick Up - Drop Offf', ST_TEXTDOMAIN),
                        'id' => 'check_in_out',
                        'type' => 'st_location_from_to',
                        'condition' => 'location_type:is(check_in_out)'
                    ),*/
                    array(
                        'label' => __('Address', ST_TEXTDOMAIN),
                        'id'    => 'cars_address',
                        'type'  => 'address_autocomplete',
                        'desc'  => __('Address', ST_TEXTDOMAIN),
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
                        'label' => __('Car Details', ST_TEXTDOMAIN),
                        'id'    => 'room_car_tab',
                        'type'  => 'tab'
                    ),
                    array(
                        'label' => __('Set as Featured', ST_TEXTDOMAIN),
                        'id'    => 'is_featured',
                        'type'  => 'on-off',
                        'desc'  => __('This is set as featured', ST_TEXTDOMAIN),
                        'std'   => 'off'
                    ), /*
                    array(
                        'label'     => __('Location', ST_TEXTDOMAIN),
                        'id'        => 'id_location',
                        'type'      => 'post_select_ajax',
                        'desc'      => __('Search location here', ST_TEXTDOMAIN),
                        'post_type' => 'location',
                    ),*/

                    array(
                        'label'     => __('Detail Car Layout', ST_TEXTDOMAIN),
                        'id'        => 'st_custom_layout',
                        'post_type' => 'st_layouts',
                        'desc'      => __('Detail Car Layout', ST_TEXTDOMAIN),
                        'type'      => 'select',
                        'choices'   => st_get_layout('st_cars')
                    ),
                    array(
                        'label'       => __( 'Gallery', ST_TEXTDOMAIN),
                        'id'          => 'gallery',
                        'type'        => 'gallery',
                    ),
                    /*array(
                        'label'       => __( 'Gallery style', ST_TEXTDOMAIN),
                        'id'          => 'gallery_style',
                        'type'        => 'select',
                        'choices'   =>array(
                            array(
                                'value'=>'grid',
                                'label'=>__('Grid',ST_TEXTDOMAIN)
                            ),
                            array(
                                'value'=>'slider',
                                'label'=>__('Slider',ST_TEXTDOMAIN)
                            ),
                        )
                    ),*/
                    array(
                        'label'    => __('Equipment Price List', ST_TEXTDOMAIN),
                        'desc'    => __('Equipment Price List', ST_TEXTDOMAIN),
                        'id'       => 'cars_equipment_list',
                        'type'     => 'list-item',
                        'settings' => array(
                            array(
                                'id'    => 'cars_equipment_list_price',
                                'label' => __('Price', ST_TEXTDOMAIN),
                                'type'  => 'text',
                            ),

                            array(
                                'id'    => 'price_unit',
                                'label' => __('Price Unit', ST_TEXTDOMAIN),
                                'desc' => __('You can choose <code>Fixed Price</code>, <code>Price per Hour</code>, <code>Price per Day</code>', ST_TEXTDOMAIN),
                                'type'  => 'select',
                                'choices'=>array(
                                    array(
                                        'value'=>'',
                                        'label'=>__('Fixed Price',ST_TEXTDOMAIN)
                                    ),
                                    array(
                                        'value'=>'per_hour',
                                        'label'=>__('Price per Hour',ST_TEXTDOMAIN)
                                    ),
                                    array(
                                        'value'=>'per_day',
                                        'label'=>__('Price per Day',ST_TEXTDOMAIN)
                                    ),
                                )
                            ),
                            array(
                                'id'    => 'cars_equipment_list_price_max',
                                'label' => __('Price Max', ST_TEXTDOMAIN),
                                'type'  => 'text',
                                'condition' => 'price_unit:not()'
                            ),
                        )
                    ),
                    array(
                        'label'    => __('Features', ST_TEXTDOMAIN),
                        'desc'    => __('Features', ST_TEXTDOMAIN),
                        'id'       => 'cars_equipment_info',
                        'type'     => 'list-item',
                        'settings' => array(
                            array(
                                'id'       => 'cars_equipment_taxonomy_id',
                                'label'    => __('Taxonomy', ST_TEXTDOMAIN),
                                'type'     => 'select',
                                'operator' => 'and',
                                'choices'  => self::$data_term
                            ),
                            array(
                                'id'    => 'cars_equipment_taxonomy_info',
                                'label' => __('Taxonomy Info', ST_TEXTDOMAIN),
                                'type'  => 'text',
                            )
                        )
                    ),
                    array(
                        'label' => __('Video', ST_TEXTDOMAIN),
                        'id'    => 'video',
                        'type'  => 'text',
                        'desc'  => __('Please use youtube or vimeo video', ST_TEXTDOMAIN)
                    ),
                    array(
                        'label' => __('Logo', ST_TEXTDOMAIN),
                        'id'    => 'cars_logo',
                        'type'  => 'upload',
                        'desc'  => __('Logo', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label' => __('Car manufacturer name', ST_TEXTDOMAIN),
                        'id'    => 'cars_name',
                        'type'  => 'text',
                        'desc'  => __('Car manufacturer name', ST_TEXTDOMAIN),
                    ),


                    array(
                        'label' => __('About', ST_TEXTDOMAIN),
                        'desc' => __('About', ST_TEXTDOMAIN),
                        'id'    => 'cars_about',
                        'type'  => 'textarea',
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
						'label' => __('Email', ST_TEXTDOMAIN),
						'id'    => 'cars_email',
						'type'  => 'text',
						'desc'  => __('E-mail Car Agent, this address will received email when have new booking', ST_TEXTDOMAIN),
					),
					array(
						'label' => __('Website', ST_TEXTDOMAIN),
						'id'    => 'cars_website',
						'type'  => 'text',
						'desc'  => __('Website Car Agent', ST_TEXTDOMAIN),
					),
					array(
						'label' => __('Phone', ST_TEXTDOMAIN),
						'id'    => 'cars_phone',
						'type'  => 'text',
						'desc'  => __('Phone', ST_TEXTDOMAIN),
					),
					array(
						'label' => __('Fax Number', ST_TEXTDOMAIN),
						'id'    => 'cars_fax',
						'type'  => 'text',
						'desc'  => __('Fax Number', ST_TEXTDOMAIN),
					),
                    array(
                        'label' => __('Price setting', ST_TEXTDOMAIN),
                        'id'    => '_price_car_tab',
                        'type'  => 'tab'
                    ),
                    array(
                        'label' => sprintf(__('Price (%s)', ST_TEXTDOMAIN), TravelHelper::get_default_currency('symbol')),
                        'id'    => 'cars_price',
                        'type'  => 'text',
                        'desc'  => __('Price', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label' => __('Custom Price', ST_TEXTDOMAIN),
                        'id'    => 'is_custom_price',
                        'std'   => 'off',
                        'type'    => 'select',
                        'choices' => array(
                            array(
                                'value' => 'price_by_number',
                                'label' => __('Price by number of day|hour', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => 'price_by_date',
                                'label' => __('Price by Date', ST_TEXTDOMAIN)
                            )
                        )
                    ),
                    array(
                        'label' => __("Price by Date", ST_TEXTDOMAIN),
                        'id'    => 'st_custom_price_by_date',
                        'type'  => 'st_custom_price',
                        'desc'  => __('Price by Date', ST_TEXTDOMAIN),
                        'condition' => 'is_custom_price:is(price_by_date)'
                    ),
                    array(
                        'label' => __("Price by number of day/hour",ST_TEXTDOMAIN),
                        'id'    => 'price_by_number_of_day_hour',
                        'desc'  => __('Price by number of day/hour', ST_TEXTDOMAIN),
                        'type'     => 'list-item',
                        'condition' => 'is_custom_price:is(price_by_number)',
                        'settings' => array(
                            array(
                                'id'       => 'number_start',
                                'label'    => __('Number Start', ST_TEXTDOMAIN),
                                'type'     => 'text',
                                'operator' => 'and',
                            ),
                            array(
                                'id'    => 'number_end',
                                'label' => __('Number End', ST_TEXTDOMAIN),
                                'type'  => 'text',
                            ),
                            array(
                                'id'    => 'price',
                                'label' => sprintf(__('Price (%s)', ST_TEXTDOMAIN), TravelHelper::get_default_currency('symbol')),
                                'type'  => 'text',
                            )
                        )
                    ),

                    array(
                        'label' => __('Discount', ST_TEXTDOMAIN),
                        'id'    => 'discount',
                        'type'  => 'text',
                        'desc'  => __('%', ST_TEXTDOMAIN),
                        'std'   => 0
                    ),
                    array(
                        'label' => __('Sale Schedule', ST_TEXTDOMAIN),
                        'id'    => 'is_sale_schedule',
                        'type'  => 'on-off',
                        'std'   => 'off',
                    ),
                    array(
                        'label'     => __('Sale Price Date From', ST_TEXTDOMAIN),
                        'desc'      => __('Sale Price Date From', ST_TEXTDOMAIN),
                        'id'        => 'sale_price_from',
                        'type'      => 'date-picker',
                        'condition' => 'is_sale_schedule:is(on)'
                    ),
                    array(
                        'label'     => __('Sale Price Date To', ST_TEXTDOMAIN),
                        'desc'      => __('Sale Price Date To', ST_TEXTDOMAIN),
                        'id'        => 'sale_price_to',
                        'type'      => 'date-picker',
                        'condition' => 'is_sale_schedule:is(on)'
                    ),
                    array(
                        'label' => __('Number of car for Rent', ST_TEXTDOMAIN),
                        'desc'  => __('Number of car for Rent', ST_TEXTDOMAIN),
                        'id'    => 'number_car',
                        'type'  => 'text',
                        'std'   => 1
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
                        'label'     => __('Deposit payment amount', ST_TEXTDOMAIN),
                        'desc'      => __('Leave empty for disallow deposit payment', ST_TEXTDOMAIN),
                        'id'        => 'deposit_payment_amount',
                        'type'      => 'text',
                        'condition' => 'deposit_payment_status:not()'
                    ),
                    array(
                        'label' => __('Car Options', ST_TEXTDOMAIN),
                        'id'    => 'cars_options',
                        'type'  => 'tab'
                    ),
                    array(
                        'label'        => __('Minimum days to book before rental', ST_TEXTDOMAIN),
                        'id'           => 'cars_booking_period',
                        'type'         => 'numeric-slider',
                        'min_max_step' => '0,30,1',
                        'std'          => 0,
                        'desc'         => __('The time period allowed booking.', ST_TEXTDOMAIN),
                    ),
                    (st()->get_option( 'cars_price_unit' , 'day' ) =='day') ?  
                        array(
                            'label'        => __('Minimum days to book', ST_TEXTDOMAIN),
                            'id'           => 'cars_booking_min_day',
                            'type'         => 'numeric-slider',
                            'min_max_step' => '0,7,1',
                            'std'          => 0,
                            'desc'         => __('Minimum days to book', ST_TEXTDOMAIN),
                        )
                        :array(
                            'label'        => __('Minimum hours to book', ST_TEXTDOMAIN),
                            'id'           => 'cars_booking_min_hour',
                            'type'         => 'numeric-slider',
                            'min_max_step' => '0,168,1',
                            'std'          => 0,
                            'desc'         => __('Minimum hours to book', ST_TEXTDOMAIN),
                        ),  
                    array(
                        'label' => __('Car external booking', ST_TEXTDOMAIN),
                        'id'    => 'st_car_external_booking',
                        'type'  => 'on-off',
                        'std'   => "off",
                    ),
                    array(
                        'label'     => __('Car external booking link', ST_TEXTDOMAIN),
                        'id'        => 'st_car_external_booking_link',
                        'type'      => 'text',
                        'std'       => "",
                        'condition' => 'st_car_external_booking:is(on)',
                        'desc'  => "<i>".__("Must be http://")."</i>"
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
            if (!empty($data_paypment) and is_array($data_paypment)) {
                $this->metabox[0]['fields'][] = array(
                    'label' => __('Payment', ST_TEXTDOMAIN),
                    'id'    => 'payment_detail_tab',
                    'type'  => 'tab'
                );
                foreach ($data_paypment as $k => $v) {
                    $this->metabox[0]['fields'][] = array(
                        'label' => $v->get_name(),
                        'id'    => 'is_meta_payment_gateway_' . $k,
                        'type'  => 'on-off',
                        'desc'  => $v->get_name(),
                        'std'   => 'on'
                    );
                }
            }
            $custom_field = st()->get_option('st_cars_unlimited_custom_field');
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

        function meta_update_sale_price($post_id)
        {
            if (wp_is_post_revision($post_id))
                return;
            $post_type = get_post_type($post_id);
            if ($post_type == 'st_cars') {
                $sale_price = get_post_meta($post_id, 'cars_price', TRUE);
                $discount = get_post_meta($post_id, 'discount', TRUE);
                $is_sale_schedule = get_post_meta($post_id, 'is_sale_schedule', TRUE);
                if ($is_sale_schedule == 'on') {
                    $sale_from = get_post_meta($post_id, 'sale_price_from', TRUE);
                    $sale_to = get_post_meta($post_id, 'sale_price_to', TRUE);
                    if ($sale_from and $sale_from) {

                        $today = date('Y-m-d');
                        $sale_from = date('Y-m-d', strtotime($sale_from));
                        $sale_to = date('Y-m-d', strtotime($sale_to));
                        if (($today >= $sale_from) && ($today <= $sale_to)) {

                        } else {

                            $discount = 0;
                        }

                    } else {
                        $discount = 0;
                    }
                }
                if ($discount) {
                    $sale_price = $sale_price - ($sale_price / 100) * $discount;
                }
                update_post_meta($post_id, 'sale_price', $sale_price);
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
                    STCart::send_mail_after_booking($order);
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
            query_posts(array('post_type' => $post_type, 'posts_per_page' => 10, 's' => $q, 'meta_key' => 'room_parent', 'meta_value' => $room_parent));
            $r = array(
                'items' => array()
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

            $gg_api_key  = st()->get_option('google_api_key', "");

            if (TravelHelper::is_https()){
                $url=add_query_arg(array(
                    'v'=>'3',
                    'signed_in'=>'true',
                    'libraries'=>'places',
                    'language'=>'en',
                    'sensor'=>'false',
                    'key' => $gg_api_key
                ),'https://maps.googleapis.com/maps/api/js');
                wp_enqueue_script('gmap-apiv3', $url, array(), null, true);
            }else {
                $url=add_query_arg(array(
                    'v'=>'3',
                    'signed_in'=>'true',
                    'libraries'=>'places',
                    'language'=>'en',
                    'sensor'=>'false',
                    'key' => $gg_api_key
                ),'http://maps.googleapis.com/maps/api/js');
                wp_enqueue_script('gmap-apiv3', $url, array(), null, true);
            }

            wp_enqueue_style('datetimepicker.css', get_template_directory_uri() . '/css/jquery-ui-timepicker-addon.css');
            wp_enqueue_script('select2');
            wp_enqueue_script('st-edit-booking', get_template_directory_uri() . '/js/admin/edit-booking.js', array('jquery'), NULL, TRUE);
            wp_enqueue_script('car-datetimepicker', get_template_directory_uri() . '/js/jquery-ui-timepicker-addon.js', array('jquery'), NULL, TRUE);
            wp_enqueue_script('car-booking', get_template_directory_uri() . '/js/admin/car-booking.js', array('jquery'), NULL, TRUE);
            wp_enqueue_script('st-jquery-ui-datepicker',get_template_directory_uri().'/js/jquery-ui.js');
            wp_enqueue_style('jjquery-ui.theme.min.css', get_template_directory_uri() . '/css/admin/jquery-ui.min.css');
        }

        static function is_booking_page()
        {
            if (is_admin()
                and isset($_GET['post_type'])
                and $_GET['post_type'] == 'st_cars'
                and isset($_GET['page'])
                and $_GET['page'] = 'st_car_booking'
            ) return TRUE;

            return FALSE;
        }

        function add_menu_page()
        {
            //Add booking page
            add_submenu_page('edit.php?post_type=st_cars', __('Car Booking', ST_TEXTDOMAIN), __('Car Booking', ST_TEXTDOMAIN), 'manage_options', 'st_car_booking', array($this, '__car_booking_page'));
        }

        function  __car_booking_page()
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
                echo balanceTags($this->load_view('car/booking_index', FALSE));
            }
        }

        function add_booking()
        {
            echo balanceTags($this->load_view('car/booking_edit', FALSE, array('page_title' => __('Add new Car Booking', ST_TEXTDOMAIN))));
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
                return FALSE;
            }

            if (isset($_POST['submit']) and $_POST['submit']) $this->_save_booking($item_id);
            echo balanceTags($this->load_view('car/booking_edit'));
        }

        function _add_booking(){
            if (!check_admin_referer('shb_action', 'shb_field')) die;
            $data = $this->check_validate();
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

                    if (!empty($check_out_field)) {
                        foreach ($check_out_field as $field_name => $field_desc) {
                            update_post_meta($order_id, $field_name, STInput::post($field_name));
                        }
                    }

                    update_post_meta($order_id, 'payment_method', 'st_submit_form');

                    $id_user = get_current_user_id();
                    update_post_meta($order_id, 'id_user', $id_user);

                    $pick_up = $drop_off ="";;
                    $id_pick_up = STInput::request('pick_up');
                    $id_drop_off = STInput::request('drop_off');
                    if(!empty($id_pick_up)){
                        $pick_up = get_the_title($id_pick_up);
                    }
                    if(!empty($id_drop_off)){
                        $drop_off = get_the_title($id_drop_off);
                    }

                    $item_price = floatval(get_post_meta($item_id, 'cars_price', true));
                    $unit = st()->get_option('cars_price_unit', 'day');
                    /*if($unit == 'day'){
                        $numberday = ceil(($check_out_timestamp - $check_in_timestamp) / (60 * 60 * 24));
                    }elseif($unit == 'hour'){
                        $numberday = ceil(($check_out_timestamp - $check_in_timestamp) / (60 * 60));
                    }*/
                    $numberday = STCars::get_date_diff( $check_in_timestamp , $check_out_timestamp , $unit);
                    $number_distance = 0;
                    if($unit == "distance"){
                        $number_distance = STPrice::getDistanceByCar($id_pick_up,$id_drop_off);
                        $origin_price = $item_price * $number_distance;
                    }else{
                        $origin_price = $item_price * $numberday;
                    }
                    $selected_equipments = $_POST['item_equipment'];
                    $price_equipment = STPrice::getPriceEuipmentCarAdmin($selected_equipments);
                    $data_equipment = STPrice::convertEquipmentToOject($selected_equipments);

                    $sale_price = STPrice::getSaleCarPrice($item_id, $item_price,  $check_in_timestamp, $check_out_timestamp,$id_pick_up,$id_drop_off);
                    $price_with_tax = STPrice::getPriceWithTax($sale_price + $price_equipment);
                    $coupon_price = 0;
                    $deposit_money['data'] = array();
                            
                    $deposit_money = STPrice::getDepositData($item_id, $deposit_money);
                    $deposit_price = STPrice::getDepositPrice($deposit_money['data']['deposit_money'], $price_with_tax, 0);
                    if(isset($deposit_money['data']['deposit_money'])){
                        $total_price = $deposit_price;
                    }else{
                        $total_price = $price_with_tax - $coupon_price;
                    }

                    $data_prices = array(
                        'origin_price' => $origin_price,
                        'sale_price' => $sale_price,
                        'coupon_price' => 0,
                        'price_with_tax' => $price_with_tax,
                        'total_price' => $total_price,
                        'deposit_price' => $deposit_price,
                        'unit' => $unit,
                        'distance_type' => st()->get_option("cars_price_by_distance","kilometer"),
                        'distance' => $number_distance,
                        'price_equipment' => $price_equipment
                    );

                    $item_data  = array(
                        'item_id' => $item_id,
                        'item_number' => 1,
                        'total_price'          => $total_price,
                        'item_price'           => $item_price,
                        'check_in'             => $check_in,
                        'check_in_timestamp'   => $check_in_timestamp,
                        'check_out'            => $check_out,
                        'check_out_timestamp'  => $check_out_timestamp,
                        'st_booking_id'        => $item_id,
                        'check_in_time'        => $check_in_time,
                        'check_out_time'       => $check_out_time,
                        'pick_up' => $pick_up,
                        'drop_off' => $drop_off,
                        'location_id_pick_up'  => $id_pick_up,
                        'location_id_drop_off' => $id_drop_off,
                        'deposit_money'        => $deposit_money,
                        'booking_by' => 'admin',
                        'st_tax'               => STPrice::getTax(),
                        'st_tax_percent'       => STPrice::getTax(),
                        'status'               => $_POST['status'],
                        'currency'        => TravelHelper::get_current_currency('symbol'),
                        'currency_rate' => TravelHelper::get_current_currency('rate'),
                        'data_equipment' => $data_equipment,
                        'data_prices' => $data_prices,
                        'commission' => TravelHelper::get_commission()
                    );
                    foreach ($item_data as $val => $value) {
                        update_post_meta($order_id, $val, $value);
                    }

                    if(TravelHelper::checkTableDuplicate('st_cars')){
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
                            'st_booking_post_type' => 'st_cars',
                            'check_in' => date('m/d/Y',strtotime($check_in)),
                            'check_out' => date('m/d/Y',strtotime($check_out)),
                            'st_booking_id' => $item_id,
                            'check_in_timestamp' => $check_in_timestamp,
                            'check_out_timestamp' => $check_out_timestamp,
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
                    do_action('st_booking_success', $order_id);

                    wp_safe_redirect(self::$booking_page);
                }
            }
        }

        function _save_booking($order_id){
            if (!check_admin_referer('shb_action', 'shb_field')) die;
            $data = $this->check_validate();
            if(is_array($data)){
                $check_out_field = STCart::get_checkout_fields();

                if(!empty($check_out_field)) {
                    foreach ($check_out_field as $field_name => $field_desc) {
                        update_post_meta($order_id, $field_name, STInput::post($field_name));
                    }
                }


                $item_data             = array(
                    'status'               => $_POST['status']
                );

                foreach ($item_data as $val => $value) {
                    update_post_meta($order_id, $val, $value);
                }

                foreach ($data as $val => $value) {
                    update_post_meta($order_id, $val, $value);
                }


                if(TravelHelper::checkTableDuplicate('st_cars')){
                    global $wpdb;

                    $table = $wpdb->prefix.'st_order_item_meta';
                    $data = array(
                        'status' => $_POST['status']
                    );
                    $where = array(
                        'order_item_id' => $order_id
                    );
                    $wpdb->update($table, $data, $where);
                }

                STCart::send_mail_after_booking($order_id, true);


                //wp_safe_redirect(self::$booking_page);
            }
        }

        function check_validate(){
            $data = array();
            
            $order_item_id = STInput::request('order_item_id', '');

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

                if($item_id <= 0 || get_post_type($item_id) != 'st_cars'){
                    STAdmin::set_message(__('The car field is not empty.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }

                $pick_up = STInput::request('pick_up', '');
                $drop_off = STInput::request('drop_off', '');

                if(!empty($pick_up) && !empty($drop_off)){
                    $pickup_country = get_post_meta($pick_up, 'location_country', true);
                    if(!$pickup_country){
                        STAdmin::set_message( __( 'The \'country\' field not set for the \'\'', ST_TEXTDOMAIN ).get_the_title($pick_up) , 'danger' );
                        $pass_validate = false;
                        return false;
                    }
                    $dropoff_country = get_post_meta($drop_off, 'location_country', true);
                    if(!$dropoff_country){
                        STAdmin::set_message( __( 'The \'country\' field not set for the \'\'', ST_TEXTDOMAIN ).get_the_title($drop_off) , 'danger' );
                        $pass_validate = false;
                        return false;
                    }

                    if($pickup_country != $dropoff_country){
                        STAdmin::set_message( __( 'The country is not same' , ST_TEXTDOMAIN ) , 'danger' );
                        $pass_validate = false;
                        return false;
                    }

                }

                $check_in = STInput::request('check_in', '');
                if(empty($check_in)){
                    STAdmin::set_message(__('The check in field  is not empty.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }

                $check_in_time = STInput::request('check_in_time', '');
                if(empty($check_in_time)){
                    STAdmin::set_message(__('The check in time field  is not empty.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }

                $check_out = STInput::request('check_out', '');
                if(empty($check_out)){
                    STAdmin::set_message(__('The check out field  is not empty.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }

                $check_out_time = STInput::request('check_out_time', '');
                if(empty($check_out_time)){
                    STAdmin::set_message(__('The check out time field  is not empty.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }

                $check_in_full = $check_in.' '.strtoupper($check_in_time);
                $check_out_full = $check_out.' '.strtoupper($check_out_time);

                $check_in_timestamp = strtotime($check_in_full);
                $check_out_timestamp = strtotime($check_out_full);

                $today = date( 'm/d/Y');

                $period = TravelHelper::dateDiff($today,$check_in);
                    
                $compare = TravelHelper::dateCompare($today, $check_out);


                $booking_period = intval(get_post_meta($item_id, 'cars_booking_period', true));
                if($booking_period <= 0) $booking_period = 0;

                if( $check_in_timestamp - $check_out_timestamp >= 0 ){
                    STAdmin::set_message( __( 'The drop off datetime is later than the pick up datetime.' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }

                if($compare < 0){
                    STAdmin::set_message( __( 'You can not set check-in date in the past' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }

                if($period < $booking_period){
                    STAdmin::set_message( sprintf( __( 'This car allow minimum booking is %d day(s)' , ST_TEXTDOMAIN ) , $booking_period ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }

                if(!CarHelper::_get_car_cant_order_by_id($item_id, $check_in_timestamp, $check_out_timestamp, $order_item_id)){
                    STAdmin::set_message(__( 'This car is full order' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }

                $data = array(
                    'order_item_id' => $order_item_id,
                    'item_id' => $item_id,
                    'location_id_pick_up' => $pick_up,
                    'location_id_drop_off' => $drop_off,
                    'pick_up' => STInput::request('st_google_location_pickup'),
                    'drop_off' => STInput::request('st_google_location_dropoff'),
                    'check_in' => date('Y-m-d',strtotime($check_in)),
                    'check_out' => date('Y-m-d', strtotime($check_out)),
                    'check_in_timestamp' => $check_in_timestamp,
                    'check_out_timestamp' => $check_out_timestamp,
                    'st_booking_post_type' => 'st_cars',
                    'st_booking_id' => $item_id,
                    'check_in_time' => $check_in_time,
                    'check_out_time' => $check_out_time
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

        // =================================================================
        function init()
        {
            $this->add_meta_field();
        }

        function add_meta_field()
        {
            if (is_admin()) {
                $pages = array('st_cars_pickup_features');
                /*
                 * prefix of meta keys, optional
                 */
                $prefix = 'st_';
                /*
                 * configure your meta box
                 */
                $config = array(
                    'id'             => 'st_extra_infomation_cars',          // meta box id, unique per meta box
                    'title'          => __('Extra Information', ST_TEXTDOMAIN),          // meta box title
                    'pages'          => $pages,        // taxonomy name, accept categories, post_tag and custom taxonomies
                    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
                    'fields'         => array(),            // list of meta fields (can be added by field arrays)
                    'local_images'   => FALSE,          // Use local or hosted images (meta box images for add/remove)
                    'use_with_theme' => FALSE          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
                );

                if (!class_exists('Tax_Meta_Class')) {
                    STFramework::write_log('Tax_Meta_Class not found in class.attribute.php line 121');

                    return;
                }
                /*
                 * Initiate your meta box
                 */
                $my_meta = new Tax_Meta_Class($config);

                /*
                 * Add fields to your meta box
                 */
                //text field
                $my_meta->addText($prefix . 'icon', array('name' => __('Icon', ST_TEXTDOMAIN),
                                                          'desc' => __('Example: <br>Input "fa-desktop" for <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank" >Fontawesome</a>,<br>Input "im-pool" for <a href="https://icomoon.io/" target="_blank">Icomoon</a>  ', ST_TEXTDOMAIN)));

                //Image field
                //$my_meta->addImage($prefix.'image',array('name'=> __('Image ',ST_TEXTDOMAIN),
                // 'desc'=>__('If dont like the icon, you can use image instead',ST_TEXTDOMAIN)));
                //file upload field

                /*
                 * Don't Forget to Close up the meta box decleration
                 */
                //Finish Meta Box Decleration
                $my_meta->Finish();
            }

        }

        function cars_update_location($post_id)
        {
            if (wp_is_post_revision($post_id))
                return;
            $post_type = get_post_type($post_id);
            if ($post_type == 'st_cars') {
                $location_id = get_post_meta($post_id, 'id_location', TRUE);
                $ids_in = array();
                $parents = get_posts(array('numberposts' => -1, 'post_status' => 'publish', 'post_type' => 'location', 'post_parent' => $location_id));

                $ids_in[] = $location_id;

                foreach ($parents as $child) {
                    $ids_in[] = $child->ID;
                }
                $arg = array(
                    'post_type'      => 'st_cars',
                    'posts_per_page' => '-1',
                    'meta_query'     => array(
                        array(
                            'key'     => 'id_location',
                            'value'   => $ids_in,
                            'compare' => 'IN',
                        ),
                    ),
                );
                $query = new WP_Query($arg);
                $offer_tours = $query->post_count;

                // get total review
                $arg = array(
                    'post_type'      => 'st_cars',
                    'posts_per_page' => '-1',
                    'meta_query'     => array(
                        array(
                            'key'     => 'id_location',
                            'value'   => $ids_in,
                            'compare' => 'IN',
                        ),
                    ),
                );
                $query = new WP_Query($arg);
                $total = 0;
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $total += get_comments_number();
                    }
                }
                // get car min price
                $arg = array(
                    'post_type'      => 'st_cars',
                    'posts_per_page' => '1',
                    'order'          => 'ASC',
                    'meta_key'       => 'sale_price',
                    'orderby'        => 'meta_value_num',
                    'meta_query'     => array(
                        array(
                            'key'     => 'id_location',
                            'value'   => $ids_in,
                            'compare' => 'IN',
                        ),
                    ),
                );
                $query = new WP_Query($arg);
                if ($query->have_posts()) {
                    $query->the_post();
                    $price_min = get_post_meta(get_the_ID(), 'cars_price', TRUE);
                    update_post_meta($location_id, 'review_st_cars', $total);
                    update_post_meta($location_id, 'min_price_st_cars', $price_min);
                    update_post_meta($location_id, 'offer_st_cars', $offer_tours);
                }
                wp_reset_postdata();
            }
        }

        function add_col_header($defaults)
        {

            $this->array_splice_assoc($defaults,2,0,array(
                'price'=>__('Price',ST_TEXTDOMAIN),
                'cars_layout'=>__('Layout',ST_TEXTDOMAIN),
            ));

            return $defaults;
        }
        function array_splice_assoc(&$input, $offset, $length = 0, $replacement = array()) {
            $tail = array_splice($input, $offset);
            $extracted = array_splice($tail, 0, $length);
            $input += $replacement + $tail;
            return $extracted;
        }
        function add_col_content($column_name, $post_ID)
        {
            if ($column_name == 'cars_layout') {
                // show content of 'directors_name' column
                $parent = get_post_meta($post_ID, 'st_custom_layout', TRUE);

                if ($parent) {
                    echo "<a href='" . get_edit_post_link($parent) . "'>" . get_the_title($parent) . "</a>";
                } else {
                    echo __('Default', ST_TEXTDOMAIN);
                }
            }
            if ($column_name == 'price') {
                $price=get_post_meta($post_ID,'cars_price',true);
                $discount=get_post_meta($post_ID,'discount',true);
                if(!empty($discount)){
                    $x=$discount;
                    $discount = $price - $price * ( $discount / 100 );
                    $is_sale_schedule=get_post_meta($post_ID,'is_sale_schedule',true);
                    if($is_sale_schedule == "on"){
                        $sale_from=get_post_meta($post_ID,'sale_price_from',true);
                        $sale_from = mysql2date('d/m/Y',$sale_from);
                        $sale_to=get_post_meta($post_ID,'sale_price_to',true);
                        $sale_to = mysql2date('d/m/Y',$sale_to);
                        echo '<span class="sale">'.TravelHelper::format_money($price).'</span>  <i class="fa fa-arrow-right"></i>  <strong>'.esc_html(TravelHelper::format_money($discount)).'</strong> <br>';
                        echo '<span>'.__('Discount rate',ST_TEXTDOMAIN).' : '.$x.'%</span><br>';
                        echo '<span> '.$sale_from.' <i class="fa fa-arrow-right"></i> '.$sale_to.'</span> <br>';
                    }else{
                        echo '<span class="sale">'.TravelHelper::format_money($price).'</span>  <i class="fa fa-arrow-right"></i>  <strong>'.esc_html(TravelHelper::format_money($discount)).'</strong><br>';
                        echo '<span>'.__('Discount rate',ST_TEXTDOMAIN).' : '.$x.'%</span><br>';
                    }
                }
                else if($price) {
                    echo '<span>'.TravelHelper::format_money($price).'</span>';
                }
            }


        }
    }

    $a = new STAdminCars();
    $a->init();
}