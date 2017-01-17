<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Admin cars booking edit
 *
 * Created by ShineTheme
 *
 */

$item_id = isset($_GET['order_item_id'])?$_GET['order_item_id']:false;

$order_item_id= get_post_meta($item_id,'item_id',true);

$order=$item_id;

if(!isset($page_title))
{
    $page_title=__('Edit Car Booking',ST_TEXTDOMAIN);
}
$currency = get_post_meta($item_id, 'currency', true);
//$rate = floatval(get_post_meta($item_id,'currency_rate', true));
?>
<div class="wrap">
    <?php echo '<h2>'.$page_title.'</h2>';?>
    <?php STAdmin::message() ?>
    <div id="post-body" class="columns-2">
        <div id="post-body-content">
            <div class="postbox-container">
                <form method="post" action="" id="form-booking-admin" class="main-search">
                    <?php wp_nonce_field('shb_action','shb_field') ?>
                    <div id="poststuff">
                        <div class="postbox">
                            <div class="handlediv" title="<?php _e('Click to toggle',ST_TEXTDOMAIN)?>"><br></div>
                            <h3 class="hndle ui-sortable-handle"><span><?php _e('Order Information',ST_TEXTDOMAIN)?></span></h3>
                            <div class="inside">
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Customer ID',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                        <?php
                                        $id_user='';
                                        $pl_name='';
                                        if($item_id){
                                            $id_user = get_post_meta($item_id,'id_user',true);

                                            if($id_user){
                                                $user = get_userdata($id_user);
                                                if($user){
                                                    $pl_name = $user->ID.' - '.$user->user_email;
                                                }
                                            }
                                        }else{
                                            $id_user = get_current_user_id();
                                            if($id_user){
                                                $user = get_userdata($id_user);
                                                if($user){
                                                    $pl_name = $user->ID.' - '.$user->user_email;
                                                }
                                            }
                                        }
                                        ?>
                                        <input readonly type="text" name="id_user" value="<?php echo esc_attr($pl_name); ?>" class="form-control form-control-admin">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Customer First Name',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                    <?php 
                                        $st_first_name = isset($_POST['st_first_name']) ? $_POST['st_first_name'] : get_post_meta($item_id,'st_first_name',true);
                                    ?>
                                        <input type="text" name="st_first_name" value="<?php echo $st_first_name; ?>" class="form-control form-control-admin">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Customer Last Name',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                    <?php 
                                        $st_last_name = isset($_POST['st_last_name']) ? $_POST['st_last_name'] : get_post_meta($item_id,'st_last_name',true);
                                    ?>
                                        <input type="text" name="st_last_name" value="<?php echo $st_last_name; ?>" class="form-control form-control-admin">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Customer Email',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                    <?php 
                                        $st_email = isset($_POST['st_email']) ? $_POST['st_email'] : get_post_meta($item_id,'st_email',true);
                                    ?>
                                        <input type="text" name="st_email" value="<?php echo $st_email; ?>" class="form-control form-control-admin">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Customer Phone',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                    <?php 
                                        $st_phone = isset($_POST['st_phone']) ? $_POST['st_phone'] : get_post_meta($item_id,'st_phone',true);
                                    ?>
                                        <input type="text" name="st_phone" value="<?php echo $st_phone; ?>" class="form-control form-control-admin">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Customer Address line 1',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                    <?php 
                                        $st_address = isset($_POST['st_address']) ? $_POST['st_address'] : get_post_meta($item_id,'st_address',true);
                                    ?>
                                        <input type="text" name="st_address" value="<?php echo $st_address; ?>" class="form-control form-control-admin">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Customer Address line 2',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                    <?php 
                                        $st_address2 = isset($_POST['st_address2']) ? $_POST['st_address2'] : get_post_meta($item_id,'st_address2',true);
                                    ?>
                                        <input type="text" name="st_address2" value="<?php echo $st_address2; ?>" class="form-control form-control-admin">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Customer City',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                    <?php 
                                        $st_city = isset($_POST['st_city']) ? $_POST['st_city'] : get_post_meta($item_id,'st_city',true);
                                    ?>
                                        <input type="text" name="st_city" value="<?php echo $st_city; ?>" class="form-control form-control-admin">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('State/Province/Region',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                    <?php 
                                        $st_province = isset($_POST['st_province']) ? $_POST['st_province'] : get_post_meta($item_id,'st_province',true);
                                    ?>
                                        <input type="text" name="st_province" value="<?php echo $st_province; ?>" class="form-control form-control-admin">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('ZIP code/Postal code',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                    <?php 
                                        $st_zip_code = isset($_POST['st_zip_code']) ? $_POST['st_zip_code'] : get_post_meta($item_id,'st_zip_code',true);
                                    ?>
                                        <input type="text" name="st_zip_code" value="<?php echo $st_zip_code; ?>" class="form-control form-control-admin">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Country',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                    <?php 
                                        $st_country = isset($_POST['st_country']) ? $_POST['st_country'] : get_post_meta($item_id,'st_country',true);
                                    ?>
                                        <input type="text" name="st_country" value="<?php echo $st_country; ?>" class="form-control form-control-admin">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Car',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                    <?php 
                                        $tour_id = isset($_POST['item_id']) ? $_POST['item_id'] : get_post_meta($item_id,'item_id',true);
                                    ?>
                                    <input type="hidden" name="item_id" value="<?php echo $tour_id; ?>" data-post-type="st_cars" class="form-control form-control-admin st_post_select_ajax " data-pl-name="<?php echo get_the_title($tour_id) ?>">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Price',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                        <div id="item-price-wrapper">
                                            <?php 
                                                $price = '';
                                                $car_id = isset($_POST['item_id']) ? $_POST['item_id'] : $order_item_id;
                                                if(intval($car_id) > 0 && get_post_type($car_id) == 'st_cars'){
                                                    $price = floatval(get_post_meta($car_id, 'cars_price', true));
                                                    $price = TravelHelper::format_money($price) .' / '.STAdminCars::get_price_unit();
                                                    echo $price;
                                                }
                                            ?>
                                        </div>
                                        <span class="spinner"></span>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Pick Up',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                    <?php
                                        $pick_up = isset($_POST['pick_up']) ? $_POST['pick_up'] : get_post_meta($item_id,'location_id_pick_up',true);
                                    ?>
                                    <input type="hidden" name="pick_up" value="<?php echo $pick_up; ?>" data-post-type="location" class="form-control form-control-admin st_post_select_ajax " data-pl-name="<?php echo get_the_title($pick_up); ?>">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Drop Off',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                    <?php
                                        $drop_off = isset($_POST['drop_off']) ? $_POST['drop_off'] : get_post_meta($item_id,'location_id_drop_off',true);
                                    ?>
                                    <input type="hidden" name="drop_off" value="<?php echo $drop_off; ?>" data-post-type="location" class="form-control form-control-admin st_post_select_ajax " data-pl-name="<?php echo get_the_title($drop_off) ?>">
                                    </div>
                                </div>





                                <!--<div class="form-row">
                                    <label class="form-label" for="check_in"><?php /*_e('Location: ',ST_TEXTDOMAIN)*/?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                        <div class="">
                                            <?php
/*                                            $st_google_location_pickup = STInput::request('st_google_location_pickup', get_post_meta($item_id,'pick_up',true));
                                            $st_locality_up = STInput::request('st_locality_up', '');
                                            $st_sublocality_level_1_up = STInput::request('st_sub_up', '');
                                            $st_administrative_area_level_1_up = STInput::request('st_admin_area_up', '');
                                            $st_country_up = STInput::request('st_country_up', '');

                                            $st_google_location_dropoff = STInput::request('st_google_location_dropoff', get_post_meta($item_id,'drop_off',true));
                                            $st_locality_off = STInput::request('st_locality_off', '');
                                            $st_sublocality_level_1_off = STInput::request('st_sub_off', '');
                                            $st_administrative_area_level_1_off = STInput::request('st_admin_area_off', '');
                                            $st_country_off = STInput::request('st_country_off', '');

                                            */?>
                                            <div class="form-group form-group-icon-left">
                                                <label for="field-st-address"><?php /*_e('Pick Up', ST_TEXTDOMAIN); */?></label>
                                                <i class="fa fa-map-marker input-icon"></i>
                                                <div class="st-google-location-wrapper pickup">
                                                    <input id="st_google_location_pickup" autocomplete="off" type="text" class="st_google_location form-control" name="st_google_location_pickup" value="<?php /*echo esc_attr($st_google_location_pickup); */?>">
                                                    <input type="hidden" name="st_locality_up" value="<?php /*echo esc_attr($st_locality_up); */?>">
                                                    <input type="hidden" name="st_sub_up" value="<?php /*echo esc_attr($st_sublocality_level_1_up); */?>">
                                                    <input type="hidden" name="st_admin_area_up" value="<?php /*echo esc_attr($st_administrative_area_level_1_up); */?>">
                                                    <input type="hidden" name="st_country_up" value="<?php /*echo esc_attr($st_country_up); */?>">
                                                </div>
                                            </div>

                                            <br>
                                            <div class="form-group form-group-icon-left">
                                                <label for="field-st-address"><?php /*_e('Drop Off', ST_TEXTDOMAIN); */?></label>
                                                <i class="fa fa-map-marker input-icon"></i>
                                                <div class="st-google-location-wrapper dropoff">
                                                    <input id="st_google_location_dropoff" autocomplete="off" type="text" class="st_google_location form-control" name="st_google_location_dropoff" value="<?php /*echo esc_attr($st_google_location_dropoff); */?>">
                                                    <input type="hidden" name="st_locality_off" value="<?php /*echo esc_attr($st_locality_off); */?>">
                                                    <input type="hidden" name="st_sub_off" value="<?php /*echo esc_attr($st_sublocality_level_1_off); */?>">
                                                    <input type="hidden" name="st_admin_area_off" value="<?php /*echo esc_attr($st_administrative_area_level_1_off); */?>">
                                                    <input type="hidden" name="st_country_off" value="<?php /*echo esc_attr($st_country_off); */?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->

                                <!--<div class="form-row">
                                    <label class="form-label" for="check_in"><?php /*_e('Drop Off',ST_TEXTDOMAIN)*/?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                        <input id="st_google_location_dropoff" autocomplete="off" type="text" class="st_google_location form-control" name="st_google_location_dropoff" value="<?php /*echo esc_attr($st_google_location_dropoff); */?>">
                                        <input type="hidden" name="st_locality_off" value="<?php /*echo esc_attr($st_locality_off); */?>">
                                        <input type="hidden" name="st_sub_off" value="<?php /*echo esc_attr($st_sublocality_level_1_off); */?>">
                                        <input type="hidden" name="st_admin_area_off" value="<?php /*echo esc_attr($st_administrative_area_level_1_off); */?>">
                                        <input type="hidden" name="st_country_off" value="<?php /*echo esc_attr($st_country_off); */?>">
                                    </div>
                                </div>-->

                                <div class="form-row">
                                    <label class="form-label" for="check_in"><?php _e('Check in',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                        <div id="check-in-wrapper">
                                            
                                        </div>
                                        <?php 
                                            $check_in = isset($_POST['check_in']) ? $_POST['check_in'] : get_post_meta($item_id,'check_in',true);
                                            
                                            if(!empty($check_in)){
                                                $check_in = date('m/d/Y',strtotime($check_in));
                                            }else{
                                                $check_in = '';
                                            }
                                        ?>
                                        <input readonly id="check_in" placeholder="mm/dd/yyyy" type="text" name="check_in" value="<?php echo $check_in; ?>" class="form-control form-control-admin st_datepicker">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for="check_in_time"><?php _e('Check in time',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                        <div id="check-in-wrapper">
                                            
                                        </div>
                                        <?php 
                                            $check_in_time = isset($_POST['check_in_time']) ? $_POST['check_in_time'] : get_post_meta($item_id,'check_in_time',true);
                                            
                                        ?>

                                        <input readonly id="check_in_time" placeholder="hh:mm tt" type="text" name="check_in_time" value="<?php echo $check_in_time; ?>" class="form-control form-control-admin st_timepicker">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for="check_out"><?php _e('Check out',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                        <div id="check-out-wrapper">
                                            
                                        </div>
                                        <?php 
                                            $check_out = isset($_POST['check_out']) ? $_POST['check_out'] : get_post_meta($item_id,'check_out',true);
                                            if(!empty($check_out)){
                                                $check_out = date('m/d/Y',strtotime($check_out));
                                            }else{
                                                $check_out = '';
                                            }
                                        ?>
                                        <input readonly id="check_out" placeholder="mm/dd/yyyy" type="text" name="check_out" value="<?php echo  $check_out; ?>" class="form-control form-control-admin st_datepicker">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for="check_out_time"><?php _e('Check out time',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                        <div id="check-in-wrapper">
                                            
                                        </div>
                                        <?php 
                                            $check_out_time = isset($_POST['check_out_time']) ? $_POST['check_out_time'] : get_post_meta($item_id,'check_out_time',true);
                                            
                                        ?>
                                        <input readonly id="check_out_time" placeholder="hh:mm tt" type="text" name="check_out_time" value="<?php echo $check_out_time; ?>" class="form-control form-control-admin st_timepicker">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Equipment Price List',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                        <div id="item-equipment-wrapper">
                                            <?php 
                                                $car_id = isset($_POST['item_id']) ? $_POST['item_id'] : $order_item_id;
                                                if(intval($car_id) > 0 && get_post_type($car_id) == 'st_cars'){
                                                    $item_equipment = get_post_meta($car_id, 'cars_equipment_list', true);
                                                    if(is_array($item_equipment) && count($item_equipment)){
                                                        $mang_ss = array();

                                                        $list_item_equipment = get_post_meta($item_id , 'data_equipment' , true);
                                                        if(is_array($list_item_equipment) && count($list_item_equipment)){
                                                            foreach ($list_item_equipment as $key => $value) {
                                                                $mang_ss[] = $value->title;
                                                            }
                                                        }else{
                                                            $list_item_equipment = isset($_POST['item_equipment']) ? $_POST['item_equipment'] : array('');
                                                                if (is_array($list_item_equipment) && count($list_item_equipment)){
                                                                foreach ($list_item_equipment as $key => $value) {
                                                                    if(!empty($value))
                                                                        $title = explode('--', $value);
                                                                    if(!empty($title[0]))
                                                                        $mang_ss[] = $title[0];
                                                                }
                                                            }
                                                        }
                                                        
                                                        $i = 0;

                                                        foreach($item_equipment as $key => $val){ 
                                                            $checked = null;
                                                            $item = $val['title'].'--'.$val['cars_equipment_list_price'];
                                                            if(in_array($val['title'], $mang_ss)){
                                                                $checked = 'checked';
                                                            }
                                                            
                                                            $cars_equipment_list_price = TravelHelper::convert_money($val['cars_equipment_list_price']);
                                                            $cars_equipment_list_price_html = TravelHelper::format_money($cars_equipment_list_price ,false);
                                                            echo '<div class="form-group" style="margin-bottom: 10px">
                                                            <label for="item_equipment-'.$i.'"><input '.$checked.' id="item_equipment-'.$i.'" type="checkbox" name="item_equipment[]" value="'.$val['title'].'--'.$cars_equipment_list_price.'">'.$val['title'].'('.$cars_equipment_list_price_html.')</label>
                                                            </div>';
                                                            $i ++;
                                                        }
                                                    }
                                                }
                                            ?>
                                        </div>
                                        <span class="spinner"></span>
                                    </div>
                                </div>
                                <?php if(st()->get_option('tax_enable','off') == 'on' && st()->get_option('st_tax_include_enable', 'off') == 'off'){ ?>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Tax',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                        <?php
                                        $tax = floatval(st()->get_option('tax_value',0));
                                        ?>
                                        <input name="tax" type="text" readonly value="<?php echo esc_attr($tax); ?>" class="form-control form-control-admin "><?php _e('(%)',ST_TEXTDOMAIN)?>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Total',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                        <?php
                                            $total_price = floatval(get_post_meta($item_id, 'total_price', true));
                                        ?>
                                        <input name="total_price" type="text" readonly value="<?php echo TravelHelper::format_money_from_db($total_price, $currency); ?>" class="form-control form-control-admin "><?php _e('Auto calculate',ST_TEXTDOMAIN)?>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for="st_note"><?php _e('Special Requirements',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                    <?php 
                                        $st_note = isset($_POST['st_note']) ? $_POST['st_note'] : get_post_meta($item_id,'st_note',true);
                                    ?>
                                        <textarea name="st_note" rows="6" class="form-control-admin"><?php echo $st_note; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for="status"><?php _e('Status',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                        <select data-block="" class="form-control" name="status">
                                            <?php $status=get_post_meta($item_id,'status',true); ?>

                                            <option value="pending" <?php selected($status,'pending') ?> ><?php _e('Pending',ST_TEXTDOMAIN)?></option>
                                            <option value="complete" <?php selected($status,'complete') ?> ><?php _e('Complete',ST_TEXTDOMAIN)?></option>
                                            <option value="canceled" <?php selected($status,'canceled') ?> ><?php _e('Canceled',ST_TEXTDOMAIN)?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">

                                    <div class="controls">
                                        <label class="form-label" for="" >&nbsp;</label>
                                        <input type="submit" name="submit" value="<?php echo __('Save',ST_TEXTDOMAIN)?>" class="button button-primary ">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>