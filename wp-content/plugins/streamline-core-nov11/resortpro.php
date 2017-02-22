<?php
/*
 * Plugin Name: StreamlineCore
 * Version: 2.2.0
 * Plugin URI: https://www.streamlinevrs.com/
 * Description: StreamlineCore API connection plugin.
 * Author: StreamlineCore
 * Author URI: https://www.streamlinevrs.com/
 * Requires at least: 4.2
 * Tested up to: 4.4.2
 *
 * Text Domain: streamline-core
 * Domain Path: /languages
 *
 * @package WordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

DEFINE( 'STREAMLINECORE_VERSION', '2.2.0' );
DEFINE( 'STREAMLINECORE_DEBUG', TRUE );
require_once( 'sibers/classes/AngryCurl.class.php' );
require_once( 'sibers/classes/SibersStrimlineAPI.php' );
// Load plugin class files
require_once('includes/class-resortpro.php');
require_once('includes/class-streamlinecore-upgrade.php');
require_once('includes/class-streamlinecore-settings.php');
require_once('includes/class-streamlinecore-ajax.php');
require_once('includes/class-streamlinecore-wrapper.php');
require_once('includes/class-resortpro-wrapper.php');

// Load plugin libraries
require_once('includes/lib/class-resortpro-admin-api.php');

// Load Widget Classes
require_once( 'widget/class.ResortProFilterWidget.php' );
require_once( 'widget/class.ResortProSearchWidget.php' );
require_once( 'widget/class.ResortProMapWidget.php' );
require_once( 'widget/class.ResortProFeaturedPropertyWidget.php' );
require_once( 'widget/class.ResortProTestimonialWidget.php' );


//Load sibers data


function resortpro_register_widgets()
{
    register_widget('ResortProMapWidget');
    register_widget('ResortProFilterWidget');
    register_widget('ResortProSearchWidget');
    register_widget('ResortProSearchWidget');
    register_widget('ResortProFeaturedPropertyWidget');
    register_widget('ResortProTestimonialWidget');
}
add_action('widgets_init', 'resortpro_register_widgets');


require 'plugin_update_check.php';
$MyUpdateChecker = new PluginUpdateChecker_2_0(
  'https://kernl.us/api/v1/updates/566086b004c34abe792efe06/',
  __FILE__,
  'streamline-core',
  1
);
// $MyUpdateChecker->purchaseCode = "somePurchaseCode";  <---- Optional!

function streamlinecore_error( $message ) {
  if ( STREAMLINECORE_DEBUG === true ) {
    echo '<br /><b>StreamlineCore Error:</b> ' . $message;
  }
}

function get_page_by_slug($page_slug, $output = OBJECT, $post_type = 'page')
{
    global $wpdb;
    $page = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", $page_slug, $post_type));
    if ($page)
        return get_post($page, $output);
    return null;
}

function get_resortpro_page_template(){
    $page_info = get_post_meta(get_option('resortpro_listing_detail_page_id'));


    $page_template = $page_info['_wp_page_template'][0];
    $template_path  =  '/' .$page_template. '';

    $plugin_path = dirname(__FILE__);


    if(!empty($page_template) && $page_template!="default"){

        if(file_exists(TEMPLATEPATH . $template_path)){
            @include (TEMPLATEPATH . $template_path);
        }else{

            @include ($plugin_path . '/includes/templates' . $template_path);
        }

        die();
    }
    return TRUE;
}

/**
 * Returns instance of ResortPro
 *
 *@since 1.0.0
 *@return object ResortPro
 */
function ResortPro() {
  $instance = ResortPro::instance( __FILE__, '1.0.0' );
  return $instance;
}

// get instance of ResortPro
$ResortPro = ResortPro::instance( __FILE__ );
// get instance of StreamlineCore_Upgrade
$StreamlineCore_Upgrade = StreamlineCore_Upgrade::get_instance();
// get instance of StreamlineCore_Settings
$StreamlineCore_Settings = StreamlineCore_Settings::get_instance();
// get instance of StreamlineCore_AJAX
$StreamlineCore_AJAX = StreamlineCore_AJAX::get_instance();

add_action('wp', array(&$ResortPro, 'handle_404'));
add_action( 'wp_ajax_paginate_results', array( &$ResortPro, 'search_results_paginate' ));
add_action( 'wp_ajax_nopriv_paginate_results', array( &$ResortPro, 'search_results_paginate' ));

add_shortcode( 'streamlinecore-browse-results', array( &$ResortPro, 'browse_results' ) );
add_shortcode( 'streamlinecore-search-filter', array( &$ResortPro, 'search_filter' ) );
add_shortcode( 'streamlinecore-search-results', array( &$ResortPro, 'search_results' ) );
add_shortcode( 'streamlinecore-checkout', array( &$ResortPro, 'property_checkout' ) );
add_shortcode( 'streamlinecore-property-info', array( &$ResortPro, 'property_info' ) );
add_shortcode( 'streamlinecore-thankyou', array( &$ResortPro, 'property_thankyou' ) );
add_shortcode( 'streamlinecore-featured-properties', array( &$ResortPro, 'featured_properties' ) );
add_shortcode( 'streamlinecore-testimonials', array( &$ResortPro, 'testimonials' ) );
add_shortcode( 'streamlinecore-quote', array( &$ResortPro, 'custom_quote' ) );
add_shortcode( 'streamlinecore-map', array(&$ResortPro, 'googlemap'));
add_shortcode( 'streamlinecore-terms', array(&$ResortPro, 'terms_and_conditions'));
add_shortcode( 'streamlinecore-favorites', array(&$ResortPro, 'favorites'));

// backwards compatibility for version prior to 2.0
add_shortcode('resortpro-browse-results', array(&$ResortPro, 'browse_results'));
add_shortcode('resortpro-search-filter', array(&$ResortPro, 'search_filter'));
add_shortcode('resortpro-search-results', array(&$ResortPro, 'search_results'));
add_shortcode('resortpro-checkout', array(&$ResortPro, 'property_checkout'));
add_shortcode('resortpro-property-info', array(&$ResortPro, 'property_info'));
add_shortcode('resortpro-thankyou', array(&$ResortPro, 'property_thankyou'));
add_shortcode('resortpro-featured-properties', array( &$ResortPro, 'featured_properties' ) );
add_shortcode('resortpro-testimonials', array(&$ResortPro, 'testimonials'));
add_shortcode('resortpro-map', array(&$ResortPro, 'googlemap'));

function streamline_init(){

	prefix_property_rewrite_rule();
	custom_rewrite_tag();

	require_once('includes/template-hooks.php');

}

function prefix_property_rewrite_rule() {

    $page_id = get_option('resortpro_listing_detail_page_id');

    $prepend_property_term = StreamlineCore_Settings::get_options( 'prepend_property_page' );

    if(!empty($prepend_property_term)){
        add_rewrite_rule('^'.$prepend_property_term.'/([^/]*)/?','index.php?page_id='.$page_id.'&property=$matches[1]','top');
    }
}

function prefix_register_query_var( $vars ) {

    return $vars;
}

function custom_rewrite_tag() {

    add_rewrite_tag('%property%', '([^&]+)');
}

add_action( 'init', 'streamline_init' );
add_action( 'template_redirect', array(&$ResortPro, 'prefix_url_rewrite_templates'));
add_filter( 'query_vars', 'prefix_register_query_var' );
