<?php
/**
 * Created by PhpStorm.
 * User: MSI
 * Date: 21/08/2015
 * Time: 9:45 SA
 */
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_script( 'tabs-js', '/wp-includes/js/jquery/ui/tabs.min.js', array('wpb_composer_front_js'), false, true);
    wp_enqueue_script( 'tabs-ui-js', '/wp-content/plugins/js_composer/assets/lib/bower/jquery-ui-tabs-rotate/jquery-ui-tabs-rotate.min.js', array('wpb_composer_front_js'), false, true);
} );