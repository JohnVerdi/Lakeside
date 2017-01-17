<div class="row calendar-wrapper" data-post-id="<?php echo get_the_ID(); ?>">
    <div class="col-xs-12 calendar-wrapper-inner">
        <div class="overlay-form"><i class="fa fa-refresh text-color"></i></div>
        <div class="calendar-content"
             data-hide_adult="<?php echo get_post_meta(get_the_ID(),'hide_adult_in_booking_form',true) ?>"
             data-hide_children="<?php echo get_post_meta(get_the_ID(),'hide_children_in_booking_form',true) ?>"
             data-hide_infant="<?php echo get_post_meta(get_the_ID(),'hide_infant_in_booking_form',true) ?>"
            >
        </div>
    </div>
    <div class="col-xs-12 mt10">
        <div class="calendar-bottom">
            <div class="item ">
                <span class="color available"></span>
                <span> <?php echo __('Available', ST_TEXTDOMAIN) ?></span>
            </div>
            <div class="item cellgrey">
                <span class="color"></span>
                <span>  <?php echo __('Not Available', ST_TEXTDOMAIN) ?></span>
            </div>
            <div class="item ">
                <span class="color bgr-main"></span>
                <span> <?php echo __('Selected', ST_TEXTDOMAIN) ?></span>
            </div>
        </div>
    </div>
</div>