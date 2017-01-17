<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User setting
 *
 * Created by ShineTheme
 *
 */
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$role = $current_user->roles;
$role = array_shift($role);
?>
<div class="st-create">
    <h2  class="pull-left"><?php STUser_f::get_title_account_setting() ?></h2>
    
     <input name="st_btn_update" type="submit" class="btn btn-primary pull-right" value="<?php st_the_language('user_save_changes') ?>">
 
</div>
<div class="row">
    <div class="col-md-5">
        <?php
        if(!empty($_REQUEST['status'])){
            if($_REQUEST['status'] == 'success'){
                echo '<div class="alert alert-'.$_REQUEST['status'].'">
                        <button data-dismiss="alert" type="button" class="close"><span aria-hidden="true">×</span>
                        </button>
                        <p class="text-small">'.st_get_language('user_update_successfully').'</p>
                      </div>';
            }
            if($_REQUEST['status'] == 'danger'){
                echo '<div class="alert alert-'.$_REQUEST['status'].'">
                        <button data-dismiss="alert" type="button" class="close"><span aria-hidden="true">×</span>
                        </button>
                        <p class="text-small">'.__("Update not successfully",ST_TEXTDOMAIN).'</p>
                      </div>';
            }
        }
        ?>
        <form method="post" action="" enctype="multipart/form-data">
            <?php wp_nonce_field('user_setting','st_update_user'); ?>
            <input type="hidden" name="id_user" value="<?php echo esc_attr($data->ID) ?>">
            <h4><?php st_the_language('user_personal_infomation') ?></h4>
            <div class="form-group form-group-icon-left">
                <label for="st_name"><?php st_the_language('user_display_name') ?></label><i class="fa fa-user input-icon"></i>
                <input name="st_name" class="form-control" value="<?php echo esc_attr($data->display_name) ?>" type="text" />
            </div>
            <div class="form-group form-group-icon-left">
                <label for="st_email"><?php st_the_language('user_mail') ?></label><i class="fa fa-envelope input-icon"></i>
                <input name="st_email" class="form-control" value="<?php echo esc_attr($data->user_email) ?>" type="text" />
            </div>
            <div class="form-group form-group-icon-left">
                <label for="st_phone"><?php st_the_language('user_phone_number') ?></label><i class="fa fa-phone input-icon"></i>
                <input name="st_phone" class="form-control" value="<?php echo get_user_meta($data->ID , 'st_phone' , true) ?>" type="text" />
            </div>
            <div class="form-group form-group-icon-left">
                <div class="checkbox">
                    <label for="st_is_check_show_info">
                        <?php
                        $is_check =  get_user_meta($data->ID , 'st_is_check_show_info' , true);
                        if(!empty($is_check)){
                            $is_check = 'checked="checked"';
                        }
                        ?>
                        <input <?php echo esc_attr($is_check); ?> name="st_is_check_show_info" class="i-check" type="checkbox" />
                        <?php st_the_language('show_email_and_phone_number_to_other_account') ?>
                    </label>
                </div>
            </div>
            <div class="form-group form-group-icon-left">
                <label for="id_avatar_user_setting"><?php _e('Avatar',ST_TEXTDOMAIN); ?></label>
                <?php
                $id_img = get_user_meta($user_id , 'st_avatar' , true);
                $url_avatar = "";
                $post_thumbnail_id = wp_get_attachment_image_src($id_img, 'full');
                if(!empty($post_thumbnail_id)){
                    $url_avatar = array_shift($post_thumbnail_id);
                }
                ?>
                <div class="input-group">
                    <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                            <?php _e("Browse…",ST_TEXTDOMAIN) ?> <input name="st_avatar"  type="file" >
                        </span>
                    </span>
                    <input type="text" readonly="" value="<?php echo esc_url($url_avatar); ?>" class="form-control data_lable">
                </div>
                <input id="id_avatar_user_setting" name="id_avatar" type="hidden" value="<?php echo esc_attr($id_img) ?>">
                <?php
                if(!empty($url_avatar)){
                    echo '<div class="user-profile-avatar user_seting">
                            <img width="300" height="300" class="avatar avatar-300 photo img-thumbnail" src="'.$url_avatar.'" alt="">
                            <input name="st_delete_avatar" type="button"  class="btn btn-danger  btn_del_avatar" value="'.st_get_language('user_delete').'">
                          </div>';
                }
                ?>
            </div>
        </form>
    </div>
    <div class="col-md-4 col-md-offset-1">
        <h4><?php st_the_language('user_change_password') ?></h4>
        <div class="msg">
            <?php
            if(!empty(STUser_f::$msg)){
                echo '<div class="alert alert-'.STUser_f::$msg['status'].'">
                        <button data-dismiss="alert" type="button" class="close"><span aria-hidden="true">×</span>
                        </button>
                        <p class="text-small">'.STUser_f::$msg['msg'].'</p>
                      </div>';
            }
            ?>
        </div>
        <form method="post" action="">
            <?php wp_nonce_field('user_setting','st_update_pass'); ?>
            <input type="hidden" name="user_login" value="<?php echo esc_attr($data->user_login) ?>">
            <div class="form-group form-group-icon-left">
                <label for="old_pass"><?php st_the_language('user_current_password') ?></label>
                <i class="fa fa-lock input-icon"></i>
                <input name="old_pass" class="form-control" type="password" />
            </div>
            <div class="form-group form-group-icon-left">
                <label for="new_pass"><?php st_the_language('user_new_password') ?></label>
                <i class="fa fa-lock input-icon"></i>
                <input name="new_pass" class="form-control" type="password" />
            </div>
            <div class="form-group form-group-icon-left">
                <label for="new_pass_again"><?php st_the_language('user_new_password_again') ?></label>
                <i class="fa fa-lock input-icon"></i>
                <input name="new_pass_again" class="form-control" type="password" />
            </div>
            <hr />
            <input name="btn_update_pass" class="btn btn-primary" type="submit" value="<?php st_the_language('user_change_password') ?>" />
        </form>
    </div>
</div>