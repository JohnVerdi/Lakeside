<?php global $post; ?>

<div class="row calendar-wrapper" data-post-id="<?php echo $post->ID; ?>">
    <div class="col-xs-12 col-lg-4">
        <div class="calendar-form">
            <div class="form-group">
                <label for="calendar_check_in"><strong><?php echo __('Check In', ST_TEXTDOMAIN); ?></strong></label>
                <input readonly="readonly" type="text" class="widefat option-tree-ui-input date-picker" name="calendar_check_in" id="calendar_check_in">
            </div> 
            <div class="form-group">
                <label for="calendar_check_out"><strong><?php echo __('Check Out', ST_TEXTDOMAIN); ?></strong></label>
                <input readonly="readonly" type="text" class="widefat option-tree-ui-input date-picker" name="calendar_check_out" id="calendar_check_out">
            </div>
            <div class="form-group">
                <label for="calendar_price"><strong><?php echo __('Price ($)', ST_TEXTDOMAIN); ?></strong></label>
                <input type="text" name="calendar_price" id="calendar_price" class="form-control">
            </div>
            <div class="form-group">
                <label for="calendar_status"><?php echo __('Status', ST_TEXTDOMAIN); ?></label>
                <select name="calendar_status" id="calendar_status">
                    <option value="available"><?php echo __('Available', ST_TEXTDOMAIN); ?></option>
                    <option value="unavailable"><?php echo __('Unavailble', ST_TEXTDOMAIN); ?></option>
                </select>
            </div>
            <div class="form-group">
                <div class="form-message">
                    <p></p>
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="calendar_post_id" value="<?php echo $post->ID; ?>">
                <input type="submit" id="calendar_submit" class="button button-primary" name="calendar_submit" value="<?php echo __('Update', ST_TEXTDOMAIN); ?>">
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-lg-8">
        <div class="calendar-content">
            
        </div>
        <div class="overlay">
            <span class="spinner is-active"></span>
        </div>
    </div>
</div>