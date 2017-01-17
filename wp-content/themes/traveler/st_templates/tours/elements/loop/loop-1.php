<?php
    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * Tours loop content 1
     *
     * Created by ShineTheme
     *
     */
    $tours = new STTour();
    $info_price = STTour::get_info_price();

$url=st_get_link_with_search(get_permalink(),array('start','end','duration','people'),$_GET);
if(empty($taxonomy)) $taxonomy=false;
?>
<li <?php post_class('booking-item') ?>>
    <?php echo STFeatured::get_featured(); ?>
    <div class="row">
        <div class="col-md-3">
            <div class="booking-item-img-wrap">
                <a class="" href="<?php echo esc_url($url)?>">
                <?php 
                if (has_post_thumbnail()){
                the_post_thumbnail(array(360, 270, 'bfi_thumb' => true));
                }
                else{
                    echo st_get_default_image();
                }
                 ?>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="booking-item-rating">
                <ul class="icon-group booking-item-rating-stars">
                    <?php
                        $avg = STReview::get_avg_rate();
                        echo TravelHelper::rate_to_string($avg);
                    ?>
                </ul>
                                <span
                                    class="booking-item-rating-number"><b><?php echo esc_html($avg) ?></b> <?php st_the_language('tour_of_5') ?></span>
                <small>
                    (<?php comments_number(__('no review',ST_TEXTDOMAIN), __('1 review',ST_TEXTDOMAIN),__('% reviews',ST_TEXTDOMAIN)); ?>)
                </small>
            </div>
            <a class="" href="<?php echo esc_url($url)?>">
                <h5 class="booking-item-title"><?php the_title() ?></h5>
            </a>
            <?php if ($address = get_post_meta(get_the_ID(), 'address', true)): ?>
                <p class="booking-item-address"><i class="fa fa-map-marker"></i> <?php echo esc_html($address) ?>
                </p>
            <?php endif; ?>
            <div class="package-info">
                <?php $max_people = get_post_meta(get_the_ID(),'max_people', true) ?>
                <i class="fa    fa-users"></i>
                <?php echo esc_html($max_people.' ') ; st_the_language('tour_people')?>
            </div>
            <div class="package-info">
                <?php $type_tour = get_post_meta(get_the_ID(),'type_tour',true); ?>
                <?php if($type_tour == 'daily_tour'){
                        
                        $day = STTour::get_duration_unit();
                        if($day) {
                            ?>
                            <i class="fa fa-calendar"></i>
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
                        <?php
                        $format=TravelHelper::getDateFormat();
                        $date = date_i18n($format,strtotime($check_in)).' <i class="fa fa-long-arrow-right"></i> '.date_i18n($format,strtotime($check_out));
                        echo balanceTags($date);
                    endif;
                    ?>
                <?php } ?>
            </div>
            <?php
            $is_st_show_number_user_book = st()->get_option('st_show_number_user_book','off');
            if($is_st_show_number_user_book == 'on'):
            ?>
                <div class="package-info st_show_user_booked">
                <?php $info_book = STTour::get_count_user_book(get_the_ID());?>
                <i class="fa  fa-user"></i>
                                    <span class="">
                                        <?php
                                        if($info_book > 1){
                                            echo sprintf( __( '%d users booked',ST_TEXTDOMAIN ), $info_book );
                                        }else{
                                            echo sprintf( __( '%d user booked',ST_TEXTDOMAIN ), $info_book );
                                        }
                                        ?>
                                    </span>
            </div>
            <?php endif ?>

            <?php
            if(!empty($taxonomy)){
                echo st()->load_template( 'tours/elements/attribute' , 'list' ,array("taxonomy"=>$taxonomy));
            }
            ?>

        </div>
        <div class="col-md-3">
            <?php if(!empty( $info_price['price_new'] ) and $info_price['price_new']>0) { ?>
                <span class="booking-item-price-from"><?php st_the_language('tour_from') ?></span>
            <?php } ?>
            <?php 
                if (empty($tour_id)) {
                    $tour_id  = get_the_ID();
                }
            ?>
            <?php echo STTour::get_price_html($tour_id); ?>
            <span class="info_price"></span>
            <a href="<?php echo esc_url($url)?>">
                <span class="btn btn-primary btn_book"><?php st_the_language('tour_book_now') ?></span>
            </a>
            <?php if(!empty( $info_price['discount'] ) and $info_price['discount']>0 and $info_price['price_new'] >0) { ?>
                <?php echo STFeatured::get_sale($info_price['discount']); ?>
            <?php } ?>
        </div>
    </div>
</li>