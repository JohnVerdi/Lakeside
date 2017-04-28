<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Activity element search check out
 *
 * Created by ShineTheme
 *
 */
$default=array(
    'title'=>'',
    'is_required'=>'',
    'placeholder'=> ''
);

if(isset($data)){
    extract(wp_parse_args($data,$default));
}else{
    extract($default);
}

if(!isset($field_size)) $field_size='lg';
if($is_required == 'on'){
    $is_required = 'required';
}
?>
<div data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" class="form-group input-daterange form-group-<?php echo esc_attr($field_size) ?> form-group-icon-left">
    <label for="field-st-checkout"><?php echo esc_html( $title)?></label>
    <i class="fa fa-calendar input-icon input-icon-highlight"></i>
    <input id="field-st-checkout" class="form-control <?php echo esc_attr($is_required) ?>" name="end" type="text" value="<?php echo STInput::get('end') ?>"  placeholder="<?php echo TravelHelper::getDateFormatJs(); ?>" />
</div>