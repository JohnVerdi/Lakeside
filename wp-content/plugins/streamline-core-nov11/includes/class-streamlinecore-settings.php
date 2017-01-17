<?php
/**
 * Settings class for StreamlineCore plugin
 */
class StreamlineCore_Settings{
  // single instance of StreamlineCore_Settings
  protected static $_instance;
  // option group
  protected $_option_group;
  // option name
  protected $_option_name;
  // settings page
  protected $_settings_page;
  // options
  protected $_option_arr;

  // construct
  public function __construct() {
    // class variables
    $this->_option_group            = 'streamlinecore-settings-group';
    $this->_option_name             = 'streamlinecore-settings';
    $this->_settings_page           = 'streamlinecore_settings';
    $this->_option_arr              = array();

    // hooks
    add_action( 'admin_init', array( &$this, 'register_settings' ) );
    add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
    add_filter( 'plugin_action_links_' . plugin_basename( ResortPro::get_file() ), array( &$this, 'plugin_action_links' ) );
    add_filter( 'pre_option_pmt_plugin_resortpro', array( &$this, 'get_old_option' ), 10, 2 );
  }

  // old plugin options trying to be loaded
  public function get_old_option( $false, $option_name ) {
    _deprecated_argument( 'get_option', '2.1.6', sprintf(__( 'Please use "%s" instead.', 'streamline-core' ), $this->_option_name ) );
    return get_option( $this->_option_name );
  }

  // register plugin settings
  public function register_settings() {
    register_setting( $this->_option_group, $this->_option_name, array( &$this, 'sanitize_settings' ) );
  }

  // admin menu
  public function admin_menu() {
    // add settings page
    add_options_page( __('StreamlineCore Settings', 'streamline-core'), __('StreamlineCore Settings', 'streamline-core'), 'manage_options', $this->_settings_page, array( &$this, 'settings_page' ) );
  }

  // plugin action links
  public function plugin_action_links( $link_arr ) {
    // add settings link
    $link_arr[] = '<a href="' . admin_url( 'options-general.php?page=' . $this->_settings_page ) . '">' . __( 'Settings', 'streamline-core' ) . '</a>';

    return $link_arr;
  }

  // settings page
  public function settings_page() {
    // check for transients clear
    if ( isset( $_POST['transients'] ) && $_POST['transients'] == 'clear' ) {
      // clear transients
      $transients_cleared = StreamlineCore_Wrapper::clear_transients();
    }

    // get options
    $option_arr = $this->get_options();

    // load admin template
    require_once( __DIR__ . '/templates/admin/settings-page.php' );
  }

  // sanatize settings
  public function sanitize_settings( $setting_arr ) {
    $setting_arr = array_merge( self::get_default_options(), $setting_arr );

    return $setting_arr;
  }

  // get options
  public static function get_options( $field = NULL ) {
    // get instance
    $instance = self::get_instance();

    // check that instance options are set
    if ( ! sizeof( $instance->_option_arr ) ) {
      // get options
      $instance->_option_arr = get_option( $instance->_option_name );
    }

    // check for specific field
    return ( ! is_null( $field ) ? ( is_array( $instance->_option_arr) && array_key_exists( $field, $instance->_option_arr ) ? $instance->_option_arr[ $field ] : FALSE ) : $instance->_option_arr );
  }

  // get default options
  static public function get_default_options() {
    $option_arr = array(
      'book_now'                          => 'enabled', // select
      'book_sticky'                       => 0, // checkbox
      'booking_blocked_requests'          => 0, // checkbox
      'bottom_spacing'                    => '', // text
      'checkout_create_leads'             => 0, // checkbox
      'checkout_description'              => '', // textarea
      'checkout_title'                    => '', // text
      'checkout_use_addons'               => 0, // checkbox
      'checkout_use_ssl'                  => 0, // checkbox
      'endpoint'                          => 'https://www.resortpro.net/api/json', // text
      'filter_location_areas'             => array(), // multiple checkboxes
      'id'                                => '', // text
      'inquiry_adults_label'              => '', // text
      'inquiry_adults_max'                => 99, // select
      'inquiry_checkin_date'              => 1, // select
      'inquiry_checkin_label'             => '', // text
      'inquiry_checkout_label'            => '', // text
      'inquiry_children_label'            => '', // text
      'inquiry_children_max'              => 99, // select
      'inquiry_default_stay'              => 1, // select
      'inquiry_pets_label'                => '', // text
      'inquiry_pets_max'                  => 99, // select
      'inquiry_thankyou_msg'              => '', // textarea
      'inquiry_thankyou_url'              => '', // text
      'inquiry_title'                     => '', // text
      'message_no_inventory'              => '', // text
      'message_restriction'               => '', // text
      'page_layout'                       => 'boxed', // select
      'phone'                             => '', // phone
      'prepend_property_page'             => '', // text
      'property_brba_text_ba'             => '', // text
      'property_brba_text_br'             => '', // text
      'property_card_type_amex'           => 0, // checkbox
      'property_card_type_discover'       => 0, // checkbox
      'property_card_type_master_card'    => 0, // checkbox
      'property_card_type_visa'           => 0, // checkbox
      'property_contact_text'             => '', // text
      'property_coupon_description'       => '', // textarea
      'property_delete_book_now_units'    => '', // text
      'property_delete_text_units'        => '', // text
      'property_delete_units'             => '', // text
      'property_description'              => 0, // select (Amentities)
      'property_loc_id'                   => 0, // select (All)
      'property_lodgin_type'              => 0, // select (Search All)
      'property_pagination'               => '', // text
      'property_pets_text'                => '', // text
      'property_price_text'               => 'nothing', // select
      'property_seo_put_canonical'        => 0, // checkbox
      'property_seo_put_description'      => 0, // checkbox
      'property_seo_put_keywords'         => 0, // checkbox
      'property_seo_put_title'            => 0, // checkbox
      'property_show_coupon_code'         => 0, // checkbox
      'property_show_pets'                => 0, // select
      'property_show_rating'              => 0, // checkbox
      'property_show_sorting_options'     => 0, // checkbox
      'property_sleeps_text'              => '', // text
      'property_slider_height'            => '', // text
      'property_title_additional_text'    => '', // text
      'property_title_structure'          => 1, // select (Without company name)
      'property_use_captions'             => 0, // checkbox
      'property_use_disable_minimal_days' => 0, // checkbox
      'property_use_html'                 => 0, // checkbox
      'property_use_seo_name'             => 0, // checkbox
      'resortpro_default_filter'          => 'name', // select
      'search_layout'                     => 1, // select (Grid Style 1)
      'sort_filter_area'                  => 0, // checkbox
      'sort_filter_bathrooms_number'      => 0, // checkbox
      'sort_filter_bedrooms_number'       => 0, // checkbox
      'sort_filter_name'                  => 0, // checkbox
      'sort_filter_occupants'             => 0, // checkbox
      'sort_filter_pets'                  => 0, // checkbox
      'sort_filter_price'                 => 0, // checkbox
      'sort_filter_view'                  => 0, // checkbox
      'thankyou_content'                  => '<table class="table table-striped table-condensed table-bordered table-hover">
                                                <tr>
                                                  <td>' . __( 'Reservation #', 'streamline-core' ) . '</td>
                                                  <td>%confirmation_id%</td>
                                                </tr>
                                                <tr>
                                                  <td>' . __( 'Property ID', 'streamline-core' ) . '</td>
                                                  <td>%unit_name%</td>
                                                </tr>
                                                <tr>
                                                  <td>' . __( 'Arrival', 'streamline-core' ) . '</td>
                                                  <td>%startdate%</td>
                                                </tr>
                                                <tr>
                                                  <td>' . __( 'Departure', 'streamline-core' ) . '</td>
                                                  <td>%enddate%</td>
                                                </tr>
                                                <tr>
                                                  <td>' . __( 'Guests', 'streamline-core' ) . '</td>
                                                  <td>%occupants%</td>
                                                </tr>
                                                <tr>
                                                  <td>' . __( 'Pets', 'streamline-core' ) . '</td>
                                                  <td>%pets%</td>
                                                </tr>
                                                <tr>
                                                  <td>' . __( 'Total Cost', 'streamline-core' ) . '</td>
                                                  <td><strong>%price_balance%</strong></td>
                                                </tr>
                                              </table>', // textarea
      'top_spacing'                       => '', // text
      'unit_book_adults_label'            => '', // text
      'unit_book_adults_max'              => 99, // select
      'unit_book_checkin_date'            => 1, // select
      'unit_book_checkin_label'           => '', // text
      'unit_book_checkout_label'          => '', // text
      'unit_book_children_label'          => '', // text
      'unit_book_children_max'            => 99, // select
      'unit_book_default_stay'            => 1, // select
      'unit_book_pets_label'              => '', // text
      'unit_book_pets_max'                => 99, // select
      'unit_book_title'                   => '', // text
      'unit_layout'                       => 1, // select (Layout 1)
      'unit_tab_amenities'                => 0, // checkbox
      'unit_tab_availability'             => 0, // checkbox
      'unit_tab_floorplan'                => 0, // checkbox
      'unit_tab_location'                 => 0, // checkbox
      'unit_tab_rates'                    => 0, // checkbox
      'unit_tab_reviews'                  => 0, // checkbox
      'unit_tab_room_details'             => 0, // checkbox
      'unit_tab_virtualtour'              => 0, // checkbox
      'use_room_type_logic'               => 0, // checkbox
      'use_favorites'                     => 0, // checkbox
      'enable_share_with_friends'         => 0, // checkbox
      'lm_booking_check'                  => 0, // checkbox
      'lm_booking_days'                   => 1, // dropdown
      'last_minute_message'               => __( 'Please call our office to place your reservation.', 'streamline-core' ),
      'google-maps-api'                   => '', //text
      'streamline_skip_amenities'         => false, //checkbox
      'enable_paybygroup'                 => 0, //checkbox
      'paybygroup_merchant_id'            => '', //text
      'use_daily_pricing'                 => 0,
      'use_weekly_pricing'                => 0,
      'use_monthly_pricing'               => 0,
      'daily_pricing_append'              => 'daily',
      'weekly_pricing_append'             => 'weekly',
      'monthly_pricing_append'            => 'monthly',
      'daily_pricing_prepend'             => 'from',
      'weekly_pricing_prepend'            => 'from',
      'monthly_pricing_prepend'           => 'from',
      'rate_markup'                       => 0,
      'client_side_amenities'             => 0 //checkbox
    );

    return $option_arr;
  }

  // get option group
  static public function get_option_group() {
    // get instance
    $instance = self::get_instance();

    // return option group
    return $instance->_option_group;
  }

  // get option name
  static public function get_option_name() {
    // get instance
    $instance = self::get_instance();

    // return option name
    return $instance->_option_name;
  }

  // get settings page
  static public function get_settings_page() {
    // get instance
    $instance = self::get_instance();

    // return settings page
    return $instance->_settings_page;
  }

  // get instance of class
  public static function get_instance() {
    // check for existing instance
    if ( is_null(self::$_instance) ) {
      // create instance
      self::$_instance = new self;
    }

    return self::$_instance;
  }
}
