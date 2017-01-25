<?php
/**
 * Wrapper class for StreamlineCore plugin (deprecated)
 *
 * allows for calls to deprecated ResortProWrapper class to still work
 */
class ResortProWrapper extends StreamlineCore_Wrapper{
  // preform request to endpoint API
  public static function json_request( $method_name, $args = array() ) {
    return parent::_api_request( $method_name, $args, 'JSON' );
  }

  public static function search($start = false, $end = false, $adults = false, $children = false, $area = false, $loc = false, $bed = false, $grp = false, $property_description = false, $lodging_type_id = false, $bedrooms_numbers = false, $page_results_number = false, $page_number = false, $disable_minimal_days = false, $pets = false, $filter = false, $home_type_id = false, $customgroup_id = false, $amenities = false, $neighborhood_id = false, $view_name = false, $resort_area_id = false, $lat_ne = false, $long_ne = false, $lat_sw = false, $long_sw = false) {
    $args = array();
    if ( $start !== false )
      $args['startdate'] = $start;
    if ( $end !== false )
      $args['enddate'] = $end;
    if ( $adults !== false )
      $args['min_occupants'] = $adults;
    if ( $children !== false )
      $args['occupants_small'] = $children;
    if ( $area !== false )
      $args['location_area_id'] = $area;
    if ( $loc !== false )
      $args['location_id'] = $loc;
    if ( $bed !== false )
      $args['condo_type_id'] = $bed;
    if ( $grp !== false )
      $args['condo_type_group_id'] = $grp;
    if ( $property_description !== false )
      $args['property_description'] = $property_description;
    if ( $lodging_type_id !== false )
      $args['lodging_type_id'] = $lodging_type_id;
    if ( $bedrooms_numbers !== false )
      $args['bedrooms_number'] = $bedrooms_numbers;
    if ( $page_results_number !== false )
      $args['page_results_number'] = $page_results_number;
    if ( $page_number !== false )
      $args['page_number'] = $page_number;
    if ( $disable_minimal_days !== false )
      $args['disable_minimal_days'] = $disable_minimal_days;
    if ( $pets !== false )
      $args['min_pets'] = $pets;
    if ( $filter !== false )
      $args['sort_by'] = $filter;
    if ( $home_type_id !== false )
      $args['home_type_id'] = $home_type_id;
    if ( $customgroup_id !== false )
      $args['customgroup_id'] = $customgroup_id;
    if ( $amenities !== false )
      $args['amenities'] = $amenities;
    if ( $neighborhood_id !== false )
      $args['neighborhood_area_id'] = $neighborhood_id;
    if ( $view_name !== false )
      $args['view_name'] = $view_name;
    if ( $resortpro_area_id !== false )
      $args['resortpro_area_id'] = $resort_area_id;
    if ( $lat_ne !== false )
      $args['lat_ne'] = $lat_ne;
    if ( $long_ne !== false )
      $args['long_ne'] = $long_ne;
    if ( $lat_sw !== false )
      $args['lat_sw'] = $lat_sw;
    if ( $long_sw !== false )
      $args['long_sw'] = $long_sw;

    return parent::search( $args );
  }

  // removed
  public static function search_results( $params ) {
    return false;
  }

  // removed
  public static function browse( $property_description = false, $number_units = 10, $page_number = 1, $skip_units = '', $sort_by = '', $location_id = false, $params = array() ) {
    return false;
  }

  public static function rates_html( $id, $show_columns, $ignore_empty_periods = true ) {
    $args = array();
    if ( $show_columns !== false )
      $args['show_columns'] = $show_columns;
    if ( ! is_null( $ignore_empty_periods ) )
      $args['ignore_empty_periods'] = (bool)$ignore_empty_periods;

    return json_encode( parent::get_rates_html( $id, $args ) );
  }

  public static function get_slug( $post_id ) {
    return parent::get_post_name( $post_id );
  }

  public static function get_reservation_info( $hash, $confirmation_id = '' ) {
    return parent::get_reservation_info( $hash );
  }

  public static function get_reservation_price( $confirmation_id, $hash = '' ) {
    return parent::get_reservation_price( $confirmation_id );
  }

  public static function get_property_info( $unit_id ) {
    return parent::get_property_info( $unit_id );
  }

  public static function get_property_info_api( $params ) {
    // removed
    return false;
  }

  public static function get_property_info_seo_name( $seo_name ) {
    return parent::get_property_info_by_seo_name( $seo_name );
  }

  public static function get_property_info_seo_name_api( $params ) {
    // removed
    return false;
  }

  public static function get_property_info_wordpress( $unit_id ) {
    return parent::get_property_info_by_wordpress( $unit_id );
  }

  public static function get_property_info_wordpress_api( $params ) {
    // removed
    return false;
  }

  public static function get_verify_seoname( $seo_page_name ) {
    return parent::verify_seo_page_name( $seo_page_name );
  }

  public static function get_unit_permalink( $seo_page_name ) {
    return parent::get_unit_permalink( $seo_page_name );
  }

  public static function get_post_url_by_id( $id ) {
    // deprecated, use get_permalink() instead
    return get_permalink( $post_id );
  }

  public static function get_price_info_availability( $id, $start, $end, $adults, $children = false, $pets = false, $fees = false, $coupon_code = false, $hash = false, $update_optional_fees = false, $guest_deposits = false ) {
    $args = array();
    if ( $children !== false )
      $args['occupants_small'] = $children;
    if ( $pets !== false )
      $args['pets'] = $pets;
    if ( $fees !== false )
      $args['fees'] = $fees;
    if ( $coupon_code !== false )
      $args['coupon_code'] = $coupon_code;
    if ( $hash !== false )
      $args['hash'] = $hash;
    if ( $update_optional_fees !== false )
      $args['update_optional_fees'] = $update_optional_fees;
    if ( $guest_deposits !== false )
      $args['guest_deposits_show_all'] = $guest_deposits;

    return json_encode( parent::get_price_info_availability( $id, $start, $end, $adults, $args ) );
  }

  public static function get_areas( $ignore_filter = false ) {
    return parent::get_location_areas( $ignore_filter );
  }

  private static function get_areas_api() {
    // removed
    return false;
  }

  public static function get_bedroom_types($params) {
    return parent::get_bedroom_types($params);
  }

  private static function get_bedroom_types_api() {
    // removed
    return false;
  }

  public static function get_neighborhoods() {
    return parent::get_neighborhoods();
  }

  private static function get_neighborhoods_api() {
    // removed
    return false;
  }

  public static function get_viewnames() {
    return parent::get_view_names();
  }

  private static function get_viewnames_api() {
    // removed
    return false;
  }

  public static function get_group_types() {
    return parent::get_group_types();
  }

  private static function get_group_types_api() {
    // removed
    return false;
  }

  public static function get_home_types() {
    return parent::get_home_types();
  }

  private static function get_home_types_api() {
    // removed
    return false;
  }

  public static function get_locations() {
    return parent::get_locations();
  }

  private static function get_locations_api() {
    // removed
    return false;
  }

  public static function get_locationresorts() {
    return parent::get_location_resorts();
  }

  private static function get_locationresorts_api() {
    // removed
    return false;
  }

  public static function get_amenity_filters() {
    return parent::get_amenity_filters();
  }

  private static function get_amenity_filters_api() {
    // removed
    return false;
  }

  public static function get_random_units( $number, $ids = array() ) {
    return parent::get_random_units( $number, $ids );
  }

  public static function get_feedbacks( $unit_id = false, $order = false, $limit = false, $min_points = false ) {
    $args = array();
    if ( $unit_id !== false )
      $args['unit_id'] = $unit_id;
    if ( $order !== false )
      $args['sort_order'] = $order;
    if ( $limit !== false )
      $args['limit'] = $limit;
    if ( $min_points !== false )
      $args['min_points'] = $min_points;

    return parent::get_feedback( $args );
  }

  public static function get_countries_list() {
    return parent::get_countries();
  }

  public static function get_states_list( $country_name ) {
    return parent::get_states( $country_name );
  }

  private static function transient( $transient_name, $method_name, $params = array() ) {
    // removed
    return FALSE;
  }

  private static function api_list( $list ) {
    // removed
    return false;
  }
}
