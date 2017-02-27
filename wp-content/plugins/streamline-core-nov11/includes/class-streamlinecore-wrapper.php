<?php
/**
 * Wrapper class for StreamlineCore plugin
 */
class StreamlineCore_Wrapper{
  // transient prefix
  public static $transient_prefix = 'sc-';

  /**
   * search
   *
   *@NOTE: this function will never return anything other then NULL if startdate or enddate holds a value
   *@NOTE: this function is a combination of search(), search_results() and browse()
   *@NOTE: 'bedrooms_number' is commented out as there does not seem to be a default
   *       which won't effect results returned.
   */
  public static function search( $args = array() ) {
    $args = self::prepareSearchArgs($args);
    return self::_api_request( 'GetPropertyListWordPress', $args );
  }

    public static function prepareSearchArgs( $args = array() ) {
        $defaults = array(
//            'amenities'             => array(),
            //'bedrooms_number'       => null,
//            'condo_type_group_id'   => false,
//            'condo_type_id'         => false,
//            'customgroup_id'        => null,
//            'disable_minimal_days'  => 1,
            'enddate'               => null,
//            'home_type_id'          => null,
//            'lat_ne'                => null,
//            'lat_sw'                => null,
//            'location_area_id'      => false,
//            'location_id'           => false,
//            'long_ne'               => null,
//            'long_sw'               => null,
//            'lodging_type_id'       => false,
//            'min_occupants'         => null,
//            'min_pets'              => null,
//            'neighborhood_area_id'  => null,
//            'occupants_small'       => 0,
            'page_number'           => 1,
            'page_results_number'   => 1000,
//            'resort_area_id'        => null,
//            'skip_units'            => 0,
            'sort_by'               => 'name',
            'startdate'             => null,
            'use_description'       => 'no', // false
            'use_amenities'         => 'no', // false
//            'view_name'             => null
        );
        $args = wp_parse_args( $args, $defaults );

        // this is from older logic
//        if ( ! empty( $args['startdate'] ) || ! empty( $args['enddate'] ) ) {
//            return NULL;
//        }

        // parse use description
        $args['use_description'] = ((bool)$args['use_description'] === TRUE ? 'yes' : $args['use_description'] );

        // parse amenities
        $args['amenities_filter'] = null;
        if ( is_array( $args['amenities'] ) && sizeof( $args['emenities'] ) ) {
            $amenities = array();
            foreach ( $args['amenities'] as $amenity ) {
                $amenity = (int)$amenity;
                if ( $amenity > 0 ) {
                    $amenities[] = $amenity;
                }
            }
            if ( sizeof( $amenities ) ) {
                $args['amenities_filter'] = implode( ',', $amenities );
            }
        }
        unset($args['amenities']);

        $property_full_rating_info = StreamlineCore_Settings::get_options( 'property_full_rating_info' );
        if ( ! empty( $property_full_rating_info ) ) {
            $args['return_rating_info'] = 'yes';
            $args['include_feedbacks'] = 'yes';
            $args['feedback_random'] = 'yes';
            $args['feedback_limit'] = 3;
        }
        return $args;
    }

  // rates html - not currently used by plugin
  public static function get_rates_html( $unit_id, $args ) {
    $defaults = array(
      'ignore_empty_periods' => true,
      'load_js' => true,
      'show_columns'  => null,
      'simple_format' => true
    );
    $args = wp_parse_args( $args, $defaults );

    // add unit id to args
    $args['unit_id'] = (int)$unit_id;

    // parse show columns (either an array of columns or a comma delimited string with no spaces)
    $columns_string = self::_generate_comma_list( $args['show_columns'] );
    if ( ! is_null( $columns_string ) )
      $args['show_columns'] = $columns_string;

    return self::_api_request( 'GetPropertyRatesHtml', $args );
  }

  // get post name
  public static function get_post_name( $post_id ) {
    $post_obj = get_post( (int)$post_id );
    return ( is_object( $post_obj ) ? $post_obj->post_name : false );
  }

  // get reservation info
  public static function get_reservation_info( $hash, $confirmation_id = "" ) {
    if(empty($confirmation_id)){
      return self::_api_request( 'GetReservationInfo', array( 'hash' => $hash ) );
    }else{
      return self::_api_request( 'GetReservationInfo', array( 'confirmation_id' => $confirmation_id ) );
    }
  }

  // get reservation price
  public static function get_reservation_price( $confirmation_id, $hash = "" ) {
    if(empty($hash)){
      return self::_api_request( 'GetReservationPrice', array( 'confirmation_id' => $confirmation_id ) );
    }else{
      return self::_api_request( 'GetReservationPrice', array( 'hash' => $hash ) );
    }

  }

  public static function get_company_policies() {    
    return self::_get_resource( 'company-policies', 'GetCompanyDocumentHtml', array('trigger_id' => 18));
  }

  // get property info
  public static function get_property_info( $unit_id ) {
    return self::_get_resource( 'property-' . $unit_id, 'GetPropertyInfo', array( 'unit_id' => $unit_id ) );
  }

  // get property info by seo name
  public static function get_property_info_by_seo_name( $name ) {
    return self::_get_resource( 'property-seo-' . $name, 'GetPropertyInfoBySeoNameWordPress', array( 'seo_page_name' => $name ) );
  }

  // get property info by wordpress
  public static function get_property_info_by_wordpress($unit_id) {
    return self::_get_resource( 'property-wp-' . $unit_id, 'GetPropertyInfoWordPress', array( 'unit_id' => $unit_id ) );
  }

  // verify seo page name
  public static function verify_seo_page_name( $seo_page_name ) {
    return self::_api_request( 'VerifyPropertyBySeoName', array( 'seo_page_name' => $seo_page_name ) );
  }

  // get unit permalink
  public static function get_unit_permalink( $name ) {

    $property_page_prefix = StreamlineCore_Settings::get_options( 'prepend_property_page' );
    if ( ! empty( $property_page_prefix ) ) {
      $name = trailingslashit( $property_page_prefix ) . $name;
    }

	if( "1" == StreamlineCore_Settings::get_options( 'property_use_html' ) ) {
		$name .= '.html';
	} else {
		$name = trailingslashit( $name );
	}

    return home_url( $name );
  }

  // get price info availability
  public static function get_price_info_availability( $unit_id, $start_date, $end_date, $occupants, $args ) {
    $defaults = array(
      'coupon_code'             => false,
      'fees'                    => false,
      'guest_deposits_show_all' => false,
      'hash'                    => false,
      'occupants_small'         => false,
      'pets'                    => false,
      'update_optional_fees'    => false
    );
    $args = wp_parse_args( $args, $defaults );

    $args['unit_id'] = (int)$unit_id;
    $args['startdate'] = $start_date;
    $args['enddate'] = $end_date;
    $args['occupants'] = (int)$occupants;

    // parse fees
    if ( is_array( $args['fees'] ) && sizeof( $args['fees'] ) ) {
      foreach ( $args['fees'] as $fee ) {
        $fee = trim( $fee );
        if ( ! empty( $fee ) ) {
          $args['optional_fee_' . $fee] = true;
        }
      }
    }
    unset( $args['fees'] );

    // hard-coded values
    $args['show_breakdowns'] = 'yes';

    /**
     * commented out the below code as this method should not be
     * quering $_POST directly.
     *
     * $args['apply_reward_points'] = intval($_POST['redeem_points']);
     */

     return self::_api_request( 'GetPreReservationPrice', $args );
  }

  // get location areas
  public static function get_location_areas( $ignore_filters = false ) {
    $location_areas = self::_get_resource( 'location-areas-list', 'GetLocationAreasList', NULL, 'location_area', array( 'id', 'name' ) );
    if ( $ignore_filters === true || ! is_array( $location_areas ) )
      return $location_areas;

    $filter_location_areas = StreamlineCore_Settings::get_options( 'filter_location_areas' );
    if ( ! is_array( $filter_location_areas ) || ! sizeof( $filter_location_areas ) )
      return $location_areas;

    $filtered_location_areas = array();
    foreach ( $location_areas as $location_area ) {
      if ( array_key_exists( $location_area->id, $filter_location_areas ) )
        $filtered_location_areas[] = $location_area;
    }

    return $filtered_location_areas;
  }

  // get bedroom types
  public static function get_bedroom_types($params) {
    $params['sort_by'] = 'property_name';
    
    return self::_get_resource( 'bedroom-types-list', 'GetRoomTypesList', $params, 'roomtype', array( 'id', 'property_name' ) );
  }

  // get neighborhoods
  public static function get_neighborhoods() {
    return self::_get_resource( 'neighborhoods-list', 'GetNeighborhoodsList', null, 'neighborhood', array( 'id', 'name' ) );
  }

  // get view names
  public static function get_view_names() {
    return self::_get_resource( 'view-names-list', 'GetViewNamesList', null, 'view', array( 'id', 'name' ) );
  }

  // get group types
  public static function get_group_types() {
    return self::_get_resource( 'group-types-list', 'GetRoomTypeGroupsList', null, 'group', array( 'id', 'name' ) );
  }

  // get home types
  public static function get_home_types() {
    return self::_get_resource( 'home-types-list', 'GetHomeTypesList', null, 'type', array( 'id', 'name' ) );
  }

  // get bedroom numbers
  public static function get_bedrooms() {
    return self::_get_resource( 'bedrooms-list', 'GetBedRoomsList', null, 'bedroom', array( 'value', 'name' ) );
  }

  /**
   * get locations
   *
   * @NOTE this function always returns FALSE, GetLocationsList returns empty array
   */
  public static function get_locations() {
    return self::_get_resource( 'locations-list', 'GetLocationsList', null, 'location', array( 'id', 'name' ) );
  }

  // get location resorts
  public static function get_location_resorts() {
    return self::_get_resource( 'location-resorts-list', 'GetLocationResortsList', null, 'location_resort', array( 'id', 'name' ) );
  }

  public static function get_heared_abouts(){
    return self::_get_resource( 'heared-about-list', 'GetHearAboutList', null, 'hear_about', array( 'id', 'name' ) );
  }

  // get amenity filters
  public static function get_amenity_filters() {
    $params['use_and_logic'] = 'yes';
    return self::_get_resource( 'amenities-list', 'GetAmenities', $params, 'amenities' );
  }

  // get random units
  public static function get_random_units( $count, $unit_ids = array() ) {
    $args = array();
    $args['page_number'] = 1;
    $args['page_results_number'] = (int)$count;
    $args['skip_units'] = StreamlineCore_Settings::get_options( 'property_delete_units' );
    $args['sort_by'] = 'random';
    $args['use_amenities'] = 'yes';
    $args['use_description'] = 'yes';

    // parse unit ids (either an array of unit ids or a comma delimited string with no spaces)
    $units_string = self::_generate_comma_list( $unit_ids );
    if ( ! is_null( $units_string ) )
      $args['include_units'] = $units_string;

    $results = self::_api_request( 'GetPropertyListWordPress', $args );

    // default
    $properties = array();

    // parse results
    if ( sizeof( $results ) && isset( $results['data'] ) && isset( $results['data']['property'] ) ) {

      $properties = ( isset( $results['data']['property'][0] ) ? $results['data']['property'] : array( $results['data']['property'] ) );
      foreach ( $properties as &$property ) {
        $property['permalink'] = StreamlineCore_Wrapper::get_unit_permalink( $property['seo_page_name'] );
      }

    }

    return $properties;
  }

  // get feedback
  public static function get_feedback( $args ) {

    $defaults = array(
      'limit'       => 0,
      'min_points'  => null,
      'order_by'    => 'newest_first',
      'unit_id'     => null
    );
    $args = wp_parse_args( $args, $defaults );

    $results = self::_api_request( 'GetPropertyFeedbacks', $args );

    // default
    $feedback = array();

    // parse results
    if ( sizeof( $results ) && isset( $results['data'] ) && isset( $results['data']['feedbacks'] ) ) {
      foreach ( $results['data']['feedbacks'] as $r ) {
        if ( array_key_exists( 'creation_date', $r ) )
          $feedback[] = $r;
      }
    }

    return $feedback;
  }

  // get countries
  public static function get_countries() {
    return self::_api_request( 'GetCountriesList' );
  }

  // get states (country is 2 character code)
  public static function get_states( $country ) {
    return self::_api_request( 'GetStatesList', array( 'country_name' => strtoupper( $country ) ) );
  }

  // get custom vacation quote
  public static function get_custom_vacation_quote( $hash ) {
    return self::_api_request( 'GetCustomVacationQuote', array( 'hash' => $hash ) );
  }

  // verify property availability
  public static function verify_property_availability( $unit_id, $start_date, $end_date, $occupants ) {
    return self::_api_request( 'VerifyPropertyAvailability', array( 'unit_id' => $unit_id, 'startdate' => $start_date, 'enddate' => $end_date, 'occupants' => $occupants ) );
  }

  // clear transients
  public static function clear_transients() {
    global $wpdb;
    $wpdb->query( "DELETE FROM " . $wpdb->options . " WHERE `option_name` LIKE '%_transient_" . self::$transient_prefix ."%' OR `option_name` LIKE '%_transient_timeout_" . self::$transient_prefix ."%'" );
    return ( $wpdb->last_error === '' ? true : false );
  }

  // generate comma list
  protected static function _generate_comma_list( $data ) {
    $data_string = '';
    $data_arr = ( is_array( $data ) ? $data : explode( ',', $data ) );
    if ( sizeof( $data_arr ) ) {
      foreach ( $data_arr as $d ) {
        $d = trim($d);
        if ( ! empty( $d ) )
          $data_string .= $d . ',';
      }

      if ( strlen( $data_string ) )
        return rtrim( $data_string, ',' );
    }

    return null;
  }

  // convert array to list
  protected static function _array_to_list( $data, $indexes ) {
    $list = array();
    if ( is_array( $data ) ) {
      foreach ( $data as $d ) {
        $obj = new stdClass();
        foreach ( $indexes as $index ) {
          $obj->$index = ( isset( $d[$index] ) ? $d[$index] : NULL );
        }
        $list[] = $obj;
      }
    }

    return $list;
  }

  // get resource
  protected static function _get_resource( $transient, $method_name, $args = null, $index = null, $list = null ) {
    // set transient
    $transient = self::$transient_prefix . $transient;

    // get and check transient
    $results = get_transient( $transient );
    if ( $results !== false )
      return $results;

    // get results from API request and check
    $results = self::_api_request( $method_name, $args );
    if ( $results === false )
      return false;

    // check for index
    if ( ! is_null( $index ) ) {
      if ( ! isset( $results['data'] ) || ! isset( $results['data'][$index] ) )
        return false;

      $results = ( isset($results['data'][$index][0]) ? $results['data'][$index] : array( $results['data'][$index] ) );
    }

    // check for list
    if ( is_array( $list ) )
      $results = self::_array_to_list( $results, $list );

    // set transient
    set_transient( $transient, $results, HOUR_IN_SECONDS );

    return $results;
  }

  // preform request to endporint API
  protected static function _api_request( $method_name, $args = array(), $return_type = 'ARRAY' ) {
    // get end point and company code
    $endpoint = StreamlineCore_Settings::get_options( 'endpoint' );
    $company_code = StreamlineCore_Settings::get_options( 'id' );

    // check end point and company code
    if ( empty( $endpoint ) || empty( $company_code ) ) {
      streamlinecore_error( __('Endpoint and Company Code must be set.', 'streamline-core') );
      return false;
    }

    // check args
    if ( ! is_array( $args ) )
      $args = array();

    // set request json
    $request_json = json_encode( array( 'methodName' => $method_name, 'params' => array_merge( array( 'company_code' => $company_code ), $args ) ) );
    // preform request

    $response = wp_remote_post( $endpoint, array(
      'headers' => array( 'Content-Type: application/json', 'Content-Length: ' . strlen( $request_json ) ),
      'body'    => $request_json
    ) );

    // check for error
    if ( is_wp_error( $response ) ) {
      streamlinecore_error( sprintf( __( 'Error querying API - %s', 'streamline-core' ), $response->get_error_message() ) );
      return false;
    }

    // get response body
    $response = wp_remote_retrieve_body( $response );
    if ( empty( $response ) ) {
      streamlinecore_error( __( 'No body in API response.', 'streamline-core' ) );
      return false;
    }

    // decode JSON response
    $response_arr = json_decode( $response, true );

    // check response
    if ( ! is_array( $response_arr ) || $response_arr === false ) {
      streamlinecore_error( sprintf( __( 'Invalid JSON response - %s', 'streamline-core'), $response ) );
      return false;
    }

    // check for error
    if ( isset( $response_arr['status'] ) && isset( $response_arr['status']['code'] ) ) {
      streamlinecore_error( sprintf( __( 'API Error - %s, %s', 'streamline-core' ), $response_arr['status']['code'], $response_arr['status']['description'] ) );
      return false;
    }

    return ( strtoupper( $return_type ) === 'ARRAY' ? $response_arr : $response );
  }

    public static function GetPropertyRatesRawData( $id ) {

        return self::_api_request( 'GetPropertyRatesRawData', array( 'unit_id' => $id ) );
    }

    public static function GetPropertyAvailabilityCalendarRawData( $id ) {

        return self::_api_request( 'GetPropertyAvailabilityCalendarRawData', array( 'unit_id' => $id ) );
    }
}
