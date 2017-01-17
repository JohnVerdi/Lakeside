<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STUser
 *
 * Created by ShineTheme
 *
 */
if(!class_exists('STUser'))
{

    class STUser extends STAdmin
    {
        private $extra_fields=array();

        function __construct()
        {
            parent::__construct();

        }



        //Do one time
        function init()
        {
            $this->extra_fields =array(


                'st_address'=>array(
                    'type'=>'text',
                    'label'=>__('Address Line 1',ST_TEXTDOMAIN),
                    'desc'=>__('Show under your reviews',ST_TEXTDOMAIN)
                ),
                'st_address2'=>array(
                    'type'=>'text',
                    'label'=>__('Address Line 2',ST_TEXTDOMAIN),
                    'desc'=>__('Address Line 2',ST_TEXTDOMAIN)
                ),
                'st_phone'=>array(
                    'type'=>'text',
                    'label'=>__('Phone',ST_TEXTDOMAIN),
                    'desc'=>__('Phone',ST_TEXTDOMAIN)
                ),
                'st_fax'=>array(
                    'type'=>'text',
                    'label'=>__('Fax Number',ST_TEXTDOMAIN),
                    'desc'=>__('Fax Number',ST_TEXTDOMAIN)
                ),
                'st_airport'=>array(
                    'type'=>'text',
                    'label'=>__('Airport',ST_TEXTDOMAIN),
                    'desc'=>__('Airport',ST_TEXTDOMAIN)
                ),
                'st_city'=>array(
                    'type'=>'text',
                    'label'=>__('City',ST_TEXTDOMAIN),
                    'desc'=>__('City',ST_TEXTDOMAIN)
                ),
                'st_province'=>array(
                    'type'=>'text',
                    'label'=>__('State/Province/Region',ST_TEXTDOMAIN),
                    'desc'=>__('State/Province/Region',ST_TEXTDOMAIN)
                ),
                'st_zip_code'=>array(
                    'type'=>'text',
                    'label'=>__('ZIP code/Postal code',ST_TEXTDOMAIN),
                    'desc'=>__('ZIP code/Postal code',ST_TEXTDOMAIN)
                ),
                'st_country'=>array(
                    'type'=>'text',
                    'label'=>__('Country',ST_TEXTDOMAIN),
                    'desc'=>__('Country',ST_TEXTDOMAIN)
                )

            );

            $this->extra_fields=apply_filters('st_user_extra_fields',$this->extra_fields);


            add_action( 'show_user_profile',array($this,'show_user_profile')  );

            add_action( 'edit_user_profile', array($this,'show_user_profile') );

            add_action( 'show_user_profile',array($this,'show_user_certificates')  );
            add_action( 'edit_user_profile', array($this,'show_user_certificates') );

            add_action( 'show_user_profile',array($this,'show_user_partner_service')  );
            add_action( 'edit_user_profile', array($this,'show_user_partner_service') );

            add_action( 'personal_options_update', array($this,'personal_options_update') );
            add_action( 'edit_user_profile_update', array($this,'personal_options_update') );


            add_action('admin_menu', array($this,'st_users_partner_menu'));

            add_action('set_user_role',array($this,'_st_check_change_role'),999,3);

            //Check booking edit and redirect
            if (self::is_admin_user_page()) {
                add_action('admin_enqueue_scripts', array(__CLASS__, 'add_edit_scripts'));
            }


            add_action( 'wp_ajax_st_load_more_service_partner' , array( $this , '_load_more_service_partner' ) );
            add_action( 'wp_ajax_nopriv_st_load_more_service_partner' , array( $this , '_load_more_service_partner' ) );

        }
        static function is_admin_user_page()
        {
            if (is_admin() and isset($_GET['page']) and $_GET['page'] == 'st-users-partner-static-menu') return TRUE;
            if (is_admin() and isset($_GET['page']) and $_GET['page'] == 'st-users-top-partner-menu') return TRUE;
            if (is_admin() and isset($_GET['page']) and $_GET['page'] == 'st-users-list-partner-menu') return TRUE;
            if (is_admin() and isset($_GET['page']) and $_GET['page'] == 'st-users-partner-withdrawal-menu') return TRUE;

            return FALSE;
        }
        static function  add_edit_scripts()
        {
            wp_enqueue_script('select2');
            wp_enqueue_script('st-jquery-ui-datepicker',get_template_directory_uri().'/js/jquery-ui.js');
            wp_enqueue_style('jjquery-ui.theme.min.css', get_template_directory_uri() . '/css/admin/jquery-ui.min.css');
            wp_enqueue_script( 'thickbox' );
            wp_enqueue_style( 'thickbox' );
            wp_enqueue_style('bootstrap.css', get_template_directory_uri() . '/inc/css/bootstrap_admin.css');
            wp_enqueue_script('Chart.min.js', get_template_directory_uri() . '/inc/plugins/chart-master/Chart.js', array('jquery'), null, true);

            wp_localize_script('jquery', 'st_params_partner', array(
                'ajax_url'        => admin_url('admin-ajax.php'),
                'loading_url'     => admin_url('/images/wpspin_light.gif'),
                'text_loading'        => __("Loading...",ST_TEXTDOMAIN),
                'text_no_more'        => __("No More",ST_TEXTDOMAIN),
            ));


        }
        function _st_check_change_role($user_id,$role_new,$role_old){
            $role_old = array_shift($role_old);
            if($role_new == "partner" and $role_old == "subscriber"){
                update_user_meta($user_id,'st_pending_partner','0');
                //$st_certificates = get_user_meta($user_id , 'st_certificates' , true);
                //update_user_meta($user_id,'st_partner_service',$st_certificates);
                if(!get_user_meta($user_id , 'st_partner_approved_date' , true)){
                    $date = date('Y-m-d');
                    update_user_meta($user_id,'st_partner_approved_date',$date);
                }
                STUser::_send_approved_customer_register_partner($user_id);
            }
        }


        function st_users_partner_menu() {
            if(current_user_can('manage_options') && class_exists('STTravelCode')) {

                add_menu_page(
                    __( 'Partner Statistic', ST_TEXTDOMAIN ),
                    __( 'Partner Statistic', ST_TEXTDOMAIN ),
                    'manage_options',
                    'st-users-partner-static-menu',
                    array(
                        $this ,
                        'st_callback_user_partner_static_function'
                    )
                    ,
                    'dashicons-admin-users',
                    35
                );

                add_submenu_page('st-users-partner-static-menu', __('List Partner', ST_TEXTDOMAIN), __('List Partner', ST_TEXTDOMAIN), 'manage_options', 'st-users-list-partner-menu', array($this, 'st_callback_user_partner_function'));

                add_submenu_page('st-users-partner-static-menu', __('Top Partner', ST_TEXTDOMAIN), __('Top Partner', ST_TEXTDOMAIN), 'manage_options', 'st-users-top-partner-menu', array($this, 'st_callback_user_partner_top_function'));


                /* add_users_page( 'Users Partner' , 'Partner Static' , 'read' , 'st-users-partner-menu' , array(
                     $this ,
                     'st_callback_user_partner_function'
                 ) );*/
            }
        }


        function st_callback_user_partner_static_function(){
            echo balanceTags($this->load_view('users/partner_static',false));
        }

        function st_callback_user_partner_function(){
            $action=STInput::request('st_action',false);
            switch($action){
                case "delete":
                    //$this->_delete_items();
                    break;
                case "approve_role":
                    $user_id = STInput::request('user_id');
                    if(!empty($user_id)){
                        $user_data = new WP_User( $user_id );
                        $user__permission = array_shift($user_data->roles);
                        if($user__permission == "subscriber" or $user__permission == "" or $user__permission == "Subscriber" or $user__permission == "partner"){
                            if(!empty($user_data->roles)){
                                foreach($user_data->roles as $k=>$v){
                                    $user_data->remove_role( $v );
                                }
                            }

                            $user_data = new WP_User( $user_id );
                            $user_data->remove_role( $user__permission );
                            $user_data->add_role( 'partner' );
                            update_user_meta($user_id,'st_pending_partner','0');
                            if(!get_user_meta($user_id , 'st_partner_approved_date' , true)){
                                $date = date('Y-m-d');
                                update_user_meta($user_id,'st_partner_approved_date',$date);
                            }
                            $st_certificates = get_user_meta($user_id , 'st_certificates' , true);
                            update_user_meta($user_id,'st_partner_service',$st_certificates);

                            STAdmin::set_message(__("Approve success !",ST_TEXTDOMAIN),'updated');
                            // send email
                            STUser::_send_approved_customer_register_partner($user_id);
                        }
                        unset( $user_data );
                    }
                    break;
                case "cancel_role":
                    $user_id = STInput::request('user_id');
                    if(!empty($user_id)){
                        update_user_meta($user_id,'st_pending_partner','0');
                        STAdmin::set_message(__("Cancel success !",ST_TEXTDOMAIN),'updated');
                        // send email
                        STUser::_send_cancel_customer_register_partner($user_id);
                    }
                    break;
            }
            echo balanceTags($this->load_view('users/partner_index',false));
        }
        function st_callback_user_partner_top_function(){
            $action=STInput::request('st_action',false);
            echo balanceTags($this->load_view('users/partner_top',false));
        }
        function _delete_items(){

            if ( empty( $_POST ) or  !check_admin_referer( 'shb_action', 'shb_field' ) ) {
                //// process form data, e.g. update fields
                return;
            }
            $ids=isset($_POST['users'])?$_POST['users']:array();
            if(!empty($ids))
            {
                foreach($ids as $id)
                    wp_delete_user($id,true);

            }

            STAdmin::set_message(__("Delete item(s) success",ST_TEXTDOMAIN),'updated');

        }

        function show_user_profile($user)
        {
            echo balanceTags($this->load_view('users/profile',null,array('user'=>$user,'extra_fields'=>$this->extra_fields)));
        }

        function show_user_certificates($user)
        {
            echo balanceTags($this->load_view('users/certificates',null,array('user'=>$user)));

        }

        function show_user_partner_service($user){
            echo balanceTags($this->load_view('users/partner_service',null,array('user'=>$user)));
        }


        static function get_list_partner( $permission = "partner" , $offset , $limit )
        {
            global $wpdb;
            $where  = '';
            $join   = '';
            if($permission == "partner"){
                $join .= " INNER JOIN {$wpdb->prefix}usermeta ON ( {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id ) ";
                $where .= " AND
                            ( {$wpdb->prefix}usermeta.meta_key = '{$wpdb->prefix}capabilities' AND CAST({$wpdb->prefix}usermeta.meta_value AS CHAR) LIKE '%\"partner\"%' )";
            }
            if($permission == "partner_pending"){
                $join .= " INNER JOIN {$wpdb->prefix}usermeta ON ( {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id )
                           INNER JOIN {$wpdb->prefix}usermeta AS mt1 ON ( {$wpdb->prefix}users.ID = mt1.user_id )";
                $where .= " AND
                            (
                                (
                                    (
                                        ( {$wpdb->prefix}usermeta.meta_key = 'st_pending_partner' AND CAST({$wpdb->prefix}usermeta.meta_value AS CHAR) = '1' )
                                    )
                                    AND
                                    (
                                        (
                                            ( mt1.meta_key = '{$wpdb->prefix}capabilities' AND CAST(mt1.meta_value AS CHAR) LIKE '%\"Subscriber\"%' )
                                        )
                                    )
                                )
                            )";
            }
            if($permission == "partner_update"){
                $join .= " INNER JOIN {$wpdb->prefix}usermeta ON ( {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id )
                           INNER JOIN {$wpdb->prefix}usermeta AS mt1 ON ( {$wpdb->prefix}users.ID = mt1.user_id )";
                $where .= " AND
                            (
                                (
                                    (
                                        ( {$wpdb->prefix}usermeta.meta_key = 'st_pending_partner' AND CAST({$wpdb->prefix}usermeta.meta_value AS CHAR) = '2' )
                                    )
                                    AND
                                    (
                                        (
                                            ( mt1.meta_key = '{$wpdb->prefix}capabilities' AND CAST(mt1.meta_value AS CHAR) LIKE '%\"partner\"%' )
                                        )
                                    )
                                )
                            )";
            }
            if($c_name = STInput::request('st_custommer_name')){

                $where .= "
                AND (  {$wpdb->users}.user_login LIKE '%{$c_name}%'
                    OR {$wpdb->users}.user_email LIKE '%{$c_name}%'
                    OR {$wpdb->users}.user_nicename LIKE '%{$c_name}%'
                    OR {$wpdb->users}.display_name LIKE '%{$c_name}%')
                ";

            }

            $querystr = "
                SELECT SQL_CALC_FOUND_ROWS {$wpdb->prefix}users.* FROM {$wpdb->prefix}users
                {$join}
                WHERE 1=1
                " . $where . "
                ORDER BY user_registered DESC
                LIMIT {$offset},{$limit}
            ";
            $pageposts = $wpdb->get_results( $querystr , OBJECT );
            return array( 'total' => $wpdb->get_var( "SELECT FOUND_ROWS();" ) , 'rows' => $pageposts );
        }

        static function _admin_get_fist_date_approved_user_partner(){
            global $wpdb;
            $querystr = "
                    SELECT SQL_CALC_FOUND_ROWS mt1.meta_value AS st_fist_date FROM {$wpdb->prefix}users

                    INNER JOIN {$wpdb->prefix}usermeta ON ( {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id  )

                    INNER JOIN {$wpdb->prefix}usermeta as mt1 ON ( {$wpdb->prefix}users.ID = mt1.user_id ) and mt1.meta_key = 'st_partner_approved_date'

                    WHERE 1=1

                    AND ( {$wpdb->prefix}usermeta.meta_key = '{$wpdb->prefix}capabilities' AND CAST({$wpdb->prefix}usermeta.meta_value AS CHAR) LIKE '%\"partner\"%' )

                    ORDER BY st_fist_date ASC LIMIT 1
                ";

            $rs = $wpdb->get_row( $querystr , OBJECT );
            if(!empty($rs)){
                return date_i18n('Y',strtotime($rs->st_fist_date));
            }else{
                return date_i18n('Y');
            }
        }

        /***
         *
         *
         * since 1.2.7
         * by quandq ahii
         */
        static function _admin_get_data_chart_number_user_partner(){
            $st_month = STInput::request('st_month',date('m'));
            $st_year = STInput::request('st_year',date('Y'));
            if($st_year == "all"){
                $data_default = array();
                global $wpdb;
                $querystr = "
                                SELECT SQL_CALC_FOUND_ROWS {$wpdb->prefix}users.* ,mt1.meta_value as st_date FROM {$wpdb->prefix}users

                                INNER JOIN {$wpdb->prefix}usermeta ON ( {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id  )

                                INNER JOIN {$wpdb->prefix}usermeta as mt1 ON ( {$wpdb->prefix}users.ID = mt1.user_id ) and mt1.meta_key = 'st_partner_approved_date'

                                WHERE 1=1

                                AND ( {$wpdb->prefix}usermeta.meta_key = '{$wpdb->prefix}capabilities' AND CAST({$wpdb->prefix}usermeta.meta_value AS CHAR) LIKE '%\"partner\"%' )

                                GROUP BY {$wpdb->prefix}users.ID

                                ORDER BY
                                mt1.meta_value ASC
                            ";
                $total = 0;
                $pageposts = $wpdb->get_results( $querystr , OBJECT );
                if(!empty($pageposts)){
                    foreach($pageposts as $key=>$value){
                        $tmp_date = date("Y",strtotime($value->st_date));
                        if(empty($data_default[$tmp_date]['number'])){
                            $data_default[$tmp_date]['number'] = 0;
                        }
                        $data_default[$tmp_date]['number'] += 1;
                        $total ++;
                    }
                }
                $data_lable = '[';
                $data_number = '[';
                foreach($data_default as $k=>$v){
                    $date = $k;
                    $data_lable .='"'.$date.'",';
                    $data_number .='"'.$v['number'].'",';
                }
                $data_lable = substr($data_lable , 0 ,-1);
                $data_number = substr($data_number , 0 ,-1);
                $data_lable .= ']';
                $data_number .= ']';
                return array('total'=>$total,'php'=>$data_default,'js'=>array('lable'=>$data_lable,'number'=>$data_number));
            }else{
                if($st_month == 'all') {
                    $date_start = date($st_year.'-01-01');
                    $date_end = date('Y-m-t',strtotime(date($st_year.'-12-01')));
                    $data_default = array();
                    for( $j = 1 ; $j <= 12 ; $j++ ) {
                        if($j < 10){
                            $data_default[ '0'.$j ] = array(
                                'number'  => 0 ,
                            );
                        }else{
                            $data_default[ $j ] = array(
                                'number'  => 0 ,
                            );
                        }
                    }
                    global $wpdb;
                    $querystr = "
                            SELECT SQL_CALC_FOUND_ROWS {$wpdb->prefix}users.* ,mt1.meta_value as st_date FROM {$wpdb->prefix}users

                            INNER JOIN {$wpdb->prefix}usermeta ON ( {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id  )

                            INNER JOIN {$wpdb->prefix}usermeta as mt1 ON ( {$wpdb->prefix}users.ID = mt1.user_id ) and mt1.meta_key = 'st_partner_approved_date'

                            WHERE 1=1

                            AND ( {$wpdb->prefix}usermeta.meta_key = '{$wpdb->prefix}capabilities' AND CAST({$wpdb->prefix}usermeta.meta_value AS CHAR) LIKE '%\"partner\"%' )

                            AND mt1.meta_value >= '{$date_start}'

                            AND mt1.meta_value <= '{$date_end}'

                            GROUP BY {$wpdb->prefix}users.ID
                        ";
                    $total = 0;
                    $pageposts = $wpdb->get_results( $querystr , OBJECT );
                    if(!empty($pageposts)){
                        foreach($pageposts as $key=>$value){
                            $tmp_date = date("m",strtotime($value->st_date));
                            $data_default[$tmp_date]['number'] += 1;
                            $total ++;
                        }

                    }
                    $data_lable = '[';
                    $data_number = '[';
                    foreach($data_default as $k=>$v){
                        $date = date("F", strtotime($st_year."-".$k.'-01'));
                        $data_lable .='"'.$date.'",';
                        $data_number .='"'.$v['number'].'",';
                    }
                    $data_lable = substr($data_lable , 0 ,-1);
                    $data_number = substr($data_number , 0 ,-1);
                    $data_lable .= ']';
                    $data_number .= ']';
                    return array('total'=>$total,'php'=>$data_default,'js'=>array('lable'=>$data_lable,'number'=>$data_number));
                }else{
                    $date_start = date($st_year.'-'.$st_month.'-01');
                    $date_end = date('Y-m-t',strtotime($date_start));
                    $this_month = date('m');
                    $data_default = array();
                    if($this_month == 2)
                        $day = 28; else $day = 31;
                    for( $j = 1 ; $j <= $day ; $j++ ) {
                        if($j < 10){
                            $data_default[ '0'.$j ] = array(
                                'number'  => 0 ,
                            );
                        }else{
                            $data_default[ $j ] = array(
                                'number'  => 0 ,
                            );
                        }
                    }
                    global $wpdb;
                    $querystr = "
                                SELECT SQL_CALC_FOUND_ROWS {$wpdb->prefix}users.* ,mt1.meta_value as st_date FROM {$wpdb->prefix}users

                                INNER JOIN {$wpdb->prefix}usermeta ON ( {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id  )

                                INNER JOIN {$wpdb->prefix}usermeta as mt1 ON ( {$wpdb->prefix}users.ID = mt1.user_id ) and mt1.meta_key = 'st_partner_approved_date'

                                WHERE 1=1

                                AND ( {$wpdb->prefix}usermeta.meta_key = '{$wpdb->prefix}capabilities' AND CAST({$wpdb->prefix}usermeta.meta_value AS CHAR) LIKE '%\"partner\"%' )

                                AND mt1.meta_value >= '{$date_start}'

                                AND mt1.meta_value <= '{$date_end}'

                                GROUP BY {$wpdb->prefix}users.ID
                            ";
                    $total = 0;
                    $pageposts = $wpdb->get_results( $querystr , OBJECT );
                    if(!empty($pageposts)){
                        foreach($pageposts as $key=>$value){
                            $tmp_date = date("d",strtotime($value->st_date));
                            $data_default[$tmp_date]['number'] += 1;
                            $total ++;
                        }
                    }
                    $data_lable = '[';
                    $data_number = '[';
                    foreach($data_default as $k=>$v){
                        $date = $k;
                        $data_lable .='"'.$date.'",';
                        $data_number .='"'.$v['number'].'",';
                    }
                    $data_lable = substr($data_lable , 0 ,-1);
                    $data_number = substr($data_number , 0 ,-1);
                    $data_lable .= ']';
                    $data_number .= ']';
                    return array('total'=>$total,'php'=>$data_default,'js'=>array('lable'=>$data_lable,'number'=>$data_number));
                }

            }
        }

        static function _admin_get_list_top_partner( $offset , $limit )
        {
            global $wpdb;
            $where = "";
            if($c_name = STInput::request('st_custommer_name')){

                $where .= "
                AND (  {$wpdb->users}.user_login LIKE '%{$c_name}%'
                    OR {$wpdb->users}.user_email LIKE '%{$c_name}%'
                    OR {$wpdb->users}.user_nicename LIKE '%{$c_name}%'
                    OR {$wpdb->users}.display_name LIKE '%{$c_name}%')
                ";

            }
            $querystr = "SELECT SQL_CALC_FOUND_ROWS
                            {$wpdb->prefix}users.user_login,
                            {$wpdb->prefix}users.user_email,
                            {$wpdb->prefix}users.user_nicename,
                            {$wpdb->prefix}users.display_name,
                            {$wpdb->prefix}st_withdrawal.*,
                        SUM({$wpdb->prefix}st_withdrawal.price) AS total_price
                        FROM
                            {$wpdb->prefix}st_withdrawal
                        INNER JOIN {$wpdb->prefix}users ON {$wpdb->prefix}users.ID = {$wpdb->prefix}st_withdrawal.user_id
                        WHERE
                            1 = 1
                        AND {$wpdb->prefix}st_withdrawal.status = 'completed'
                            {$where}
                        GROUP BY
                            {$wpdb->prefix}st_withdrawal.user_id
            ";
            $pageposts = $wpdb->get_results( $querystr , OBJECT );
            return array( 'total' => $wpdb->get_var( "SELECT FOUND_ROWS();" ) , 'rows' => $pageposts );
        }

        static function _send_admin_new_register_partner($user_id){
            global $st_user_id;
            $st_user_id = $user_id;
            $admin_email = st()->get_option('email_admin_address');
            if(!$admin_email) return false;
            $to = $admin_email;
            if($user_id){
                $message = st()->load_template('email/header');
                $email_to = st()->get_option('partner_email_for_admin', '');
                $message .= do_shortcode($email_to);
                $message .= st()->load_template('email/footer');
                $user_data = get_userdata( $user_id );
                $title = $user_data->user_nicename." - ".$user_data->user_email." - ".$user_data->user_registered;
                $subject = sprintf(__('New Register Partner: %s',ST_TEXTDOMAIN), $title);
                $check = self::_send_mail_user($to, $subject, $message);
            }
            unset($st_user_id);
            return $check;
        }
        static function _send_admin_new_register_user($user_id){

            global $st_user_id;
            $st_user_id = $user_id;
            $admin_email = st()->get_option('email_admin_address');
            if(!$admin_email) return false;
            $to = $admin_email;
            if($user_id){
                $message = st()->load_template('email/header');
                $email_to = st()->get_option('user_register_email_for_admin', '');
                $message .= do_shortcode($email_to);
                $message .= st()->load_template('email/footer');
                $title =  wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
                $subject = sprintf(__('[%s] New User Registration',ST_TEXTDOMAIN), $title);
                $check = self::_send_mail_user($to, $subject, $message);
            }
            unset($st_user_id);
            return $check;
        }

        static function _resend_send_admin_update_certificate_partner($user_id){
            global $st_user_id;
            $st_user_id = $user_id;
            $admin_email = st()->get_option('email_admin_address');
            if(!$admin_email) return false;
            $to = $admin_email;
            if($user_id){
                $message = st()->load_template('email/header');
                $email_to = st()->get_option('partner_resend_email_for_admin', '');
                $message .= do_shortcode($email_to);
                $message .= st()->load_template('email/footer');
                $user_data = get_userdata( $user_id );
                $title = $user_data->user_nicename." - ".$user_data->user_email;
                $subject = sprintf(__('New Update Certificate Partner: %s',ST_TEXTDOMAIN), $title);
                $check = self::_send_mail_user($to, $subject, $message);
            }
            unset($st_user_id);
            return $check;
        }
        static function _send_customer_register_partner($user_id){
            global $st_user_id;
            $st_user_id = $user_id;
            $user_data = get_userdata( $user_id );
            $user_email = $user_data->user_email;
            if(!$user_email) return false;
            $to = $user_email;
            $message = st()->load_template('email/header');
            $email_to = st()->get_option('partner_email_for_customer', '');
            $message .= do_shortcode($email_to);
            $message .= st()->load_template('email/footer');
            $title = get_option('blogname');
            $subject = sprintf(__('Your partner registration at %s is approved!',ST_TEXTDOMAIN), $title);
            $check = self::_send_mail_user($to, $subject, $message);
            unset($st_user_id);
            return $check;
        }
        static function _send_approved_customer_register_partner($user_id){
            global $st_user_id;
            $st_user_id = $user_id;
            $user_data = get_userdata( $user_id );
            $user_email = $user_data->user_email;
            if(!$user_email) return false;
            $to = $user_email;
            $message = st()->load_template('email/header');
            $email_to = st()->get_option('partner_email_approved', '');
            $message .= do_shortcode($email_to);
            $message .= st()->load_template('email/footer');
            $title = get_option('blogname');
            $subject = sprintf(__(' %s Partner Registration Has Been Received and Welcome to %s! ',ST_TEXTDOMAIN), $title,$title);
            $check = self::_send_mail_user($to, $subject, $message);
            unset($st_user_id);
            return $check;
        }
        static function _send_cancel_customer_register_partner($user_id){
            global $st_user_id;
            $st_user_id = $user_id;
            $user_data = get_userdata( $user_id );
            $user_email = $user_data->user_email;
            if(!$user_email) return false;
            $to = $user_email;
            $message = st()->load_template('email/header');
            $email_to = st()->get_option('partner_email_cancel', '');
            $message .= do_shortcode($email_to);
            $message .= st()->load_template('email/footer');
            $title = get_option('blogname');
            $subject = sprintf(__('%s Partner Registration Has Been Cancel! ',ST_TEXTDOMAIN), $title);
            $check = self::_send_mail_user($to, $subject, $message);
            unset($st_user_id);
            return $check;
        }
        private static function _send_mail_user($to, $subject, $message, $attachment=false){
            if(!$message) return array(
                'status'  => false,
                'data'    => '',
                'message' => __("Email content is empty",ST_TEXTDOMAIN)
            );
            $from = st()->get_option('email_from');
            $from_address = st()->get_option('email_from_address');
            $headers = array();

            if($from and $from_address){
                $headers[]='From:'. $from .' <'.$from_address.'>';
            }
            add_filter( 'wp_mail_content_type', array(__CLASS__,'set_html_content_type') );
            $check=wp_mail( $to, $subject, $message,$headers ,$attachment);
            remove_filter( 'wp_mail_content_type', array(__CLASS__,'set_html_content_type') );
            return array(
                'status'=>$check,
                'data'=>array(
                    'to'=>$to,
                    'subject'=>$subject,
                    'message'=>$message,
                    'headers'=>$headers
                )
            );
        }
        static function set_html_content_type() {
            return 'text/html';
        }
        function personal_options_update($user_id)
        {
            if ( !current_user_can( 'edit_user', $user_id ) )
                return false;

            if(!empty($this->extra_fields))
            {

                foreach($this->extra_fields as $key=> $value)
                {
                    update_user_meta($user_id, $key, sanitize_text_field($_POST[$key]));
                }

                //Update service partner
                $user_data = new WP_User( $user_id );

                $user__permission = array_shift($user_data->roles);
                if($user__permission == "partner"){
                    $data_service_new = STInput::request('st_partner_service');
                    $st_partner_service = array();
                    if(!empty($data_service_new)){
                        foreach($data_service_new as $k=>$v){
                            $st_partner_service[$v]=array('name'=>$v,'image'=>'');
                        }
                    }
                    $data_service_old = get_user_meta($user_id,'st_partner_service',true);
                    if(!empty($data_service_old) and !empty($st_partner_service)){
                        $check = true;
                        foreach($data_service_old as $k=>$v){
                            if(empty($st_partner_service[$k])){
                                $check = false;
                            }
                        }
                        foreach($st_partner_service as $k=>$v){
                            if(empty($data_service_old[$k])){
                                $check = false;
                            }
                        }
                        if($check == false){
                            STUser::_send_approved_customer_register_partner($user_id);
                            if(!get_user_meta($user_id , 'st_partner_approved_date' , true)){
                                $date = date('Y-m-d');
                                update_user_meta($user_id,'st_partner_approved_date',$date);
                            }
                        }
                    }
                    if(empty($st_partner_service)){
                        STUser::_send_cancel_customer_register_partner($user_id);
                    }
                    update_user_meta($user_id,'st_pending_partner','0');
                    update_user_meta($user_id, 'st_partner_service', $st_partner_service);
                }

            }
            //$st_certificates = STInput::request('st_partner_service');
            //update_user_meta($user_id, 'st_certificates', $st_certificates);
        }

        static function count_comment($user_id=false,$post_id=false,$comment_type=false)
        {
            if(!$user_id)
            {
                if(is_user_logged_in())
                {

                    global $current_user;

                    $user_id=$current_user->ID;
                }
            }

            if($user_id)
            {
                global $wpdb;

                $query='SELECT COUNT(comment_ID) FROM ' . $wpdb->comments. ' WHERE user_id = "' . sanitize_title_for_query($user_id) . '"';


                if($post_id)
                {
                    $query.=' AND comment_post_ID="'.sanitize_title_for_query($post_id).'"';
                }

                if($comment_type)
                {
                    $query.=' AND comment_type="'.sanitize_title_for_query($comment_type).'"';
                }

                $count = $wpdb->get_var($query);


                return $count;
            }
        }
        static function count_comment_by_email($email,$post_id=false,$comment_type=false)
        {
            if(!$email)
                return  0 ; 

            if($email)
            {
                global $wpdb;

                $query='SELECT COUNT(comment_ID) FROM ' . $wpdb->comments. ' WHERE comment_author_email = "' . $email . '"';


                if($post_id)
                {
                    $query.=' AND comment_post_ID="'.sanitize_title_for_query($post_id).'"';
                }

                if($comment_type)
                {
                    $query.=' AND comment_type="'.sanitize_title_for_query($comment_type).'"';
                }

                $count = $wpdb->get_var($query);


                return $count;
            }
        }

        static function count_review($user_id=false,$post_id=false)
        {
            return self::count_comment($user_id,$post_id,"st_reviews");
        }
        static function count_review_by_email($email,$post_id=false)
        {
            return self::count_comment_by_email($email,$post_id,"st_reviews");
        }

        static function _load_more_service_partner($post_type = false , $user_id = false){
            if(STInput::request('st_post_type')){
                $post_type = STInput::request('st_post_type');
            }
            if(STInput::request('st_user_id')){
                $user_id = STInput::request('st_user_id');
            }
            if(!empty($post_type) and !empty($user_id)){
                $paged = 1;
                $limit = 10;
                if(!empty( $_REQUEST[ 'st_paged' ] )) {
                    $paged = $_REQUEST[ 'st_paged' ];
                }
                $offset = ($paged-1) * $limit;
                global $wpdb;

                $querystr = "SELECT SQL_CALC_FOUND_ROWS *
                        FROM
                            {$wpdb->prefix}posts
                        WHERE 1 = 1

                        AND {$wpdb->prefix}posts.post_author = {$user_id}

                        AND {$wpdb->prefix}posts.post_type = '{$post_type}'

                        ORDER BY
                            {$wpdb->prefix}posts.post_date DESC
                        LIMIT {$offset},{$limit}";
                $pageposts = $wpdb->get_results( $querystr , OBJECT );
                $number_post = $wpdb->get_var( "SELECT FOUND_ROWS();" );
                $html = '';
                if(!empty($pageposts)){
                    foreach($pageposts as $k=>$v){
                        $post_id = $v->ID;
                        $address = get_post_meta($post_id,'address',true);
                        switch($post_type){
                            case "st_hotel":
                                $price  = TravelHelper::format_money(STHotel::get_price($post_id));
                                break;
                            case "st_rental":
                                $price  = TravelHelper::format_money(get_post_meta( $post_id , 'price' , true ));
                                break;
                            case "st_tours":
                                $adult_price  = TravelHelper::format_money(get_post_meta( $post_id , 'adult_price' , true ));
                                $child_price  = TravelHelper::format_money(get_post_meta( $post_id , 'child_price' , true ));
                                $infant_price  = TravelHelper::format_money(get_post_meta( $post_id , 'infant_price' , true ));
                                $price = "
                                	".__("Adult Price",ST_TEXTDOMAIN).": {$adult_price} <br>
                                	".__("Child Price",ST_TEXTDOMAIN).": {$child_price} <br>
                                	".__("Infant Price",ST_TEXTDOMAIN).": {$infant_price}";
                                break;
                            case "st_cars":
                                $price  = TravelHelper::format_money(get_post_meta( $post_id , 'cars_price' , true ));
                                $address = get_post_meta($post_id,'cars_address',true);
                                break;
                            case "st_activity":
                                $adult_price  = TravelHelper::format_money(get_post_meta( $post_id , 'adult_price' , true ));
                                $child_price  = TravelHelper::format_money(get_post_meta( $post_id , 'child_price' , true ));
                                $infant_price  = TravelHelper::format_money(get_post_meta( $post_id , 'infant_price' , true ));
                                $price = "
                                	".__("Adult Price",ST_TEXTDOMAIN).": {$adult_price} <br>
                                	".__("Child Price",ST_TEXTDOMAIN).": {$child_price} <br>
                                	".__("Infant Price",ST_TEXTDOMAIN).": {$infant_price}";
                                break;
                        }
                        $date_create = date_i18n(TravelHelper::getDateFormat(),strtotime($v->post_date));
                        $status = $v->post_status;
                        $img = get_the_post_thumbnail( $post_id , array(50,50)) ;
                        if(empty($img)){
                            $img = '<img width="50" height="50" alt="no-image" class="wp-post-image" src="'.bfi_thumb(get_template_directory_uri().'/img/no-image.png',array('width'=>50,'height'=>50)) .'">';
                        }
                        $title = "<a href=".admin_url("post.php?post={$post_id}&action=edit").">".$v->post_title."</a>";
                        $number = $offset + $k + 1;
                        $html .= "
                                    <tr class='post-id-{$post_id}'>
                                        <td style='text-align: center'>{$number}</td>
                                        <td>{$img}</td>
                                        <td>{$title}</td>
                                        <td>{$price}</td>
                                        <td>{$address}</td>
                                        <td>{$date_create}</td>
                                        <td >{$status}</td>
                                    </tr>";
                    }
                }
                if(STInput::request('st_show') == 'true'){
                    echo json_encode(array('data_html'=>$html));
                    die();
                }else{
                    return array("html"=>$html,"number_post"=>$number_post);
                }

            }
        }
    }

    $User=new STUser();

    $User->init();
}