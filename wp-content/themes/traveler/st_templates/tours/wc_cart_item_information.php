<?php 
$format=TravelHelper::getDateFormat();
$div_id = "st_cart_item".md5(json_encode($st_booking_data['cart_item_key']));
$data = $st_booking_data;
?>
<?php if(isset($data['type_tour'])): ?>
<p class="booking-item-description">
    <span><?php echo __('Type tour', ST_TEXTDOMAIN); ?>: </span>
    <?php
    if($data['type_tour'] == 'daily_tour'){
        echo __('Daily Tour', ST_TEXTDOMAIN);
    }elseif($data['type_tour'] == 'specific_date'){
        echo __('Special Date', ST_TEXTDOMAIN);
    }
    ?>
</p>
<?php endif; ?>

<?php if(isset($data['type_tour']) && $data['type_tour'] == 'daily_tour'): ?>
<p class="booking-item-description"><span><?php echo __('Departure date', ST_TEXTDOMAIN); ?>: </span><?php echo $data['check_in']; ?></p>
<p class="booking-item-description"><span><?php echo __('Duration', ST_TEXTDOMAIN); ?>: </span><?php echo $data['duration']; ?></p>
<?php endif; ?>

<?php if(isset($data['type_tour']) && $data['type_tour'] == 'specific_date'): ?>
<p class="booking-item-description"><span><?php echo __('Departure date', ST_TEXTDOMAIN); ?>: </span><?php echo $data['check_in']; ?></p>
<p class="booking-item-description"><span><?php echo __('Arrive date', ST_TEXTDOMAIN); ?>: </span><?php echo $data['check_out']; ?></p>
<?php endif; ?>

<div id="<?php echo esc_attr($div_id);?>" class='<?php if (apply_filters('st_woo_cart_is_collapse' , false)) {echo esc_attr("collapse");}?>'>
	<p><small><?php echo __("Booking Details" , ST_TEXTDOMAIN) ; ?></small> </p>
	<div class='cart_border_bottom'></div>
	<div class="cart_item_group" style='margin-bottom: 10px'>
        <div class="booking-item-description">   
            <?php 
                $data_price = $st_booking_data['data_price'];                
                $adult_price = ( (float) $data_price['adult_price'] > 0 ) ? (float) $data_price['adult_price'] : 0;
                $child_price = ( (float) $data_price['child_price'] > 0 ) ? (float) $data_price['child_price'] : 0;
                $infant_price = ( (float) $data_price['infant_price'] > 0 ) ? (float) $data_price['infant_price'] : 0;
             ?>        	
			<p class="booking-item-description"> 
			     <?php if (!empty($data['adult_number'])) :?>
                     <span><?php echo __('Adult', ST_TEXTDOMAIN); ?>: </span><?php echo $data['adult_number']; ?> 
                    x 
                    <?php                      
                        echo TravelHelper::format_money($adult_price/$data['adult_number']);
                        echo ' <i class="fa fa-long-arrow-right"></i> ';
                        echo TravelHelper::format_money($adult_price);
                    ?>
                    <br>
                <?php endif ; ?>
                <?php if (!empty($data['child_number'])) :?>
                    <span><?php echo __('Child', ST_TEXTDOMAIN); ?>: </span><?php echo $data['child_number']; ?>
                    x 
                    <?php                        
                        echo TravelHelper::format_money($child_price/$data['child_number']);
                        echo ' <i class="fa fa-long-arrow-right"></i> ';
                        echo TravelHelper::format_money($child_price);
                    ?>
                    <br>
                <?php endif ; ?>
                <?php if (!empty($data['infant_number'])) :?>
                    <span><?php echo __('Infant', ST_TEXTDOMAIN); ?>: </span><?php echo $data['infant_number']; ?>
                    x 
                    <?php                     
                        echo TravelHelper::format_money( $infant_price / $data['infant_number']);
                        echo ' <i class="fa fa-long-arrow-right"></i> ';
                        echo TravelHelper::format_money($infant_price);
                    ?>
                    <br>
                <?php endif ; ?>
			</p>
        </div>
    </div>
    <div class="cart_item_group" style='margin-bottom: 10px'>
        <?php 
            $discount = $st_booking_data['discount_rate'];
            if (!empty($discount)){ ?>
                <b class='booking-cart-item-title'><?php echo __( "Discount", ST_TEXTDOMAIN); ?>: </b>
                <?php echo esc_attr($discount)."%" ?>
            <?php }            
        ?>        
    </div> 
	 <div class="cart_item_group" style='margin-bottom: 10px'>        
        <?php  if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) {
            $wp_cart = WC()->cart->cart_contents; 
            $item = $wp_cart[$st_booking_data['cart_item_key']];
            $tax = $item['line_tax']; 
            if (!empty($tax)) { ?>
                <b class='booking-cart-item-title'><?php echo __( "Tax", ST_TEXTDOMAIN); ?>: </b>
                <?php echo TravelHelper::format_money($tax);?>
            <?php }
        }else{$tax = 0;}
        ?>
    </div>
    <div class="cart_item_group" style='margin-bottom: 10px'>
        <?php

        if(!empty($st_booking_data['extras']) and $st_booking_data['extra_price']):
            $extras = $st_booking_data['extras'];
            if(isset($extras['title']) && is_array($extras['title']) && count($extras['title'])): ?>
                <b class='booking-cart-item-title'><?php _e("Extra prices",ST_TEXTDOMAIN) ?></b>
                <div class="booking-item-payment-price-amount">
                    <?php foreach($extras['title'] as $key => $title):
                        $price_item = floatval($extras['price'][$key]);
                        if($price_item <= 0) $price_item = 0;
                        $number_item = intval($extras['value'][$key]);
                        if($number_item <= 0) $number_item = 0;
                        ?><?php if($number_item){ ?>
                        <span style="padding-left: 10px ">
                            <?php echo esc_attr($title).": ".esc_attr($number_item).' x '.TravelHelper::format_money($price_item); ?>
                        </span> <br />
                    <?php };?>
                    <?php endforeach;?>
                </div>
            <?php  endif; ?>
        <?php endif; ?>
    </div>
    <div class='cart_border_bottom'></div>
    <div class="cart_item_group" style='margin-bottom: 10px'>        
        <b class='booking-cart-item-title'><?php echo __("Total amount" , ST_TEXTDOMAIN) ;  ?>:</b>  
        <?php echo TravelHelper::format_money($st_booking_data['ori_price'] + $tax )?>
    </div>   
</div>