<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * form new login
 *
 * Created by ShineTheme
 *
 */
$reset = 'false';
if(!empty($_REQUEST['btn_reg'])){
    $reset = STUser_f::registration_user();
}
$class_form = "";
if(is_page_template('template-login.php')){
    $class_form = 'form-group-ghost';
}
    $btn_register = get_post_meta(get_the_ID(),'btn_register',true);
    if(empty($btn_register))$btn_register=__("Register",ST_TEXTDOMAIN);
?>

<form class="register_form" data-reset="<?php echo esc_attr($reset) ?>"  method="post" action="<?php echo esc_url(add_query_arg(array( 'url'=>STInput::request('url') )))?>" >

    <div class="row mt30 <?php if(st()->get_option( 'partner_enable_feature' ) == 'off'){ echo "hidden" ;} ?>">
        <div class="col-md-12">
            <div class="form-group <?php echo esc_attr($class_form); ?> form-group-icon-left">
                <label for="field-password"><?php _e("Select User Type",ST_TEXTDOMAIN) ?></label>
            </div>
        </div>
        <div class="col-md-6 mt20">
            <div class="checkbox checkbox-lg">
                <label>
                    <input class="i-check register_as" type="radio" name="register_as" <?php if(STInput::request('register_as',"normal") == "normal") echo "checked"?> value="normal"  /><?php _e("Normal User",ST_TEXTDOMAIN) ?></label>
            </div>
        </div>
        <div class="col-md-6 mt20">
            <div class="checkbox checkbox-lg">
                <label>
                    <input class="i-check register_as" type="radio" name="register_as" <?php if(STInput::request('register_as') == "partner") echo "checked"?> value="partner" /><?php _e("Partner",ST_TEXTDOMAIN) ?></label>
            </div>
        </div>
    </div>
    <div class="row mt20 data_field">
        <div class="col-md-6">
            <div class="form-group <?php echo esc_attr($class_form); ?> form-group-icon-left">
                <label for="field-user_name"><?php _e("User Name",ST_TEXTDOMAIN) ?><span class="color-red"> (*)</span></label>
                <i class="fa fa-user input-icon input-icon-show"></i>
                <input id="field-user_name" name="user_name" class="form-control" placeholder="<?php _e('e.g. johndoe',ST_TEXTDOMAIN)?>" type="text" value="<?php echo STInput::request('user_name') ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group <?php echo esc_attr($class_form); ?> form-group-icon-left">
                <label for="field-password"><?php st_the_language('password') ?><span class="color-red"> (*)</span></label>
                <i class="fa fa-lock input-icon input-icon-show"></i>
                <input id="field-password" name="password" class="form-control" type="password" placeholder="<?php _e('my secret password',ST_TEXTDOMAIN)?>" />
            </div>
        </div>
    </div>
    <div class="row mt20 data_field">
        <div class="col-md-6">
            <div class="form-group <?php echo esc_attr($class_form); ?> form-group-icon-left">
                <label for="field-email"><?php st_the_language('email') ?><span class="color-red"> (*)</span></label>
                <i class="fa fa-envelope input-icon input-icon-show"></i>
                <input id="field-email" name="email" class="form-control" placeholder="<?php _e('e.g. johndoe@gmail.com',ST_TEXTDOMAIN)?>" type="text" value="<?php echo STInput::request('email') ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group <?php echo esc_attr($class_form); ?> form-group-icon-left">
                <label for="field-full_name"><?php st_the_language('full_name') ?></label>
                <i class="fa fa-user input-icon input-icon-show"></i>
                <input id="field-full_name" name="full_name" class="form-control" placeholder="<?php _e('e.g. John Doe',ST_TEXTDOMAIN)?>" type="text" value="<?php echo STInput::request('full_name') ?>" />
            </div>
        </div>
    </div>
    <?php if(st()->get_option( 'partner_enable_feature' ) == 'on'){ ?>
    <div class="content_partner data_field">
        <div class="row mt20">
            <div class="col-md-3">
                <div class="form-group <?php echo esc_attr($class_form); ?> form-group-icon-left">
                    <label for="field-service"><?php _e("Select Your Service",ST_TEXTDOMAIN) ?></label>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group <?php echo esc_attr($class_form); ?> form-group-icon-left">
                    <label for="field-certificate"><?php _e("Upload Certificate",ST_TEXTDOMAIN) ?></label>
                </div>
            </div>
        </div>
        <?php if (st_check_service_available('st_hotel')){?>
            <div class="row mt20 div_st_hotel">
                <div class="col-md-3">
                    <div class="checkbox checkbox-stroke">
                        <label><input class="i-check st_register_service" type="checkbox" name="st_service_st_hotel" <?php if(STInput::request("st_service_st_hotel") == "on") echo "checked" ?> /><?php _e("Hotel",ST_TEXTDOMAIN) ?></label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="input-group">
                    <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                            <?php _e("Browse…",ST_TEXTDOMAIN) ?> <input type="file" name="st_certificates_st_hotel" data-type="st_hotel"  class="st_certificates">
                        </span>
                    </span>
                        <input type="text" class="form-control data_lable st_certificates_st_hotel_url" value="<?php echo esc_url(STInput::request('st_certificates_st_hotel_url')) ?>" readonly="">
                        <input type="hidden" class="form-control st_certificates_st_hotel_url" value="<?php echo esc_url(STInput::request('st_certificates_st_hotel_url')) ?>" name="st_certificates_st_hotel_url" >
                    </div>
                    <i><?php _e("Image format : jpg, png, gif . Image size 800x600 and max file size 2MB",ST_TEXTDOMAIN) ?></i>
                </div>
                <div class="col-md-2 data_image_certificates">
                    <?php if( STInput::request('st_certificates_st_hotel_url') !=""){ ?>
                        <img  class="thumbnail" src="<?php echo esc_html(STInput::request('st_certificates_st_hotel_url')) ?>">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (st_check_service_available('st_rental')){?>
            <div class="row mt20 div_st_rental">
                <div class="col-md-3">
                    <div class="checkbox checkbox-stroke">
                        <label><input class="i-check st_register_service" type="checkbox" name="st_service_st_rental" <?php if(STInput::request("st_service_st_rental") == "on") echo "checked" ?> /><?php _e("Rental",ST_TEXTDOMAIN) ?></label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="input-group">
                    <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                            <?php _e("Browse…",ST_TEXTDOMAIN) ?> <input type="file" name="st_certificates_st_rental" data-type="st_rental" class="st_certificates">
                        </span>
                    </span>
                        <input type="text" class="form-control data_lable st_certificates_st_rental_url" value="<?php echo esc_url(STInput::request('st_certificates_st_rental_url')) ?>" readonly="">
                        <input type="hidden" class="form-control st_certificates_st_rental_url" value="<?php echo esc_url(STInput::request('st_certificates_st_rental_url')) ?>" name="st_certificates_st_rental_url" >
                    </div>
                    <i><?php _e("Image format : jpg, png, gif . Image size 800x600 and max file size 2MB",ST_TEXTDOMAIN) ?></i>
                </div>
                <div class="col-md-2 data_image_certificates">
                    <?php if(STInput::request('st_certificates_st_rental_url') !=""){ ?>
                        <img  class="thumbnail" src="<?php echo esc_html(STInput::request('st_certificates_st_rental_url')) ?>">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (st_check_service_available('st_cars')){?>
            <div class="row mt20 div_st_cars">
                <div class="col-md-3">
                    <div class="checkbox checkbox-stroke">
                        <label><input class="i-check st_register_service" type="checkbox" name="st_service_st_cars" <?php if(STInput::request("st_service_st_cars") == "on") echo "checked" ?> /><?php _e("Car",ST_TEXTDOMAIN) ?></label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="input-group">
                    <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                            <?php _e("Browse…",ST_TEXTDOMAIN) ?> <input type="file" name="st_certificates_st_cars" data-type="st_cars" class="st_certificates">
                        </span>
                    </span>
                        <input type="text" class="form-control data_lable st_certificates_st_cars_url" value="<?php echo esc_url(STInput::request('st_certificates_st_cars_url')) ?>" readonly="">
                        <input type="hidden" class="form-control st_certificates_st_cars_url" value="<?php echo esc_url(STInput::request('st_certificates_st_cars_url')) ?>" name="st_certificates_st_cars_url" >
                    </div>
                    <i><?php _e("Image format : jpg, png, gif . Image size 800x600 and max file size 2MB",ST_TEXTDOMAIN) ?></i>
                </div>
                <div class="col-md-2 data_image_certificates">
                    <?php if(STInput::request('st_certificates_st_cars_url') !=""){ ?>
                        <img  class="thumbnail" src="<?php echo esc_html(STInput::request('st_certificates_st_cars_url')) ?>">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (st_check_service_available('st_tours')){?>
            <div class="row mt20 div_st_tours">
                <div class="col-md-3">
                    <div class="checkbox checkbox-stroke">
                        <label><input class="i-check st_register_service" type="checkbox" name="st_service_st_tours" <?php if(STInput::request("st_service_st_tours") == "on") echo "checked" ?> /><?php _e("Tour",ST_TEXTDOMAIN) ?></label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="input-group">
                    <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                            <?php _e("Browse…",ST_TEXTDOMAIN) ?> <input type="file" name="st_certificates_st_tours" data-type="st_tours" class="st_certificates">
                        </span>
                    </span>
                        <input type="text" class="form-control data_lable st_certificates_st_tours_url" value="<?php echo esc_url(STInput::request('st_certificates_st_tours_url')) ?>" readonly="">
                        <input type="hidden" class="form-control st_certificates_st_tours_url" value="<?php echo esc_url(STInput::request('st_certificates_st_tours_url')) ?>" name="st_certificates_st_tours_url" >
                    </div>
                    <i><?php _e("Image format : jpg, png, gif . Image size 800x600 and max file size 2MB",ST_TEXTDOMAIN) ?></i>
                </div>
                <div class="col-md-2 data_image_certificates">
                    <?php if(STInput::request('st_certificates_st_tours_url') !=""){ ?>
                        <img  class="thumbnail" src="<?php echo esc_html(STInput::request('st_certificates_st_tours_url')) ?>">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (st_check_service_available('st_activity')){?>
            <div class="row mt20 div_st_activity">
                <div class="col-md-3">
                    <div class="checkbox checkbox-stroke">
                        <label><input class="i-check st_register_service" type="checkbox" name="st_service_st_activity" <?php if(STInput::request("st_service_st_activity") == "on") echo "checked" ?> /><?php _e("Activity",ST_TEXTDOMAIN) ?></label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="input-group">
                    <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                            <?php _e("Browse…",ST_TEXTDOMAIN) ?> <input type="file" name="st_certificates_st_activity" data-type="st_activity" class="st_certificates">
                        </span>
                    </span>
                        <input type="text" class="form-control data_lable st_certificates_st_activity_url" value="<?php echo esc_url(STInput::request('st_certificates_st_activity_url')) ?>" readonly="">
                        <input type="hidden" class="form-control st_certificates_st_activity_url" value="<?php echo esc_url(STInput::request('st_certificates_st_activity_url')) ?>" name="st_certificates_st_activity_url" >
                    </div>
                    <i><?php _e("Image format : jpg, png, gif . Image size 800x600 and max file size 2MB",ST_TEXTDOMAIN) ?></i>
                </div>
                <div class="col-md-2 data_image_certificates">
                    <?php if(STInput::request('st_certificates_st_activity_url') !=""){ ?>
                        <img  class="thumbnail" src="<?php echo esc_html(STInput::request('st_certificates_st_activity_url')) ?>">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
    <div class="checkbox st_check_term_conditions mt20">
        <label>
            <input class="i-check term_condition" name="term_condition" type="checkbox" <?php if(STInput::post('term_condition')==1) echo 'checked'; ?>/><?php echo  st_get_language('i_have_read_and_accept_the').'<a target="_blank" href="'.get_the_permalink(st()->get_option('page_terms_conditions')).'"> '.st_get_language('terms_and_conditions').'</a>';?>
        </label>
    </div>
    <div class="text-center mt20">
        <input name="btn_reg" class="btn btn-primary btn-lg" type="hidden" value="register">
        <button class="btn btn-primary btn-lg" type="submit" ><?php echo esc_html($btn_register) ?></button>
    </div>
</form>