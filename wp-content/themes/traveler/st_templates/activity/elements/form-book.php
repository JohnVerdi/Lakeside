<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Activity element form book
 *
 * Created by ShineTheme
 *
 */

//check is booking with modal
$st_is_booking_modal = apply_filters( 'st_is_booking_modal' , false );
$type_activity       = get_post_meta( get_the_ID() , 'type_activity' , true );
$max_people = intval(get_post_meta( get_the_ID(), 'max_people', true));
?>
<?php echo STTemplate::message(); ?>

<div class="info-activity">
    <div class="info">
        <span class="head"><i class="fa fa-info"></i> <?php echo __('Activity type', ST_TEXTDOMAIN) ?> : </span>
        <span><?php if($type_activity == 'daily_activity') echo __('Daily Activity', ST_TEXTDOMAIN); else echo __('Specific Date', ST_TEXTDOMAIN) ?></span>
    </div>
<?php
    $activity_time = get_post_meta( get_the_ID() , 'activity-time' , true );
    if(!empty( $activity_time )):
?>
        <div class="info">
            <span class="head"><i class="fa fa-clock-o"></i> <?php st_the_language( 'activity_time' ) ?> : </span>
            <span><?php echo esc_html( $activity_time ); ?> </span>
        </div>
<?php endif; ?>
<?php
    $duration = get_post_meta( get_the_ID() , 'duration' , true );
    if(!empty( $duration )):
?>
        <div class="info">
            <span class="head"><i class="fa fa-clock-o"></i> <?php _e('Duration',ST_TEXTDOMAIN) ?> : </span>
            <span><?php echo esc_html( $duration ); ?> </span>
        </div>
<?php endif; ?>
    <div class="info">
        <span class="head"><i class="fa fa-users"></i> <?php echo __('Maximum number of people', ST_TEXTDOMAIN); ?> : </span>
        <span><?php 
            if( !$max_people || $max_people == 0 ){
                $max_people = __('Unlimited', ST_TEXTDOMAIN);
            }
            echo $max_people; 
        ?></span>
    </div>
<?php
    $facilities = get_post_meta( get_the_ID() , 'venue-facilities' , true );
    if(!empty( $facilities )):
?>
        <div class="info">
            <span class="head"><i class="fa fa-cogs"></i> <?php st_the_language( 'venue_facilities' ) ?> : </span>
            <span><?php echo esc_html( $facilities ); ?> </span>
        </div>
<?php endif; ?>
</div>
<?php
    $activity_show_calendar = st()->get_option('activity_show_calendar', 'on');
    $activity_show_calendar_below = st()->get_option('activity_show_calendar_below', 'off');
    if($activity_show_calendar == 'on' && $activity_show_calendar_below == 'off'):
?>
<div class='activity_show_caledar_below_off'>
<?php echo st()->load_template('activity/elements/activity_calendar'); ?>
</div>
<?php endif; ?>
<div class="package-info-wrapper">
    <div class="overlay-form"><i class="fa fa-refresh text-color"></i></div>
    <form id="form-booking-inpage" method="post" action="" class="activity_booking_form booking_modal_form" data-activity-type="<?php echo esc_attr($type_activity); ?>">
        <div class="message_box"></div>
        <?php
        if(!get_option( 'permalink_structure' )) {
            echo '<input type="hidden" name="st_activity"  value="' . st_get_the_slug() . '">';
        }
        ?>
        <input type="hidden" name="action" value="activity_add_to_cart">
        <input type="hidden" name="item_id" value="<?php echo get_the_ID() ?>">
        <input name="type_activity" type="hidden" value="<?php echo $type_activity; ?>">
        <div class="book_form">
            <?php $check_in = STInput::request('check_in', ''); ?>
            <?php $check_out = STInput::request('check_out', ''); ?>
            <?php
                if($activity_show_calendar == 'on'):
            ?>
                <div class="row ">
                    <div class="col-xs-12 ">
                        <strong><?php _e('Departure date',ST_TEXTDOMAIN)?>: </strong>

                        <input data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" readonly="readonly" placeholder ="<?php echo __("Select a day in the calendar", ST_TEXTDOMAIN) ; ?>" id="check_in" type="text" name="check_in" value="<?php echo $check_in; ?>" class="form-control">
                    </div>
                    <div class="col-xs-12 mt10" style="display: none;">
                        <strong><?php _e('Arrive date',ST_TEXTDOMAIN)?>: </strong>

                        <input data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" readonly="readonly" id="check_out" type="text" name="check_out" value="<?php echo $check_out; ?>" readonly="readonly" class="form-control">
                    </div>
                </div>
            <?php else: ?>
            <div class="row">
                    <div class="col-xs-12 mb5">
                        <a href="#list_activity_item" id="select-a-activity" class="btn btn-primary"><?php echo __('Select an activity', ST_TEXTDOMAIN); ?></a>
                    </div>
                    <div class="col-xs-12 mb5" style="display: none">
                        <strong><?php _e('Departure date',ST_TEXTDOMAIN)?>: </strong>
                        <input readonly="readonly" placeholder ="<?php echo __("Select a day in the calendar", ST_TEXTDOMAIN) ; ?>" id="check_in" type="text" name="check_in" value="<?php echo $check_in; ?>" class="form-control">
                    </div>
                    <div class="col-xs-12 mb5" style="display: none">
                        <strong><?php _e('Arrive date',ST_TEXTDOMAIN)?>: </strong>
                        <input readonly="readonly" id="check_out" type="text" name="check_out" value="<?php echo $check_out; ?>" class="form-control">
                    </div>
                </div>
                <div id="list_activity_item" data-type-tour="" style="display: none; width: 500px; height: auto;">
                    <div id="single-tour-calendar">
                        <?php echo st()->load_template('activity/elements/activity_calendar'); ?>
                        <style>
                            .qtip{
                                max-width: 250px !important;
                            }
                        </style>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row line_ald">
                <?php if(get_post_meta(get_the_ID(),'hide_adult_in_booking_form',true) != 'on'): ?>
                <div class="col-md-4">
                    <span><?php _e( 'Adults' , ST_TEXTDOMAIN ) ?>: </span>
                    <select class="form-control st_tour_adult" name="adult_number" required>
                        <?php
                        $adult_number = intval(STInput::request('adult_number', 1));
                        for( $i = 0 ; $i <= 20 ; $i++ ) {
                            echo "<option ".selected($adult_number, $i)." value='{$i}'>{$i}</option>";
                        } ?>
                    </select>
                </div>
                <?php endif ?>
                <?php if(get_post_meta(get_the_ID(),'hide_children_in_booking_form',true) != 'on'): ?>
                <div class="col-md-4">
                    <span><?php _e( 'Children' , ST_TEXTDOMAIN ) ?>: </span>
                    <select class="form-control st_tour_children" name="child_number" required>
                        <?php
                        $child_number = intval(STInput::request('child_number', 0));
                        for( $i = 0 ; $i <= 20 ; $i++ ) {
                            echo "<option ".selected($child_number, $i)." value='{$i}'>{$i}</option>";
                        } ?>
                    </select>
                </div>
                <?php endif ?>
                <?php if(get_post_meta(get_the_ID(),'hide_infant_in_booking_form',true) != 'on'): ?>
                <div class="col-md-4">
                    <span><?php _e( 'Infant' , ST_TEXTDOMAIN ) ?>: </span>
                    <select class="form-control st_tour_infant" name="infant_number" required>
                        <?php
                        $infant_number = intval(STInput::request('infant_number', 0));
                        for( $i = 0 ; $i <= 20 ; $i++ ) {
                            echo "<option ".selected($infant_number, $i)." value='{$i}'>{$i}</option>";
                        } ?>
                    </select>
                </div>
                <?php endif ?>
            </div>
            <div class="row line_ald">
                <div class="col-md-12">
                    <?php  $extra_price = get_post_meta(get_the_ID(), 'extra_price', true); ?>
                    <?php if(is_array($extra_price) && count($extra_price)): ?>
                        <?php $extra = STInput::request("extra_price");
                        if(!empty($extra['value'])){
                            $extra_value = $extra['value'];
                        }
                        ?>
                        <label><?php echo __('Extra', ST_TEXTDOMAIN); ?></label>
                        <table class="table">
                            <?php foreach($extra_price as $key => $val): ?>
                                <tr>
                                    <td width="80%">
                                        <label for="field-<?php echo $val['extra_name']; ?>" class="ml20 mt5"><?php echo $val['title'].' ('.TravelHelper::format_money($val['extra_price']).')'; ?></label>
                                        <input type="hidden" name="extra_price[price][<?php echo $val['extra_name']; ?>]" value="<?php echo $val['extra_price']; ?>">
                                        <input type="hidden" name="extra_price[title][<?php echo $val['extra_name']; ?>]" value="<?php echo $val['title']; ?>">
                                    </td>
                                    <td width="20%">
                                        <select  style="width: 100px" class="form-control app" name="extra_price[value][<?php echo $val['extra_name']; ?>]" id="field-<?php echo $val['extra_name']; ?>">
                                            <?php
                                            $max_item = intval($val['extra_max_number']);
                                            if($max_item <= 0) $max_item = 1;
                                            for($i = 0; $i <= $max_item; $i++):
                                                $check = "";
                                                if(!empty($extra_value[$val['extra_name']]) and $i == $extra_value[$val['extra_name']]){
                                                    $check = "selected";
                                                }
                                                ?>
                                                <option <?php echo esc_html($check) ?>  value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            <br>
            <?php if($st_is_booking_modal) { ?>
                <a data-target="#activity_booking_<?php the_ID() ?>" onclick="return false" class="btn btn-primary btn-st-add-cart"
                   ><?php st_the_language( 'book_now' ) ?> <i class="fa fa-spinner fa-spin"></i></a>
            <?php } else { ?>

                <?php echo STActivity::activity_external_booking_submit(); ?>

            <?php } ?>
            <?php
            $best_price = get_post_meta( get_the_ID() , 'best-price-guarantee' , true );
            if($best_price == 'on') {
                ?>
                <div class="btn btn-ghost btn-info tooltip_2 tooltip_2-effect-1 activity" style="font-size: 16px">
                <span class="">
                    <?php st_the_language( 'best_price_guarantee' ) ?>
                    <i class="fa fa-check-square-o fa-lg primary"></i>
                </span>
                <span class="tooltip_2-content clearfix title">
                    <?php echo get_post_meta( get_the_ID() , 'best-price-guarantee-text' , true ) ?>
                </span>
                </div>
            <?php } ?>
        </div>

    </form>
</div>
<?php
    if($activity_show_calendar == 'on' && $activity_show_calendar_below == 'on'):
?>
<div class='activity_show_caledar_below_on'>
<?php echo st()->load_template('activity/elements/activity_calendar'); ?>
</div>
<?php endif; ?>
<?php
if($st_is_booking_modal) {
    ?>
    <div class="mfp-with-anim mfp-dialog mfp-search-dialog mfp-hide" id="activity_booking_<?php the_ID() ?>">
        <?php echo st()->load_template( 'activity/modal_booking' ); ?>
    </div>
<?php } ?>