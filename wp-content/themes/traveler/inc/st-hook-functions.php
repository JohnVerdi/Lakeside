<?php
    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * List all hook function
     *
     * Created by ShineTheme
     *
     */
    if (!function_exists('st_setup_theme')) :
        function st_setup_theme()
        {

            add_role('partner', 'Partner', array(
                'read'                    => true,  // true allows this capability
                'delete_posts'            => true,  // true allows this capability
                'edit_posts'              => true,  // true allows this capability
                'edit_published_posts'    => true,
                'upload_files'            => true,
                'delete_published_posts'  => true,
                'manage_options'          => false,
                'wpcf7_edit_contact_form' => false,
            ));
			// Add caps for Author role
			$role = get_role('partner');
			$role->add_cap('level_2');
			$role->add_cap('level_1');
			$role->add_cap('level_0');
            /*
             * Make theme available for translation.
             * Translations can be filed in the /languages/ directory.
             * If you're building a theme based on stframework, use a find and replace
             * to change $st_textdomain to the name of your theme in all the template files
             */

            // Add default posts and comments RSS feed links to head.
            add_theme_support('automatic-feed-links');

            /*
             * Enable support for Post Thumbnails on posts and pages.
             *
             * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
             */
            //add_theme_support( 'post-thumbnails' );

            // This theme uses wp_nav_menu() in one location.
            register_nav_menus(array(
                'primary' => __('Primary Navigation', ST_TEXTDOMAIN),
            ));


            /*
             * Switch default core markup for search form, comment form, and comments
             * to output valid HTML5.
             */
            add_theme_support('html5', array(
                'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
            ));
            add_theme_support('custom-header', array());
            add_theme_support('custom-background', array());
            add_theme_support('woocommerce');

            /*
             * Enable support for Post Formats.
             * See http://codex.wordpress.org/Post_Formats
             */
            add_theme_support('post-thumbnails');

            add_theme_support('post-formats', array(
                'image', 'video', 'gallery', 'audio', 'quote', 'link'
            ));
            add_theme_support("title-tag");

            load_theme_textdomain(ST_TEXTDOMAIN,get_template_directory().'/language');

            // Setup the WordPress core custom background feature.
//        add_theme_support( 'custom-background', apply_filters( 'stframework_custom_background_args', array(
//            'default-color' => 'ffffff',
//            'default-image' => '',
//        ) ) );
        }
    endif; // stframework_setup


    if (!function_exists('st_add_sidebar')) {
        function st_add_sidebar()
        {
            register_sidebar(array(
                'name'          => __('Blog Sidebar', ST_TEXTDOMAIN),
                'id'            => 'blog-sidebar',
                'description'   => __('Widgets in this area will be shown on all posts and pages.', ST_TEXTDOMAIN),
                'before_title'  => '<h4>',
                'after_title'   => '</h4>',
                'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
                'after_widget'  => '</div>',
            ));

            register_sidebar(array(
                'name'          => __('Page Sidebar', ST_TEXTDOMAIN),
                'id'            => 'page-sidebar',
                'description'   => __('Widgets in this area will be shown on all  pages.', ST_TEXTDOMAIN),
                'before_title'  => '<h4>',
                'after_title'   => '</h4>',
                'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
                'after_widget'  => '</div>',
            ));
            register_sidebar(array(
                'name'          => __('Shop Sidebar', ST_TEXTDOMAIN),
                'id'            => 'shop',
                'description'   => __('Widgets in this area will be shown on all shop page.', ST_TEXTDOMAIN),
                'before_title'  => '<h4 class="shop-widget-title">',
                'after_title'   => '</h4>',
                'before_widget' => '<div id="%1$s" class="sidebar-widget shop-widget %2$s">',
                'after_widget'  => '</div>',
            ));


        }
    }

    if (!function_exists('st_setup_author')) {

        function st_setup_author()
        {
            global $wp_query;

            if ($wp_query->is_author() && isset($wp_query->post)) {
                $GLOBALS['authordata'] = get_userdata($wp_query->post->post_author);
            }
        }

    }

    if (!function_exists('st_wp_title')) {

        function st_wp_title($title)
        {
            if (is_feed()) {
                return $title;
            }

            global $page, $paged;

            // Add the blog name
            //$title = get_bloginfo('name', 'display');

//            // Add the blog description for the home/front page.
//            $site_description = get_bloginfo('description', 'display');
//            if ($site_description && (is_home() || is_front_page())) {
//                $title .= " $sep $site_description";
//            }

            // Add a page number if necessary:
            /*if (($paged >= 2 || $page >= 2) && !is_404()) {
                $title['title'] .= " $sep " . sprintf(__('Page %s', ST_TEXTDOMAIN), max($paged, $page));
            }
*/

            if (is_search()) {
                $post_type = STInput::get('post_type');
                $s = STInput::get('s');
                $location_id = STInput::get('location_id');

                $extra = '';
                if (post_type_exists($post_type)) {
                    $post_type_obj = get_post_type_object($post_type);
                    $extra .= '  ' . $post_type_obj->labels->singular_name;
                }

                $location_name = get_the_title($location_id);

                if ($location_id and $location_name) {
                    $extra .= sprintf(__(' in %s',ST_TEXTDOMAIN),$location_name) ;
                }

                if ($extra) $extra = __('Search ', ST_TEXTDOMAIN) . $extra;


                $title['title'] = $extra;
            }/*

            if (is_page_template('template-user.php')) {
                $title_tmp = get_bloginfo('name', 'display');
                $title['title'] = STUser_f::st_get_title_head_partner()." | ".$title_tmp;
            }*/



            return $title;

        }

    }


    if (!function_exists('st_add_scripts')) {
        function st_add_scripts()
        {
            // if is_singlar
            wp_enqueue_script('comment');
            wp_enqueue_script('comment-reply');
            wp_enqueue_script('st-reviews-form', get_template_directory_uri() . '/js/init/review_form.js', array('jquery'), null, true);
            wp_enqueue_script('bootstrap-traveler', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'), null, true);

            $gg_api_key  = st()->get_option('google_api_key', "");

            if (is_ssl()){
                $url=add_query_arg(array(
                    'v'=>'3', //v=3.exp
                    'signed_in'=>'true',
                    'libraries'=>'places',
                    'language'=>'en',
                    'sensor'=>'false',
                    'key'=> $gg_api_key
                ),'https://maps.googleapis.com/maps/api/js');
            }else {
                $url=add_query_arg(array(
                    'v'=>'3',
                    'signed_in'=>'true',
                    'libraries'=>'places',
                    'language'=>'en',
                    'sensor'=>'false',
                    'key'=> $gg_api_key
                ),'http://maps.googleapis.com/maps/api/js');

            }
            wp_enqueue_script('gmap-apiv3', $url, array('jquery'), null, true);

            //if (is_page_template('template-user.php') or is_singular('location')) {
            wp_enqueue_script('gmapv3',get_template_directory_uri().'/inc/plugins/ot-custom/fields/gmap/js/gmap3.min.js',array('jquery'),false,true);
            wp_enqueue_script('bt-gmapv3-init',get_template_directory_uri().'/inc/plugins/ot-custom/fields/gmap/js/init.js',array('gmapv3'),false,true);
            wp_enqueue_script('template-user-js',get_template_directory_uri().'/js/template-user.js',array('jquery'),false,true);
            wp_enqueue_style('bt-gmapv3',get_template_directory_uri().'/inc/plugins/ot-custom/fields/gmap/css/bt-gmap.css');

            wp_enqueue_script( 'st-moment' , get_template_directory_uri() . '/js/moment.js' , array( 'jquery', ) , null , true );
            wp_enqueue_script( 'st-moment-timezone' , get_template_directory_uri() . '/js/moment-timezone-with-data-2010-2020.js' , array( 'jquery', ) , null , true );
            wp_enqueue_script('slimmenu', get_template_directory_uri() . '/js/jquery.slimmenu.min.js', array('jquery'), null, true);
            //wp_enqueue_script('bootstrap-datepicker.js', get_template_directory_uri() . '/js/bootstrap-datepicker.js', array('jquery'), null, true);
            wp_enqueue_script('bootstrap-timepicker.js', get_template_directory_uri() . '/js/bootstrap-timepicker.js', array('jquery'), null, true);
            wp_enqueue_script('jquery.form', get_template_directory_uri() . '/js/jquery.form.js', array('jquery'), null, true);
            wp_enqueue_script('ionrangeslider.js', get_template_directory_uri() . '/js/ionrangeslider.js', array('jquery'), null, true);
            wp_enqueue_script('icheck.js', get_template_directory_uri() . '/js/icheck.js', array('jquery'), null, true);
            wp_enqueue_script('fotorama.js', get_template_directory_uri() . '/js/fotorama.js', array('jquery'), null, true);
            wp_register_script('handlebars-v2.0.0.js', get_template_directory_uri() . '/js/handlebars-v2.0.0.js', array(), null, true);
            wp_enqueue_script('typeahead.js', get_template_directory_uri() . '/js/typeahead.js', array('jquery', 'handlebars-v2.0.0.js'), null, true);
            wp_enqueue_script('magnific.js', get_template_directory_uri() . '/js/magnific.js', array('jquery'), null, true);
            wp_enqueue_script('owl-carousel.js', get_template_directory_uri() . '/js/owl-carousel.js', array('jquery'), null, true);

            // is_page_template('template-commingsoon.php')  get_post_meta(get_the_ID(),'cs_style',true)
            wp_enqueue_script('syotimer.js', get_template_directory_uri() . '/js/coming-soon/st_tour_ver/jquery.syotimer.js', array('jquery'), null, true);
            wp_enqueue_script('countdown.js', get_template_directory_uri() . '/js/coming-soon/countdown.js', array('jquery'), null, true);

           /** $locales = STOptiontree::get_locales();
            if (!empty($locales) and is_array($locales)){
                foreach ($locales as $locale){
                    //$locale=get_locale();
                    if($locale and $locale!='en') {
                        $date_picker_file_path=get_template_directory(). '/js/locales/bootstrap-datepicker.' . $locale . '.js';
                        if (file_exists($date_picker_file_path)) {
                            wp_enqueue_script('bootstrap-datepicker-'.$locale.'.js', get_template_directory_uri(). '/js/locales/bootstrap-datepicker.' . $locale . '.js', array('jquery'), null, true);
                        }else{
                            $locale = TravelHelper::get_minify_locale($locale);
                            $date_picker_file = get_template_directory() . '/js/locales/bootstrap-datepicker.' . $locale . '.js';
                            if(file_exists($date_picker_file)){
                                wp_enqueue_script('bootstrap-datepicker-'.$locale.'.js', get_template_directory_uri() . '/js/locales/bootstrap-datepicker.' . $locale . '.js', array('jquery'), null, true);
                            }
                        }
                    }
                }
            }**/

            wp_enqueue_script('modernizr', get_template_directory_uri() . '/js/modernizr.js', array('jquery'), null, true);
            wp_register_script('gridrotator.js', get_template_directory_uri() . '/js/gridrotator.js', array('jquery'), null, true);

            wp_enqueue_script('gmap-info-box', get_template_directory_uri() . '/js/infobox.js', array('gmap-apiv3'), null, true);
			wp_enqueue_script('markerclusterer.js', get_template_directory_uri() . '/js/markerclusterer.js', array('jquery'), null, true);
            wp_enqueue_script('gmaplib', get_template_directory_uri() . '/js/gmap3.js', array('gmap-apiv3'), null, true);
            wp_enqueue_script('gmap-init', get_template_directory_uri() . '/js/init/gmap-init.js', array('gmaplib'), null, true);
            wp_enqueue_script('gmap-init-list-map', get_template_directory_uri() . '/js/init/init-list-map.js', array('gmaplib'), null, true);
            wp_enqueue_script('detailed-map', get_template_directory_uri() . '/js/custom_google_map.js', array('gmaplib'), null, true);
            wp_register_script('jquery.noty', get_template_directory_uri() . '/js/noty/packaged/jquery.noty.packaged.min.js', array('jquery'), null, true);
            wp_enqueue_script('chosen.jquery', get_template_directory_uri() . '/js/chosen/chosen.jquery.min.js', array('jquery'), null, true);
            wp_enqueue_script('st.noty', get_template_directory_uri() . '/js/init/class.notice.js', array('jquery', 'jquery.noty'), null, true);
            wp_enqueue_script('moment.js', get_template_directory_uri() . '/js/fullcalendar-2.4.0/lib/moment.min.js', array('jquery'), NULL, TRUE);
            wp_enqueue_script('fullcalendar', get_template_directory_uri() . '/js/fullcalendar-2.4.0/fullcalendar.min.js', array('jquery'), NULL, TRUE);
            wp_enqueue_script('fullcalendar-lang', get_template_directory_uri() . '/js/fullcalendar-2.4.0/lang-all.js', array('jquery'), NULL, TRUE);
            wp_enqueue_style('fullcalendar-css', get_template_directory_uri() . '/js/fullcalendar-2.4.0/fullcalendar.min.css');
            wp_enqueue_style('availability', get_template_directory_uri() . '/css/availability.css');

            // is booking modal
            wp_enqueue_script('booking_modal', get_template_directory_uri() . '/js/init/booking_modal.js', array('jquery'), null, true);

            // is_single
            wp_enqueue_script('gmap-init');
            wp_enqueue_script('hotel-ajax', get_template_directory_uri() . '/js/init/hotel-ajax.js', array('jquery', 'moment.js', 'fullcalendar','date.js','fullcalendar-lang'), '1.1.0', true);
            //wp_enqueue_script('rental-js', get_template_directory_uri() . '/js/init/rental-date-ajax.js', array('jquery', 'moment.js', 'fullcalendar','date.js','fullcalendar-lang'), '1.1.0', true);
            wp_enqueue_script('single-rental-js', get_template_directory_uri() . '/js/init/single-rental.js', array('jquery', 'moment.js', 'fullcalendar','date.js','fullcalendar-lang'), '1.1.0', true);
            wp_enqueue_script('single-hotel-room-js', get_template_directory_uri() . '/js/init/single-hotel-room.js', array('jquery', 'moment.js', 'fullcalendar','date.js','fullcalendar-lang'), '1.1.0', true);
            wp_enqueue_script('single-hotel-js', get_template_directory_uri() . '/js/init/single-hotel.js', array('jquery', 'moment.js', 'fullcalendar','date.js','fullcalendar-lang'), '1.1.0', true);
            wp_enqueue_script('single-tour-js', get_template_directory_uri() . '/js/init/single-tour.js', array('jquery', 'moment.js', 'fullcalendar','date.js','fullcalendar-lang'), '1.1.0', true);
            wp_enqueue_script('single-activity-js', get_template_directory_uri() . '/js/init/single-activity.js', array('jquery', 'moment.js', 'fullcalendar','date.js','fullcalendar-lang'), '1.1.0', true);
            wp_enqueue_script('single-car' , get_template_directory_uri() . '/js/init/single-car.js',array('jquery', 'moment.js', 'fullcalendar','date.js','fullcalendar-lang'), '1.1.0', true);
            wp_enqueue_script('single-location', get_template_directory_uri() . '/js/init/single-location.js', array('jquery', 'moment.js', 'fullcalendar','date.js','fullcalendar-lang'), '1.1.0', true);

            /**
            * @since 1.1.0
            **/
            
            /**if(get_post_type(get_the_ID()) == 'st_rental'){
                wp_enqueue_script('rental-js', get_template_directory_uri() . '/js/init/rental-date-ajax.js', array('jquery'), '1.1.0', true);
            }**/

            /**
            *@since 1.1.7
            **/
            if(is_singular('hotel_room') || is_singular('st_rental') || is_singular('st_tours' ) || is_singular('st_activity')){ 

                wp_enqueue_script('moment.js', get_template_directory_uri() . '/js/fullcalendar-2.4.0/lib/moment.min.js', array('jquery'), NULL, TRUE);
                wp_enqueue_script('fullcalendar', get_template_directory_uri() . '/js/fullcalendar-2.4.0/fullcalendar.min.js', array('jquery'), NULL, TRUE);
                wp_enqueue_script('fullcalendar-lang', get_template_directory_uri() . '/js/fullcalendar-2.4.0/lang-all.js', array('jquery'), NULL, TRUE);
                wp_enqueue_script('fullcalendar-lang', get_template_directory_uri() . '/js/fullcalendar-2.4.0/lang-all.js', array('jquery'), NULL, TRUE);                
                wp_enqueue_style('fullcalendar', get_template_directory_uri() . '/js/fullcalendar-2.4.0/fullcalendar.min.css');
                wp_enqueue_style('availability', get_template_directory_uri() . '/css/availability.css');
            }
            if(is_singular('st_rental')){
                wp_enqueue_script('single-rental-js', get_template_directory_uri() . '/js/init/single-rental.js', array('jquery', 'moment.js', 'fullcalendar','date.js','fullcalendar-lang'), NULL, true);
                }
            if(is_singular('hotel_room')){
                 wp_enqueue_script('single-hotel-room-js', get_template_directory_uri() . '/js/init/single-hotel-room.js', array('jquery', 'moment.js', 'fullcalendar','date.js','fullcalendar-lang'), NULL, true);
                }
            if(is_singular('st_hotel')){
                wp_enqueue_script('single-hotel-js', get_template_directory_uri() . '/js/init/single-hotel.js', array('jquery'), NULL, true);
            }
            if(is_singular('st_tours')){ 
                wp_enqueue_script('single-tour-js', get_template_directory_uri() . '/js/init/single-tour.js', array('jquery'), NULL, true);
            }
            if(is_singular('st_activity')){
                wp_enqueue_script('single-activity-js', get_template_directory_uri() . '/js/init/single-activity.js', array('jquery'), NULL, true);
            }
            /**
            *@since 1.1.3
            **/
            if (st()->get_option('gen_enable_smscroll', 'off') == 'on') {
                wp_enqueue_script('nicescroll.js', get_template_directory_uri() . '/js/nicescroll.js', array('jquery'), null, true);
            }
            wp_enqueue_script('st-qtip',get_template_directory_uri().'/js/jquery.qtip.js',array('jquery'),null,true);
            wp_enqueue_script('date.js', get_template_directory_uri() . '/js/date.js', array('jquery'), null, true);
            wp_enqueue_script('mousewheel.js', get_template_directory_uri() . '/js/jquery.mousewheel-3.0.6.pack.js', array('jquery'), null, true);
            wp_enqueue_script('fancybox.js', get_template_directory_uri() . '/js/jquery.fancybox.js', array('jquery'), null, true);
            wp_enqueue_script('fancybox-buttons.js', get_template_directory_uri() . '/js/helpers/jquery.fancybox-buttons.js', array('jquery'), null, true);
            wp_enqueue_script('fancybox-media.js', get_template_directory_uri() . '/js/helpers/jquery.fancybox-media.js', array('jquery'), null, true);
            wp_enqueue_script('fancybox-thumbs.js', get_template_directory_uri() . '/js/helpers/jquery.fancybox-thumbs.js', array('jquery'), null, true);
            wp_enqueue_script('st-select.js', get_template_directory_uri() . '/js/init/st-select.js', array('jquery'), null, true);
            wp_enqueue_script('custom.js', get_template_directory_uri() . '/js/custom.js', array('jquery', 'st-select.js'), null, true);
            wp_enqueue_script('custom2.js', get_template_directory_uri() . '/js/custom2.js', array('jquery'), null, true);
            wp_enqueue_script('google-location.js', get_template_directory_uri() . '/js/google-location.js', array('jquery'), null, true);
            wp_enqueue_script('user.js', get_template_directory_uri() . '/js/user.js', array('jquery'), null, true);
            wp_enqueue_script('social-login.js', get_template_directory_uri() . '/js/init/social-login.js', array('jquery'), null, true);
            wp_enqueue_script('jquery-sticky', get_template_directory_uri() . '/js/sticky.js', array('jquery'), null, true);
            wp_enqueue_script('tour_version', get_template_directory_uri() . '/js/tour_version.js', array('jquery'), null, true);

            // check out template
            wp_enqueue_script('checkout-js', get_template_directory_uri() . '/js/init/template-checkout.js', array('jquery'), null, true);

            wp_localize_script('jquery', 'st_checkout_text', array(
                'without_pp'        => __('Submit Request', ST_TEXTDOMAIN),
                'with_pp'           => __('Booking Now', ST_TEXTDOMAIN),
                'validate_form'     => __('Please fill all required fields', ST_TEXTDOMAIN),
                'error_accept_term' => __('Please accept our terms and conditions', ST_TEXTDOMAIN),
                'adult_price'       => __('Adult' , ST_TEXTDOMAIN) , 
                'child_price'       => __("Child" , ST_TEXTDOMAIN) , 
                'infant_price'      => __("Infant" , ST_TEXTDOMAIN) , 
                'adult'             => __("Adult" , ST_TEXTDOMAIN) , 
                'child'             => __("Child" , ST_TEXTDOMAIN) , 
                'infant'            => __("Infant" , ST_TEXTDOMAIN) ,
                'price'             => __("Price" , ST_TEXTDOMAIN) ,
                'origin_price'      => __("Origin Price" , ST_TEXTDOMAIN)
            ));
            wp_localize_script('jquery', 'st_params', array(
                'theme_url'       => get_template_directory_uri(),
                'site_url'        => site_url(),
                'ajax_url'        => admin_url('admin-ajax.php'),
                'loading_url'     => admin_url('/images/wpspin_light.gif'),
                'st_search_nonce' => wp_create_nonce("st_search_security"),
                'facebook_enable' => st()->get_option('social_fb_login', 'on'),
                'facbook_app_id'  => st()->get_option('social_fb_app_id'),
                'booking_currency_precision'=> TravelHelper::get_current_currency('booking_currency_precision'),
                'thousand_separator'=> TravelHelper::get_current_currency('thousand_separator'),
                'decimal_separator'=> TravelHelper::get_current_currency('decimal_separator'),
                'currency_symbol'=>TravelHelper::get_current_currency('symbol'),
                'currency_position'=> TravelHelper::get_current_currency('booking_currency_pos'),
                'currency_rtl_support'=>TravelHelper::get_current_currency('currency_rtl_support'),
                'free_text'=>__('Free',ST_TEXTDOMAIN),
                'date_format' => TravelHelper::getDateFormatJs(),
                'date_format_calendar' => TravelHelper::getDateFormatJs(null, 'calendar'),
                'time_format'=>st()->get_option('time_format','12h'),

                'mk_my_location'        => get_template_directory_uri().'/img/my_location.png',
                //'locale'            => file_exists(get_template_directory(). '/js/locales/bootstrap-datepicker.' . get_locale() . '.js') ? get_locale() : TravelHelper::get_minify_locale(get_locale()) ,
                'header_bgr'  => st()->get_option('header_background' , ''),
                'text_refresh'=>__("Refresh",ST_TEXTDOMAIN),
                'date_fomat' => TravelHelper::getDateFormatMoment(),
                'text_loading'        => __("Loading...",ST_TEXTDOMAIN),
                'text_no_more'        => __("No More",ST_TEXTDOMAIN),
            ));
            wp_localize_script('jquery','st_timezone',array(
                'timezone_string'=> get_option('timezone_string', 'local')
            ));
            wp_localize_script('jquery', 'st_list_map_params', array(
                'mk_my_location'        => get_template_directory_uri().'/img/my_location.png',
                'text_my_location'        => __("3000 m radius",ST_TEXTDOMAIN),
                'text_no_result'        => __("No Result",ST_TEXTDOMAIN),
                'cluster_0'        => __("<div class='cluster cluster-1'>CLUSTER_COUNT</div>",ST_TEXTDOMAIN),
                'cluster_20'        => __("<div class='cluster cluster-2'>CLUSTER_COUNT</div>",ST_TEXTDOMAIN),
                'cluster_50'        => __("<div class='cluster cluster-3'>CLUSTER_COUNT</div>",ST_TEXTDOMAIN),
                'cluster_m1'        => get_template_directory_uri().'/img/map/m1.png',
                'cluster_m2'        => get_template_directory_uri().'/img/map/m2.png',
                'cluster_m3'        => get_template_directory_uri().'/img/map/m3.png',
                'cluster_m4'        => get_template_directory_uri().'/img/map/m4.png',
                'cluster_m5'        => get_template_directory_uri().'/img/map/m5.png',
            ));
            wp_localize_script( 'jquery' , 'st_config_partner' , array(
                'text_er_image_format'     => false ,
            ) );

            

            // template user
            wp_enqueue_script('select2.js', get_template_directory_uri() . '/js/select2/select2.min.js', array('jquery'), NULL, TRUE);
            $lang=get_locale();
            $lang_file=get_template_directory().'/js/select2/select2_locale_'.$lang.'.js';
            if(file_exists($lang_file)){
                wp_enqueue_script('select2-lang',get_template_directory_uri().'/js/select2/select2_locale_'.$lang.'.js',array('jquery','select2.js'),null,true);
            }else{
                $locale = TravelHelper::get_minify_locale($lang);
                $lang_file=get_template_directory_uri().'/js/select2/select2_locale_'.$locale.'.js';
                if(file_exists($lang_file))
                {
                    wp_enqueue_script('select2-lang',get_template_directory_uri().'/js/select2/select2_locale_'.$locale.'.js',array('jquery','select2.js'),null,true);
                }
            }
            wp_enqueue_style('st-select2', get_template_directory_uri() . '/js/select2/select2.css');
            wp_enqueue_style('jquery.jqplot.min.css', get_template_directory_uri() . '/js/jqplot/js/jquery.jqplot.min.css');
            wp_enqueue_style('shCoreDefault.min.css', get_template_directory_uri() . '/js/jqplot/syntaxhighlighter/styles/shCoreDefault.min.css');
            wp_enqueue_style('shThemejqPlot.min.css', get_template_directory_uri() . '/js/jqplot/syntaxhighlighter/styles/shThemejqPlot.min.css');
            wp_enqueue_script('jquery.jqplot.min.js', get_template_directory_uri() . '/js/jqplot/js/jquery.jqplot.js', array('jquery'), null, true);
            wp_enqueue_script('shCore.min.js', get_template_directory_uri() . '/js/jqplot/syntaxhighlighter/scripts/shCore.min.js', array('jquery'), null, true);
            wp_enqueue_script('shBrushJScript.min.js', get_template_directory_uri() . '/js/jqplot/syntaxhighlighter/scripts/shBrushJScript.min.js', array('jquery'), null, true);
            wp_enqueue_script('shBrushXml.min.js', get_template_directory_uri() . '/js/jqplot/syntaxhighlighter/scripts/shBrushXml.min.js', array('jquery'), null, true);
            wp_enqueue_script('jqplot.barRenderer.min.js', get_template_directory_uri() . '/js/jqplot/plugins/jqplot.barRenderer.min.js', array('jquery'), null, true);
            wp_enqueue_script('jqplot.categoryAxisRenderer.min.js', get_template_directory_uri() . '/js/jqplot/plugins/jqplot.categoryAxisRenderer.js', array('jquery'), null, true);
            wp_enqueue_script('jqplot.pointLabels.min.js', get_template_directory_uri() . '/js/jqplot/plugins/jqplot.pointLabels.min.js', array('jquery'), null, true);
            wp_enqueue_script('jqplot.cursor.min', get_template_directory_uri() . '/js/jqplot/js/jqplot.cursor.min.js', array('jquery'), null, true);
            wp_enqueue_script('Chart.min.js', get_template_directory_uri() . '/inc/plugins/chart-master/Chart.js', array('jquery'), null, true);

            $post_id = (int) STInput::get('id', '');
            $lists = array();
            $car = new STAdminCars();
            $results = $car->get_data_location_from_to( $post_id );
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

            // edit room
            wp_enqueue_script('hotel_room_availability_partner', get_template_directory_uri() . '/js/availability_hotel_partner.js', array('jquery'), NULL, TRUE);
            // edit tour
            wp_enqueue_script('tour_availability_partner', get_template_directory_uri() . '/js/availability_tour_partner.js', array('jquery'), NULL, TRUE);
            // edit activity
            wp_enqueue_script('activity_availability_partner', get_template_directory_uri() . '/js/availability_activity_partner.js', array('jquery'), NULL, TRUE);
            // edit rental
            wp_enqueue_script('rental_availability_partner', get_template_directory_uri() . '/js/availability_rental_partner.js', array('jquery'), NULL, TRUE);
            // add tour booking
            wp_enqueue_script('booking-partner.js', get_template_directory_uri() . '/js/booking_partner.js', array('jquery'), null, true);
            wp_enqueue_style('fullcalendar', get_template_directory_uri() . '/js/fullcalendar-2.4.0/fullcalendar.min.css');
            wp_enqueue_style('availability_partner', get_template_directory_uri() . '/css/availability_partner.css');

            //icon picker
            wp_enqueue_script('iconpicker', get_template_directory_uri().'/js/iconpicker/js/fontawesome-iconpicker.min.js', array('jquery'), '1.0', true );
            wp_enqueue_script('custom-iconpicker', get_template_directory_uri().'/js/iconpicker/js/custom-iconpicker.js', array('jquery'), null, true );



            wp_enqueue_script('jquery.scrollTo.min.js', get_template_directory_uri() . '/js/jquery.scrollTo.min.js', array('jquery'), null, true);

            if (class_exists('WooCommerce')){
                wp_dequeue_style('woocommerce-layout');
                wp_dequeue_style('woocommerce-smallscreen');
                wp_dequeue_style('woocommerce-general');
            }
            // remove some css stylesheet from external plugins
            wp_deregister_style( 'js_composer_front' );
            wp_deregister_style( 'wsl-widget' );
            wp_deregister_style( 'contact-form-7' );

			wp_enqueue_style('slimmenu-css',get_template_directory_uri().'/css/slimmenu.min.css');
			wp_enqueue_style('bootstrap.css', get_template_directory_uri() . '/css/bootstrap.css');
			wp_enqueue_style('select2_css');
			wp_enqueue_style('animate.css', get_template_directory_uri() . '/css/animate.css');
			wp_enqueue_style('selectize-css');
			wp_enqueue_style('selectize-bt3-css');
			wp_enqueue_style('iconpicker-css');
			wp_enqueue_style('switcher');

			if (class_exists('Vc_Base') and function_exists('vc_asset_url')){
				$front_css_file = vc_asset_url( 'css/js_composer.min.css' );
				wp_enqueue_style( 'js_composer_front', $front_css_file, array(), WPB_VC_VERSION );
			}

			if (class_exists('WooCommerce')){
				wp_enqueue_style('woocommerce-layout');
				wp_enqueue_style('woocommerce-smallscreen');
				wp_enqueue_style('woocommerce-general');
			}
			if (function_exists('wsl_activate')){
				wp_enqueue_style( "wsl-widget", WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . "assets/css/style.css" );
			}
			if (defined( 'WPCF7_VERSION' )){
				wp_enqueue_style( 'contact-form-7',wpcf7_plugin_url( 'includes/css/styles.css' ),array(), WPCF7_VERSION, 'all' );

				if ( wpcf7_is_rtl() ) {
					wp_enqueue_style( 'contact-form-7-rtl',wpcf7_plugin_url( 'includes/css/styles-rtl.css' ), array(), WPCF7_VERSION, 'all' );
				}

				do_action( 'wpcf7_enqueue_styles' );
			}

			if (function_exists('w3tc_cdncache_purge_url')){
				function remove_cssjs_ver( $src ) {
					if( strpos( $src, '?ver=' ) )
						$src = remove_query_arg( 'ver', $src );
					return $src;
				}
				add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
				add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );
			}

			wp_enqueue_style('icomoon.css', get_template_directory_uri() . '/css/icomoon.css');
			wp_enqueue_style('weather-icons.css', get_template_directory_uri() . '/css/weather-icons.min.css');
			wp_enqueue_style('fontawesome', get_template_directory_uri().'/css/font-awesome.css');
			wp_register_style('iconpicker-css',get_template_directory_uri().'/js/iconpicker/css/fontawesome-iconpicker.css');
			wp_enqueue_style('styles.css', get_template_directory_uri() . '/css/styles.css');
			wp_enqueue_style('mystyles.css', get_template_directory_uri() . '/css/mystyles.css');
			wp_enqueue_style('tooltip-classic.css', get_template_directory_uri() . '/css/tooltip-classic.css');
			wp_enqueue_style('chosen-css', get_template_directory_uri() . '/js/chosen/chosen.min.css');
			wp_enqueue_style('default-style', get_stylesheet_uri());
			wp_enqueue_style('fancybox.css', get_template_directory_uri() . '/css/jquery.fancybox.css');
			wp_enqueue_style('fancybox-buttons.css', get_template_directory_uri() . '/js/helpers/jquery.fancybox-buttons.css');
			wp_enqueue_style('fancybox-thumbs.css', get_template_directory_uri() . '/js/helpers/jquery.fancybox-thumbs.css');
			wp_enqueue_style('custom.css', get_template_directory_uri() . '/css/custom.css');
			wp_enqueue_style('custom2css', get_template_directory_uri() . '/css/custom2.css');
			wp_enqueue_style('st_tour_ver', get_template_directory_uri() . '/css/st_tour_ver.css');
			wp_enqueue_style('user.css', get_template_directory_uri() . '/css/user.css');
			wp_enqueue_style('custom-responsive', get_template_directory_uri() . '/css/custom-responsive.css');
			wp_enqueue_style('st-select.css', get_template_directory_uri() . '/css/st-select.css');
			wp_enqueue_script('testimonial', get_template_directory_uri() . '/js/testimonial.js', array('jquery'), null, true);
			wp_enqueue_script('hover_effect_fix', get_template_directory_uri() . '/js/hover_effect.js', array('jquery'), null, true);

			wp_enqueue_style('hover_effect_demo', get_template_directory_uri() . '/css/hover_effect/demo.css');
			wp_enqueue_style('hover_effect_normal', get_template_directory_uri() . '/css/hover_effect/normalize.css');
			wp_enqueue_style('hover_effect_set1', get_template_directory_uri() . '/css/hover_effect/set1.css');
			wp_enqueue_style('hover_effect_set2', get_template_directory_uri() . '/css/hover_effect/set2.css');
			wp_enqueue_style('box_icon_color', get_template_directory_uri() . '/css/box-icon-color.css');


			if(st_is_https()){
				wp_enqueue_style('roboto-font', 'https://fonts.googleapis.com/css?family=Roboto:500,700,400,300,100');
			}else
			{
				wp_enqueue_style('roboto-font', 'http://fonts.googleapis.com/css?family=Roboto:500,700,400,300,100');
			}
			if (st()->get_option('right_to_left') == 'on') {
				wp_enqueue_style('rtl.css', get_template_directory_uri() . '/rtl.css');
			}
			$menu_style = st()->get_option('menu_style', '1');
			wp_enqueue_style('menu_style'.$menu_style.'.css', get_template_directory_uri() . '/css/menu_style'.$menu_style.'.css');

            $list_icon = get_option('st_list_fonticon_', array());
            if(is_array($list_icon) && count($list_icon)){
                foreach($list_icon as $item => $val){
                    wp_enqueue_style($item, $val['link_file_css']);
                }
            }
        }
    }
    if (!function_exists('st_before_footer')){
        add_action('st_before_footer','st_before_footer');
        function st_before_footer(){
            if (defined('W3TC')){
                //echo "<!-- W3TC-include-css -->";
            }

        }
    }
    if (!function_exists('st_add_custom_css')) {
        add_action( defined('W3TC') ? 'st_after_footer': 'wp_head' ,'st_add_custom_css',21);
        function st_add_custom_css()
        {

            $css = '';

            if ($scheme = st()->get_option('style_default_scheme')) {
                $css .= st()->load_template('custom_css', null, array('main_color_char' => $scheme));
            } else {
                $css .= st()->load_template('custom_css');
            }

            echo "\r\n";
            ?>
            <!-- Custom_css.php-->
            <style id="st_custom_css_php">
                <?php echo ($css)?>
            </style>
            <!-- End Custom_css.php-->
            <!-- start css hook filter -->
            <style type="text/css" id="st_custom_css">
                <?php echo apply_filters('st_custom_css', ""); ?>
            </style>
            <!-- end css hook filter -->
            <!-- css disable javascript -->
            <?php $allow_disable_script = st()->get_option("sp_disable_javascript","off"); ?>
            <style type="text/css" id="st_enable_javascript">
                <?php if ($allow_disable_script !="on"){
                        echo ".search-tabs-bg > .tabbable >.tab-content > .tab-pane{display: none; opacity: 0;}.search-tabs-bg > .tabbable >.tab-content > .tab-pane.active{display: block;opacity: 1;}.search-tabs-to-top { margin-top: -120px;}";
                    } ?>
            </style>

            <style>
                <?php echo st()->get_option('custom_css');?>
            </style>
            <?php
        }
    }
    if (!function_exists('st_add_favicon')) {
        function st_add_favicon()
        {
            $favicon = st()->get_option('favicon');

            $ext = pathinfo($favicon, PATHINFO_EXTENSION);

            //if(strtolower($ext)=="pne")

            $type = "";

            switch (strtolower($ext)) {

                case "png":
                    $type = "image/png";
                    break;

                case "jpg":
                    $type = "image/jpg";
                    break;

                case "jpeg":
                    $type = "image/jpeg";
                    break;

                case "gif":
                    $type = "image/gif";
                    break;
            }

            if(!empty($favicon)){
                echo '<link rel="icon"  type="' . esc_attr($type) . '"  href="' . esc_url($favicon) . '">';
            }

        }
    }

    if (!function_exists('st_before_body_content')) {
        function st_before_body_content()
        {
            if (st()->get_option('gen_disable_preload') == "off") {
                ?>
                <!-- Preload -->
                <div id="bt-preload"></div>
                <!-- End Preload -->
            <?php
            }

            echo st()->get_option('adv_before_body_content');
        }
    }

    if (!function_exists('st_add_compress_html')) {
        function st_add_compress_html()
        {
//            if (st()->get_option('adv_compress_html') == "on") {
//                include_once st()->dir('plugins/html-compression.php');
//            }
        }
    }

    if (!function_exists('st_add_ie8_support')) {
        function st_add_ie8_support()
        {
            ?>
            <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
            <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->
        <?php
        }
    }

    if (!function_exists('st_add_custom_style')) {
        function st_add_custom_style()
        {
            get_template_part('custom-style');
        }
    }

    if (!function_exists('st_control_container')) {
        function st_control_container($is_wrap = false)
        {
            $layout = st()->get_option('style_layout');

            if (is_singular()) {
                if ($layout_meta = get_post_meta(get_the_ID(), 'style_layout', true)) {
                    $layout = $layout_meta;
                }
            }

            if ($layout == "boxed") {
                return "container";
            } else {
                return "container-fluid";
            }
        }
    }

    if (!function_exists('st_blog_sidebar')) {
        function st_blog_sidebar()
        {
            $sidebar_pos = st()->get_option('blog_sidebar_pos', 'right');


            if (is_single()) {
                if ($sidebar_pos_meta = get_post_meta(get_the_ID(), 'post_sidebar_pos', true)) {
                    $sidebar_pos = $sidebar_pos_meta;
                }
            } else if (is_page()) {

                if ($sidebar_pos_meta = get_post_meta(get_the_ID(), 'post_sidebar_pos', true)) {
                    $sidebar_pos = $sidebar_pos_meta;
                }

            }

            return $sidebar_pos;
        }

    }

    if (!function_exists('st_blog_sidebar_id')) {
        function st_blog_sidebar_id()
        {
            $sidebar_id = st()->get_option('blog_sidebar_id');


            if (is_single()) {
                if ($sidebar_id_meta = get_post_meta(get_the_ID(), 'post_sidebar', true)) {
                    $sidebar_id = $sidebar_id_meta;
                }
            } else if (is_page()) {

                if ($sidebar_id_meta = get_post_meta(get_the_ID(), 'post_sidebar', true)) {
                    $sidebar_id = $sidebar_id_meta;
                }

            }

            return $sidebar_id;
        }

    }


    if (!function_exists('st_change_comment_excerpt_limit')) {
        function st_change_comment_excerpt_limit($comment)
        {
            return TravelHelper::cutnchar($comment, 55);
        }
    }
    if (!function_exists('st_set_post_view')) {
        function st_set_post_view()
        {
            if (is_singular()) {
                $count_key = 'post_views_count';
                $count = get_post_meta(get_the_ID(), $count_key, true);


                if ($count) {
                    $count = 0;
                    $count++;
                }
                update_post_meta(get_the_ID(), $count_key, $count);
            }
        }
    }


    if (!function_exists('st_admin_add_scripts')) {
        function st_admin_add_scripts()
        {
            wp_enqueue_style('fontawesome', get_template_directory_uri().'/css/font-awesome.css');
            wp_enqueue_script('admin-custom-js', st()->url('js/custom.js'));
            wp_enqueue_style('admin-custom-css', st()->url('css/custom_admin.css'));
        }
    }

    if (!function_exists('st_admin_body_class')) {
        function st_admin_body_class($class = array())
        {

            return $class;
        }
    }


    if (!function_exists('st_add_body_class')) {

        function st_add_body_class($class)
        {
            $class[] = (st()->get_option('body_class','') );
            $class[] = (st()->get_option('style_layout','') );
            $class[] = "menu_style".st()->get_option('menu_style' , '1') ;
            if (st()->get_option('menu_style' , '1') =='4' ) $class[] = "menu_position_".(st()->get_option('menu_position','default') );
            if (st()->get_option('enable_topbar' , 'off') =='on' )$class[] = "topbar_position_".(st()->get_option('topbar_position','default') );
            if (st()->get_option('gen_enable_smscroll') == 'on') {
                $class[] = ' enable_nice_scroll';
            }

            $class[] = STInput::get("sc");
            if (st()->get_option('search_enable_preload', 'on') == 'on' and is_search()) {
                $class[ ] = 'search_enable_preload';
            }

            if (st()->get_option('search_enable_preload', 'on') == 'on') {
                if(is_page_template('template-tour-search.php')
                    or is_page_template('template-hotel-search.php')
                    or is_page_template('template-hotel-room-search.php')
                    or is_page_template('template-cars-search.php')
                    or is_page_template('template-activity-search.php')
                    or is_page_template('template-rental-search.php')

                )
                $class[] = 'search_enable_preload';
            } 

            if(st()->get_option('gen_enable_sticky_topbar' , 'off') =='on'){
                    $class[] = 'enable_sticky_topbar' ; 
                }

            if(st()->get_option('gen_enable_sticky_header','off')=='on')
            {
                $class[] = 'enable_sticky_header';

            }
            if(st()->get_option('gen_enable_sticky_menu','off')=='on'){
                    $class[] = 'enable_sticky_menu';
                }          

            if(st()->get_option('header_transparent')=='on'){
                $class[]='header_transparent';
            } 

            if (is_admin_bar_showing()) {
                $class[]='admin_bar_showing';
            }
            return apply_filters('st_body_class' , $class  );
        }

    }


    add_action('admin_footer', 'st_add_vc_element_icon');
    if (!function_exists('st_add_vc_element_icon')) {
        function st_add_vc_element_icon()
        {
            ?>
            <style>
                .vc-element-icon.icon-st,
                .vc_element-icon.icon-st {
                    background-image: url('<?php echo get_template_directory_uri().'/img/logo80x80.png' ?>') !important;
                    background-size: 100% 100%;
                }

                .vc_shortcodes_container > .wpb_element_wrapper > .wpb_element_title .vc_element-icon.icon-st {
                    background-position: 0px
                }

            </style>
        <?php
        }
    }



    if (!function_exists('st_get_layout')) {
        function st_get_layout($post_type)
        {
            if (empty($post_type)) return false;
            $data[] = array(
                'value' => '',
                'label' => __('-- Default Layout --', ST_TEXTDOMAIN)
            );
            global $wpdb;
            $default = explode('_search', $post_type);
            $default = $default[0];
            $sql = st_get_layout_sql($post_type);
            $rs = $wpdb->get_results($sql,OBJECT);
            if(empty($rs)){
                $rs = $wpdb->get_results(st_get_layout_sql($default),OBJECT);
            }
            if(!empty($rs)){
                foreach($rs as $k=>$v){
                    if ($v->post_title){
                        $data[] = array(
                            'value' => $v->ID,
                            'label' => $v->post_title
                        );
                    }                    
                }
            }
            return $data ; 

        }
    }
    if (!function_exists('st_get_layout_sql')){
        function st_get_layout_sql($post_type){
            if (!$post_type)return ; 
            global $wpdb;
            return $sql = "SELECT   ".$wpdb->posts.".* FROM ".$wpdb->posts."  INNER JOIN $wpdb->postmeta ON ( ".$wpdb->posts.".ID = ".$wpdb->postmeta.".post_id )
                    WHERE 1=1
                    AND
                    (
                      ( ".$wpdb->postmeta.".meta_key = 'st_type_layout' AND CAST(".$wpdb->postmeta.".meta_value AS CHAR) = '".$post_type."' )
                    )
                    AND ".$wpdb->posts.".post_type = 'st_layouts'
                    AND (".$wpdb->posts.".post_status = 'publish')
                    GROUP BY ".$wpdb->posts.".ID ORDER BY ".$wpdb->posts.".post_date DESC ";

        }
    }

    if (!function_exists('st_inside_post_gallery')) {
        function st_inside_post_gallery($output, $attr)
        {
            global $post, $wp_locale;

            static $instance = 0;
            $instance++;

            // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
            if (isset($attr['orderby'])) {
                $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
                if (!$attr['orderby'])
                    unset($attr['orderby']);
            }

            extract(shortcode_atts(array(
                'order'      => 'ASC',
                'orderby'    => 'menu_order ID',
                'id'         => $post->ID,
                'itemtag'    => 'dl',
                'icontag'    => 'dt',
                'captiontag' => 'dd',
                'columns'    => 3,
                'size'       => array(1000, 9999),
                'include'    => '',
                'exclude'    => ''
            ), $attr));

            $id = intval($id);
            if ('RAND' == $order)
                $orderby = 'none';

            if (!empty($include)) {
                $include = preg_replace('/[^0-9,]+/', '', $include);
                $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

                $attachments = array();
                foreach ($_attachments as $key => $val) {
                    $attachments[$val->ID] = $_attachments[$key];
                }
            } elseif (!empty($exclude)) {
                $exclude = preg_replace('/[^0-9,]+/', '', $exclude);
                $attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
            } else {
                $attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
            }

            if (empty($attachments))
                return '';

            if (is_feed()) {
                $output = "\n";
                foreach ($attachments as $att_id => $attachment)
                    $output .= wp_get_attachment_link($att_id, $size, true) . "\n";

                return $output;
            }

            $itemtag = tag_escape($itemtag);
            $captiontag = tag_escape($captiontag);
            $columns = intval($columns);
            $itemwidth = $columns > 0 ? floor(100 / $columns) : 100;
            $float = is_rtl() ? 'right' : 'left';

            $selector = "gallery-{$instance}";

            $output = apply_filters('gallery_style', "

        <!-- see gallery_shortcode() in wp-includes/media.php -->
        <div data-width=\"100%\" class=\"fotorama gallery galleryid-{$id} \" data-allowfullscreen=\"true\">");

            $i = 0;
            foreach ($attachments as $id => $attachment) {
                $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

                $output .= $link;

            }

            $output .= "

        </div>\n";

            return $output;
        }
    }
    if (!function_exists('st_add_login_css')) {
        function st_add_login_css()
        {

            ?>
            <style type="text/css">
                .wp-social-login-widget {
                    display: none;
                }
            </style>

        <?php

        }
    }

if(!function_exists('st_check_is_checkout_woocomerce'))
{
    function st_check_is_checkout_woocomerce($check)
    {
        if(st()->get_option('use_woocommerce_for_booking')=='on' and class_exists('Woocommerce')){
            $check=true;
        }else{
            $check=false;
        }

        return $check;
    }

}
if(!function_exists('st_check_is_booking_modal')) {
    function st_check_is_booking_modal()
    {
        //check is woocommerce
        $st_is_woocommerce_checkout=apply_filters('st_is_woocommerce_checkout',false);

        if(st()->get_option('booking_modal','off')=='on' and !$st_is_woocommerce_checkout)
        {
            return true;
        }else{
            return false;
        }
    }
}

/**
 *
 *
 * @since 1.1.2
 * */
if(!function_exists('st_limit_partner_goto_dashboard'))
{
    function st_limit_partner_goto_dashboard() {
        if ( is_admin() && ! current_user_can( 'administrator' ) &&
            ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
            wp_redirect( home_url() );
            exit;
        }
    }
}

/**
 *
 *
 * @since 1.1.3
 * */

 if(!function_exists('st_check_service_available')) {
     function st_check_service_available($post_type=false)
     {
         if($post_type)
         {
             if(function_exists('ot_options_id'))
             {
                 $option= get_option( ot_options_id() );
                 $disable_list=isset($option['list_disabled_feature'])?$option['list_disabled_feature']:array();

                 if(!empty($disable_list))
                 {
                     foreach($disable_list as $key)
                     {
                         if($key==$post_type) return false;
                     }
                 }
             }


             return true;
         }

         return false;
     }
 }

if(!function_exists('st_after_logout_redirect'))
{
    function st_after_logout_redirect($redirect_to, $requested_redirect_to, $user)
    {
        $page=st()->get_option('page_redirect_to_after_logout');

        if($page)
        {
            $redirect_to=get_permalink($page);
        }

        return $redirect_to;
    }
}
if(!function_exists('st_after_login_redirect'))
{
    function st_after_login_redirect($redirect_to, $request, $user)
    {
        $page=st()->get_option('page_redirect_to_after_login');
        if($page)
        {
            $redirect_to=get_permalink($page);
        }

        return $redirect_to;
    }
}