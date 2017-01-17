<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Single hotel
 *
 * Created by ShineTheme
 *
 */
get_header();
$detail_hotel_layout=apply_filters('st_hotel_detail_layout',st()->get_option('hotel_single_layout'));
$menu_style = st()->get_option('menu_style' , "1");
if($menu_style != '3'):
    if(get_post_meta($detail_hotel_layout , 'is_breadcrumb' , true) !=='off'){
        get_template_part('breadcrumb');
    }
endif;

$layout_class = get_post_meta($detail_hotel_layout , 'layout_size' , true);
if (!$layout_class) $layout_class = "container";
?>

<div itemscope itemtype="http://schema.org/Hotel">
<div class="<?php echo balanceTags($layout_class) ; ?>">
    <div class="booking-item-details">
        <?php

        if($detail_hotel_layout) {
            $content=STTemplate::get_vc_pagecontent($detail_hotel_layout);
            echo balanceTags($content);
        }else{
            echo st()->load_template('hotel/single','default');
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
    <span class="hidden st_single_hotel"></span>
</div>
<?php  get_footer( ) ?>