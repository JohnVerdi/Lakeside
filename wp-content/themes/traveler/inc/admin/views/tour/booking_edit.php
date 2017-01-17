<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Admin tour booking edit
 *
 * Created by ShineTheme
 *
 */

$item_id = isset($_GET['order_item_id'])?$_GET['order_item_id']:false;

$order_item_id = get_post_meta($item_id,'item_id',true);

$section = isset($_GET['section']) ? $_GET['section'] : false;

if(!isset($page_title))
{
    $page_title=__('Edit Tour Booking',ST_TEXTDOMAIN);
}
$currency = get_post_meta($item_id, 'currency', true);

?>
<div class="wrap">
    <?php echo '<h2>'.$page_title.'</h2>';?>
    <?php STAdmin::message() ?>
    <div id="post-body" class="columns-2">
        <div id="post-body-content">
            <div class="postbox-container">
                <form method="post" action="" id="form-booking-admin">
                    <?php wp_nonce_field('shb_action','shb_field') ?>
                    <div id="poststuff">
                        <div class="postbox">
                            <div class="handlediv" title="<?php _e('Click to toggle',ST_TEXTDOMAIN)?>"><br></div>
                            <h3 class="hndle ui-sortable-handle"><span><?php _e('Order Information',ST_TEXTDOMAIN)?></span></h3>

                            <div class="inside">
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Booker ID',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                        <?php
                                        $id_user='';
                                        $pl_name='';
                                        if($item_id){
                                            $id_user= get_post_meta($item_id,'id_user',true);
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
                                    <label class="form-label" for=""><?php _e('Tour',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                    <?php 

                                        $tour_id = intval(get_post_meta($item_id,'item_id',true));
                                        if(get_post_type($tour_id) == 'st_tours' && $section == 'edit_order_item'):
                                    ?>
                                        <select name="item_id" id="item_id" class="form-control form-control-admin">
                                            <option value="<?php echo $tour_id ?>"><?php echo get_the_title($tour_id); ?></option>
                                        </select>
                                    <?php endif; ?>
                                    <?php 
                                        $tour_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : '';
                                        $tour_name = (get_post_type($tour_id) == 'st_tours') ? get_the_title($tour_id) : '';
                                        if($section == 'add_booking'):
                                    ?>
                                        <input id="tour_id" type="hidden" name="item_id" value="<?php echo $tour_id; ?>" data-post-type="st_tours" class="form-control form-control-admin st_post_select_ajax " data-pl-name="<?php echo $tour_name; ?>">
                                    <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Tour Type',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                        <?php 
                                            $tour_type = get_post_meta($item_id, 'type_tour', true);
                                            if($tour_type && $section == 'edit_order_item'):
                                                if($tour_type == 'daily_tour'){
                                                    $tour_name = __('Daily Tour', ST_TEXTDOMAIN);
                                                }elseif($tour_type == 'specific_date'){
                                                    $tour_name = __('Specific Date', ST_TEXTDOMAIN);
                                                }
                                        ?>
                                        <select readonly name="type_tour" id="type_tour" class="form-control form-control-admin">
                                            <option value="<?php echo $tour_type; ?>"><?php echo $tour_name; ?></option>
                                        </select>
                                        <?php endif; ?>
                                        <div id="tour-type-wrapper">
                                             
                                        </div>
                                        
                                        <span class="spinner "></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for="max_people"><?php _e('Max people',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                        <?php 
                                            $max_people = isset($_POST['max_people']) ? $_POST['max_people'] : get_post_meta($order_item_id,'max_people',true);
                                        ?>
                                        <input readonly id="max_people" placeholder="" type="text" name="max_people" value="<?php if(isset($max_people)) echo $max_people; ?>" class="form-control form-control-admin ">
                                        <span class="spinner "></span>
                                    </div>
                                </div>
                                <?php if($section == 'add_booking'): ?>
                                <div class="form-row">
                                    <label class="form-label" for="tour_time"><?php _e('Select a day',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <a class="button button-primary" id="tour_time" href="#" onclick="return false"><?php echo __('Select', ST_TEXTDOMAIN) ?></a>
                                </div>
                                <?php endif; ?>

                                <div class="form-row">
                                    <label class="form-label" for="check_in"><?php _e('Departure date',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                        <?php 
                                            $check_in = isset($_POST['check_in']) ? $_POST['check_in'] : get_post_meta($item_id,'check_in',true);
                                            
                                            if(!empty($check_in)){
                                                $check_in = date('m/d/Y',strtotime($check_in));
                                            }else{
                                                $check_in = '';
                                            }
                                        ?>
                                        <input readonly id="check_in" placeholder="mm/dd/yyyy" type="text" name="check_in" value="<?php echo $check_in; ?>" class="form-control form-control-admin ">
                                        
                                    </div>
                                </div>
                                <div class="form-row <?php if($section != 'add_booking' && $tour_type == 'daily_tour') echo 'hide'; ?>">
                                    <label class="form-label" for="check_out"><?php _e('Arrive date',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                        <?php 
                                            $check_out = isset($_POST['check_out']) ? $_POST['check_out'] : get_post_meta($item_id,'check_out',true);
                                            if(!empty($check_out)){
                                                $check_out = date('m/d/Y',strtotime($check_out));
                                            }else{
                                                $check_out = '';
                                            }
                                        ?>
                                        <input readonly  id="check_out" placeholder="mm/dd/yyyy" type="text" name="check_out" value="<?php echo  $check_out; ?>" class="form-control form-control-admin">
                                        
                                    </div>
                                </div>
                                <?php if($section != 'add_booking' && $tour_type == 'daily_tour'): ?>
                                <div class="form-row">
                                    <label class="form-label" for="duration"><?php _e('Duration',ST_TEXTDOMAIN)?> </label>
                                    <div class="controls">
                                        <?php 
                                            $duration = get_post_meta($item_id , 'duration' , true);                                            
                                        ?>
                                        <input readonly  id="duration" placeholder="" type="text" name="duration" value="<?php echo  $duration; ?>" class="form-control form-control-admin">
                                        
                                    </div>
                                </div>
                                <?php endif;?>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('No. Adults',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                    <?php 
                                        $adult_number = isset($_POST['adult_number']) ? $_POST['adult_number'] : get_post_meta($item_id,'adult_number',true);
                                    ?>
                                        <input <?php if($section == 'edit_order_item') echo 'readonly="readonly"'; ?> type="text" name="adult_number" value="<?php echo $adult_number; ?>" class="form-control form-control-admin  " >
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('No. Children',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                    <?php 
                                        $child_number = isset($_POST['child_number']) ? $_POST['child_number'] : get_post_meta($item_id,'child_number',true);
                                    ?>
                                        <input <?php if($section == 'edit_order_item') echo 'readonly="readonly"'; ?> type="text" name="child_number" value="<?php echo $child_number; ?>"  class="form-control form-control-admin " >
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('No. Infant',ST_TEXTDOMAIN)?><span class="require"> (*)</span></label>
                                    <div class="controls">
                                    <?php 
                                        $infant_number = isset($_POST['infant_number']) ? $_POST['infant_number'] : get_post_meta($item_id,'infant_number',true);
                                    ?>
                                        <input <?php if($section == 'edit_order_item') echo 'readonly="readonly"'; ?> type="text" name="infant_number" value="<?php echo $infant_number; ?>"  class="form-control form-control-admin " >
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Adult Price',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                    <?php 
                                        $adult_price = isset($_POST['adult_price']) ? floatval($_POST['adult_price']) : floatval(get_post_meta($item_id, 'adult_price', true));
                                    ?>
                                        <input id="adult_price" type="text" readonly name="adult_price" value="<?php echo TravelHelper::format_money_from_db($adult_price, $currency); ?>" class="form-control form-control-admin ">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Children Price',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                    <?php 
                                        $child_price = isset($_POST['child_price']) ? floatval($_POST['child_price']) : floatval(get_post_meta($item_id, 'child_price', true));
                                    ?>
                                        <input id="child_price" type="text" readonly name="child_price" value="<?php echo TravelHelper::format_money_from_db($child_price, $currency); ?>" class="form-control form-control-admin ">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Infant Price',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                    <?php 
                                        $infant_price = isset($_POST['infant_price']) ? floatval($_POST['infant_price']) : floatval(get_post_meta($item_id, 'infant_price', true));
                                    ?>
                                        <input id="infant_price" type="text" readonly name="infant_price" value="<?php echo TravelHelper::format_money_from_db($infant_price, $currency) ?>" class="form-control form-control-admin ">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for="extra"><?php _e('Extra',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                        <?php
                                        if($section == 'edit_order_item' && get_post_type($order_item_id) == 'st_tours'):
                                            $extra_price = get_post_meta($order_item_id, 'extra_price', true);
                                            $extras = get_post_meta($item_id, 'extras', true);
                                            $data_item = array(); $data_number = array();
                                            if(isset($extras['value']) && is_array($extras['value']) && count($extras['value'])){
                                                foreach($extras['value'] as $name => $number){
                                                    $data_item[] = $name;
                                                    $data_number[$name] = $extras['value'][$name];
                                                }
                                            }
                                            ?>
                                            <?php if(is_array($extra_price) && count($extra_price)): ?>
                                            <table class="table">
                                                <?php foreach($extra_price as $key => $val): ?>
                                                    <tr>
                                                        <td width="80%">
                                                            <label for="<?php echo $val['extra_name']; ?>" class="ml20">
                                                                <?php echo $val['title']; ?></label>
                                                            <input type="hidden" name="extra_price[price][<?php echo $val['extra_name']; ?>]" value="<?php echo $val['extra_price']; ?>">
                                                            <input type="hidden" name="extra_price[title][<?php echo $val['extra_name']; ?>]" value="<?php echo $val['title']; ?>">
                                                        </td>
                                                        <td width="20%">
                                                            <select style="width: 100px" class="form-control" name="extra_price[value][<?php echo $val['extra_name']; ?>]" id="">
                                                                <option value=""><?php echo __('----Select----', ST_TEXTDOMAIN); ?></option>
                                                                <?php
                                                                $max_item = intval($val['extra_max_number']);
                                                                if($max_item <= 0) $max_item = 1;
                                                                for($i = 1; $i <= $max_item; $i++):
                                                                    ?>
                                                                    <option value="<?php echo $i; ?>" <?php if(isset($data_number[$val['extra_name']]) && $i == $data_number[$val['extra_name']]) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
                                                                <?php endfor; ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php endif; ?>
                                        <?php endif;?>
                                        <div id="extra-price-wrapper">

                                        </div>
                                        <span class="spinner extra_price"></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-label" for=""><?php _e('Total',ST_TEXTDOMAIN)?></label>
                                    <div class="controls">
                                       <?php 
                                        $total_price = floatval(get_post_meta($item_id, 'total_price', true));
                                        
                                    ?>
                                        <input name="total_price" type="text" readonly value="<?php echo TravelHelper::format_money_from_db($total_price, $currency); ?>" class="form-control form-control-admin ">
                                        <br /> <span><em><?php echo __('Auto Calculate when saving', ST_TEXTDOMAIN); ?></em></span>
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
                                            <?php $status = get_post_meta($item_id,'status',true); ?>

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
<?php if($section == 'add_booking'): ?>
<div id="tour_time_content" style="padding:15px; display: none">
    <div class="row calendar-wrapper mb20" data-post-id="<?php echo get_the_ID(); ?>">
        <div class="overlay">
            <div class="spinner user_img_loading loaded">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="calendar-content">
                
            </div>
        </div>
    </div>
</div>
<?php endif; ?>