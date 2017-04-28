<div class=" <?php echo 'div_map_item_'.get_the_ID() ?>">
    <?php
    $thumb_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
    $link = st_get_link_with_search(get_permalink(), array('start', 'end', 'room_num_search', 'adult_number','children_num'), $_GET);
    ?>
    <?php echo STFeatured::get_featured(); ?>
    <div class="thumb">
        <?php if($discount_text=get_post_meta(get_the_ID(),'discount_text',true)){ ?>
            <?php STFeatured::get_sale($discount_text); ?>
        <?php }?>
        <header class="thumb-header">
            <a class="hover-img" href="<?php echo esc_url($link)?>">
                <?php the_post_thumbnail(array(360, 270, 'bfi_thumb' => TRUE)) ?>
                <h5 class="hover-title-center"><?php st_the_language('book_now')?> </h5>
            </a>
        </header>
        <div class="thumb-caption">
            <?php
            $view_star_review = st()->get_option('view_star_review', 'review');
            if($view_star_review == 'review') :
                ?>
                <ul class="icon-group text-color">
                    <?php
                    $avg = STReview::get_avg_rate();
                    echo TravelHelper::rate_to_string($avg);
                    ?>
                </ul>
            <?php elseif($view_star_review == 'star'): ?>
                <ul class="icon-list icon-group booking-item-rating-stars text-color">
                    <?php
                    $star = STHotel::getStar();
                    echo  TravelHelper::rate_to_string($star);
                    ?>
                </ul>
            <?php endif; ?>
            <h5 class="thumb-title"><a class="text-darken" href="<?php echo esc_url($link)?>"><?php the_title()?></a></h5>
            <?php if ($address = get_post_meta(get_the_ID(), 'address', TRUE)): ?>
                <p class="mb0">
                    <small> <i class="fa fa-map-marker">&nbsp;</i> <?php echo esc_html($address) ?></small>
                </p>
            <?php endif;?>
            <p class="mb0 text-darken item_price_map">
                <span class="text-lg lh1em"><?php echo TravelHelper::format_money(STHotel::get_avg_price()) ?></span>
                <small> <?php st_the_language('avg/night')?></small>
            </p>
        </div>
    </div>
    <div class="gap"></div>
</div>
