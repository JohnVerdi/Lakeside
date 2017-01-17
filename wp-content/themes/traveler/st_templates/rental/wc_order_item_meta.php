<?php
/**
 * Created by PhpStorm.
 * User: MSI
 * Date: 14/07/2015
 * Time: 3:17 CH
 */
$item_data=isset($item['item_meta'])?$item['item_meta']:array();
$format=TravelHelper::getDateFormat();
?>
<ul class="wc-order-item-meta-list">
    <?php if(isset($item_data['_st_check_in'])): $data=st_wc_parse_order_item_meta($item_data['_st_check_in']); ?>
        <li>
            <span class="meta-label"><?php _e('Date:',ST_TEXTDOMAIN) ?></span>
            <span class="meta-data"><?php
                echo @date_i18n($format,strtotime($data));
                ?>
                <?php if(isset($item_data['_st_check_out'])){ $data=st_wc_parse_order_item_meta($item_data['_st_check_out']); ?>
                    <i class="fa fa-long-arrow-right"></i>
                    <?php echo @date_i18n($format,strtotime($data));?>
                <?php }?>


            </span>
        </li>
    <?php endif;?>

    <?php if(isset($item_data['_st_adult_number'])):?>
        <li>
            <span class="meta-label"><?php _e('Adult:',ST_TEXTDOMAIN) ?></span>
            <span class="meta-data"><?php echo st_wc_parse_order_item_meta($item_data['_st_adult_number']) ?></span>
        </li>
    <?php endif; ?>    
    <?php if(isset($item_data['_st_child_number'])):?>
        <li>
            <span class="meta-label"><?php _e('Children:',ST_TEXTDOMAIN) ?></span>
            <span class="meta-data"><?php echo st_wc_parse_order_item_meta($item_data['_st_child_number']) ?></span>
        </li>
    <?php endif;?>
    <?php if(isset($item_data['_st_extras']) and ($extra_price = st_wc_parse_order_item_meta($item_data['_st_extra_price']))): $data=st_wc_parse_order_item_meta($item_data['_st_extras']);?>
        <li>
        <p><?php echo __("Extra prices"  ,ST_TEXTDOMAIN) .": "; ?></p>
        <ul>
        <?php 
        $data = unserialize($data) ;  
        
        if(!empty($data['title']) and  is_array($data['title'])){
            foreach ($data['title'] as $key => $title) { ?>
                <?php if($data['value'][$key]){ ?>
                    <li style="padding-left: 10px "> <?php echo esc_attr($title) ;?>: 
                    <?php 
                        echo $data['value'][$key] ;?> x <?php echo TravelHelper::format_money($data['price'][$key]) ;
                    ?> 
                </li>
                <?php }?>                
            <?php }
        }        
        ?>
        </ul>
        </li>
    <?php endif; ?>
    <?php  if(isset($item_data['_st_discount_rate'])): $data=st_wc_parse_order_item_meta($item_data['_st_discount_rate']);?>
        <?php  if (!empty($data)) {?><li><p>
            <?php echo __("Discount"  ,ST_TEXTDOMAIN) .": "; ?>
            <?php echo esc_attr($data) ."%";?>
        <?php } ;?></p></li>
    <?php endif; ?>
    <?php  if(isset($item_data['_line_tax'])): $data=st_wc_parse_order_item_meta($item_data['_line_tax']);?>
            <?php  if (!empty($data)) {?><li><p>
            <?php echo __("Tax"  ,ST_TEXTDOMAIN) .": "; ?>
            <?php echo TravelHelper::format_money($data) ;?>
        <?php } ;?></p></li>
    <?php endif; ?>
    
</ul>