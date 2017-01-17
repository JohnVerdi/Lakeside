<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User loop room
 *
 * Created by ShineTheme
 *
 */
$status = get_post_status(get_the_ID());
if($status == 'draft'){
    $icon_class = 'status_warning fa-warning';
}else{
    $icon_class = 'status_ok  fa-check-square-o';
}
$page_my_account_dashboard = st()->get_option('page_my_account_dashboard');
?>
<li <?php  post_class() ?>>
    <a data-id="<?php the_ID() ?>" data-id-user="<?php echo esc_attr($data['ID']) ?>" data-placement="top" rel="tooltip"  class="btn_remove_post_type cursor fa fa-times booking-item-wishlist-remove" data-original-title="<?php st_the_language('user_remove') ?>"></a>
    <a rel="tooltip" data-original-title="<?php st_the_language('user_edit') ?>" href="<?php echo esc_url(add_query_arg(array('sc'=>'edit-room-rental','id'=>get_the_ID()),get_the_permalink($page_my_account_dashboard))) ?>"  class="btn_remove_post_type cursor fa fa-edit booking-item-wishlist-remove" style="top:90px ; background: #ed8323 ; color: #fff"></a>
    <i rel="tooltip" data-original-title="<?php st_the_language('user_status') ?>" data-placement="top"  class="<?php echo esc_attr($icon_class) ?> cursor fa  booking-item-wishlist-remove" style="top: 60px;"></i>

    <div class="spinner user_img_loading ">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
    <a href="<?php the_permalink() ?>" class="booking-item">
        <div class="row">
            <div class="col-md-3">
                <?php
                $img = get_the_post_thumbnail( get_the_ID() , array(800,600,'bfi_thumb'=>true)) ;
                if(!empty($img)){
                    echo balanceTags($img);
                }else{
                    echo '<img width="800" height="600" alt="no-image" class="wp-post-image" src="'.bfi_thumb(get_template_directory_uri().'/img/no-image.png',array('width'=>800,'height'=>600)) .'">';
                }
                ?>
            </div>
            <div class="col-md-9">
                <h5 class="booking-item-title">
                    <?php the_title()?>
                </h5>
                <div class="text-small">
                    <p style="margin-bottom: 10px;">
                        <?php
                        $excerpt=get_the_content( );
                        $excerpt=strip_tags($excerpt);
                        echo TravelHelper::cutnchar($excerpt,200);
                        ?>
                    </p>
                </div>
                <ul class="booking-item-features booking-item-features-sign clearfix">
                    <?php if($adult=get_post_meta(get_the_ID(),'adult_number',true)): ?>
                        <li rel="tooltip" data-placement="top" title="" data-original-title="<?php st_the_language('user_adults_occupancy')?>"><i class="fa fa-male"></i><span class="booking-item-feature-sign">x <?php echo esc_html($adult) ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if($child=get_post_meta(get_the_ID(),'children_number',true)): ?>
                        <li rel="tooltip" data-placement="top" title="" data-original-title="<?php st_the_language('user_childs')?>"><i class="im im-children"></i><span class="booking-item-feature-sign">x <?php echo esc_html($child) ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if($bed=get_post_meta(get_the_ID(),'bed_number',true)): ?>
                        <li rel="tooltip" data-placement="top" title="" data-original-title="<?php st_the_language('user_bebs')?>"><i class="im im-bed"></i><span class="booking-item-feature-sign">x <?php echo esc_html($bed) ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if($room_footage=get_post_meta(get_the_ID(),'room_footage',true)): ?>
                        <li rel="tooltip" data-placement="top" title="" data-original-title="<?php st_the_language('user_room_footage')?>"><i class="im im-width"></i><span class="booking-item-feature-sign"><?php echo esc_html($room_footage) ?></span>
                        </li>
                    <?php endif;?>
                </ul>
                <ul class="booking-item-features booking-item-features-small clearfix">
                    <?php get_template_part('single-hotel/room-facility','list') ;?>
                </ul>
            </div>
        </div>
    </a>
</li>

