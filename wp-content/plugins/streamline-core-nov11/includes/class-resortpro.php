<?php

if (!defined('ABSPATH')) {
    exit;
}

class ResortPro extends SibersStrimlineAPI
{


    /**
     * The single instance of ResortPro.
     *
     * @var    object
     * @access   private
     * @since    1.0.0
     */
    private static $_instance = NULL;

    /**
     * The token.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_token;

    /**
     * The main plugin file.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $file;

    /**
     * The main plugin directory.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $dir;

    /**
     * The plugin assets directory.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_dir;

    /**
     * The plugin assets URL.
     *
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_url;

    /**
     * The plugin ajax URL
     */
    public $ajax_url;

    /**
     * The plugin vendor URL
     */
    public $vendor_url;

    /**
     * The array of templates that this plugin tracks.
     *
     * @var      array
     */
    protected $templates;

    protected $js_params;

    public $perPage = 12;

    public $orderBy = 'fav';

    public $sort = 'desc';

    public $available_params = array(
                                                'location_area_name',
                                                'location_name',
                                                'location_type_name',
                                                'condo_type_group_name',
                                                'occupants',
                                                'adults',
                                                'pets',
                                                'min_occupants',
                                                'min_adults',
                                                'min_pets',
                                                'bedrooms_number',
                                                'min_bedrooms_number',
                                                'bathrooms_number',
                                                'min_bathrooms_number',
                                                'longterm_enabled'
                                              );

    /**
     * Constructor function.
     *
     * @access  public
     * @since   1.0.0
     * @return  void
     */

    public $line = array();

    public  $result_data = array();

    public function __construct($file = '')
    {
      // settings
      $this->_token = 'resortpro';
      $this->file = $file;
      $this->dir = dirname( $this->file );
      $this->assets_dir = trailingslashit( $this->dir ) . 'assets';
      $this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );
      $this->ajax_url = esc_url( trailingslashit( plugins_url( '/ajax/', $this->file ) ) );
      $this->vendor_url = esc_url( trailingslashit( plugins_url( '/vendor/', $this->file ) ) );

      // Load API for generic admin functions
      if (is_admin()) {
        $this->admin = new ResortPro_Admin_API();
      }

      // plugin activation/deactivation
      register_activation_hook( $this->file, array( &$this, 'activation' ) );
      register_deactivation_hook( $this->file, array( &$this, 'deactivation' ) );

      // frontend JS & CSS
      add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_styles' ), 10, 1 );
      add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ), 10, 1 );
      // admin JS & CSS
      add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ), 10, 1);
      add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_styles' ), 10, 1);

      // Handle localisation
      add_action( 'plugins_loaded', array( &$this, 'load_plugin_textdomain' ) );

      // Add a filter to the page attributes metabox to inject our template into the page template cache.
      add_filter( 'page_attributes_dropdown_pages_args', array( &$this, 'register_project_templates' ) );
      // Add a filter to the save post in order to inject out template into the page cache
      add_filter( 'wp_insert_post_data', array( &$this, 'register_project_templates' ) );
      // Add a filter to the template include in order to determine if the page has our template assigned and return it's path
      add_filter( 'template_include', array( &$this, 'view_project_template' ) );

      // [TODO] plugin_slug is not being set
      // Add your templates to this array.
      $this->templates = array(
          'page-resortpro-listings-template.php' =>       __( 'StreamlineCore Listings Template', 'streamline-core' ),
          'page-resortpro-listing-detail-template.php' => __( 'StreamlineCore Detail Template', 'streamline-core' ),
          'page-resortpro-checkout-template.php' =>       __( 'StreamlineCore Checkout Template', 'streamline-core' ),
          'page-resortpro-home-template.php' =>           __( 'StreamlineCore Home Template', 'streamline-core' )
      );

      // adding support for theme templates to be merged and shown in dropdown
      $templates = wp_get_theme()->get_page_templates();
      $templates = array_merge( $templates, $this->templates );

      // Add the handler for fetching resortpro properties from the API.
      add_action( 'init', array( &$this, 'resortpro_find_listings' ) );
      add_action( 'init', array( &$this, 'resortpro_listing_detail' ) );
      add_action( 'init', array( &$this, 'resortpro_checkout' ) );
      add_action( 'init', array( &$this, 'resortpro_book_unit' ) );

      // Add angular javascript for Api settings, etc.
      add_action('wp_footer', array( &$this, 'resortpro_angular_api_settings' ) );

      // plugin sidebars
      add_action( 'widgets_init', array( &$this, 'widgets_init' ) );
    } // End __construct ()

    /**
     * get options - Deprecated use StreamlineCore_Settings::get_options()
     */
    public static function get_options($field = FALSE) {
      return StreamlineCore_Settings::get_options($field);
    }

    /**
     * Checks if the template is assigned to the page
     *
     * @version   2.0.3
     * @since    2.0.3
     */
    public function widgets_init() {
      register_sidebar( array(
        'id' => 'side_search_widget', // used to be side_search_widget
        'name' => __( 'StreamlineCore Side Search Area', 'streamline-core' ),
        'class' => 'e-widget',
        'before_widget' => '<div id="%1$s" class="%2$s side_search_widget">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => ''
      ) );

      register_sidebar( array(
        'id' => 'top_search_widget', // used to be top_search_widget
        'name' => __( 'StreamlineCore Top Search Area', 'streamline-core' ),
        'class' => 'e-widget',
        'before_widget' => '<div id="%1$s" class="%2$s top_search_widget">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => ''
      ) );
    } // end widget_init

    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     *
     * @param   array $atts The attributes for the page attributes dropdown
     * @return  array    $atts    The attributes for the page attributes dropdown
     * @verison    1.0.0
     * @since    1.0.0
     */
    public function register_project_templates($atts)
    {
        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());
        // Retrieve the cache list. If it doesn't exist, or it's empty prepare an array
        $templates = wp_cache_get($cache_key, 'themes');
        if (empty($templates)) {
            $templates = array();
        } // end if
        // Since we've updated the cache, we need to delete the old cache
        wp_cache_delete($cache_key, 'themes');
        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge($templates, $this->templates);
        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add($cache_key, $templates, 'themes', 1800);
        return $atts;
    } // end register_project_templates

    /**
     * Checks if the template is assigned to the page
     *
     * @version    1.0.0
     * @since    1.0.0
     */
    public function view_project_template($template)
    {
        global $post;
        // If no posts found, return to

        // avoid "Trying to get property of non-object" error
        if (!isset($post)) {
            return $template;
        }

        if (!isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true)])) {
            return $template;
        } // end if

        $file = plugin_dir_path(__FILE__) . 'templates/' . get_post_meta($post->ID, '_wp_page_template', true);

        // Just to be safe, we check if the file exist first
        if (file_exists($file)) {
            return $file;
        } // end if

        return $template;
    } // end view_project_template

    /*--------------------------------------------*
     * Delete Templates from Theme
    *---------------------------------------------*/

    public function delete_template($filename)
    {
        $theme_path = get_template_directory();
        $template_path = $theme_path . '/' . $filename;
        if (file_exists($template_path)) {
            unlink($template_path);
        }
        // we should probably delete the old cache
        wp_cache_delete($cache_key, 'themes');
    }

    /**
     * Retrieves and returns the slug of this plugin. This function should be called on an instance
     * of the plugin outside of this class.
     *
     * Deprecated: $this->plugin_slug holds no value as it's value is
     * never set, therefore this function will always return NULL.
     *
     * @return  string    The plugin's slug used in the locale.
     * @version    1.0.0
     * @since    1.0.0
     */
    public function get_locale() {
      //return $this->plugin_slug;
      return null;
    }

    function resortpro_angular_api_settings_init()
    {
        add_action('wp_head', 'resortpro_angular_api_settings');
    }

    function resortpro_angular_api_settings()
    {
/* I seriously these are used - trr
        //set default settings here:
        $wpt_resortpro_show_tax_breakdown = 'false';
        $wpt_resortpro_max_occupants_adult = (get_option('wpt_resortpro_max_occupants_adult') !== FALSE) ? get_option('wpt_resortpro_max_occupants_adult') : 5;
        //setting checkbox to booleans since wordpress does these as on or null
        if (get_option('wpt_resortpro_show_tax_breakdown') == 'on') {
            $wpt_resortpro_show_tax_breakdown = 'true';
        }

        $max_occupants = get_option('wpt_resortpro_max_occupants_adult');
*/
        $options = StreamlineCore_Settings::get_options();

        $pagination = (!empty($options['property_pagination'])) ? $options['property_pagination'] : 5;
        $useAddOns = (!empty($options['checkout_use_addons'])) ? $options['checkout_use_addons'] : 0;
        $createLeads = (!empty($options['checkout_create_leads'])) ? $options['checkout_create_leads'] : 0;
        $blockedRequest = (!empty($options['booking_blocked_requests'])) ? $options['booking_blocked_requests'] : 0;
        $roomTypeLogic = (!empty($options['use_room_type_logic']) && $options['use_room_type_logic'] == '1') ? 1 : 0;        
        $enforceOccupancy = (!empty($options['book_enforce_occupancy'])) ? $options['book_enforce_occupancy'] : 0;
        $searchMethod = (!empty($options['search_method'])) ? $options['search_method'] : 'GetPropertyAvailabilitySimple';
        $priceDisplay = (!empty($options['price_display'])) ? $options['price_display'] : 'total';
        $maxSearchDays = (!empty($options['max_days_search'])) ? $options['max_days_search'] : 30;
        $locationId =  (!empty($options['property_loc_id'])) ? $options['property_loc_id'] : 0;
        $resortAreaId =  (!empty($options['property_resort_area_id'])) ? $options['property_resort_area_id'] : 0;
        $hearAboutId =  (!empty($options['heared_about_id'])) ? $options['heared_about_id'] : 0;
        $useDailyPricing =  (!empty($options['use_daily_pricing'])) ? $options['use_daily_pricing'] : 0;
        $useWeeklyPricing =  (!empty($options['use_weekly_pricing'])) ? $options['use_weekly_pricing'] : 0;
        $useMonthlyPricing =  (!empty($options['use_monthly_pricing'])) ? $options['use_monthly_pricing'] : 0;
        $dailyPrepend = (!empty($options['daily_pricing_prepend'])) ? $options['daily_pricing_prepend'] : '';
        $dailyAppend = (!empty($options['daily_pricing_append'])) ? $options['daily_pricing_append'] : '';
        $weeklyPrepend = (!empty($options['weekly_pricing_prepend'])) ? $options['weekly_pricing_prepend'] : '';
        $weeklyAppend = (!empty($options['weekly_pricing_append'])) ? $options['weekly_pricing_append'] : '';
        $monthlyPrepend = (!empty($options['monthly_pricing_prepend'])) ? $options['monthly_pricing_prepend'] : '';
        $monthlyAppend = (!empty($options['monthly_pricing_append'])) ? $options['monthly_pricing_append'] : '';              
        $restrictionMsg = (!empty($options['message_restriction'])) ? $options['message_restriction'] : '';

        $additionalVariables = (!empty($options['additional_variables'])) ? $options['additional_variables'] : 0;
        $clientSideAmenities = (!empty($options['client_side_amenities'])) ? $options['client_side_amenities'] : 0;
        
        $extraCharges = (!empty($options['extra_charges'])) ? $options['extra_charges'] : 0;
        $rateMarkup = (!empty($options['rate_markup'])) ? (int)$options['rate_markup'] : 0;

        $locations = array();
        foreach ($options['filter_location_areas'] as $key => $value) {
          $locations[] = $key;
        }

        $assetsUrl = esc_url($this->assets_url);

        $propertyLink = get_bloginfo("url")."/";
        if (!empty($options['prepend_property_page'])) {
            $propertyLink .= $options['prepend_property_page'] . "/";
        }
        //$skip_amenities = (!empty(get_option( 'streamline_skip_amenities' ))) ? 1 : 0;
        $amenities = get_option( 'streamline_skip_amenities' );
    
        $skip_amenities = (!empty($amenities) || $clientSideAmenities == 0) ? 1 : 0;

        $useHTML = $options['property_use_html'];

        $output = '<script type="text/javascript">
            (function () {
                var app = angular.module("resortpro");

                app.run(function($rootScope){

                    $rootScope.APIServer = "' . $options['endpoint'] . '";
                    $rootScope.companyCode = "' . $options['id'] . '";
                    $rootScope.propertyUrl = "'.$propertyLink.'";
                    $rootScope.useHTML = '.$useHTML.';
                    $rootScope.roomTypeLogic = ' . $roomTypeLogic . ';                    
                    $rootScope.enforceOccupancy = '.$enforceOccupancy.';
                    $rootScope.rateMarkup = '.$rateMarkup.';

                    $rootScope.checkoutSettings = {
                      showTaxBreakdown : "true",
                      useAddOns : '.$useAddOns.',
                      createLeads : '.$createLeads.'
                    };

                    $rootScope.searchSettings = {
                      maxOccupantsAdults : "2",
                      locationAreas : \'' . implode(',',$locations) . '\',
                      locationId : ' . $locationId . ',
                      resortAreaId : ' . $resortAreaId . ',
                      searchMethod : \'' . $searchMethod . '\',
                      priceDisplay : \'' . $priceDisplay . '\',
                      maxSearchDays : ' . $maxSearchDays . ',
                      additionalVariables : ' . $additionalVariables . ',
                      extraCharges : ' . $extraCharges . ',
                      propertyPagination : ' . $pagination . ',
                      propertyDeleteUnits : \'' . $options['property_delete_units'] . '\',
                      defaultFilter : \'' . $options['resortpro_default_filter'] . '\',
                      skipAmenities : ' . $skip_amenities . ',                      
                      restrictionMsg : \''. $restrictionMsg . '\',
                      useDailyPricing : ' . $useDailyPricing . ',
                      useWeeklyPricing : ' . $useWeeklyPricing . ',
                      useMonthlyPricing : ' . $useMonthlyPricing . ',
                      dailyAppend : \'' . $dailyAppend . '\',
                      dailyPrepend : \'' . $dailyPrepend . '\',
                      weeklyAppend : \'' . $weeklyAppend . '\',
                      weeklyPrepend : \'' . $weeklyPrepend . '\',
                      monthlyAppend : \'' . $monthlyAppend . '\',
                      monthlyPrepend : \'' . $monthlyPrepend . '\',
                    };

                    $rootScope.bookingSettings = {
                      hearedAboutId : '.$hearAboutId.',
                      blockedRequest : '.$blockedRequest.',
                      inquiryThankMsg : \''.$options['inquiry_thankyou_msg'].'\',
                      inquiryThankUrl : \''.$options['inquiry_thankyou_url'].'\'
                    };
                });
            })();
            var assetsUrl = "'.$assetsUrl.'";
        </script>';

        echo $output;

    }

    // add_action( 'wp_head', 'resortpro_angular_api_settings' );

    /**
     * Load frontend CSS.
     *
     * @access  public
     * @since   1.0.0
     * @return void
     */
    public function enqueue_styles() {
      // font awesome
      wp_enqueue_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );

      // jquery ui
      wp_enqueue_style( 'jquery-ui-css', $this->assets_url . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'src/css/jquery-ui.css' : 'dist/css/jquery-ui.min.css' ) );

      // waitMe
      wp_enqueue_style( 'waitme-css', $this->vendor_url . 'waitMe/' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'waitMe.css' : 'waitMe.min.css' ) );

      // plugin styles
      wp_enqueue_style( $this->_token . '-css', $this->assets_url . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'src/css/resortpro.css' : 'dist/css/resortpro.min.css' ) );

      wp_enqueue_style( 'streamline-core', $this->assets_url . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'src/css/streamline-core.css' : 'dist/css/streamline-core.min.css' ) );
      // [TODO] this is incorrect however works for now
      if (defined( 'RESORTPRO_PROPERTY_ID' ) ) {
        // lightbox
        wp_enqueue_style( 'lightbox-css', 'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/3.3.0/ekko-lightbox.min.css' );

        // tool tipster
        wp_enqueue_style( 'tooltipster-css', 'https://cdnjs.cloudflare.com/ajax/libs/tooltipster/3.3.0/css/tooltipster.min.css' );

        // master slider
        wp_enqueue_style( 'masterslider-css', $this->assets_url . 'masterslider/style/masterslider.css' );
        wp_enqueue_style( 'masterslider-skin-css', $this->assets_url . 'masterslider/skins/default/style.css' );
        wp_enqueue_style( 'masterslider-partial-css', $this->assets_url . 'masterslider/style/ms-partialview.css' );
      }
    } // End enqueue_styles ()

    /**
     * Load frontend Javascript.
     *
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function enqueue_scripts() {
      // jquery
      wp_enqueue_script( 'jquery' );

      // jquery color
      wp_enqueue_script( 'jquery-color' );

      // jquery-ui
      wp_enqueue_script( 'jquery-ui-core' );
      wp_enqueue_script( 'jquery-ui-slider' );
      wp_enqueue_script( 'jquery-ui-datepicker' );

      // google maps api
      $options = StreamlineCore_Settings::get_options();

      if(is_page('checkout')){
        $pbg_enabled = (isset($options['enable_paybygroup']) && $options['enable_paybygroup'] == 1) ? true : false;
        $pbg_merchant = $options['paybygroup_merchant_id'];
        if($pbg_enabled && !empty($pbg_merchant))
          wp_enqueue_script( 'pbg-js', 'https://cdn.paybygroup.com/snippet/v2/loader.js?merchant_id=' . $pbg_merchant );
      }
      

      $google_endpoint = 'https://maps.googleapis.com/maps/api/js';
      if(!empty($options['google-maps-api']))
        $google_endpoint .= "?key={$options['google-maps-api']}";
      
//      wp_enqueue_script( 'googlemaps-js', $google_endpoint ); // GOOGLE MAPS

      wp_enqueue_script( 'richMarker', $this->vendor_url . 'richMarker/' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'richmarker.js' : 'richmarker.min.js' ), array( 'googlemaps-js' ) );

      // waitMe
      wp_enqueue_script( 'waitme-js', $this->vendor_url . 'waitMe/' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'waitMe.js' : 'waitMe.min.js' ), array( 'jquery' ) );

      // dateFormat
      wp_enqueue_script( 'date-format-js', $this->vendor_url . 'dateFormat/' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'dateFormat.js' : 'dateFormat.min.js' ) );

      // frontend js
      wp_enqueue_script( 'frontend-js', $this->assets_url . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'src/js/frontend.js' : 'dist/js/frontend.min.js' ), array( 'jquery' ) );

      // angularjs
      wp_enqueue_script( 'angularjs', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.min.js', array( 'jquery' ) );

      wp_enqueue_script( 'angularcookies', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-cookies.js', array('angularjs'));

      // ng-map
      wp_enqueue_script( 'ng-map-js', $this->vendor_url . 'ng-map/' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'ng-map.js' : 'ng-map.min.js' ) );

      wp_register_script( 'ecommerce',  $this->assets_url . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'src/js/ecommerce.js' : 'dist/js/ecommerce.min.js' ) );
      // App scripts
      if( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
  		  wp_enqueue_script( 'resortpro-services-js', $this->assets_url . 'app/services/services.js', array( 'angularjs' ) );
  		  wp_enqueue_script( 'resortpro-alerts-js', $this->assets_url . 'app/services/alerts.js', array( 'angularjs' ) );
  		  wp_enqueue_script( 'resortpro-api-js', $this->assets_url . 'app/services/rpapi.js', array( 'angularjs' ) );
  		  wp_enqueue_script( 'resortpro-dirPagination-js', $this->assets_url . 'app/directives/dirPagination.js', array( 'angularjs' ) );
  		  wp_enqueue_script( 'resortpro-filters-js', $this->assets_url . 'app/filters/filters.js', array( 'angularjs' ) );
  		  wp_enqueue_script( 'resortpro-property-js', $this->assets_url . 'app/property/property.js', array( 'angularjs' ) );
  		  wp_enqueue_script( 'resortpro-checkout-js', $this->assets_url . 'app/checkout/checkout.js', array( 'angularjs' ) );        
  		  wp_enqueue_script( 'resortpro-app-js', $this->assets_url . 'app/app.js', array( 'angularjs' ) );
  	  } else {
  	  	wp_enqueue_script ( 'resortpro', $this->assets_url . 'dist/js/resortpro.min.js', array('angularjs') );
  	  }

      // wp_enqueue_script( 'angularjs-isotope', $this->vendor_url . 'angular-isotope/dist/' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'angular-isotope.js' : 'angular-isotope.min.js' ), array( 'angularjs' ) );
      // [TODO] this is incorrect however works for now

      // jquery sticky
      wp_enqueue_script( 'jquery-sticky-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.sticky/1.0.3/jquery.sticky.min.js' );

      wp_enqueue_script( 'custom-quote-js', $this->assets_url . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'src/js/custom_quote.js' : 'dist/js/custom_quote.min.js' ), array( 'jquery' ) );


      if (defined( 'RESORTPRO_PROPERTY_ID' ) ) {
        // lightbox
        wp_enqueue_script( 'lightbox-js', 'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/3.3.0/ekko-lightbox.min.js' );



        // master slider
        wp_enqueue_script( 'masterslider-js', $this->assets_url . 'masterslider/masterslider.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'masterslider-partial-js', $this->assets_url . 'masterslider/masterslider.partialview.js', array( 'jquery' ) );

        // tooltipster
        wp_enqueue_script( 'tooltipster-js', 'https://cdnjs.cloudflare.com/ajax/libs/tooltipster/3.3.0/js/jquery.tooltipster.min.js' );

        // unit
        wp_enqueue_script( 'unit-js', $this->assets_url . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'src/js/unit.js' : 'dist/js/unit.min.js' ), array( 'jquery', 'masterslider-js', 'waitme-js' ) );
      }

      wp_localize_script( 'frontend-js', 'streamlinecoreConfig', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ) ) );
    } // End enqueue_scripts ()

    /**
     * Load admin CSS.
     *
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function admin_enqueue_styles( $hook = '' ) {
      // check what page we are on
      if ( $hook == 'widgets.php' ) {
        // jquery ui theme
        wp_enqueue_style( 'jqueryui-theme-css', 'https://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css' );

        // plugin styles
        wp_enqueue_style( $this->_token . '-admin', $this->assets_url . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'src/css/admin.css' : 'dist/css/admin.min.css' ), array(), STREAMLINECORE_VERSION );
      } elseif ( $hook == 'settings_page_' . StreamlineCore_Settings::get_settings_page() ) {
        // bootstrap
        wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' );
      }
    } // End admin_enqueue_styles ()

    /**
     * Load admin Javascript.
     *
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function admin_enqueue_scripts( $hook = '' ) {
      // check that we are on the plugin's settings page or widgets page
      if ( in_array( $hook, array( 'settings_page_' . StreamlineCore_Settings::get_settings_page(), 'widgets.php' ) ) ) {
        if ( $hook == 'widgets.php' ) {
          // jquery ui core and dialog
          wp_enqueue_script( 'jquery-ui-core' );
          wp_enqueue_script( 'jquery-ui-dialog' );

          // plugin admin acript
          wp_enqueue_script( $this->_token . '-admin-js', $this->assets_url . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'src/js/admin.js' : 'dist/js/admin.min.js' ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog' ), STREAMLINECORE_VERSION, true );
        } else {
          // bootstrap
          wp_enqueue_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js', array( 'jquery' ), '3.3.5' );
        }
      }
    } // End admin_enqueue_scripts ()

    /**
     * load plugin text domain
     *
     *@access public
     *@return void
     */
    public function load_plugin_textdomain() {
      load_plugin_textdomain( 'streamline-core', false, basename( dirname( $this->file ) ) . '/languages/' );
    }

    /**
     * Main ResortPro Instance
     *
     * Ensures only one instance of ResortPro is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see   ResortPro()
     * @return Main ResortPro instance
     */
    public static function instance($file = '')
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($file);
        }

        return self::$_instance;
    } // End instance ()

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), STREAMLINECORE_VERSION);
    } // End __clone ()

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), STREAMLINECORE_VERSION);
    } // End __wakeup ()

    /**
     * plugin activation
     *
     * @access  public
     * @since   1.0.0
     * @return void
     */
    public function activation() {

      // create plugin pages (listings, details, checkout, thankyou)
      $page_arr = array();
      $page_arr['listings'] = array(
        'title'    => __( 'Search Results', 'streamline-core' ),
        'name'     => 'search-results',
        'content'  => '[streamlinecore-search-results]',
        'template' => null
      );
      $page_arr['listing_detail'] = array(
        'title'    => __( 'Property Info', 'streamline-core' ),
        'name'     => 'property-info',
        'content'  => '[streamlinecore-property-info]',
        'template' => null
      );
      $page_arr['checkout'] = array(
        'title'    => __( 'Checkout', 'streamline-core' ),
        'name'     => 'checkout',
        'content'  => '[streamlinecore-checkout]',
        'template' => 'null'
      );
      $page_arr['thank_you'] = array(
        'title'    => __( 'Thank You', 'streamline-core' ),
        'name'     => 'thank-you',
        'content'  => '[streamlinecore-thankyou]',
        'template' => null
      );
      $page_arr['custom_quote'] = array(
        'title'    => __( 'Custom Quote', 'streamline-core' ),
        'name'     => 'custom-quote',
        'content'  => '[streamlinecore-quote]',
        'template' => null
      );
      $page_arr['terms-and-conditions'] = array(
        'title'    => __( 'Terms and conditions', 'streamline-core' ),
        'name'     => 'terms-and-conditions',
        'content'  => '[streamlinecore-terms]',
        'template' => null
      );

      // loop through pages set above
      foreach ($page_arr as $slug => $p) {
        // try and get page
        $page = get_page_by_title( $p['title'] );

        if ( ! $page ) {
          // create page
          $page_id = wp_insert_post( array(
            'post_title'      => $p['title'],
            'post_name'       => $p['name'],
            'post_content'    => $p['content'],
            'page_template'   => ( is_null($p['template']) ? 'default' : $p['template']),
            'post_status'     => 'publish',
            'post_type'       => 'page',
            'ping_status'     => 'closed',
            'comment_status'  => 'closed'
          ) );

          // check that page is created
          if ( $page_id == 0 ) {
            exit('Unable to create ' . $p['title'] . ' page.');
          }
        } elseif ( $page->post_status != 'publish' ) {
          // publish page
          $page->post_status = 'publish';
          $page_id = wp_update_post($page);

          // check that page was published
          if ( $page_id == 0 ) {
            exit('Unable to publish ' . $p['title'] . ' page.');
          }
        } else {
          // all good to go, just set the page id
          $page_id = $page->ID;
        }

		if( 0 != $page_id ) :
			// update options (I think only the id is actually being used)
			update_option( 'resortpro_' . $slug . '_page_title', $p['title'] );
			update_option( 'resortpro_' . $slug . '_page_name', $p['name'] );
			update_option( 'resortpro_' . $slug . '_page_id', $page_id );
        endif;
      }

      // resortpro nonce
      update_option( 'wpt_resortpro_api_nonce', 'resortpro_nonce' );
    }

    /**
     * plugin deactivation
     *
     * @access  public
     * @since   1.0.0
     * @return void
     */
    public function deactivation( $network_wide ) {
      // delete resorpro_testdata table (if exists)
      // note this table is no longer created
      global $wpdb;
      $wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "resortpro_testdata" );
    } // end deactivation

    /**
     * When submitting a filter request for properties, redirect to the listings page
     * (resortpro-listings) and populate the querystring with the filter params.
     */
    public function resortpro_find_listings()
    {
      if (isset($_POST['resortpro_search_nonce'])) {

        global $wpdb;

        $amenities = '';
        if(isset($_POST['resortpro_sw_amenities'])){
            $amenities = implode(',', $_POST['resortpro_sw_amenities']);
        }

        $occupants = (isset($_POST['resortpro_sw_adults']) && $_POST['resortpro_sw_adults'] > 0) ? $_POST['resortpro_sw_adults'] : null;
        $occupants_small = (isset($_POST['resortpro_sw_children']) && $_POST['resortpro_sw_children'] > 0) ? $_POST['resortpro_sw_children'] : null;

        $raw_start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $raw_end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;

        $unit_id = isset($_POST['resortpro_sw_bed']) ? $_POST['resortpro_sw_bed'] : null;

        $params = array();
        if (!empty($raw_start_date) && !empty($raw_end_date)) {
            if( version_compare ( '5.3', PHP_VERSION, '<' ) ){
              $start = new \DateTime($raw_start_date);
              $start_date = $start->format('m/d/Y');

              $end = new \DateTime($raw_end_date);
              $end_date = $end->format('m/d/Y');
            } else {
              $start_date = date('Y-m-d', strtotime($raw_start_date)); // now
              $end_date = date('Y-m-d', strtotime($raw_end_date)); // two days from now
            }

            $params = array(
                'sd' => $start_date,
                'ed' => $end_date
            );
        }

        if(!empty($unit_id)){
            $params['unit_id'] = $unit_id;

            $results = StreamlineCore_Wrapper::search( array(
              'startdate'     => $start_date,
              'enddate'       => $end_date,
              'min_occupants' => $occupants,
              'condo_type_id' => $unit_id
            ) );

            if($results['data']['available_properties']['pagination']['total_units'] == "1"){

                $seo_page_name =  $results['data']['property']['seo_page_name'];

                if(!empty($seo_page_name)){

					$destination_url = StreamlineCore_Wrapper::get_unit_permalink( $seo_page_name );
                    header("location: $destination_url");
                    exit;
                }
            }
        }

        if (is_numeric($occupants) && $occupants > 0)
            $params['oc'] = $occupants;

        if (is_numeric($occupants_small) & $occupants_small > 0)
            $params['ch'] = $occupants_small;

        if (isset($_POST['resortpro_sw_pets']) && $_POST['resortpro_sw_pets'] > 0)
          $params['pets'] = filter_var($_POST['resortpro_sw_pets'], FILTER_SANITIZE_NUMBER_INT);

        if (isset($_POST['resortpro_sw_bedrooms_number']) && is_numeric($_POST['resortpro_sw_bedrooms_number']))
          $params['beds'] = filter_var($_POST['resortpro_sw_bedrooms_number'], FILTER_SANITIZE_NUMBER_INT);

        if (isset($_POST['resortpro_sw_area']) && $_POST['resortpro_sw_area'] > 0)
          $params['area_id'] = filter_var($_POST['resortpro_sw_area'], FILTER_SANITIZE_NUMBER_INT);

        if (isset($_POST['resortpro_sw_lodging_type_id']) && $_POST['resortpro_sw_lodging_type_id'] > 0)
          $params['lodging_type_id'] = filter_var($_POST['resortpro_sw_lodging_type_id'], FILTER_SANITIZE_NUMBER_INT);

        if (isset($_POST['resortpro_sw_ra_id']) && $_POST['resortpro_sw_ra_id'] > 0)
          $params['resort_area_id'] =  filter_var($_POST['resortpro_sw_ra_id'], FILTER_SANITIZE_NUMBER_INT);

        if (isset($_POST['resortpro_sw_view_name']) && !empty($_POST['resortpro_sw_view_name']))
          $params['view_name'] = filter_var ( $_POST['resortpro_sw_view_name'], FILTER_SANITIZE_STRING);

        if (isset($_POST['resortpro_sw_lodging_unit']) && !empty($_POST['resortpro_sw_lodging_unit']))
          $params['unit_name'] = filter_var ( $_POST['resortpro_sw_lodging_unit'], FILTER_SANITIZE_STRING);

        if (isset($_POST['resortpro_sw_lodging_code']) && !empty($_POST['resortpro_sw_lodging_code']))
          $params['unit_name'] = filter_var ( $_POST['resortpro_sw_lodging_code'], FILTER_SANITIZE_STRING);

        if (isset($_POST['resortpro_sw_filter']) && !empty($_POST['resortpro_sw_filter']))
          $params['sort_by'] = filter_var ( $_POST['resortpro_sw_filter'], FILTER_SANITIZE_STRING);

        if (isset($_POST['resortpro_sw_neighborhood_id']) && !empty($_POST['resortpro_sw_neighborhood_id']))
          $params['neighborhood_area_id'] = filter_var ( $_POST['resortpro_sw_neighborhood_id'], FILTER_SANITIZE_STRING);

        if (isset($_POST['resortpro_sw_loc']) && !empty($_POST['resortpro_sw_loc']))
          $params['location_id'] = filter_var ( $_POST['resortpro_sw_loc'], FILTER_SANITIZE_STRING);

        if (isset($_POST['resortpro_sw_grp']) && !empty($_POST['resortpro_sw_grp']))
          $params['group_id'] = filter_var ( $_POST['resortpro_sw_grp'], FILTER_SANITIZE_STRING);

        if(isset($_POST['plus']) && is_numeric($_POST['plus']))
          $params['plus'] = filter_var ( $_POST['plus'], FILTER_SANITIZE_STRING);


        if(isset($_POST['resortpro_sw_home_type_id']) && !empty($_POST['resortpro_sw_home_type_id']))
          $params['property_type_id'] = filter_var($_POST['resortpro_sw_home_type_id'], FILTER_SANITIZE_NUMBER_INT);

        if(!empty($amenities))
          $params['amenities'] = $amenities;

        $permalink = get_permalink(get_page_by_slug('search-results'));

        if (isset($url['query'])) {
            $redirect_url = sprintf('%s&%s', $permalink, http_build_query($params));
        } else {
            $redirect_url = sprintf('%s?%s', $permalink, http_build_query($params));
        }

        header('location: ' . $redirect_url);
        exit;
      }
    }

    public function resortpro_book_unit()
    {
      if (isset($_POST['resortpro_book_unit'])) {
        global $wpdb;

        $unit = isset($_POST['book_unit']) ? $_POST['book_unit'] : null;
        $occupants = isset($_POST['book_occupants']) ? $_POST['book_occupants'] : null;
        $raw_start_date = isset($_POST['book_start_date']) ? $_POST['book_start_date'] : null;
        $raw_end_date = isset($_POST['book_end_date']) ? $_POST['book_end_date'] : null;

        if( version_compare ( '5.3', PHP_VERSION, '<' ) ){

          $start = new \DateTime($raw_start_date);
          $start_date = $start->format('Y-m-d');

          $end = new \DateTime($raw_end_date);
          $end_date = $end->format('Y-m-d');
        } else {
          $start_date = date('Y-m-d'); // now
          $end_date = date('Y-m-d', time()+ (2 * SECONDS_PER_DAY ) ); // two days from now
        }

        $params = array(
            'unit' => $unit,
            'oc' => $occupants,
            'sd' => $start_date,
            'ed' => $end_date
        );

        if(isset($_POST['book_occupants_small']) && is_numeric($_POST['book_occupants_small']))
          $params['os'] = $_POST['book_occupants_small'];

        if(isset($_POST['book_pets']) && is_numeric($_POST['book_pets']))
          $params['pets'] = $_POST['book_pets'];

        if(isset($_POST['book_coupon_code']) && !empty($_POST['book_coupon_code']))
          $params['coupon_code'] = filter_var ( $_POST['book_coupon_code'], FILTER_SANITIZE_STRING);

        if(isset($_POST['hash']) && !empty($_POST['hash']))
          $params['hash'] = filter_var ( $_POST['hash'], FILTER_SANITIZE_STRING);

        $permalink = get_permalink(get_page_by_slug('resortpro-checkout'));

        $url = parse_url($permalink);

        if (isset($url['query'])) {
            $redirect_url = sprintf('%s&%s', $permalink, http_build_query($params));
        } else {
            $redirect_url = sprintf('%s?%s', $permalink, http_build_query($params));
        }

        header('location: ' . $redirect_url);
        exit;
      }
    }

    /**
     * Fetch a single resortpro listing.
     */
    public function resortpro_listing_detail()
    {

        $rpid = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($rpid) {

            /**
             * Fetch the record from ResortPro API
             * Handeled by Angular
             */
            $property = StreamlineCore_Wrapper::get_property_info( (int)$_GET['id'] );

            return (array)$property['data'];

        }

    }


    public function favorites(){
      $options = StreamlineCore_Settings::get_options();

      $search_layout = $options['search_layout'];

      $use_favorites = ($options['use_favorites'] == '1') ? true : false;
      
      $fav_units = ($_COOKIE['streamline-favorites']);

      //$units = StreamlineCore_Wrapper::get_random_units(1000, $fav_units);

      $template = ResortPro::locate_template( 'listing-search-template.php' );
      if ( empty( $template ) ) {
        // default template
        $template = trailingslashit( $this->dir ) . 'includes/templates/search/listing-search-template' . $search_layout . '.php';
      }

      //return $template;
      ob_start();
      include(trailingslashit($this->dir) . 'includes/templates/favorites.php');
      $output = ob_get_clean();

      return $output;
    }

    /**
     * Handle the checkout page.
     */
    public function resortpro_checkout()
    {
        /**
         * @TODO: maybe something here, if not angular will handle it..
         */
    }

    public function browse_results($params = array(), $return_units = false)
    {
        $options = StreamlineCore_Settings::get_options();
        if (isset($params['location_area_name']) && strpos($params['location_area_name'], ',') !== false) {
            $params['location_area_name'] = explode(',', $params['location_area_name']);
        }

        if (isset($params['view_name']) && strpos($params['view_name'], ',') !== false) {
            $params['view_name'] = explode(',', $params['view_name']);
        }

        if (isset($params['condo_type_group_id']) && strpos($params['condo_type_group_id'], ',') !== false) {
            $params['condo_type_group_id'] = explode(',', $params['condo_type_group_id']);
        }

        if (isset($params['resort_area_id']) && strpos($params['resort_area_id'], ',') !== false) {
            $params['resort_area_id_filter'] = $params['resort_area_id'];
            unset($params['resort_area_id']);
        }

        $search_layout = $options['search_layout'];

        $json_params = str_replace('"', '\'', json_encode($params));

        $method = "requestPropertyList('GetPropertyListWordPress', {$json_params});";

        // look for template in theme
        $template = ResortPro::locate_template( 'listing-search-template.php' );
        if ( empty( $template ) ) {
          // default template
          $template = trailingslashit( $this->dir ) . 'includes/templates/search/listing-search-template' . $search_layout . '.php';
        }

        $property_link = get_bloginfo("url");
        if (!empty($options['prepend_property_page'])) {
            $property_link .= "/" . $options['prepend_property_page'];
        }

        $arr_available_fields = array(
          "max_occupants",
          "bedrooms_number",
          "name",
          "area",
          "view",
          "pets",
          "rotation",
          "random",
          "price_low"
        );

        $max_occupants = (isset($options['inquiry_adults_max']) && $options['inquiry_adults_max'] > 0) ? $options['inquiry_adults_max'] : 1;
        $max_occupants_small = (isset($options['inquiry_children_max']) && $options['inquiry_children_max'] > 0) ? $options['inquiry_children_max'] : 1;
        $max_pets = (isset($options['inquiry_pets_max']) && $options['inquiry_pets_max'] > 0) ? $options['inquiry_pets_max'] : 1;

        $sorted_by = isset($_GET['sort_by']) ? filter_var ( $_GET['sort_by'], FILTER_SANITIZE_STRING) : $options['resortpro_default_filter'];

        if(!in_array($sorted_by, $arr_available_fields))
          $sorted_by = 'default';

        ob_start();
        include(trailingslashit($this->dir) . 'includes/templates/page-resortpro-browse-template.php');
        $output = ob_get_clean();

        return $output;
    }

    public function search_filter($attr = array())
    {
        $options = StreamlineCore_Settings::get_options();
        if ($options['property_description'] == 1) {
            $attr['use_description'] = 'yes';
        }
        if ($attr['sale_enabled'] == 'yes') {
            $sales = TRUE;
        }
        if ($attr['longterm_sale_template'] == "yes") {
            $longterm_sale_template = TRUE;
        }
        if ($attr['change_price']) {
            $change_price = $attr['change_price'];
        }
        $attr['page_results_number'] = $options['property_pagination'] ? $options['property_pagination'] : '1000';
        $attr['page_number'] = $_GET['page_number'] ? $_GET['page_number'] : '1';
        if ($_REQUEST['all_page'] == 'yes') {
            $attr['page_results_number'] = '10000';
            $attr['page_number'] = '1';
        }
        $attr['sort_by'] = $_REQUEST['resortpro_sw_filter'] ? $_REQUEST['resortpro_sw_filter'] : $options['resortpro_default_filter'];
        $attr['skip_units'] = $options['property_delete_units'];
        if (empty($_REQUEST['resortpro_sw_loc']) && !empty($options['property_loc_id'])) {
            $attr['location_id'] = $options['property_loc_id'];
        } else {
            $attr['location_id '] = ($_REQUEST['resortpro_sw_loc']) ? $_REQUEST['resortpro_sw_loc'] : false;
        }

        $search_layout = $options['search_layout'];

        $params = str_replace('"', '\'', json_encode($attr));

        $method = "requestPropertyList('GetPropertyListWordPress', {$params});";
        if (empty($search_layout)) {
            $search_layout = 1;
        }

        // look for template in theme
        $template = ResortPro::locate_template( 'listing-search-template.php' );
        if ( empty( $template ) ) {
          // default template
          $template = trailingslashit( $this->dir ) . 'includes/templates/search/listing-search-template' . $search_layout . '.php';
        }

        $property_link = get_bloginfo("url") . "/";
        if (!empty($options['prepend_property_page'])) {
            $property_link .= $options['prepend_property_page'];
        }

        ob_start();
        include(trailingslashit($this->dir) . 'includes/templates/page-resortpro-browse-template.php');
        $output = ob_get_clean();

        return $output;
    }

    /**
     * Get data from api
     */
    public function getData(){
        $request = new SibersStrimlineAPI();
        $data = $request->request();
    }

    /**
     *  Check query params and call api queries for merging response
     *
     * @return array|mixed
     */
    public function generateQuery($args){
        $bedroomData = array();
        $locationData = array();
        $rentalData = array();
        $dataArray = array();
        $shortData = array();
        foreach ($args as $name => $arg){
            $data = $this->getShortCodeParams($arg, $name);
            if(count($data)){
                $shortData[] = $data;
            }
        }
        $dataArray = array_merge($dataArray, $shortData);
        if(isset($_GET['bedrooms_number']) && count($_GET['bedrooms_number'])){
            foreach ($_GET['bedrooms_number'] as $bedrooms){
                foreach (explode(',', $bedrooms) as $bedroom){
                    if($bedroom != ''){
                        $bedroomData['bedrooms_number'][] = $bedroom;
                    }
                }
            }
            if(!empty($bedroomData)){
                array_push($dataArray, $bedroomData);
            }
        }
        if(isset($_GET['locations']) && count($_GET['locations'])){
            foreach ($_GET['locations'] as $location){
                $locationData['resort_area_id'][] = $location;
            }
            array_push($dataArray, $locationData);

        }
        if(isset($_GET['rental_type']) && count($_GET['rental_type'])){
            foreach ($_GET['rental_type'] as $rental_type){
                $rentalData['home_type_id'][] = $rental_type;
            }
            array_push($dataArray, $rentalData);

        }
        $params = array();
        if (isset($_GET['start'])) {
            $start = new DateTime($_GET['start']);
            $params['startdate'] = $start->format('m/d/Y');
        }
        if (isset($_GET['end'])) {
            $end = new DateTime($_GET['end']);
            $params['enddate'] = $end->format('m/d/Y');
        }
        $filterCombinations = $this->fill($dataArray);
        $queriesArgs = array();
        foreach ($filterCombinations as $filterCombination){
            $queriesArgs[] = StreamlineCore_Wrapper::prepareSearchArgs(array_merge($filterCombination, $params));
        }

        $request = new SibersStrimlineAPI();
        // call api method for getting data
        foreach ($queriesArgs as $queriesArg){
            $request->getData($queriesArg, 'GetPropertyAvailabilitySimple');
        }
        // check founded data
        $result_data = $this->prepareData($request->getResponse());
        return $result_data;
    }

    /**
     * add params to $_GET
     *
     */
    public function getShortCodeParams ($arg, $name){
        $data = array();
            if(in_array($name, $this->available_params)) {
                foreach ( explode(',', $arg)as $d){
                    if($name != 'bedrooms_number'){
                        $data[$name][] = $d;
                    }
                    $_GET[$name][] = $d;
                }
            }
        return $data;
    }

    /**
     *  Prepare data for rendering
     *
     * @param $result_data
     * @return array|mixed
     */
    public function prepareData($result_data){
        $return_data = array();
        // favorite hotels
        $fav =$this->getFavorites();
        foreach ($result_data as $key => $data){
            $data['fav'] = in_array($data['id'], $fav) ? 1 : 0;
            foreach ($data as $k => $d){
                // check data is needed
                if($this->checkNeededData($k)){
                    // get only daily price
                    if($k == 'price_data'){
                        $d = $d['daily'];
                    }
                  $return_data[$key][$k] = $d;
                }
            }
        }
        // check sort params exist
        $this->checkOrderParams();
        //sort data
        $return_data = $this->sortData($return_data);
        // return array of hotels data
        return $return_data;
    }

    /**
     *  Check if exist order params
     *
     */
    public function checkOrderParams(){
        // if order params exist
        if(isset($_GET['orderby'])){
            $this->orderBy = $_GET['orderby'];
        }
        // if sort param exist
        if(isset($_GET['sort'])){
            $this->sort = $_GET['sort'];
        }
    }

    /**
     * Apply sorting adn render data
     */
    public function change_sort(){
        // get founded data
        $data = $this->getStoredData();
        // check order data
        $this->checkOrderParams();
        // sort data
        $sortData = $this->sortData($data);
        // save data
        $this->setStoredData($sortData);
        // return function for rendering template
        return $this->search_results_paginate();
    }

    /**
     *  Set founded data to session for using
     *
     * @param $data
     */
    public function setStoredData($data){
        $_SESSION['data'] = serialize($data);
    }


    /**
     *  Get founded data
     *
     * @return array|mixed
     */
    public function getStoredData(){

        if(isset($_SESSION['data'])){
            return unserialize($_SESSION['data']);
        }
        return array();
    }

    /**
     *  Sort data dy params
     *
     * @param $data
     * @return mixed
     */
    public function sortData($data){
        usort($data , function ($item1, $item2) {
            if ($item1[$this->orderBy] == $item2[$this->orderBy]) return 0;
            // if sort is desc
            if($this->sort == 'desc'){
                return $item1[$this->orderBy] > $item2[$this->orderBy] ? -1 : 1;
            // if sort is asc
            }else{
                return $item1[$this->orderBy] < $item2[$this->orderBy] ? -1 : 1;
            }

        });
        return $data;
    }

    /**
     *  Check needed data to render
     *
     * @param $key
     * @return bool
     */
    public function checkNeededData($key){
        // needed data for rendering in template
        $needData = array(
            'id',
            'rating_count',
            'rating_average',
            'name',
            'location_name',
            'default_thumbnail_path',
            'price_data',
            'location_area_name',
            'fav'
        );

        if(in_array($key, $needData)) return true;

        return false;
    }


    /**
     *  Prepare queries conbinations
     *
     * @param $arr
     * @param int $idx
     * @return array
     */
    public function fill (&$arr, $idx = 0) {
        static $keys;
        static $max;
        static $results;
        if ($idx == 0) {
            $keys = array_keys($arr);
            $max = count($arr);
            $results = array();
        }
        if ($idx < $max) {
            $values = $arr[$keys[$idx]];
            foreach ($values as $key => $values){
                foreach ($values as $value) {
                    array_push($this->line, array($key=>$value));
                    $this->fill($arr, $idx+1);
                    array_pop($this->line);
                }
            }
        } else {
            $assocArray = array();
            foreach ($this->line as $key => $value){

                $assocArray[key($value)] = end($value);
            }
            $results[] = $assocArray;
        }
        if ($idx == 0) return $results;
    }

    /**
     * Get Bedrooms
     *
     * @return array
     */
    public function getBedrooms(){
        $rooms = array();

        for ($i=1; $i<7; $i++){
            if($i < 4){
                $rooms[$i][$i] = $i;
            }else if($i > 0){
                $rooms[4][$i] = '3+';
            }
        }
        ksort($rooms);
       return $rooms;
    }

    /**
     * Change favorite - add or remove
     *
     */
    function change_favorite(){
        if($_POST['fav'] == ''){
            $fav = array();
        }else{
            $fav = explode(',', $_POST['fav']);
        }
        $data = $this->getStoredData();
        $keyInData = array_search($_POST['hotel'], array_column($data, 'id'));
        //add to favourite
        if($_POST['method'] == 'add'){
            array_push($fav, $_POST['hotel']);
            $data[$keyInData]['fav'] = 1;
        //remove from favorite
        }else{
            if(($key = array_search($_POST['hotel'], $fav)) !== false) {
                unset($fav[$key]);
            }
            $data[$keyInData]['fav'] = 0;

        }
        $this->setStoredData($data);
        echo implode(',',$fav);die;
    }


    /**
     *  Get paginated data
     *
     * @param int $page
     */
    public function search_results_paginate($page = 1){

        if(isset($_POST['page'])){
            $page = $_POST['page'];
        }
        //hotels data used in template
        $data = $this->getPaginatedResult($page);
        // favourite hotels
        $fav = $this->getFavorites();
        ob_start();
        //render template
        include(trailingslashit($this->dir) . 'includes/templates/page-resortpro-listings-template_ajax.php');
        $output = ob_get_clean();
        wp_send_json(array('html' => $output, 'status' => 'success', 'data' => $data));
    }

    /**
     *  Get paginated results
     *
     * @param int $current_page - page number
     * @return array
     */
    public function getPaginatedResult($current_page = 1){
        $data = $this->getStoredData();
        $offset = ($current_page-1) * $this->perPage;
        $pageData = array('data' => array_slice($data, $offset, $this->perPage));
        // data for pagination
        $pagination = array('total' => count($data),
            'page' => $current_page,
            'per_page' => $this->perPage,
            'showing_start' => $offset+1,
            'showing_end'=> $offset + count($pageData['data']),
            'current_page' => $current_page);

        return array_merge($pagination, $pageData);
    }

    /**
     *  Get favorites hotels
     *
     * @return array
     */
    public function getFavorites(){
        $cookies = parse_ini_string( str_replace( ";" , "\n" , $_SERVER['HTTP_COOKIE']));
        $fav = array();
        if(isset($cookies['favorites'])){
            $fav = explode(',', $cookies['favorites']);
        }
        return $fav;
    }

    /**
     *  Search data
     *
     * @param array $params
     * @param bool $return_units
     * @return string
     */
    public function search_results($params = array(), $return_units = false)
    {
        // get all data from api by search params
        $totalData = $this->sortData($this->generateQuery($params));
        // save founded data
        $this->setStoredData($totalData);
        //used in template
        $fav = $this->getFavorites();
        //used in template
        $data = $this->getPaginatedResult();

        $max_occupants = (isset($options['inquiry_adults_max']) && $options['inquiry_adults_max'] > 0) ? $options['inquiry_adults_max'] : 1;
        $max_occupants_small = (isset($options['inquiry_children_max']) && $options['inquiry_children_max'] > 0) ? $options['inquiry_children_max'] : 1;
        $max_pets = (isset($options['inquiry_pets_max']) && $options['inquiry_pets_max'] > 0) ? $options['inquiry_pets_max'] : 1;
        $selectedBedrooms = array();
        if(isset($_GET['bedrooms_number'])){
            $selectedBedrooms = $_GET['bedrooms_number'];
        }
        $selectedLocation = array();
        if(isset($_GET['locations'])){
            $selectedLocation = $_GET['locations'];
        }
        $selectedRentalType = array();
        if(isset($_GET['rental_type'])){
            $selectedRentalType = $_GET['rental_type'];
        }
        //locations for filter
        $locationResorts = ResortProWrapper::get_location_resorts();
        //home types for filter
        $rentalTypes = ResortProWrapper::get_home_types();
        //bedrooms for filter
        $bedRooms = $this->getBedrooms();

        ob_start();
        //render template
        include(trailingslashit($this->dir) . 'includes/templates/page-resortpro-listings-template2.php');
        $output = ob_get_clean();

        return $output;

    }

    public function prepareShortCodeParams($params){

    }


    public function property_info()
    {
        if (defined("RESORTPRO_PROPERTY_ID")) :
            $site_id = (empty($options['site_id'])) ? FALSE : $options['site_id'];
            $sale = $_GET['sale'] ? $_GET['sale'] : 0;
            $long_term_enabled = $_GET['longterm_sale_template'] ? $_GET['longterm_sale_template'] : 0;

            $property_data = StreamlineCore_Wrapper::get_property_info_by_seo_name( RESORTPRO_PROPERTY_SEO_PAGE_NAME );
            $property1 = $property_data['data'];

            $property_data = StreamlineCore_Wrapper::get_property_info( RESORTPRO_PROPERTY_ID );

            $property2 = $property_data['data'];

            $property = array_merge($property2, $property1);

            $property_gallery = $property['gallery']['image'];

            $layout = StreamlineCore_Settings::get_options( 'unit_layout' );

            // look for template in theme
            $template = ResortPro::locate_template( 'listing-detail-template.php' );
            if ( empty( $template ) ) {
              // default template
              if ( (int)$layout == 2 || (int)$layout == 3 ) {
                $template = trailingslashit( $this->dir ) . 'includes/templates/details/page-resortpro-listing-detail-template' . $layout . '.php';
              } else {
                $template = trailingslashit( $this->dir ) . 'includes/templates/details/page-resortpro-listing-detail-template1.php';
              }
            }

            $options = StreamlineCore_Settings::get_options();

            $ssl_enabled = (isset($options['checkout_use_ssl']) && $options['checkout_use_ssl'] == 1) ? true : false;

            $checkout_url = ($ssl_enabled) ? str_replace('http://','https://', get_permalink(get_page_by_slug('checkout'))) : get_permalink(get_page_by_slug('checkout'));

            $start_date = (isset($_GET['sd']) && !empty($_GET['sd'])) ? urldecode($_GET['sd']) : '';
            $end_date = (isset($_GET['ed']) && !empty($_GET['ed'])) ? urldecode($_GET['ed']) : '';
            $occupants = (isset($_GET['oc']) && !empty($_GET['oc'])) ? $_GET['oc'] : '1';
            $occupants_small = (isset($_GET['ch']) && !empty($_GET['ch'])) ? $_GET['ch'] : '0';
            $pets = (isset($_GET['pets']) && !empty($_GET['pets'])) ? $_GET['pets'] : '0';

            $hash = (isset($_REQUEST['hash']) && !empty($_REQUEST['hash'])) ? filter_var ( $_REQUEST['hash'], FILTER_SANITIZE_STRING) : '';

            $res = (isset($_GET['ed']) && !empty($_GET['ed'])) ? 1 : 0;
            $min_stay = (isset($options['unit_book_default_stay']) && $options['unit_book_default_stay'] > 0) ? $options['unit_book_default_stay'] : 2;
            $checkin_days = (isset($options['unit_book_checkin_date']) && $options['unit_book_checkin_date'] > 0) ? $options['unit_book_checkin_date'] : 0;

            $max_adults = $property['max_adults'];
            $max_pets = $property['max_pets'];
            $max_children = $property['max_occupants'];

            $booknow_title = (isset($options['unit_book_title']) && !empty($options['unit_book_title'])) ? $options['unit_book_title'] : "";
            $inquiry_title = (isset($options['inquiry_title']) && !empty($options['inquiry_title'])) ? $options['inquiry_title'] : "";


            $arrive_label = (isset($options['unit_book_checkin_label']) && !empty($options['unit_book_checkin_label'])) ? $options['unit_book_checkin_label'] : "Arrive";
            $depart_label = (isset($options['unit_book_checkout_label']) && !empty($options['unit_book_checkout_label'])) ? $options['unit_book_checkout_label'] : "Depart";
            $adults_label = (isset($options['unit_book_adults_label']) && !empty($options['unit_book_adults_label'])) ? $options['unit_book_adults_label'] : "Adults";
            $children_label = (isset($options['unit_book_children_label']) && !empty($options['unit_book_children_label'])) ? $options['unit_book_children_label'] : "Children";
            $pets_label = (isset($options['unit_book_pets_label']) && !empty($options['unit_book_pets_label'])) ? $options['unit_book_pets_label'] : "Pets";

            $show_captions = (isset($options['property_use_captions']) && $options['property_use_captions'] == '1') ? true : false;

            $slider_height = (isset($options['property_slider_height']) && is_numeric($options['property_slider_height'])) ? $options['property_slider_height'] : 420;

            $sticky_class = (isset($options['book_sticky']) && $options['book_sticky'] == '1') ? "sticky" : "";
            $sticky_spacing = (isset($options['top_spacing']) && !empty($options['top_spacing'])) ? 'data-top-spacing="'.$options['top_spacing'].'"' : "";
            $sticky_spacing .= (isset($options['bottom_spacing']) && !empty($options['bottom_spacing'])) ? 'data-bottom-spacing="'.$options['bottom_spacing'].'"' : "";

            ob_start();
            include($template);
            $output = ob_get_clean();

            return $output;

        endif;
    }

    public function custom_quote(){

      $reservation_qhash = filter_var ( $_REQUEST['qhash'], FILTER_SANITIZE_STRING);
      $reservation_hash = filter_var ( $_REQUEST['hash'], FILTER_SANITIZE_STRING);

      $options = StreamlineCore_Settings::get_options();

      $ssl_enabled = (isset($options['checkout_use_ssl']) && $options['checkout_use_ssl'] == 1) ? true : false;

      $checkout_url = ($ssl_enabled) ? str_replace('http://','https://', get_permalink(get_page_by_slug('checkout'))) : get_permalink(get_page_by_slug('checkout'));

      $checkout_url2 = $checkout_url;

      $checkout_url = add_query_arg('hash', $reservation_hash, $checkout_url);

      if (empty($reservation_hash) && empty($reservation_qhash)) {
          $message .= '<div class="alert alert-danger">' . __( 'You are attempting to load information on a quote that does not exist.', 'streamline-core' ) . '</div>';
          return $message;
      }

      if (!empty($reservation_hash)) {

        $reservations_quote = StreamlineCore_Wrapper::get_custom_vacation_quote( $reservation_hash );

        if(isset($reservations_quote['data']['reservations']['resrvation']['id'])){
          $reservations['resrvation'][] = $reservations_quote['data']['reservations']['resrvation'];
        }else{
          $reservations = $reservations_quote['data']['reservations'];
        }

        $property_link = get_bloginfo("url");
        if (!empty($options['prepend_property_page'])) {
            $property_link .= "/" . $options['prepend_property_page'];
        }

        if( isset( $reservations_quote['status']['code'] ) ){
        // Error retrieving quote.
          $template = ResortPro::locate_template( 'quote-error.php' );
          if ( empty( $template ) ) {
            $template = trailingslashit($this->dir) . 'includes/templates/quote-error.php';
          }
		    } else {

          $template = ResortPro::locate_template( 'quote.php' );
          if ( empty( $template ) ) {
            $template = trailingslashit($this->dir) . 'includes/templates/quote.php';
          }
        }

        ob_start();
        include($template);
        $output = ob_get_clean();

        return $output;
      }
    }

    public function property_checkout()
    {
        $options = StreamlineCore_Settings::get_options();
        $min_days_error = false;
        $future_days_error = false;
        $difference = 0;
        $differenceFuture = 0;

        $checkout_title = (!empty($options['checkout_title'])) ? $options['checkout_title'] : __( 'Pricing, Optional Additional Services, and Booking', 'streamline-core' );
        $checkout_description = $options['checkout_description'];

        $has_coupon = $options['property_show_coupon_code'];

        $default_country = 'US';

        $online_bookings = 0;

        if(isset($_REQUEST['hash'])){
          $hash = filter_var ( $_REQUEST['hash'], FILTER_SANITIZE_STRING);

          $reservation_data = StreamlineCore_Wrapper::get_reservation_info( $hash );

          $str_checkin = $reservation_data['data']['reservation']['startdate'];
          $str_checkout = $reservation_data['data']['reservation']['enddate'];

          $property_data = StreamlineCore_Wrapper::get_property_info( $reservation_data['data']['reservation']['unit_id'] );
          if(isset($property_data['data']))
            $online_bookings = $property_data['data']['online_bookings'];

        }else{
          $hash = '';
          $property_data = StreamlineCore_Wrapper::get_property_info( absint( $_REQUEST['unit'] ) );
          if(isset($property_data['data']))
            $online_bookings = $property_data['data']['online_bookings'];

          $str_checkin = filter_var ( $_REQUEST['sd'], FILTER_SANITIZE_STRING);
          $str_checkout = filter_var ( $_REQUEST['ed'], FILTER_SANITIZE_STRING);
        }

        if( version_compare ( '5.3', PHP_VERSION, '<' ) ){
          $checkin = new DateTime($str_checkin);
          $checkout = new DateTime($str_checkout);
          $currentDate = new DateTime(date('Y/m/d'));
          $difference = $currentDate->diff($checkin);
          $difference = $difference->format('%a');

          if(isset($options['lm_booking_future_days']) && $options['lm_booking_future_days'] > 0){
            $future = $currentDate->modify('+'.$options['lm_booking_future_days'] . ' day');

            $differenceFuture = $future->diff($checkout);
            $differenceFuture = $differenceFuture->format('%r%a');
          }

        }else{

          $currentDate = strtotime(date('Y-m-d'));

          $checkin = strtotime($str_checkin);
          $checkout = strtotime($str_checkout);

          $seconds = abs($checkin - $currentDate);

          $years = floor($seconds / (365*60*60*24));
          $months = floor(($seconds - $years * 365*60*60*24) / (30*60*60*24));
          $difference = floor(($seconds - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

          if(isset($options['lm_booking_future_days']) && $options['lm_booking_future_days'] > 0){
            $future = strtotime('+'.$options['lm_booking_future_days'] . ' day');

            if($checkout > $future){
              $differenceFuture = 1;
            }
          }
        }
        if(isset($options['lm_booking_check']) && $options['lm_booking_check'] == 1) {

          if($difference <= $options['lm_booking_days']) {
            $min_days_error = true;
          }
        }
        if(isset($options['lm_booking_future_check']) && $options['lm_booking_future_check'] == 1) {

          if($differenceFuture > 0) {
            $future_days_error = true;
          }
        }


        
        $ssl_enabled = (isset($options['checkout_use_ssl']) && $options['checkout_use_ssl'] == 1) ? true : false;
        $pbg_enabled = (isset($options['enable_paybygroup']) && $options['enable_paybygroup'] == 1 && !empty($options['paybygroup_merchant_id'])) ? true : false;

        $checkout_url = ($ssl_enabled) ? str_replace('http://','https://', get_permalink(get_page_by_slug('checkout'))) : get_permalink(get_page_by_slug('checkout'));

        // look for template in theme
        $template = ResortPro::locate_template( 'checkout-template.php' );
        if ( empty( $template ) ) {
          // default template
          $template = trailingslashit( $this->dir ) . 'includes/templates/page-resortpro-checkout-template.php';
        }

        ob_start();
        include($template);
        $output = ob_get_clean();

        return $output;
    }

    public function property_thankyou() {

        $content = StreamlineCore_Settings::get_options( 'thankyou_content' );

        $content = str_replace("%confirmation_id%", $_REQUEST['confirmation_id'], $content);
        $content = str_replace("%location_name%", $_REQUEST['location_name'], $content);
        $content = str_replace("%condo_type_name%", $_REQUEST['condo_type_name'], $content);
        $content = str_replace("%unit_name%", $_REQUEST['unit_name'], $content);
        $content = str_replace("%startdate%", $_REQUEST['startdate'], $content);
        $content = str_replace("%enddate%", $_REQUEST['enddate'], $content);
        $content = str_replace("%occupants%", $_REQUEST['occupants'], $content);
        $content = str_replace("%occupants_small%", $_REQUEST['occupants_small'], $content);
        $content = str_replace("%pets%", $_REQUEST['pets'], $content);
        $content = str_replace("%price_common%", $_REQUEST['price_common'], $content);
        $content = str_replace("%price_balance%", $_REQUEST['price_balance'], $content);
        $content = str_replace("%travelagent_name%", $_REQUEST['travelagent_name'], $content);
        $content = str_replace("%email%", $_REQUEST['email'], $content);
        $content = str_replace("%fname%", $_REQUEST['fname'], $content);
        $content = str_replace("%lname%", $_REQUEST['lname'], $content);
        $content = str_replace("%unit_id%", $_REQUEST['unit_id'], $content);

        $sitename = get_bloginfo('name');

        if(empty($sitename))
          $sitename = 'Website';

        $confirmation_id  = filter_var ( $_REQUEST['confirmation_id'], FILTER_SANITIZE_STRING);
        $reservation_price = StreamlineCore_Wrapper::get_reservation_price( $confirmation_id );
        $reservation_info = StreamlineCore_Wrapper::get_reservation_info( null, $confirmation_id );

        $revenue = $reservation_price['data']['price'];
        $total = $reservation_price['data']['total'];

        if($reservation_price['data']['optional_fees']['id']){
          if(isset($reservation_price['data']['optional_fees']["active"]) && $reservation_price['data']['optional_fees']["active"] == 1){
            $revenue += $reservation_price['data']['optional_fees']['value'];
          }
        }

        if(count($reservation_price['data']['optional_fees']) > 0){
          foreach ($reservation_price['data']['optional_fees'] as $key => $value) {
            if(isset($value['active']) && $value['active'] == 1){
              $revenue += $value['value'];
            }
          }
        }

        // Localize the script with new data
        $arr_transaction = array(
          'id' => $confirmation_id,
          'affiliation' => $sitename,
          'revenue' => $revenue,
          'rent' => $reservation_price['data']['price'],
          'shipping' => '0',
          'tax' => $reservation_price['data']['taxes'],
          'unit_name' => $reservation_price['data']['unit_name'],
          'optional_fees' => $reservation_price['data']['optional_fees']
          );

        // Enqueued script with localized data.
        wp_enqueue_script( 'ecommerce' );

        wp_localize_script( 'ecommerce', 'streamline_transaction', $arr_transaction );

        $template = ResortPro::locate_template( 'checkout-thankyou.php' );

        if ( empty( $template ) ) {
          return $content;
        }

        ob_start();
        include($template);
        $output = ob_get_clean();

        return $output;
    }

    function featured_properties($params = array(), $return_units = false){

        $units = StreamlineCore_Wrapper::get_random_units( $params['number'], $params['ids'] );

        // look for template in theme
        $template = ResortPro::locate_template( 'featured-property-template.php' );
        if ( empty( $template ) ) {
          // default template
          $template = trailingslashit( $this->dir ) . 'includes/templates/featured-property-template.php';
        }

        ob_start();
        foreach ($units as $unit) {
          $property_link = get_bloginfo("url");
          if (!empty($options['prepend_property_page'])) {
              $property_link .= "/" . $options['prepend_property_page'];
          }
          $property_link .= "/{$unit['seo_page_name']}";
          include($template);
        }
        $template_content = ob_get_clean();

        return $template_content;
    }

    function testimonials($params = array()){
      $feedbacks = apply_filters( 'streamline-feedbacks', StreamlineCore_Wrapper::get_feedback( array( 'limit' => $params['number'], 'min_points' => $params['min_points'] ) ), $params );

      //var_dump($feedbacks);

      // look for template in theme
      $template = ResortPro::locate_template( 'testimonials.php' );
      if ( empty( $template ) ) {
        // default template
        $template = trailingslashit( $this->dir ) . 'includes/templates/testimonials-template.php';
      }


        ob_start();
        foreach ($feedbacks as $key => $feedback) {
          # code...
          $divider = ($feedback['points'] > 5) ? 20 : 1;
          include($template);
        }

        $output = ob_get_clean();
        return $output;

    }

    function parse_featured_template($template, $unit){
        foreach ($unit as $key=>$value){
            $template = str_replace("%$key%",$value,$template);
        }

        return $template;
    }

    function terms_and_conditions(){
        $content = StreamlineCore_Wrapper::get_company_policies();

        $html = '';
        if(isset($content['data']['document_html_code']) && is_string($content['data']['document_html_code']))
          $html = $content['data']['document_html_code'];
        
        return $html;
    }

    function prefix_url_rewrite_templates()
    {
      global $wp_query;

      if (isset($wp_query->query_vars['property']) && !empty($wp_query->query_vars['property'])) {
        $seo_name = $wp_query->query_vars['property'];

        $options = StreamlineCore_Settings::get_options();

        if (!empty($options['property_use_html'])) //.html must be in the end
            $seo_name = substr($seo_name, 0, -5);

        $result = StreamlineCore_Wrapper::verify_seo_page_name( $seo_name );

        $property_info = $result['data'];

        if (!isset($property_info) || !array_key_exists('id', $property_info)) {
            $wp_query->set_404();
            status_header(404);
        } else {

          define("RESORTPRO_PROPERTY_SEO_PAGE_NAME", $seo_name);
          define("RESORTPRO_PROPERTY_ID", $property_info['id']);

          if (!empty($options['property_seo_put_canonical']))
          {
            add_filter( 'wpseo_canonical', '__return_false' );
            $canonical = StreamlineCore_Wrapper::get_unit_permalink( $seo_name );
            add_action('wp_head', create_function("", "echo '<link rel=\"canonical\" href=\"$canonical\" />\n';"));
          }

          if (!empty($options['property_seo_put_title']) && !empty($property_info['seo_title']))
          {
            $seo_title = htmlspecialchars($property_info['seo_title'],ENT_QUOTES);
            define('RESORTPRO_PROPERTY_SEO_TITLE', $seo_title);
            //add_filter( 'wp_title', 'sl_the_title', 20, 3);
            add_filter( 'wp_title', array($this, 'sl_the_title'), 20, 3);

            add_filter('document_title_parts', array($this,'sl_title_parts'));
          }

          if (!empty($options['property_seo_put_description']) && !empty($property_info['seo_description']))
          {
            $description = htmlspecialchars($property_info['seo_description'],ENT_QUOTES);
             add_action('wp_head', create_function("", "echo '<meta name=\"description\" content=\"$description\" />\n';"));
           }

          if (!empty($options['property_seo_put_keywords']) && !empty($property_info['seo_keywords']))
          {
            $keywords = htmlspecialchars($property_info['seo_keywords'],ENT_QUOTES);
             add_action('wp_head', create_function("", "echo '<meta name=\"keywords\" content=\"$keywords\" />\n';"));
          }

          if (!empty($property_info['additional_seo']) && !empty($property_info['additional_seo']) && (substr(trim($property_info['additional_seo']),0,1) == "<") )
          {   //must be a tag
            $additional_seo = addcslashes($property_info['additional_seo'],"'");
            add_action('wp_head', create_function("", "echo '$additional_seo\n';"));
          }
        }
      }

      return true;
    }

    function handle_404($wp)
    {
      if (!is_404())
          return; //nothing to do because our page is non-existent

      $url = $this->get_relative_url($_SERVER["REQUEST_URI"]);

      $options = StreamlineCore_Settings::get_options();

	    // strip the url down to just the unit seo name
	    // strip out extra to find seo_name
      $url = str_replace( array( '.html', $options['prepend_property_page'] ), '', $wp->request );
      $url = trim( $url, ' /');

      $result = StreamlineCore_Wrapper::verify_seo_page_name( $url );


      $property_info = $result['data'];

      if (!is_array($property_info))
          return;

      if (!array_key_exists('id', $property_info))
          return;

      define("RESORTPRO_PROPERTY_SEO_PAGE_NAME", $url);
      define("RESORTPRO_PROPERTY_ID", $property_info['id']);

      if (!empty($options['property_seo_put_canonical']))
      {
        add_filter( 'wpseo_canonical', '__return_false' );
        $canonical = StreamlineCore_Wrapper::get_unit_permalink( $url );
        add_action('wp_head', create_function("", "echo '<link rel=\"canonical\" href=\"$canonical\" />\n';"));
      }


      if (!empty($options['property_seo_put_title']) && !empty($property_info['seo_title']))
      {
        $seo_title = htmlspecialchars($property_info['seo_title'],ENT_QUOTES);
        define('RESORTPRO_PROPERTY_SEO_TITLE', $seo_title);

        add_filter( 'wp_title', array($this, 'sl_the_title'), 20, 3);

        add_filter('document_title_parts', array($this,'sl_title_parts'));
      }

      if (!empty($options['property_seo_put_description']) && !empty($property_info['seo_description']))
      {
        $description = htmlspecialchars($property_info['seo_description'],ENT_QUOTES);
        add_action('wp_head', create_function("", "echo '<meta name=\"description\" content=\"$description\" />\n';"));
      }

      if (!empty($options['property_seo_put_keywords']) && !empty($property_info['seo_keywords']))
      {
        $keywords = htmlspecialchars($property_info['seo_keywords'],ENT_QUOTES);
        add_action('wp_head', create_function("", "echo '<meta name=\"keywords\" content=\"$keywords\" />\n';"));
      }

      if (!empty($property_info['additional_seo']) && !empty($property_info['additional_seo']) && (substr(trim($property_info['additional_seo']),0,1) == "<") ){
        //must be a tag
        $additional_seo = addcslashes($property_info['additional_seo'],"'");
        add_action('wp_head', create_function("", "echo '$additional_seo\n';"));
      }

      header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
      global $wp_query;
      $fake = new stdClass();
      $fake->ID = StreamlineCore_Settings::get_options( 'page_property_info' );
      $fake->post_title = get_the_title($fake->ID);
      $fake->post_content = "[resortpro-property-info]";
      $fake->post_type = 'page';
      $fake->post_parent = 0;
      $fake->post_status = 'publish';
      $fake->comment_status = 'closed';
      $fake->ping_status = 'closed';
      $wp_query->posts = array($fake);
      $wp_query->post = $fake;
      $wp_query->post_count = 1;
      $wp_query->is_page = true;
      $wp_query->is_404 = false;
      $wp_query->set('pagename', 'property-info');
      add_action('template_redirect', 'get_resortpro_page_template');
      remove_action('wp_head', 'feed_links_extra', 3);
    }

    function get_relative_url($url, $base = null)
    {
        if ($base === null)
            $base = get_option('siteurl');
        $url = parse_url($url, PHP_URL_PATH);
        $home = parse_url($base, PHP_URL_PATH);
        if (!$home)
            $home = "/";
        $url = substr($url, strlen($home));
        $url = trim($url, "/");
        return $url;
    }

    public static function file_url($filepath = '')
    {
        return plugins_url($filepath, __FILE__);
    }

    public static function file_path($file)
    {
        return ABSPATH . 'wp-content/plugins/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)) . $file;
    }

	/**
	 * assets_url
	 *
	 * Echoes the path to the assets folder within the StreamlineCore plugin.
	 *
	 * @since  2.0.1
	 * @access  public
	 *
	 * @param  text  $path    relative path to be added to the assets url
	 *
	 * @uses  get_assets_url()            Gets the assets url
	 */
    public static function assets_url( $path = null ){
    	echo self::$_instance->get_assets_url( $path );
    }

	/**
	 * get_assets_url
	 *
	 * Returns the path to the assets folder within the StreamlineCore plugin.
	 *
	 * @since  2.0.1
	 * @access  public
	 *
	 * @param  text  $path    relative path to be added to the assets url
	 * @return  text  url with trailing slash
	 *
	 * @uses  esc_url()            Safe the assets url
	 */
    public function get_assets_url( $path = null ){
		return esc_url( $this->assets_url . $path ) ;
    }

    static function dropdown($name, $options, $cur_value, $zero_option = null, $ng_model = null, $plus = null)
    {
      $angular_str = (!empty($ng_model)) ? 'ng-init="'.$ng_model.'=\''.$cur_value.'\'" ng-model="' . $ng_model . '" ng-change="availabilitySearch(search)"' : '';

      $s = "<select name=\"$name\" id=\"$name\" class=\"form-control\" {$angular_str}>";
      if (is_array($zero_option))
          $s .= "<option value=\"{$zero_option[0]}\">{$zero_option[1]}</option>";
      foreach ($options as $value => $label) {
          if($plus == 1){
            $label .= "+";
          }
          $selected = ((string)$value == (string)$cur_value) ? " selected=\"selected\"" : "";
          $s .= "<option value=\"$value\"$selected>$label</option>";
      }
      $s .= "</select>";

      return $s;
    }

    static function range($min_value, $max_value)
    {
        return array_combine(range($min_value, $max_value), range($min_value, $max_value));
    }

    /**
     * look up template in theme, supports backwards combability for both template directory and name
     */
    public static function locate_template( $template_name, $load = FALSE, $require_once = TRUE ) {
      // directories to check, in order
      $directory_arr = array( 'streamline', 'streamline_templates', 'resortpro_templates', '' );

      // prefix template name with resortpro for backwards compatibility
      if ( stripos( $template_name, 'resortpro-' ) === FALSE ) {
        $template_name_arr = array( $template_name, 'resortpro-' . $template_name );
      } else {
        $template_name_arr = array( str_replace( 'resortpro-', '', $template_name ), $template_name);
      }

      // loop through directories and template names and try and locate template
      foreach ( $directory_arr as $directory ) {
        foreach ($template_name_arr as $template_name ) {
          $template = locate_template( $directory . DIRECTORY_SEPARATOR . $template_name, $load, $require_once );
          if ( ! empty( $template ) ) {
            return $template;
          }
        }
      }

      // nothing found
      return '';
    }

    public function googlemap($params=array())
    {
      wp_enqueue_script( 'google_maps_marker', $this->assets_url . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'src/js/google_maps.js' : 'dist/js/google_maps.min.js' ), array( 'jquery' ) );

      $popup_height = isset($params['max_popup_height'])?$params['max_popup_height']."px":"auto";
      $map_width = intval($params['map_width']) ? intval($params['map_width']).'px' : '100%';
      $map_height = intval($params['map_height']) ? intval($params['map_height']).'px' : '400px';
      $map_id = ($params['map_id']) ? $params['map_id'] : 'resortpro_map';
      $map_icon = ($params['map_icon']) ? $params['map_icon'] : 'default';
      $map_cache = intval($params['map_cache']) ? intval($params['map_cache']) : 24;

      $transient = array();

      foreach ($params as $k=>$v)
      {
        if (substr($k,0,4)!='map_')
          $transient[$k] = $v;
      }

      $units = array();

      $transient = 'resortpro-map-'.substr(md5(serialize($transient)),0,16);

      if ( false === ( $units = get_transient( $transient ) ) )
      {
        if (isset($params['location_area_name']) && strpos($params['location_area_name'], ',') !== false) {
          $params['location_area_name'] = explode(',', $params['location_area_name']);
        }

        if (isset($params['view_name']) && strpos($params['view_name'], ',') !== false) {
          $params['view_name'] = explode(',', $params['view_name']);
        }

        if (isset($params['condo_type_group_id']) && strpos($params['condo_type_group_id'], ',') !== false) {
          $params['condo_type_group_id'] = explode(',', $params['condo_type_group_id']);
        }

        if (isset($params['resort_area_id']) && strpos($params['resort_area_id'], ',') !== false) {
          $params['resort_area_id_filter'] = $params['resort_area_id'];
          unset($params['resort_area_id']);
        }

        $results = StreamlineCore_Wrapper::search($params);

        if($results['data']['available_properties']['pagination']['total_units'] > 0){
          if($results['data']['available_properties']['pagination']['total_units'] == 1){
            $units[] = $results['data']['property'];
          }else{
            $units = $results['data']['property'];
          }
        }

        set_transient( $transient, $units, $map_cache * 3600);
      }

      $places = array();
      foreach ($units as $unit)
      {
        $json = array();

        if(!empty($unit['location_latitude']) && !empty($unit['location_longitude'])){

          $json['latlng'] = array($unit['location_latitude'], $unit['location_longitude']);
          $json['key'] =  round($json['latlng'][0], 4).",".round($json['latlng'][1], 4);
          $json['title'] = self::get_unit_name($unit);

          $url = StreamlineCore_Wrapper::get_unit_permalink($unit['seo_page_name']);

          $json['html'] = '<a href=\''.$url.'\'><strong>'.$unit['location_name'].'</strong><br /><img src=\''.$unit['default_thumbnail_path'].'\' style=\'width:100px;margin-right:4px;\' align=\'left\'/></a><br />';
          $json['html'] .= __( 'Bedrooms:', 'streamline-core' ) . ' ' . $unit['bedrooms_number'] . '<br />';
          $json['html'] .= __( 'Bathrooms:', 'streamline-core' ) . ' ' . $unit['bathrooms_number'] . '<br />';
          $json['html'] .= __( 'Max. Adults:', 'streamline-core' ) . ' ' . $unit['max_adults'];
          $json['html'] .= '<div style=\'clear:both;\'></div>';

          $places[] = $json;
        }
      }

      foreach($places as $result_item){
          if(!empty($popup_height)){
              $result_item['html'] = "<div style='display:block ;max-height: $popup_height; overflow-y:auto;'>".$result_item['html']."</div>";
          }

          $places_update[]= $result_item;
      }

      wp_localize_script( 'google_maps_marker', 'streamline_gmap', array('map_id' => $map_id,
                                                              'places' => $places_update,
                                                              'icon'   => $map_icon ));

      $output = "<div id=\"{$map_id}\" style=\"width:{$map_width};height:{$map_height};\"></div>";

      return $output;
    }

  static function get_file() {
    return self::$_instance->file;
  }

  static function get_token() {
    return self::$_instance->_token;
  }

  static function get_vendor_url() {
    return self::$_instance->vendor_url;
  }

  static function get_unit_name(&$unit)
  {
    if ($unit['lodging_type_id'] == 3)
      return $unit['location_name'];
    else
      return $unit['name'];
  }

  function sl_title_parts($title){

    $seo_title = RESORTPRO_PROPERTY_SEO_TITLE;

    if(!empty($seo_title)){
      $title['title'] = $seo_title;
    }

    return $title;
  }

  function sl_the_title( $title, $sep, $seplocation){

    $seo_title = RESORTPRO_PROPERTY_SEO_TITLE;

    if($seplocation == 'right'){
      return sprintf("%s %s ", $seo_title, $sep);
    }else{
      return sprintf("%s %s ", $sep, $seo_title);
    }
  }
}
