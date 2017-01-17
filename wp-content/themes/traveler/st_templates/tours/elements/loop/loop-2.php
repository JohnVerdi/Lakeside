<?php
    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * Tours loop content 2
     *
     * Created by ShineTheme
     *
     */
    $col = 12 / 3;
    $info_price = STTour::get_info_price();

$url=st_get_link_with_search(get_permalink(),array('start','end','duration','people'),$_GET);
if(empty($taxonomy)) $taxonomy=false;
?>
<div class="col-md-<?php echo esc_attr($col) ?> col-sm-6 col-xs-12 style_box">
    <?php echo STFeatured::get_featured(); ?>
    <div class="thumb">
        <?php if(!empty( $info_price['discount'] ) and $info_price['discount']>0 and $info_price['price_new'] >0) { ?>
            <?php echo STFeatured::get_sale($info_price['discount']); ?>
        <?php } ?>
        <header class="thumb-header">
            <a href="<?php echo esc_url($url) ?>" class="hover-img">
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
                <a href="<?php echo esc_url($url)?>" class="text-darken">
                    <?php the_title(); ?>
                </a>
            </h5>
            <?php if($address = get_post_meta(get_the_ID(),'address',true)) {?>
            <p class="mb0">
                <small><i class="fa fa-map-marker"></i> 
                    <?php
                        if(!empty($address)){
                            echo esc_html($address);
                        }
                    ?>
                </small>
            </p>
            <?php } ?>
            <p class="mb0">
                <small>
                    <?php $type_tour = get_post_meta(get_the_ID(),'type_tour',true); ?>
                    <?php if($type_tour == 'daily_tour'){
                        
                        $day = STTour::get_duration_unit();
                        if($day) {
                            ?>
                            <i class="fa fa-calendar"></i>
                            <?php echo esc_html($day) ?>
                            
                        <?php
                        }}else{ ?>
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
                </small>
            </p>
            <?php
            $is_st_show_number_user_book = st()->get_option('st_show_number_user_book','off');
            if($is_st_show_number_user_book == 'on'):
            ?>
            <p class="mb0 st_show_user_booked">
                <small>
                    <?php $info_book = STTour::get_count_book(get_the_ID());?>
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
                </small>
            </p>
            <?php endif ?>
            <div class="text-darken">
                <?php echo st()->load_template( 'tours/elements/attribute' , 'list' ,array("taxonomy"=>$taxonomy));?>
            </div>
            <p class="mb0 text-darken">
                <?php echo STTour::get_price_html(get_the_ID()) ?>
            </p>
        </div>
    </div>
</div>

