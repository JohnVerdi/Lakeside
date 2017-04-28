<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User create hotel
 *
 * Created by ShineTheme
 *
 */
?>
<?php $validator= STUser_f::$validator; ?>
<div class="st-create">
    <h2><?php _e("Add new Hotel",ST_TEXTDOMAIN) ?></h2>
</div>
<div class="msg">
    <?php echo STTemplate::message() ?>
    <?php echo STUser_f::get_msg(); ?>
</div>
<form action="" method="post" enctype="multipart/form-data" id="st_form_add_partner" class="<?php //echo STUser_f::get_status_msg(); ?>">
    <?php wp_nonce_field('user_setting','st_insert_post_hotel'); ?>
    <div class="form-group form-group-icon-left">
        
        <label for="title" class="head_bol"><?php _e("Name of Hotel",ST_TEXTDOMAIN) ?>:</label>
        <i class="fa  fa-file-text input-icon input-icon-hightlight"></i>
        <input id="title" name="st_title" type="text" placeholder="<?php _e("Name of Hotel",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('st_title') ?>">
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_title'),'danger') ?></div>
    </div>
    <div class="form-group form-group-icon-left">
        <label for="st_content" class="head_bol"><?php st_the_language('user_create_hotel_content') ?>:</label>
        <?php wp_editor(stripslashes(STInput::request('st_content')),'st_content'); ?>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_content'),'danger') ?></div>
    </div>
    <div class="form-group">
        <label for="desc" class="head_bol"><?php _e("Hotel Description",ST_TEXTDOMAIN) ?>:</label>
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
            <li><a href="#tab-hotel-details" data-toggle="tab"><?php _e("Hotel Details",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-price-setting" data-toggle="tab"><?php _e("Price Settings",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-checkinout-time" data-toggle="tab"><?php _e("Check in/out time",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-other-options" data-toggle="tab"><?php _e("Other Options",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-hotel-policy" data-toggle="tab"><?php _e("Hotel Policy",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-discount-flash" data-toggle="tab"><?php _e("Discount Flash",ST_TEXTDOMAIN) ?></a></li>
            <?php $custom_field = st()->get_option( 'hotel_unlimited_custom_field' );
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
            <div class="tab-pane fade  " id="tab-hotel-details">
                <div class="row">
                    <?php
                    $taxonomies = (get_object_taxonomies('st_hotel'));
                    if (is_array($taxonomies) and !empty($taxonomies)){
                        foreach ($taxonomies as $key => $value) {
                            ?>
                            <div class="col-md-12">
                                <?php
                                $category = STUser_f::get_list_taxonomy($value);
                                $taxonomy_tmp = get_taxonomy( $value );
                                $taxonomy_label =  ($taxonomy_tmp->label );
                                $taxonomy_name =  ($taxonomy_tmp->name );
                                if(!empty($category)){
                                    ?>
                                    <div class="form-group form-group-icon-left">
                                        <label for="taxonomy"> <?php echo esc_html($taxonomy_label); ?>:</label>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="checkbox-inline checkbox-stroke">
                                                    <label for="taxonomy">
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
                                <?php }?>
                            </div>
                        <?php
                        }
                    } else { ?>
                        <input name="no_taxonomy" type="hidden" value="no_taxonomy">
                    <?php } ?>
                    <div class="col-md-12">
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('taxonomy[]'),'danger') ?></div>
                    </div>
                    <div class="col-md-12">
                        <?php
                        $options = st()->get_option('booking_card_accepted', array());
                        if(!empty($options) and is_array($options)):
                            $data_array = STInput::request('card_accepted');
                            ?>
                            <div class="form-group form-group-icon-left">
                                <label for="card_accepted"> <?php _e("Card Accepted",ST_TEXTDOMAIN) ?>:</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="checkbox-inline checkbox-stroke">
                                            <label for="card_accepted">
                                                <i class="fa fa-cogs"></i>
                                                <input name="check_all" class="i-check check_all" type="checkbox"  /><?php _e("All",ST_TEXTDOMAIN) ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php
                                    $i=0;
                                        foreach($options as $k=>$v):
                                        $check = '';
                                        $value = sanitize_title_with_dashes($v['title']);
                                        if( !empty($data_array) and in_array( $value , $data_array )){
                                            $check = 'checked';
                                        }
                                        ?>
                                        <div class="col-md-3">
                                            <div class="checkbox-inline checkbox-stroke">
                                                <label for="card_accepted">
                                                    <input <?php echo esc_attr($check) ?> name="card_accepted[<?php echo esc_attr($i) ?>]" class="i-check item_tanoxomy" type="checkbox" value="<?php echo esc_attr($value) ?>" /><?php echo esc_html($v['title']) ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php $i++; endforeach ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-12">
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('card_accepted[]'),'danger') ?></div>
                    </div>
					<hr>
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
                            
                            <label for="email"><?php st_the_language('user_create_hotel_email') ?>:</label>
                            <i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
                            <input id="email" name="email" type="text" placeholder="<?php st_the_language('user_create_hotel_email') ?>" class="form-control" value="<?php echo STInput::request('email') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('email'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="website"><?php st_the_language('user_create_hotel_website') ?>:</label>
                            <i class="fa fa-link input-icon input-icon-hightlight"></i>
                            <input id="website" name="website" type="text" placeholder="<?php st_the_language('user_create_hotel_website') ?>" class="form-control" value="<?php echo STInput::request('website') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('website'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="phone"><?php st_the_language('user_create_hotel_phone') ?>:</label>
                            <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                            <input id="phone" name="phone" type="text" placeholder="<?php st_the_language('user_create_hotel_phone') ?>" class="form-control" value="<?php echo STInput::request('phone') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('phone'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="fax"><?php st_the_language('user_create_hotel_fax_number') ?>:</label>
                            <i class="fa  fa-fax input-icon input-icon-hightlight"></i>
                            <input id="fax" name="fax" type="text" placeholder="<?php st_the_language('user_create_hotel_fax_number') ?>" class="form-control number" value="<?php echo STInput::request('fax') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('fax'),'danger') ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="video"><?php st_the_language('user_create_hotel_video') ?>:</label>
                            <i class="fa  fa-youtube-play input-icon input-icon-hightlight"></i>
                            <input id="video" name="video" type="text" placeholder="<?php _e("Enter Youtube or Vimeo video link (Eg: https://www.youtube.com/watch?v=JL-pGPVQ1a8)",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('video') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('video'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="hotel_star"><?php _e("Star Rating",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa  fa-star input-icon input-icon-hightlight"></i>
                            <input id="hotel_star" name="hotel_star" type="text" placeholder="<?php _e("Star Rating",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('hotel_star') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('hotel_star'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 clear">
                        <div class='form-group form-group-icon-left'>
                            
                            <label for="st_custom_layout"><?php _e( "Detail Hotel Layout" , ST_TEXTDOMAIN ) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $layout = st_get_layout('st_hotel');
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
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            <label for="id_logo" class="head_bol"><?php _e("Logo",ST_TEXTDOMAIN) ?>:</label>
                            <?php
                            $id_img = STInput::request('id_logo');
                            $post_thumbnail_id = wp_get_attachment_image_src($id_img, 'full');
                            ?>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <span class="btn btn-primary btn-file">
                                        <?php _e("Browse…",ST_TEXTDOMAIN) ?> <input name="logo"  type="file" >
                                    </span>
                                </span>
                                <input type="text" readonly="" value="<?php echo esc_url($post_thumbnail_id['0']); ?>" class="form-control data_lable">
                            </div>
                            <input id="id_logo" name="id_logo" type="hidden" value="<?php echo esc_attr($id_img) ?>">
                            <?php
                            if(!empty($post_thumbnail_id)){
                                echo '<div class="user-profile-avatar user_seting st_edit">
                                        <div><img width="300" height="300" class="avatar avatar-300 photo img-thumbnail" src="'.$post_thumbnail_id['0'].'" alt=""></div>
                                        <input name="" type="button"  class="btn btn-danger  btn_featured_image" value="'.st_get_language('user_delete').'">
                                      </div>';
                            }
                            ?>
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('logo'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('gallery'),'danger') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade  " id="tab-price-setting">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="is_auto_caculate"><?php _e("Auto Calculate Average Price",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $is_auto_caculate = STInput::request('is_auto_caculate'); ?>
                            <select class="form-control is_auto_caculate" name="is_auto_caculate" id="is_auto_caculate">
                                <option <?php if($is_auto_caculate == "on") echo "selected"; ?>  value="on"><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                <option <?php if($is_auto_caculate == "off") echo "selected"; ?> value="off"><?php _e("No",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6  data_is_auto_caculate">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="price_avg"><?php _e("Price AVG",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-money input-icon input-icon-hightlight"></i>
                            <input id="price_avg" name="price_avg" type="text" min="0" placeholder="<?php _e("Price AVG",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('price_avg') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('price_avg'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 clear">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="min_price"><?php _e("Min Price",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-money input-icon input-icon-hightlight"></i>
                            <input id="min_price" name="min_price" type="text" min="0" placeholder="<?php _e("Min Price",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('min_price') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('min_price'),'danger') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-checkinout-time">
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
                    <div class="col-xs-6">
                        <label for="check_in_time"><?php echo __('Check in time', ST_TEXTDOMAIN); ?></label>
                        <input type="text" class="form-control st_timepicker" name="check_in_time">
                    </div>
                    <div class="col-xs-6">
                        <label for="check_out_time"><?php echo __('Check out time', ST_TEXTDOMAIN); ?></label>
                        <input type="text" class="form-control st_timepicker" name="check_out_time">
                    </div>
                </div>
            </div>
            <div class="tab-pane fade  " id="tab-other-options">
                <div class="row">
                    <div class='col-md-12'>
                        <div class="form-group">
                            <label for="hotel_booking_period"><?php _e("Booking Period",ST_TEXTDOMAIN) ?>:</label>
                            <input id="hotel_booking_period" name="hotel_booking_period" type="text" min="0" placeholder="<?php _e("Booking Period (day)",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('hotel_booking_period') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('hotel_booking_period'),'danger') ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class='col-md-12'>
                        <div class="form-group">
                            <label for="min_book_room"><?php _e("Minimum days to book room",ST_TEXTDOMAIN) ?>:</label>
                            <input id="min_book_room" name="min_book_room" type="text" min="0" placeholder="<?php _e("Minimum days to book room",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('min_book_room') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('min_book_room'),'danger') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade  " id="tab-hotel-policy">
                <div class="row">
                    <div class='col-md-12'>
                        <div class="form-group">
                            <label for="hotel_policy"><?php _e("Hotel policy",ST_TEXTDOMAIN) ?>:</label>
                            <div id="data_hotel_policy" class='row'>
                                <?php 
                                    $policy_title = STInput::request('policy_title');
                                    $policy_description = STInput::request('policy_description');
                                    if (!empty($policy_title)                                        )
                                    {                                       
                                        if (is_array($policy_title)){
                                            foreach ($policy_title as $key => $value) {
                                                ?>
                                                    <div class="item">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="policy_title"><?php _e("Title",ST_TEXTDOMAIN) ?></label>
                                                                <input id="" name="policy_title[]" type="text" class="form-control" value=" <?php echo esc_attr($value);?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label for="policy_description"><?php _e("Policy Description",ST_TEXTDOMAIN) ?></label>
                                                                <textarea id="" name="policy_description[]" class="form-control" value=""><?php echo stripslashes($policy_description[$key]);?></textarea>
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
                                    <button id="btn_hotel_policy" type="button" class="btn btn-info btn-sm"><?php _e("Add New",ST_TEXTDOMAIN) ?></button><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade  " id="tab-discount-flash">
                <div class="row">
                    <div class='col-md-12'>
                        <div class="form-group">
                            <label for="discount_text"><?php _e("Discount Text",ST_TEXTDOMAIN) ?>:</label>
                            <input id="discount_text" name="discount_text" type="text" placeholder="<?php _e("Discount Text",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('discount_text') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('discount_text'),'danger') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade  " id="tab-custom-fields">
                <?php
                $custom_field = st()->get_option( 'hotel_unlimited_custom_field' );
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
                                        <input id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" type="text" placeholder="<?php _e( 'Enter your description here' , ST_TEXTDOMAIN ) ?>" class="form-control" value="<?php echo STInput::request($key) ?>">
                                    <?php } ?>
                                    <?php if($v[ 'type_field' ] == "date-picker") { ?>
                                        <input id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" type="text" placeholder="<?php _e( 'Enter your description here' , ST_TEXTDOMAIN ) ?>" class="date-pick form-control" value="<?php echo STInput::request($key) ?>">
                                    <?php } ?>
                                    <?php if($v[ 'type_field' ] == "textarea") { ?>
                                        <textarea id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" class="form-control" ><?php echo get_post_meta( $post_id , $key , true); ?></textarea>
                                    <?php } ?>
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
        <input name="btn_insert_post_type_hotel" id="btn_insert_post_type_hotel" type="submit" disabled class="btn btn-primary btn-lg "  value="<?php _e("SUBMIT HOTEL",ST_TEXTDOMAIN) ?>">
    </div>
</form>
<div id="html_hotel_policy" style="display: none">
    <div class="item">
        <div class="col-md-3">
            <div class="form-group">
                <label for="policy_title"><?php _e("Title",ST_TEXTDOMAIN) ?></label>
                <input id="" name="policy_title[]" type="text" class="form-control" value="">
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="policy_description"><?php _e("Policy Description",ST_TEXTDOMAIN) ?></label>
                <textarea id="" name="policy_description[]" class="form-control" value=""></textarea>
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
