<?php
$room_id = $rs->post_id;

$date  = date_i18n('l d M Y',$rs->check_in);
$room_price = $rs->price;
$price = $room_price * (1 - $rs->discount_rate / 100);

//    $price     = STHotel::get_price($hotel_id);
//    $date  = date_i18n('l d M',$rs->check_in);
$price = TravelHelper::format_money($price);

?>
<div class="text-center text-white">
    <h2 class="text-uc mb20"><?php _e("Last Minute Deal",ST_TEXTDOMAIN) ?></h2>
    <ul class="icon-list list-inline-block mb0 last-minute-rating">
        <?php echo balanceTags(TravelHelper::rate_to_string(STReview::get_avg_rate($room_id))) ?>
    </ul>
    <h5 class="last-minute-title"><?php echo get_the_title($room_id) ?> </h5>
    <p class="last-minute-date"><?php echo esc_html($date) ?></p>
    <p class="mb20">
        <b>
            <?php
                printf(__("from %s / night", ST_TEXTDOMAIN), $price);
            ?>
        </b>
    </p>
    <a class="btn btn-lg btn-white btn-ghost" href="<?php echo get_the_permalink($room_id) ?>">
        <?php _e("Book now",ST_TEXTDOMAIN) ?>
        <i class="fa fa-angle-right"></i>
    </a>
</div>