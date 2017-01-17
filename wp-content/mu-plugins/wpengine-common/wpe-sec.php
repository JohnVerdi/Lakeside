<?php

add_action( 'wp_login_failed', 'wpesec_on_login_failed' );

function wpesec_on_login_failed( $username ) {
	// $username is already sanitize_user'ed
	$addr = $_SERVER['REMOTE_ADDR'];
	$prefix = 'wpe_rate_limits_login_failed_' . floor( time() / 300 );

	$bump = array(
		"${prefix}_addr_$addr",
		"${prefix}_user_$username",
		"${prefix}_global",
	);

	foreach ( $bump as $key ) {
		// wp_cache_incr does not support setting expiry
		// or default value even though memcached does
		// so we atomic add the key first every time :/
		// expiry time is just a touch longer than the window
		if ( !wp_cache_add( $key, 1, '', 400 ) ) {
			wp_cache_incr( $key );
		}
	}
}

add_action( 'login_form_login', 'wpesec_on_before_login' );

function wpesec_on_before_login() {
	if ( 'GET' === $_SERVER['REQUEST_METHOD'] ) {
		return;
	}

	// need to sanitize to match failed login value
	$username = sanitize_user( $_POST['log'] );
	$addr = $_SERVER['REMOTE_ADDR'];
	$prefix = 'wpe_rate_limits_login_failed_' . floor( time() / 300 );

	$check = array(
		"${prefix}_addr_$addr" => 1000,
		"${prefix}_user_$username" => 20,
		"${prefix}_global" => 10000,
	);

	foreach ( $check as $key => $limit ) {
		$state = wp_cache_get( $key );
		if ( $state > $limit ) {
			error_log( "enforcing rate limit [$key]" );
			header( 'Rate-Limit: login', false, 503 );
			// change this to readfile(__DIR__ . '/ratelimit.html')
			// whenever someone wants to put together a pretty page
			echo '<h1>RATE LIMIT EXCEEDED', PHP_EOL;
			die;
		}
	}
}

//add_action( 'init', 'wpesec_encourage_tls' );

function wpesec_encourage_tls() {
	if ( force_ssl_admin() ) {
		return;
	}
	if ( PWP_NAME . '.wpengine.com' !== $_SERVER['HTTP_HOST'] ) {
		return;
	}

	// there is a cert available for the temporary domain, use it
	force_ssl_admin( true );
}
