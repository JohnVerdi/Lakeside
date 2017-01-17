<?php
/**
* @since 1.1.3
**/
get_header();
$layout = st()->get_option('rental_room_layout','');
if(get_post_meta(get_the_ID(), 'st_custom_layout', true))    $layout = get_post_meta(get_the_ID(), 'st_custom_layout', true);

if(get_post_meta($layout , 'is_breadcrumb' , true) !=='off'){
    get_template_part('breadcrumb');
}
?>
<?php 
if(have_posts()): the_post(); 
?>
<?php 
    if(has_post_thumbnail() and get_the_post_thumbnail())
        $thumb_url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );

    $gallery=get_post_meta(get_the_ID(),'gallery',true);
    $gallery_array=explode(',',$gallery);
    $fancy_arr = array();
    if(is_array($gallery_array) and !empty($gallery_array)){
        foreach($gallery_array as $key=>$value){
            $img_link=wp_get_attachment_image_src($value,array(800,600,'bfi_thumb'=>true));
            $fancy_arr[] = array(
                'href' => $img_link[0],
                'title' => ''
                );
        }
    }

?>
<div id="single-room"  class="booking-item-details">
    <div class="thumb">
        <a href="javascript:;" id="fancy-gallery">
            <?php if(has_post_thumbnail() and get_the_post_thumbnail())
            {
                the_post_thumbnail(array(1600, 500), array('class'=> 'fancy-responsive'));
            }else{
                echo "<img src='".get_template_directory_uri().'/img/default/1600x500.png'."' class='fancy-responsive' alt='".get_the_title()."'>";
            } ?>
        </a>
    </div>
    <div class="container">
	<?php        
        if($layout && !empty($layout))
        {
            echo STTemplate::get_vc_pagecontent($layout);
        }else{
            echo do_shortcode('
                [vc_row][vc_column width="2/3"][st_rental_room_header][st_rental_room_content][st_rental_room_facility choose_taxonomies="room_rental_type"][st_rental_room_gallery style="slide"][/vc_column][vc_column width="1/3"][st_related_rental_room header_title="'.__("Related Rental Room",ST_TEXTDOMAIN).'" number_of_room="5" show_excerpt="yes"][/vc_column][/vc_row][vc_row full_width="" parallax="" parallax_image="" row_fullwidth="no" parallax_class="no" bg_video="no" row_id=""][vc_column width="2/3" css=".vc_custom_1435720542975{padding-top: 20px !important;}"][st_rental_room_review][/vc_column][vc_column width="1/3"][/vc_column][/vc_row]
            ');
        }
    ?>
    </div>
</div>
<?php endif; ?>
<span class="hidden st_single_rental_room" data-fancy_arr = '<?php echo (is_array($fancy_arr) and count($fancy_arr)) ;?>'></span>
<?php get_footer( ) ?>
