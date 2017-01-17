<?php 
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.1.3
**/
$paged=STInput::get('room_page',1);
$item_id = get_the_ID();
extract($attr);
/**
* Extract $attr to:
*@param post_per_page
*@param number_in_row
*@param order_by
*@param order
**/
$query = array(
	'post_type' => 'rental_room',
	'posts_per_page' => $post_per_page,
	'order_by' => $order_by,
	'paged' => $paged,
	'order' => $order,
	'meta_query' => array(
		array(
			'key' => 'room_parent',
			'value' => $item_id ,
			'compare' => '='
			)
		)
	);
query_posts( $query );
if(have_posts()):
echo '<div class="row">';
echo '
	<div class="col-xs-12">
		<h4>'.$header_title.'</h4>
	</div>
';
echo '<div class="st_list_rental_room owl-carousel clearfix" style="padding: 0 30px;">';
while(have_posts()): the_post();

?>
<div <?php post_class('item')?>>
    <div class="booking-item">
        <div class="row">
            <div class="col-md-12">
                <?php 
                	$link = get_the_permalink();
                
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
            <div class="col-md-12">
                <h5 class="booking-item-title mt10"><a href="<?php echo esc_url($link); ?>" title=""><?php the_title()?></a></h5>

                <ul class="booking-item-features booking-item-features-sign clearfix mt10">
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
            </div>
        </div>
    </div>
</div>
<?php
endwhile; endif; wp_reset_postdata(); wp_reset_query();
?>
</div></div>