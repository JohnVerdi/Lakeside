<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title"><?php _e( 'Layout', 'streamline-core' ) ?></h4>
  </div>
  <div class="panel-body">
    <div class="form-horizontal">
      <div class="form-group">
        <label for="unit_layout" class="col-sm-2 control-label"><?php _e( 'Select Layout', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_layout]" class="form-control">
            <option value="1"<?php if ( isset( $option_arr['unit_layout'] ) && (int)$option_arr['unit_layout'] == 1 ) : ?> selected="selected"<?php endif; ?>><?php _e( 'Layout 1 (Single Page)', 'streamline-core' ) ?></option>
            <option value="2"<?php if ( isset( $option_arr['unit_layout'] ) && (int)$option_arr['unit_layout'] == 2 ) : ?> selected="selected"<?php endif; ?>><?php _e( 'Layout 2 (Single Page)', 'streamline-core' ) ?></option>
            <option value="3"<?php if ( isset( $option_arr['unit_layout'] ) && (int)$option_arr['unit_layout'] == 3 ) : ?> selected="selected"<?php endif; ?>><?php _e( 'Layout 3 (Tabs Page)', 'streamline-core' ) ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="property_use_captions" class="col-sm-2 control-label"><?php _e( 'Add caption to property gallery', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_use_captions]" class="form-control" value="1"<?php if ( isset( $option_arr['property_use_captions'] ) && (int)$option_arr['property_use_captions'] == 1 ) : ?> checked="checked"<?php endif; ?> />
        </div>
      </div>
      <div class="form-group">
        <label for="enable_share_with_friends" class="col-sm-2 control-label"><?php _e( 'Enable Share with friends functionality', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[enable_share_with_friends]" class="form-control" value="1"<?php if ( isset( $option_arr['enable_share_with_friends'] ) && (int)$option_arr['enable_share_with_friends'] == 1 ) : ?> checked="checked"<?php endif; ?> />
        </div>
      </div>
      <div class="form-group">
        <label for="property_slider_height" class="col-sm-2 control-label"><?php _e( 'Slider height', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_slider_height]" class="form-control" placeholder="<?php _e( 'Height of slider in px', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['property_slider_height'] ) ? esc_attr( $option_arr['property_slider_height'] ) : '' ) ?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="property_tabs_description" class="col-sm-2 control-label"><?php _e( 'Select Available Tabs', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_tab_amenities]" class="form-control" value="1"<?php if ( isset( $option_arr['unit_tab_amenities'] ) && (int)$option_arr['unit_tab_amenities'] == 1 ) : ?> checked="checked"<?php endif; ?> />
              <?php _e( 'Amenities', 'streamline-core' ) ?>
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_tab_location]" class="form-control" value="1"<?php if ( isset( $option_arr['unit_tab_location'] ) && (int)$option_arr['unit_tab_location'] == 1 ) : ?> checked="checked"<?php endif; ?> />
              <?php _e( 'Location (Map)', 'streamline-core' ) ?>
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_tab_reviews]" class="form-control" value="1"<?php if ( isset( $option_arr['unit_tab_reviews'] ) && (int)$option_arr['unit_tab_reviews'] == 1 ) : ?> checked="checked"<?php endif; ?> />
              <?php _e( 'Reviews', 'streamline-core' ) ?>
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_tab_room_details]" class="form-control" value="1"<?php if ( isset( $option_arr['unit_tab_room_details'] ) && (int)$option_arr['unit_tab_room_details'] == 1 ) : ?> checked="checked"<?php endif; ?> />
              <?php _e( 'Room Details', 'streamline-core' ) ?>
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_tab_rates]" class="form-control" value="1"<?php if ( isset( $option_arr['unit_tab_rates'] ) && (int)$option_arr['unit_tab_rates'] == 1 ) : ?> checked="checked"<?php endif; ?> />
              <?php _e( 'Rates', 'streamline-core' ) ?>
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_tab_availability]" class="form-control" value="1"<?php if ( isset( $option_arr['unit_tab_availability'] ) && (int)$option_arr['unit_tab_availability'] == 1 ) : ?> checked="checked"<?php endif; ?> />
              <?php _e( 'Availability', 'streamline-core' ) ?>
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_tab_floorplan]" class="form-control" value="1"<?php if ( isset( $option_arr['unit_tab_floorplan'] ) && (int)$option_arr['unit_tab_floorplan'] == 1 ) : ?> checked="checked"<?php endif; ?> />
              <?php _e( 'Floor Plan', 'streamline-core' ) ?>
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_tab_virtualtour]" class="form-control" value="1"<?php if ( isset( $option_arr['unit_tab_virtualtour'] ) && (int)$option_arr['unit_tab_virtualtour'] == 1 ) : ?> checked="checked"<?php endif; ?> />
              <?php _e( 'Virtual Tour', 'streamline-core' ) ?>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title"><?php _e( 'SEO &amp; URLs', 'streamline-core' ) ?></h4>
  </div>
  <div class="panel-body">
    <div class="form-horizontal">
      <div class="form-group">
        <label for="unit_book_title" class="col-sm-2 control-label"><?php _e( 'Prepend property page link', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[prepend_property_page]" class="form-control" placeholder="<?php _e( 'Name before the property page in the url', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['prepend_property_page'] ) ? esc_attr( $option_arr['prepend_property_page'] ) : '' ) ?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="property_use_html" class="col-sm-2 control-label"><?php _e( 'Append .html', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_use_html]" class="form-control" value="1"<?php if ( isset( $option_arr['property_use_html'] ) && (int)$option_arr['property_use_html'] == 1 ) : ?> checked="checked"<?php endif; ?> />
        </div>
      </div>
      <div class="form-group">
        <label for="property_seo_put_canonical" class="col-sm-2 control-label"><?php _e( 'Put link rel="canonical"', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_seo_put_canonical]" class="form-control" value="1"<?php if ( isset( $option_arr['property_seo_put_canonical'] ) && (int)$option_arr['property_seo_put_canonical'] == 1 ) : ?> checked="checked"<?php endif; ?> />
        </div>
      </div>
      <div class="form-group">
        <label for="property_seo_put_title" class="col-sm-2 control-label"><?php _e( 'Put title tag (pull from system)', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_seo_put_title]" class="form-control" value="1"<?php if ( isset( $option_arr['property_seo_put_title'] ) && (int)$option_arr['property_seo_put_title'] == 1 ) : ?> checked="checked"<?php endif; ?> />
        </div>
      </div>
      <div class="form-group">
        <label for="property_seo_put_description" class="col-sm-2 control-label"><?php _e( 'Put meta keywords tag', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_seo_put_description]" class="form-control" value="1"<?php if ( isset( $option_arr['property_seo_put_description'] ) && (int)$option_arr['property_seo_put_description'] == 1 ) : ?> checked="checked"<?php endif; ?> />
        </div>
      </div>
      <div class="form-group">
        <label for="property_seo_put_keywords" class="col-sm-2 control-label"><?php _e( 'Put meta description tag', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_seo_put_keywords]" class="form-control" value="1"<?php if ( isset( $option_arr['property_seo_put_keywords'] ) && (int)$option_arr['property_seo_put_keywords'] == 1 ) : ?> checked="checked"<?php endif; ?> />
        </div>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title"><?php _e( 'Book Now', 'streamline-core' ) ?></h4>
  </div>
  <div class="panel-body">
    <div class="form-horizontal">
      <div class="form-group">
        <label for="unit_book_title" class="col-sm-2 control-label"><?php _e( 'Title', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_book_title]" class="form-control" placeholder="<?php _e( 'Title of book unit widget', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['unit_book_title'] ) ? esc_attr( $option_arr['unit_book_title'] ) : '' ) ?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="unit_book_checkin_label" class="col-sm-2 control-label"><?php _e( 'Check In', 'streamline-core' ) ?></label>
        <div class="col-sm-5">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_book_checkin_label]" class="form-control" placeholder="<?php _e( 'Label to show for checkin', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['unit_book_checkin_label'] ) ? esc_attr( $option_arr['unit_book_checkin_label'] ) : '' ) ?>" />
        </div>
        <div class="col-sm-5">
          <?php echo ResortPro::dropdown( StreamlineCore_Settings::get_option_name() . '[unit_book_checkin_date]', array_combine( range( 0, 7 ), range( 0, 7 ) ), ( isset( $option_arr['unit_book_checkin_date'] ) ? $option_arr['unit_book_checkin_date'] : 1 ) ) ?>
        </div>
      </div>
      <div class="form-group">
        <label for="unit_book_checkout_label" class="col-sm-2 control-label"><?php _e( 'Check Out', 'streamline-core' ) ?></label>
        <div class="col-sm-5">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_book_checkout_label]" class="form-control" placeholder="<?php _e( 'Label to show for checkout', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['unit_book_checkout_label'] ) ? esc_attr( $option_arr['unit_book_checkout_label'] ) : '' ) ?>" />
        </div>
        <div class="col-sm-5">
          <?php echo ResortPro::dropdown( StreamlineCore_Settings::get_option_name() . '[unit_book_default_stay]', array_combine( range( 0, 90 ), range( 0, 90 ) ), ( isset( $option_arr['unit_book_default_stay'] ) ? $option_arr['unit_book_default_stay'] : 1 ) ) ?>
        </div>
      </div>
      <div class="form-group">
        <label for="unit_book_adults_label" class="col-sm-2 control-label"><?php _e( 'Adults', 'streamline-core' ) ?></label>
        <div class="col-sm-5">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_book_adults_label]" class="form-control" placeholder="<?php _e( 'Label to show for adults', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['unit_book_adults_label'] ) ? esc_attr( $option_arr['unit_book_adults_label'] ) : '' ) ?>" />
        </div>
        <div class="col-sm-5">
          <?php echo ResortPro::dropdown( StreamlineCore_Settings::get_option_name() . '[unit_book_adults_max]', array_combine( range( 0, 100 ), range( 0, 100 ) ), ( isset( $option_arr['unit_book_adults_max'] ) ? $option_arr['unit_book_adults_max'] : 99 ) ) ?>
        </div>
      </div>
      <div class="form-group">
        <label for="unit_book_children_label" class="col-sm-2 control-label"><?php _e( 'Children', 'streamline-core' ) ?></label>
        <div class="col-sm-5">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_book_children_label]" class="form-control" placeholder="<?php _e( 'Label to show for children', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['unit_book_children_label'] ) ? esc_attr( $option_arr['unit_book_children_label'] ) : '' ) ?>" />
        </div>
        <div class="col-sm-5">
          <?php echo ResortPro::dropdown( StreamlineCore_Settings::get_option_name() . '[unit_book_children_max]', array_combine( range( 0, 100 ), range( 0, 100 ) ), ( isset( $option_arr['unit_book_children_max'] ) ? $option_arr['unit_book_children_max'] : 99 ) ) ?>
        </div>
      </div>
      <div class="form-group">
        <label for="unit_book_pets_label" class="col-sm-2 control-label"><?php _e( 'Pets', 'streamline-core' ) ?></label>
        <div class="col-sm-5">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[unit_book_pets_label]" class="form-control" placeholder="<?php _e( 'Label to show for pets', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['unit_book_pets_label'] ) ? esc_attr( $option_arr['unit_book_pets_label'] ) : '' ) ?>" />
        </div>
        <div class="col-sm-5">
          <?php echo ResortPro::dropdown( StreamlineCore_Settings::get_option_name() . '[unit_book_pets_max]', array_combine( range( 0, 100 ), range( 0, 100 ) ), ( isset( $option_arr['unit_book_pets_max'] ) ? $option_arr['unit_book_pets_max'] : 99 ) ) ?>
        </div>
      </div>
      <div class="form-group">
        <label for="property_use_html" class="col-sm-2 control-label"><?php _e( 'Make it sticky', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[book_sticky]" class="control-label" value="1"<?php if ( isset( $option_arr['book_sticky'] ) && (int)$option_arr['book_sticky'] == 1 ) : ?> checked="checked"<?php endif; ?> />
        </div>
      </div>
      <div class="form-group">
        <label for="top_spacing" class="col-sm-2 control-label"><?php _e( 'Top Spacing', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[top_spacing]" class="form-control" placeholder="<?php _e( 'Sticky Top Spacing', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['top_spacing'] ) ? esc_attr( $option_arr['top_spacing'] ) : '' ) ?>" />
        </div>
      </div>
      <div class="form-group">
        <label for="<?php echo StreamlineCore_Settings::get_option_name() ?>[bottom_spacing]" class="col-sm-2 control-label"><?php _e( 'Bottom Spacing', 'streamline-core' ) ?></label>
        <div class="col-sm-10">
          <input type="text" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[bottom_spacing]" class="form-control" placeholder="<?php _e( 'Sticky Bottom Spacing', 'streamline-core' ) ?>" value="<?php echo ( isset( $option_arr['bottom_spacing'] ) ? esc_attr( $option_arr['bottom_spacing'] ) : '' ) ?>" />
        </div>
      </div>
    </div>
  </div>
</div>
