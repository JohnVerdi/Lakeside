<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Single cars
 *
 * Created by ShineTheme
 *
 */
get_header();

$booking_period = get_post_meta(get_the_ID(), 'cars_booking_period', true);
if(empty($booking_period)) $booking_period = 0;
$detail_cars_layout=apply_filters('st_cars_detail_layout',st()->get_option('cars_single_layout'));
if(get_post_meta($detail_cars_layout , 'is_breadcrumb' , true) !=='off'){
    get_template_part('breadcrumb');
}
$layout_class = get_post_meta($detail_cars_layout , 'layout_size' , true);
if (!$layout_class) $layout_class = "container";
?>

<div itemscope itemtype="http://schema.org/Product">
    <div class="mfp-with-anim mfp-dialog mfp-search-dialog mfp-hide" id="search-dialog" data-booking-period="<?php echo esc_attr($booking_period); ?>">
        <div class="overlay-form"><i class="fa fa-refresh text-color"></i></div>
        <?php echo st()->load_template('cars/change-search-form');?>
    </div>
    <div class="<?php echo balanceTags($layout_class) ; ?>">
        <div class="booking-item-details no-border-top">
            <header class="booking-item-header ">
                <?php echo STTemplate::message()?>
                <div class="row">
                    <div class="col-md-8">
						<?php echo st()->load_template('cars/elements/header') ?>

                    </div>
                    <div class="col-md-4">
                        <?php
                        $info_price = STCars::get_info_price();
                        $price = $info_price['price'];
                        $price_origin = $info_price['price_origin'];
                        $count_sale = $info_price['discount'];
                        $show_price = st()->get_option('show_price_free');
                        if($show_price == 'on' || !empty($price)) :?>
                            <p class="booking-item-header-price">
                                <small><?php  st_the_language('car_price') ?></small>
                                <?php if($price_origin != $price){ ?>
                                     <span class=" onsale">
                                      <?php echo TravelHelper::format_money( $price_origin )?>
                                     </span>
                                    <i class="fa fa-long-arrow-right"></i>
                                <?php } ?>
                                <span class="text-lg">
                                        <?php echo TravelHelper::format_money($price) ?>
                                </span>/<?php echo strtolower(STCars::get_price_unit('label')) ?>
                            </p>
                        <?php endif; ?>    
                    </div>
                </div>
            </header>
            <div class="gap gap-small"></div>
            <?php
            
            if($detail_cars_layout)
            {
                echo  STTemplate::get_vc_pagecontent($detail_cars_layout);
            }else{
                echo st()->load_template('cars/single','default');
            }
            ?>
        </div><!-- End .booking-item-details-->
    </div>
	<!--Review Rich Snippets-->
	<div itemprop="aggregateRating" class="hidden" itemscope itemtype="http://schema.org/AggregateRating">
		<div><?php _e('Book rating:',ST_TEXTDOMAIN)?>
			<?php printf(__('%s out of %s with %s ratings',ST_TEXTDOMAIN),
				'<span itemprop="ratingValue">'.STReview::get_avg_rate().'</span>',
				'<span itemprop="bestRating">5</span>',
				'<span itemprop="ratingCount">'.get_comments_number().'</span>'
			); ?>
		</div>
	</div>
	<!--End Review Rich Snippets-->
</div>
    <span class="hidden st_single_car"></span>

<?php get_footer( ) ?>