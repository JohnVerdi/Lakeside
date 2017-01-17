<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User loop cars
 *
 * Created by ShineTheme
 *
 */
$status = get_post_status(get_the_ID());
$icon_class = STUser_f::st_get_icon_status_partner();


$info_price = STCars::get_info_price();
$price = $info_price['price'];
$count_sale = $info_price['discount'];
$price_origin = $info_price['price_origin'];

$page_my_account_dashboard = st()->get_option('page_my_account_dashboard');
?>
<li <?php  post_class($status) ?>>
    <a data-id="<?php the_ID() ?>" data-id-user="<?php echo esc_attr($data['ID']) ?>" data-placement="top" rel="tooltip"  class="btn_remove_post_type cursor fa fa-times booking-item-wishlist-remove" data-original-title="<?php st_the_language('user_remove') ?>"></a>
    <a rel="tooltip" data-original-title="<?php st_the_language('user_edit') ?>" href="<?php echo esc_url(add_query_arg(array('sc'=>'edit-cars','id'=>get_the_ID()),get_the_permalink($page_my_account_dashboard))) ?>"  class="btn_remove_post_type cursor fa fa-edit booking-item-wishlist-remove" style="top:90px ; background: #ed8323 ; color: #fff"></a>

    <i rel="tooltip" data-original-title="<?php st_the_language('user_status') ?>" data-placement="top"  class="<?php echo esc_attr($icon_class) ?> cursor fa  booking-item-wishlist-remove" style="top: 60px;"></i>

    <a data-id="<?php the_ID() ?>" data-id-user="<?php echo esc_attr($data['ID']) ?>" data-status="<?php if($status == 'trash' ) echo "on";else echo 'off'; ?>" data-placement="top" rel="tooltip"  class="btn_on_off_post_type_partner cursor fa <?php if($status == 'trash' ) echo "fa-eye-slash";else echo 'fa-eye'; ?> booking-item-wishlist-remove" data-original-title="<?php _e("On/Off",ST_TEXTDOMAIN) ?>" style="top:120px"></a>


    <div class="spinner user_img_loading ">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
    <a href="<?php the_permalink() ?>" class="booking-item">
        <div class="row">
            <div class="col-md-3">
                <div class="booking-item-car-img">
                    <?php
                    if(has_post_thumbnail() and get_the_post_thumbnail()){
                        the_post_thumbnail(array(800,400,'bfi_thumb'=>true));
                    }else{
                        echo st_get_default_image();
                    }
                    ?>
                    <p class="booking-item-car-title"><?php the_title() ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-8">
                        <?php
                        $i=0;
                        $limit = st()->get_option('car_equipment_info_limit',11);
                        $data_text_small = $data_text_lg ='';
                        $taxonomy= get_object_taxonomies('st_cars','object');
                        $taxonomy_info = get_post_meta(get_the_ID(),'cars_equipment_info',true);
                        foreach($taxonomy as $key => $value){
                            if($key != 'st_category_cars'){
                                if($key != 'st_cars_pickup_features') {
                                    $data_term = get_the_terms(get_the_ID(), $key, true);
                                    if(!empty($data_term)){
                                        foreach($data_term as $k=>$v){
                                            // check taxonomy info
                                            $dk_check = false;
                                            if(is_array($taxonomy_info)){
                                                foreach($taxonomy_info as $k_info => $v_info){
                                                    if( $v->term_id == $v_info['cars_equipment_taxonomy_id'] ){
                                                        $dk_check = true;
                                                        $data_info = $v_info['cars_equipment_taxonomy_info'];
                                                        $data_title_info = $v_info['title'];
                                                    }
                                                }
                                            }
                                            if($i<$limit){
                                                if($dk_check == true){
                                                    $data_text_lg .=  '<li title="" data-placement="top" rel="tooltip" data-original-title="'.$data_title_info.'">
                                                                            <i class="'.TravelHelper::handle_icon(get_tax_meta($v->term_id, 'st_icon',true)).'"></i>
                                                                            <span class="booking-item-feature-sign">'.$data_info.'</span>
                                                                        </li>';
                                                }else{
                                                    $data_text_small .= '<li title="" data-placement="top" rel="tooltip" data-original-title="'.esc_html($v->name).'">
                                                                            <i class="'.TravelHelper::handle_icon(get_tax_meta($v->term_id, 'st_icon',true)).'"></i>
                                                                      </li>';
                                                }
                                            }
                                            $i++;
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                        <ul class="booking-item-features booking-item-features-sign clearfix"><?php echo balanceTags($data_text_lg)?></ul>
                        <ul class="booking-item-features booking-item-features-small clearfix"><?php echo balanceTags($data_text_small)?></ul>
                    </div>
                    <div class="col-md-4">
                        <ul class="booking-item-features booking-item-features-dark">
                            <?php $data_terms = get_the_terms(get_the_ID(),'st_cars_pickup_features');
                            if(!empty($data_terms)){
                                foreach($data_terms as $k=>$v){
                                    $icon = get_tax_meta($v->term_id ,'st_icon',true);
                                    echo '<li title="" data-placement="top" rel="tooltip" data-original-title="'.$v->name.'">
                                                           <i class="'.TravelHelper::handle_icon( $icon ).'"></i>
                                                         </li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <span class="booking-item-price">
                    <?php if($price != $price_origin){ ?>
                        <span class="text-lg lh1em sale_block onsale">
                         <?php echo TravelHelper::format_money( $price_origin )?>
                        </span>
                    <?php } ?>
                    <?php echo TravelHelper::format_money($price) ?>
                </span>
                <?php $category = get_the_terms(get_the_ID() ,'st_category_cars') ?>
                <?php
                $txt ='';
                if(!empty($category)){
                    foreach($category as $k=>$v){
                        $txt .= $v->name.' ,';
                    }
                    $txt = substr($txt,0,-1);
                }
                ?>
                <p class="booking-item-flight-class"><?php echo esc_html($txt); ?></p>
            </div>
        </div>
    </a>
</li>

