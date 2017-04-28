<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Activity element loop 1
 *
 * Created by ShineTheme
 *
 */
$link = st_get_link_with_search(get_permalink(), array('start'), $_GET);
$info_price = STActivity::get_info_price();

$thumb_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
$type_activity = get_post_meta( get_the_ID() , 'type_activity' , true );
$title_type_activity = __('Specific Date',ST_TEXTDOMAIN);
if($type_activity == 'specific_date')
    $title_type_activity = __('Specific Date',ST_TEXTDOMAIN);
if($type_activity == 'daily_activity')
    $title_type_activity = __('Daily Activity',ST_TEXTDOMAIN);
?>
<li <?php post_class( 'booking-item' ) ?>>
    <?php echo STFeatured::get_featured(); ?>
    <div class="row">
        <div class="col-md-3">
            <div class="booking-item-img-wrap st-popup-gallery">
                <?php

                    if(has_post_thumbnail() and get_the_post_thumbnail()){
                        ?><a href="<?php echo esc_url($thumb_url) ?>" class="st-gp-item"> <?php
                            the_post_thumbnail( array( 360, 270, 'bfi_thumb' => true ) );
                        ?></a><?php
                    }else{
                        echo st_get_default_image() ;
                    }
                    $count = 0;
                    $gallery = get_post_meta(get_the_ID(), 'gallery', TRUE);

                    $gallery = explode(',', $gallery);


                    if (!empty($gallery) and $gallery[0]) {
                        $count += count($gallery);
                    }

                    if (has_post_thumbnail()) {
                        $count++;
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
                        foreach ($gallery as $key => $value) {
                            $img_link = wp_get_attachment_image_src($value, array(800, 600, 'bfi_thumb' => TRUE));
                            if (isset($img_link[0]))
                                echo "<a class='st-gp-item' href='{$img_link[0]}'></a>";
                        }

                    } ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="booking-item-rating">
                <ul class="icon-group booking-item-rating-stars">
                    <?php
                    $avg = STReview::get_avg_rate();
                    echo TravelHelper::rate_to_string( $avg );
                    ?>
                </ul>
                <span class="booking-item-rating-number"><b><?php echo esc_html( $avg ) ?></b> <?php st_the_language( 'of' ) ?>5</span>
                <small>
                    (<?php comments_number( st_get_language( 'no_review' ) , st_get_language( '1_review' ) , "% " . st_get_language( 'reviews' ) ) ?>)
                </small>
            </div>
            <a class="" href="<?php echo $link; ?>">
                <h5 class="booking-item-title"><?php the_title() ?></h5>
            </a>
            <?php if($address = get_post_meta( get_the_ID() , 'address' , true )): ?>
                <p class="booking-item-address"><i class="fa fa-map-marker"></i> <?php echo esc_html( $address ) ?>
                </p>
            <?php endif; ?>
            <p class="booking-item-address">
                <i class="fa fa-cogs"></i> <?php _e( "Activity Type: ", ST_TEXTDOMAIN ) ?><?php echo esc_html( $title_type_activity ) ?>
            </p>
            <?php /*if($type_activity == 'specific_date'): */?><!--
                <div class="package-info">
                    <i class="fa fa-calendar"></i>
                    <?php
/*                    $check_in = get_post_meta( get_the_ID() , 'check_in' , true );
                    if(!empty( $check_in )) {
                        $check_in = strtotime( $check_in );
                        echo date_i18n( TravelHelper::getDateFormat(), $check_in );
                    }

                    $check_out = get_post_meta( get_the_ID() , 'check_out' , true );
                    if(!empty( $check_out )) {
                        $check_out = strtotime( $check_out );
                        echo ' <i class="fa fa-arrow-right"></i> ' . date_i18n( TravelHelper::getDateFormat(), $check_out );
                    }
                    */?>
                </div>
            --><?php /*endif */?>
            <p class="booking-item-description">
                <?php echo st_get_the_excerpt_max_charlength( 100 ) ?>
            </p>
            <?php
            if(!empty($taxonomy)){
                echo st()->load_template( 'activity/elements/attribute' , 'list' ,array("taxonomy"=>$taxonomy));
            }
            ?>
        </div>
        <div class="col-md-3">
            <?php if(!empty( $info_price['price_new'] ) and $info_price['price_new']>0) { ?>
                <span class="booking-item-price-from"><?php st_the_language( 'from' ) ?></span>
            <?php } ?>
            <?php echo STActivity::get_price_html( get_the_ID() , false , '<br>' , 'booking-item-price' ); ?>
            <br>
            <a href="<?php echo $link; ?>"><span
                    class="btn btn-primary btn_book"><?php st_the_language( 'book_now' ) ?></span></a>
            <?php if(!empty( $info_price['discount'] ) and $info_price['discount']>0 and $info_price['price_new'] >0) { ?>
                <?php echo STFeatured::get_sale($info_price['discount']); ?>
            <?php } ?>
        </div>
    </div>
</li>
