<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * form login
 *
 * Created by ShineTheme
 *
 */
$class_form='';
if(is_page_template('template-login.php')){
    $class_form = 'form-group-ghost';
}
    $btn_sing_in = get_post_meta(get_the_ID(),'btn_sing_in',true);
    if(empty($btn_sing_in))$btn_sing_in=__("Sign In",ST_TEXTDOMAIN);
?>
<form method="post" action="<?php echo esc_url(add_query_arg(array(
    'url'=>STInput::request('url')
)))?>">
    <?php
        global $status_error_login;
        echo balanceTags($status_error_login);

    ?>
    <div class="form-group <?php echo esc_attr($class_form); ?> form-group-icon-left">
        <label for="field-login_name"><?php _e("User Name",ST_TEXTDOMAIN) ?></label>
        <i class="fa fa-user input-icon input-icon-show"></i>
        <input id="field-login_name" name="login_name" class="form-control" placeholder="<?php _e('e.g. johndoe',ST_TEXTDOMAIN)?>" type="text" value="<?php echo STInput::request('login_name') ?>" />
    </div>
    <div class="form-group <?php echo esc_attr($class_form); ?> form-group-icon-left">
        <label for="field-login_password"><?php st_the_language('password') ?></label>
        <i class="fa fa-lock input-icon input-icon-show"></i>
        <input id="field-login_password" name="login_password" class="form-control" type="password"  placeholder="<?php st_the_language('my_secret_password') ?>" />
    </div>
    <input class="btn btn-primary" name="dlf_submit" type="submit" value="<?php echo esc_html($btn_sing_in) ?>" />
    <?php
    if(!empty($status_error_login)){
        ?>
        <br>
        <a href="<?php echo wp_lostpassword_url(); ?>" title="<?php _e("Forget Password",ST_TEXTDOMAIN) ?>"><?php _e("Forget Password ?",ST_TEXTDOMAIN) ?></a>
    <?php
    }
    unset($status_error_login);
    ?>
</form>