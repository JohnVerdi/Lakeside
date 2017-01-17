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
    <h2><?php _e("Tours Booking" , ST_TEXTDOMAIN) ?></h2>
</div>
    <?php
    $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
    $limit=10;
    $offset=($paged-1)*$limit;
    $data_post=STUser_f::get_history_bookings('st_tours',$offset,$limit,$data->ID);
    $posts=$data_post['rows'];
    $total=ceil($data_post['total']/$limit);
    ?>

<table class="table table-bordered table-striped table-booking-history">
    <thead>
    <tr>
        <th><?php _e("STT",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Customer",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Tour Name",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Type Tour",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Date",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Adult Number",ST_TEXTDOMAIN)?></th>
        <th><?php _e("Child Number",ST_TEXTDOMAIN)?></th>
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
                <td class=""> <?php
                    if ($item_id) {
                        if ($item_id) {
                            echo "<a href='" . get_the_permalink($item_id) . "' target='_blank'>" . get_the_title($item_id) . "</a>";
                        }
                    }
                    ?>
                </td>
                <?php
                if(!empty($value->duration)){
                    $type_tour = 'daily_tour';
                }else{
                    $type_tour = 'specific_date';
                }
                ?>
                <?php if($type_tour == 'specific_date'){ ?>
                    <td class="">
                        <?php _e('Specific Date',ST_TEXTDOMAIN) ?>
                    </td>
                <?php }else{ ?>
                    <td class="">
                        <?php _e('Daily Tour',ST_TEXTDOMAIN) ?>
                    </td>
                <?php } ?>
                <?php 
                    if ($type_tour =='daily_tour'){
                    ?>
                    <td class="">
                        <?php $date= $value->check_in ;if($date) echo date('d/m/Y',strtotime($date)); ?><br>
                        <?php echo __("Duration: " , ST_TEXTDOMAIN);?>
                        <?php echo esc_attr($value->duration);?>
                    </td>
                    <?php
                    }
                    else { ?>
                    <td class="">
                        <?php $date= $value->check_in ;if($date) echo date('d/m/Y',strtotime($date)); ?><br>
                        <i class="fa fa-long-arrow-right"></i><br>
                        <?php $date= $value->check_out ;if($date) echo date('d/m/Y',strtotime($date)); ?>
                    </td>
                    <?php }
                ?>


                <td class=""><?php echo esc_html($value->adult_number) ?></td>
                <td class=""><?php echo esc_html($value->child_number) ?></td>
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
        echo '<h5>'.__('No Tour',ST_TEXTDOMAIN).'</h5>';
    }
    ?>
    </tbody>
</table>

<?php st_paging_nav('',null,$total) ?>


