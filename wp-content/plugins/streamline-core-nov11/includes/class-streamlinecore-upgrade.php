<?php
/**
 * Upgrade class for StreamlineCore plugin
 */
class StreamlineCore_Upgrade{
  // single instance of StreamlineCore_AJAX
  protected static $_instance;

  // construct
  public function __construct() {
    add_action( 'admin_init', array( &$this, 'upgrade_plugin' ) );
  }

  // upgrade plugin
  public function upgrade_plugin() {
    // get current plugin version
    $resortpro_plugin_version = get_option( 'resortpro_version' );
    $streamlinecore_plugin_version = get_option( 'streamlinecore_version' );
    $plugin_version = ( $streamlinecore_plugin_version !== FALSE ? $streamlinecore_plugin_version : ( $resortpro_plugin_version !== FALSE ? $resortpro_plugin_version : '0' ) );

  	// compare version numbers
  	if ( version_compare( $plugin_version, STREAMLINECORE_VERSION, '<' ) ) {
      if ( version_compare( $plugin_version, '2.1.5', '<' ) ) {
        $this->_upgrade_215();
      }
      if ( version_compare( $plugin_version, '2.2.0', '<' ) ) {
        $this->_upgrade_220();
      }

      // check for old plugin version name
      if ( $resortpro_plugin_version !== FALSE ) {
        delete_option( 'resortpro_version' );
      }

      // upgrade plugin version
      update_option( 'streamlinecore_version', STREAMLINECORE_VERSION );
  	}
  }

  // upgrade to version 2.1.5
  protected function _upgrade_215() {
    // check if custom quote page exists
    $custom_quote_page = get_page_by_title( 'Custom Quote' );
    if ( ! $custom_quote_page ) {
      // create page
      wp_insert_post( array(
        'post_title'      => __( 'Custom Quote', 'streamline-core' ),
        'post_name'       => 'custom-quote',
        'post_content'    => '[streamlinecore-quote]',
        'page_template'   => NULL,
        'post_status'     => 'publish',
        'post_type'       => 'page',
        'ping_status'     => 'closed',
        'comment_status'  => 'closed'
      ) );

      // update options (I think only the id is actually being used)
      update_option( 'resortpro_custom_quote_page_title', __( 'Custom Quote', 'streamline-core' ) );
      update_option( 'resortpro_custom_quote_page_name', 'custom-quote' );
      update_option( 'resortpro_custom_quote_page_id', $custom_quote_page_id );
    }
  }

  // update to version 2.2.0
  protected function _upgrade_220() {
    // remove filter - throws deprecated which can prevent plugin activation
    remove_filter( 'pre_option_pmt_plugin_resortpro', array( StreamlineCore_Settings::get_instance(), 'get_old_option' ) );
    // check for plugin options
    $resortpro_options = get_option('pmt_plugin_resortpro');
    // add filter back
    add_filter( 'pre_option_pmt_plugin_resortpro', array( StreamlineCore_Settings::get_instance(), 'get_old_option' ), 10, 2 );

    // check options
    if ( $resortpro_options === FALSE ) {
      // set default options
      update_option( StreamlineCore_Settings::get_option_name(), StreamlineCore_Settings::get_default_options() );
    } else {
      // update old options
      $streamlinecore_options = $resortpro_options;
      // fix reverse logic for checkboxes
      $checkboxes_to_fix = array_intersect(
        array_keys( $streamlinecore_options ),
        array(
          'property_card_type_visa',
          'property_card_type_master_card',
          'property_card_type_amex',
          'property_card_type_discover',
          'sort_filter_occupants',
          'sort_filter_bedrooms_number',
          'sort_filter_bathrooms_number',
          'sort_filter_name',
          'sort_filter_area',
          'sort_filter_view',
          'sort_filter_price',
          'sort_filter_pets'
        )
      );
      if ( sizeof( $checkboxes_to_fix ) ) {
        foreach( $checkboxes_to_fix as $key ) {
          $streamlinecore_options[$key] = ( (int)$streamlinecore_options[$key] === 1 ? 0 : 1 );
        }
      }

      // remove tab and _ajax_nonce - no longer used
      if ( isset( $streamlinecore_options['tab'] ) ) {
        unset( $streamlinecore_options['tab'] );
      }
      if ( isset( $streamlinecore_options['_ajax_nonce'] ) ) {
        unset( $streamlinecore_options['_ajax_nonce'] );
      }

      // set new options (upsert old options info default options)
      update_option( StreamlineCore_Settings::get_option_name(), array_merge( StreamlineCore_Settings::get_default_options(), $streamlinecore_options ) );

      // delete old option
      delete_option( 'pmt_plugin_resortpro' );
    }
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
