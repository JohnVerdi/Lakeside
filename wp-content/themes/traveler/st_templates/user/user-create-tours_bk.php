<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User create tours
 *
 * Created by ShineTheme
 *
 */
$validator= STUser_f::$validator;
?>
<div class="st-create">
    <h2><?php _e("Add new Tour",ST_TEXTDOMAIN) ?></h2>
</div>
<div class="msg">
    <?php echo STTemplate::message() ?>
    <?php echo STUser_f::get_msg(); ?>
</div>
<form action="" method="post" enctype="multipart/form-data" id="st_form_add_partner" class="<?php //echo STUser_f::get_status_msg(); ?>">
    <?php wp_nonce_field('user_setting','st_insert_post_tours'); ?>
    <div class="form-group form-group-icon-left">
        
        <label for="title" class="head_bol"><?php echo __('Name of Tour', ST_TEXTDOMAIN); ?>:</label>
        <i class="fa  fa-file-text input-icon input-icon-hightlight"></i>
        <input id="title" name="st_title" type="text" placeholder="<?php echo __('Name of Tour', ST_TEXTDOMAIN); ?>" value="<?php echo STInput::request('st_title') ?>" class="form-control">
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_title'),'danger') ?></div>
    </div>
    <div class="form-group">
        <label for="st_content"  class="head_bol"><?php st_the_language('user_create_tour_content') ?>:</label>
        <?php wp_editor(stripslashes(STInput::request('st_content')),'st_content'); ?>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_content'),'danger') ?></div>
    </div>
    <div class="form-group ">
        <label for="desc" class="head_bol"><?php _e("Tour Description",ST_TEXTDOMAIN) ?>:</label>
        <textarea id="desc" name="st_desc" rows="6" class="form-control"><?php echo stripslashes(STInput::request( 'st_desc' )) ?></textarea>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_desc'),'danger') ?></div>
    </div>
    <div class="form-group form-group-icon-left">
        <label class="head_bol"><?php _e("Featured Image",ST_TEXTDOMAIN) ?>:</label>
        <?php
        $id_img = STInput::request('id_featured_image');
        $post_thumbnail_id = wp_get_attachment_image_src($id_img, 'full');
        ?>
        <div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        <?php _e("Browse…",ST_TEXTDOMAIN) ?> <input name="featured-image"  type="file" >
                    </span>
                </span>
            <input type="text" readonly="" value="<?php echo esc_url($post_thumbnail_id['0']); ?>" class="form-control data_lable">
        </div>
        <input id="id_featured_image" name="id_featured_image" type="hidden" value="<?php echo esc_attr($id_img) ?>">
        <?php
        if(!empty($post_thumbnail_id)){
            echo '<div class="user-profile-avatar user_seting st_edit">
                        <div><img width="300" height="300" class="avatar avatar-300 photo img-thumbnail" src="'.$post_thumbnail_id['0'].'" alt=""></div>
                        <input name="" type="button"  class="btn btn-danger  btn_featured_image" value="'.st_get_language('user_delete').'">
                      </div>';
        }
        ?>
        <i><?php _e("Image format : jpg, png, gif . We recommend size 800x600",ST_TEXTDOMAIN) ?></i>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('featured_image'),'danger') ?></div>
    </div>
    <div class="tabbable tabs_partner">
        <ul class="nav nav-tabs" id="">
            <li class="active"><a href="#tab-location-setting" data-toggle="tab"><?php _e("Location Settings",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-general" data-toggle="tab"><?php _e("General Settings",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-price-setting" data-toggle="tab"><?php _e("Price Settings",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-info" data-toggle="tab"><?php _e("Informations",ST_TEXTDOMAIN) ?></a></li>
            <?php $st_is_woocommerce_checkout=apply_filters('st_is_woocommerce_checkout',false);
            if(!$st_is_woocommerce_checkout):?>
                <li><a href="#tab-payment" data-toggle="tab"><?php _e("Payment Settings",ST_TEXTDOMAIN) ?></a></li>
            <?php endif ?>
            <?php $custom_field = st()->get_option( 'tours_unlimited_custom_field' );
            if(!empty( $custom_field ) and is_array( $custom_field )) { ?>
                <li><a href="#tab-custom-fields" data-toggle="tab"><?php _e("Custom Fields",ST_TEXTDOMAIN) ?></a></li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="tab-location-setting">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="multi_location"><?php st_the_language( 'user_create_car_location' ) ?>:</label>
                            <div id="setting_multi_location" class="location-front multi_location">
                                <?php 
                                    $html_location = TravelHelper::treeLocationHtml();
                                ?>  
                                <div class="form-group st-select-loction">
                                    <input placeholder="<?php echo __('Type to search', ST_TEXTDOMAIN); ?>" type="text" class="widefat form-control" name="search" value="">
                                    <div class="list-location-wrapper">
                                        <?php 
                                            if(is_array($html_location) && count($html_location)):
                                                foreach($html_location as $key => $location):
                                        ?>
                                            <div data-name="<?php echo $location['parent_name']; ?>" class="item" style="margin-left: <?php echo $location['level'].'px;'; ?> margin-bottom: 5px;">
                                                <label for="<?php echo 'location-'.$location['ID']; ?>">
                                                    <input id="<?php echo 'location-'.$location['ID']; ?>" type="checkbox" name="multi_location[]" value="<?php echo '_'.$location['ID'].'_'; ?>">
                                                    <span><?php echo $location['post_title']; ?></span>
                                                </label>
                                            </div>
                                        <?php  endforeach; endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('multi_location'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="address"><?php _e("Address",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-home input-icon input-icon-hightlight"></i>
                            <input id="address" name="address" type="text"
                                   placeholder="<?php _e("Address",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('address') ?>">

                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('address'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-12 partner_map">
                        <?php
                        if(class_exists('BTCustomOT')){
                            BTCustomOT::load_fields();
                            ot_type_bt_gmap_html();
                        }
                        ?>
                    </div>
                    <div class="col-md-6">
                        <br>
                        <div class='form-group form-group-icon-left'>
                            <label for="is_featured"><?php _e( "Enable Street Views" , ST_TEXTDOMAIN ) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $enable_street_views_google_map  = STInput::request('enable_street_views_google_map') ?>
                            <select class='form-control' name='enable_street_views_google_map' id="enable_street_views_google_map">
                                <option value='on' <?php if($enable_street_views_google_map == 'on') echo 'selected'; ?> ><?php _e("On",ST_TEXTDOMAIN) ?></option>
                                <option value='off' <?php if($enable_street_views_google_map == 'off') echo 'selected'; ?> ><?php _e("Off",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-general">
                <div class="row">
                    <?php
                    $taxonomies = (get_object_taxonomies('st_tours'));
                    if (is_array($taxonomies) and !empty($taxonomies)){
                        foreach ($taxonomies as $key => $value) {
                            ?>
                            <div class="col-md-12">

                                <?php
                                $category = STUser_f::get_list_taxonomy($value);
                                $taxonomy_tmp = get_taxonomy( $value );
                                $taxonomy_label =  ($taxonomy_tmp->label );
                                $taxonomy_name =  ($taxonomy_tmp->name );
                                if(!empty($category)):
                                    ?>
                                    <div class="form-group form-group-icon-left">
                                        <label for="check_all"> <?php echo esc_html($taxonomy_label); ?>:</label>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="checkbox-inline checkbox-stroke">
                                                    <label for="check_all">
                                                        <i class="fa fa-cogs"></i>
                                                        <input name="check_all" class="i-check check_all" type="checkbox"  /><?php _e("All",ST_TEXTDOMAIN) ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php foreach($category as $k=>$v):
                                                $icon = get_tax_meta($k,'st_icon');
                                                $icon = TravelHelper::handle_icon($icon);
                                                $check = '';
                                                if(STUser_f::st_check_post_term_partner( ''  ,$value , $k) == true ){
                                                    $check = 'checked';
                                                }
                                                ?>
                                                <div class="col-md-3">
                                                    <div class="checkbox-inline checkbox-stroke">
                                                        <label for="taxonomy">
                                                            <i class="<?php echo esc_html($icon) ?>"></i>
                                                            <input name="taxonomy[]" class="i-check item_tanoxomy" <?php echo esc_html($check) ?> type="checkbox" value="<?php echo esc_attr($k.','.$taxonomy_name) ?>" /><?php echo esc_html($v) ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                        <?php
                        }
                    } else { ?>
                        <input name="no_taxonomy" type="hidden" value="no_taxonomy">
                    <?php } ?>
                    <div class="col-md-12">
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('taxonomy[]'),'danger') ?></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class='form-group form-group-icon-left'>
                            
                            <label for="st_custom_layout"><?php _e( "Detail Tour Layout" , ST_TEXTDOMAIN ) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $layout = st_get_layout('st_tours');
                            if(!empty($layout) and is_array($layout)):
                                ?>
                                <select class='form-control' name='st_custom_layout' id="st_custom_layout">
                                    <?php
                                    $st_custom_layout = STInput::request('st_custom_layout');
                                    foreach($layout as $k=>$v):
                                        if($st_custom_layout == $v['value']) $check = "selected"; else $check = '';
                                        echo '<option '.$check.' value='.$v['value'].'>'.$v['label'].'</option>';
                                    endforeach;
                                    ?>
                                </select>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class='form-group form-group-icon-left'>
                            <?php if(st()->get_option( 'partner_set_feature' ) == "on") { ?>
                                
                                <label for="is_featured"><?php _e( "Set as Featured" , ST_TEXTDOMAIN ) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $is_featured  = STInput::request('is_featured') ?>
                                <select class='form-control' name='is_featured' id="is_featured">
                                    <option value='off' <?php if($is_featured == 'off') echo 'selected'; ?> ><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                    <option value='on' <?php if($is_featured == 'on') echo 'selected'; ?> ><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                </select>
                            <?php }; ?>
                        </div>
                    </div>
                </div>
                <div class="row">
					<div class="col-md-6">
						<div class="form-group form-group-icon-left">
							<label for="show_agent_contact_info"><?php _e('Choose which contact info will be shown?',ST_TEXTDOMAIN) ?>:</label>
							<?php $select=array(
								'use_theme_option'=>__('Use Theme Options',ST_TEXTDOMAIN),
								'user_agent_info'=>__('Use Agent Contact Info',ST_TEXTDOMAIN),
								'user_item_info'=>__('Use Item Info',ST_TEXTDOMAIN),
							) ?>
							<i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
							<select name="show_agent_contact_info" id="show_agent_contact_info" class="form-control app">
								<?php
								if(!empty($select)){
									foreach($select as $s=>$v){
										printf('<option value="%s" %s >%s</option>',$s,selected(STInput::request('show_agent_contact_info'),$s,FALSE),$v);
									}
								}
								?>
							</select>
							<div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('show_agent_contact_info'),'danger') ?></div>
						</div>
					</div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label id="email"><?php _e("Contact email address",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
                            <input id="email" name="email" type="text" placeholder="<?php _e("Contact email address",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('email') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('email'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 clear">
                        <div class="form-group form-group-icon-left">

                            <label id="website"><?php _e("Website",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa  fa-home input-icon input-icon-hightlight"></i>
                            <input id="website" name="website" type="text" placeholder="<?php _e("Website",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('website') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('website'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group form-group-icon-left">

                            <label id="website"><?php _e("Phone",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                            <input id="phone" name="phone" type="text" placeholder="<?php _e("Phone",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('phone') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('phone'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">

                            <label id="fax"><?php _e("Fax",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa  fa-fax input-icon input-icon-hightlight"></i>
                            <input id="fax" name="fax" type="text" placeholder="<?php _e("Fax",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('fax') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('fax'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="video"><?php st_the_language('user_create_tour_video') ?>:</label>
                            <i class="fa fa-youtube-play input-icon input-icon-hightlight"></i>
                            <input id="video" name="video" type="text" placeholder="<?php _e("Enter Youtube or Vimeo video link (Eg: https://www.youtube.com/watch?v=JL-pGPVQ1a8)") ?>" class="form-control" value="<?php echo STInput::request('video') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('video'),'danger') ?></div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="id_gallery"><?php _e( "Gallery" , ST_TEXTDOMAIN ) ?>:</label>
                            <?php $id_img = STInput::request('id_gallery') ?>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <span class="btn btn-primary btn-file multiple">
                                        <?php _e( "Browse…" , ST_TEXTDOMAIN ) ?> <input name="gallery[]" id="gallery" multiple type="file">
                                    </span>
                                </span>
                                <input type="text" readonly="" value="<?php echo esc_html( $id_img ) ?>" class="form-control data_lable">
                            </div>
                            <input id="id_gallery" name="id_gallery" type="hidden" value="<?php echo esc_attr( $id_img ) ?>">
                            <?php
                            if(!empty( $id_img )) {
                                echo '<div class="user-profile-avatar user_seting st_edit"><div>';
                                foreach( explode( ',' , $id_img ) as $k => $v ) {
                                    $post_thumbnail_id = wp_get_attachment_image_src( $v , 'full' );
                                    echo '<img width="300" height="300" class="avatar avatar-300 photo img-thumbnail" src="' . $post_thumbnail_id[ '0' ] . '" alt="">';
                                }
                                echo '</div><input name="" type="button"  class="btn btn-danger  btn_del_gallery" value="' . st_get_language( 'user_delete' ) . '"></div>';
                            }
                            ?>
                        </div>
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('gallery'),'danger') ?></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-price-setting">
                <div class="row">
                    <div class="people_price">
                        <div class="col-md-4">
                            <div class="form-group form-group-icon-left">
                                
                                <label for="adult_price"><?php _e("Adult Price",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                <input id="adult_price" name="adult_price" type="text" placeholder="<?php _e("Adult Price",ST_TEXTDOMAIN) ?>" class="number form-control" value="<?php echo STInput::request('adult_price') ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('adult_price'),'danger') ?></div>
                            </div>
                        </div>

                        <!--Fields list discount by Adult number booking-->
                        <div class="col-md-4">
                            <div class="form-group form-group-icon-left">
                                
                                <label for="child_price"><?php _e("Child Price",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                <input id="child_price" name="child_price" type="text" placeholder="<?php _e("Child Price",ST_TEXTDOMAIN) ?>" class="number form-control" value="<?php echo STInput::request('child_price') ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('child_price'),'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-group-icon-left">
                                
                                <label for="infant_price"><?php _e("Infant Price",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                <input id="infant_price" name="infant_price" type="text" placeholder="<?php _e("Infant Price",ST_TEXTDOMAIN) ?>" class="number form-control" value="<?php echo STInput::request('infant_price') ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('infant_price'),'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-group-icon-left">
                                <label for="is_sale_schedule"><?php _e("Hide No of adult in booking form",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <select class="form-control hide_adult_in_booking_form" name="hide_adult_in_booking_form" id="hide_adult_in_booking_form">
                                    <option value="off"><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                    <option value="on"><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-group-icon-left">
                                <label for="is_sale_schedule"><?php _e("Hide No of child in booking form",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <select class="form-control hide_children_in_booking_form" name="hide_children_in_booking_form" id="hide_children_in_booking_form">
                                    <option value="off" ><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                    <option value="on" ><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-group-icon-left">
                                <label for="is_sale_schedule"><?php _e("Hide No of infant in booking form",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <select class="form-control hide_infant_in_booking_form" name="hide_infant_in_booking_form" id="hide_infant_in_booking_form">
                                    <option value="off"><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                    <option value="on"><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                </select>
                            </div>
                        </div>
                        <!--Fields list discount by Child number booking-->
                        <div class="adult">
                            <div class="col-md-12">
                                <div class="form-group form-group-icon-left">
                                    <label for="discount_by_adult"  class="head_bol"><?php _e("Discount by No. Adults",ST_TEXTDOMAIN) ?>:</label>
                                </div>
                            </div>
                            <?php
                            $discount_by_adult_title = STInput::request('discount_by_adult_title');
                            $discount_by_adult_key = STInput::request('discount_by_adult_key');
                            $discount_by_adult_value = STInput::request('discount_by_adult_value');
                            ?>
                            <div class="" id="data_discount_by_adult">
                                <?php
                                if(!empty($discount_by_adult_title)){
                                    foreach($discount_by_adult_title as $k=>$v){
                                        if(!empty($v) and !empty($discount_by_adult_key[ $k ]) and !empty($discount_by_adult_value[ $k ])){
                                        ?>
                                        <div class="item">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="discount_by_adult_title"><?php _e("Title",ST_TEXTDOMAIN) ?></label>
                                                    <input id="" name="discount_by_adult_title[]" type="text" class="form-control" value="<?php echo esc_attr($v) ?>" placeholder="<?php _e("Title",ST_TEXTDOMAIN) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="discount_by_adult_key"><?php _e("Key Number",ST_TEXTDOMAIN) ?></label>
                                                    <input id="" name="discount_by_adult_key[]" type="text" class="form-control" value="<?php echo esc_attr($discount_by_adult_key[$k]) ?>" placeholder="<?php _e("Key Number",ST_TEXTDOMAIN) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="discount_by_adult_value"><?php _e("Percentage of Discount",ST_TEXTDOMAIN) ?></label>
                                                    <input id="" name="discount_by_adult_value[]" type="text" class="form-control" value="<?php echo esc_attr($discount_by_adult_value[$k]) ?>" placeholder="<?php _e("Percentage of Discount",ST_TEXTDOMAIN) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group form-group-icon-left">
                                                    <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                                                        X
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                            <div class="col-md-12 div_btn_add_custom">
                                <div class="form-group form-group-icon-left">
                                    <button id="btn_discount_by_adult" type="button" class="btn btn-info btn-sm"><?php _e("Add New",ST_TEXTDOMAIN) ?></button><br>
                                </div>
                            </div>
                        </div>
                        <div class="child">
                            <div class="col-md-12">
                                <div class="form-group form-group-icon-left">
                                    <label class="head_bol"><?php _e("Discount by No. Children",ST_TEXTDOMAIN) ?>:</label>
                                </div>
                            </div>
                            <?php
                            $discount_by_child_title = STInput::request('discount_by_child_title');
                            $discount_by_child_key = STInput::request('discount_by_child_key');
                            $discount_by_child_value = STInput::request('discount_by_adult_value');
                            ?>
                            <div class="" id="data_discount_by_child">
                                <?php
                                if(!empty($discount_by_child_title)){
                                    foreach($discount_by_child_title as $k=>$v){
                                        if(!empty($v) and !empty($discount_by_child_key[ $k ]) and !empty($discount_by_child_value[$k])) {
                                            ?>
                                            <div class="item">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="discount_by_child_title"><?php _e( "Title" , ST_TEXTDOMAIN ) ?></label>
                                                        <input id="" name="discount_by_child_title[]" type="text"
                                                               class="form-control"
                                                               value="<?php echo esc_attr( $v ) ?>" placeholder="<?php _e( "Title" , ST_TEXTDOMAIN ) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="discount_by_child_key"><?php _e( "Key Number" , ST_TEXTDOMAIN ) ?></label>
                                                        <input id="" name="discount_by_child_key[]" type="text"
                                                               class="form-control"
                                                               value="<?php echo esc_attr( $discount_by_child_key[$k] ) ?>" placeholder="<?php _e( "Key Number" , ST_TEXTDOMAIN ) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="discount_by_child_value"><?php _e( "Percentage of Discount" , ST_TEXTDOMAIN ) ?></label>
                                                        <input id="" name="discount_by_child_value[]" type="text"
                                                               class="form-control"
                                                               value="<?php echo esc_attr( $discount_by_child_value[$k] ) ?>" placeholder="<?php _e( "Percentage of Discount" , ST_TEXTDOMAIN ) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group form-group-icon-left">
                                                        <div class="btn btn-danger btn_del_program"
                                                             style="margin-top: 27px">
                                                            X
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                            <div class="col-md-12 div_btn_add_custom">
                                <div class="form-group form-group-icon-left">
                                    <button id="btn_discount_by_child" type="button" class="btn btn-info btn-sm"><?php _e("Add New",ST_TEXTDOMAIN) ?></button><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="extra"><?php _e("Extra",ST_TEXTDOMAIN) ?>:</label>
                        </div>
                    </div>
                    <div class="content_extra_price">
                        <?php
                        $extra = isset($_POST['extra']) ? $_POST['extra'] : '';
                        if(isset($extra['title']) && count($extra['title'])):
                            foreach($extra['title'] as $key => $val):
                                ?>
                                <div class="item">
                                    <div class="col-xs-12 col-sm-3">
                                        <div class="form-group form-group-icon-left">

                                            <label for="extra_title"><?php _e("Title",ST_TEXTDOMAIN) ?></label>
                                            <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                            <input value="<?php echo esc_html($val); ?>" id="extra_title" data-date-format="yyyy-mm-dd" name="extra[title][]" type="text" placeholder="<?php _e("Title",ST_TEXTDOMAIN) ?>" class="form-control date-pick">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-3">
                                        <div class="form-group form-group-icon-left">

                                            <label for="extra_name"><?php _e("Name",ST_TEXTDOMAIN) ?></label>
                                            <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                            <input value="<?php echo esc_html($extra['extra_name'][$key]); ?>" id="extra_name" data-date-format="yyyy-mm-dd" name="extra[extra_name][]" type="text" placeholder="<?php _e("Name",ST_TEXTDOMAIN) ?>" class="form-control date-pick" value="">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-3">
                                        <div class="form-group form-group-icon-left">

                                            <label for="extra_max_number"><?php _e("Max No. People. Leave empty or enter '0' for unlimited",ST_TEXTDOMAIN) ?></label>
                                            <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                            <input value="<?php echo esc_html($extra['extra_max_number'][$key]); ?>" id="extra_max_number" data-date-format="yyyy-mm-dd" name="extra[extra_max_number][]" type="text" placeholder="<?php _e("Max of number",ST_TEXTDOMAIN) ?>" class="form-control date-pick">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-2">
                                        <div class="form-group form-group-icon-left">

                                            <label for="extra_price"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                                            <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                            <input value="<?php echo esc_html($extra['extra_price'][$key]); ?>" id="extra_price" data-date-format="yyyy-mm-dd" name="extra[extra_price][]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="form-control date-pick">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group form-group-icon-left">
                                            <div class="btn btn-danger btn_del_extra_price" style="margin-top: 27px">
                                                X
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; endif; ?>
                    </div>
                    <div class="col-md-12 div_btn_add_custom">
                        <div class="form-group form-group-icon-left">
                            <button id="btn_add_extra_price" class="btn btn-info btn-sm" type="button"><?php _e("Add Extra",ST_TEXTDOMAIN) ?></button>
                            <br>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="discount"><?php _e("Discount Rate",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-star input-icon input-icon-hightlight"></i>
                            <input id="discount" name="discount" type="text" placeholder="<?php _e("Discount rate (%)",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('discount') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('discount'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="is_sale_schedule"><?php _e("Sale Schedule",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $is_sale_schedule = STInput::request('is_sale_schedule'); ?>
                            <select class="form-control is_sale_schedule" name="is_sale_schedule" id="is_sale_schedule">
                                <option value="on" <?php if($is_sale_schedule == 'on') echo 'selected'; ?>><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                <option value="off" <?php if($is_sale_schedule == 'off') echo 'selected'; ?>><?php _e("No",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="data_is_sale_schedule">
                        <div class="col-md-6 clear input-daterange">
                            <div class="form-group form-group-icon-left" >
                                
                                <label for="sale_price_from"><?php _e("Sale Start Date",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                <input name="sale_price_from" class="date-pick form-control st_date_start" placeholder="<?php _e("Sale Start Date",ST_TEXTDOMAIN) ?>" data-date-format="yyyy-mm-dd" type="text" value="<?php echo STInput::request('sale_price_from') ?>"/>
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('sale_price_from'),'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left" >
                                
                                <label for="sale_price_to"><?php _e("Sale End Date",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                <input name="sale_price_to" class="date-pick form-control st_date_end" placeholder="<?php _e("Sale End Date",ST_TEXTDOMAIN) ?>" data-date-format="yyyy-mm-dd" type="text" value="<?php echo STInput::request('sale_price_to') ?>" />
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('sale_price_to'),'danger') ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 clear">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="deposit_payment_status"><?php _e("Deposit Payment Options",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $deposit_payment_status = STInput::request('deposit_payment_status') ?>
                            <select class="form-control deposit_payment_status" name="deposit_payment_status" id="deposit_payment_status">
                                <option value=""><?php _e("Disallow Deposit",ST_TEXTDOMAIN) ?></option>
                                <option value="percent" <?php if($deposit_payment_status == 'percent') echo 'selected' ?>><?php _e("Deposit By Percent",ST_TEXTDOMAIN) ?></option>
                                <option value="amount" <?php if($deposit_payment_status == 'amount') echo 'selected' ?>><?php _e("Deposit By Amount",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 data_deposit_payment_status">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="deposit_payment_amount"><?php _e("Deposit Amount",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs  input-icon input-icon-hightlight"></i>
                            <input id="deposit_payment_amount" name="deposit_payment_amount" type="text" placeholder="<?php _e("Deposit Amount",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('deposit_payment_amount') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('deposit_payment_amount'),'danger') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-info">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="tour_type"><?php _e("Tour Type",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa  fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $tour_type = STInput::request('tour_type')?>
                            <select id="tour_type" name="tour_type" class="form-control">
                                <option  value="specific_date" <?php if($tour_type == 'specific_date') echo 'selected'; ?>><?php _e("Specific Date",ST_TEXTDOMAIN )?></option>
                                <option  value="daily_tour" <?php if($tour_type == 'daily_tour') echo 'selected'; ?>><?php _e("Daily Tour",ST_TEXTDOMAIN )?></option>
                            </select>
                            <div class="st_msg console_msg_tour_type"></div>
                        </div>
                    </div>
                    <div class="col-md-6 data_duration">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="duration"><?php _e("Duration",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-star input-icon input-icon-hightlight"></i>
                            <input id="duration" name="duration" type="text" placeholder="<?php _e("Duration",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('duration') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('duration'),'danger') ?></div>
                        </div>
                    </div>
                </div>
                <!-- <div class="row data_specific_date">
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="check_in"><?php _e("Departure Date",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                            <input id="check_in" name="check_in" type="text" class="form-control date-pick" placeholder="<?php _e("Departure date",ST_TEXTDOMAIN) ?>" value="<?php echo STInput::request('check_in') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('check_in'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="check_out"><?php _e("Arrive Date",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                            <input id="check_out" name="check_out" type="text"  class="form-control date-pick" placeholder="<?php _e("Arrive date",ST_TEXTDOMAIN) ?>" value="<?php echo STInput::request('check_out') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('check_out'),'danger') ?></div>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="row data_duration">
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="duration_unit"><?php _e("Duration Unit",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa  fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $duration_unit = STInput::request('duration_unit') ?>
                            <select id="duration_unit" name="duration_unit" class="form-control">
                                <option  value="day" <?php if($duration_unit == 'day') echo 'selected="selected"'; ?>><?php _e("Days",ST_TEXTDOMAIN )?></option>
                                <option  value="hour" <?php if($duration_unit == 'hour') echo 'selected="selected"'; ?>><?php _e("Hours",ST_TEXTDOMAIN )?></option>
                                <option  value="hour" <?php if($duration_unit == 'week') echo 'selected="selected"'; ?>><?php _e("Weeks",ST_TEXTDOMAIN )?></option>
                                <option  value="hour" <?php if($duration_unit == 'month') echo 'selected="selected"'; ?>><?php _e("Months",ST_TEXTDOMAIN )?></option>
                            </select>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class='col-md-6'>
                        <div class="form-group">
                            <label for="tours_booking_period"><?php _e("Booking Period",ST_TEXTDOMAIN) ?>:</label>
                            <input id="tours_booking_period" name="tours_booking_period" type="text" min="0" placeholder="<?php _e("Booking Period (day)",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('tours_booking_period',"0") ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('tours_booking_period'),'danger') ?></div>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class="form-group">
                            <label for="min_people"><?php _e("Min No. People",ST_TEXTDOMAIN) ?>:</label>
                            <input id="min_people" name="min_people" type="text" min="1" placeholder="<?php _e("Min No. People",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('min_people') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('min_people'),'danger') ?></div>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class="form-group">
                            <label for="max_people"><?php _e("Max No. People",ST_TEXTDOMAIN) ?>:</label>
                            <input id="max_people" name="max_people" type="text" min="1" placeholder="<?php _e("Max No. People",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('max_people') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('max_people'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="st_tour_external_booking"><?php _e("External Booking",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $external_booking = STInput::request('st_tour_external_booking') ?>
                            <select class="form-control st_tour_external_booking" name="st_tour_external_booking" id="st_tour_external_booking">
                                <option value="off" <?php if($external_booking == 'off') echo 'selected'; ?> ><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                <option value="on" <?php if($external_booking == 'on') echo 'selected'; ?> ><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class='col-md-6 data_st_tour_external_booking'>
                        <div class="form-group form-group-icon-left">
                            
                            <label for="st_tour_external_booking_link"><?php _e("External Booking URL",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-link  input-icon input-icon-hightlight"></i>
                            <input id="st_tour_external_booking_link" name="st_tour_external_booking_link" type="text" placeholder="<?php _e("Eg: https://domain.com") ?>" class="form-control" value="<?php echo STInput::request('st_tour_external_booking_link') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_tour_external_booking_link'),'danger') ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="tour_program"><?php _e("Tour's Program",ST_TEXTDOMAIN) ?>:</label>
                        </div>
                    </div>
                    <div class="" id="data_program">
                        <?php
                        $program_title = STInput::request('program_title');
                        $program_desc = STInput::request('program_desc');
                        ?>
                        <div class=""  id="data_program">
                            <?php
                            if(!empty($program_title)){
                                foreach($program_title as $k=>$v){
                                    if(!empty($v) and !empty($program_desc[ $k ])) {
                                        ?>
                                        <div class="item">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="program_title"><?php st_the_language( 'user_create_tour_program_title' ) ?></label>
                                                    <input id="title" name="program_title[]" type="text"
                                                           class="form-control" value="<?php echo esc_html( $v ) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label for="program_desc"><?php st_the_language( 'user_create_tour_program_desc' ) ?></label>
                                                    <textarea name="program_desc[]"
                                                              class="form-control h_35"><?php echo stripslashes( $program_desc[ $k ] ) ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group form-group-icon-left">
                                                    <div class="btn btn-danger btn_del_program"
                                                         style="margin-top: 27px">
                                                        X
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-12 div_btn_add_custom">
                        <div class="form-group form-group-icon-left">
                            <button id="btn_add_program" type="button" class="btn btn-info btn-sm"><?php st_the_language('user_create_tour_add_program') ?></button><br>
                        </div>
                    </div>
                </div>

            </div>
			<?php echo st()->load_template('user/tabs/cancel-booking',FALSE,array('validator'=>$validator)) ?>
            <div class="tab-pane fade" id="tab-payment">
                <?php
                $data_paypment = STPaymentGateways::get_payment_gateways();
                if (!empty($data_paypment) and is_array($data_paypment)) {
                    foreach( $data_paypment as $k => $v ) {
                        $is_enable  = (st()->get_option('pm_gway_'.$k.'_enable'));
                        if ($is_enable =='off') {}else   {
                        ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-icon-left">
                                    
                                    <label for="is_meta_payment_gateway_<?php echo esc_attr($k) ?>"><?php echo esc_html($v->get_name()) ?>:</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $is_pay = STInput::request("is_meta_payment_gateway_".$k) ?>
                                    <select class="form-control" name="is_meta_payment_gateway_<?php echo esc_attr($k) ?>" id="is_meta_payment_gateway_<?php echo esc_attr($k) ?>">
                                        <option <?php if($is_pay == 'on') echo "selected"; ?> value="on"><?php _e( "Yes" , ST_TEXTDOMAIN ) ?></option>
                                        <option <?php if($is_pay == 'off') echo "selected"; ?> value="off"><?php _e( "No" , ST_TEXTDOMAIN ) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php
                    }}
                }
                ?>
            </div>
            <div class="tab-pane fade" id="tab-custom-fields">
                <?php
                $custom_field = st()->get_option( 'tours_unlimited_custom_field' );
                if(!empty( $custom_field ) and is_array( $custom_field )) {
                    ?>
                    <div class="row">
                        <?php
                        foreach( $custom_field as $k => $v ) {
                            $key   = str_ireplace( '-' , '_' , 'st_custom_' . sanitize_title( $v[ 'title' ] ) );
                            $class = 'col-md-12';
                            if($v[ 'type_field' ] == "date-picker") {
                                $class = 'col-md-6';
                            }
                            ?>
                            <div class="<?php echo esc_attr( $class ) ?>">
                                <div class="form-group">
                                    <label for="<?php echo esc_attr( $key ) ?>"><?php echo esc_html($v[ 'title' ]) ?>:</label>
                                    <?php if($v[ 'type_field' ] == "text") { ?>
                                        <input id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" type="text"
                                               placeholder="<?php echo esc_html($v[ 'title' ]) ?>" class="form-control" value="<?php echo STInput::request($key) ?>">
                                    <?php } ?>
                                    <?php if($v[ 'type_field' ] == "date-picker") { ?>
                                        <input id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" type="text"
                                               placeholder="<?php echo esc_html($v[ 'title' ]) ?>"
                                               class="date-pick form-control" value="<?php echo STInput::request($key) ?>">
                                    <?php } ?>
                                    <?php if($v[ 'type_field' ] == "textarea") { ?>
                                        <textarea id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" class="form-control" ><?php echo STInput::request($key) ?></textarea>
                                    <?php } ?>

                                    <div class="st_msg console_msg_"></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="text-center div_btn_submit">
        <input  type="hidden" id=""  class="" name="action_partner" value="add_partner">
        <input name="btn_insert_post_type_tours" id="btn_insert_post_type_tours" type="submit" disabled class="btn btn-primary btn-lg"  value="<?php _e("SUBMIT TOUR",ST_TEXTDOMAIN) ?>">
    </div>
</form>
<div id="html_program" style="display: none">
    <div class="item">
        <div class="col-md-4">
            <div class="form-group">
                <label for="title"><?php st_the_language('user_create_tour_program_title') ?></label>
                <input id="title" name="program_title[]" type="text" class="form-control" placeholder="<?php st_the_language('user_create_tour_program_title') ?>">
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group">
                <label for="program_desc"><?php st_the_language('user_create_tour_program_desc') ?></label>
                <textarea name="program_desc[]" class="form-control " placeholder="<?php st_the_language('user_create_tour_program_desc') ?>"></textarea>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group form-group-icon-left">
                <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                    X
                </div>
            </div>
        </div>
    </div>
</div>
<div id="html_discount_by_adult" style="display: none">
    <div class="item">
        <div class="col-md-4">
            <div class="form-group">
                <label for="discount_by_adult_title"><?php _e("Title",ST_TEXTDOMAIN) ?></label>
                <input id="" name="discount_by_adult_title[]" type="text" class="form-control" placeholder="<?php _e("Title",ST_TEXTDOMAIN) ?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="discount_by_adult_key"><?php _e("Key Number",ST_TEXTDOMAIN) ?></label>
                <input id="" name="discount_by_adult_key[]" type="text" class="form-control" placeholder="<?php _e("Key Number",ST_TEXTDOMAIN) ?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="discount_by_adult_value"><?php _e("Percentage of Discount",ST_TEXTDOMAIN) ?></label>
                <input id="" name="discount_by_adult_value[]" type="text" class="form-control" placeholder="<?php _e("Percentage of Discount",ST_TEXTDOMAIN) ?>">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group form-group-icon-left">
                <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                    X
                </div>
            </div>
        </div>
    </div>
</div>
<div id="html_discount_by_child" style="display: none">
    <div class="item">
        <div class="col-md-4">
            <div class="form-group">
                <label for="discount_by_child_title"><?php _e("Title",ST_TEXTDOMAIN) ?></label>
                <input id="" name="discount_by_child_title[]" type="text" class="form-control" placeholder="<?php _e("Title",ST_TEXTDOMAIN) ?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="discount_by_child_key"><?php _e("Key Number",ST_TEXTDOMAIN) ?></label>
                <input id="" name="discount_by_child_key[]" type="text" class="form-control" placeholder="<?php _e("Key Number",ST_TEXTDOMAIN) ?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="discount_by_child_value"><?php _e("Percentage of Discount",ST_TEXTDOMAIN) ?></label>
                <input id="" name="discount_by_child_value[]" type="text" class="form-control" placeholder="<?php _e("Percentage of Discount",ST_TEXTDOMAIN) ?>">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group form-group-icon-left">
                <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                    X
                </div>
            </div>
        </div>
    </div>
</div>
<div class="data-extra-price-html" style="display: none">
    <div class="item">
        <div class="col-xs-12 col-sm-3">
            <div class="form-group form-group-icon-left">

                <label for="extra_title"><?php _e("Title",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                <input id="extra_title" data-date-format="yyyy-mm-dd" name="extra[title][]" type="text" placeholder="<?php _e("Title",ST_TEXTDOMAIN) ?>" class="form-control date-pick">
            </div>
        </div>
        <div class="col-xs-12 col-sm-3">
            <div class="form-group form-group-icon-left">

                <label for="extra_name"><?php _e("Name",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                <input id="extra_name" data-date-format="yyyy-mm-dd" name="extra[extra_name][]" type="text" placeholder="<?php _e("Name",ST_TEXTDOMAIN) ?>" class="form-control date-pick" value="">
            </div>
        </div>
        <div class="col-xs-12 col-sm-3">
            <div class="form-group form-group-icon-left">

                <label for="extra_max_number"><?php _e("Max Of Number",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                <input id="extra_max_number" data-date-format="yyyy-mm-dd" name="extra[extra_max_number][]" type="text" placeholder="<?php _e("Max of number",ST_TEXTDOMAIN) ?>" class="form-control date-pick">
            </div>
        </div>
        <div class="col-xs-12 col-sm-2">
            <div class="form-group form-group-icon-left">

                <label for="extra_price"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                <input id="extra_price" data-date-format="yyyy-mm-dd" name="extra[extra_price][]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="form-control date-pick">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group form-group-icon-left">
                <div class="btn btn-danger btn_del_extra_price" style="margin-top: 27px">
                    X
                </div>
            </div>
        </div>
    </div>
</div>