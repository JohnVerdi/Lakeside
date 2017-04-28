<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User create cabin
 *
 * Created by ShineTheme
 *
 */
?>
<div class="st-create">
    <h2><?php st_the_language('user_create_cruise_cabin') ?></h2>
</div>
<div class="msg">
    <?php echo STUser_f::get_msg(); ?>
</div>
<form action="" method="post" enctype="multipart/form-data">
    <?php wp_nonce_field('user_setting','st_insert_cabin'); ?>
    <div class="form-group form-group-icon-left">
        <i class="fa  fa-file-text input-icon input-icon-hightlight"></i>
        <label><?php st_the_language('user_create_cruise_cabin_title') ?></label>
        <input id="title" name="st_title" type="text" placeholder="Title" class="form-control">
        <div class="st_msg console_msg_title"></div>
    </div>
    <div class="form-group form-group-icon-left">
        <label><?php st_the_language('user_create_cruise_cabin_content') ?></label>
        <?php wp_editor('','st_content'); ?>
    </div>
    <div class="form-group form-group-icon-left">
        <label><?php st_the_language('user_create_cruise_cabin_description') ?></label>
        <textarea id="desc" name="st_desc" class="form-control"></textarea>
        <div class="st_msg console_msg_desc"></div>
    </div>
    <div class="form-group form-group-icon-left">
        <label><?php st_the_language('user_create_cruise_cabin_featured_image') ?> </label>
        <input id="featured-image" name="featured-image" type="file" placeholder="Featured Image" class="">
    </div>
    <h4><?php st_the_language('user_create_cruise_cabin_detail') ?></h4>
    <div class="row">
        <div class="col-md-12">
            <?php
            $category = STUser_f::get_list_taxonomy('cabin_type');
            if(!empty($category)):
                ?>
                <div class="form-group form-group-icon-left">
                    <label> <?php st_the_language('user_create_cruise_cabin_type') ?></label>
                    <div class="row">
                        <?php foreach($category as $k=>$v): ?>
                            <div class="col-md-3">
                                <div class="checkbox-inline checkbox-stroke">
                                    <label>
                                        <input name="id_category[]" class="i-check" type="checkbox" value="<?php echo esc_attr($k) ?>" /><?php echo esc_html($v) ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <div class="col-md-12">
            <?php
            $taxonomy = STUser_f::get_list_value_taxonomy('cruise_cabin');
            if(!empty($taxonomy)):
                ?>
                <div class="form-group form-group-icon-left">
                    <label> <?php st_the_language('user_create_cruise_cabin_attributes') ?></label>
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
                <label><?php st_the_language('user_create_cruise_cabin_title_cruise') ?></label>
                <input type="text" name="cruise_id" placeholder="<?php st_the_language('user_create_cruise_cabin_search') ?>" id="cruise_id" class="st_post_select" data-author="<?php echo esc_attr($data->ID)?>" data-post-type="cruise" style="width: 100%">
                <div class="st_msg console_msg_cruise_id"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <label><?php st_the_language('user_create_cruise_cabin_max_adults') ?></label>
                <input id="max_adult" name="max_adult" type="number" min="1" value="1" placeholder="<?php st_the_language('user_create_cruise_cabin_max_adults') ?>" class="form-control">
                <div class="st_msg console_msg_max_adult"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <label><?php st_the_language('user_create_cruise_cabin_max_children') ?></label>
                <input id="max_children" name="max_children" type="number" min="1" value="1" placeholder="<?php st_the_language('user_create_cruise_cabin_max_children') ?>" class="form-control">
                <div class="st_msg console_msg_max_children"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <label><?php st_the_language('user_create_cruise_cabin_beb_size') ?></label>
                <input id="bed_size" name="bed_size" type="number" min="1" value="1" placeholder="<?php st_the_language('user_create_cruise_cabin_beb_size') ?>" class="form-control">
                <div class="st_msg console_msg_bed_size"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <label><?php st_the_language('user_create_cruise_cabin_size') ?></label>
                <input id="cabin_size" name="cabin_size" type="number" min="1" value="1" placeholder="<?php st_the_language('user_create_cruise_cabin_size') ?>" class="form-control">
                <div class="st_msg console_msg_cabin_size"></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group form-group-icon-left">
                <label><?php st_the_language('user_create_cruise_cabin_features') ?></label>
            </div>
        </div>
        <div class="" id="data_features_rental"></div>
        <div class="col-md-12">
            <div class="form-group form-group-icon-left">
                <button id="btn_add_features_rental" type="button" class="btn btn-info"><?php st_the_language('user_create_cruise_cabin_add_features') ?></button><br>
            </div>
        </div>
    </div>
    <h4><?php st_the_language('user_create_cruise_cabin_price') ?></h4>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <i class="fa fa-money input-icon input-icon-hightlight"></i>
                <label><?php st_the_language('user_create_cruise_cabin_price') ?></label>
                <input id="price" name="price" type="text" placeholder="<?php st_the_language('user_create_cruise_cabin_price') ?>" class="form-control">
                <div class="st_msg console_msg_id_location"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-group-icon-left">
                <i class="fa fa-star input-icon input-icon-hightlight"></i>
                <label><?php st_the_language('user_create_cruise_cabin_discount') ?></label>
                <input id="discount" name="discount" type="text" placeholder="<?php st_the_language('user_create_cruise_cabin_discount') ?>" class="form-control">
                <div class="st_msg console_msg_address"></div>
            </div>
        </div>
    </div>

    <input  type="button" id="btn_check_insert_cruise_cabin"  class="btn btn-primary" value="<?php st_the_language('user_create_cruise_cabin_submit') ?>">
    <input name="btn_insert_cruise_cabin" id="btn_insert_cruise_cabin" type="submit"  class="btn btn-primary hidden" hidden="" value="SUBMIT">
</form>

<div id="html_features_rental" style="display: none">
    <div class="item">
        <div class="col-md-4">
            <div class="form-group form-group-icon-left">
                <label><?php st_the_language('user_create_cruise_cabin_features_title') ?></label>
                <input id="title" name="features_title[]" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-icon-left">
                <label><?php st_the_language('user_create_cruise_cabin_features_number') ?></label>
                <input id="title" name="features_number[]" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-group-icon-left">
                <label><?php st_the_language('user_create_cruise_cabin_features_icon') ?></label>
                <input id="title" name="features_icon[]" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group form-group-icon-left">
                <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                    <?php st_the_language('user_create_cruise_cabin_features_del') ?>
                </div>
            </div>
        </div>
    </div>
</div>