<?php
# Database Configuration
define( 'DB_NAME', 'wp_simplesample' );
define( 'DB_USER', 'simplesample' );
define( 'DB_PASSWORD', 'B8W2AkprJYaFDewxx2vv' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         '5|eZ=Wzfw|4^~,!gQ/m!3rbRU&:ExalR1[@Wc#>}4gh3:M[{RNF)JtL}G|, 51%+');
define('SECURE_AUTH_KEY',  '&dMz,y[cQg^ESzJ+tmy+jeqstd/k]L=}}F6Xt$wqaHvRW_tM-+=f`.V+Oxqu2+bY');
define('LOGGED_IN_KEY',    '>t.5j2pdsoZ^SlEaj-[BRENMAz2Fu*miAG|15-|YK[FUq|g>[gvC/(wW]-VHl}t@');
define('NONCE_KEY',        '^ovXd!zXZeVmk9.O2i8~$G~N!RH+E:TNHpe`(Rg1A@9baSUhaw2vFu$^z#F<`?Up');
define('AUTH_SALT',        'Y~0aw~wv1g(j#Y}R~_aKYP9q[Sv8>-;FR?}82WIX0sb}o oiQw?s!DnnmJE=*+k8');
define('SECURE_AUTH_SALT', '{91+bC!L1d%<bc+KztT##Jha@+y.TA]( Rd,F;PER[=[C3d BRx<xwYM?V9>A<8z');
define('LOGGED_IN_SALT',   '^xG]SK&Md-i+w_4b.^z l+m0_0HWcpWGHTzjye3iqwaY:#8`<Lt[bI=!|k*=(l+]');
define('NONCE_SALT',       'CBkG0_!^|y=!-7fRNZQA=G/<cFO%QE{1Hh;B/s7Z/Nu-NW?(yC| -I-/ RulOdZ|');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'simplesample' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '5162e864efbc18e31cd2142a1eb8cab8d359dd11' );

define( 'WPE_CLUSTER_ID', '112544' );

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

$wpe_all_domains=array ( 0 => 'simplesample.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-112544', );

$wpe_special_ips=array ( 0 => '104.196.6.170', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings






# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
