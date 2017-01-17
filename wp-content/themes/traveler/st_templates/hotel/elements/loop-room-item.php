<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Hotel loop room item
 *
 * Created by ShineTheme
 *
 */

//check is booking with modal
$st_is_booking_modal=apply_filters('st_is_booking_modal',false);
global $post;
?>
<li <?php post_class()?>>
    <div class="booking-item">
        <div class="row">
            <div class="col-md-3">
                <?php 
                $link = get_the_permalink();
                if (STInput::request('start') and STInput::request('end')){
                    $link = esc_url( 
                        add_query_arg( array(
                            'check_in'=>STInput::request('start'),
                            'check_out'=>STInput::request('end'),
                            'room_num_search'=>STInput::request('room_num_search') , 
                            'child_number'=> STInput::request('child_number'),
                            'adult_number'=> STInput::request('adult_number')
                        ) , $link ) 
                    );
                }               

                ?>
                <a href="<?php echo esc_url($link); ?>" class="hover-img">
                <?php
                if(has_post_thumbnail() and get_the_post_thumbnail())
                {
                    the_post_thumbnail('thumbnail');
                }
                else
                {
                    if(function_exists('st_get_default_image'))
                        echo st_get_default_image();
                }
                ?>
                </a>
            </div>
            <div class="col-md-6">
                <h5 class="booking-item-title"><a href="<?php echo esc_url($link); ?>" title=""><?php the_title()?></a></h5>
                <div class="text-small">
                    <p style="margin-bottom: 10px;">
                    <?php
                    $excerpt=$post->post_excerpt;
                    $excerpt=strip_tags($excerpt);
                    echo TravelHelper::cutnchar($excerpt,120);
                    ?>
                    </p>
                </div>

                <ul class="booking-item-features booking-item-features-sign clearfix">
                    <?php if($adult=get_post_meta(get_the_ID(),'adult_number',true)): ?>
                        <li rel="tooltip" data-placement="top" title="" data-original-title="<?php st_the_language('adults_occupany')?>"><i class="fa fa-male"></i><span class="booking-item-feature-sign">x <?php echo esc_html($adult) ?></span>
                        </li>
                    <?php endif; ?>

                    <?php if($child=get_post_meta(get_the_ID(),'children_number',true)): ?>
                        <li rel="tooltip" data-placement="top" title="" data-original-title="<?php st_the_language('childs')?>"><i class="im im-children"></i><span class="booking-item-feature-sign">x <?php echo esc_html($child) ?></span>
                        </li>
                    <?php endif; ?>

                    <?php if($bed=get_post_meta(get_the_ID(),'bed_number',true)): ?>
                        <li rel="tooltip" data-placement="top" title="" data-original-title="<?php st_the_language('bebs')?>"><i class="im im-bed"></i><span class="booking-item-feature-sign">x <?php echo esc_html($bed) ?></span>
                        </li>
                    <?php endif; ?>


                    <?php if($room_footage=get_post_meta(get_the_ID(),'room_footage',true)): ?>

                        <li rel="tooltip" data-placement="top" title="" data-original-title="<?php st_the_language('room_footage')?>"><i class="im im-width"></i><span class="booking-item-feature-sign"><?php echo esc_html($room_footage) ?></span>
                        </li>
                    <?php endif;?>
                </ul>
                <ul class="booking-item-features booking-item-features-small clearfix">
                    <?php get_template_part('single-hotel/room-facility','list') ;?>

                </ul>
            </div>
            <div class="col-md-3">
                    <?php
                        $start = TravelHelper::convertDateFormat(STInput::request('start'));
                        $end = TravelHelper::convertDateFormat(STInput::request('end'));
                        $numberday = TravelHelper::dateDiff($start, $end);
                        $is_search_room = STInput::request('is_search_room');
                    ?>
                    <?php if($start and $end and $is_search_room){ ?>
                        <?php
                            $room_id = get_the_ID();
                            $room_num_search = intval(STInput::request('room_num_search', 1));
                            $sale_price = STPrice::getRoomPrice($room_id, strtotime($start), strtotime($end), $room_num_search);
                            $total_price = STPrice::getRoomPriceOnlyCustomPrice($room_id, strtotime($start), strtotime($end), $room_num_search);
                        ?>
                        <?php if($sale_price < $total_price): ?>
                        <span class="text-lg  onsale mr20">
                            <?php echo TravelHelper::format_money($total_price)?>
                        </span>
                        <br />
                        <?php endif; ?>
                        <span class="booking-item-price">
                            <?php echo TravelHelper::format_money($sale_price)?>
                        </span>
                        <span class="booking-item-price-unit"><?php printf(__('/ %d night(s)',ST_TEXTDOMAIN),$numberday) ?></span>
                        <br>
                        <?php 
                            $external = STRoom::get_external_url() ;
                            $link = ($external)? $external : $link ; 
                        ?>
                        <a href="<?php echo $link; ?>" class="btn btn-primary btn_hotel_booking"><?php echo st_get_language('book'); ?></a>

                    <?php }else{ ?>
                        <button class="btn btn-primary btn-show-price" type="button"><?php _e("Show Price",ST_TEXTDOMAIN)?></button>
                    <?php } ?>
                    <?php
                    if(st()->get_option('booking_modal','off')=='on'){?>
                        <div class="mfp-with-anim mfp-dialog mfp-search-dialog mfp-hide" id="hotel_booking_<?php the_ID()?>">
                            <?php echo st()->load_template('hotel/modal_booking');?>
                        </div>
                        
                    <?php }?>

            </div>
        </div>
    </div>
</li>