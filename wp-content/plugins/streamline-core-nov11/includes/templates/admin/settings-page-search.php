<div class="panel panel-info">
    <div class="panel-heading">
        <h4 class="panel-title"><?php _e('Layout', 'streamline-core') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="page_layout"
                       class="col-sm-2 control-label"><?php echo _e('Page Layout', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[page_layout]"
                            class="form-control">
                        <option
                            value="fluid"<?php if (isset($option_arr['page_layout']) && $option_arr['page_layout'] == 'fluid') : ?> selected="selected"<?php endif; ?>><?php _e('Full Width', 'streamline-core') ?></option>
                        <option
                            value="boxed"<?php if (isset($option_arr['page_layout']) && $option_arr['page_layout'] == 'boxed') : ?> selected="selected"<?php endif; ?>><?php _e('Boxed', 'streamline-core') ?></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="search_layout"
                       class="col-sm-2 control-label"><?php _e('Search Layout', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[search_layout]"
                            class="form-control">
                        <option
                            value="1"<?php if (isset($option_arr['search_layout']) && $option_arr['search_layout'] == '1') : ?> selected="selected"<?php endif; ?>><?php _e('Grid Style 1', 'streamline-core') ?></option>
                        <option
                            value="2"<?php if (isset($option_arr['search_layout']) && $option_arr['search_layout'] == '2') : ?> selected="selected"<?php endif; ?>><?php _e('Grid Style 2', 'streamline-core') ?></option>
                        <option
                            value="3"<?php if (isset($option_arr['search_layout']) && $option_arr['search_layout'] == '3') : ?> selected="selected"<?php endif; ?>><?php _e('Large Thumbnails', 'streamline-core') ?></option>
                        <option
                            value="4"<?php if (isset($option_arr['search_layout']) && $option_arr['search_layout'] == '4') : ?> selected="selected"<?php endif; ?>><?php _e('Medium Thumbnails', 'streamline-core') ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 class="panel-title"><?php _e('Search Method', 'streamline-core') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="page_layout"
                       class="col-sm-2 control-label"><?php echo _e('Search Method', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[search_method]"
                            class="form-control">
                        <option
                            value="GetPropertyAvailabilitySimple"<?php if (isset($option_arr['search_method']) && $option_arr['search_method'] == 'GetPropertyAvailabilitySimple') : ?> selected="selected"<?php endif; ?>><?php _e('Availability Simple (faster)', 'streamline-core') ?></option>
                        <option
                            value="GetPropertyAvailabilityWithRatesWordPress"<?php if (isset($option_arr['search_method']) && $option_arr['search_method'] == 'GetPropertyAvailabilityWithRatesWordPress') : ?> selected="selected"<?php endif; ?>><?php _e('Availability With Rates (slower)', 'streamline-core') ?></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="<?php echo StreamlineCore_Settings::get_option_name() ?>[client_side_amenities]" value="1"<?php if ( isset( $option_arr['client_side_amenities'] ) && (int)$option_arr['client_side_amenities'] == 1 ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Use client side search for amenities', 'streamline-core' ) ?>
                  </label>
                </div>
              </div>
            </div>
            
            <div class="form-group">
                <label for="search_layout"
                       class="col-sm-2 control-label"><?php _e('Max. number of days to search for availability', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[max_days_search]"
                            class="form-control">
                        <?php for ($i = 1; $i <= 30; $i++):
                            $selected = (isset($option_arr['max_days_search']) && $option_arr['max_days_search'] == $i) ? 'selected="selected"' : '';
                            ?>
                            <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>

                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="search_layout"
                       class="col-sm-2 control-label"><?php _e('Price display', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[price_display]"
                            class="form-control">
                        <option
                            value="price"<?php if (isset($option_arr['price_display']) && $option_arr['price_display'] == 'price') : ?> selected="selected"<?php endif; ?>><?php _e('Subtotal (excluding taxes)', 'streamline-core') ?></option>
                        <option
                            value="total"<?php if (isset($option_arr['price_display']) && $option_arr['price_display'] == 'total') : ?> selected="selected"<?php endif; ?>><?php _e('Total (including taxes)', 'streamline-core') ?></option>
                    </select>                    
                </div>
            </div>
            
            <div class="form-group">
                <label for="search_layout"
                       class="col-sm-2 control-label"><?php _e('Search Options', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[additional_variables]"
                                   value="1"<?php if (isset($option_arr['additional_variables']) && (int)$option_arr['additional_variables'] == 1) : ?> checked="checked"<?php endif; ?> /> <?php _e('Use additional variables', 'streamline-core') ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[extra_charges]"
                                   value="1"<?php if (isset($option_arr['extra_charges']) && (int)$option_arr['extra_charges'] == 1) : ?> checked="checked"<?php endif; ?> /> <?php _e('Use extra charges', 'streamline-core') ?>
                        </label>
                    </div>
                </div>
            </div>
            
            
            
            <div class="form-group">
                <label for="search_layout"
                       class="col-sm-2 control-label"><?php _e('Price Templates', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"
                                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[use_daily_pricing]"
                                           value="1"<?php if (isset($option_arr['use_daily_pricing']) && (int)$option_arr['use_daily_pricing'] == 1) : ?> checked="checked"<?php endif; ?> /> <?php _e('Use daily pricing', 'streamline-core') ?>
                                    

                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text"
                                       name="<?php echo StreamlineCore_Settings::get_option_name() ?>[daily_pricing_prepend]"
                                       class="form-control"
                                       placeholder="<?php _e('Daily pricing prepend ', 'streamline-core') ?>"
                                       value="<?php echo(isset($option_arr['daily_pricing_prepend']) ? esc_attr($option_arr['daily_pricing_prepend']) : 'Starting from') ?>"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text"
                                       name="<?php echo StreamlineCore_Settings::get_option_name() ?>[daily_pricing_append]"
                                       class="form-control"
                                       placeholder="<?php _e('Daily pricing append ', 'streamline-core') ?>"
                                       value="<?php echo(isset($option_arr['daily_pricing_append']) ? esc_attr($option_arr['daily_pricing_append']) : 'daily') ?>"/>
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"
                                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[use_weekly_pricing]"
                                           value="1"<?php if (isset($option_arr['use_weekly_pricing']) && (int)$option_arr['use_weekly_pricing'] == 1) : ?> checked="checked"<?php endif; ?> /> <?php _e('Use weekly pricing', 'streamline-core') ?>                                    
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text"
                                       name="<?php echo StreamlineCore_Settings::get_option_name() ?>[weekly_pricing_prepend]"
                                       class="form-control"
                                       placeholder="<?php _e('Weekly pricing prepend ', 'streamline-core') ?>"
                                       value="<?php echo(isset($option_arr['weekly_pricing_prepend']) ? esc_attr($option_arr['weekly_pricing_prepend']) : 'Starting from') ?>"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text"
                                       name="<?php echo StreamlineCore_Settings::get_option_name() ?>[weekly_pricing_append]"
                                       class="form-control"
                                       placeholder="<?php _e('Weekly pricing append ', 'streamline-core') ?>"
                                       value="<?php echo(isset($option_arr['weekly_pricing_append']) ? esc_attr($option_arr['weekly_pricing_append']) : 'weekly') ?>"/>
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"
                                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[use_monthly_pricing]"
                                           value="1"<?php if (isset($option_arr['use_monthly_pricing']) && (int)$option_arr['use_monthly_pricing'] == 1) : ?> checked="checked"<?php endif; ?> /> <?php _e('Use monthly pricing', 'streamline-core') ?>                                    
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text"
                                       name="<?php echo StreamlineCore_Settings::get_option_name() ?>[monthly_pricing_prepend]"
                                       class="form-control"
                                       placeholder="<?php _e('Monthly pricing prepend ', 'streamline-core') ?>"
                                       value="<?php echo(isset($option_arr['monthly_pricing_prepend']) ? esc_attr($option_arr['monthly_pricing_prepend']) : 'Starting from') ?>"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text"
                                       name="<?php echo StreamlineCore_Settings::get_option_name() ?>[monthly_pricing_append]"
                                       class="form-control"
                                       placeholder="<?php _e('Monthly pricing append ', 'streamline-core') ?>"
                                       value="<?php echo(isset($option_arr['monthly_pricing_append']) ? esc_attr($option_arr['monthly_pricing_append']) : 'monthly') ?>"/>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 class="panel-title"><?php _e('Wordings', 'streamline-core') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="property_delete_text_units"
                       class="col-sm-2 control-label"><?php _e('Insert this text', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <input type="text"
                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_delete_text_units]"
                           class="form-control"
                           placeholder="<?php _e('Text to display when unit not available', 'streamline-core') ?>"
                           value="<?php echo(isset($option_arr['property_delete_text_units']) ? esc_attr($option_arr['property_delete_text_units']) : '') ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label for="message_restriction"
                       class="col-sm-2 control-label"><?php _e('Restriction message', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <input type="text"
                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[message_restriction]"
                           class="form-control" placeholder="<?php _e('Restriction message', 'streamline-core') ?>"
                           value="<?php echo(isset($option_arr['message_restriction']) ? esc_attr($option_arr['message_restriction']) : '') ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label for="message_no_inventory"
                       class="col-sm-2 control-label"><?php _e('No inventory message', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <input type="text"
                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[message_no_inventory]"
                           class="form-control" placeholder="<?php _e('No inventory message', 'streamline-core') ?>"
                           value="<?php echo(isset($option_arr['message_no_inventory']) ? esc_attr($option_arr['message_no_inventory']) : '') ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 class="panel-title"><?php _e('Skip Units', 'streamline-core') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="property_delete_units"
                       class="col-sm-2 control-label"><?php _e('Hide this units', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <input type="text"
                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_delete_units]"
                           class="form-control"
                           placeholder="<?php _e('Enter the names of units which do not display (separator - comma)', 'streamline-core') ?>"
                           value="<?php echo(isset($option_arr['property_delete_units']) ? esc_attr($option_arr['property_delete_units']) : '') ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label for="property_delete_book_now_units"
                       class="col-sm-2 control-label"><?php _e('Hide book now button', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <input type="text"
                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_delete_book_now_units]"
                           class="form-control"
                           placeholder="<?php _e('Enter the names of units which do not display Book Now button (comma delimeted)', 'streamline-core') ?>"
                           value="<?php echo(isset($option_arr['property_delete_book_now_units']) ? esc_attr($option_arr['property_delete_book_now_units']) : '') ?>"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_use_disable_minimal_days]"
                                   value="1"<?php if (isset($option_arr['property_use_disable_minimal_days']) && (int)$option_arr['property_use_disable_minimal_days'] == 1) : ?> checked="checked"<?php endif; ?> /> <?php _e('Disable minimal days', 'streamline-core') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="property_lodgin_type"
                       class="col-sm-2 control-label"><?php _e('Please select what need show in website', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_lodgin_type]"
                            class="form-control">
                        <option
                            value="0"<?php if (isset($option_arr['property_lodgin_type']) && (int)$option_arr['property_lodgin_type'] == 0) : ?> selected="selected"<?php endif; ?>><?php _e('Search All', 'streamline-core') ?></option>
                        <option
                            value="3"<?php if (isset($option_arr['property_lodgin_type']) && (int)$option_arr['property_lodgin_type'] == 3) : ?> selected="selected"<?php endif; ?>><?php _e('Homes', 'streamline-core') ?></option>
                        <option
                            value="1"<?php if (isset($option_arr['property_lodgin_type']) && (int)$option_arr['property_lodgin_type'] == 1) : ?> selected="selected"<?php endif; ?>><?php _e('Resorts', 'streamline-core') ?></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <?php $location_arr = StreamlineCore_Wrapper::get_locations(); ?>
                <label for="property_loc_id"
                       class="col-sm-2 control-label"><?php _e('You can select just one Resort:', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_loc_id]"
                            class="form-control">
                        <option value="0">All</option>
                        <?php if (is_array($location_arr) && sizeof($location_arr)) : ?>
                            <?php foreach ($location_arr as $location) : ?>
                                <option
                                    value="<?php echo $location->id ?>"<?php if (isset($option_arr['property_loc_id']) && $option_arr['property_loc_id'] == $location->id) : ?> selected="selected"<?php endif; ?>><?php echo $location->name ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <?php $location_arr = StreamlineCore_Wrapper::get_location_resorts(); ?>
                <label for="property_loc_id"
                       class="col-sm-2 control-label"><?php _e('You can select just one Location Resort:', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_resort_area_id]"
                            class="form-control">
                        <option value="0">All</option>
                        <?php if (is_array($location_arr) && sizeof($location_arr)) : ?>
                            <?php foreach ($location_arr as $location) : ?>
                                <option
                                    value="<?php echo $location->id ?>"<?php if (isset($option_arr['property_resort_area_id']) && $option_arr['property_resort_area_id'] == $location->id) : ?> selected="selected"<?php endif; ?>><?php echo $location->name ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <?php $area_arr = StreamlineCore_Wrapper::get_location_areas(); ?>
                <label for="filter_location_areas"
                       class="col-sm-2 control-label"><?php _e('Show units from selected areas ONLY:', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <?php if (sizeof($area_arr)) : ?>
                        <?php foreach ($area_arr as $area) : ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="1"
                                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[filter_location_areas][<?php echo $area->id ?>]"<?php if (isset($option_arr['filter_location_areas']) && array_key_exists($area->id, $option_arr['filter_location_areas'])) : ?> checked="checked"<?php endif; ?> />
                                    <?php echo $area->name ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 class="panel-title"><?php _e('Units Name', 'streamline-core') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_use_seo_name]"
                                   value="1" <?php if (isset($option_arr['property_use_seo_name']) && (int)$option_arr['property_use_seo_name'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Use SEO name', 'streamline-core') ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 class="panel-title"><?php _e('Amenities or description', 'streamline-core') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_description]"
                    class="form-control">
                <option
                    value="0"<?php if (isset($option_arr['property_description']) && (int)$option_arr['property_description'] == 0) : ?> selected="selected"<?php endif; ?>><?php _e('Amenities', 'streamline-core') ?></option>
                <option
                    value="1"<?php if (isset($option_arr['property_description']) && (int)$option_arr['property_description'] == 1) : ?> selected="selected"<?php endif; ?>><?php _e('Description', 'streamline-core') ?></option>
            </select>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 class="panel-title"><?php _e('Pets', 'streamline-core') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_show_pets]"
                    class="form-control">
                <option
                    value="0"<?php if (isset($option_arr['property_show_pets']) && (int)$option_arr['property_show_pets'] == 0) : ?> selected="selected"<?php endif; ?>><?php _e('Show image', 'streamline-core') ?></option>
                <option
                    value="1"<?php if (isset($option_arr['property_show_pets']) && (int)$option_arr['property_show_pets'] == 1) : ?> selected="selected"<?php endif; ?>><?php _e('Show text', 'streamline-core') ?></option>
            </select>
        </div>
        <div class="form-group">
            <input type='text' name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_pets_text]"
                   class="form-control" placeholder="<?php _e('Text to display for pets', 'streamline-core') ?>"
                   value="<?php echo(isset($option_arr['property_pets_text']) ? esc_attr($option_arr['property_pets_text']) : '') ?>"/>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading" role="tab">
        <h4 class="panel-title"><?php _e('Pagination', 'streamline-core') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="property_pagination"
                       class="col-sm-2 control-label"><?php _e('Pagination', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <input type="text"
                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_pagination]"
                           class="form-control"
                           placeholder="<?php _e('Units to be displayed on a search page', 'streamline-core') ?>"
                           value="<?php echo(isset($option_arr['property_pagination']) ? esc_attr($option_arr['property_pagination']) : '') ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading" role="tab">
        <h4 class="panel-title"><?php _e('Title', 'streamline-core') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="property_title_structure"
                       class="col-sm-2 control-label"><?php _e('Name Structure', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_title_structure]"
                            class="form-control">
                        <option
                            value="0"<?php if (isset($option_arr['property_title_structure']) && (int)$option_arr['property_title_structure'] == 0) : ?> selected="selected"<?php endif; ?>><?php _e('With company name', 'streamline-core') ?></option>
                        <option
                            value="1"<?php if (isset($option_arr['property_title_structure']) && (int)$option_arr['property_title_structure'] == 1) : ?> selected="selected"<?php endif; ?>><?php _e('Without company name', 'streamline-core') ?></option>
                        <option
                            value="2"<?php if (isset($option_arr['property_title_structure']) && (int)$option_arr['property_title_structure'] == 2) : ?> selected="selected"<?php endif; ?>><?php _e('With text', 'streamline-core') ?></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="property_title_additional_text"
                       class="col-sm-2 control-label"><?php _e('Add to name text', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <input type="text"
                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_title_additional_text]"
                           class="form-control"
                           value="<?php echo(isset($option_arr['property_title_additional_text']) ? esc_attr($option_arr['property_title_additional_text']) : '') ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading" role="tab">
        <h4 class="panel-title"><?php _e('Price', 'streamline-core') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="property_price_text"
                       class="col-sm-2 control-label"><?php _e('Text', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_price_text]"
                            class="form-control">
                        <option
                            value="nothing"<?php if (isset($option_arr['property_price_text']) && $option_arr['property_price_text'] == 'nothing') : ?> selected="selected"<?php endif; ?>><?php _e('Nothing', 'streamline-core') ?></option>
                        <option
                            value="price"<?php if (isset($option_arr['property_price_text']) && $option_arr['property_price_text'] == 'price') : ?> selected="selected"<?php endif; ?>><?php _e('Price', 'streamline-core') ?></option>
                        <option
                            value="starting"<?php if (isset($option_arr['property_price_text']) && $option_arr['property_price_text'] == 'starting') : ?> selected="selected"<?php endif; ?>><?php _e('Starting From', 'streamline-core') ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 class="panel-title"><?php _e('Bedrooms/Bathrooms', 'streamline-core') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="property_brba_text_br"
                       class="col-sm-2 control-label"><?php _e('Bedrooms Label', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <input type="text"
                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_brba_text_br]"
                           class="form-control"
                           value="<?php echo(isset($option_arr['property_brba_text_br']) ? esc_attr($option_arr['property_brba_text_br']) : '') ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label for="property_brba_text_ba"
                       class="col-sm-2 control-label"><?php _e('Bathrooms Label', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <input type="text"
                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_brba_text_ba]"
                           class="form-control"
                           value="<?php echo(isset($option_arr['property_brba_text_ba']) ? esc_attr($option_arr['property_brba_text_ba']) : '') ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 class="panel-title"><?php _e('Sleeps', 'streamline-core') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="property_sleeps_text"
                       class="col-sm-2 control-label"><?php _e('Sleeps Label', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <input type="text" class="form-control"
                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_sleeps_text]"
                           value="<?php echo(isset($option_arr['property_sleeps_text']) ? esc_attr($option_arr['property_sleeps_text']) : '') ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 class="panel-title"><?php _e('Contact', 'streamlinecode') ?></h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="property_contact_text"
                       class="col-sm-2 control-label"><?php _e('Contact Label', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <input type="text" class="form-control"
                           name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_contact_text]"
                           value="<?php echo(isset($option_arr['property_contact_text']) ? esc_attr($option_arr['property_contact_text']) : '') ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion-search"
                                   href="#panel-search-reviews" aria-expanded="true"
                                   aria-controls="heading-search-reviews"><?php _e('Reviews', 'streamlinecode') ?></a>
        </h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_show_rating]"
                                   value="1"<?php if (isset($option_arr['property_show_rating']) && (int)$option_arr['property_show_rating'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Show Ratings', 'streamlinecode') ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion-search"
                                   href="#panel-search-sort-by" aria-expanded="true"
                                   aria-controls="heading-search-sort-by"><?php _e('Sort By', 'streamline-core') ?></a>
        </h4>
    </div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[property_show_sorting_options]"
                                   value="1"<?php if (isset($option_arr['property_show_sorting_options']) && (int)$option_arr['property_show_sorting_options'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Show Sorting Options', 'streamline-core') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="resortpro_default_filter"
                       class="col-sm-2 control-label"><?php _e('Select default filter', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <select name="<?php echo StreamlineCore_Settings::get_option_name() ?>[resortpro_default_filter]"
                            class="form-control">
                        <option value=""><?php _e('None', 'streamline-core') ?></option>
                        <option
                            value="max_occupants"<?php if (isset($option_arr['resortpro_default_filter']) && $option_arr['resortpro_default_filter'] == 'max_occupants') : ?> selected="selected"<?php endif; ?>><?php _e('Occupants (high to low)', 'streamline-core') ?></option>
                        <option
                            value="min_occupants"<?php if (isset($option_arr['resortpro_default_filter']) && $option_arr['resortpro_default_filter'] == 'min_occupants') : ?> selected="selected"<?php endif; ?>><?php _e('Occupants (low to high)', 'streamline-core') ?></option>
                        <option
                            value="bedrooms_number"<?php if (isset($option_arr['resortpro_default_filter']) && $option_arr['resortpro_default_filter'] == 'bedrooms_number') : ?> selected="selected"<?php endif; ?>><?php _e('Bedrooms (high to low)', 'streamline-core') ?></option>                        
                        <option
                            value="min_bedrooms_number"<?php if (isset($option_arr['resortpro_default_filter']) && $option_arr['resortpro_default_filter'] == 'min_bedrooms_number') : ?> selected="selected"<?php endif; ?>><?php _e('Bedrooms (low to high)', 'streamline-core') ?></option>                        
                        <option
                            value="name"<?php if (isset($option_arr['resortpro_default_filter']) && $option_arr['resortpro_default_filter'] == 'name') : ?> selected="selected"<?php endif; ?>><?php _e('Unit Name', 'streamline-core') ?></option>
                        <!--
                        <option
                            value="area"<?php if (isset($option_arr['resortpro_default_filter']) && $option_arr['resortpro_default_filter'] == 'area') : ?> selected="selected"<?php endif; ?>><?php _e('Square feet', 'streamline-core') ?></option>
                        <option
                            value="view"<?php if (isset($option_arr['resortpro_default_filter']) && $option_arr['resortpro_default_filter'] == 'view') : ?> selected="selected"<?php endif; ?>><?php _e('View', 'streamline-core') ?></option>
                            -->
                        <option
                            value="price"<?php if (isset($option_arr['resortpro_default_filter']) && $option_arr['resortpro_default_filter'] == 'price') : ?> selected="selected"<?php endif; ?>><?php _e('Price (high to low)', 'streamline-core') ?></option>

                        <option
                            value="price_low"<?php if (isset($option_arr['resortpro_default_filter']) && $option_arr['resortpro_default_filter'] == 'price_low') : ?> selected="selected"<?php endif; ?>><?php _e('Price (low to high)', 'streamline-core') ?></option>

                        <option
                            value="pets"<?php if (isset($option_arr['resortpro_default_filter']) && $option_arr['resortpro_default_filter'] == 'pets') : ?> selected="selected"<?php endif; ?>><?php _e('Pets', 'streamline-core') ?></option>
                        <option
                            value="rotation"<?php if (isset($option_arr['resortpro_default_filter']) && $option_arr['resortpro_default_filter'] == 'rotation') : ?> selected="selected"<?php endif; ?>><?php _e('Unit Rotate', 'streamline-core') ?></option>
                        <option
                            value="random"<?php if (isset($option_arr['resortpro_default_filter']) && $option_arr['resortpro_default_filter'] == 'random') : ?> selected="selected"<?php endif; ?>><?php _e('Random', 'streamline-core') ?></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php _e('Select filters:', 'streamline-core') ?></label>

                <div class="col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[sort_filter_occupants]"
                                   class="form-control"
                                   value="1"<?php if (isset($option_arr['sort_filter_occupants']) && (int)$option_arr['sort_filter_occupants'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Occupants (high to low)', 'streamline-core') ?>
                        </label>
                    </div>
                     <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[sort_filter_occupants_min]"
                                   class="form-control"
                                   value="1"<?php if (isset($option_arr['sort_filter_occupants_min']) && (int)$option_arr['sort_filter_occupants_min'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Occupants (low to high)', 'streamline-core') ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[sort_filter_bedrooms_number]"
                                   class="form-control"
                                   value="1"<?php if (isset($option_arr['sort_filter_bedrooms_number']) && (int)$option_arr['sort_filter_bedrooms_number'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Bedrooms Number (high to low)', 'streamline-core') ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[sort_filter_bedrooms_number_min]"
                                   class="form-control"
                                   value="1"<?php if (isset($option_arr['sort_filter_bedrooms_number_min']) && (int)$option_arr['sort_filter_bedrooms_number_min'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Bedrooms Number (low to high)', 'streamline-core') ?>
                        </label>
                    </div>
                    <!--
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[sort_filter_bathrooms_number]"
                                   class="form-control"
                                   value="1"<?php if (isset($option_arr['sort_filter_bathrooms_number']) && (int)$option_arr['sort_filter_bathrooms_number'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Bathrooms Number', 'streamline-core') ?>
                        </label>
                    </div>
                    -->
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[sort_filter_name]"
                                   class="form-control"
                                   value="1"<?php if (isset($option_arr['sort_filter_name']) && (int)$option_arr['sort_filter_name'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Unit Name', 'streamline-core') ?>
                        </label>
                    </div>
                    <!--
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[sort_filter_area]"
                                   class="form-control"
                                   value="1"<?php if (isset($option_arr['sort_filter_area']) && (int)$option_arr['sort_filter_area'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Square Feet', 'streamline-core') ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[sort_filter_view]"
                                   class="form-control"
                                   value="1"<?php if (isset($option_arr['sort_filter_view']) && (int)$option_arr['sort_filter_view'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('View', 'streamline-core') ?>
                        </label>
                    </div>
                    -->
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[sort_filter_price]"
                                   class="form-control"
                                   value="1"<?php if (isset($option_arr['sort_filter_price']) && (int)$option_arr['sort_filter_price'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Price (hight to low)', 'streamline-core') ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[sort_filter_price_low]"
                                   class="form-control"
                                   value="1"<?php if (isset($option_arr['sort_filter_price_low']) && (int)$option_arr['sort_filter_price_low'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Price (low to high)', 'streamline-core') ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo StreamlineCore_Settings::get_option_name() ?>[sort_filter_pets]"
                                   class="form-control"
                                   value="1"<?php if (isset($option_arr['sort_filter_pets']) && (int)$option_arr['sort_filter_pets'] == 1) : ?> checked="checked"<?php endif; ?> />
                            <?php _e('Pets', 'streamline-core') ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
