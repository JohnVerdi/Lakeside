<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Rental payment success item
 *
 * Created by ShineTheme
 *
 */
$order_token_code = STInput::get('order_token_code');

if($order_token_code){
    $order_code = STOrder::get_order_id_by_token($order_token_code);
}

$object_id = $key;

$rental_link = get_permalink($object_id);
$item_price = floatval(get_post_meta($order_code, 'item_price', true));
$check_in = get_post_meta($order_code, 'check_in', true);
$check_out = get_post_meta($order_code, 'check_out', true);
$adult_number = intval(get_post_meta($order_code, 'adult_number', true));
$child_number = intval(get_post_meta($order_code, 'child_number', true));

?>
<tr>
    <td><?php echo esc_html($i+1) ?></td>
    <td >
        <a href="<?php echo esc_url($rental_link)?>" target="_blank">
        <?php echo get_the_post_thumbnail($key,array(360,270,'bfi_thumb'=>true),array('style'=>'max-width:100%;height:auto'))?>
        </a>
    </td>
    <td valign="top">
        <p><a href="<?php echo esc_url($rental_link)?>" target="_blank"> <?php  echo get_the_title($object_id)?></a></p>
        <p><strong><?php st_the_language('booking_price') ?></strong> <?php echo TravelHelper::format_money($item_price); ?></p>
        <p><strong><?php st_the_language('booking_check_in') ?></strong> <?php echo date_i18n(TravelHelper::getDateFormat(), strtotime($check_in)) ?></p>
        <p><strong><?php st_the_language('booking_check_out') ?></strong> <?php echo date_i18n(TravelHelper::getDateFormat(), strtotime($check_out)) ?></p>
		<?php if($adult_number){?>
        <p><strong><?php echo __('Number of Adult: ', ST_TEXTDOMAIN); ?></strong> <?php echo $adult_number; ?></p>
		<?php } ?>
		<?php if($child_number){?>
        <p><strong><?php echo __('Number of Children: ', ST_TEXTDOMAIN); ?></strong> <?php echo $child_number; ?></p>
		<?php } ?>
        <?php 
            $extras = get_post_meta($order_code, 'extras', true); 
            if(isset($extras['value']) && is_array($extras['value']) && count($extras['value'])):
        ?>
		<?php if(!empty($extras)){?>
        <p>
            <strong><?php echo __('Extra:' , ST_TEXTDOMAIN) ;  ?></strong>
        </p>
        <p>    
        <?php        
            foreach($extras['value'] as $name => $number):

                $price_item = floatval($extras['price'][$name]);
                if($price_item <= 0) $price_item = 0;
                $number_item = intval($extras['value'][$name]);
                if($number_item <= 0) $number_item = 0;

				if($number_item > 0){
        ?>
            <span class="pull-right">
                <?php echo $extras['title'][$name].' ('.TravelHelper::format_money($price_item).') x '.$number_item.' '.__('Item(s)', ST_TEXTDOMAIN); ?>
            </span> <br />
            
        <?php } endforeach; ?>

		<?php }?>
        </p>
        <?php endif; ?>
    </td>
</tr>