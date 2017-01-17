<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User create rental
 *
 * Created by ShineTheme
 *
 */
/*if( STUser_f::st_check_edit_partner(STInput::request('id')) == false ){
    return false;
}*/
$post_id = STInput::request('id');
$title = $content = $excerpt = "";
if(!empty($post_id)){
    $post = get_post( $post_id );
    $title = $post->post_title;
    $content = $post->post_content;
    $excerpt = $post->post_excerpt;
}
$validator= STUser_f::$validator;
?>
<div class="st-create">
    <h2 class="pull-left">
        <?php if(!empty($post_id)){?>
            <?php _e("Edit Rental",ST_TEXTDOMAIN) ?>
        <?php }else{ ?>
            <?php _e("Add Rental",ST_TEXTDOMAIN) ?>
        <?php } ?>
    </h2>
    <?php if(!empty($post_id)){  ?>
        <a target="_blank" href="<?php echo get_the_permalink($post_id) ?>" class="btn btn-default pull-right"><?php _e("Preview",ST_TEXTDOMAIN) ?></a>
    <?php }else{ ?>
        <span class="btn btn-default pull-right btn_save_and_preview"><?php _e("Save & Preview",ST_TEXTDOMAIN) ?></span>
    <?php } ?>
</div>
<div class="msg">
    <?php echo STTemplate::message() ?>
    <?php echo STUser_f::get_msg(); ?>
    <?php echo STUser_f::get_control_data(); ?>
</div>
<form action="" method="post" enctype="multipart/form-data" id="st_form_add_partner">
    <?php if(!empty($post_id)){?>
        <?php wp_nonce_field('user_setting','st_update_post_rental'); ?>
    <?php }else{ ?>
        <?php wp_nonce_field('user_setting','st_insert_post_rental'); ?>
    <?php } ?>
    <div class="form-group form-group-icon-left">
        
        <label for="title" class="head_bol"><?php echo __('Name of Rental', ST_TEXTDOMAIN); ?>:</label>
        <i class="fa  fa-file-text input-icon input-icon-hightlight"></i>
        <input id="title" name="st_title" type="text" placeholder="<?php echo __('Name of Rental', ST_TEXTDOMAIN); ?>" class="form-control" value="<?php echo STInput::request("st_title",$title) ?>">
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_title'),'danger') ?></div>
    </div>
    <div  class="form-group form-group-icon-left">
        <label for="st_content" class="head_bol"><?php st_the_language('user_create_rental_content') ?>:</label>
        <?php wp_editor( STInput::request("st_content",$content) ,'st_content'); ?>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_content'),'danger') ?></div>
    </div>
    <div class="form-group">
        <label for="desc" class="head_bol"><?php _e("Rental Description",ST_TEXTDOMAIN) ?>:</label>
        <textarea id="desc" rows="6" name="st_desc" class="form-control"><?php echo STInput::request("st_desc",$excerpt) ?></textarea>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_desc'),'danger') ?></div>
    </div>
    <div class="form-group form-group-icon-left">
        <label for="id_featured_image" class="head_bol"><?php _e("Featured Image",ST_TEXTDOMAIN) ?>:</label>
        <?php

        if(!empty($post_id)){
            $id_img = get_post_thumbnail_id($post_id);
        }else{
            $id_img = STInput::request('id_featured_image');
        }
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
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('featured_image'),'danger') ?></div>
    </div>

    <div class="tabbable tabs_partner">
        <ul class="nav nav-tabs" id="">
            <li class="active"><a href="#tab-location-setting" data-toggle="tab"><?php _e("Location Settings",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-rental-info" data-toggle="tab"><?php _e("Rental Informations",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-agent-info" data-toggle="tab"><?php _e("Agent Informations",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-rental-price" data-toggle="tab"><?php _e("Rental Prices",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-rental-option" data-toggle="tab"><?php _e("Rental Options",ST_TEXTDOMAIN) ?></a></li>
			<li><a href="#tab-cancel-booking" data-toggle="tab"><?php _e('Cancel Booking',ST_TEXTDOMAIN) ?></a></li>
            <?php $st_is_woocommerce_checkout=apply_filters('st_is_woocommerce_checkout',false);
            if(!$st_is_woocommerce_checkout):?>
                <li><a href="#tab-payment" data-toggle="tab"><?php _e("Payment Settings",ST_TEXTDOMAIN) ?></a></li>
            <?php endif ?>
            <?php $custom_field = st()->get_option( 'rental_unlimited_custom_field' );
            if(!empty( $custom_field ) and is_array( $custom_field )) { ?>
                <li><a href="#tab-custom-fields" data-toggle="tab"><?php _e("Custom Fields",ST_TEXTDOMAIN) ?></a></li>
            <?php } ?>
            <?php if(!empty($post_id)){ ?>
                <li><a href="#availablility_tab" data-toggle="tab"><?php _e("Availability",ST_TEXTDOMAIN) ?></a></li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="tab-location-setting">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="multi_location"><?php st_the_language( 'user_create_car_location' ) ?>:</label>
                            <div id="setting_multi_location" class="location-front">
                                <?php 
                                    $html_location = TravelHelper::treeLocationHtml();
                                    $post_id = STInput::request('id','');
                                    
                                    $multi_location = get_post_meta( $post_id, 'multi_location', true );
                                    if( !empty( $multi_location ) && !is_array( $multi_location ) ){
                                        $multi_location = explode(',', $multi_location);
                                    }
                                    if( empty( $multi_location ) ){
                                        $multi_location = array('');
                                    }
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
                                                    <input <?php if(in_array('_'.$location['ID'].'_', $multi_location)) echo 'checked'; ?>  id="<?php echo 'location-'.$location['ID']; ?>" type="checkbox" name="multi_location[]" value="<?php echo '_'.$location['ID'].'_'; ?>">
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
                            
                            <label for="address"><?php st_the_language( 'user_create_car_address' ) ?>:</label>
                            <i class="fa fa-home input-icon input-icon-hightlight"></i>
                            <input id="address" name="address" type="text"
                                   placeholder="<?php st_the_language( 'user_create_car_address' ) ?>" class="form-control" value="<?php echo STInput::request("address",get_post_meta( $post_id , 'address' , true)); ?>" >

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
                            <?php $enable_street_views_google_map  = STInput::request('enable_street_views_google_map',get_post_meta($post_id,'enable_street_views_google_map',true)) ?>
                            <select class='form-control' name='enable_street_views_google_map' id="enable_street_views_google_map">
                                <option value='on' <?php if($enable_street_views_google_map == 'on') echo 'selected'; ?> ><?php _e("On",ST_TEXTDOMAIN) ?></option>
                                <option value='off' <?php if($enable_street_views_google_map == 'off') echo 'selected'; ?> ><?php _e("Off",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-rental-info">
                <div class="row">
                    <?php
                    $taxonomies = (get_object_taxonomies('st_rental'));
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
                                                if(STUser_f::st_check_post_term_partner( $post_id  ,$value , $k) == true ){
                                                    $check = 'checked';
                                                }
                                                ?>
                                                <div class="col-md-3">
                                                    <div class="checkbox-inline checkbox-stroke">
                                                        <label for="taxonomy">
                                                            <i class="<?php echo esc_html($icon) ?>"></i>
                                                            <input name="taxonomy[]" class="i-check item_tanoxomy" type="checkbox" <?php echo esc_html($check) ?> value="<?php echo esc_attr($k.','.$taxonomy_name) ?>" /><?php echo esc_html($v) ?>
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
                            
                            <label for="st_custom_layout"><?php _e( "Detail Rental Layout" , ST_TEXTDOMAIN ) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $layout = st_get_layout('st_rental');
                            if(!empty($layout) and is_array($layout)):
                                ?>
                                <select class='form-control' name='st_custom_layout' id="st_custom_layout">
                                    <?php
                                    $st_custom_layout = STInput::request('st_custom_layout',get_post_meta($post_id , 'custom_layout' , true));
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
                                <?php $is_featured = STInput::request('is_featured',get_post_meta($post_id,'is_featured',true)) ?>
                                <select class='form-control' name='is_featured' id="is_featured">
                                    <option value='off' <?php if($is_featured == 'off') echo 'selected'; ?> ><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                    <option value='on'  <?php if($is_featured == 'on') echo 'selected'; ?> ><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                </select>
                            <?php }; ?>
                        </div>
                    </div>
                    <div class="col-md-6 clear">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="rental_number"><?php _e("Numbers",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa  fa-cogs input-icon input-icon-hightlight"></i>
                            <input id="rental_number" name="rental_number" type="text" placeholder="<?php _e("Number of rental available for booking",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('rental_number',get_post_meta($post_id,'rental_number',true)) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rental_number'),'danger') ?></div>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class="form-group form-group-icon-left">
                            
                            <label for="rental_max_adult"><?php echo __("Max of Adult",ST_TEXTDOMAIN);?>:</label>
                            <i class="fa fa-plus-square input-icon input-icon-hightlight"></i>
                            <input id="" name="rental_max_adult" type="text" placeholder="1" class="form-control number" value="<?php echo STInput::request('rental_max_adult',get_post_meta($post_id,'rental_max_adult',true)) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rental_max_adult'),'danger') ?></div>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class="form-group form-group-icon-left">
                            
                            <label for="rental_max_children"><?php echo __("Max of Children",ST_TEXTDOMAIN);?>:</label>
                            <i class="fa fa-plus-square input-icon input-icon-hightlight"></i>
                            <input id="" name="rental_max_children" type="text" placeholder="1" class="form-control number" value="<?php echo STInput::request('rental_max_children',get_post_meta($post_id,'rental_max_children',true)) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rental_max_children'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="video"><?php st_the_language('user_create_rental_video') ?>:</label>
                            <i class="fa  fa-youtube-play input-icon input-icon-hightlight"></i>
                            <input id="video" name="video" type="text"  placeholder="<?php _e("Enter Youtube or Vimeo video link (Eg: https://www.youtube.com/watch?v=JL-pGPVQ1a8)") ?>" class="form-control" value="<?php echo STInput::request('video',get_post_meta($post_id,'video',true)) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('video'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="gallery"><?php _e( "Gallery" , ST_TEXTDOMAIN ) ?>:</label>
                            <?php
                            if(!empty($post_id)){
                                $id_img = get_post_meta($post_id , 'gallery',true);
                            }else{
                                $id_img = STInput::request('id_gallery');
                            }
                            ?>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <span class="btn btn-primary btn-file">
                                        <?php _e( "Browse…" , ST_TEXTDOMAIN ) ?> <input name="gallery[]" id="gallery" multiple type="file">
                                    </span>
                                </span>
                                <input type="text" readonly="" value="<?php echo esc_html( $id_img ) ?>"
                                       class="form-control data_lable">
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
            <div class="tab-pane fade" id="tab-agent-info">
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
										printf('<option value="%s" %s >%s</option>',$s,selected(STInput::request('show_agent_contact_info',get_post_meta($post_id,'show_agent_contact_info',true)),$s,FALSE),$v);
									}
								}
								?>
							</select>
							<div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('show_agent_contact_info'),'danger') ?></div>
						</div>
					</div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="email"><?php st_the_language('user_create_rental_email') ?>:</label>
                            <i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
                            <input id="email" name="email" type="email" placeholder="<?php st_the_language('user_create_rental_email') ?>" class="form-control" value="<?php echo STInput::request('agent_email',get_post_meta($post_id,'agent_email',true)) ?>">
                            <i class="placeholder"><?php _e("E-mail of Rental Agent which will receive email when new booking is recorded",ST_TEXTDOMAIN) ?></i>
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('email'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 clear">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="website"><?php st_the_language('user_create_rental_website') ?>:</label>
                            <i class="fa fa-link input-icon input-icon-hightlight"></i>
                            <input id="website" name="website" type="text" placeholder="<?php st_the_language('user_create_rental_website') ?>" class="form-control" value="<?php echo STInput::request('agent_website',get_post_meta($post_id,'agent_website',true)) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('website'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="phone"><?php st_the_language('user_create_rental_phone') ?>:</label>
                            <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                            <input id="phone" name="phone" type="text" placeholder="<?php st_the_language('user_create_rental_phone') ?>" class="form-control" value="<?php echo STInput::request('agent_phone',get_post_meta($post_id,'agent_phone',true)) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('phone'),'danger') ?></div>
                        </div>
                    </div>
					<div class="col-md-6 clear">
						<div class="form-group form-group-icon-left">

							<label for="st_fax"><?php _e('Fax Number',ST_TEXTDOMAIN) ?>:</label>
							<i class="fa  fa-phone input-icon input-icon-hightlight"></i>
							<input id="st_fax" name="st_fax" type="text" placeholder="<?php _e('Fax Number',ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('st_fax',get_post_meta($post_id,'st_fax',true)) ?>">
							<div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_fax'),'danger') ?></div>
						</div>
					</div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-rental-price">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="price"><?php st_the_language('user_create_rental_price') ?>:</label>
                            <i class="fa fa-money input-icon input-icon-hightlight"></i>
                            <input id="price" name="price" type="text" placeholder="<?php st_the_language('user_create_rental_price') ?>" class="form-control number" value="<?php echo STInput::request('price',get_post_meta($post_id,'price',true)) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('price'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="discount"><?php _e("Discount Rate",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-star input-icon input-icon-hightlight"></i>
                            <input id="discount" name="discount" type="text" placeholder="<?php _e("Discount Rate (%)",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('discount', get_post_meta($post_id,'discount_rate',true)) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('discount'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="is_sale_schedule"><?php _e("Sale Schedule",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $is_sale_schedule = STInput::request('is_sale_schedule',get_post_meta($post_id,'is_sale_schedule',true)) ?>
                            <select class="form-control is_sale_schedule" name="is_sale_schedule" id="is_sale_schedule">
                                <option value="on" <?php if($is_sale_schedule == 'on') echo 'selected'; ?>><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                <option value="off" <?php if($is_sale_schedule == 'off') echo 'selected'; ?>><?php _e("No",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="data_is_sale_schedule">
                        <div class="col-md-6 input-daterange">
                            <div class="form-group form-group-icon-left" >
                                
                                <label for="sale_price_from"><?php _e("Sale Start Date",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                <input name="sale_price_from" class="date-pick form-control st_date_start" data-date-format="yyyy-mm-dd" type="text" value="<?php echo STInput::request('sale_price_from',get_post_meta($post_id,'sale_price_from',true)) ?>"/>
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('sale_price_from'),'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left" >
                                
                                <label for="sale_price_to"><?php _e("Sale End Date",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                <input name="sale_price_to" class="date-pick form-control st_date_end" data-date-format="yyyy-mm-dd" type="text" value="<?php echo STInput::request('sale_price_to',get_post_meta($post_id,'sale_price_to',true)) ?>" />
                                <div class="st_msg console_sale_price_to"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="deposit_payment_status"><?php _e("Deposit Payment Options",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $deposit_payment_status = STInput::request('deposit_payment_status',get_post_meta($post_id ,'deposit_payment_status',true) )?>
                            <select class="form-control deposit_payment_status" name="deposit_payment_status" id="deposit_payment_status">
                                <option value=""><?php _e("Disallow Deposit",ST_TEXTDOMAIN) ?></option>
                                <option value="percent" <?php if($deposit_payment_status == 'percent') echo 'selected' ?>><?php _e("Deposit By Percent",ST_TEXTDOMAIN) ?></option>
                                <option value="amount" <?php if($deposit_payment_status == 'amount') echo 'selected' ?>><?php _e("Deposit By Amount",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 data_deposit_payment_status">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="deposit_payment_amount"><?php _e("Deposit Payment Amount",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs  input-icon input-icon-hightlight"></i>
                            <input id="deposit_payment_amount" name="deposit_payment_amount" type="text" placeholder="<?php _e("Deposit payment amount",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('deposit_payment_amount',get_post_meta($post_id,'deposit_payment_amount',true)) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('deposit_payment_amount'),'danger') ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="extra"><?php _e("Extra",ST_TEXTDOMAIN) ?>:</label>
                        </div>
                    </div>
                    <div class="content_extra_price">

                        <?php if(!empty($post_id)){ ?>
                            <?php
                            $extra = get_post_meta($post_id, 'extra_price', true);
                            if(!empty($extra) and is_array($extra)):
                                foreach($extra as $key => $val):
                                    ?>
                                    <div class="item">
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="form-group form-group-icon-left">

                                                <label for="extra_title"><?php _e("Title",ST_TEXTDOMAIN) ?></label>
                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                <input value="<?php echo esc_html($val['title']); ?>" id="extra_title"  name="extra[title][]" type="text" placeholder="<?php _e("Title",ST_TEXTDOMAIN) ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="form-group form-group-icon-left">

                                                <label for="extra_name"><?php _e("Name",ST_TEXTDOMAIN) ?></label>
                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                <input value="<?php echo esc_html($val['extra_name']); ?>" id="extra_name"  name="extra[extra_name][]" type="text" placeholder="<?php _e("Name",ST_TEXTDOMAIN) ?>" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="form-group form-group-icon-left">

                                                <label for="extra_max_number"><?php _e("Max Of Number",ST_TEXTDOMAIN) ?></label>
                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                <input value="<?php echo esc_html($val['extra_max_number']); ?>" id="extra_max_number" name="extra[extra_max_number][]" type="text" placeholder="<?php _e("Max of number",ST_TEXTDOMAIN) ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-2">
                                            <div class="form-group form-group-icon-left">

                                                <label for="extra_price"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                <input value="<?php echo esc_html($val['extra_price']); ?>" id="extra_price" name="extra[extra_price][]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="form-control">
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
                        <?php }else{ ?>
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
                                                <input value="<?php echo esc_html($val); ?>" id="extra_title" name="extra[title][]" type="text" placeholder="<?php _e("Title",ST_TEXTDOMAIN) ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="form-group form-group-icon-left">

                                                <label for="extra_name"><?php _e("Name",ST_TEXTDOMAIN) ?></label>
                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                <input value="<?php echo esc_html($extra['extra_name'][$key]); ?>" id="extra_name"  name="extra[extra_name][]" type="text" placeholder="<?php _e("Name",ST_TEXTDOMAIN) ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="form-group form-group-icon-left">

                                                <label for="extra_max_number"><?php _e("Max Of Number",ST_TEXTDOMAIN) ?></label>
                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                <input value="<?php echo esc_html($extra['extra_max_number'][$key]); ?>" id="extra_max_number"  name="extra[extra_max_number][]" type="text" placeholder="<?php _e("Max of number",ST_TEXTDOMAIN) ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-2">
                                            <div class="form-group form-group-icon-left">

                                                <label for="extra_price"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                <input value="<?php echo esc_html($extra['extra_price'][$key]); ?>" id="extra_price" name="extra[extra_price][]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="form-control">
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
                        <?php } ?>

                    </div>
                    <div class="col-md-12 div_btn_add_custom">
                        <div class="form-group form-group-icon-left">
                            <button id="btn_add_extra_price" class="btn btn-info" type="button"><?php _e("Add Extra",ST_TEXTDOMAIN) ?></button>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-rental-option">
                <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <div class="form-group">
                            <label for=""><?php echo __('Allow booking full day', ST_TEXTDOMAIN); ?></label>
                            <?php $is_auto_caculate = STInput::request('allow_full_day',get_post_meta($post_id  ,'allow_full_day' ,true)); ?>
                            <select name="allow_full_day" id="allow_full_day" class="form-control">
                                <option <?php if($is_auto_caculate == "on") echo "selected"?> value="on"><?php echo __('On', ST_TEXTDOMAIN); ?></option>
                                <option <?php if($is_auto_caculate == "off") echo "selected"?> value="off"><?php echo __('Off', ST_TEXTDOMAIN); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class='col-md-12'>
                        <div class="form-group">
                            <label for="rentals_booking_period"><?php _e("Booking Period",ST_TEXTDOMAIN) ?>:</label>
                            <input id="rentals_booking_period" name="rentals_booking_period" type="number" min="0" placeholder="<?php _e("Booking Period (day)",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('rentals_booking_period',get_post_meta($post_id,'rentals_booking_period',true)) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rentals_booking_period'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="st_rental_external_booking"><?php _e("External Booking",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $st_car_external_booking = STInput::request('st_rental_external_booking',get_post_meta($post_id , 'st_rental_external_booking' , true)) ?>
                            <select class="form-control st_rental_external_booking" name="st_rental_external_booking" id="st_rental_external_booking">
                                <option value="off" <?php if($st_car_external_booking == 'off') echo 'selected'; ?>><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                <option value="on" <?php if($st_car_external_booking == 'on') echo 'selected'; ?>><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class='col-md-6 data_st_rental_external_booking'>
                        <div class="form-group form-group-icon-left">
                            
                            <label for="st_rental_external_booking_link"><?php _e("External Booking URL",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-link  input-icon input-icon-hightlight"></i>
                            <input id="st_rental_external_booking_link" name="st_rental_external_booking_link" type="text" placeholder="<?php _e("Eg: https://domain.com") ?>" class="form-control" value="<?php echo STInput::request('st_rental_external_booking_link',get_post_meta($post_id,'st_rental_external_booking_link',true)) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_rental_external_booking_link'),'danger') ?></div>
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
                        if ($is_enable =='off') {}else{
                        ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-icon-left">
                                    
                                    <label for="is_meta_payment_gateway_<?php echo esc_attr($k) ?>"><?php echo esc_html($v->get_name()) ?>:</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $is_pay = STInput::request('is_meta_payment_gateway_'.$k,get_post_meta($post_id , 'is_meta_payment_gateway_'.$k , true)) ?>
                                    <select class="form-control" name="is_meta_payment_gateway_<?php echo esc_attr($k) ?>" id="is_meta_payment_gateway_<?php echo esc_attr($k) ?>">
                                        <option value="on" <?php if($is_pay == 'on') echo 'selected' ?>><?php _e( "Yes" , ST_TEXTDOMAIN ) ?></option>
                                        <option value="off" <?php if($is_pay == 'off') echo 'selected' ?>><?php _e( "No" , ST_TEXTDOMAIN ) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php }
                    }
                }
                ?>
            </div>
            <div class="tab-pane fade" id="tab-custom-fields">
                <?php
                $custom_field = st()->get_option( 'rental_unlimited_custom_field' );
                if(!empty( $custom_field ) and is_array( $custom_field )) {
                    ?>
                    <div class="row">
                        <?php
                        foreach( $custom_field as $k => $v ) {
                            $key   = str_ireplace( '-' , '_' , 'st_custom_' . sanitize_title( $v[ 'title' ] ) );
                            $class = 'col-md-12';
                            if($v[ 'type_field' ] == "date-picker") {
                                $class = 'col-md-4';
                            }
                            ?>
                            <div class="<?php echo esc_attr( $class ) ?>">
                                <div class="form-group form-group-icon-left">
                                    <label for="<?php echo esc_attr( $key ) ?>"><?php echo esc_html($v[ 'title' ]) ?>:</label>
                                    <?php if($v[ 'type_field' ] == "text") { ?>
                                        <input id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" type="text"
                                               placeholder="<?php echo esc_html($v[ 'title' ]) ?>" class="form-control" value="<?php echo STInput::request($key,get_post_meta( $post_id , $key , true)) ?>">
                                    <?php } ?>
                                    <?php if($v[ 'type_field' ] == "date-picker") { ?>
                                        <input id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" type="text"
                                               placeholder="<?php echo esc_html($v[ 'title' ]) ?>"
                                               class="date-pick form-control" value="<?php echo STInput::request($key,get_post_meta( $post_id , $key , true)); ?>">
                                    <?php } ?>
                                    <?php if($v[ 'type_field' ] == "textarea") { ?>
                                        <textarea id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" class="form-control" ><?php echo STInput::request($key, get_post_meta( $post_id , $key , true)); ?></textarea>
                                    <?php } ?>

                                    <div class="st_msg console_msg_"></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <?php if(!empty($post_id)){ ?>
                <div class="tab-pane fade" id="availablility_tab">
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo st()->load_template('availability/form'); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="text-center div_btn_submit">
        <?php if(!empty($post_id)){?>
            <input  type="button" id="btn_check_insert_post_type_rental"  class="btn btn-primary btn-lg" value="<?php _e("UPDATE RENTAL",ST_TEXTDOMAIN) ?>">
            <input name="btn_update_post_type_rental" id="btn_insert_post_type_rental" type="submit"  class="btn btn-primary hidden btn_partner_submit_form" value="SUBMIT">
        <?php }else{ ?>
            <input  type="hidden"  class="save_and_preview" name="save_and_preview" value="false">
            <input  type="hidden" id=""  class="" name="action_partner" value="add_partner">
            <input name="btn_insert_post_type_rental" id="btn_insert_post_type_rental" type="submit" disabled class="btn btn-primary btn-lg btn_partner_submit_form" value="<?php _e("SUBMIT RENTAL",ST_TEXTDOMAIN) ?>">
        <?php } ?>


    </div>
</form>

<div class="data-extra-price-html" style="display: none">
    <div class="item">
        <div class="col-xs-12 col-sm-3">
            <div class="form-group form-group-icon-left">
                
                <label for="extra_title"><?php _e("Title",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                <input id="extra_title" data-date-format="yyyy-mm-dd" name="extra[title][]" type="text" placeholder="<?php _e("Title",ST_TEXTDOMAIN) ?>" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-3">
            <div class="form-group form-group-icon-left">
                
                <label for="extra_name"><?php _e("Name",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                <input id="extra_name" data-date-format="yyyy-mm-dd" name="extra[extra_name][]" type="text" placeholder="<?php _e("Name",ST_TEXTDOMAIN) ?>" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-3">
            <div class="form-group form-group-icon-left">
                
                <label for="extra_max_number"><?php _e("Max Of Number",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                <input id="extra_max_number" data-date-format="yyyy-mm-dd" name="extra[extra_max_number][]" type="text" placeholder="<?php _e("Max of number",ST_TEXTDOMAIN) ?>" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-2">
            <div class="form-group form-group-icon-left">
                
                <label for="extra_price"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                <input id="extra_price" data-date-format="yyyy-mm-dd" name="extra[extra_price][]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="form-control">
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
