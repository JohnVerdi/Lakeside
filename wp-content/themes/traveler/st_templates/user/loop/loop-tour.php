<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User loop tours
 *
 * Created by ShineTheme
 *
 */
$status = get_post_status(get_the_ID());
$icon_class = STUser_f::st_get_icon_status_partner();
$page_my_account_dashboard = st()->get_option('page_my_account_dashboard');
?>
<li <?php  post_class() ?>>
    <a data-id="<?php the_ID() ?>" data-id-user="<?php echo esc_attr($data['ID']) ?>" data-placement="top" rel="tooltip"  class="btn_remove_post_type cursor fa fa-times booking-item-wishlist-remove" data-original-title="<?php st_the_language('user_remove') ?>"></a>
    <a rel="tooltip" data-original-title="<?php st_the_language('user_edit') ?>" href="<?php echo esc_url(add_query_arg(array('sc'=>'edit-tours','id'=>get_the_ID()),get_the_permalink($page_my_account_dashboard))) ?>"  class="btn_remove_post_type cursor fa fa-edit booking-item-wishlist-remove" style="top:90px ; background: #ed8323 ; color: #fff"></a>
    <i rel="tooltip" data-original-title="<?php st_the_language('user_status') ?>" data-placement="top"  class="<?php echo esc_attr($icon_class) ?> cursor fa  booking-item-wishlist-remove" style="top: 60px;"></i>

    <a data-id="<?php the_ID() ?>" data-id-user="<?php echo esc_attr($data['ID']) ?>" data-status="<?php if($status == 'trash' ) echo "on";else echo 'off'; ?>" data-placement="top" rel="tooltip"  class="btn_on_off_post_type_partner cursor fa <?php if($status == 'trash' ) echo "fa-eye-slash";else echo 'fa-eye'; ?> booking-item-wishlist-remove" data-original-title="<?php _e("On/Off",ST_TEXTDOMAIN) ?>" style="top:120px"></a>

    <div class="spinner user_img_loading ">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
    <div <?php post_class('booking-item') ?>>
        <div class="row">
            <div class="col-md-3">
                <div class="booking-item-img-wrap st-popup-gallery">
                    <?php $thumb_url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
                    <a href="<?php echo esc_url($thumb_url)?>" class="st-gp-item">
                        <?php
                        $img = get_the_post_thumbnail( get_the_ID() , array(800,600,'bfi_thumb'=>true)) ;
                        if(!empty($img)){
                            echo balanceTags($img);
                        }else{
                            echo '<img width="800" height="600" alt="no-image" class="wp-post-image" src="'.bfi_thumb(get_template_directory_uri().'/img/no-image.png',array('width'=>800,'height'=>600)) .'">';
                        }
                        ?>
                    </a>
                    <?php
                    $count = 1;
                    $gallery = get_post_meta(get_the_ID(), 'gallery', true);
                    $gallery = explode(',', $gallery);
                    if (!empty($gallery) and $gallery[0]) {
                        $count += count($gallery);
                    }
                    if ($count) {
                        echo '<div class="booking-item-img-num"><i class="fa fa-picture-o"></i>';
                        echo esc_attr($count);
                        echo '</div>';
                    }
                    ?>
                    <div class="hidden">
                        <?php if (!empty($gallery) and $gallery[0]) {
                            $count += count($gallery);
                            foreach($gallery as $key=>$value)
                            {
                                $img_link=wp_get_attachment_image_src($value,array(800,600,'bfi_thumb'=>true));
                                if(isset($img_link[0]))
                                    echo "<a class='st-gp-item' href='{$img_link[0]}'></a>";
                            }
                        }?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <a href="<?php the_permalink() ?>" class="color-inherit">
                    <div class="booking-item-rating">
                    <ul class="icon-group booking-item-rating-stars">
                        <?php echo  TravelHelper::rate_to_string(STReview::get_avg_rate()); ?>
                    </ul>
                            <span class="booking-item-rating-number">
                                <b><?php echo STReview::get_avg_rate() ?></b> <?php st_the_language('user_of_5') ?></span>
                    <small>
                        (<?php comments_number( st_get_language('user_no_reviews'), st_get_language('user_1_review'), '% '.st_get_language('user_reviews') );?>)
                    </small>
                    </div>
                    <h5 class="booking-item-title"><?php the_title() ?></h5>
                    <p class="booking-item-address">
                        <?php  $address =  get_post_meta(get_the_ID() ,'address' ,true);
                        if(!empty($address)){
                            echo '<i class="fa fa-map-marker"></i> '.$address;
                        }
                        ?>
                    </p>
                    <div class="package-info">
                        <?php $max_people = get_post_meta(get_the_ID(),'max_people', true) ?>
                        <i class="fa    fa-users"></i>
                        <span class=""><?php st_the_language('user_max_people') ?> : </span>
                        <?php echo esc_html($max_people) ?>
                        <?php if($max_people>1)st_the_language('user_peoples');else st_the_language('user_people') ?>
                    </div>
                    <div class="package-info">
                        <?php $type_tour = get_post_meta(get_the_ID(),'type_tour',true); ?>
                        <?php if($type_tour == 'daily_tour'){
                            
                            $day = STTour::get_duration_unit();
                            if($day) {
                                ?>
                                <i class="fa fa-calendar"></i>
                                <span class=""><?php _e('Duration', ST_TEXTDOMAIN)?> : </span>
                                <?php echo esc_html($day) ?>
                                
                            <?php
                            }
                        }else{ ?>
                            <?php
                            $check_in = get_post_meta(get_the_ID() , 'check_in' ,true);
                            $check_out = get_post_meta(get_the_ID() , 'check_out' ,true);
                            if(!empty($check_out) and !empty($check_out)):
                                ?>
                                <i class="fa fa-calendar"></i>
                                <span class=""><?php st_the_language('tour_date') ?> : </span>
                                <?php
                                $date = date_i18n(TravelHelper::getDateFormat(),strtotime($check_in)).' <i class="fa fa-long-arrow-right"></i> '.date_i18n(TravelHelper::getDateFormat(),strtotime($check_out));
                                echo balanceTags($date);
                            endif;
                            ?>
                        <?php } ?>
                    </div>
                    <p class="booking-item-description">
                    </p>
                </a>
            </div>
            <div class="col-md-3">
                <?php echo STTour::get_price_html(get_the_ID(),false,'<br>','booking-item-price'); ?>
            
            </div>
        </div>
    </div>
</li>

