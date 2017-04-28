<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title"><?php _e( 'Booking', 'streamline-core' ) ?></h4>
  </div>
  <div class="panel-body">
    <div class="form-horizontal">
      <div class="form-group">
        <div class="col-sm-12">
          <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[book_now]" class="form-control">
            <option value="enabled"<?php if ( isset( $option_arr['book_now'] ) && $option_arr['book_now'] == 'enabled' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'Enabled', 'streamline-core' ) ?></option>
            <option value="disabled"<?php if ( isset( $option_arr['book_now'] ) && $option_arr['book_now'] == 'disabled' ) : ?> selected="selected"<?php endif; ?>><?php _e( 'Disabled', 'streamline-core' ) ?></option>
          </select>
        </div>
      </div>

      <div class="form-group">
            <?php $about_arr = StreamlineCore_Wrapper::get_heared_abouts(); ?>

            <label for="heared_about_id"
                   class="col-sm-2 control-label"><?php _e('Heard about:', 'streamline-core') ?></label>

            <div class="col-sm-10">
                <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[heared_about_id]"
                        class="form-control">
                    <option value="0">None</option>
                    <?php if (is_array($about_arr) && sizeof($about_arr)) : ?>
                        <?php foreach ($about_arr as $about) : ?>
                            <option
                                value="<?php echo $about->id ?>"<?php if (isset($option_arr['heared_about_id']) && $option_arr['heared_about_id'] == $about->id) : ?> selected="selected"<?php endif; ?>><?php echo $about->name ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

    </div>
  </div>
</div>
<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title"><?php _e( 'Reservation Queue', 'streamline-core' ) ?></h4>
  </div>
  <div class="panel-body">
    <div class="form-horizontal">
      <div class="form-group">
        <label for="property_use_html" class="col-sm-2 control-label"><?php _e( 'Create Reservations as Blocked Requests', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[booking_blocked_requests]" class="form-control" value="1"<?php if ( isset( $option_arr['booking_blocked_requests'] ) && (int)$option_arr['booking_blocked_requests'] == 1 ) : ?> checked="checked"<?php endif; ?> />
        </div>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title">
      <?php _e( 'Property Inquiries', 'streamline-core' ) ?>
    </h4>
  </div>
  <div class="panel-body">
    <div class="form-horizontal">
      <div class="form-group">
        <label for="inquiry_title" class="col-sm-2 control-label"><?php _e( 'Title', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[inquiry_title]" class="form-control" placeholder="<?php _e( 'Title of inquiry widget', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['inquiry_title'] ) ? esc_attr( $option_arr['inquiry_title'] ) : '' ) ?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="inquiry_checkin_label" class="col-sm-2 control-label"><?php _e( 'Check In', 'streamline-core' ) ?></label>
        <div class="col-sm-5">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[inquiry_checkin_label]" class="form-control" placeholder="<?php _( 'Label to show for checkin', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['inquiry_checkin_label'] ) ? esc_attr( $option_arr['inquiry_checkin_label'] ) : '' ) ?>" />
        </div>
        <div class="col-sm-5">
          <?php echo ResortPro::dropdown( StreamlineCore_Settings::get_option_name() . '[inquiry_checkin_date]', array_combine( range( 0, 7 ), range( 0, 7 ) ), ( isset( $option_arr['inquiry_checkin_date'] ) ? $option_arr['inquiry_checkin_date'] : 1 ) ) ?>
        </div>
      </div>
      <div class="form-group">
        <label for="inquiry_checkout_label" class="col-sm-2 control-label"><?php _e( 'Check Out', 'streamline-core' ) ?></label>
        <div class="col-sm-5">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[inquiry_checkout_label]" class="form-control" placeholder="<?php _e( 'Label to show for checkout', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['inquiry_checkout_label'] ) ? esc_attr( $option_arr['inquiry_checkout_label'] ) : '' ) ?>" />
        </div>
        <div class="col-sm-5">
          <?php echo ResortPro::dropdown( StreamlineCore_Settings::get_option_name() . '[inquiry_default_stay]', array_combine( range( 0, 90 ), range( 0, 90 ) ), ( isset( $option_arr['inquiry_default_stay'] ) ? $option_arr['inquiry_default_stay'] : 1 ) ) ?>
        </div>
      </div>
      <div class="form-group">
        <label for="inquiry_adults_label" class="col-sm-2 control-label"><?php _e( 'Adults', 'streamline-core' ) ?></label>
        <div class="col-sm-5">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[inquiry_adults_label]" class="form-control" placeholder="<?php _e( 'Label to show for adults', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['inquiry_adults_label'] ) ? esc_attr( $option_arr['inquiry_adults_label'] ) : '' ) ?>" />
        </div>
        <div class="col-sm-5">
          <?php echo ResortPro::dropdown( StreamlineCore_Settings::get_option_name() . '[inquiry_adults_max]', array_combine( range( 0, 100 ), range( 0, 100 ) ), ( isset( $option_arr['inquiry_adults_max'] ) ? $option_arr['inquiry_adults_max'] : 99 ) ) ?>
        </div>
      </div>
      <div class="form-group">
        <label for="inquiry_children_label" class="col-sm-2 control-label"><?php _e( 'Children', 'streamline-core' ) ?></label>
        <div class="col-sm-5">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[inquiry_children_label]" class="form-control" placeholder="<?php _e( 'Label to show for children', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['inquiry_children_label'] ) ? esc_attr( $option_arr['inquiry_children_label'] ) : '' ) ?>" />
        </div>
        <div class="col-sm-5">
          <?php echo ResortPro::dropdown( StreamlineCore_Settings::get_option_name() . '[inquiry_children_max]', array_combine( range( 0, 100 ), range( 0, 100 ) ), ( isset( $option_arr['inquiry_children_max'] ) ? $option_arr['inquiry_children_max'] : 99 ) ) ?>
        </div>
      </div>
      <div class="form-group">
        <label for="inquiry_pets_label" class="col-sm-2 control-label"><?php _e( 'Pets', 'streamline-core' ) ?></label>
        <div class="col-sm-5">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[inquiry_pets_label]" class="form-control" placeholder="<?php _e( 'Label to show for pets', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['inquiry_pets_label'] ) ? esc_attr( $option_arr['inquiry_pets_label'] ) : '' ) ?>" />
        </div>
        <div class="col-sm-5">
          <?php echo ResortPro::dropdown( StreamlineCore_Settings::get_option_name() . '[inquiry_pets_max]', array_combine( range( 0, 100 ), range( 0, 100 ) ), ( isset( $option_arr['inquiry_pets_max'] ) ? $option_arr['inquiry_pets_max'] : 99 ) ) ?>
        </div>
      </div>
      <div class="form-group">
        <label for="inquiry_thankyou_msg" class="col-sm-2 control-label"><?php _e( 'Inquiry Thank Message', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <textarea name="<?php echo StreamlineCore_Settings::get_option_name() ?>[inquiry_thankyou_msg]" class="form-control" placeholder="<?php _e( 'Message to display after property inquiry', 'streamline-core' ) ?>" rows="10"><?php echo ( isset( $option_arr['inquiry_thankyou_msg'] ) ? $option_arr['inquiry_thankyou_msg'] : '' ) ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label for="inquiry_thankyou_url" class="col-sm-2 control-label"><?php _e( 'Inquiry Thank You Url', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[inquiry_thankyou_url]" class="form-control" placeholder="<?php _e( 'Inquiry thank you page URL', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['inquiry_thankyou_url'] ) ? esc_attr( $option_arr['inquiry_thankyou_url'] ) : '' ) ?>" />
          <br />
          <div class="alert alert-warning"><?php _e( 'If empty, the thank you message will be used.', 'streamline-core' ) ?></div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title"><?php _e( 'Restrict Last Minute Bookings', 'streamline-core' ) ?></h4>
  </div>
  <div class="panel-body">
    <div class="form-horizontal">
      <div class="form-group">
        <label for="property_use_html" class="col-sm-2 control-label">
          <?php _e( 'Restrict Last Minute Bookings', 'streamline-core' ) ?>
        </label>
        <div class="col-sm-10">
          <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[lm_booking_check]" class="form-control" value="1"<?php if ( isset( $option_arr['lm_booking_check'] ) && (int)$option_arr['lm_booking_check'] == 1 ) : ?> checked="checked"<?php endif; ?> />
        </div>
      </div>
      <div class="form-group">
          <label for="lm_booking_days" class="col-sm-2 control-label">
            <?php _e( 'Restrict Booking # of Days Before Check-In', 'streamline-core' ) ?>
          </label>
          <div class="col-sm-10">
            <?php echo ResortPro::dropdown( StreamlineCore_Settings::get_option_name() . '[lm_booking_days]', array_combine( range( 0, 100 ), range( 0, 100 ) ), ( isset( $option_arr['lm_booking_days'] ) ? $option_arr['lm_booking_days'] : 1 ) ) ?>
          </div>
      </div>
      <div class="form-group">
          <label for="last_minute_message" class="col-sm-2 control-label">
            <?php _e( 'Last Minute Booking Message', 'streamline-core' ) ?>
          </label>
          <div class="col-sm-10">
              <textarea name="<?php echo StreamlineCore_Settings::get_option_name() ?>[last_minute_message]" id="last_minute_message"
                        class="form-control"
                        placeholder="<?php _e( 'Message to display when user attempts to make a last minute booking', 'streamline-core' ) ?>"><?php echo $option_arr['last_minute_message']; ?></textarea>

          </div>
      </div>
    </div>
  </div>
</div>

<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title"><?php _e( 'Restrict Future Bookings', 'streamline-core' ) ?></h4>
  </div>
  <div class="panel-body">
    <div class="form-horizontal">
      <div class="form-group">
        <label for="property_use_html" class="col-sm-2 control-label">
          <?php _e( 'Restrict Future Bookings', 'streamline-core' ) ?>
        </label>
        <div class="col-sm-10">
          <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[lm_booking_future_check]" class="form-control" value="1"<?php if ( isset( $option_arr['lm_booking_future_check'] ) && (int)$option_arr['lm_booking_future_check'] == 1 ) : ?> checked="checked"<?php endif; ?> />
        </div>
      </div>
      <div class="form-group">
          <label for="lm_booking_days" class="col-sm-2 control-label">
            <?php _e( 'Restrict Checkout # of Days in Future', 'streamline-core' ) ?>
          </label>

          <div class="col-sm-10">
            <?php echo ResortPro::dropdown( StreamlineCore_Settings::get_option_name() . '[lm_booking_future_days]', array_combine( range( 0, 1095 ), range( 0, 1095 ) ), ( isset( $option_arr['lm_booking_future_days'] ) ? $option_arr['lm_booking_future_days'] : 365 ) ) ?>
          </div>
      </div>
      <div class="form-group">
          <label for="last_minute_message" class="col-sm-2 control-label">
            <?php _e( 'Future Bookings Message', 'streamline-core' ) ?>
            </label>

          <div class="col-sm-10">
              <textarea name="<?php echo StreamlineCore_Settings::get_option_name() ?>[future_booking_message]" id="future_booking_message"
                        class="form-control"
                        placeholder="<?php _e( 'Message to display when user attempts to make future booking past the number of days defined above', 'streamline-core' ) ?>"><?php echo $option_arr['future_booking_message']; ?></textarea>

          </div>
      </div>
    </div>
  </div>
</div>
