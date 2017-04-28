<ul class='booking-list'>
    <?php
    while(have_posts()):
        the_post();
        $info_price = STTour::get_info_price();
        ?>
        <li <?php post_class('item-nearby')?>>
            <?php echo STFeatured::get_featured(); ?>
            <div class="booking-item booking-item-small">
                <div class="row">
                    <div class="col-xs-4">

                        <a href="<?php the_permalink()?>">
                            <?php if (has_post_thumbnail()){
                                echo the_post_thumbnail(array(100,100));
                            }else {
                                echo st_get_default_image();
                            }?>
                        </a>
                    </div>
                    <div class="col-xs-4">
                        <h5 class="booking-item-title"><a href="<?php the_permalink()?>"><?php the_title()?></a> </h5>
                        <ul class="icon-group booking-item-rating-stars">
                            <?php
                            $avg = STReview::get_avg_rate();
                            echo TravelHelper::rate_to_string($avg);
                            ?>
                        </ul>
                    </div>
                    <div class="col-xs-4">
                        <?php if(!empty( $info_price['price_new'] ) and $info_price['price_new']>0) { ?>
                            <span class="booking-item-price-from"><?php st_the_language('tour_from') ?></span>
                        <?php } ?>
                       <span class="booking-item-price list_tour_4">
                            <?php echo STTour::get_price_html(false,false,' <br> '); ?>
                       </span>
                        <?php if(!empty( $info_price['discount'] ) and $info_price['discount']>0 and $info_price['price_new'] >0) { ?>
                            <?php echo STFeatured::get_sale($info_price['discount']); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </li>
    <?php
    endwhile;
    ?>
</ul>