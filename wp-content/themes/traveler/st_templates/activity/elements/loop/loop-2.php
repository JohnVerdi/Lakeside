<?php
    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * Activity element loop 2
     *
     * Created by ShineTheme
     *
     */
    $link = st_get_link_with_search(get_permalink(), array('start'), $_GET);
    $col = 12 / 3;
    $info_price = STActivity::get_info_price();


if(empty($taxonomy)) $taxonomy=false;
?>
<div class="col-md-<?php echo esc_attr($col) ?> col-sm-6 col-xs-12 list_tour style_box">
    <?php echo STFeatured::get_featured(); ?>
    <div class="thumb">
        <?php if(!empty( $info_price['discount'] ) and $info_price['discount']>0 and $info_price['price_new'] >0) { ?>
            <?php echo STFeatured::get_sale($info_price['discount']); ?>
        <?php } ?>
        <header class="thumb-header">
            <a href="<?php echo $link; ?>" class="hover-img">
                <?php
                    $img = get_the_post_thumbnail( get_the_ID() , array(800,600,'bfi_thumb'=>true)) ;
                    if(!empty($img)){
                        echo balanceTags($img);
                    }else{
                        echo '<img width="800" height="600" alt="no-image" class="wp-post-image" src="'.bfi_thumb(get_template_directory_uri().'/img/no-image.png',array('width'=>800,'height'=>600)) .'">';
                    }
                ?>
                <h5 class="hover-title-center"><?php st_the_language('book_now')?></h5>
            </a>
        </header>
        <div class="thumb-caption">
            <ul class="icon-group text-tiny text-color">
                <?php echo  TravelHelper::rate_to_string(STReview::get_avg_rate()); ?>
            </ul>
            <h5 class="thumb-title">
                <a href="<?php echo $link; ?>" class="text-darken">
                    <?php the_title(); ?>
                </a>
            </h5>
            <p class="mb0">
                <small><i class="fa fa-map-marker"></i>
                    <?php $address = get_post_meta(get_the_ID(),'address',true); ?>
                    <?php
                        if(!empty($address)){
                            echo esc_html($address);
                        }
                    ?>
                </small>
            </p>
            <?php echo st()->load_template( 'activity/elements/attribute' , 'list' ,array("taxonomy"=>$taxonomy));?>
            <p class="mb0 text-darken">

                <span class="text-lg lh1em text-color">
                      <?php echo STActivity::get_price_html(get_the_ID(),false,'<br>'); ?>
                </span>
            </p>
        </div>

    </div>
</div>

