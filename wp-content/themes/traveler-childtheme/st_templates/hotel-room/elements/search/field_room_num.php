<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Hotel field room num
 *
 * Created by ShineTheme
 *
 */
$default=array(
    'title'=>''
);

if(isset($data)){
    extract(wp_parse_args($data,$default));
}else{
    extract($default);
}
$old=STInput::get('room_num_search');

if(!isset($field_size)) $field_size='lg';
?>
<div class="form-group form-group-<?php echo esc_attr($field_size)?> form-group-select-plus">
    <label for="field-hotel-room-num"><?php echo esc_html($title)?></label>
    <div class="btn-group btn-group-select-num <?php if($old>=4)echo 'hidden';?>" data-toggle="buttons">
        <label class="btn btn-primary <?php echo (!$old)?'active':false; ?>">
            <input type="radio" value="" name="" />All</label>
        <label class="btn btn-primary <?php echo ($old==1)?'active':false; ?>">
            <input type="radio" value="1" name="bedrooms_number[]" />1</label>
        <label class="btn btn-primary <?php echo ($old==2)?'active':false; ?>">
            <input type="radio" value="2" name="bedrooms_number[]" />2</label>
        <label class="btn btn-primary <?php echo ($old==3)?'active':false; ?>">
            <input type="radio" value="3" name="bedrooms_number[]" />3</label>
        <label class="btn btn-primary <?php echo ($old==4)?'active':false; ?>">
            <input type="radio" value="4,5,6" name="bedrooms_number[]" />3+</label>
    </div>
<!--    <select id="field-hotel-room-num" class="form-control --><?php //if($old<4)echo 'hidden';?><!-- " name="bedrooms[]">-->
<!--        --><?php //$adult_max=14;
//        for($i=1;$i<=$adult_max;$i++){
//            echo "<option ".selected($old,$i,false)." value='{$i}'>{$i}</option>";
//        }
//        ?>
<!--    </select>-->
</div>