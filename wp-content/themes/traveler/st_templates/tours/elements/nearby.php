<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Tours nearby
 *
 * Created by ShineTheme
 *
 */

$tour=new STTour();
$nearby_posts=$tour->get_near_by();


if($nearby_posts and !empty($nearby_posts))
{
	if(empty($font_size)) $font_size='3';
	if(!empty($title)){
		?>
		<h<?php echo esc_attr($font_size) ?>>
            <?php echo esc_html($title); ?>
            <span class="title_bol"><?php echo the_title(); ?></span>
        </h<?php echo esc_attr($font_size) ?>>
		<?php
	}

	global $post;
    echo "<ul class='booking-list'>";
    foreach($nearby_posts as $key=>$post)
    {
        setup_postdata($post);
        $info_price = STTour::get_info_price();
        ?>
        <li <?php post_class('item-nearby')?>>
            <?php echo STFeatured::get_featured(); ?>
            <div class="booking-item booking-item-small">
                <div class="row">
                    <div class="col-xs-4">

                        <a href="<?php the_permalink()?>">
                            <?php the_post_thumbnail(array(100,100))?>
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
                        <?php if(!empty( $info_price[ 'price_new' ] ) and $info_price[ 'price_new' ] > 0) { ?>
                            <span class="booking-item-price-from"><?php st_the_language( 'tour_from' ) ?></span>
                        <?php } ?>
                        <span class="booking-item-price list_tour_4">
                           <?php echo STTour::get_price_html( false , false , '-' ); ?>
                        </span>
                        <?php if(!empty( $info_price[ 'discount' ] ) and $info_price[ 'discount' ] > 0 and $info_price[ 'price_new' ] > 0) { ?>
                            <?php echo STFeatured::get_sale( $info_price[ 'discount' ] ); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </li>


        <?php
    }
    echo "</ul>";
    wp_reset_query();
    wp_reset_postdata();
}