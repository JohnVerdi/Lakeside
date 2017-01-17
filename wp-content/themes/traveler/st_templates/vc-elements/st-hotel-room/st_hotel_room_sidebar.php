<?php
if(!empty( $_REQUEST['check_in'] ) ){
    $check_in = TravelHelper::convertDateFormat(STInput::request('check_in'));
    $check_out = TravelHelper::convertDateFormat(STInput::request('check_out'));
}elseif(!empty( $_REQUEST['start'] )){
    $check_in = TravelHelper::convertDateFormat(STInput::request('start'));
    $check_out = TravelHelper::convertDateFormat(STInput::request('end'));
}else{
    $check_in = date("Y-m-d");
    $check_out = date("Y-m-d", strtotime("+1 day"));
}

$room_num_search = STInput::request('room_num_search');
if(!isset($room_num_search) || intval($room_num_search) <= 0) $room_num_search = 1;

$room_id = get_the_ID();

$total_price = STPrice::getRoomPriceOnlyCustomPrice($room_id, strtotime($check_in), strtotime($check_out), $room_num_search);
$sale_price = STPrice::getRoomPrice($room_id, strtotime($check_in), strtotime($check_out), $room_num_search);
$default = array(
    'align' => 'right'
);
if (isset($attr)) {
    extract(wp_parse_args($attr, $default));
}else {
    extract($default);
}
?>
<?php
    $room_id = get_the_ID();
    $item_id = get_post_meta(get_the_ID(), 'room_parent', true);
    if(empty($item_id)){
        $item_id = $room_id;
    }
    $booking_period = intval(get_post_meta($item_id, 'hotel_booking_period', TRUE));
    $numberday = TravelHelper::dateDiff($check_in, $check_out);
    $extra_price = get_post_meta(get_the_ID(), 'extra_price', true);
    $external = STRoom::get_external_url();
?>
<div class="hotel-room-form">
    <!-- <div class="overlay-form"><i class="fa fa-refresh text-color"></i></div> -->
    <div class="price bgr-main clearfix">
        <div class="pull-left">
            <?php if($sale_price < $total_price): ?>
                <span class="text-sm  onsale mr10 text-white">
                    <?php echo TravelHelper::format_money($total_price)?>
                </span>
            <?php endif; ?>
            <span class="text-lg"><?php echo TravelHelper::format_money($sale_price) ?></span>
        </div>
        <div class="pull-right">
            <?php printf(__('per %d Night(s)', ST_TEXTDOMAIN), $numberday); ?>
        </div>
    </div>
    <?php echo STTemplate::message()?>
    <form id="form-booking-inpage" class="single-room-form" method="post">
    <div class="search_room_alert "></div>
		<div class="message_box mb10"></div>
		<?php wp_nonce_field('room_search','room_search')?>
        <?php if ($external): ?>
            <!-- showing nothing with external booking . -->
        <?php else :?>
        <div class="input-daterange" data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" data-booking-period="<?php echo $booking_period; ?>">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group form-group-icon-left">
                        <label for="field-hotelroom-checkin"><?php st_the_language('check_in')?></label>
                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                        <input
                            id="field-hotelroom-checkin"
                            data-post-id="<?php echo get_the_ID(); ?>"
                            placeholder="<?php echo TravelHelper::getDateFormatJs(__("Select date", ST_TEXTDOMAIN)); ?>"
                            class="form-control checkin_hotel"
                            value="<?php if( !empty( $check_in ) ) echo date(TravelHelper::getDateFormat(),strtotime($check_in)); ?>"
                            name="check_in"
                            type="text">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group form-group-icon-left">
                        <label for="field-hotelroom-checkout"><?php st_the_language('check_out')?></label>
                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                        <input
                            id="field-hotelroom-checkout"
                            data-post-id="<?php echo get_the_ID(); ?>"
                            placeholder="<?php echo TravelHelper::getDateFormatJs(__("Select date", ST_TEXTDOMAIN)); ?>"
                            class="form-control checkout_hotel"
                            value="<?php if( !empty( $check_out ) ) echo date(TravelHelper::getDateFormat(),strtotime($check_out)); ?>"
                            name="check_out"
                            type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <div class="form-group form-group-select-plus">
                    <label for="field-hotelroom-room"><?php st_the_language('rooms')?></label>
                    <?php $num_room = intval(get_post_meta($room_id, 'number_room', true));
                    ?>
                    <select id="field-hotelroom-room" name="room_num_search" class="form-control room_num_search">
                        <?php

                        if(!$num_room || $num_room < 0)
                            $num_room = 9;
                        for($i = 1; $i <= $num_room; $i ++):?>
                            <option <?php selected( $i , $room_num_search,1); ?> value='<?php echo $i; ?>'><?php echo $i; ?></option>
                        <?php endfor;?>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="form-group form-group-select-plus">
                    <label for="field-hotelroom-adult"><?php st_the_language('adults')?></label>
                    <select id="field-hotelroom-adult" name="adult_number" class="form-control adult_number">
                        <?php
                        $max = intval(get_post_meta($room_id, 'adult_number', true));
                        for($i = 1; $i <= $max; $i++):
                            $select = selected( $i , STInput::request('adult_number',1)); ?>
                        <option <?php echo $select; ?> value='<?php echo $i;?>'><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="form-group form-group-select-plus">
                    <label for="field-hotelroom-children"><?php st_the_language('children')?></label>

                    <select id="field-hotelroom-children" name="child_number" class="form-control child_number">
                        <?php

                        $max = intval(get_post_meta($room_id, 'children_number', true));
                        for($i = 0; $i <= $max; $i++):
                            $select = selected($i,STInput::request('child_number',0));?>
                        <option <?php echo $select; ?> value='<?php echo $i; ?>'><?php echo $i; ?></option>
                        <?php endfor;?>
                    </select>
                </div>
            </div>
        </div>
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
        <?php endif; // end external  ?>
        <div class="text-right">
        <?php
            $st_is_woocommerce_checkout = apply_filters('st_is_woocommerce_checkout',false);
            $st_is_booking_modal = apply_filters('st_is_booking_modal',false);
            if($external) { ?>
                <a class=" btn btn-primary btn_hotel_booking" href="<?php echo esc_url($external); ?>"><?php echo __('Book Now', ST_TEXTDOMAIN); ?></a>
            <?php }else {
                if($st_is_booking_modal && !$st_is_woocommerce_checkout){
            ?>
            <a class=" btn btn-primary btn-st-add-cart" data-effect="mfp-zoom-out" onclick="return false" data-target="#hotel_booking_<?php echo get_the_ID(); ?>" type="submit"><?php echo __('Book Now', ST_TEXTDOMAIN); ?> <i class="fa fa-spinner fa-spin"></i></a>
            <?php  }else{ ?>
            <input class=" btn btn-primary btn_hotel_booking" value="<?php echo __('Book Now', ST_TEXTDOMAIN); ?>" type="submit">
            <?php };
            }
             ?>
        </div>
        <input name="action" value="hotel_add_to_cart" type="hidden">
        <input name="item_id" value="<?php echo $item_id; ?>" type="hidden">
        <input name="room_id" value="<?php echo $room_id; ?>" type="hidden">
    </form>
</div>
<?php if(st()->get_option('booking_modal','off')=='on'): ?>
<div class="mfp-with-anim mfp-dialog mfp-search-dialog mfp-hide" id="hotel_booking_<?php the_ID()?>">
    <?php echo st()->load_template('hotel/modal_booking');?>
</div>
<?php endif; ?>
