<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User create cruise
 *
 * Created by ShineTheme
 *
 */
?>
<div class="st-create">
    <h2><?php st_the_language('user_create_cruise') ?></h2>
</div>
<div class="msg">
    <?php echo STUser_f::get_msg(); ?>
</div>
<form action="" method="post" enctype="multipart/form-data">
    <?php wp_nonce_field('user_setting','st_insert_post_cruise'); ?>
    <div class="form-group form-group-icon-left">
        <i class="fa  fa-file-text input-icon input-icon-hightlight"></i>
        <label><?php st_the_language('user_create_cruise_title') ?></label>
        <input id="title" name="st_title" type="text" placeholder="Title" class="form-control">
        <div class="st_msg console_msg_title"></div>
    </div>
    <div class="form-group form-group-icon-left">
        <label><?php st_the_language('user_create_cruise_content') ?></label>
        <?php wp_editor('','st_content'); ?>
    </div>
    <div class="form-group form-group-icon-left">
        <label><?php st_the_language('user_create_cruise_description') ?></label>
        <textarea id="desc" name="st_desc" class="form-control"></textarea>
        <div class="st_msg console_msg_desc"></div>
    </div>
    <h4><?php st_the_language('user_create_cruise_detail') ?></h4>
    <div class="row">
        <div class="col-md-12">
            <?php
            $taxonomy = STUser_f::get_list_value_taxonomy('cruise');
            if(!empty($taxonomy)):
                ?>
                <div class="form-group form-group-icon-left">
                    <label> <?php st_the_language('user_create_cruise_attributes') ?></label>
                    <div class="row">
                        <?php foreach($taxonomy as $k=>$v): ?>
                            <div class="col-md-3">
                                <div class="checkbox-inline checkbox-stroke">
                                    <label>
                                        <i class="<?php echo esc_html($v['icon']) ?>"></i>
                                        <input name="taxonomy[]" class="i-check" type="checkbox" value="<?php echo esc_attr($v['value'].','.$v['taxonomy']) ?>" /><?php echo esc_html($v['label']) ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <i class="fa  fa-user input-icon input-icon-hightlight"></i>
                <label><?php st_the_language('user_create_cruise_number_of_children_stay_free') ?></label>
                <input id="st_children_free" name="st_children_free" type="number" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
                <label><?php st_the_language('user_create_cruise_email') ?></label>
                <input id="email" name="email" type="email" placeholder="<?php st_the_language('user_create_cruise_email') ?>" class="form-control">
                <div class="st_msg console_msg_email"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <i class="fa fa-info-circle input-icon input-icon-hightlight"></i>
                <label><?php st_the_language('user_create_cruise_website') ?></label>
                <input id="website" name="website" type="text" placeholder="<?php st_the_language('user_create_cruise_website') ?>" class="form-control">
                <div class="st_msg console_msg_website"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                <label><?php st_the_language('user_create_cruise_phone') ?></label>
                <input id="phone" name="phone" type="text" placeholder="<?php st_the_language('user_create_cruise_phone') ?>" class="form-control">
                <div class="st_msg console_msg_phone"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                <label><?php st_the_language('user_create_cruise_fax_number') ?></label>
                <input id="fax" name="fax" type="text" placeholder="<?php st_the_language('user_create_cruise_fax_number') ?>" class="form-control">
                <div class="st_msg console_msg_fax"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <i class="fa  fa-youtube input-icon input-icon-hightlight"></i>
                <label><?php st_the_language('user_create_cruise_video') ?></label>
                <input id="video" name="video" type="text" placeholder="<?php st_the_language('user_create_cruise_video') ?>" class="form-control">
                <div class="st_msg console_msg_video"></div>
            </div>
        </div>
    </div>
    <h4><?php st_the_language('user_create_cruise_programes') ?></h4>
    <div class="row">
        <div class=""  id="data_program">
        </div>
        <div class="col-md-8">
            <div class="form-group form-group-icon-left">
                <button id="btn_add_program" type="button" class="btn btn-info"><?php st_the_language('user_create_cruise_add_program') ?></button><br>
            </div>
        </div>
    </div>
    <h4><?php st_the_language('user_create_cruise_location') ?></h4>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <label><?php st_the_language('user_create_cruise_location') ?></label>
                <input type="text" name="id_location" placeholder="Cruise : search"="Location : search" id="id_location" class="st_post_select" data-post-type="location" style="width: 100%">
                <div class="st_msg console_msg_id_location"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <i class="fa fa-home input-icon input-icon-hightlight"></i>
                <label><?php st_the_language('user_create_cruise_address') ?></label>
                <input id="address" name="address" type="text" placeholder="<?php st_the_language('user_create_cruise_address') ?>" class="form-control">
                <div class="st_msg console_msg_address"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <i class="fa  fa-rocket input-icon input-icon-hightlight"></i>
                <label><?php st_the_language('user_create_cruise_latitude') ?></label>
                <input id="map_lat" name="map_lat" type="text" placeholder="<?php st_the_language('user_create_cruise_latitude') ?>" class="form-control">
                <div class="st_msg console_msg_map_lat"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <i class="fa  fa-rocket input-icon input-icon-hightlight"></i>
                <label><?php st_the_language('user_create_cruise_longitude') ?></label>
                <input id="map_lng" name="map_lng" type="text" placeholder="<?php st_the_language('user_create_cruise_longitude') ?>" class="form-control">
                <div class="st_msg console_msg_map_lng"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <i class="fa fa-plus-square input-icon input-icon-hightlight"></i>
                <label><?php st_the_language('user_create_cruise_map_zoom') ?></label>
                <input id="map_zoom" name="map_zoom" type="text" placeholder="<?php st_the_language('user_create_cruise_map_zoom') ?>" class="form-control" value="13">
                <div class="st_msg console_msg_map_zoom"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <label> <?php st_the_language('user_create_cruise_featured_image') ?></label>
                <input id="featured-image" name="featured-image" type="file" class="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <label><?php st_the_language('user_create_cruise_gallery') ?></label>
                <input id="gallery" name="gallery[]" type="file" multiple class="">
            </div>
        </div>
    </div>

    <input  type="button" id="btn_check_insert_post_type_cruise"  class="btn btn-primary" value="<?php st_the_language('user_create_cruise_submit') ?>">
    <input name="btn_insert_post_type_cruise" id="btn_insert_post_type_cruise" type="submit"  class="btn btn-primary hidden" hidden="" value="SUBMIT">
</form>
<div id="html_program" style="display: none">
    <div class="item">
        <div class="col-md-4">
            <div class="form-group form-group-icon-left">
                <label><?php st_the_language('user_create_cruise_program_title') ?></label>
                <input id="title" name="program_title[]" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group form-group-icon-left">
                <label><?php st_the_language('user_create_cruise_program_desc') ?></label>
                <textarea name="program_desc[]" class="form-control h_35"></textarea>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group form-group-icon-left">
                <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                    <?php st_the_language('user_create_cruise_del') ?>
                </div>
            </div>
        </div>
    </div>
</div>