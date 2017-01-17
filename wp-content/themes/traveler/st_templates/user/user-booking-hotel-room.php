<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.1.0
 *
 * User hotel booking
 *
 * Created by ShineTheme
 *
 */
$format=TravelHelper::getDateFormat();
?>
<div class="st-create">
    <h2><?php _e("Room Booking" , ST_TEXTDOMAIN) ?></h2>
</div>
    <?php
    $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
    $limit=10;
    $offset=($paged-1)*$limit;
    $data_post=STUser_f::get_history_bookings('hotel_room',$offset,$limit,$data->ID);
    $posts=$data_post['rows'];
    $total=ceil($data_post['total']/$limit);
    ?>

<table class="table table-bordered table-striped table-booking-history">
    <thead>
    <tr>
        <th><?php _e("STT",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Customer",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Check-in/Check-out",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Room Name",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Rooms",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Price",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Created Date",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Status",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Type",ST_TEXTDOMAIN)?></th>
    </tr>
    </thead>
    <tbody id="data_history_book booking-history-title">
    <?php if(!empty($posts)) {
        $i=1 + $offset;
        foreach( $posts as $key => $value ) {
            $post_id = $value->wc_order_id;
            $item_id = $value->st_booking_id;
            ?>
            <tr>
                <td class="text-center"><?php echo esc_attr($i) ?></td>
                <td class="booking-history-type">
                    <?php
                    if ($post_id) {
                        $name = get_post_meta($post_id, 'st_first_name', true);
                        if (!empty($name)) {
                            $name .= " ".get_post_meta($post_id, 'st_last_name', true);
                        }
                        if (!$name) {
                            $name = get_post_meta($post_id, 'st_name', true);
                        }
                        if (!$name) {
                            $name = get_post_meta($post_id, 'st_email', true);
                        }
                        if (!$name) {
                            $name = get_post_meta($post_id, '_billing_first_name', true);
                            $name .= " ".get_post_meta($post_id, '_billing_last_name', true);
                        }
                        echo esc_html( $name);
                    }
                    ?>
                </td>
                <td class="">
                    <?php $date= $value->check_in ;if($date) echo date('d/m/Y',strtotime($date)); ?><br>
                    <i class="fa fa-long-arrow-right"></i><br>
                    <?php $date= $value->check_out ;if($date) echo date('d/m/Y',strtotime($date)); ?>
                </td>
                <td class=""> <?php
                    if ($item_id) {
                        if ($item_id) {
                            echo "<a href='" . get_the_permalink($item_id) . "' target='_blank'>" . get_the_title($item_id) . "</a>";
                        }
                    }
                    ?>
                </td>
                <td class="text-center"><?php echo esc_html($value->room_num_search) ?> </td>
                <td class=""> <?php
                    if($value->type == "normal_booking"){
                        $total_price=get_post_meta($post_id,'total_price',true);
                    }else{
                        $total_price=get_post_meta($post_id,'_order_total',true);
                    }
                    
                    $currency = TravelHelper::_get_currency_book_history($post_id);
                    echo TravelHelper::format_money_raw($total_price, $currency);
                    ?>
                </td>
                <td class=""><?php echo date_i18n($format,strtotime($value->created)) ?></td>
                <td class=""><?php echo esc_html($value->status) ?> </td>
                <td class="">
                    <?php
                    if($value->type == "normal_booking"){
                        _e("Normal booking",ST_TEXTDOMAIN);
                    }else{
                        _e("Woocommerce",ST_TEXTDOMAIN);
                    }
                    ?>
                </td>
            </tr>
    <?php
            $i++;
        }
    }else{
        echo '<h5>'.__("No Room",ST_TEXTDOMAIN).'</h5>';
    }
    ?>
    </tbody>
</table>

<?php st_paging_nav('',null,$total) ?>


