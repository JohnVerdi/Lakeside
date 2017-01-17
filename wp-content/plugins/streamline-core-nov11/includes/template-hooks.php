<?php
add_action( 'streamline-insert-calendar', 'streamline_add_calendar_module',10, 1);

function streamline_add_calendar_module($unit_id){
	$template = ResortPro()->locate_template("availability-calendar.php");
	if ( empty( $template ) ) {
      $template = trailingslashit( ResortPro()->dir ) . 'includes/templates/availability-calendar.php';
    }	
	include( $template);	
}

add_action( 'streamline-insert-booknow', 'streamline_add_booknow_module',11, 7);

function streamline_add_booknow_module($location_name, $unit_id, $max_adults, $max_guests, $max_pets, $min_stay, $checkin_days){
	$template = ResortPro()->locate_template("booknow-modal-ang.php");
	if ( empty( $template ) ) {
      $template = trailingslashit( ResortPro()->dir ) . 'includes/templates/booknow-modal-ang.php';
    }	
	include( $template);	
}

add_action( 'streamline-insert-inquiry', 'streamline_add_inquiry_module',10, 13);

function streamline_add_inquiry_module($location_name, $unit_id, $max_adults, $max_guests, $max_pets, $min_stay, $checkin_days, $is_modal, $start_date, $end_date, $occupants, $occupants_small, $pets){
	$template = ResortPro()->locate_template("property-inquiry.php");
	if ( empty( $template ) ) {
      $template = trailingslashit( ResortPro()->dir ) . 'includes/templates/property-inquiry.php';
    }	
	include( $template);	
}

if(StreamlineCore_Settings::get_options( 'enable_share_with_friends' ) == '1'){
	add_action( 'streamline-insert-share', 'streamline_add_share_module',11, 7);
}

function streamline_add_share_module($location_name, $unit_id, $start_date, $end_date, $occupants, $occupants_small, $pets){
	$template = ResortPro()->locate_template("share-with-friends.php");
	if ( empty( $template ) ) {
      $template = trailingslashit( ResortPro()->dir ) . 'includes/templates/share-with-friends.php';
    }	
	include( $template);	
}