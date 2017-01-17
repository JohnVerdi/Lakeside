<?php
/*
 * Template Name: User Dashboard
*/
get_header();
$user_link = get_permalink();
$current_user = wp_get_current_user();
$lever = $current_user->roles;
$lever = array_shift($lever);
$url_id_user = '';
if(!empty( $_REQUEST[ 'id_user' ] )) {
    $id_user_tmp  = $_REQUEST[ 'id_user' ];
    $current_user = get_userdata( $id_user_tmp );
    $url_id_user  = $id_user_tmp;
}
$default_page = "setting";
if(STUser_f::check_lever_partner($lever ) and st()->get_option( 'partner_enable_feature' ) == 'on'){
    $default_page = "dashboard";
}
$sc = STInput::request( 'sc' , $default_page );

?>
<?php if($sc == "details-owner"){ ?>
    <?php echo st()->load_template( 'user/user' , $sc ); ?>
<?php }else{ ?>
    <div class="container bg-partner-new <?php echo esc_html($sc) ?>">
        <div class="row row_content_partner">
            <div class="col-md-3 user-left-menu ">
                <div class="page-sidebar navbar-collapse st-page-sidebar-new">
                    <ul class="page-sidebar-menu st_menu_new">
                        <li class="heading text-center user-profile-sidebar">
                            <div class="user-profile-avatar text-center">
                                <?php echo st_get_profile_avatar($current_user->ID, 300); ?>
                                <h5><?php echo esc_html($current_user->display_name) ?></h5>

                                <p><?php echo st_get_language('user_member_since') . mysql2date(' M Y', $current_user->data->user_registered); ?></p>
                            </div>
                        </li>
                        <?php if(!empty( $_REQUEST[ 'id_user' ] )) { ?>
                            <li class="item <?php if($sc == 'setting-info') echo 'active' ?> ">

                                <a href="<?php echo esc_url( add_query_arg( array(
                                    'sc'      => 'setting-info' ,
                                    'id_user' => $url_id_user
                                ) , $user_link ) ); ?>">
                                    <i class="fa fa-cog"></i>
                                    <span class="title"><?php st_the_language( 'user_settings' ) ?></span>
                                </a>
                            </li>
                        <?php }else{ ?>
                            <?php if(STUser_f::check_lever_partner( $lever ) and st()->get_option( 'partner_enable_feature' ) == 'on'): ?>
                                <li class="item <?php if($sc == 'dashboard' or $sc == 'dashboard-info') echo 'active' ?>">
                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'dashboard' , $user_link ) ) ?>">
                                        <i class="fa fa-cogs"></i>
                                        <span class="title"><?php _e("Dashboard",ST_TEXTDOMAIN) ?></span>
                                        <span class="arrow "></span>
                                    </a>
                                    <ul class="sub-menu item">
                                        <?php if(STUser_f::_check_service_available_partner('st_hotel')):?>
                                            <li class="<?php if($sc == 'dashboard-info' and STInput::request('type') == 'st_hotel' ) echo 'active' ?>">
                                                <a href="<?php echo esc_url( add_query_arg( array('sc'=>'dashboard-info','type'=>'st_hotel') , $user_link ) ) ?>"><i class="fa fa-building-o"> &nbsp; </i><?php _e("Hotel Statistics",ST_TEXTDOMAIN) ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(STUser_f::_check_service_available_partner('st_hotel')):?>
                                            <li class="<?php if($sc == 'dashboard-info' and STInput::request('type') == 'hotel_room' ) echo 'active' ?>">
                                                <a href="<?php echo esc_url( add_query_arg( array('sc'=>'dashboard-info','type'=>'hotel_room') , $user_link ) ) ?>"><i class="fa fa-building-o"> &nbsp; </i><?php _e("Room Statistics",ST_TEXTDOMAIN) ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(STUser_f::_check_service_available_partner('st_rental')):?>
                                            <li class="<?php if($sc == 'dashboard-info' and STInput::request('type') == 'st_rental' ) echo 'active' ?>">
                                                <a href="<?php echo esc_url( add_query_arg( array('sc'=>'dashboard-info','type'=>'st_rental') , $user_link ) ) ?>"> <i class="fa fa-home"> &nbsp; </i><?php _e("Rental Statistics",ST_TEXTDOMAIN) ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(STUser_f::_check_service_available_partner('st_cars')):?>
                                            <li class="<?php if($sc == 'dashboard-info' and STInput::request('type') == 'st_cars' ) echo 'active' ?>">
                                                <a href="<?php echo esc_url( add_query_arg( array('sc'=>'dashboard-info','type'=>'st_cars') , $user_link ) ) ?>"> <i class="fa fa-cab"> &nbsp; </i><?php _e("Car Statistics",ST_TEXTDOMAIN) ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(STUser_f::_check_service_available_partner('st_tours')):?>
                                            <li class="<?php if($sc == 'dashboard-info' and STInput::request('type') == 'st_tours' ) echo 'active' ?>">
                                                <a href="<?php echo esc_url( add_query_arg( array('sc'=>'dashboard-info','type'=>'st_tours') , $user_link ) ) ?>"> <i class="fa fa-flag-o"> &nbsp; </i><?php _e("Tour Statistics",ST_TEXTDOMAIN)?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(STUser_f::_check_service_available_partner('st_activity')):?>
                                            <li class="<?php if($sc == 'dashboard-info' and STInput::request('type') == 'st_activity' ) echo 'active' ?>">
                                                <a href="<?php echo esc_url( add_query_arg( array('sc'=>'dashboard-info','type'=>'st_activity') , $user_link ) ) ?>"> <i class="fa fa-bolt"> &nbsp; </i><?php _e("Activity Statistics",ST_TEXTDOMAIN) ?></a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif ?>
                            <li class="item <?php if($sc == 'setting') echo 'active' ?>">
                                <a href="<?php echo esc_url( add_query_arg( 'sc' , 'setting' , $user_link ) ) ?>"><i
                                        class="fa fa-cog"></i><?php st_the_language( 'user_settings' ) ?></a>
                            </li>
                            <?php
                            $custom_layout = st()->get_option('partner_custom_layout','off');
                            $custom_layout_booking_history = st()->get_option('partner_custom_layout_booking_history','on');
                            if($custom_layout == "off"){
                                $custom_layout_booking_history = "on";
                            }
                            ?>
                            <?php if($custom_layout_booking_history == "on"){ ?>
                            <li class="item <?php if($sc == 'booking-history') echo 'active' ?>">
                                <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-history' , $user_link ) ) ?>"><i
                                        class="fa fa-clock-o"></i><?php st_the_language( 'user_booking_history' ) ?>
                                </a>
                            </li>
                            <?php } ?>


                            <?php if(STUser_f::check_lever_partner( $lever ) and st()->get_option( 'partner_enable_feature' ) == 'on'): ?>

                                <!--<li class="item <?php /*if($sc == 'reports')echo 'active' */?>">
                                    <a href="<?php /*echo esc_url( add_query_arg( 'sc' , 'reports' , $user_link ) ) */?>"><i class="fa fa-book"></i><?php /*_e( "Reports" , ST_TEXTDOMAIN ) */?>
                                    </a>
                                </li>-->

                                    <?php if($lever != "administrator"){  ?>
                                        <li class="item <?php if(in_array($sc,array('withdrawal','withdrawal-history'))) echo "active" ?>">
                                            <a href="<?php echo esc_url( add_query_arg( 'sc' , 'withdrawal' , $user_link ) );?>">
                                                <i class="fa fa-user"></i>
                                                <?php _e("Withdrawal",ST_TEXTDOMAIN) ?>
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="sub-menu item">
                                                <li <?php if($sc == 'withdrawal-history') echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'withdrawal-history' , $user_link ) ) ?>">
                                                        <i class="fa fa-clock-o">&nbsp;</i><?php _e("History",ST_TEXTDOMAIN) ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <?php if(STUser_f::_check_service_available_partner( 'st_hotel' )): ?>
                                        <li class="item <?php if(in_array($sc,array('create-hotel','edit-hotel','my-hotel','create-room','edit-room','my-room','booking-hotel','booking-hotel-room','add-hotel-room-booking','add-hotel-booking'))) echo "active" ?>">
                                            <a class="cursor" style="cursor: pointer !important">
                                                <i class="fa fa-building-o"></i>
                                                <span class="title"><?php _e( 'Hotel' , ST_TEXTDOMAIN ) ?></span>
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="sub-menu item">
                                                <li <?php if($sc == 'create-hotel')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-hotel' , $user_link ) ) ?>"><i class="fa fa-building-o">&nbsp;</i><?php st_the_language( 'user_create_hotel' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-hotel')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-hotel' , $user_link ) ) ?>"><i class="fa fa-building-o">&nbsp;</i><?php st_the_language( 'user_my_hotel' ) ?>
                                                    </a>
                                                </li>

                                                <li <?php if($sc == 'add-hotel-booking')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'add-hotel-booking' , $user_link ) ) ?>"><i class="fa fa-building-o">&nbsp;</i><?php _e( "Add Booking Hotel" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'booking-hotel')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-hotel' , $user_link ) ) ?>"><i class="fa fa-building-o">&nbsp;</i><?php _e( "Booking Hotel" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'create-room')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-room' , $user_link ) ) ?>"><i class="fa fa-hotel">&nbsp;</i><?php st_the_language( 'user_create_room' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-room')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-room' , $user_link ) ) ?>"><i class="fa fa-hotel">&nbsp;</i><?php st_the_language( 'user_my_room' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'add-hotel-room-booking')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'add-hotel-room-booking' , $user_link ) ) ?>"><i class="fa fa-hotel">&nbsp;</i><?php _e( "Add Booking Room" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'booking-hotel-room')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-hotel-room' , $user_link ) ) ?>"><i class="fa fa-hotel">&nbsp;</i><?php _e( "Booking Room" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php endif; ?>

                                    <?php if(STUser_f::_check_service_available_partner( 'st_tours' )): ?>
                                        <li class="item <?php if(in_array($sc,array('create-tours','edit-tours','my-tours','booking-tours'))) echo "active" ?>">
                                            <a class=" cursor" style="cursor: pointer !important">
                                                <i class="fa fa-flag-o"></i>
                                                <?php _e( 'Tour' , ST_TEXTDOMAIN ) ?>
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="sub-menu item">
                                                <li <?php if($sc == 'create-tours')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-tours' , $user_link ) ) ?>"><i
                                                            class="fa fa-flag-o">&nbsp;</i><?php st_the_language( 'user_create_tour' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-tours')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-tours' , $user_link ) ) ?>">
                                                        <i class="fa fa-flag-o">&nbsp;</i><?php st_the_language( 'user_my_tour' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'add-tour-booking')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'add-tour-booking' , $user_link ) ) ?>"><i class="fa fa-flag-o">&nbsp;</i><?php _e( "Add Booking Tour" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'booking-tours')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-tours' , $user_link ) ) ?>"><i
                                                            class="fa fa-flag-o">&nbsp;</i><?php _e( "Tour Bookings" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php endif; ?>

                                    <?php if(STUser_f::_check_service_available_partner( 'st_activity' )): ?>
                                        <li class="item <?php if(in_array($sc,array('create-activity','edit-activity','my-activity','booking-activity'))) echo "active" ?>">
                                            <a class="cursor">
                                                <i class="fa fa-bolt"></i>
                                                <?php _e( 'Activity' , ST_TEXTDOMAIN ) ?>
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="sub-menu item">
                                                <li <?php if($sc == 'create-activity')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-activity' , $user_link ) ) ?>"><i
                                                            class="fa fa-bolt">&nbsp;</i><?php st_the_language( 'user_create_activity' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-activity')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-activity' , $user_link ) ) ?>"><i
                                                            class="fa fa-bolt">&nbsp;</i><?php st_the_language( 'user_my_activity' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'add-activity-booking')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'add-activity-booking' , $user_link ) ) ?>"><i class="fa fa-bolt">&nbsp;</i><?php _e( "Add Booking Activity" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'booking-activity')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-activity' , $user_link ) ) ?>"><i
                                                            class="fa fa-bolt">&nbsp;</i><?php _e( "Activity Bookings" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php endif; ?>

                                    <?php if(STUser_f::_check_service_available_partner( 'st_cars' )): ?>
                                        <li class="item <?php if(in_array($sc,array('create-cars','edit-cars','my-cars','booking-cars'))) echo "active" ?>">
                                            <a class="cursor" style="cursor: pointer !important">
                                                <i class="fa fa-cab"></i><?php _e( 'Car' , ST_TEXTDOMAIN ) ?>
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="sub-menu item">
                                                <li <?php if($sc == 'create-cars')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-cars' , $user_link ) ) ?>">
                                                        <i class="fa fa-cab">&nbsp;</i><?php st_the_language( 'user_create_car' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-cars')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-cars' , $user_link ) ) ?>">
                                                        <i class="fa fa-cab">&nbsp;</i><?php st_the_language( 'user_my_car' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'add-car-booking')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'add-car-booking' , $user_link ) ) ?>"><i class="fa fa-cab">&nbsp;</i><?php _e( "Add Booking Car" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'booking-cars')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-cars' , $user_link ) ) ?>">
                                                        <i class="fa fa-cab">&nbsp;</i><?php _e( "Car Bookings" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php endif; ?>

                                    <?php if(STUser_f::_check_service_available_partner( 'st_rental' )): ?>
                                        <li class="item <?php if(in_array($sc,array('create-rental','edit-rental','my-rental','create-room-rental','my-room-rental','booking-rental'))) echo "active" ?>">
                                            <a class="cursor" style="cursor: pointer !important">
                                                <i class="fa fa-home"></i></i>
                                                <?php _e( 'Rental' , ST_TEXTDOMAIN ) ?>
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="sub-menu item">
                                                <li <?php if($sc == 'create-rental')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-rental' , $user_link ) ) ?>"><i
                                                            class="fa fa-home">&nbsp;</i><?php st_the_language( 'user_create_rental' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-rental')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-rental' , $user_link ) ) ?>"><i
                                                            class="fa fa-home">&nbsp;</i><?php st_the_language( 'user_my_rental' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'create-room-rental')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-room-rental' , $user_link ) ) ?>"><i
                                                            class="fa fa-hotel">&nbsp;</i><?php echo __( 'Add new Rental Room' , ST_TEXTDOMAIN ); ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-room-rental')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-room-rental' , $user_link ) ) ?>"><i
                                                            class="fa fa-hotel">&nbsp;</i><?php echo __( 'My Rental Room' , ST_TEXTDOMAIN ); ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'add-rental-booking')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'add-rental-booking' , $user_link ) ) ?>"><i class="fa fa-home">&nbsp;</i><?php _e( "Add Booking Rental" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'booking-rental')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-rental' , $user_link ) ) ?>"><i
                                                            class="fa fa-home">&nbsp;</i><?php _e( "Rental Bookings" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php endif; ?>
                                    <?php if( is_super_admin() ): ?>
                                        <li class="item <?php if(in_array($sc,array('list-refund'))) echo "active" ?>">
                                            <a href="<?php echo esc_url( add_query_arg( 'sc' , 'list-refund' , $user_link ) ) ?>" class="cursor" style="cursor: pointer !important">
                                                <i class="fa fa-money"></i></i>
                                                <?php _e( 'Refund Manager' , ST_TEXTDOMAIN ) ?>
                                            </a>
                                        </li>    
                                    <?php endif; ?>

                            <?php endif ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="user-content col-md-9">
                <div class="st-page-bar">
                    <ul class="page-breadcrumb">
                        <?php echo STUser_f::st_get_breadcrumb_partner() ?>
                    </ul>
                </div>
                <?php
                if(!empty( $_REQUEST[ 'id_user' ] )) {
                    echo st()->load_template( 'user/user' , 'setting-info' , get_object_vars( $current_user ) );
                } else {
                    if(STUser_f::check_lever_partner( $lever )){
                        if(STUser_f::check_lever_service_partner($sc,$lever)){
                            switch($sc){
                                case "create-hotel";
                                    $sc = "edit-hotel";
                                    break;
                                case "create-room";
                                    $sc = "edit-room";
                                    break;
                                case "create-rental";
                                    $sc = "edit-rental";
                                    break;
                                case "create-room-rental";
                                    $sc = "edit-room-rental";
                                    break;
                                case "create-tours";
                                    $sc = "edit-tours";
                                    break;
                                case "create-cars";
                                    $sc = "edit-cars";
                                    break;
                                case "create-activity";
                                    $sc = "edit-activity";
                                    break;
                            }
                            echo st()->load_template( 'user/user' , $sc , get_object_vars( $current_user ) );
                        }else{
                            _e("You don't have permission to access this page",ST_TEXTDOMAIN);
                        }
                    }else{
                        if(in_array($sc,array('list-refund',"overview","setting","setting-info","wishlist","booking-history","certificate","write_review"))){
                            echo st()->load_template( 'user/user' , $sc , get_object_vars( $current_user ) );
                        }else{
                            _e("You don't have permission to access this page",ST_TEXTDOMAIN);
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
<?php } ?>
    <div class="gap"></div>
<?php
get_footer();
?>