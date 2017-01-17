<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Cars element search field drop off to list
 *
 * Created by ShineTheme
 *
 */
$default=array(
    'title'=>'',
    'is_required'=>'on',
);
if(isset($data)){
    extract(wp_parse_args($data,$default));
}else{
    extract($default);
}
$old_location=STInput::get('location_id_drop_off');
$list_location = TravelerObject::get_list_location();
if($is_required == 'on'){
    $is_required = 'required';
}
if(!isset( $field_size ))
    $field_size = 'md';
?>
<?php if(!empty($list_location) and is_array($list_location)): ?>
<div class="form-group form-group-<?php echo esc_attr($field_size)?> form-group-icon-left">
    
    <label for="field-st-dropoff-list"><?php echo esc_html( $title)?></label>
    <i class="fa fa-map-marker input-icon"></i>
   <select id="field-st-dropoff-list" name="location_id_drop_off" class="form-control <?php echo esc_attr($is_required) ?>" >
       <option value=""><?php _e('-- Select --',ST_TEXTDOMAIN) ?></option>
       <?php foreach($list_location as $k=>$v): ?>
            <option <?php if($old_location == $v['id'] ) echo 'selected' ?> value="<?php echo esc_html($v['id']) ?>">
                <?php echo esc_html($v['title']) ?>
            </option>
       <?php endforeach; ?>
   </select>
</div>
<?php endif ?>