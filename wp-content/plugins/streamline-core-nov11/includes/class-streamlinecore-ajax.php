<?php
/**
 * AJAX class for StreamlineCore plugin
 */

class StreamlineCore_AJAX{
  // single instance of StreamlineCore_AJAX
  protected static $_instance;
  // default AJAX error message
  protected $_default_error_message;

  // construct
  public function __construct() {
    // class variables
    $this->_default_error_message = sprintf(__('We are unable to complete your request at this time. Please call %s', 'streamline-core' ), StreamlineCore_Settings::get_options('phone') );
    // get price
    add_action( 'wp_ajax_streamlinecore-get-price', array( &$this, 'get_price' ) );
    add_action( 'wp_ajax_nopriv_streamlinecore-get-price', array( &$this, 'get_price' ) );
    // share with firends
    add_action( 'wp_ajax_streamlinecore-share-with-friends', array( &$this, 'share_with_friends' ) );
    add_action( 'wp_ajax_nopriv_streamlinecore-share-with-friends', array( &$this, 'share_with_friends' ) );
  }

  // get price
  public function get_price() {
    // get POST variables
    $unit_id = ( isset( $_POST['unit_id'] ) ? $this->_sanitize_and_validate_variable( $_POST['unit_id'], TRUE, FALSE) : FALSE );
    $start_date = ( isset( $_POST['startdate'] ) ? $this->_sanitize_and_validate_variable( $_POST['startdate'] ) : FALSE );
    $end_date = ( isset( $_POST['enddate'] ) ? $this->_sanitize_and_validate_variable( $_POST['enddate'] ) : FALSE );
    $occupants = ( isset( $_POST['occupants'] ) ? $this->_sanitize_and_validate_variable( $_POST['occupants'], TRUE, FALSE ) : FALSE );
    $occupants_small = ( isset( $_POST['occupants_small'] ) ? $this->_sanitize_and_validate_variable( $_POST['occupants_small'], TRUE, 0 ) : 0 );
    $pets = ( isset( $_POST['pets'] ) ? $this->_sanitize_and_validate_variable( $_POST['pets'], TRUE, 0 ) : 0 );

    // check unit id
    if ( $unit_id === FALSE || $start_date === FALSE || $end_date === FALSE || $occupants === FALSE ) {
      $this->_json_error();
    }

    // check PHP version
    if( version_compare ( '5.3', PHP_VERSION, '<' ) ) {
      // check start date
      try{
        $start_date_obj = new DateTime( $start_date );
        $start_date = $start_date_obj->format('m/d/Y');
      } catch( Exception $e ) {
        $this->_json_error();
      }

      // check end date
      if ( is_numeric( $end_date ) ) {
        // treat as start date + end date in days
        $start_date_obj->add(new DateInterval('P' . (int)$end_date . 'D'));
        $end_date = $start_date_obj->format('m/d/Y');
      } else {
        try{
          $end_date_obj = new DateTime( $end_date );
        } catch( Exception $e ) {
          $this->_json_error();
        }
      }
    } else {
      // check start date
      $start_date = date( 'm/d/Y', strtotime( $start_date ) );
      if ( $start_date == '01/01/1970' ) {
        $this->_json_error();
      }

      // check end date
      if ( is_numeric( $end_date ) ) {
        // treat as start date + end date in days
        $end_date = date( 'm/d/Y', strtotime( $start_date . ' + ' . (int)$end_date . ' day' ) );
      } else {
        $end_date = date( 'm/d/Y', strtotime( $end_date ) );
        if ( $end_date == '01/01/1970' ) {
          $this->_json_error();
        }
      }
    }

    // check occupants
    if ($occupants === FALSE) {
      $this->_json_error();
    }

    // verify property availability
    $availability_arr = StreamlineCore_Wrapper::verify_property_availability( $unit_id, $start_date, $end_date, $occupants );
    if ( ! is_array( $availability_arr ) || array_key_exists( 'status', $availability_arr ) && array_key_exists( 'code', $availability_arr['status'] ) ) {
      $this->_json_error( isset( $availability_arr['status']['description'] ) ? $availability_arr['status']['description'] : NULL );
    }

    // get price info
    $price_info_arr = StreamlineCore_Wrapper::get_price_info_availability( $unit_id, $start_date, $end_date, $occupants, array( 'occupants_small' => $occupants_small, 'pets' => $pets ) );
    if ( ! is_array( $price_info_arr ) || ! array_key_exists( 'data', $price_info_arr ) ) {
      $this->_json_error();
    }

    // set property array
    $property_arr = $price_info_arr['data'];

    // set security deposit
    $security_deposit = 0;
    if ( isset( $property_arr['security_deposits']['security_deposit']['deposit_required'] ) ) {
      $security_deposit = $property_arr['security_deposits']['security_deposit']['deposit_required'];
    } else {
      foreach ( $property_arr['security_deposits']['security_deposit'] as $deposit ) {
        $security_deposit += $deposit['deposit_required'];
      }
    }

    // set total fees
    $total_fees = 0;
    if ( isset( $property_arr['required_fees']['id'] ) ) {
      $total_fee = $$property_arr['required_fees']['value'];
    } else {
      foreach ( $property_arr['required_fees'] as $fee) {
        $total_fees += $fee['value'];
      }
    }

    // set total taxes
    if ( isset( $property_arr['taxes_details']['id'] ) ) {
      $total_taxes = $property_arr['taxes_details']['value'];
    } else {
      foreach ( $property_arr['taxes_details'] as $tax ) {
        $total_taxes += $tax['value'];
      }
    }

    wp_send_json_success( array(
      'checkin' => $start_date,
      'checkout' => $end_date,
      'rent' => number_format( $property_arr['price'], 2 ),
      'fees' => number_format( $total_fees, 2 ),
      'taxes' => number_format( $total_taxes, 2 ),
      'total' => number_format( $property_arr['total'], 2 ),
      'deposit' => number_format( $security_deposit , 2 )
    ) );
  }

  // share with friends
  public function share_with_friends() {

    $friend_names = ( isset( $_REQUEST['fnames'] ) ? $this->_sanitize_and_validate_variable( $_REQUEST['fnames'] ) : FALSE );
    $friend_emails = ( isset( $_REQUEST['femails'] ) ? $this->_sanitize_and_validate_variable( $_REQUEST['femails'] ) : FALSE );
    $name = ( isset( $_REQUEST['name'] ) ? $this->_sanitize_and_validate_variable( $_REQUEST['name'] ) : FALSE );
    $email = ( isset( $_REQUEST['email'] ) ? $this->_sanitize_and_validate_variable( $_REQUEST['email'] ) : FALSE );
    $hash = ( isset( $_REQUEST['hash'] ) ? $this->_sanitize_and_validate_variable( $_REQUEST['hash'] ) : FALSE );
    $message = ( isset( $_REQUEST['msg'] ) ? $this->_sanitize_and_validate_variable( $_REQUEST['msg'] ) : FALSE );
    $slug = ( isset( $_REQUEST['slug'] ) ? $this->_sanitize_and_validate_variable( $_REQUEST['slug'] ) : FALSE );
    $link = ( isset( $_REQUEST['link'] ) ? $this->_sanitize_and_validate_variable( $_REQUEST['link'] ) : FALSE );
    $nonce = ( isset( $_REQUEST['nonce'] ) ? $this->_sanitize_and_validate_variable( $_REQUEST['nonce'] ) : FALSE );

    
    if(!wp_verify_nonce( $nonce, 'share-with-friends' )){
      $this->_json_error();
    }

    // parse post variables and check for errors
    $error_arr = array();

    // friend names
    if ( $friend_names === FALSE ) {
      $error_arr[] = __( 'Friend(s) Name(s) is required.', 'streamline-core' );
    } else {
      $friend_name_arr = array();
      foreach ( explode( ',', $friend_names ) as $friend_name ) {
        $friend_name = trim( $friend_name );
        if ( ! empty( $friend_name ) ) {
          $friend_name_arr[] = $friend_name;
        }
      }
      if ( ! sizeof( $friend_name_arr ) ) {
        $error_arr[] = __( 'Friend(s) Name(s) is required.', 'streamline-core' );
      }
    }

    // friend emails
    if ( $friend_emails === FALSE ) {
      $error_arr[] = __( 'Friend(s) Email(s) is required.', 'streamline-core' );
    } else {
      $friend_email_arr = array();
      $email_error = FALSE;
      foreach ( explode( ',', $friend_emails ) as $friend_email ) {
        $friend_email = trim( $friend_email );
        if ( ! empty( $friend_email ) ) {
          if ( filter_var( $friend_email, FILTER_VALIDATE_EMAIL ) === FALSE ) {
            $error_arr[] = sprintf( __( '"%s" is not a valid email.', 'streamline-core' ), $friend_email );
            $email_error = FALSE;
          } else {
            $friend_email_arr[] = $friend_email;
          }
        }
        if ( ! sizeof( $friend_email_arr ) && $email_error === FALSE ) {
          $error_arr[] = __( 'Friend(s) Email(s) is required.', 'streamline-core' );
        }
      }
    }

    // check that friend names and emails sizes match
    if ( sizeof( $friend_name_arr ) != sizeof( $friend_email_arr) ) {
      $error_arr[] = __( 'The amount of Friend(s) Name(s) and Friend(s) Email(s) do not match.', 'streamline-core' );
    }

    // name
    if ( $name === FALSE ) {
      $error_arr[] = __( 'Your name is required.', 'streamline-core' );
    }

    // email
    if ( $email === FALSE ) {
      $error_arr[] = __( 'Your email is required.', 'streamline-core' );
    } elseif ( filter_var( $email, FILTER_VALIDATE_EMAIL ) === FALSE ) {
      $error_arr[] = sprintf( __( '"%s" is not a valid email.', 'streamline-core' ), $email );
    }

    

    // message
    if ( $message === FALSE ) {
      $error_arr[] = __( 'Message is required.', 'streamline-core' );
    }

    // slug
    if ( $slug === FALSE ) {
      // slug is not set, submission will never work - hard fail
      $this->_json_error();
    } else {
      // check for property page prefix
      $property_page_prefix = StreamlineCore_Settings::get_options( 'prepend_property_page' );
      if ( ! empty( $property_page_prefix ) ) {
        $slug = trailingslashit( $property_page_prefix ) . $slug;
      }
      // set url
      $url = home_url( trailingslashit( $slug ) );
    }

    // check for errors
    if ( sizeof( $error_arr ) ) {
      // submission has errors
      $this->_json_error( implode( '<br />', $error_arr ) );
    }

    // set email headers
    $header_arr = array();
    $header_arr[] = 'MIME-Version: 1.0';
    $header_arr[] = 'Content-type: text/html';
    $header_arr[] = 'charset=iso-8859-1';
    $header_arr[] = 'From: ' . $name . ' <' . $email . '>';

    // set email to
    $to_arr = array();
    for( $i = 0; $i < sizeof( $friend_email_arr ); $i++ ) {
      $to_arr[] = $friend_name_arr[$i] . ' <' . $friend_email_arr[$i] . '>';
    }

    // hash
    if ( $hash === FALSE || strlen( $hash ) != 32 ) {
      $url = $link;
    }else{
      $url .= "?hash={$hash}";      
    }

    // set email subject
    $subject = __( 'Share with friends', 'streamline-core' );

    // set email content
    $content = '<p>' . $message . '</p><p><a href="' . $url . '">' . $url . '</a></p>';

    if ( wp_mail( $to_arr, $subject, $content, $header_arr ) ) {
      // success

      wp_send_json_success( array( 'message' => __( 'Message was sent successfully.' , 'streamline-core' ) ) );
    } else {
      // failure
      $this->_json_error();
    }
  }

  // JSON error
  protected function _json_error( $message = NULL ) {
    wp_send_json_error( array( 'message' => ( is_null( $message ) ? $this->_default_error_message : $message ) ) );
  }

  // sanitize variable
  protected function _sanitize_and_validate_variable( $variable, $is_numeric = FALSE, $default = FALSE )
  {
    $sanitized_variable = trim( $variable );

    if ( $is_numeric ) {
      return ( ( ! is_numeric( $sanitized_variable ) || (int)$sanitized_variable == 0 ) ? $default : (int)$sanitized_variable );
    }

    $is_empty = empty( $sanitized_variable );
    return ( $is_empty ? $default : $sanitized_variable );
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
