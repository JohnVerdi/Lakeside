<?php
/**
 * Created by PhpStorm.
 * User: MSI
 * Date: 21/08/2015
 * Time: 9:45 SA
 */

function get_children_template_directory_uri() {
    $directory_template = get_template_directory_uri();
    $directory_child = str_replace('traveler', '', $directory_template) . 'traveler-childtheme';

    return $directory_child;
}

add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_script( 'tabs-js', '/wp-includes/js/jquery/ui/tabs.min.js', array('wpb_composer_front_js'), false, true);
    wp_enqueue_script( 'tabs-ui-js', '/wp-content/plugins/js_composer/assets/lib/bower/jquery-ui-tabs-rotate/jquery-ui-tabs-rotate.min.js', array('wpb_composer_front_js'), false, true);
} );

register_sidebar( array(
    'name'          => __( 'Streamline Homepage Search', 'twentyfifteen' ),
    'id'            => 'streamlinecore_home_search',
    'description'   => __( 'Add Streamline Core Search Widget here.', 'twentyfifteen' ),
    'before_widget' => '<div id="streamlinecore_home_search" class="container">',
    'after_widget'  => '</div>',
    'before_title'  => '<h1>',
    'after_title'   => '</h1>',
) );

if( isset($post_id)){
    wp_get_post_revisions( $post_id, $args );
}