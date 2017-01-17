<?php
$post_id = $rs->post_id;
$date  = date_i18n('l d M Y',strtotime(get_post_meta($post_id,'sale_price_from' ,true)))." - ".date_i18n('l d M Y',strtotime(get_post_meta($post_id ,'sale_price_to' ,true)));
$price     = get_post_meta( $post_id , 'price' , true );
$discount         = get_post_meta( $post_id , 'discount_rate' , true );
if($discount) {
    if($discount > 100)
        $discount = 100;
    $price = $price - ( $price / 100 ) * $discount;
}
$price = TravelHelper::format_money($price);
?>
<div class="text-center text-white">
    <h2 class="text-uc mb20"><?php _e("Last Minute Deal",ST_TEXTDOMAIN) ?></h2>
    <ul class="icon-list list-inline-block mb0 last-minute-rating">
        <?php echo balanceTags(TravelHelper::rate_to_string(STReview::get_avg_rate($post_id))) ?>
    </ul>
    <h5 class="last-minute-title"><?php echo get_the_title($post_id) ?></h5>
    <p class="last-minute-date"><?php echo esc_html($date) ?></p>
    <p class="mb20">
		<b><?php printf(__('From %s',ST_TEXTDOMAIN),$price) ?></b>
    </p>
    <a class="btn btn-lg btn-white btn-ghost" href="<?php echo get_the_permalink($post_id) ?>">
        <?php _e("Book now",ST_TEXTDOMAIN) ?>
        <i class="fa fa-angle-right"></i>
    </a>
</div>