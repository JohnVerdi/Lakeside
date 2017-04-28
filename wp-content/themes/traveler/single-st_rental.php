<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Single rental
 *
 * Created by ShineTheme
 *
 */
get_header();

$detail_hotel_layout=apply_filters('rental_single_layout',st()->get_option('rental_single_layout'));
if(get_post_meta($detail_hotel_layout , 'is_breadcrumb' , true) !=='off'){
    get_template_part('breadcrumb');
}
$layout_class = get_post_meta($detail_hotel_layout , 'layout_size' , true);
if (!$layout_class) $layout_class = "container";
?>

<div itemscope itemtype="http://schema.org/Product">
    <div class="<?php echo balanceTags($layout_class) ; ?>">
        <div class="booking-item-details" style="padding-top: 20px">
            <?php
            
            if($detail_hotel_layout){
                $content=STTemplate::get_vc_pagecontent($detail_hotel_layout);
                echo balanceTags( $content);
            }else{
                echo st()->load_template('rental/single','default');
            }
            ?>
            <div class="gap"></div>
        </div>
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
    <span class="hidden st_single_rental"></span>
<?php  get_footer( ) ?>