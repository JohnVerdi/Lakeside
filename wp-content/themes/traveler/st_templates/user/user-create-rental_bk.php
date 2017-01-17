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
$validator= STUser_f::$validator;
?>
<div class="st-create">
    <h2><?php _e("Add new Rental",ST_TEXTDOMAIN) ?></h2>
</div>
<div class="msg">
    <?php echo STTemplate::message() ?>
    <?php echo STUser_f::get_msg(); ?>
</div>
<form action="" method="post" enctype="multipart/form-data" id="st_form_add_partner" class="<?php //echo STUser_f::get_status_msg(); ?>">
    <?php wp_nonce_field('user_setting','st_insert_post_rental'); ?>
    <div class="form-group form-group-icon-left">
        
        <label for="title" class="head_bol"><?php echo __('Name of Rental', ST_TEXTDOMAIN); ?>:</label>
        <i class="fa  fa-file-text input-icon input-icon-hightlight"></i>
        <input id="title" name="st_title" type="text" placeholder="<?php echo __('Name of Rental', ST_TEXTDOMAIN); ?>" class="form-control" value="<?php echo STInput::request('st_title') ?>">
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_title'),'danger') ?></div>
    </div>
    <div class="form-group form-group-icon-left">
        <label for="st_content" class="head_bol"><?php st_the_language('user_create_rental_content') ?>:</label>
        <?php wp_editor(stripslashes(STInput::request('st_content')),'st_content'); ?>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_content'),'danger') ?></div>
    </div>
    <div class="form-group ">
        <label for="desc" class="head_bol"><?php _e("Rental Description",ST_TEXTDOMAIN) ?>:</label>
        <textarea id="desc" name="st_desc" rows="6" class="form-control"><?php echo stripslashes(STInput::request( 'st_desc' )) ?></textarea>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_desc'),'danger') ?></div>
    </div>
    <div class="form-group form-group-icon-left">
        <label for="id_featured_image" class="head_bol"><?php _e("Featured Image",ST_TEXTDOMAIN) ?>:</label>
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
                            
                            <label for="address"><?php st_the_language( 'user_create_car_address' ) ?>:</label>
                            <i class="fa fa-home input-icon input-icon-hightlight"></i>
                            <input id="address" name="address" type="text"
                                   placeholder="<?php st_the_language( 'user_create_car_address' ) ?>" class="form-control" value="<?php echo STInput::request('address') ?>">

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
                                        <label for="taxonomy"> <?php echo esc_html($taxonomy_label); ?>:</label>
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
                                                            <input name="taxonomy[]" class="i-check item_tanoxomy" type="checkbox" <?php echo esc_html($check) ?>  value="<?php echo esc_attr($k.','.$taxonomy_name) ?>" /><?php echo esc_html($v) ?>
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
                    <div class="col-md-6 clear">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="rental_number"><?php _e("Numbers of Rental",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa  fa-cogs input-icon input-icon-hightlight"></i>
                            <input id="rental_number" name="rental_number" type="text" placeholder="<?php _e("Number of rental available for booking",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('rental_number') ?>" />
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rental_number'),'danger') ?></div>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class="form-group form-group-icon-left">
                            
                            <label for="rental_max_adult"><?php echo __("Max of Adult",ST_TEXTDOMAIN);?>:</label>
                            <i class="fa fa-plus-square input-icon input-icon-hightlight"></i>
                            <input id="rental_max_adult" name="rental_max_adult" type="text" placeholder="1" class="form-control number" value="<?php echo STInput::request('rental_max_adult') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rental_max_adult'),'danger') ?></div>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class="form-group form-group-icon-left">
                            
                            <label for="rental_max_children"><?php echo __("Max of Children",ST_TEXTDOMAIN);?>:</label>
                            <i class="fa fa-plus-square input-icon input-icon-hightlight"></i>
                            <input id="rental_max_children" name="rental_max_children" type="text" placeholder="1" class="form-control number" value="<?php echo STInput::request('rental_max_children') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rental_max_children'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="video"><?php st_the_language('user_create_rental_video') ?>:</label>
                            <i class="fa  fa-youtube-play input-icon input-icon-hightlight"></i>
                            <input id="video" name="video" type="text"  placeholder="<?php _e("Enter Youtube or Vimeo video link (Eg: https://www.youtube.com/watch?v=JL-pGPVQ1a8)") ?>" class="form-control"  value="<?php echo STInput::request('video') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('video'),'danger') ?></div>
                        </div>
                    </div>
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
                            
                            <label for="email"><?php st_the_language('user_create_rental_email') ?>:</label>
                            <i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
                            <input id="email" name="email" type="text" placeholder="<?php st_the_language('user_create_rental_email') ?>" class="form-control" value="<?php echo STInput::request('email') ?>">
                            <i class="placeholder"><?php _e("E-mail of Rental Agent which will receive email when new booking is recorded",ST_TEXTDOMAIN) ?></i>
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('email'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 clear">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="website"><?php st_the_language('user_create_rental_website') ?>:</label>
                            <i class="fa fa-link input-icon input-icon-hightlight"></i>
                            <input id="website" name="website" type="text" placeholder="<?php st_the_language('user_create_rental_website') ?>" class="form-control" value="<?php echo STInput::request('website') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('website'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="phone"><?php st_the_language('user_create_rental_phone') ?>:</label>
                            <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                            <input id="phone" name="phone" type="text" placeholder="<?php st_the_language('user_create_rental_phone') ?>" class="form-control" value="<?php echo STInput::request('phone') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('phone'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">

                            <label for="st_fax"><?php _e('Fax Number',ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                            <input id="st_fax" name="st_fax" type="text" placeholder="<?php _e('Fax Number',ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('st_fax') ?>">
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
                            <input id="price" name="price" type="text" placeholder="<?php st_the_language('user_create_rental_price') ?>" class="form-control number" value="<?php echo STInput::request('price') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('price'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="discount"><?php _e("Discount Rate",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-star input-icon input-icon-hightlight"></i>
                            <input id="discount" name="discount" type="text" placeholder="<?php _e("Discount Rate (%)",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('discount') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('discount'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="is_sale_schedule"><?php _e("Sale Schedule",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $is_sale_schedule = STInput::request('is_sale_schedule');?>
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
                    <div class="col-md-6">
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
                            <input id="deposit_payment_amount" name="deposit_payment_amount" type="text" placeholder="<?php _e("Deposit Amount",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('deposit_payment_amount') ?>">
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
                                    
                                    <label for="extra_max_number"><?php _e("Max Of Number",ST_TEXTDOMAIN) ?></label>
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
                            <label for="allow_full_day"><?php echo __('Allow booking full day', ST_TEXTDOMAIN); ?></label>
                            <select name="allow_full_day" id="allow_full_day" class="form-control">
                                <option value="on"><?php echo __('On', ST_TEXTDOMAIN); ?></option>
                                <option value="off"><?php echo __('Off', ST_TEXTDOMAIN); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class='col-md-12'>
                        <div class="form-group">
                            <label for="rentals_booking_period"><?php _e("Booking Period",ST_TEXTDOMAIN) ?>:</label>
                            <input id="rentals_booking_period" name="rentals_booking_period" type="text" min="0" placeholder="<?php _e("Booking Period (day)",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('rentals_booking_period') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rentals_booking_period'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="st_rental_external_booking"><?php _e("External Booking",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $st_rental_external_booking = STInput::request('st_rental_external_booking'); ?>
                            <select class="form-control st_rental_external_booking" name="st_rental_external_booking">
                                <option <?php if($st_rental_external_booking == 'off') echo 'selected';?> value="off"><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                <option <?php if($st_rental_external_booking == 'on') echo 'selected';?> value="on"><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class='col-md-6 data_st_rental_external_booking'>
                        <div class="form-group form-group-icon-left">
                            
                            <label for="st_rental_external_booking_link"><?php _e("External Booking URL",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-link  input-icon input-icon-hightlight"></i>
                            <input id="st_rental_external_booking_link" value="<?php echo STInput::request('st_rental_external_booking_link') ?>" name="st_rental_external_booking_link" type="text" placeholder="<?php _e("Eg: https://domain.com") ?>" class="form-control">
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
                        if ($is_enable =='off') {}else   {
                        ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-icon-left">
                                    
                                    <label for="is_meta_payment_gateway_<?php echo esc_attr($k) ?>"><?php echo esc_html($v->get_name()) ?>:</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $is_pay = STInput::request("is_meta_payment_gateway_".$k) ?>
                                    <select class="form-control" id="is_meta_payment_gateway_<?php echo esc_attr($k) ?>" name="is_meta_payment_gateway_<?php echo esc_attr($k) ?>">
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
                                        <textarea id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" class="form-control" ><?php echo get_post_meta( $post_id , $key , true); ?></textarea>
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
        <input name="btn_insert_post_type_rental" id="btn_insert_post_type_rental" type="submit" disabled class="btn btn-primary btn-lg" value="<?php _e("SUBMIT RENTAL",ST_TEXTDOMAIN) ?>">
    </div>

</form>
<div id="html_features_rental" style="display: none">
    <div class="item">
        <div class="col-md-4">
            <div class="form-group form-group-icon-left">
                <label for="features_title"><?php st_the_language('user_create_rental_features_title') ?></label>
                <input id="features_title" name="features_title[]" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-icon-left">
                <label for="features_number"><?php st_the_language('user_create_rental_features_number') ?></label>
                <input id="features_number" name="features_number[]" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-group-icon-left">
                <label for="features_icon"><?php st_the_language('user_create_rental_features_icon') ?></label>
                <input id="features_icon" name="features_icon[]" type="text" class="form-control" placeholder="<?php _e("(eg: fa-facebook)",ST_TEXTDOMAIN) ?>">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group form-group-icon-left">
                <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                    <?php st_the_language('user_create_rental_features_del') ?>
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