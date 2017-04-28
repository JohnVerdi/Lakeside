<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Rental form book
 *
 * Created by ShineTheme
 *
 */
if(!isset($field_size)) $field_size='';

$adult_max = intval(get_post_meta(get_the_ID(), 'rental_max_adult', true ));
$child_max = intval(get_post_meta(get_the_ID(), 'rental_max_children', true ));

echo STTemplate::message();
global $post;

//check is booking with modal
$st_is_booking_modal=apply_filters('st_is_booking_modal',false);
$booking_period = get_post_meta( get_the_ID() , 'rentals_booking_period' , true );
?>
<form method="post" action="" id="form-booking-inpage">
    <?php
        if(!get_option('permalink_structure'))
        {
            echo '<input type="hidden" name="st_rental"  value="'.st_get_the_slug().'">';
        }
    ?>
    <input type="hidden" name="action" value="rental_add_cart">
    <input type="hidden" name="item_id" value="<?php the_ID()?>">

<div class="booking-item-dates-change" data-booking-period="<?php echo $booking_period; ?>" data-post-id="<?php echo get_the_ID(); ?>">
		<div class="message_box mb10"></div>
        <div class="input-daterange" data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-group-icon-left">
                        <label for="field-rental-start"><?php st_the_language('rental_check_in')?></label>
                        <i class="fa fa-calendar input-icon"></i>
                        <input id="field-rental-start" required="required" placeholder="<?php echo TravelHelper::getDateFormatJs(__("Select date", ST_TEXTDOMAIN)); ?>" value="<?php echo STInput::post('start', STInput::get('start')); ?>" class="form-control required checkin_rental" name="start" type="text" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-group-icon-left">
                        <label for="field-rental-end"><?php st_the_language('rental_check_out')?></label>
                        <i class="fa fa-calendar input-icon"></i>
                        <input id="field-rental-end" required="required" placeholder="<?php echo TravelHelper::getDateFormatJs(__("Select date", ST_TEXTDOMAIN)); ?>" value="<?php echo STInput::post('end', STInput::get('end')); ?>" class="form-control required checkout_rental" name="end" type="text" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-6">
                <?php

                $old= STInput::post('adult_number',STInput::get('adult_number', 1));
                if( !$old ) $old = 1;

                ?>
                <div class="form-group form-group-<?php echo esc_attr($field_size) ?> form-group-select-plus">
                    <label for="field-rental-adult"><?php st_the_language('rental_adult')?></label>
                    <div class="btn-group btn-group-select-num <?php if($old>=4)echo 'hidden';?>" data-toggle="buttons">
                        <?php 
                            $max = 4;
                            if( $adult_max < 4) $max = $adult_max;

                            for( $i = 1; $i <= $max; $i ++):
                                $name = ''.$i;
                                if( $i == 4 ){
                                    $name = ''.$i.'+';
                                }
                        ?>
                        <label class="btn btn-primary <?php echo ($old == $i) ? 'active': false; ?>">
                            <input type="radio" value="<?php echo $i; ?>" name="options" /><?php echo $name; ?>
                        </label>
                        <?php endfor; ?>
                    </div>
                    <select id="field-rental-adult" class="form-control required <?php if($old<4)echo 'hidden';?>" name="adult_number">
                        <?php $max=14;
                        for($i=1;$i<=$adult_max;$i++){
                            echo "<option ".selected($i,$old,false)." value='{$i}'>{$i}</option>";
                        }
                        ?>
                    </select>
                </div>
                </div>
            <div class="col-sm-6">
                <?php
               $old=STInput::post('child_number',STInput::get('child_number', 0));;
                ?>
                <div class="form-group form-group-<?php echo esc_attr($field_size) ?> form-group-select-plus">
                    <label for="field-rental-children"><?php st_the_language('rental_children')?></label>
                    <div class="btn-group btn-group-select-num <?php if($old>=3)echo 'hidden';?>" data-toggle="buttons">
                        <?php 
                            $max = 4;
                            if( $child_max < 4) $max = $child_max;

                            for( $i = 1; $i <= $max; $i ++):
                                $name = ''.$i;
                                if( $i == 4 ){
                                    $name = ''.($i - 1).'+';
                                }
                        ?>
                        <label class="btn btn-primary <?php echo ($old == $i) ? 'active': false; ?>">
                            <input type="radio" value="<?php echo $i; ?>" name="options" /><?php echo $name; ?>
                        </label>
                        <?php endfor; ?>
                    </div>
                    <select id="field-rental-children" class="form-control required <?php if($old<3)echo 'hidden';?>" name="child_number">
                        <?php $max=14;
                        for($i=0;$i<=$child_max;$i++){
                            echo "<option ".selected($i,$old,false)." value='{$i}'>{$i}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?php
                    $extra_price = get_post_meta(get_the_ID(), 'extra_price', true);
                ?>
                <?php if(is_array($extra_price) && count($extra_price)): ?>
                    <?php $extra = STInput::request("extra_price");
                    if(!empty($extra['value'])){
                        $extra_value = $extra['value'];
                    }
                    ?>
                    <label ><?php echo __('Extra', ST_TEXTDOMAIN); ?></label>
                    <table class="table">
                    <?php foreach($extra_price as $key => $val): ?>
                        <tr>
                            <td width="80%">
                                <label for="<?php echo $val['extra_name']; ?>" class="ml20"><?php echo $val['title'].' ('.TravelHelper::format_money($val['extra_price']).')'; ?></label>
                                <input type="hidden" name="extra_price[price][<?php echo $val['extra_name']; ?>]" value="<?php echo $val['extra_price']; ?>">
                                <input type="hidden" name="extra_price[title][<?php echo $val['extra_name']; ?>]" value="<?php echo $val['title']; ?>">
                            </td>
                            <td width="20%">
                                <select style="width: 100px" class="form-control app" name="extra_price[value][<?php echo $val['extra_name']; ?>]" id="">
                                <?php
                                    $max_item = intval($val['extra_max_number']);
                                    if($max_item <= 0) $max_item = 1;
                                    for($i = 0; $i <= $max_item; $i++):
                                        $check = "";
                                        if(!empty($extra_value[$val['extra_name']]) and $i == $extra_value[$val['extra_name']]){
                                            $check = "selected";
                                        }
                                ?>
                                    <option  <?php echo esc_html($check) ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>

</div>
<div class="gap gap-small"></div>
    <?php if(!$st_is_booking_modal):?>
    <?php echo STRental::rental_external_booking_submit();?>
    <?php else:?>
        <a href="#rental_booking_<?php the_ID() ?>" onclick="return false" class="btn btn-primary btn-st-add-cart" data-target=#rental_booking_<?php the_ID() ?>  data-effect="mfp-zoom-out" ><?php st_the_language('rental_book_now') ?> <i class="fa fa-spinner fa-spin"></i></a>
    <?php endif;?>
</form>
<?php
if($st_is_booking_modal){?>
    <div class="mfp-with-anim mfp-dialog mfp-search-dialog mfp-hide" id="rental_booking_<?php the_ID()?>">
        <?php echo st()->load_template('rental/modal_booking');?>
    </div>

<?php }?>