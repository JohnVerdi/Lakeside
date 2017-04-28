<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User cruise cabin
 *
 * Created by ShineTheme
 *
 */
?>
<div class="st-create">
    <h2><?php st_the_language('my_cruise_cabin') ?></h2>
</div>
<ul id="data_whislist" class="booking-list booking-list-wishlist ">
<?php
    /*if(!empty($_REQUEST['paged'])){
        $paged = $_REQUEST['paged'];
    }else{
        $paged = 1;
    }*/
    $paged  = (get_query_var('paged' ) ? get_query_var('paged' ) : 1) ; 
    $args = array(
        'post_type' => 'cruise_cabin',
        'post_status'=>'publish , draft',
        'author'=>$data->ID,
        'posts_per_page'=>10,
        'paged'=>$paged
    );
    query_posts($args);
    if ( have_posts() ) {
        while (have_posts()) {
            the_post();
            echo st()->load_template('user/loop/loop', 'cruise-cabin' ,get_object_vars($data));
        }
    }else{
        echo '<h1>'.st_get_language('no_cruise_cabin').'</h1>';
    };
?>
</ul>
<?php st_paging_nav() ?>
<?php  wp_reset_query(); ?>


