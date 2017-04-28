<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User cruise
 *
 * Created by ShineTheme
 *
 */
?>
<div class="st-create">
    <h2><?php st_the_language('my_cruise') ?></h2>
</div>
<ul id="" class="booking-list booking-list-wishlist ">
<?php
    $paged  = (get_query_var('paged' ) ? get_query_var('paged' ) : 1) ; 
    $args = array(
        'post_type' => 'cruise',
        'post_status'=>'publish , draft , trash',
        'author'=>$data->ID,
        'posts_per_page'=>10,
        'paged'=>$paged
    );
    query_posts($args);
    if ( have_posts() ) {
        while (have_posts()) {
            the_post();
            echo st()->load_template('user/loop/loop', 'cruise' ,get_object_vars($data));
        }
    }else{
        echo '<h5>'.st_get_language('no_cruise').'</h5>';
    };
?>
</ul>
<?php st_paging_nav() ?>
<?php  wp_reset_query(); ?>


