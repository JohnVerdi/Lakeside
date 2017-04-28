<div class="row calendar-wrapper mb20" data-post-id="<?php echo get_the_ID(); ?>">
    <div class="col-xs-12 calendar-wrapper-inner">
        <div class="overlay-form"><i class="fa fa-refresh text-color"></i></div>
        <div class="calendar-content"></div>
    </div>
    <div class="col-xs-12 mt10">
        <div class="calendar-bottom">
            <div class="item ">
                <span class="color available"></span>
                <span> <?php echo __('Available', ST_TEXTDOMAIN) ?></span>
            </div>
            <div class="item redopacity">
                <span class="color"></span>
                <span>  <?php echo __('Not Available', ST_TEXTDOMAIN) ?></span>
            </div>
            <div class="item still ">
                <span class="color"></span>
                <span>  <?php echo __('Still Available', ST_TEXTDOMAIN) ?></span>
            </div>
            <div class="item ">
                <span class="color bgr-main"></span>
                <span> <?php echo __('Selected', ST_TEXTDOMAIN) ?></span>
            </div>
        </div>
    </div>
</div>