<?php
/**
 * Streamliners API data select view
 */
$default = array(
    'title'                    => '' ,
    'taxonomy'                 => '' ,
    'is_required'              => 'on' ,
    'type_show_taxonomy_hotel' => 'checkbox' ,
);
if(isset( $data )) {
    extract( wp_parse_args( $data , $default ) );
    if(!empty( $data[ 'custom_terms_' . $taxonomy ] )) {
        $terms_custom = $data[ 'custom_terms_' . $taxonomy ];
        $terms_custom = array_values( $terms_custom );
    }
} else {
    extract( $default );
}

if(!isset( $field_size ))
    $field_size = 'lg';

if($is_required == 'on') {
    $is_required = 'required';
}

$checkbox_item_size = 4;
if(!empty( $st_direction ) and $st_direction == 'vertical') {
    $checkbox_item_size = apply_filters( "st_taxonomy_checkbox_size" , '6' );
}

if($type_show_taxonomy_hotel == "select") { ?>

    <div class="form-group form-group-<?php echo esc_attr( $field_size )?>" taxonomy="<?php echo esc_html( $taxonomy ) ?>">
        <label for="field-hotel-tax-<?php echo esc_html( $taxonomy ) ?>"><?php echo esc_html( $title )?></label>

        <select name="taxonomy_hotel_room[room_type]" id="field-hotel-tax-room_type" class="form-control">
            <option value="">— Select —</option>
            <?php foreach (StreamlineCore_Wrapper::get_location_resorts() as $location):?>
                <option class="level-0" value="<?php echo $location->id ;?>"><?php echo $location->name ;?></option>
            <?php endforeach ;?>
        </select>

    </div>

<?php } ?>