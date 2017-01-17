<?php $post_id = intval($_GET['id']); ?>
<span class="hidden st_partner_avaiablity <?php echo STInput::get('sc') ?> "></span>
<div class="row calendar-wrapper template-user" data-post-id="<?php echo $post_id; ?>">
    <div class="col-xs-12 col-md-12">
        <div class="calendar-form">
            <div class="row">
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <label for="calendar_check_in"><?php echo __('Check In', ST_TEXTDOMAIN); ?></label>
                        <input readonly="readonly" type="text" class="form-control date-picker" name="calendar_check_in" id="calendar_check_in">
                    </div>
                </div>
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <label for="calendar_check_out"><?php echo __('Check Out', ST_TEXTDOMAIN); ?></label>
                        <input readonly="readonly" type="text" class="form-control date-picker" name="calendar_check_out" id="calendar_check_out">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 <?php if(get_post_meta($post_id,'hide_adult_in_booking_form',true) == 'on') echo 'hide' ?>">
                    <div class="form-group">
                        <label for="calendar_adult_price"><?php echo __('Adult Price', ST_TEXTDOMAIN); ?></label>
                        <input type="text" name="calendar_adult_price" id="calendar_adult_price" class="form-control number">
                    </div>
                </div>
                <div class="col-xs-4 <?php if(get_post_meta($post_id,'hide_children_in_booking_form',true) == 'on') echo 'hide' ?>">
                    <div class="form-group ">
                        <label for="calendar_child_price"><?php echo __('Child Price', ST_TEXTDOMAIN); ?></label>
                        <input type="text" name="calendar_child_price" id="calendar_child_price" class="form-control number">
                    </div>
                </div>
                <div class="col-xs-4 <?php if(get_post_meta($post_id,'hide_infant_in_booking_form',true) == 'on') echo 'hide' ?>">
                    <div class="form-group ">
                        <label for="calendar_infant_price"><?php echo __('Infant Price', ST_TEXTDOMAIN); ?></label>
                        <input type="text" name="calendar_infant_price" id="calendar_infant_price" class="form-control number">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6  ">
                    <div class="form-group ">
                        <label for="calendar_status"><?php echo __('Status', ST_TEXTDOMAIN); ?></label>
                        <select name="calendar_status" id="calendar_status" class="form-control">
                            <option value="available"><?php echo __('Available', ST_TEXTDOMAIN); ?></option>
                            <option value="unavailable"><?php echo __('Unavailble', ST_TEXTDOMAIN); ?></option>
                        </select>
                    </div>
                </div>
                <?php if(get_post_meta($post_id,'type_tour',true) == 'specific_date') { ?>
                    <div class="col-xs-6">
                        <div class="form-group mt5">
                            <label for="calendar_groupday"><?php echo __('Group day', ST_TEXTDOMAIN); ?></label>
                            <div class="ml20">
                                <input type="checkbox" name="calendar_groupday" id="calendar_groupday" class="i-check" value="1"><span class="ml5"><?php echo __('Group day', ST_TEXTDOMAIN); ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="form-group">
                <div class="form-message">
                    <p></p>
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="calendar_post_id" value="<?php echo esc_attr($post_id); ?>">
                <input type="submit" id="calendar_submit" class="btn btn-primary" name="calendar_submit" value="<?php echo __('Update', ST_TEXTDOMAIN); ?>">
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-12 calendar-wrapper-inner">
        <div class="overlay-form"><i class="fa fa-refresh text-color"></i></div>
        <div class="calendar-content"
             data-hide_adult="<?php echo get_post_meta($post_id,'hide_adult_in_booking_form',true) ?>"
             data-hide_children="<?php echo get_post_meta($post_id,'hide_children_in_booking_form',true) ?>"
             data-hide_infant="<?php echo get_post_meta($post_id,'hide_infant_in_booking_form',true) ?>"
            >
        </div>
    </div>


</div>