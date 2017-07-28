<?php
# Database Configuration
define( 'DB_NAME', 'database_name_here' );
define( 'DB_USER', 'username_here' );
define( 'DB_PASSWORD', 'password_here' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         '^^]019Guz>8XV_@r^zwy0Ti7*^sgFiq|2u]+_Qq<Ki<4K/3kGvb-N[1XXL:V!}M^');
define('SECURE_AUTH_KEY',  '+G4`i,y(-Z>PAGxISD-}Gr#<kK4^5yX:|Au>b#G%l}xdU pj-L(+JZR@iB?=~`[k');
define('LOGGED_IN_KEY',    '`SE|y;z74;7:M(kq~3&m+`8@%w#xI?*Z=g0Pey|SGLYA q85V(u~t-WEFO^M}2}7');
define('NONCE_KEY',        '+^!KztopkL^9oOiIGheC-s!vVkfvA2XG}Z:bVl(h|c[> C~T5Car$qY:>kw+$>xE');
define('AUTH_SALT',        '|{jV824`P ([Fq1V9f2S}^Emp}|P|Hvj]Aflc7nyR+qTnhLk_=Lk4=05p>^w7Go;');
define('SECURE_AUTH_SALT', 'W+tO?-v|1 ;ae&3Lobe 5KNghC_kw=Hf1a/dvBhKx];.NDv0y[cHy`yFX/^!~wgm');
define('LOGGED_IN_SALT',   '@H)|aCH9+w)JDH4l/6`RKsv!]$1.h]A_h 6y<W4d4/-*3FeSzHANQrrJRjh2L|!k');
define('NONCE_SALT',       '0;)_QZ=6f(Qd&/vY(Zr2_B`Zl]D?A]D>zMe|&1%n,E#;.ykz`<hTBB@FVg_4yl%t');


# Localized Language Stuff

define( 'WP_CACHE', false );
define( 'WP_AUTO_UPDATE_CORE', false );
define( 'PWP_NAME', 'primbsdev' );
define( 'FS_METHOD', 'direct' );
define( 'FS_CHMOD_DIR', 0775 );
define( 'FS_CHMOD_FILE', 0664 );
define( 'PWP_ROOT_DIR', '/nas/wp' );
define( 'WPE_APIKEY', '95b125c434217b4a717e756504ce54df2a801209' );
define( 'WPE_CLUSTER_ID', '34709' );
define( 'WPE_CLUSTER_TYPE', 'pod' );
define( 'WPE_ISP', true );
define( 'WPE_BPOD', false );
define( 'WPE_RO_FILESYSTEM', false );
define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );
define( 'WPE_SFTP_PORT', 2222 );
define( 'WPE_LBMASTER_IP', '' );
define( 'WPE_CDN_DISABLE_ALLOWED', true );
define( 'DISALLOW_FILE_MODS', FALSE );
define( 'DISALLOW_FILE_EDIT', FALSE );
define( 'DISABLE_WP_CRON', false );
define( 'WPE_FORCE_SSL_LOGIN', false );
define( 'FORCE_SSL_LOGIN', false );
/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/
define( 'WPE_EXTERNAL_URL', false );
define( 'WP_POST_REVISIONS', FALSE );
define( 'WPE_WHITELABEL', 'wpengine' );
define( 'WP_TURN_OFF_ADMIN_BAR', false );
define( 'WPE_BETA_TESTER', false );
umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'demo.lakeside.dev5.sibers.com', );

$wpe_varnish_servers=array ( 0 => 'pod-34709', );

$wpe_special_ips=array ( 0 => '127.0.0.1', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );


# WP Engine ID

# WP Engine Settings
define('WPCACHEHOME', '/nas/content/live/primbsdev/wp-content/plugins/wp-super-cache/');
define('WP_MEMORY_LIMIT', '64M');
define('WP_DEBUG', false);
define( 'SCRIPT_DEBUG', true );

# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}

// DB
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'].'');
define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST'].'');