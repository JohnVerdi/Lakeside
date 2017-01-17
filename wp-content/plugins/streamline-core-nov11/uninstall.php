<?php

/**
 *
 * This file runs when the plugin in uninstalled (deleted).
 * This will not run when the plugin is deactivated.
 * Ideally you will add all your clean-up scripts here
 * that will clean-up unused meta, options, etc. in the database.
 *
 */

// If plugin is not being uninstalled, exit (do nothing)
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option("resortpro_listings_page_title");
delete_option("resortpro_listings_page_name");
delete_option("resortpro_listings_page_id");

delete_option("resortpro_listings_detail_page_title");
delete_option("resortpro_listings_detail_page_name");
delete_option("resortpro_listings_detail_page_id");

delete_option("resortpro_checkout_page_title");
delete_option("resortpro_checkout_page_name");
delete_option("resortpro_checkout_page_id");

delete_option("resortpro_thank_you_page_title");
delete_option("resortpro_thank_you_page_name");
delete_option("resortpro_thank_you_page_id");

delete_option("resortpro_custom_quote_page_title");
delete_option("resortpro_custom_quote_page_name");
delete_option("resortpro_custom_quote_page_id");

delete_option("wpt_resortpro_api_nonce");
delete_option('streamline_skip_amenities');

delete_option('resortpro_version');
delete_option('streamlinecore_version');
delete_option('pmt_plugin_resortpro');
delete_option('wpt_resortpro_api_endpoint');
delete_option('wpt_resortpro_api_nonce');

global $wpdb;
$table_name = $wpdb->prefix . 'resortpro_testdata';
$sql = "DROP TABLE  IF EXISTS $table_name";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );